<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\Pbx_cdr;
use Illuminate\Http\Request;
use DB; 

class CDRController extends Controller
{
    public function __construct()
    {
        //
    }
    
	public function insertCDR($id)
	{
		$kanzooID = $id; 
		
		$header = array(
			'Content-type: application/json',
		);
		$service_url = 'http://kazoo.nautilus-network.com:8000/v1/user_auth';
		$data = '{ "data" : { "credentials" : "f3bd594cfd74565faf337ec0de37e711","account_name" : "company1" } }';
		$result = $this->callAPI('PUT', $service_url, $data, $header);
		$response = json_decode($result, true);
		$auth_token = $response['auth_token'];
		
		$header = array(
			'Content-type: application/json',
			'X-Auth-Token:'.$auth_token
		);
		$service_url = 'http://kazoo.nautilus-network.com:8000/v2/accounts/'.$kanzooID.'/cdrs?created_from=05-07-2018&created_to=05-07-2018';
		$result = $this->callAPI('', $service_url, '', $header);
		$response = json_decode($result, true);
		
		$insertData = array();
		foreach($response['data'] as $key => $data)
		{
			$insertData[] = array('kazooid' => $kanzooID, 
					'c_id' => $data['id'], 
					'call_id' => $data['call_id'], 
					'caller_id_number' => $data['caller_id_number'], 
					'caller_id_name' => $data['caller_id_name'], 
					'callee_id_number' => $data['callee_id_number'], 
					'callee_id_name' => $data['callee_id_name'], 
					'duration_seconds' => $data['duration_seconds'], 
					'billing_seconds' => $data['billing_seconds'], 
					'c_timestamp' => $data['timestamp'], 
					'hangup_cause' => $data['hangup_cause'], 
					'other_leg_call_id' => $data['other_leg_call_id'], 
					'owner_id' => $data['owner_id'], 
					'c_to' => $data['to'], 
					'c_from' => $data['from'], 
					'direction' => $data['direction'], 
					'request' => $data['request'], 
					'authorizing_id' => $data['authorizing_id'], 
					'cost' => $data['cost'], 
					'dialed_number' => $data['dialed_number'], 
					'calling_from' => $data['calling_from'], 
					'datetime' => $data['datetime'], 
					'unix_timestamp' => $data['unix_timestamp'], 
					'rfc_1036' => $data['rfc_1036'], 
					'iso_8601' => $data['iso_8601'], 
					'call_type' => $data['call_type'], 
					'rate' => $data['rate'], 
					'rate_name' => $data['rate_name'], 
					'bridge_id' => $data['bridge_id'], 
					'recording_url' => $data['recording_url'], 
					'media_recordings' => '', 
					'media_server' => $data['media_server'], 
					'call_priority' => $data['call_priority'], 
					'reseller_cost' => $data['reseller_cost'], 
					'reseller_call_type' => $data['reseller_call_type']);
		}
		if($insertData!="")
		{
			//print_r($insertData);
			Pbx_cdr::insert($insertData);
		}
	}
	
	public function callAPI($method, $url, $data, $header)
	{
	   $curl = curl_init();
	
	   switch ($method){
		  case "POST":
			 curl_setopt($curl, CURLOPT_POST, 1);
			 if ($data)
				curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
			 break;
		  case "PUT":
			 curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
			 if ($data)
				curl_setopt($curl, CURLOPT_POSTFIELDS, $data);			 					
			 break;
		  default:
			 if ($data)
				$url = sprintf("%s?%s", $url, http_build_query($data));
	   }
	
	   // OPTIONS:
	   curl_setopt($curl, CURLOPT_URL, $url);
	   curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
	   curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	   curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	
	   // EXECUTE:
	   $result = curl_exec($curl);
	   $errors = curl_error($curl);
	   print_r($errors);
	   if(!$result){die("Connection Failure");}
	   curl_close($curl);
	   return $result;
	}
}
