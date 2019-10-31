<?php

namespace App\Repositories;

use App\Repositories\ImageRepository;
use DB;
use Auth;
use Session;
use Excel;

class ReportsRepository
{

    /**
     * @var App\Models\User
     */
    protected $db_pbx_cdr;


    public function __construct()
    {

    }

    public function QueueReport($inputs)
    {
        $queue = Auth::User()->queue()->Pluck("queue")->ToArray();
        $queue[] = "00000";


        $hourFrom = isset($inputs['hourFrom']) ? $inputs['hourFrom'] : '00';
        $hourTo = isset($inputs['hourTo']) ? $inputs['hourTo'] : '23';
        $minFrom = isset($inputs['minFrom']) ? $inputs['minFrom'] : '00';
        $minTo = isset($inputs['minTo']) ? $inputs['minTo'] : '59';

        $start = (isset($inputs['dateFrom']) ? $inputs['dateFrom'] . " $hourFrom:$minFrom:00" : date("Y-m-d") . " $hourFrom:$minFrom:00");
        $end = (isset($inputs['dateTo']) ? $inputs['dateTo'] . " $hourTo:$minTo:00" : date("Y-m-d") . " $hourTo:$minTo:00");
        $json = array();
        if (strtotime(date('H:i', strtotime($start))) > strtotime(date('H:i', strtotime($end)))) {
            $timeWhere = " DATE_FORMAT(created,'%H:%i') between   DATE_FORMAT('$start', '%H:%i')  and   '23:59'
            OR DATE_FORMAT(created,'%H:%i') between   '00:00'  and   DATE_FORMAT('$end', '%H:%i')
            ";
        } else {

            $timeWhere = " DATE_FORMAT(created,'%H:%i') between   DATE_FORMAT('$start', '%H:%i')  and   DATE_FORMAT('$end', '%H:%i')";

        }


        $query = "select queue, sum(CASE verb When 'ENTERQUEUE' Then 1 else 0 End) as Received ,";
        $query .= "sum(CASE verb When 'CONNECT' Then 1 else 0 End) as Answered , ";
        $query .= "         sum(CASE verb When 'ABANDON' Then 1 else 0 End) as Abandoned FROM queue_log ";
        $query .= " where queue IN (" . implode(',', $queue) . ") and  verb IN ('ENTERQUEUE', 'ABANDON', 'CONNECT')";

        $query .= isset($inputs['queue']) ? " and queue = '" . $inputs['queue'] . "'" : "";

        $query .= " and created between '" . $start . "' and '" . $end . "' and $timeWhere";
        $query .= " group by queue";

        $Result = DB::connection('mysql2')->select($query);
        return $Result;
    }

    public function QueueReportByStatus($req)
    {
        $query = "";
        $groupby = "";

        if (isset($req['queryby'])) {

            if (isset($req['queryby']) and $req['queryby'] == 'month') {
                $start = $req['year'] . "-01-01";
                $end = $req['year'] . "-12-31";
                $groupby = "Group by  year(created), Month(created)";
                $query = "select created, MONTHNAME(STR_TO_DATE(MONTH(created), '%m')) as month,
                  sum(CASE verb When 'ENTERQUEUE' Then 1 else 0 End) as Received , 
                  sum(CASE verb When 'CONNECT' Then 1 else 0 End) as Answered , 
                  sum(CASE verb When 'ABANDON' Then 1 else 0 End) as Abandoned 
                  FROM queue_log where queue = '" . $req['queue'] . "' and verb IN ('ENTERQUEUE', 'ABANDON', 'CONNECT')
                  and created between '" . $start . "' and '" . $end . "' " . $groupby;

            } else if (isset($req['queryby']) and $req['queryby'] == 'week') {
                $time = explode("-", $req['daterange']);
                $start = date("Y-m-d", strtotime($time[0])) . " 00:00";
                $end = date("Y-m-d", strtotime($time[1])) . " 23:59";
                $groupby = "Group by Month(created), Week(created)";

                $query = "select concat(
                  STR_TO_DATE(concat(year(created), \" \" , week(created), ' sunday'), '%X %V %W'), ' <> ', 
                  STR_TO_DATE(concat(year(created), \" \" , week(created), ' saturday'), '%X %V %W')) AS week, 
                  sum(CASE verb When 'ENTERQUEUE' Then 1 else 0 End) as Received , 
                  sum(CASE verb When 'CONNECT' Then 1 else 0 End) as Answered , 
                  sum(CASE verb When 'ABANDON' Then 1 else 0 End) as Abandoned 
                  FROM queue_log where queue = '" . $req['queue'] . "' and verb IN ('ENTERQUEUE', 'ABANDON', 'CONNECT')
                  and created between '" . $start . "' and '" . $end . "' " . $groupby;

            } else if (isset($req['queryby']) and $req['queryby'] == 'day') {
                $time = explode("-", $req['daterange']);
                $start = date("Y-m-d", strtotime($time[0])) . " 00:00";
                $end = date("Y-m-d", strtotime($time[1])) . " 23:59";
                $groupby = "Group by  day";

                $query = "select date(created) as day,
                  sum(CASE verb When 'ENTERQUEUE' Then 1 else 0 End) as Received , 
                  sum(CASE verb When 'CONNECT' Then 1 else 0 End) as Answered , 
                  sum(CASE verb When 'ABANDON' Then 1 else 0 End) as Abandoned 
                  FROM queue_log where queue = '" . $req['queue'] . "' and verb IN ('ENTERQUEUE', 'ABANDON', 'CONNECT')
                  and created between '" . $start . "' and '" . $end . "' " . $groupby;

            } else if (isset($req['queryby']) and $req['queryby'] == 'hour') {
                $start = date("Y-m-d", strtotime($req['daterange'])) . " 00:00";
                $end = date("Y-m-d", strtotime($req['daterange'])) . " 23:59";
                $groupby = "group by  hour";

                $query = "select Hour(created) as hour,
                  sum(CASE verb When 'ENTERQUEUE' Then 1 else 0 End) as Received , 
                  sum(CASE verb When 'CONNECT' Then 1 else 0 End) as Answered , 
                  sum(CASE verb When 'ABANDON' Then 1 else 0 End) as Abandoned 
                  FROM queue_log where queue = '" . $req['queue'] . "' and verb IN ('ENTERQUEUE', 'ABANDON', 'CONNECT')
                  and created between '" . $start . "' and '" . $end . "' " . $groupby;


            } else if (isset($req['queryby']) and $req['queryby'] == 'minute') {
                $time = explode("-", $req['timepicker']);
                $start = date("Y-m-d", strtotime($req['daterange'])) . " " . $time[0];
                $end = date("Y-m-d", strtotime($req['daterange'])) . " " . $time[1];
                $groupby = "GROUP BY minute";
                $query = "select  DATE_FORMAT(created,'%H:%i') minute,
                  sum(CASE verb When 'ENTERQUEUE' Then 1 else 0 End) as Received , 
                  sum(CASE verb When 'CONNECT' Then 1 else 0 End) as Answered , 
                  sum(CASE verb When 'ABANDON' Then 1 else 0 End) as Abandoned 
                  FROM queue_log where queue = '" . $req['queue'] . "' and verb IN ('ENTERQUEUE', 'ABANDON', 'CONNECT')
                  and created between '" . $start . "' and '" . $end . "' " . $groupby;

            }


            //return $query;
        }


        $Result = DB::connection('mysql2')->select($query);

        return array($Result, array('queryby' => $req['queryby']));


    }

    public function DashboardReport($start = null, $end = null)
    {
        $start = isset($start) ? $start : date("Y-m-d") . " 00:00:00";
        $end = isset($end) ? $end : date("Y-m-d") . " 23:59:59";
        $json = array();


        $channel = "TRIM(REPLACE(SUBSTRING(channel,1,LOCATE(\"-\",channel,LENGTH(channel)-8)-1),\"SIP/\",\"\"))";
        $userExtention = implode(',', Auth::User()->Extension()->Pluck("extension_no")->ToArray());

        $userDid = $this->dids($userExtention);//Auth::User()->did_no;
        $userExtention .= (($userExtention != "" and $userDid != "") ? "," : "") . $userDid;


        /* Combinde Hourly Report */
        $where = "((src in ($userExtention) AND Length(dst)>4) OR ( dst in ($userExtention) /*and src not in ($userExtention)*/) )";
        $where = $where . " and calldate between '" . $start . "' and '" . $end . "'";
        $Result = DB::connection('mysql3')
            ->table('cdr')
            ->leftjoin('asterisk.users', function ($join) {
                $join->on('cdr.cnum', '=', 'asterisk.users.extension')
                    ->orOn('cdr.dst', '=', 'asterisk.users.extension');

            })
            ->select(DB::raw("DATE_FORMAT(calldate, '%Y-%m-%d %H:00') Createdhour,
                              count(*) as Total, 
                              IFNULL(sum(case when dst in ($userExtention) then 1 end),0) as Inbound, 
                              IFNULL(sum(case when src in ($userExtention) AND Length(dst)>4 then 1 end),0) as Outbound, 
                              sum(case when billsec>0 then 1 else 0 end) as Completed, 
                              sum(case when billsec=0 then 1 else 0 end) as Missed, sum(billsec) as Duration"))
            ->whereRaw($where)
            ->groupby(DB::raw("DATE_FORMAT(calldate, '%Y-%m-%d %H:00')"))->Get();


        $json['Hrs'] = $Result;

//        $query = "select DATE_FORMAT(created, '%Y-%m-%d %H:00') as Createdhour, count(*) as Inbound
//                  from queue_log where verb in ('ENTERQUEUE')
//                  and created between '" . $start . " 00:00:00' and '" . $end . " 23:59:59'
//                  group by DATE_FORMAT(created, '%Y-%m-%d %H:00')";
//        $Result = DB::connection('mysql2')->select($query);
        $json['HrsIB'] = $Result;

        //OB stats

        //$userExtention = implode(',', Auth::User()->Extension()->Pluck("extension_no")->ToArray());
        $where = "(src in ($userExtention)  )";
        $where = $where . " and calldate between '" . $start . "' and '" . $end . "'";
        $sql = "count(*) as Total, 
                IFNULL(sum(case when dst in ($userExtention) then 1 end),0) as Inbound, 
                IFNULL(sum(case when src in ($userExtention) then 1 end),0) as Outbound, 
                sum(case when billsec>0 then 1 else 0 end) as Completed, 
                sum(case when billsec=0 then 1 else 0 end) as Missed, 
                sum(billsec) as Duration, sum(billsec) as Billing";
//        echo "Select $sql from cdr where ". $where;
//        exit();

        $Result = DB::connection('mysql3')->table('cdr')->select(DB::raw($sql))
            ->whereRaw($where)->Get();
        $json['OBTotalTime'] = 0;
        $json['OBAnswer'] = 0;
        $json['OBUnanswer'] = 0;
        $json['OBDuration'] = 0;

        foreach ($Result as $row) {
            $json['OBTotalTime'] = isset($row->Total) ? $row->Total : 0;
            $json['OBAnswer'] = isset($row->Completed) ? $row->Completed : 0;
            $json['OBUnanswer'] = isset($row->Missed) ? $row->Missed : 0;
            $json['OBDuration'] = isset($row->Duration) ? $row->Duration : 0;
        }


        $queue = implode(',', Auth::User()->queue()->Pluck("queue", "queue")->ToArray());

        $query = "select count(*) as answer from queue_log 
                    where verb in ('CONNECT') and created between '" . $start . "' and '" . $end . "'";
        $query .= (isset($queue) and $queue != "") ? " and queue IN ($queue)" : "";

        $query .= " and call_id not in 
                    (select call_id from queue_log where verb in ('COMPLETEAGENT', 'COMPLETECALLER','blindtransfer')";
        $query .= (isset($queue) and $queue != "") ? " and queue IN ($queue)" : "";
        $query .= "  and created between '" . $start . "' and '" . $end . "')";


        $Result = DB::connection('mysql2')->select($query);
        foreach ($Result as $row) {
            $json['Received'] = $row->answer;
        }

        $query = "select  count( if(verb in ('exitwithtimeout','abandon'),1,NULL)) as abandon,
                    count(if(verb='connect',1,NULL)) as answered,
                    count(if(verb='enterqueue',1,NULL)) as totalcalls,
                    Ceiling(count(if(verb='connect',1,NULL))*100/count( if(verb='enterqueue',1,NULL))) as answeravg, 
                    Ceiling(count( if(verb='abandon',1,NULL))*100/count( if(verb='enterqueue',1,NULL))) as abandonavg
                    from queue_log where 
                    verb in ('connect','abandon','ENTERQUEUE','exitwithtimeout') 
                    and created between '" . $start . "' and '" . $end . "'";
        $query .= (isset($queue) and $queue != "") ? " and queue IN ($queue)" : "";

        $Result = DB::connection('mysql2')->select($query);
        foreach ($Result as $row) {
            $json['TotalCalls'] = $row->totalcalls;
            $json['Abandoned'] = (isset($row->abandon) ? $row->abandon : 0);
            $json['Answered'] = (isset($row->answered) ? $row->answered : 0);
            $json['Holdtime'] = strtotime(isset($row->holdtime) ? $row->holdtime : 0);

        }


        $query = "select ROUND(sum(data1)) AS waittime from queue_log where verb='CONNECT' 
                  and  created between '" . $start . "' and '" . $end . "'";
        $query .= (isset($queue) and $queue != "") ? " and queue IN ($queue)" : "";

        $Result = DB::connection('mysql2')->select($query);
        foreach ($Result as $row) {
            $seconds = $row->waittime;
            $parse = gmdate("H:i:s", $seconds);
            $json['WaitTime'] = $row->waittime;
        }

        $query = "select ROUND(sum(data2)) AS talktime from queue_log where verb='COMPLETEAGENT' 
                  and created between '" . $start . "' and '" . $end . "'";
        $query .= (isset($queue) and $queue != "") ? " and queue IN ($queue)" : "";

        $Result = DB::connection('mysql2')->select($query);
        foreach ($Result as $row) {
            $talktime = $row->talktime;
            $json['TalkTime'] = $row->talktime;
        }

        $query = "select sum(data2) AS totaltime from queue_log where verb in ('COMPLETECALLER', 'COMPLETEAGENT') 
                  and  created between '" . $start . "' and '" . $end . "'";
        $query .= (isset($queue) and $queue != "") ? " and queue IN ($queue)" : "";

        $Result = DB::connection('mysql2')->select($query);
        foreach ($Result as $row) {
            $totaltime = $row->totaltime;
            $json['TotalTime'] = isset($row->totaltime) ? $row->totaltime : 0;
        }

        $query = "select count(*) as waiting from queue_log 
                    where verb in ('ENTERQUEUE') and created between '" . $start . "' and '" . $end . "' 
                    and call_id not in ( select call_id from queue_log where verb in ('CONNECT', 'ABANDON','exitwithtimeout') 
                    and created between '" . $start . "' and '" . $end . "')";
        $query .= (isset($queue) and $queue != "") ? " and queue IN ($queue)" : "";

        $Result = DB::connection('mysql2')->select($query);
        foreach ($Result as $row) {
            $json['Waiting'] = isset($row->waiting) ? $row->waiting : 0;
        }
        return $json;
    }

    public function downloadCallReport($type, $data)
    {

        $data = json_decode(json_encode($data), True);
        return Excel::create('crd_data', function ($excel) use ($data) {
            $excel->sheet('mySheet', function ($sheet) use ($data) {
                $sheet->fromArray($data);
            });
        })->download();

    }

    public function ioUserReport($inputs)
    {

        //$where = "TRIM( SUBSTRING_INDEX(SUBSTRING_INDEX(clid,'>',1),'<',-1)) in (".implode(',',Auth::User()->Extension()->Pluck("extension_no")->ToArray()).") ";

        //$channel = "TRIM(REPLACE(SUBSTRING(channel,1,LOCATE(\"-\",channel,LENGTH(channel)-8)-1),\"SIP/\",\"\"))";

        $ext = $userExtention = implode(',', Auth::User()->Extension()->Pluck("extension_no")->ToArray());
        if (isset($inputs['calling_from']) != '') {
            $userExtention = $inputs['calling_from'];
        }
        $userDid = $this->dids($userExtention);//Auth::User()->did_no;
        $userExtention .= (($userExtention != "" and $userDid != "") ? "," : "") . $userDid;

        $where = "
        ( 
            (
                cdr.src in ($userExtention) AND Length(cdr.dst)>4
            ) 
        OR 
            (
                cdr.dst in ($userExtention) and cdr.dst=extension
            ) 
        )
        ";

        if (isset($inputs['calling_from']) != '') {
            $calling_from = $inputs['calling_from'];
            $where = $where . " and asterisk.users.extension in (" . $calling_from . ")";
        }
        else {
            $where = $where . " and asterisk.users.extension in (" . $ext . ")";
        }


        $dateFrom = (isset($inputs['dateFrom']) ? $inputs['dateFrom'] : date("Y-m-d"));
        $dateTo = (isset($inputs['dateTo']) ? $inputs['dateTo'] : date("Y-m-d"));
        Session::put('dateFrom', $dateFrom);
        Session::put('dateTo', $dateTo);
        $where = $where . " and calldate between '" . $dateFrom . " 00:00:00' and '" . $dateTo . " 23:59:59'";

        if (isset($inputs['type']) and $inputs['type'] != "") {
            $sql_select = "
                extension as User,
                count(extension) as Total,
                IFNULL(sum(case when dst in ($userExtention) then 1 end),0) as Inbound,
                IFNULL(sum(case when src in ($userExtention) AND Length(dst)>4 then 1 end),0) as Outbound,
                sum(case when billsec>0 then 1 else 0 end) as Answered, 
                sum(case when billsec=0 then 1 else 0 end) as Unanswered,
                sum(billsec) as Duration, 
                SEC_TO_TIME(sum(billsec)/count(extension)) as \"Avg Duration\"
                ";
            //  DB::connection('mysql3')->enableQueryLog(); // Enable query log
            //User	Total	Incoming	Outgoing	Answered	Unanswered	Duration	Avg Duration

            $data =   DB::connection('mysql3')->table('cdr')
                ->leftjoin('asterisk.users', function ($join) {
                    $join->on('cdr.dst', '=', 'asterisk.users.extension');
                    // ->orOn('cdr.dst', '=', 'asterisk.users.extension');

                })
                ->select(DB::raw(
                    $sql_select
                ))
                ->whereRaw($where)
                ->orderby("extension")
                ->groupby("extension")
                ->get();

            $this->downloadCallReport($inputs['type'], $data);
        } else {

              $sql_select = "
                count(*) as Total, 
                IFNULL(sum(case when dst in ($userExtention) then 1 end),0) as Inbound,
                IFNULL(sum(case when src in ($userExtention) AND Length(dst)>4 then 1 end),0) as Outbound,
                sum(case when billsec>0 then 1 else 0 end) as Completed, 
                sum(case when billsec=0 then 1 else 0 end) as Missed,
                sum(billsec) as Duration, sum(billsec) as Billing,
                u1.extension,
                cnum as accountcode,
                cnum  as caller_id_number,
                cnam,cnum";

            return  DB::connection('mysql3')->table('cdr')
                ->leftjoin('asterisk.users as u1', function ($join) {
                    $join->on('cdr.dst', '=', 'u1.extension')
                        ->orOn('cdr.cnum', '=', 'u1.extension');
                })
                ->select(DB::raw(
                    $sql_select
                ))
                ->whereRaw($where)
                ->orderby("u1.extension")
                ->groupby("u1.extension")
                ->paginate(40);

            //dd(DB::connection('mysql3')->getQueryLog()); // Show results of log

            // exit();

        }
    }

    public function ioCallReport($inputs)
    {

        $dispo= $direction = $calling_from = $dialed_number = "";


        $channel = "TRIM(REPLACE(SUBSTRING(channel,1,LOCATE(\"-\",channel,LENGTH(channel)-8)-1),\"SIP/\",\"\"))";
        $dstchannel = "TRIM(REPLACE(SUBSTRING(dstchannel,1,LOCATE(\"-\",dstchannel,LENGTH(channel)-8)-1),\"SIP/\",\"\"))";
        $dstExtension = implode(',', Auth::User()->Extension()->Pluck("extension_no")->ToArray());
        $srcExtension = implode(',', Auth::User()->Extension()->Pluck("extension_no")->ToArray());
        $did = $this->dids($srcExtension);
        // $did = Auth::User()->did_no;

        if (isset($inputs['calling_from']) != '') {
            $calling_from=$inputs['calling_from'];
            // $srcExtension = $inputs['calling_from'];
        }

        if (isset($inputs['dialed_number']) != '') {
            $dialed_number=$inputs['dialed_number'];
            // $dstExtension = $inputs['dialed_number'];
        }

        if($did!="") {
            $srcExtension .= "," . $did;
            $dstExtension .= "," . $did;
        }

        if (isset($inputs['direction']) and $inputs['direction']== '0') {
            $direction = $inputs['direction'];
            $where = "(dst in ($dstExtension) )";
        }
        else if(isset($inputs['direction'])  and $inputs['direction']==1){
            $direction = $inputs['direction'];
            $where = "(src in ($srcExtension) )";
        }
        else {
            $where = "((src in ($srcExtension)) OR dst in ($dstExtension) )";
        }
        if (isset($inputs['calling_from']) != '') {
            $calling_from=$inputs['calling_from'];
            $where .= "and cnum  like '" . $calling_from. "%'";
            // $srcExtension = $inputs['calling_from'];
        }

        if (isset($inputs['dialed_number']) != '') {
            $dialed_number=$inputs['dialed_number'];
            $where.=" and (dst like '".$dialed_number."%' OR $dstchannel like '". $dialed_number."%')";
            // $dstExtension = $inputs['dialed_number'];
        }

        if (isset($inputs['dispo']) != '') {
            $dispo = $inputs['dispo'];
            if ($inputs['dispo'] == 0) {
                $disposition = "NO ANSWER";
            }
            if ($inputs['dispo'] == 1) {
                $disposition = "ANSWERED";
            }
            if ($inputs['dispo'] == 2) {
                $disposition = "BUSY";
            }
            if ($inputs['dispo'] == 3) {
                $disposition = "FAILED";
            }
            $where = $where . " and disposition='" . $disposition . "'";
        }
        if(request()->user()->can('outbound_idd') and isset($inputs['outbound_idd'])){
            $where = $where . " and LENGTH(dst) > 9";

        }
        $dateFrom = (isset($inputs['dateFrom']) ? $inputs['dateFrom'] : date("Y-m-d"));
        $dateTo = (isset($inputs['dateTo']) ? $inputs['dateTo'] : date("Y-m-d"));
        Session::put('dateFrom', $dateFrom);
        Session::put('dateTo', $dateTo);
        $where = $where . " and calldate between '" . $dateFrom . " 00:00:00' and '" . $dateTo . " 23:59:59'";
        $where = $where . " and cdr.uniqueid = cel.linkedid";

        if (isset($inputs['type']) and $inputs['type'] != "") {

            $sql_select = "
                DATE_FORMAT(max(calldate),'%d-%m-%Y %H:%i:%s') AS \"Call Date Time\",
               case 
                    when src in ($dstExtension) 
                        then cnum 
                    else 
                    src 
                end as \"From\",
                case 
                    when dst not in (select grpnum from asterisk.ringgroups)
                        then dst
                    else
                        $dstchannel
                    end  as \"To\",
                 case 
                    when group_concat(disposition,\";\") like \"%ANSWERED%\" 
                        then \"Answered\" 
                        else \"Abandoned\" 
                    end as Status,
                max(billsec) as billsec,
                (duration-billsec) as ringtime,
                case
                    when recordingfile!='' Then
                        recordingfile
                    else
                        \"No Recording\"
                 end as Recording,
                case when src in ($srcExtension) then 'Outbound' else 'Inbound' end as Direction,
                cnam AS CallerID";

            return  DB::connection('mysql3')->table('cdr')
                ->leftjoin('asterisk.users', function ($join) {
                    $join->on('cdr.cnum', '=', 'asterisk.users.extension')
                        ->orOn('cdr.dst', '=', 'asterisk.users.extension');

                })
                ->leftjoin('cel',function ($join){
                    $join->on("cel.linkedid","=",'cdr.uniqueid');
                })
                ->select(DB::raw(
                    $sql_select
                ))
                ->whereRaw($where)
                ->orderby("calldate")
                ->groupby("cel.linkedid")
                ->get();

            // $this->downloadCallReport($inputs['type'], $data);

        } else {
            $sql_select = "
                DATE_FORMAT(max(calldate),'%d-%m-%Y %H:%i:%s') AS calldate,
                case 
                    when src in ($dstExtension) 
                        then cnum 
                    else 
                    src 
                end as outbound_caller_id,
                case 
                    when dst not in (select grpnum from asterisk.ringgroups)
                        then dst
                    else
                        $dstchannel
                    end  as destination,
                 case 
                    when group_concat(disposition,\";\") like \"%ANSWERED%\" 
                        then \"Answered\" 
                        else \"Abandoned\" 
                    end as disposition,
                max(billsec) as billsec,
                (duration-billsec) as ringtime,
                case
                    when recordingfile!='' Then
                        recordingfile
                    else
                        \"No Data\"
                 end as Recording,
                case when src in ($srcExtension) then 'Outbound' else 'Inbound' end as Direction,
                cnam AS CallerID";
            // echo "<div style=display:none>$sql_select  where $where</div>";
            return  DB::connection('mysql3')->table('cdr')
                ->leftjoin('asterisk.users', function ($join) {
                    $join->on('cdr.cnum', '=', 'asterisk.users.extension')
                        ->orOn('cdr.dst', '=', 'asterisk.users.extension');

                })
                ->leftjoin('cel',function ($join){
                    $join->on("cel.linkedid","=",'cdr.uniqueid');
                })
                ->select(DB::raw(
                    $sql_select
                ))
                ->whereRaw($where)
                // ->orderby("calldate")
                ->groupby("cel.linkedid");
            //->get();
            // ->paginate(40)
            //->withPath('?dispo=' . $dispo . '&direction=' . $direction . '&dateFrom=' . $dateFrom . '&dateTo=' . $dateTo . '&calling_from=' . $calling_from . '&dialed_number=' . $dialed_number);
        }
    }

    public function iCallReport($inputs)
    {
        //$where = "TRIM(dst) in (".implode(',',Auth::User()->Extension()->Pluck("extension_no")->ToArray()).") ";

        $channel = "TRIM(REPLACE(SUBSTRING(channel,1,LOCATE(\"-\",channel,LENGTH(channel)-8)-1),\"SIP/\",\"\"))";

        $userExtention = implode(',', Auth::User()->Extension()->Pluck("extension_no")->ToArray());
        $userDid = $this->dids($userExtention);//Auth::User()->did_no;
        $userExtention .= (($userExtention != "" and $userDid != "") ? "," : "") . $userDid;

        $where = "";
        if(isset($inputs["agent"]))
            $where = "dst in (".$inputs["agent"].")";

        $dateFrom = (isset($inputs['dateFrom']) ? $inputs['dateFrom'] : date("Y-m-d"));
        $dateTo = (isset($inputs['dateTo']) ? $inputs['dateTo'] : date("Y-m-d"));
        Session::put('dateFrom', $dateFrom);
        Session::put('dateTo', $dateTo);
        $where = ($where!=""?$where." and ":"") . " calldate between '" . $dateFrom . " 00:00:00' and '" . $dateTo . " 23:59:59'";


        if (isset($inputs['type']) and $inputs['type'] != "") {
            $where = "";
            if(isset($inputs["calling_from"]))
                $where = "dst in (".$inputs["calling_from"].")";

            $dateFrom = (isset($inputs['dateFrom']) ? $inputs['dateFrom'] : date("Y-m-d"));
            $dateTo = (isset($inputs['dateTo']) ? $inputs['dateTo'] : date("Y-m-d"));
            Session::put('dateFrom', $dateFrom);
            Session::put('dateTo', $dateTo);
            $where = ($where!=""?$where." and ":"") . " calldate between '" . $dateFrom . " 00:00:00' and '" . $dateTo . " 23:59:59'";


            $data = DB::connection('mysql3')->table('cdr')
                ->leftjoin('asterisk.users', function ($join) {
                    $join->on('cdr.dst', '=', 'asterisk.users.extension');
                })
                ->select(DB::raw("
                DATE_FORMAT(calldate,'%d-%m-%Y %H:%i:%s') AS calldate,
                cnam, src as \"From\", 
                outboundcid as DID,
                dst AS destination,
                billsec,
                (duration-billsec) as ringtime, 
                case
                    when recordingfile!='' Then
                        recordingfile
                    else
                        \"No Data\"
                 end as Recording,
                disposition as \"Status\"
                "
                ))
                ->whereRaw($where)->get();

            $this->downloadCallReport($inputs['type'], $data);
        } else {
            return DB::connection('mysql3')->table('cdr')
                ->leftjoin('asterisk.users', function ($join) {
                    $join->on('cdr.dst', '=', 'asterisk.users.extension');
                })
                ->select(DB::raw("
                $channel as channelVal,
                DATE_FORMAT(max(calldate),'%d-%m-%Y %H:%i:%s') AS calldate,
                cnam, src,cnum, outboundcid as did,
                dst AS outbound_caller_id,
                dst AS destination,disposition,
                max(billsec) as billsec,
                (duration-billsec) as ringtime, 
                 case
                    when recordingfile!='' then recordingfile
                    else \"No Data\"  
                end As Recording, 
                case when dst in ($userExtention) then 'Inbound' else 'Outbound' end as Direction,
                 clid AS CallerID"
                ))
                ->whereRaw($where)
                ->groupby('uniqueid')
                ->get();
            //->paginate(10000)
            //->withPath('?dispo=' . $dispo . '&direction=' . $direction . '&dateFrom=' . $dateFrom . '&dateTo=' . $dateTo . '&calling_from=' . $calling_from . '&dialed_number=' . $dialed_number);
        }
    }

    public function oCallReport($inputs)
    {

        $channel = "TRIM(REPLACE(SUBSTRING(channel,1,LOCATE(\"-\",channel,LENGTH(channel)-8)-1),\"SIP/\",\"\"))";
        $userExtention = implode(',', Auth::User()->Extension()->Pluck("extension_no")->ToArray());

        if (isset($inputs['agent']) != '') {
            $userExtention = $inputs['agent'];
        }
        $userDid = $this->dids($userExtention);//Auth::User()->did_no;
        $userExtention .= (($userExtention != "" and $userDid != "") ? "," : "") . $userDid;

        $where = "src in ($userExtention) AND Length(dst)>4 ";

        $dateFrom = (isset($inputs['dateFrom']) ? $inputs['dateFrom'] : date("Y-m-d"));
        $dateTo = (isset($inputs['dateTo']) ? $inputs['dateTo'] : date("Y-m-d"));
        Session::put('dateFrom', $dateFrom);
        Session::put('dateTo', $dateTo);
        $where = $where . " and calldate between '" . $dateFrom . " 00:00:00' and '" . $dateTo . " 23:59:59'";

        if (isset($inputs['agent']) != '') {
            $calling_from = $inputs['agent'];
            $where = $where . " and extension = '" . $calling_from . "'";
        }


        if (isset($inputs['type']) and $inputs['type'] != "") {
            if (isset($inputs['calling_from']) != '') {
                $userExtention = $inputs['calling_from'];
            }
            $userDid = $this->dids($userExtention);// Auth::User()->did_no;
            $userExtention .= (($userExtention != "" and $userDid != "") ? "," : "") . $userDid;

            $where = "src in ($userExtention) AND Length(dst)>4 ";

            $dateFrom = (isset($inputs['dateFrom']) ? $inputs['dateFrom'] : date("Y-m-d"));
            $dateTo = (isset($inputs['dateTo']) ? $inputs['dateTo'] : date("Y-m-d"));
            Session::put('dateFrom', $dateFrom);
            Session::put('dateTo', $dateTo);
            $where = $where . " and calldate between '" . $dateFrom . " 00:00:00' and '" . $dateTo . " 23:59:59'";

            if (isset($inputs['calling_from']) != '') {
                $calling_from = $inputs['calling_from'];
                $where = $where . " and extension = '" . $calling_from . "'";
            }

            $sql_select = "
                DATE_FORMAT(calldate,'%d-%m-%Y %H:%i:%s') AS calldate,
                dst AS destination,
                extension as \"From\",                
                outboundcid as DID,
                billsec as \"Talk Time\", 
                (duration-billsec) as \"Ring Time\",
                case
                    when recordingfile!='' then recordingfile
                    else \"No Data\"  
                end As Recording, 
                disposition as Status
                ";

            $data = DB::connection('mysql3')->table('cdr')
                ->join('asterisk.users', function ($join) {
                    $join->on('extension', '=', 'cnum');
                })
                ->select(DB::raw(
                    $sql_select
                ))
                ->whereRaw($where)
                ->get();

            $this->downloadCallReport($inputs['type'], $data);
        } else {
            $sql_select = "
                $channel as channelVal,
                DATE_FORMAT(calldate,'%d-%m-%Y %H:%i:%s') AS calldate,
                dst AS destination,
                src,outboundcid,
                name,
                disposition,billsec, (duration-billsec) as ringtime,
                extension,
                case
                    when recordingfile!='' then recordingfile
                    else \"No Data\"  
                end As Recording,
                cnum as outbound_caller_id,
                case when dst in ($userExtention) then 'Inbound' else 'Outbound' end as Direction,
                cnam,cnum,
                clid AS CallerID
                ";

            return DB::connection('mysql3')->table('cdr')
                ->join('asterisk.users', function ($join) {
                    $join->on('extension', '=', 'cnum');
                })
                ->select(DB::raw(
                    $sql_select
                ))
                ->whereRaw($where)
                ->get();
            //->paginate(10000);
            /// ->withPath('?dateFrom=' . $dateFrom . '&dateTo=' . $dateTo . '&calling_from=' . $calling_from);


        }


    }

    public function iUserReport($inputs)
    {
        $channel = "TRIM(REPLACE(SUBSTRING(channel,1,LOCATE(\"-\",channel,LENGTH(channel)-8)-1),\"SIP/\",\"\"))";
        $userExtention = implode(',', Auth::User()->Extension()->Pluck("extension_no")->ToArray());// . ", " . Auth::User()->did_no;
        $userDid = $this->dids($userExtention);// Auth::User()->did_no;
        $userExtention .= (($userExtention != "" and $userDid != "") ? "," : "") . $userDid;

        $where = "cdr.dst in ($userExtention)";
        $calling_from = "";
        $dateFrom = (isset($inputs['dateFrom']) ? $inputs['dateFrom'] : date("Y-m-d"));
        $dateTo = (isset($inputs['dateTo']) ? $inputs['dateTo'] : date("Y-m-d"));
        Session::put('dateFrom', $dateFrom);
        Session::put('dateTo', $dateTo);
        $where = $where . " and calldate between '" . $dateFrom . " 00:00:00' and '" . $dateTo . " 23:59:59'";
        if (isset($inputs['calling_from']) != '') {
            $calling_from = $inputs['calling_from'];
            $where = $where . " and TRIM(cdr.dst)='" . $calling_from . "'";
        }





        $missed = "select count(distinct uniqueid) as Missed,dst from cdr where 
                calldate between '" . $dateFrom . " 00:00:00' and '" . $dateTo . " 23:59:59' and
                uniqueid not in (
                  select uniqueid from cdr where calldate between '" . $dateFrom . " 00:00:00' and '" . $dateTo . " 23:59:59' and disposition='ANSWERED'
                  ) and disposition in ('No Answer','busy','Failed')  group by dst
                  ";
        $answered = "select count(distinct uniqueid) as Completed, sum(billsec) as Billing, dst from cdr where 
                calldate between '" . $dateFrom . " 00:00:00' and '" . $dateTo . " 23:59:59' and 
                 disposition in ('Answered')  group by dst
                  ";

        $sql_select = "
                extension,cdr.dst,
                cnum as accountcode,
                cnum as caller_id_number,
                cnam,cnum,
                count(distinct uniqueid) as Total,  
                Completed,
                Missed,
                Duration, 
                Billing  
                ";

        return DB::connection('mysql3')->table('cdr')
            ->leftjoin(DB::raw("($missed) as cdr1"), function ($join) {
                $join->on('cdr.dst', '=', 'cdr1.dst');
            })
            ->leftjoin(DB::raw("($answered) as cdr2"), function ($join) {
                $join->on('cdr.dst', '=', 'cdr2.dst');
            })
            ->leftjoin('asterisk.users', function ($join) {
                $join->on('cdr.dst', '=', 'asterisk.users.extension');
            })
            ->select(DB::raw(
                $sql_select
            ))
            ->whereRaw($where)
            ->orderby("extension")
            ->groupby("extension")
            ->get();
    }

    public function oUserReport($inputs)
    {
        $channel = "TRIM(REPLACE(SUBSTRING(channel,1,LOCATE(\"-\",channel,LENGTH(channel)-8)-1),\"SIP/\",\"\"))";

        $userExtention = implode(',', Auth::User()->Extension()->Pluck("extension_no")->ToArray());
        if (isset($inputs['calling_from']) != '') {
            $userExtention = $inputs['calling_from'];
        }
        $userDid = $this->dids($userExtention);// Auth::User()->did_no;
        $userExtention .= (($userExtention != "" and $userDid != "") ? "," : "") . $userDid;

        $where = "src in ($userExtention) AND Length(dst)>4 ";

        $dateFrom = (isset($inputs['dateFrom']) ? $inputs['dateFrom'] : date("Y-m-d"));
        $dateTo = (isset($inputs['dateTo']) ? $inputs['dateTo'] : date("Y-m-d"));
        Session::put('dateFrom', $dateFrom);
        Session::put('dateTo', $dateTo);
        $where = $where . " and calldate between '" . $dateFrom . " 00:00:00' and '" . $dateTo . " 23:59:59'";

        if (isset($inputs['calling_from']) != '') {
            $calling_from = $inputs['calling_from'];
            $where = $where . " and extension = '" . $calling_from . "'";
        }


        $sql_select = "
                count(extension) as Total,
                IFNULL(sum(case when src in ($userExtention) AND Length(dst)>4 then 1 end),0) as Outbound,
                sum(case when billsec>0 then 1 else 0 end) as Completed,
                sum(case when billsec=0 then 1 else 0 end) as Missed,
                sum(billsec) as Duration, sum(billsec) as Billing,
                extension,
                cnum as accountcode,
                extension as caller_id_number,
                cnam,cnum
                ";
        return DB::connection('mysql3')->table('cdr')
            ->leftjoin('asterisk.users', function ($join) {
                $join->on('extension', '=', 'cnum');
            })
            ->select(DB::raw(
                $sql_select
            ))
            ->whereRaw($where)
            ->groupby("extension")
            ->paginate(40);
        // ->withPath('?dateFrom=' . $dateFrom . '&dateTo=' . $dateTo . '&calling_from=' . $calling_from);

    }

    public function internalDetailedReport($inputs)
    {
        //$where = "TRIM(dst) in (".implode(',',Auth::User()->Extension()->Pluck("extension_no")->ToArray()).") ";

        $channel = "TRIM(REPLACE(SUBSTRING(channel,1,LOCATE(\"-\",channel,LENGTH(channel)-8)-1),\"SIP/\",\"\"))";

        $userExtention =  implode(',', Auth::User()->Extension()->Pluck("extension_no")->ToArray());
        $userDid = $this->dids($userExtention);// Auth::User()->did_no;
        $userExtention .= (($userExtention != "" and $userDid != "") ? "," : "") . $userDid;

        $where = "";
        if(isset($inputs["agent"]))
            $where = "(
                       /*(
                            dst in (".$inputs["agent"] . ($userDid!=""? ",".$userDid:"") .")
                            and 
                            (src in ($userExtention) or cnum in ($userExtention))
                        )
                        OR*/
                        (
                            (src in (".$inputs["agent"].") or cnum in (".$inputs["agent"]."))
                            and dst in ($userExtention)
                         )
                         
                       )";

        $dateFrom = (isset($inputs['dateFrom']) ? $inputs['dateFrom'] : date("Y-m-d"));
        $dateTo = (isset($inputs['dateTo']) ? $inputs['dateTo'] : date("Y-m-d"));
        Session::put('dateFrom', $dateFrom);
        Session::put('dateTo', $dateTo);
        $where = ($where!=""?$where." and ":"") . " calldate between '" . $dateFrom . " 00:00:00' and '" . $dateTo . " 23:59:59'";


        if (isset($inputs['type']) and $inputs['type'] != "") {
            $where = "";
            if(isset($inputs["calling_from"]))
                $where = "dst in (".$inputs["calling_from"].")";

            $dateFrom = (isset($inputs['dateFrom']) ? $inputs['dateFrom'] : date("Y-m-d"));
            $dateTo = (isset($inputs['dateTo']) ? $inputs['dateTo'] : date("Y-m-d"));
            Session::put('dateFrom', $dateFrom);
            Session::put('dateTo', $dateTo);
            $where = ($where!=""?$where." and ":"") . " calldate between '" . $dateFrom . " 00:00:00' and '" . $dateTo . " 23:59:59'";


            $data = DB::connection('mysql3')->table('cdr')
                ->leftjoin('asterisk.users', function ($join) {
                    $join->on('cdr.dst', '=', 'asterisk.users.extension');
                })
                ->select(DB::raw("
                DATE_FORMAT(calldate,'%d-%m-%Y %H:%i:%s') AS calldate,
                cnam, src as \"From\", 
                outboundcid as DID,
                dst AS destination,
                billsec,
                (duration-billsec) as ringtime, 
                case
                    when recordingfile!='' Then
                        recordingfile
                    else
                        \"No Data\"
                 end as Recording,
                disposition as \"Status\"
                "
                ))
                ->whereRaw($where)->get();

            $this->downloadCallReport($inputs['type'], $data);
        } else {
            return DB::connection('mysql3')->table('cdr')
                ->leftjoin('asterisk.users', function ($join) {
                    $join->on('cdr.dst', '=', 'asterisk.users.extension')
                        ->orOn('cdr.src', '=', 'asterisk.users.extension')
                        ->orOn('cdr.cnum', '=', 'asterisk.users.extension');
                })
                ->select(DB::raw("
                $channel as channelVal,
                DATE_FORMAT(max(calldate),'%d-%m-%Y %H:%i:%s') AS calldate,
                cnam, src,cnum, outboundcid as did,
                dst AS outbound_caller_id,
                dst AS destination,
                disposition,
                max(billsec) as billsec,
                (duration-billsec) as ringtime, 
                 case
                    when recordingfile!='' then recordingfile
                    else \"No Data\"  
                end As Recording, 
                case when dst in ($userExtention) then 'Internal' else 'Outbound' end as Direction,
                 clid AS CallerID"
                ))
                ->whereRaw($where)
                ->groupby('uniqueid')
                ->get();
            //->paginate(10000)
            //->withPath('?dispo=' . $dispo . '&direction=' . $direction . '&dateFrom=' . $dateFrom . '&dateTo=' . $dateTo . '&calling_from=' . $calling_from . '&dialed_number=' . $dialed_number);
        }
    }

    public function internalReport($inputs)
    {
        $channel = "TRIM(REPLACE(SUBSTRING(channel,1,LOCATE(\"-\",channel,LENGTH(channel)-8)-1),\"SIP/\",\"\"))";

        $userExtention = implode(',', Auth::User()->Extension()->Pluck("extension_no")->ToArray());
//        if (isset($inputs['calling_from']) != '') {
//            $userExtention = $inputs['calling_from'];
//        }
        $userDid = $this->dids($userExtention);// Auth::User()->did_no;
        $userExtention .= (($userExtention != "" and $userDid != "") ? "," : "") . $userDid;

        $where = "dst in ($userExtention) and (src in ($userExtention) or cnum in ($userExtention))";

        $dateFrom = (isset($inputs['dateFrom']) ? $inputs['dateFrom'] : date("Y-m-d"));
        $dateTo = (isset($inputs['dateTo']) ? $inputs['dateTo'] : date("Y-m-d"));
        Session::put('dateFrom', $dateFrom);
        Session::put('dateTo', $dateTo);
        $where = $where . " and calldate between '" . $dateFrom . " 00:00:00' and '" . $dateTo . " 23:59:59'";

        if (isset($inputs['calling_from']) != '') {
            $calling_from = $inputs['calling_from'];
            $where = $where . " and extension = '" . $calling_from . "'";
        }


        $sql_select = "
                count(distinct uniqueid) as Total,
                IFNULL(sum(case when src in ($userExtention) AND Length(dst)>4 then 1 end),0) as Outbound,
                sum(case when billsec>0 then 1 else 0 end) as Completed,
                sum(case when billsec=0 then 1 else 0 end) as Missed,
                sum(billsec) as Duration, sum(billsec) as Billing,
                extension,
                cnum as accountcode,
                extension as caller_id_number,
                cnam,cnum
                ";
        return DB::connection('mysql3')->table('cdr')
            ->leftjoin('asterisk.users', function ($join) {
                $join->on('cdr.src', '=', 'asterisk.users.extension')
                    ->orOn('cdr.cnum', '=', 'asterisk.users.extension');            })
            ->select(DB::raw(
                $sql_select
            ))
            ->whereRaw($where)
            ->groupby("extension")
            ->paginate(40);
        // ->withPath('?dateFrom=' . $dateFrom . '&dateTo=' . $dateTo . '&calling_from=' . $calling_from);

    }

    public function iCmbReport($inputs)
    {
        $userExtention = implode(',', Auth::User()->Extension()->Pluck("extension_no")->ToArray());
        $userDid = $this->dids($userExtention);// Auth::User()->did_no;

        $userExtention .= (($userExtention != "" and $userDid != "") ? "," : "") . $userDid;

        if (isset($inputs['calling_from']) != '') {
            $userDid = $this->dids($userExtention);// Auth::User()->did_no;
            $userExtention = $inputs['calling_from'];
            $userExtention .= (($userExtention != "" and $userDid != "") ? "," : "") . $userDid;
        }
        $where = "cdr.dst in ($userExtention)";
        $dateFrom = (isset($inputs['dateFrom']) ? $inputs['dateFrom'] : date("Y-m-d"));
        $dateTo = (isset($inputs['dateTo']) ? $inputs['dateTo'] : date("Y-m-d"));
        Session::put('dateFrom', $dateFrom);
        Session::put('dateTo', $dateTo);

        $where = $where . " and calldate between '" . $dateFrom . " 00:00:00' and '" . $dateTo . " 23:59:59'";

        $missed = "select count(distinct uniqueid) as Missed,dst from cdr where 
                calldate between '" . $dateFrom . " 00:00:00' and '" . $dateTo . " 23:59:59' and
                uniqueid not in (
                  select uniqueid from cdr where calldate between '" . $dateFrom . " 00:00:00' and '" . $dateTo . " 23:59:59' and disposition='ANSWERED'
                  ) and disposition in ('No Answer','busy','Failed')  group by dst
                  ";
        $answered = "select count(distinct uniqueid) as Completed, sum(billsec) as Billing, dst from cdr where 
                calldate between '" . $dateFrom . " 00:00:00' and '" . $dateTo . " 23:59:59' and 
                 disposition in ('Answered')  group by dst
                  ";

        $sql_select = "
                extension,cdr.dst,
                cnum as accountcode,
                cnum as caller_id_number,
                cnam,cnum,
                count(distinct uniqueid) as Total,  
                Completed,
                Missed,
                Duration, 
                Billing  
                ";

        return DB::connection('mysql3')->table('cdr')
            ->leftjoin(DB::raw("($missed) as cdr1"), function ($join) {
                $join->on('cdr.dst', '=', 'cdr1.dst');
            })
            ->leftjoin(DB::raw("($answered) as cdr2"), function ($join) {
                $join->on('cdr.dst', '=', 'cdr2.dst');
            })
            ->leftjoin('asterisk.users', function ($join) {
                $join->on('cdr.dst', '=', 'asterisk.users.extension');
            })
            ->select(DB::raw(
                $sql_select
            ))
            ->whereRaw($where)
            ->orderby("extension")
            ->groupby("extension")
            ->get();
//
//        return DB::connection('mysql3')->table('cdr')
//            ->select(DB::raw("
//            dst AS caller_id_number,cnam, dst,
//            count(*) as Total, IFNULL(sum(case when dst in ($userExtention) then 1 end),0) as Inbound,
//            sum(case when billsec>0 then 1 else 0 end) as Completed,
//            sum(case when billsec=0 then 1 else 0 end) as Missed,
//            sum(billsec) as Duration, sum(billsec) as Billing"))
//            ->whereRaw($where)
//            ->groupby("dst")->get();

    }

    public function oCmbReport($inputs)
    {
        $userExtention = implode(',', Auth::User()->Extension()->Pluck("extension_no")->ToArray());
        if (isset($inputs['calling_from']) != '') {
            $userExtention = $inputs['calling_from'];
        }
        $userDid = $this->dids($userExtention);//Auth::User()->did_no;
        $userExtention .= (($userExtention != "" and $userDid != "") ? "," : "") . $userDid;

        $where = "(src in ($userExtention) AND Length(dst)>4 )";

        $dateFrom = (isset($inputs['dateFrom']) ? $inputs['dateFrom'] : date("Y-m-d"));
        $dateTo = (isset($inputs['dateTo']) ? $inputs['dateTo'] : date("Y-m-d"));
        Session::put('dateFrom', $dateFrom);
        Session::put('dateTo', $dateTo);
        $where = $where . " and calldate between '" . $dateFrom . " 00:00:00' and '" . $dateTo . " 23:59:59'";

        if (isset($inputs['calling_from']) != '') {
            $calling_from = $inputs['calling_from'];
            $where = $where . " and extension = '" . $calling_from . "'";
        }

        $sql = "count(*) as Total, 
                IFNULL(sum(case when src in ($userExtention) then 1 end),0) as Outbound, 
                sum(case when billsec>0 then 1 else 0 end) as Completed, 
                sum(case when billsec=0 then 1 else 0 end) as Missed, 
                sum(billsec) as Duration, sum(billsec) as Billing,
                cnum  AS caller_id_number,
                cnum as src";

        return DB::connection('mysql3')
            ->table('cdr')
            ->leftjoin('asterisk.users', function ($join) {
                $join->on('extension', '=', 'cnum');
            })
            ->select(DB::raw($sql))
            ->whereRaw($where)
            ->groupby("cnum")
            ->get();


    }

    public function iExtReport()
    {
        $userExtention = implode(',', Auth::User()->Extension()->Pluck("extension_no")->ToArray());
        $userDid = $this->dids($userExtention);// Auth::User()->did_no;

            $userExtention.= ($userExtention!=""?$userExtention:"")  . "," . $userDid;
        $where = "cdr.dst in ($userExtention)";
        $dateFrom = date("Y-m-d");
        $dateTo =  date("Y-m-d");
        $where = $where . " and calldate between '" . $dateFrom . " 00:00:00' and '" . $dateTo . " 23:59:59'";

        $missed = "select count(distinct uniqueid) as Missed,dst from cdr where 
                calldate between '" . $dateFrom . " 00:00:00' and '" . $dateTo . " 23:59:59' and
                uniqueid not in (
                  select uniqueid from cdr where calldate between '" . $dateFrom . " 00:00:00' and '" . $dateTo . " 23:59:59' and disposition='ANSWERED'
                  ) and disposition in ('No Answer','busy','Failed')  group by dst
                  ";
        $answered = "select count(distinct uniqueid) as Completed, sum(billsec) as Billing, dst from cdr where 
                calldate between '" . $dateFrom . " 00:00:00' and '" . $dateTo . " 23:59:59' and 
                 disposition in ('Answered')  group by dst
                  ";

        $sql_select = "
                extension,cdr.dst,
                cnum as accountcode,
                cnum as caller_id_number,
                cnam,cnum,
                count(distinct uniqueid) as Total,  
                Completed,
                Missed,
                Duration, 
                Billing  
                ";

        return DB::connection('mysql3')->table('cdr')
            ->leftjoin(DB::raw("($missed) as cdr1"), function ($join) {
                $join->on('cdr.dst', '=', 'cdr1.dst');
            })
            ->leftjoin(DB::raw("($answered) as cdr2"), function ($join) {
                $join->on('cdr.dst', '=', 'cdr2.dst');
            })
            ->leftjoin('asterisk.users', function ($join) {
                $join->on('cdr.dst', '=', 'asterisk.users.extension');
            })
            ->select(DB::raw(
                $sql_select
            ))
            ->whereRaw($where)
            ->orderby("extension")
            ->groupby("extension")
            ->get();

        return DB::connection('mysql3')->table('cdr')
            ->select(DB::raw("
            dst AS caller_id_number,cnam, dst, 
            count(*) as Total, IFNULL(sum(case when dst in ($userExtention) then 1 end),0) as Inbound, 
            sum(case when billsec>0 then 1 else 0 end) as Completed, 
            sum(case when billsec=0 then 1 else 0 end) as Missed, 
            sum(billsec) as Duration, sum(billsec) as Billing"))
            ->whereRaw($where)
            ->groupby("dst")->get();

    }

    public function oExtReport()
    {
        $userExtention = implode(',', Auth::User()->Extension()->Pluck("extension_no")->ToArray());
        $userDid = $this->dids($userExtention);// Auth::User()->did_no;
        $userExtention.= ($userExtention!=""?$userExtention:"")  . "," . $userDid;


        $where = "(src in ($userExtention) AND Length(dst)>4 )";
        $dateFrom = date("Y-m-d"). " 00:00:00";
        $dateTo =  date("Y-m-d"). " 23:59:59";
        $where = $where . " and calldate between '" . $dateFrom . "' and '" . $dateTo . "'";
        $sql = "count(*) as Total, 
                IFNULL(sum(case when src in ($userExtention) then 1 end),0) as Outbound, 
                sum(case when billsec>0 then 1 else 0 end) as Completed, 
                sum(case when billsec=0 then 1 else 0 end) as Missed, 
                sum(billsec) as Duration, sum(billsec) as Billing,
                cnum  AS caller_id_number,
                cnum as src";

        return DB::connection('mysql3')->table('cdr')->select(DB::raw($sql))
            ->whereRaw($where)
            ->groupby("cnum")
            ->get();


    }


    public function realTimeReport($request)
    {
        $userExtensions = Auth::User()->Extension()->Pluck("extension_no")->ToArray();
        $userExtensions[] = $this->dids($userExtensions);

        $sql = "Select * from agentlogin 
        where interface in ('" . implode("','", $userExtensions) . "') 
        and event='QueueMemberAdded'
        and  logout_time is NULL";
        return DB::connection('mysql')->select($sql);
    }

    public function agentsReport($data)
    {
        $userExtensions = Auth::User()->Extension()->Pluck("extension_no")->ToArray();
        $userExtensions[] = $this->dids($userExtensions);//Auth::User()->did_no;
        $sql = "select *, timediff(logout_time,login_time) as logintime from agentlogin 
                where interface in ('" . implode("','", $userExtensions) . "') 
                and event='queuememberadded' group by logintime,interface";
        return DB::connection('mysql')->select($sql);


    }

    public function distributionSubData($req)
    {


        $type = $req['type'];
        $start = $req['dateFrom'];
        $end = $req['dateTo'];
        $starthr = $req['timeFrom'];
        $endhr = $req['timeTo'];
        $queue = $req['queue'];
        $agent = isset($req['agent']) ? $req['agent'] : "N0NE";
        $json['type'] = $type;
        $ext = '"' . implode('","', $this->extensions($agent)) . '"';

        $select2 = "select call_id from queue_log 
                        where 
                        (
                        (agent in ($ext) or SUBSTRING(agent,5) in ($agent)) and verb='connect' and  DATE_FORMAT(created, '%H:%i') between '" . $starthr . "' and '" . $endhr . "'
                        and created between '" . $start . " 00:00:00' and '" . $end . " 23:59:59'
                         )
                          OR 
                         (
                            verb in ('abandon','EXITWITHTIMEOUT') and  DATE_FORMAT(created, '%H:%i') between '" . $starthr . "' and '" . $endhr . "'
                            and created between '" . $start . " 00:00:00' and '" . $end . " 23:59:59'
                          
                          )";

        $sql_select = "t1.call_id, t1.agent, t1.verb, t1.queue,t2.data2 as caller_id, 
                       t2.data1 as waittime,u.name as agent_name, t1.created as date
                          from queue_log t1 left join
                        (
                        select call_id, data1,data2 from queue_log where verb in (\"enterqueue\")) t2 
                               on t1.call_id = t2.call_id                            
                        
                            left join (select agent,call_id from queue_log where verb =\"ringnoanswer\" and call_id 
                              
                              in (select call_id from queue_log where verb in (\"exitwithtimeout\"))
                             
                             )  t3 on t1.call_id = t3.call_id
                             left join asterisk.users u 
                            on 
                                  SUBSTRING(t1.agent COLLATE utf8_unicode_ci,5) = u.extension
                            or
                            t1.agent = u.name COLLATE utf8_general_ci
                            

                             ";
        switch ($type) {
            case "queue":
                $queue = $req['typeval'];
                $query = "select $sql_select
                   where                   
                  t1.verb in ('connect','abandon','EXITWITHTIMEOUT') 
                  and t1.call_id in
                  
                   (
                     $select2        
                    )
                  
                  and DATE_FORMAT(t1.created, '%H:%i') between '" . $starthr . "' and '" . $endhr . "'
                  and t1.created between '" . $start . " 00:00:00' and '" . $end . " 23:59:59'";
                $query .= ((isset($queue) and $queue != "") ? " and t1.queue in ($queue)" : "");
                $query .=" group by t1.call_id";
                $json['data'] = DB::connection('mysql2')->select($query);
                return $json;
                break;
            case "month":
                $month = $req['typeval'];

                $query = "select $sql_select
                   where                   
                  t1.verb in ('connect','abandon','EXITWITHTIMEOUT') 
                  and t1.call_id in 
                  
                   (
                     $select2        
                    )
                   and DATE_FORMAT(t1.created,'%M %Y') = '" . $month . "' 
                  and DATE_FORMAT(t1.created, '%H:%i') between '" . $starthr . "' and '" . $endhr . "'
                  and t1.created between '" . $start . " 00:00:00' and '" . $end . " 23:59:59'";
                $query .= ((isset($queue) and $queue != "") ? " and t1.queue in ($queue)" : "");
                $query .=" group by t1.call_id";


                $json['data'] = DB::connection('mysql2')->select($query);

                return $json;
                break;
            case "week":
                $week = $req['typeval'];
                $query = "select $sql_select
                   where                   
                  t1.verb in ('connect','abandon','EXITWITHTIMEOUT') 
                  and t1.call_id in 
                  
                   (
                     $select2        
                    )
                   and Week(t1.created) = '" . $week . "' 
                  and DATE_FORMAT(t1.created, '%H:%i') between '" . $starthr . "' and '" . $endhr . "'
                  and t1.created between '" . $start . " 00:00:00' and '" . $end . " 23:59:59'";
                $query .= ((isset($queue) and $queue != "") ? " and t1.queue in ($queue)" : "");
                $query .=" group by t1.call_id";


                $json['data'] = DB::connection('mysql2')->select($query);
                return $json;
                break;

            case "day":
                $day = $req['typeval'];
                $query = "select $sql_select
                   where                   
                  t1.verb in ('connect','abandon','EXITWITHTIMEOUT') 
                  and t1.call_id in 
                  
                   (
                     $select2        
                    )                  
                  and DATE_FORMAT(t1.created, '%H:%i') between '" . $starthr . "' and '" . $endhr . "'
                  and t1.created between '" . $day . " 00:00:00' and '" . $day . " 23:59:59'";
                $query .= ((isset($queue) and $queue != "") ? " and t1.queue in ($queue)" : "");
                $query .=" group by t1.call_id";

                $json['data'] = DB::connection('mysql2')->select($query);
                return $json;
                break;

            case "hour":
                $hour = $req['typeval'];

                $query = "select $sql_select
                   where                   
                  t1.verb in ('connect','abandon','EXITWITHTIMEOUT') 
                  and t1.call_id in 
                  
                   (
                     $select2        
                    )
                  and hour(t1.created) = '" . $hour . "' and t1.created >= '" . $start . "'
                  and DATE_FORMAT(t1.created, '%H:%i') between '" . $starthr . "' and '" . $endhr . "'
                  and t1.created between '" . $start . " 00:00:00' and '" . $end . " 23:59:59'";
                $query .= ((isset($queue) and $queue != "") ? " and t1.queue in ($queue)" : "");
                $query .=" group by t1.call_id";

                $json['data'] = DB::connection('mysql2')->select($query);
                return $json;
                break;
            case "dayweek":
                $day = $req['typeval'];
                $query = "select $sql_select 
                   where                   
                  t1.verb in ('connect','abandon','EXITWITHTIMEOUT') 
                  and t1.call_id in 
                  
                   (
                     $select2        
                    )
                  and DayName(t1.created) = '" . $day . "'
                  and DATE_FORMAT(t1.created, '%H:%i') between '" . $starthr . "' and '" . $endhr . "'
                  and t1.created between '" . $start . " 00:00:00' and '" . $end . " 23:59:59'";
                $query .= ((isset($queue) and $queue != "") ? " and t1.queue in ($queue)" : "");
                $query .=" group by t1.call_id";


                $json['data'] = DB::connection('mysql2')->select($query);
                return $json;
                break;
        }


    }


    public function distributionSubDataExportCSV($req)
    {

        $type = $req['type'];
        $start = $req['dateFrom'];
        $end = $req['dateTo'];
        $starthr = $req['timeFrom'];
        $endhr = $req['timeTo'];
        $queue = $req['queue'];
        $agent = isset($req['agent']) ? $req['agent'] : "N0NE";
        $json['type'] = $type;
        $ext = '"' . implode('","', $this->extensions($agent)) . '"';

        $select2 = "select call_id from queue_log 
                        where 
                        (
                        (agent in ($ext) or SUBSTRING(agent,5) in ($agent)) and verb='connect' and  DATE_FORMAT(created, '%H:%i') between '" . $starthr . "' and '" . $endhr . "'
                        and created between '" . $start . " 00:00:00' and '" . $end . " 23:59:59'
                          )
                          OR 
                         (
                            verb in ('abandon','EXITWITHTIMEOUT') and  DATE_FORMAT(created, '%H:%i') between '" . $starthr . "' and '" . $endhr . "'
                            and created between '" . $start . " 00:00:00' and '" . $end . " 23:59:59'
                          )";

        $exp_sql = "t2.data2 as caller_id,  t1.created as date, u.name as agent, 
                 case when t1.verb in ('abandon','exitwithtimeout') then 'Abandon' else 'Answered' end as status,
                 t1.queue";

        switch ($type) {
            case "queue":
                $queue = $req['typeval'];
                $query = "select $exp_sql
                  from queue_log t1 left join 
                   (select * from queue_log where verb in ('enterqueue')) t2 
                       on t1.call_id = t2.call_id 
                   left join asterisk.users u 
                    on 
                          SUBSTRING(t1.agent COLLATE utf8_unicode_ci,5) = u.extension
                    or
                          t1.agent = u.name COLLATE utf8_general_ci    
                   where                   
                  t1.verb in ('connect','abandon','EXITWITHTIMEOUT') 
                  and t1.call_id in 
                  
                   (
                     $select2        
                    )
                  
                  and DATE_FORMAT(t1.created, '%H:%i') between '" . $starthr . "' and '" . $endhr . "'
                  and t1.created between '" . $start . " 00:00:00' and '" . $end . " 23:59:59'";
                $query .= ((isset($queue) and $queue != "") ? " and t1.queue in ($queue)" : "");

                $json = DB::connection('mysql2')->select($query);
                return $json;
                break;
            case "month":
                $month = $req['typeval'];

                $query = "select $exp_sql
                  from queue_log t1 
                    left join 
                    (select * from queue_log where verb in ('enterqueue')) t2 
                       on t1.call_id = t2.call_id 
                    left join asterisk.users u 
                    on 
                          SUBSTRING(t1.agent COLLATE utf8_unicode_ci,5) = u.extension
                    or
                          t1.agent = u.name COLLATE utf8_general_ci  
                   where                   
                  t1.verb in ('connect','abandon','EXITWITHTIMEOUT') 
                  and t1.call_id in 
                  
                   (
                     $select2        
                    )
                   and DATE_FORMAT(t1.created,'%M %Y') = '" . $month . "' 
                  and DATE_FORMAT(t1.created, '%H:%i') between '" . $starthr . "' and '" . $endhr . "'
                  and t1.created between '" . $start . " 00:00:00' and '" . $end . " 23:59:59'";
                  $query .= ((isset($queue) and $queue != "") ? " and t1.queue in ($queue)" : "");


                $json = DB::connection('mysql2')->select($query);

                return $json;
                break;
            case "week":
                $week = $req['typeval'];
                $query = "select $exp_sql
                  from queue_log t1 left join 
                   (select * from queue_log where verb in ('enterqueue')) t2 
                       on t1.call_id = t2.call_id 
                   left join asterisk.users u 
                    on 
                          SUBSTRING(t1.agent COLLATE utf8_unicode_ci,5) = u.extension
                    or
                          t1.agent = u.name COLLATE utf8_general_ci      
                   where                   
                  t1.verb in ('connect','abandon','EXITWITHTIMEOUT') 
                  and t1.call_id in 
                  
                   (
                     $select2        
                    )
                   and Week(t1.created) = '" . $week . "' 
                  and DATE_FORMAT(t1.created, '%H:%i') between '" . $starthr . "' and '" . $endhr . "'
                  and t1.created between '" . $start . " 00:00:00' and '" . $end . " 23:59:59'";
                $query .= ((isset($queue) and $queue != "") ? " and t1.queue in ($queue)" : "");


                $json = DB::connection('mysql2')->select($query);
                return $json;
                break;
            case "day":
                $day = $req['typeval'];
                $query = "select $exp_sql
                  from queue_log t1 left join 
                   (select * from queue_log where verb in ('enterqueue')) t2 
                       on t1.call_id = t2.call_id 
                   left join asterisk.users u 
                    on 
                          SUBSTRING(t1.agent COLLATE utf8_unicode_ci,5) = u.extension
                    or
                          t1.agent = u.name COLLATE utf8_general_ci      
                   where                   
                  t1.verb in ('connect','abandon','EXITWITHTIMEOUT') 
                  and t1.call_id in 
                  
                   (
                     $select2        
                    )
                   and DayName(t1.created) = '" . $day . "'  
                  and DATE_FORMAT(t1.created, '%H:%i') between '" . $starthr . "' and '" . $endhr . "'
                  and t1.created between '" . $start . " 00:00:00' and '" . $end . " 23:59:59'";
                $query .= ((isset($queue) and $queue != "") ? " and t1.queue in ($queue)" : "");
                $json = DB::connection('mysql2')->select($query);
                return $json;
                break;
            case "hour":
                $hour = $req['typeval'];

                $query = "select $exp_sql
                  from queue_log t1 left join 
                   (select * from queue_log where verb in ('enterqueue')) t2 
                       on t1.call_id = t2.call_id 
                       left join asterisk.users u 
                    on 
                          SUBSTRING(t1.agent COLLATE utf8_unicode_ci,5) = u.extension
                    or
                          t1.agent = u.name COLLATE utf8_general_ci  
                   where                   
                  t1.verb in ('connect','abandon','EXITWITHTIMEOUT') 
                  and t1.call_id in 
                  
                   (
                     $select2        
                    )
                  and hour(t1.created) = '" . $hour . "' and t1.created >= '" . $start . "'
                  and DATE_FORMAT(t1.created, '%H:%i') between '" . $starthr . "' and '" . $endhr . "'
                  and t1.created between '" . $start . " 00:00:00' and '" . $end . " 23:59:59'";
                $query .= ((isset($queue) and $queue != "") ? " and t1.queue in ($queue)" : "");

                $json = DB::connection('mysql2')->select($query);
                return $json;
                break;
            case "dayweek":
                $day = $req['typeval'];
                $query = "select $exp_sql
                  from queue_log t1 left join 
                   (select * from queue_log where verb in ('enterqueue')) t2 
                       on t1.call_id = t2.call_id 
                    left join asterisk.users u 
                    on 
                          SUBSTRING(t1.agent COLLATE utf8_unicode_ci,5) = u.extension
                    or
                          t1.agent = u.name COLLATE utf8_general_ci  
                   where                   
                  t1.verb in ('connect','abandon','EXITWITHTIMEOUT') 
                  and t1.call_id in 
                  
                   (
                     $select2        
                    )
                  and DayName(t1.created) = '" . $day . "'
                  and DATE_FORMAT(t1.created, '%H:%i') between '" . $starthr . "' and '" . $endhr . "'
                  and t1.created between '" . $start . " 00:00:00' and '" . $end . " 23:59:59'";
                $query .= ((isset($queue) and $queue != "") ? " and t1.queue in ($queue)" : "");

                $json = DB::connection('mysql2')->select($query);
                return $json;
                break;


        }


    }

    public function distribution($req)
    {
        $date = explode("-", $req['daterange']);
        $start = trim($date[2])."-".trim($date[1])."-".trim($date[0]);//date('Y-m-d', strtotime($date[0]));
        $starthr = $req['hour1'] . ":" . $req['minute1'];
        $end =  trim($date[5])."-".trim($date[4])."-".trim($date[3]);//date('Y-m-d', strtotime($date[1]));
        $endhr = $req['hour2'] . ":" . $req['minute2'];
        $json = array();
        $json['available_queue'] = implode(',', $req['queue']);
        $json['start_date'] = $start;//date('Y/m/d', strtotime($start));
        $json['end_date'] = $end; //date('Y/m/d', strtotime($end));
        $json['hour_range'] = $req['hour1'] . ":" . $req['minute1'] . " - " . $req['hour2'] . ":" . $req['minute2'];
        $json['timefrom'] = $req['hour1'] . ":" . $req['minute1'];
        $json['timeto'] = $req['hour2'] . ":" . $req['minute2'];
        $json['period'] = round((strtotime($end) - strtotime($start)) / (60 * 60 * 24)) + 1;
        $json['datefrom'] = $start;
        $json['dateto'] = $end;
        $json['agents'] = $extensions = isset($req['agents']) ? implode(',', $req['agents']) : "N0NE";

        $ext = '"' . implode('","', $this->extensions($json['agents'])) . '"';


        $select1 = "count(distinct call_id) as received,
                      sum(CASE When verb in ('ABANDON','EXITWITHTIMEOUT') Then 1 else 0 End) as abandon, 
                      sum(CASE verb When 'CONNECT' Then 1 else 0 End) as answered,
                      Round(sum(CASE verb When 'CONNECT' Then 1 else 0 End)*100/count(distinct call_id)) as answeravg,
                      Round(sum(CASE When verb in ('ABANDON','EXITWITHTIMEOUT') Then 1 else 0 End)*100/count(distinct call_id)) as abandonavg";


        $select2 = "select call_id from queue_log 
                        where 
                        (
                        (agent in ($ext) or SUBSTRING(agent,5) in ($extensions)) and verb in ('blindtransfer','connect') and  DATE_FORMAT(created, '%H:%i') between '" . $starthr . "' and '" . $endhr . "'
                        and created between '" . $start . " 00:00:00' and '" . $end . " 23:59:59'
                          )
                          OR 
                         (
                            verb in ('abandon','EXITWITHTIMEOUT') and  DATE_FORMAT(created, '%H:%i') between '" . $starthr . "' and '" . $endhr . "'
                            and created between '" . $start . " 00:00:00' and '" . $end . " 23:59:59'
                          )";

        $query = "select $select1 
                  from queue_log where 
                  verb in ('connect','abandon','EXITWITHTIMEOUT') 
                  and queue in (" . $json['available_queue'] . ")
                  and call_id in                   
                  (
                   $select2              
                  ) 
                  and DATE_FORMAT(created, '%H:%i') between '" . $starthr . "' and '" . $endhr . "'
                  and created between '" . $start . " 00:00:00' and '" . $end . " 23:59:59'";


        $Result = DB::connection('mysql2')->select($query);

        foreach ($Result as $row) {
            $json['total_calls']['Received'] = $row->received;
            $json['total_calls']['Answered'] = $row->answered;
            $json['total_calls']['Abandoned'] = $row->abandon;
            $json['total_calls']['AbandonRate'] = $row->abandonavg;
            $json['total_calls']['AnswerRate'] = $row->answeravg;
        }


        $query = "select queue, $select1
                  from queue_log where                   
                  verb in ('connect','abandon','EXITWITHTIMEOUT') 
                  and queue in (" . $json['available_queue'] . ")
                  and call_id in 
                  
                   (
                     $select2        
                    )

                  and DATE_FORMAT(created, '%H:%i') between '" . $starthr . "' and '" . $endhr . "'
                  and created between '" . $start . " 00:00:00' and '" . $end . " 23:59:59' group by queue";



        $json['dist_by_queue'] = DB::connection('mysql2')->select($query);
        $json['dist_by_queue_chart'] = json_encode(DB::connection('mysql2')->select($query), 1);



        $query = "select DATE_FORMAT(created,'%M %Y') as month,queue, 
                  $select1
                  from queue_log 
                  where  
                  verb in ('connect','abandon','EXITWITHTIMEOUT') 
                  and queue in (" . $json['available_queue'] . ")
                  and call_id in 
                  
                  (
                  $select2             
                  )

                  and DATE_FORMAT(created, '%H:%i') between '" . $starthr . "' and '" . $endhr . "'
                  and created between '" . $start . " 00:00:00' and '" . $end . " 23:59:59' group by month order by id";

        $json['dist_by_month'] = DB::connection('mysql2')->select($query);

        $groupby = "Group by Month(created), Week(created)";

        $query = "select Week(created) AS week, 
                   queue, $select1
                  FROM queue_log 
                  where 
                  verb in ('connect','abandon','EXITWITHTIMEOUT') 
                  and queue in (" . $json['available_queue'] . ")
                  and call_id in 
                  
                   (
                    $select2
                   )

                  and DATE_FORMAT(created, '%H:%i') between '" . $starthr . "' and '" . $endhr . "'
                  and created between '" . $start . " 00:00:00' and '" . $end . " 23:59:59' " . $groupby . " order by id";

        $json['dist_by_week'] = DB::connection('mysql2')->select($query);
        $groupby = "Group by day";

        $query = "select Date_format(created,'%Y-%m-%d') AS day, 
                  $select1
                  FROM queue_log 
                  where 
                  verb in ('connect','abandon','EXITWITHTIMEOUT') 
                  and queue in (" . $json['available_queue'] . ")
                  and call_id in 
                  
                   (
                    $select2
                   )

                  and DATE_FORMAT(created, '%H:%i') between '" . $starthr . "' and '" . $endhr . "'
                  and created between '" . $start . " 00:00:00' and '" . $end . " 23:59:59' " . $groupby . " order by id";

        $json['dist_by_day'] = DB::connection('mysql2')->select($query);
        $json['dist_by_day_chart'] = json_encode(DB::connection('mysql2')->select($query), 1);

        $groupby = "Group by hour";

        $query = "select Hour(created) AS hour, 
                  $select1
                  FROM queue_log 
                  where 
                  verb in ('connect','abandon','EXITWITHTIMEOUT') 
                  and queue in (" . $json['available_queue'] . ")
                  and call_id in 
                  
                   (
                    $select2             
                    )
                  and DATE_FORMAT(created, '%H:%i') between '" . $starthr . "' and '" . $endhr . "'
                  and created between '" . $start . " 00:00:00' and '" . $end . " 23:59:59' " . $groupby . " order by hour";

        $json['dist_by_hour'] = DB::connection('mysql2')->select($query);
        $json['dist_by_hour_chart'] = json_encode(DB::connection('mysql2')->select($query), 1);

        $groupby = "Group by day";

        $query = "select Dayname(created) AS day, 
                  $select1
                  FROM queue_log 
                  where 
                  verb in ('connect','abandon','EXITWITHTIMEOUT') 
                  and queue in (" . $json['available_queue'] . ")
                  and call_id in 
                  
                   (
                    $select2               
                    )

                  and DATE_FORMAT(created, '%H:%i') between '" . $starthr . "' and '" . $endhr . "'
                  and created between '" . $start . " 00:00:00' and '" . $end . " 23:59:59' " . $groupby . " order by DAYOFWEEK(created) asc";

        $json['dist_by_weekday'] = DB::connection('mysql2')->select($query);

        return $json;

    }

    public function extensions($where = "")
    {
        if ($where == "") {
            $data = DB::connection('mysql4')->table('devices')->select(DB::raw('id,description'))->get()->ToArray();
        } else
            $data = DB::connection('mysql4')->table('devices')->select(DB::raw('id,description'))->whereRaw('id in (' . $where . ')')->get()->toArray();


        $data = json_decode(json_encode($data), true);
        $temp_ext = array();
        foreach ($data as $item) {
            $temp_ext[$item['id']] = ((isset($item['description']) and $item['description']!="")?$item['description']:$item['id']);
        }
        return $temp_ext;
    }


    public function dids($exts = "")
    {
        if ($exts == "") {
            $data = DB::connection('mysql4')->table('users')->select(DB::raw('outboundcid'))->get();
        } else
            $data = DB::connection('mysql4')->table('users')
                ->select(DB::raw('outboundcid'))->whereRaw('extension in (' . $exts . ')')->get();
return implode(',',$data->Pluck('outboundcid')->ToArray());
        echo "<div style='display:none'>". implode(',',$data->Pluck('outboundcid')->ToArray()) . "</div>";
   }


    public function abandonCalls($queue)
    {
        $query = "select call_id from queue_log 
        where ".  ($queue!=""? "queue in ($queue) and ":" "). " 
        verb IN ('abandon','EXITWITHTIMEOUT')"; //and DATE(created) = CURDATE()
        $query = "select data2,created,queue,agent from queue_log where call_id in (" . $query . ") 
        and verb in ('enterqueue')";
        return DB::connection('mysql2')->select($query);

    }

    public function realTimeQueueData($request)
    {


        $queue_data = DB::connection('mysql')->select("select * from queue where user_id=" . Auth::id());
        foreach ($queue_data as $queue_datum) {
            $queue[] = $queue_datum->queue;
            $queue_datum->received = 0;
            $queue_datum->answered = 0;
            $queue_datum->abandon = 0;
            $queue_datum->talk_time = "00:00:00";
            $arr[$queue_datum->queue] = $queue_datum;//->queue_description;
        }

        $query = "select queue, 
                  count(distinct call_id) as received,
                  count(if(verb in ('ABANDON','EXITWITHTIMEOUT'),1,NULL)) as abandon, 
                  count(if(verb='connect',1,NULL)) as answered,
                  Round(sum(if(verb in ('COMPLETECALLER', 'COMPLETEAGENT'),data2,NULL))) as average_talk_time,
                  Round(count(if(verb='connect',1,NULL))*100/count(distinct call_id)) as answeravg,
                  Round(count(if(verb in ('ABANDON','EXITWITHTIMEOUT'),1,NULL))*100/count(distinct call_id)) as abandonavg
                  from queue_log where 
                  verb in ('connect','ABANDON','EXITWITHTIMEOUT','ENTERQUEUE','COMPLETECALLER', 'COMPLETEAGENT')
                  and queue in (". implode(",",$queue) . ") 
                  and created between '" . date('Y-m-d') . "  00:00:00' and '" . date('Y-m-d') . " 23:59:59' 
                  group by queue";

        $data = DB::connection('mysql2')->select($query);
        DB::disconnect('mysql2');

        if (count($data) > 0) {
            foreach ($data as $datum) {
                if ($datum->answered != 0) {
                    $datum->talk_time = gmdate("H:i:s", ($datum->average_talk_time/$datum->answered));
                }
                else
                    $datum->talk_time = "00:00:00";
                if(isset($arr[$datum->queue]))
                    $datum->queue_description = $arr[$datum->queue]->queue_description;
                $arr[$datum->queue] = $datum;
            }
        }

        $query = "select queue, 
                  sum(CASE verb When 'ENTERQUEUE' Then 1 else 0 End) as received,
                  count(if(verb in ('ABANDON','EXITWITHTIMEOUT'),1,NULL)) as abandon, 
                  count(if(verb='connect',1,NULL)) as answered,
                  Round(sum(if(verb in ('COMPLETECALLER', 'COMPLETEAGENT'),data2,NULL))) as average_talk_time,
                  Round(count(if(verb='connect',1,NULL))*100/count(distinct call_id)) as answeravg,
                  Round(count(if(verb in ('ABANDON','EXITWITHTIMEOUT'),1,NULL))*100/count(distinct call_id)) as abandonavg
                  from queue_log where 
                  verb in ('connect','ABANDON','EXITWITHTIMEOUT','ENTERQUEUE','COMPLETECALLER', 'COMPLETEAGENT')
                  and queue in (". implode(",",$queue) . ") 
                  and created between '" . date('Y-m-d') . "  00:00:00' and '" . date('Y-m-d') . " 23:59:59'";


        $data = DB::connection('mysql2')->select($query);
        DB::disconnect('mysql2');
        if (count($data) > 0) {
            foreach ($data as $datum) {
                if ($datum->answered != 0) {
                    $datum->talk_time = gmdate("H:i:s", ($datum->average_talk_time/$datum->answered));
                }
                else
                    $datum->talk_time = "00:00:00";
                if(isset($arr[$datum->queue]))
                    $datum->queue_description = "Total";
                $arr[] = $datum;
            }
        }
        return $arr;

    }

    public  function AMI($user,$pass)
    {

        $socket = fsockopen("127.0.0.1","5038", $errno, $errstr, 10);
        if (!$socket)
        {
            echo "$errstr ($errno)\n";
        }
        else
        {
            fputs($socket, "Action: Login\r\n");
            fputs($socket, "UserName: $user\r\n");
            fputs($socket, "Secret: $pass\r\n\r\n");

            fputs($socket, "Action: Command\r\n");
            fputs($socket, "Command: core show hints\r\n\r\n");

            fputs($socket, "Action: Logoff\r\n\r\n");

            $res="";
            while (!feof($socket))
            {
                $res.= fgets($socket);

            }
        }
        fclose($socket);
        return $res;

    }


}

