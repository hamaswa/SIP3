<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Did;
use App\Models\Plan;
use Session;
use App\Repositories\PlanRepository;
use App\Repositories\UserProfileRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    /** @var  SubUsersRepository */
    private $userProfileRepository;
	/** @var  PlanRepository */
    private $planRepository;
	
    public function __construct(UserProfileRepository $userProfileRepository, PlanRepository $planRepository)
    {
        $this->userProfileRepository = $userProfileRepository;
		$this->planRepository = $planRepository;
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rand_val = mt_rand();
        Session::put('rand_val', $rand_val);
		$info_Did = Did::all();
		$info_Plan = Plan::all();
		return view('welcome',array('info_Did' => $info_Did, 'info_Plan' => $info_Plan));
    }
	
	/**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $inputs = $request->all();
		$plan = $this->planRepository->findWithoutFail($inputs['planid']);
		
		//If select existing DID number
		if($inputs['reqType']==1)
		{
			$kanzooId = $this->userProfileRepository->KazooRegister($inputs);
			
			if($this->userProfileRepository->StripePayment($inputs,$plan))
			{
				$inputs['kanzooId'] = $kanzooId;
				$user = $this->userProfileRepository->addUser($inputs,$plan);				
				$this->userProfileRepository->addPayment($inputs, $user->id);
				$this->userProfileRepository->addPaymentHistory($plan, $user->id);
				$user->assignRole('admin');
				Session::flash ( 'success-message', 'Payment done successfully !' );
				return view('paymentresponse');
			}
			else
			{
				Session::flash ( 'fail-message', "Error! Please Try again." );
				return view('paymentresponse');
			}
		}
		else
		{
			
		}
    }
	
	public function sendSMS(Request $request)
	{
		$input = $request->all();
		$rand_val = Session::get('rand_val');
		$sms_code="";
		//return $input['rand_val']."--".$rand_val;
		if($input['rand_val']==$rand_val)
		{
			$sms_code = rand(1000, 9999);
			$mobile = $input['mobile'];
			$this->userProfileRepository->gw_send_sms("apiusername", "apipassword", "Nautilus", "$mobile", "Your sms verification code is $sms_code");
			Session::put('sms_code', $sms_code);
			Session::put('mobile', $mobile);
		}
		return $sms_code;
	}
	
	public function verifySMS(Request $request)
	{
		$input = $request->all();
		$sms_code = Session::get('sms_code');
		if($input['verifycode']==$sms_code)
		{
			return 1;
		}
		else
		{
			return 0;
		}
	}
	
	public function verifyEmail(Request $request)
	{
		$rules = [
            'name' => 'required|string|max:255',
            'email' =>  'unique:users,email|required|email',
			'password' => 'string|min:6',
        ];
		
		$validator = Validator::make($request->all(), $rules);
		if($validator->fails())
            return $validator->errors()->first();
		else
			return "";
	}
	
	
}
