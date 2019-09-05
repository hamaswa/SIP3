<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;
use App\Repositories\ReportsRepository;
use Auth;
use Response;


class DistributionController extends AppBaseController
{
    public function __construct()
    {
        $this->repo = new ReportsRepository();
    }

    public function index(Request $request){
        $data['queue']=  Auth::User()->queue()->Pluck("queue","queue")->ToArray();
        $data['queue_sel']= Auth::User()->queue()->Pluck("queue")->ToArray();
        $data['extension'] = $this->repo->extensions(implode(',',Auth::User()->Extension()->Pluck("extension_no")->ToArray()));

        $data['extension_sel']=  Auth::User()->Extension()->Pluck("extension_no")->ToArray();
        return view('cms.reports.distributionform',$data);
    }

    public function messages()
    {
        return [
            'queue.required' => 'Queue is required. Select atleast One',
            'extension.required'  => 'An Extension is required. Select atleast One',
        ];
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'queue' => 'required',
            'agents' => 'required',
        ]);
    }

    public function distribution(Request $request){
        $this->validator($request->all())->validate();

        $req = $request->all();
        $this->data = $this->repo->distribution($req);
        return view('cms.reports.distribution',$this->data);

    }

    public function distributionSubData(Request $request){
        $this->data = $this->repo->distributionSubData($request->all());
        return view('cms.reports.distributionsubdata',$this->data)->with("inputs",$request->all());
    }

    public function distributionSubDataExportCSV(Request $request)
    {

        $inputs = $request->all();
        $data = json_decode(json_encode($this->repo->distributionSubDataExportCSV($inputs)),true);

        $file_name = "distribution_by_".$inputs['type']."-". $inputs['typeval'];
        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$file_name.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $columns = array('Call ID', 'Date', 'Agent', 'Status', 'Queue');

        $callback = function () use ($data, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($data as $datum) {
                fputcsv($file, $datum);
            }
            fclose($file);
        };
        return Response::stream($callback, 200, $headers);
    }







}
