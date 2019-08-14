<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\User;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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
        $id = $inputs['user_id'];
        $user = User::find($id);
        $user->permissions()->detach();
        foreach ($inputs as $k => $v){
            $user->givePermissionsTo($k);
        }

        return back()->with("success","Permissions Successfully Updated");

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $permissions  = Permission::all();

        foreach ($permissions as $k=>$v){
            $arr_temp[$v->slug] = $v;
        }
        $arr['permissions'] = $arr_temp;
        $user = User::find($id);
        $arr["user_permissions"] = $user->permissions()->Pluck("permission_id",'slug')->ToArray();
//        echo "<pre>";
//        print_r($arr['user_permissions']);
//        exit();
        $arr['user_id'] = $id;
        return view("admin.permissions.form")->with($arr);

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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
