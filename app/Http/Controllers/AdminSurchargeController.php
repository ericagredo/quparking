<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Helper;

class AdminSurchargeController extends Controller {

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit() {
        $surcharge_amount = DB::table('surcharge_amount')->first();
        return view('surchargeamount/editsurchargeamount', ['surcharge_amount' => $surcharge_amount]);
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
            'amount_before_half_min' => 'required',
            'amount_after_half_min' => 'required',
            'amount_per_hour' => 'required',
        ]);

        $UserId = $request->session()->get('userData');
        $user_id = $UserId->id;

        $surcharge_amount = DB::table('surcharge_amount')->first();
        if (count($surcharge_amount) > 0) {
            $surcharge_amount_update = DB::table('surcharge_amount')->update([
                'amount_before_half_min' => $request->get('amount_before_half_min'),
                'amount_after_half_min' => $request->get('amount_after_half_min'),
                'amount_per_hour' => $request->get('amount_per_hour'),
                'updated_date' => Helper::get_curr_datetime(),
                'updated_by' => $user_id,
            ]);
        } else {
            DB::table('surcharge_amount')->insert(
                    [
                        'amount_before_half_min' => $request->amount_before_half_min,
                        'amount_after_half_min' => $request->amount_after_half_min,
                        'amount_per_hour' => $request->amount_per_hour,
                        'created_date' => Helper::get_curr_datetime(),
                        'created_by' => $user_id,
                    ]
            );
        }
        $message = 'Record save successfully';
        return redirect('surchargeamount/editsurchargeamount')->with('success', $message);
    }

}
