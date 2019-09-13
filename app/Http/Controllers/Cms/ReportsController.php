<?php

namespace App\Http\Controllers\Cms;

use App\DataTables\realTimeReportDataTable;
use App\DataTables\realTimeReportSummaryDataTable;
use App\Repositories\ReportsRepository;
use App\Http\Controllers\AppBaseController;
use Flash;
use Auth;
use App\Models\Pbx_cdr;
use Illuminate\Http\Request;

class ReportsController extends AppBaseController
{
    /** @var  SubUsersRepository */
    private $reportRepository;
    private $temp_queue;

    public function __construct(ReportsRepository $reportRepository)
    {
        $this->reportRepository = $reportRepository;
    }
	
	public function ioUserReport(Request $request)
    {
        $inputs = $request->all();

        $idata_tmp = $this->reportRepository->iCmbReport($inputs)->ToArray();
        $odata_tmp = $this->reportRepository->oCmbReport($inputs)->ToArray();

        foreach ($idata_tmp as $idatum) {
            $idata[$idatum->dst] = $idatum;
        }

        foreach ($odata_tmp as $odatum) {
            $odata[$odatum->src] = $odatum;
        }

        $userExtention = implode(',', Auth::User()->Extension()->Pluck("extension_no")->ToArray());
        $extensions = $this->reportRepository->extensions($userExtention);


        $reception_console = explode("\n", shell_exec('asterisk -rx "core show hints"'));

        for ($k = 2; $k < count($reception_console); $k++) { // as $key=>$val){
            $val = $reception_console[$k];
            $output = explode(" ", preg_replace('!\s+!', ' ', $val));
            if (isset($output[0]))
                $output[0] = preg_replace('/@.*/', '', $output[0]);
            if (isset($output[3]))
                $output[3] = preg_replace('/State:/', '', $output[3]);

            if (isset($extensions[$output[0]])) {
                $output[2] = $extensions[$output[0]];
                $recp_arr[$output[0]] = array('status' => $output, 'inbound' => (isset($idata[$output[0]]) ? $idata[$output[0]] : "no_data"), 'outbound' => (isset($odata[$output[0]]) ? $odata[$output[0]] : "no_data"));
            }
        }
        ksort($recp_arr, 1);
        $arr['ioReport'] = json_decode(json_encode($recp_arr), true);


        
        foreach ($arr['ioReport'] as $key => $data) {
            if ((isset($data['inbound']['Total']) and $data['inbound']['Total'] != 0)
                OR (isset($data['outbound']['Total']) and $data['outbound']['Total'] != 0)) {
                if (isset($data['inbound']['Total']) and isset($data['outbound']['Total']))
                    $total = ($data['inbound']['Total'] + $data['outbound']['Total']);
                else if (isset($data['inbound']['Total']))
                    $total = $data['inbound']['Total'];
                else
                    $total = isset($data['outbound']['Total']) ? $data['outbound']['Total'] : 1;
                $inbound_total = isset($data['inbound']['Total']) ? $data['inbound']['Total'] : "0";
                $outbound_total = isset($data['outbound']['Total']) ? $data['outbound']['Total'] : "0";

                if (isset($data['inbound']['Completed']) and isset($data['outbound']['Completed']))
                    $completed = ($data['inbound']['Completed'] + $data['outbound']['Completed']);
                else if (isset($data['inbound']['Completed']))
                    $completed = $data['inbound']['Completed'];
                else
                    $completed = isset($data['outbound']['Completed']) ? $data['outbound']['Completed'] : "0";

                if (isset($data['inbound']['Missed']) and isset($data['outbound']['Missed']))
                    $missed = ($data['inbound']['Missed'] + $data['outbound']['Missed']);
                else if (isset($data['inbound']['Missed']))
                    $missed = $data['inbound']['Missed'];
                else
                    $missed = isset($data['outbound']['Missed']) ? $data['outbound']['Missed'] : "0";

                if (isset($data['inbound']['Duration']) and isset($data['outbound']['Duration']))
                    $duration = ($data['inbound']['Duration'] + $data['outbound']['Duration']);
                else if (isset($data['inbound']['Duration']))
                    $duration = $data['inbound']['Duration'];
                else
                    $duration = isset($data['outbound']['Duration']) ? $data['outbound']['Duration'] : 1;

                $duration = gmdate("H:i:s", (int)$duration);
                $avg_duration = gmdate("H:i:s", (int)round((int)$duration / (int)$total));
                $exp[] = [
                    "Total" => $total,
                    "Incoming" => $inbound_total,
                    "Outgoing" => $outbound_total,
                    "Answered" => $completed,
                    "Missed" => $missed,
                    "Duration" => $duration,
                    "Avg Duration" => $avg_duration
                ];

            }

        }
        if (isset($inputs['type']) and $inputs['type'] != "") {
//
            $this->reportRepository->downloadCallReport($inputs['type'], $exp);
        }
        else {

            return view('cms.reports.iouserreport')->with($arr);
        }
	}
	
	public function ioCallReport(Request $request)
	{
		$inputs =  $request->all();
		$ioReport = $this->reportRepository->ioCallReport($inputs);
		return view('cms.reports.iocallreport', compact('ioReport'));
	}

	public function iCallReport(Request $request){
        $inputs =  $request->all();
        $inputs['direction'] = 2;
        $iReportDetail = $this->reportRepository->iCallReport($inputs );
        return view('cms.reports.iuserreport_sub', array('iReportDetail' => $iReportDetail));

    }
	public function iUserReport(Request $request)
	{
		$inputs =  $request->all();
		$inputs['direction'] = 2;
        if (isset($inputs['type']) and $inputs['type'] != "") {
            $this->reportRepository->iCallReport($inputs );
        }
        $iReport = $this->reportRepository->iUserReport($inputs);

		return view('cms.reports.iuserreport', array('iReport' => $iReport));//, 'iReportDetail' => $iReportDetail));
	}

	public function oCallReport(Request $request){
        $inputs =  $request->all();
        $inputs['direction'] = 1;
        $oReportDetail = $this->reportRepository->oCallReport($inputs );
        return view('cms.reports.ouserreport_sub', array('oReportDetail' => $oReportDetail));
    }
	public function oUserReport(Request $request)
	{
		$inputs =  $request->all();
		$inputs['direction'] = 1;
        if (isset($inputs['type']) and $inputs['type'] != "") {
            $this->reportRepository->oCallReport($inputs );
        }
        $oReport = $this->reportRepository->oUserReport($inputs);
		return view('cms.reports.ouserreport', array('oReport' => $oReport));
	}

    public function showRealTime(realTimeReportDataTable $dataTable)
    {
        return $dataTable->render('cms.reports.realtime');
    }

    public function realTimeFull(realTimeReportDataTable $dataTable)
    {
        return $dataTable->render('cms.reports.realtime')->with("mode","advanced");
    }

//    public function realTime(Request $request)
//    {
//
//        $data = $this->reportRepository->realTimeReport($request);
//        return response()->json($data);
//    }

    public function realTime(Request $request)
    {

        //$arr["data"] = $this->reportRepository->realTimeReport($request);
        $userExtention = implode(',', Auth::User()->Extension()->Pluck("extension_no")->ToArray());
        $extensions = $this->reportRepository->extensions($userExtention);


        //$reception_console = explode("\n",shell_exec('asterisk -rx "core show hints"'));
        $reception_console = explode("\n",$this->reportRepository->AMI("admin","dc65db7a1505cfaa84e4561ad423562c"));



        for($k = 12; $k < count($reception_console);$k++){ // as $key=>$val){
            $val = $reception_console[$k];
            $output = explode(" ", preg_replace('!\s+!', ' ', $val));
            if(isset($output[0]))
                $output[0] = preg_replace('/@.*/', '', $output[0]);
            if(isset($output[3]))
                $output[3] = preg_replace('/State:/', '', $output[3]);

            if(isset($extensions[$output[0]])) {
                $output[2] = $extensions[$output[0]];

                $recp_arr[$output[0]] = $output;
            }
        }
        ksort($recp_arr,1);
        $arr['reception_console'] = $recp_arr;


        return response()->json($arr);
    }


    public function realTimeDetails(Request $request)
    {


        $idata_tmp = $this->reportRepository->iExtReport();
        $odata_tmp = $this->reportRepository->oExtReport();

        foreach ($idata_tmp as $idatum){
            $idata[$idatum->dst] = $idatum;
        }

        foreach ($odata_tmp as $odatum){
            $odata[$odatum->src] = $odatum;
        }


        $userExtention = implode(',', Auth::User()->Extension()->Pluck("extension_no")->ToArray());
        $extensions = $this->reportRepository->extensions($userExtention);


        ///$reception_console = explode("\n",shell_exec('asterisk -rx "core show hints"'));
        $reception_console = explode("\n",$this->reportRepository->AMI("admin","dc65db7a1505cfaa84e4561ad423562c"));

        for($k = 2; $k < count($reception_console);$k++){ // as $key=>$val){
            $val = $reception_console[$k];
            $output = explode(" ", preg_replace('!\s+!', ' ', $val));
            if(isset($output[0]))
                $output[0] = preg_replace('/@.*/', '', $output[0]);
            if(isset($output[3]))
                $output[3] = preg_replace('/State:/', '', $output[3]);

            if(isset($extensions[$output[0]])) {
                $output[2] = $extensions[$output[0]];
                $recp_arr[$output[0]] = array('status'=> $output,'inbound'=>(isset($idata[$output[0]])?$idata[$output[0]]:"no_data"),'outbound'=>(isset($odata[$output[0]])?$odata[$output[0]]:"no_data"));
            }
        }
        ksort($recp_arr,1);
        $arr['reception_console'] = $recp_arr;


        return response()->json($arr);
    }

    public function realTimeReport($interface,realTimeReportSummaryDataTable $dataTable)
    {
        return $dataTable->with('interface',$interface)->render("cms.reports.realtimereport");
    }



    public function showQueueStatsReport(Request $request)
	{
        $arr = array('dateFrom' => date('Y-m-d'),
        'dateTo' => date('Y-m-d'),
        'hourFrom' => "00",
        'hourTo' => "23",
        'minFrom' => "00",
        'minTo' => "59");
		return view('cms.reports.queuestats',$arr);
	}

    public function queueStatsReport(Request $request)
    {
        $arr["queue_data"] = $this->reportRepository->realTimeQueueData($request);
        return response()->json($arr);
    }

    public function showQueueReport(Request $request)
    {
        $hour=array();
        $hour =['00:00-00:30'=>'00:00-00:30','00:30-01:00'=>'00:30-01:00','01:00-01:30'=>'01:00-01:30','01:30-02:00'=>'01:30-02:00',
                '02:00-02:30'=>'02:00-02:30','02:30-03:00'=>'02:30-03:00','03:00-03:30'=>'03:00-03:30','03:30-04:00'=>'03:30-04:00',
                '04:00-04:30'=>'04:00-04:30','04:30-05:00'=>'04:30-05:00','05:00-05:30'=>'05:00-03:30','05:30-06:00'=>'05:30-06:00',
                '06:00-06:30'=>'06:00-06:30','06:30-07:00'=>'06:30-07:00','07:00-07:30'=>'07:00-07:30','07:30-08:00'=>'07:30-08:00',
                '08:00-08:30'=>'08:00-08:30','08:30-09:00'=>'08:30-09:00','09:00-09:30'=>'09:00-09:30','09:30-10:00'=>'09:30-10:00',
                '10:00-10:30'=>'10:00-10:30','10:30-11:00'=>'10:30-11:00','11:00-11:30'=>'11:00-11:30','11:30-12:00'=>'11:30-12:00',
                '12:00-12:30'=>'12:00-12:30','12:30-13:00'=>'12:30-13:00','13:00-13:30'=>'13:00-13:30','13:30-14:00'=>'13:30-14:00',
                '14:00-14:30'=>'14:00-14:30','14:30-15:00'=>'14:30-15:00','15:00-15:30'=>'15:00-15:30','15:30-16:00'=>'15:30-16:00',
                '16:00-16:30'=>'16:00-16:30','16:30-17:00'=>'16:30-17:00','17:00-17:30'=>'17:00-17:30','17:30-18:00'=>'17:30-18:00',
                '18:00-18:30'=>'18:00-18:30','18:30-19:00'=>'18:30-19:00','19:00-19:30'=>'19:00-19:30','19:30-20:00'=>'19:30-20:00',
                '20:00-20:30'=>'20:00-20:30','20:30-21:00'=>'20:30-21:00','21:00-21:30'=>'21:00-21:30','21:30-22:00'=>'21:30-22:00',
                '22:00-22:30'=>'22:00-22:30','22:30-23:00'=>'22:30-23:00','23:00-23:30'=>'23:00-23:30','23:30-24:00'=>'23:30-00:00'];

        $queue = array('options'=>$this->getQueue(),'selected'=>$request['queue']);
        $thisyear = Date("Y");
        for($i=0;$i<=10;$i++) {
            $year[$thisyear-$i] = $thisyear-$i;
        }
            return view('cms.reports.queuereport',array('hour'=>$hour,'year'=>$year,'queue' => $queue));
    }


    public function queueReport(Request $request)
    {
        //return $this->reportRepository->QueueReportByStatus($request->all());
        $ReportDetail = $this->reportRepository->QueueReportByStatus($request->all());
        return response()->json($ReportDetail);
    }

    public function getQueue(){
        $queue = Auth::User()->queue()->select("queue","queue_description")->get()->toArray();
        foreach ($queue as $item) {
            $this->temp_queue[$item['queue']]=$item['queue_description'];
        }
        return $this->temp_queue;
    }

    public function abandonCalls(){

        $queue = Auth::User()->queue()->Pluck("queue")->ToArray();
        $arr["abandon_call_data"] = $this->reportRepository->abandonCalls(implode(",",$queue));
        return view("cms.reports.abandoncalls",$arr);
    }


}
