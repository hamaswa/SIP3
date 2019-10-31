<?php

namespace App\Http\Controllers\Cms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Queue;
use App\Models\Extension;
use DB;
use Hash;
use Flash;


//User datatable
use App\DataTables\SubUserDataTable;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(SubUserDataTable $subUserDataTable)
    {
        return $subUserDataTable->render('cms.users.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['extensions'] = $this->getExtension(implode(",",auth()->user()->Extension()->Pluck("extension_no")->ToArray()));
        $data['queue'] = auth()->user()->queue()->Pluck("queue_description","id")->ToArray();
        $data["user_id"]= auth()->id();
        return view("cms.users.create")->with(["data"=>$data]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $input = $request->all();
            $user = new User();
            $user->name = $input['name'];
            $user->created_at = date('Y-m-d h:i:s');
            $user->email = $input['email'];
            $user->mobile = $input['mobile'];
            $user->password = Hash::make($input['password']);
            $user->did_no = $input['did_no'];
            $user->parent_id = $input['parent_id'];
            $user->status = $input['status'];
            $user->save();
            $user_id = $user->id;

            $extension = new Extension();

            foreach ($input['extension'] as $ext) {
                $extension->create(['user_id' => $user_id, 'extension_no' => $ext]);
            }

            foreach ($input['queue'] as $q) {
                $queue = Queue::find($q);
                $new_queue= $queue->replicate();
                $new_queue->p_id=$queue->id;
                $new_queue->user_id=$user_id;
                $new_queue->save();
            }

            Flash::success('User saved successfully.');

            return redirect(route('users.index'));
        }
        catch (\Exception $exception){
            Flash::success($exception->getMessage());
            return redirect(route('users.index'));

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
        $user = User::find($id);
        $data['selected'] = $user->Extension()->Pluck("extension_no")->ToArray();
        $data['selected_queue'] = $user->queue()->Pluck("p_id")->ToArray();
        $data['extensions'] = $this->getExtension(implode(",",auth()->user()->Extension()->Pluck("extension_no")->ToArray()));
        $data['queue'] = auth()->user()->queue()->Pluck("queue_description","id")->ToArray();
        $data["user_id"]= auth()->id();
        return view('cms.users.edit',['data'=>$data])->with('user', $user);
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
        $user = User::find($id);
        $input = $request->all();

        if (empty($user)) {
            Flash::error('User not found');
            return redirect(route('nusers.index'));
        }

        $user->name = $input['name'];
        $user->updated_at = date('Y-m-d h:i:s');
        $user->mobile = $input['mobile'];
        $user->did_no = $input['did_no'];
        $user->status = $input['status'];
        $user_id = $user->id;

        $user->Extension()->delete();
        $extension = new Extension();
        foreach ($input['extension'] as $ext) {
            $extension->create(['user_id' => $user_id, 'extension_no' => $ext]);
        }

        $user->queue()->delete();
        foreach ($input['queue'] as $q) {
            $queue = Queue::find($q);
            $new_queue= $queue->replicate();
            $new_queue->p_id=$queue->id;
            $new_queue->user_id=$user_id;
            $new_queue->save();
        }
        $user->update();
        return redirect(route('users.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if (empty($user)) {
            Flash::error('User not found');
            return redirect(route('users.index'));
        }
        $user->delete();

        Flash::success('User deleted successfully.');

        return redirect(route('users.index'));
    }

    protected function getExtension($selected=array()){

        $data = DB::connection('mysql4')->table('devices')
            ->select(DB::raw('id,description'))->whereRaw("id in (".$selected.")")->get()->toArray();

        $data = json_decode(json_encode($data), true);

        foreach ($data as $item) {
            $temp_ext[$item['id']]=$item['description'];
        }
        return $temp_ext;
    }


}
