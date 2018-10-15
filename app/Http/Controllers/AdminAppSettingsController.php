<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Helper;

class AdminAppSettingsController extends Controller {

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit() {
        $app_setting = DB::table('app_setting')->first();
        return view('appsettings/editappsettings', ['app_setting' => $app_setting]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request) {

        $this->validate($request, [
            'app_name' => 'required|max:255',
            'admin_users' => 'required',
        ]);

        $UserId = $request->session()->get('userData');
        $user_id = $UserId->id;

        $app_setting = DB::table('app_setting')->first();
        if (count($app_setting) > 0) {
            $imageName = $app_setting->app_logo;
            if ($request->app_logo != '') {
                $imageName = time() . '.' . $request->app_logo->getClientOriginalExtension();
                $request->app_logo->move(public_path('uploads/app_logo_images'), $imageName);
            }
            
            $app_setting_update = DB::table('app_setting')->update([
                'app_name' => $request->get('app_name'),
                'app_logo' => $imageName,
                'tbl_adminuser_id' => $request->admin_users,
                'updated_date' => Helper::get_curr_datetime(),
                'updated_by' => $user_id,
            ]);
        } else {
            $imageName = '';
            if ($request->app_logo != '') {
                $imageName = time() . '.' . $request->app_logo->getClientOriginalExtension();
                $request->app_logo->move(public_path('uploads/app_logo_images'), $imageName);
            }
        
            DB::table('app_setting')->insert(
                    [
                        'app_name' => $request->app_name,
                        'app_logo' => $imageName,
                        'tbl_adminuser_id' => $request->admin_users,
                        'created_date' => Helper::get_curr_datetime(),
                        'created_by' => $user_id,
                    ]
            );
        }
        $message = 'Record save successfully';
        return redirect('appsettings/editappsettings')->with('success', $message);
    }

}
