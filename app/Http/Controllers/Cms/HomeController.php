<?php

namespace App\Http\Controllers\Cms;
use App\Http\Controllers\Controller;
use App\Repositories\ReportsRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\URL;
use Auth;
use Hash;


class HomeController extends Controller
{
    /** @var  SubUsersRepository */
    private $reportRepository;

    public function __construct(ReportsRepository $reportRepository)
    {
        $this->middleware('auth');
        $this->reportRepository = $reportRepository;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->user()->can('dashboard_view'))
            return view('cms.home');
        elseif(request()->user()->can("view_combined"))
            return redirect(URL::asset('/')."cms/iouserreport");
        elseif(request()->user()->can('view_outgoing'))
            return redirect(URL::asset("/")."cms/ouserreport");
        elseif(request()->user()->can('view_incoming'))
            return redirect(URL::asset("/")."cms/incoming");
        elseif(request()->user()->can("view_realtime"))
            return redirect(URL::asset("/")."cms/realtime");
        elseif(request()->user()->can('view_queue_status'))
            return redirect(URL::asset("/")."cms/queuestats");
    }

    public function dashboardStats()
    {
        $dashboardReport = $this->reportRepository->DashboardReport();

        //$dashboardReport['TotalCalls'] = $dashboardReport['Abandoned']+$dashboardReport['Answered'];

        if($dashboardReport['TotalCalls']==0)
            $Connect=1;
        else
            $Connect=$dashboardReport['TotalCalls'];

        //$dashboardReport['Holdtime'] = gmdate("H:i:s", $dashboardReport['Holdtime']);

        $dashboardReport['AbandonRate'] = round($dashboardReport['Abandoned']*100/$Connect);
        $dashboardReport['AnswerRate'] = round($dashboardReport['Answered']*100/$Connect);

        $TotalTime = $dashboardReport['TotalTime'];
        $dashboardReport['TotalTime'] = gmdate("H:i:s", $dashboardReport['TotalTime']);


        $TotalAnswer=$dashboardReport['Answered']-$dashboardReport['Received'];

        if($TotalAnswer==0)
            $TotalAnswer=1;


        $dashboardReport['TalkTime'] = gmdate("H:i:s", $TotalTime/$TotalAnswer);
        $dashboardReport['WaitTime'] = gmdate("H:i:s", $dashboardReport['WaitTime']/$TotalAnswer);
        $dashboardReport['Holdtime'] = gmdate("H:i:s", $dashboardReport['Holdtime']/$TotalAnswer);

        //$dashboardReport['TalkTime'] = gmdate("H:i:s", round($dashboardReport['TalkTime']/($TotalTime*100)));
        //$dashboardReport['WaitTime'] = gmdate("H:i:s", round($dashboardReport['WaitTime']/($TotalTime*100)));

        if($dashboardReport['OBAnswer']==0)
            $OBAnswer=1;
        else
            $OBAnswer=$dashboardReport['OBAnswer'];

        $dashboardReport['OBAVGDuration'] = gmdate("H:i:s", ($dashboardReport['OBDuration']/($OBAnswer)));

        $dashboardReport['OBDuration'] = gmdate("H:i:s", $dashboardReport['OBDuration']);



        return response()->json($dashboardReport);
    }

    public function showChangePassword()
    {
        return view('cms.changepassword');
    }

    public function resetPassword(Request $request)
    {
        $this->validate($request, [
            'oldpassword' => 'required',
            'password' => 'required|confirmed',
        ]);

        $oldpassword = Input::get('oldpassword');
        $password = Input::get('password');

        $user = Auth::user();

        if(Hash::check($oldpassword, $user->password))
        {
            $user->password = bcrypt($password);
            $user->save();
            return redirect()->back()->with('flash_message',"Password changed successfully.");
        }
        else
        {
            return redirect()->back()->with('flash_message',"Please enter correct current password");
        }
    }

    public function dashboardQueueReport(){
        return $this->reportRepository->dashboardQueueReport();
    }
}
