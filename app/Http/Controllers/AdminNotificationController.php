<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Helper;

class AdminNotificationController extends Controller {

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show() {
        $admin_notification = DB::table('admin_notification')->get();
        return view('adminnotification/adminnotificationList', ['admin_notification' => $admin_notification]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request) {
        $id = $request->id;
        $Mode = $request->mode;
        if($Mode === 'ON'){
            $Change_Mode = 'OFF';
        }else if($Mode === 'OFF'){
            $Change_Mode = 'ON';
        }
        
        $surcharge_amount_update = DB::table('admin_notification')->where('id', $id)->update([
            'notification_mode' => $Change_Mode,
            'updated_date' => Helper::get_curr_datetime()
        ]);
        
        if($surcharge_amount_update){
            return 1;
        }
       return 0;
    }

}
