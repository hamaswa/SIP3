<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $this->assertTrue(true);
        $sql = "Select TRIM(REPLACE(SUBSTRING(channel,1,LOCATE(\"-\",channel,LENGTH(channel)-8)-1),\"SIP/\",\"\")) as channelVal,
                DATE_FORMAT(max(calldate),'%d-%m-%Y %H:%i:%s') AS calldate,
                cnam,
                case 
                    when src in (7104,7106,7201,7202,7203,7301,7302,7303,7401,7402,7403,7502,7503,7102,7601,7602,7603,8002,7901,7902,7903,7801,7802,7803,8003,7501,7103,7101,7105,8005,8004,8001,8024,7702,7701,7703,8008,62640422,62800256,62807909,62814313,62930355,63972739,64814261,65322644,65819135,66361303,67023238,68334351,68334352,68334357,69503030,69503031,69503032,69503033,69503034,69503035,69503036,69503037,69503038,69503039,69504714,+6568334351,+6568334352) 
                        then cnum 
                    else 
                    src 
                end as outbound_caller_id,
                case 
                    when dst in (7104,7106,7201,7202,7203,7301,7302,7303,7401,7402,7403,7502,7503,7102,7601,7602,7603,8002,7901,7902,7903,7801,7802,7803,8003,7501,7103,7101,7105,8005,8004,8001,8024,7702,7701,7703,8008,62640422,62800256,62807909,62814313,62930355,63972739,64814261,65322644,65819135,66361303,67023238,68334351,68334352,68334357,69503030,69503031,69503032,69503033,69503034,69503035,69503036,69503037,69503038,69503039,69504714,+6568334351,+6568334352) 
                        then dst
                    else
                        TRIM(REPLACE(SUBSTRING(dstchannel,1,LOCATE(\"-\",dstchannel,LENGTH(channel)-8)-1),\"SIP/\",\"\"))
                    end  as destination,
                 case 
                    when group_concat(disposition,\";\") like \"%ANSWERED%\" 
                        then \"Answered\" 
                        else \"Abandoned\" 
                    end as disposition,
                accountcode as PIN,
                max(billsec) as billsec,
                (duration-billsec) as ringtime,
                case
                    when recordingfile!='' Then
                        recordingfile
                    else
                        \"No Data\"
                 end as Recording,
                case when dst in (7104,7106,7201,7202,7203,7301,7302,7303,7401,7402,7403,7502,7503,7102,7601,7602,7603,8002,7901,7902,7903,7801,7802,7803,8003,7501,7103,7101,7105,8005,8004,8001,8024,7702,7701,7703,8008,62640422,62800256,62807909,62814313,62930355,63972739,64814261,65322644,65819135,66361303,67023238,68334351,68334352,68334357,69503030,69503031,69503032,69503033,69503034,69503035,69503036,69503037,69503038,69503039,69504714,+6568334351,+6568334352) then 'Inbound' else 'Outbound' end as Direction,
                cnam AS CallerID from cdr ";
    }
}
