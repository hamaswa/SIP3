<?php

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use App\Models\Admin;
class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->SeedAdmin();
        $this->SeedPermission("sub_users","Sub Users");
        $this->SeedPermission("user_add","Add Users");
        $this->SeedPermission("user_edit","Edit Users");
        $this->SeedPermission("user_delete","Delete Users");
        $this->SeedPermission("user_permission","Manage Permissions");
        $this->SeedPermission("dashboard_view","View Dashboard");
        $this->SeedPermission("view_combined","View Combined Report");
        $this->SeedPermission("download_combined","Download Combined Report");
        $this->SeedPermission("view_distribution","View Dsitribution Details");
        $this->SeedPermission("download_distribution","Download Dsitribution Details");
        $this->SeedPermission("view_outgoing","View Outgoing Call Details");
        $this->SeedPermission("download_outgoing","Download outgoing call details");
        $this->SeedPermission("view_incoming","View Incoming Call details");
        $this->SeedPermission("download_incoming","Download Incoming Call details");
        $this->SeedPermission("view_queue_status","View Queue Stats");
        $this->SeedPermission("view_callback","View Callback details");
        $this->SeedPermission("view_realtime","View Real Time Extensions");
        $this->SeedPermission("real_time_ext","Realtime Extension");
        $this->SeedPermission("realtime_ext_simple","Realtime Extension Simple");
        $this->SeedPermission("realtime_ext_advance","Realtime Extension Advance");

    }

    public function SeedAdmin(){
        $admin = new Admin();
        $admin->name="Administrator";
        $admin->email="support@nautilus-network.com";
        $admin->password=Hash::make("boy2cat4");
        $admin->save();
    }
    public function SeedUser($name,$email,$username,$pass,$role){
        $role = Role::where('slug',$role)->first();
        $user = new User();
        $user->name = $name;
        $user->username = $username;
        $user->email = $email;
        $user->password = bcrypt($pass);
        $user->save();
        $user->roles()->attach($role);
    }

    public function SeedPermission($key,$val)
    {
        $add_dep = new Permission();
        $add_dep->slug = $key;
        $add_dep->name = $val;
        $add_dep->save();


    }
}
