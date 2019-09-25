<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CreateSubUsersRequest;
use App\Http\Requests\UpdateSubUsersRequest;
use Illuminate\Http\Request;

//User datatable
use App\DataTables\UserDataTable;

//Validation
use Illuminate\Support\Facades\Validator;

//Repository
use App\Repositories\SubUsersRepository;
//Models
use App\Models\User;
use App\Models\Permission;
use App\Models\Extension;

use Flash;
use DB;
use Response;
use Auth;
use Hash;


class UserController extends AppBaseController
{
    /** @var  UserRepository */
    private $userRepository;
    private $temp_ext;

    public function __construct(SubUsersRepository $userRepo)
    {
        $this->userRepository = $userRepo;
    }

    /**
     * Display a listing of the Package.
     *
     * @param PackageDataTable $packageDataTable
     * @return Response
     */
    public function index(UserDataTable $subUserDataTable)
    {
        $extensions = DB::table('extensions')
            ->leftjoin("asterisk.users u", 'extension_no', '=', 'u.extension')
            ->where("user_id","=",Auth::id());
        return $subUserDataTable->render('admin.users.index')->with("extensions",$extensions);
    }
    /*/
    public function index(){
        return view('admin.users.index');
    }

    /**
     * Show the form for creating a new User.
     *
     * @return Response
     */
    public function create()
    {
        //$data['selected'] = array();  // Auth::User()->Extension()->Pluck("extension_no")->ToArray();
        $data['data'] = $this->getExtension();
        return view('admin.users.create',array("data"=>$data));
    }

    public function getExtension($selected=array()){
        $where = "extension_no not in (\"". (count($selected)>0? implode('", "',$selected):0) ."\")";

        $ext = DB::connection('mysql')->table('extensions')->select(DB::raw('extension_no'))
            ->whereRaw($where)
            ->get()
            ->toArray();
        $ext = json_decode(json_encode($ext), true);

        $this->temp_ext = array();
        foreach ($ext as $item) {
            $this->temp_ext[]=$item['extension_no'];
        }
        $ext = implode(',',$this->temp_ext);

        $this->temp_ext = array();
        $where = "user not in ($ext)";
        //if($ext!=0)
        //$data = DB::connection('mysql4')->table('devices')->select(DB::raw('id,description'))->whereRaw($where)->get()->toArray();
        //else
        $data = DB::connection('mysql4')->table('users')->select(DB::raw('extension,name'))->get()->toArray();

        $data = json_decode(json_encode($data), true);

        foreach ($data as $item) {
            $this->temp_ext[$item['extension']]=$item['name'];
        }
        return $this->temp_ext;
    }

    /**
     * Change password form for user
     * @param User id
     * @return view
     */


    public function showChangePassowrdForm($id)
    {
        return view("admin.users.passwords.reset")->with("id",$id);
    }



    public function changePassowrd(Request $request){

        $inputs = $request->all();
        Validator::make($inputs, [
            'email' => 'required|email|max:255|unique:admins',
            'password' => 'required|min:6|confirmed',
        ]);

        try {

            $user = User::whereRaw("id=" . $inputs['user_id'] . " and email='" . $inputs['email'] . "'")->first();
            $user->password = Hash::make($inputs['password']);
            $user->save();
            return redirect(route("nusers.index"))->with("success","Password Successfully Changed");
        }
        catch(\Exception $exception)
        {
            return back()->with("error",$exception->getMessage());
        }

    }

    /**
     * Store a newly created User in storage.
     *
     * @param CreateUserRequest $request
     *
     * @return Response
     */
    public function store(CreateSubUsersRequest $request)
    {

        $input = $request->all();
        $extension = new Extension();

        $user = new User();
        $user->name = $input['name'];
        $user->created_at=date('Y-m-d h:i:s');
        $user->email=$input['email'];
        $user->mobile=$input['mobile'];
        $user->password=Hash::make($input['password']);
        $user->did_no=$input['did_no'];
        $user->status=$input['status'];
        $user->save();
        $user_id=$user->id;

        $permissions=Permission::all()->Pluck("slug")->ToArray();
        foreach ($permissions as $permission){
            $user->givePermissionsTo($permission);
        }

        foreach ($input['extension'] as $ext) {
            $extension->create(['user_id' => $user_id, 'extension_no' => $ext]);
        }

        Flash::success('User saved successfully.');

        return redirect(route('nusers.index'));
    }

    /**
     * Display the specified User.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $user = $this->userRepository->findWithoutFail($id);

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('nusers.index'));
        }

        return view('admin.users.show')->with('user', $user);
    }

    /**
     * Show the form for editing the specified User.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $user = $this->userRepository->findWithoutFail($id);
        $data['selected'] = $user->Extension()->Pluck("extension_no")->ToArray();
        $data['data'] = $this->getExtension($data['selected']);
        return view('admin.users.edit',['data'=>$data])->with('user', $user);
    }

    /**
     * Update the specified User in storage.
     *
     * @param  int              $id
     * @param UpdateSubUsersRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateSubUsersRequest $request)
    {

        $user = $this->userRepository->findWithoutFail($id);
        $input = $request->all();

        if (empty($user)) {
            Flash::error('User not found');
            return redirect(route('nusers.index'));
        }

        $user->name = $input['name'];
        $user->did_no = $input['did_no'];
        $user ->mobile = $input['mobile'];
        $user->status = $input['status'];
        $user->Extension()->delete();
        foreach ($input['extension'] as $ext) {
            $user->Extension()->create(['extension_no'=> $ext]);
        }
        $user->update();
        return redirect(route('nusers.index'));
    }

    /**
     * Remove the specified User from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $user = $this->userRepository->findWithoutFail($id);

        if (empty($user)) {
            Flash::error('User not found');
            return redirect(route('nusers.index'));
        }
        $user->delete();

        Flash::success('User deleted successfully.');

        return redirect(route('nusers.index'));
    }
}
