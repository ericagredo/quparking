<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Helper;

class AdminGeneralSettingsController extends Controller {

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit() {
        $general_settings = DB::table('general_settings')->first();
        return view('generalsettings/editgeneralsettings', ['general_settings' => $general_settings]);
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
            'cancellation_fee' => 'required',
            'commission_amount' => 'required',
            'distance_of_miles' => 'required',
            'discount_amount' => 'required',
            'penalty_amount' => 'required',
        ]);

        $UserId = $request->session()->get('userData');
        $user_id = $UserId->id;

        $surcharge_amount = DB::table('general_settings')->first();
        if (count($surcharge_amount) > 0) {
            $surcharge_amount_update = DB::table('general_settings')->update([
                'cancellation_fee' => $request->get('cancellation_fee'),
                'commission_amount' => $request->get('commission_amount'),
                'distance_of_miles' => $request->get('distance_of_miles'),
                'discount_amount' => $request->get('discount_amount'),
                'penalty_amount' => $request->get('penalty_amount'),
                'updated_date' => Helper::get_curr_datetime(),
                'updated_by' => $user_id,
            ]);
        } else {
            DB::table('general_settings')->insert(
                [
                    'cancellation_fee' => $request->cancellation_fee,
                    'commission_amount' => $request->commission_amount,
                    'created_date' => Helper::get_curr_datetime(),
                    'created_by' => $user_id,
                ]
            );
        }
        $message = 'Record save successfully';
        return redirect('generalsettings/editgeneralsettings')->with('success', $message);
    }



}
