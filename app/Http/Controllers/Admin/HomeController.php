<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Auth;
use Hash;


class HomeController extends Controller
{	
	public function showChangePassword()
	{
		return view('admin.changepassword');
	}
	
	public function resetPassword(Request $request)
	{
		$this->validate($request, [
			'oldpassword' => 'required',
			'password' => 'required|confirmed',
		]);
		
		$oldpassword = Input::get('oldpassword');
		$password = Input::get('password');
		
		$user = Auth::guard('admin')->user();
		
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
}
