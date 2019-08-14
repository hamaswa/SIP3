<?php

namespace App\Http\Controllers\Cms;

use App\DataTables\CaseManagementDataTable;
use App\Models\CaseManagement;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CaseManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CaseManagementDataTable $dataTable)
    {
        return $dataTable->render("cms.case_management.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("cms.case_management.create");

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $inputs = $request->all();
        $inputs['user_id']=Auth::id();
        $case = CaseManagement::create($inputs);
        if($case->id){
            return back()->with("success","Record Successfully Added");
        }
        else {
            return back()->with("error","Error Occured");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $case = CaseManagement::find($id);

        return view("cms.case_management.show")->with(["case"=>$case,"inputs"=>['id'=>$id]]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $case = CaseManagement::find($id);
        return view("cms.case_management.edit")->with("case_management",$case);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $inputs = $request->all();
        $case = CaseManagement::find($id);
        try{
            $case->update($inputs);
            return back()->with("success","Record Successfully Updated");
        }
        catch (\Exception $exception) {
            return back()->with("success","Error Occured while updating record");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $case = CaseManagement::find($id);
        try{
            $case->delete();
            return back()->with("success","Record Successfully deleted");
        }
        catch (\Exception $exception) {
            return back()->with("success","Error Occured while deleting record");
        }
    }
}
