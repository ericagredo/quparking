<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\validate;
use Illuminate\Support\Facades\Session;
use App\Http\Helper;
use App\Models\EmailTemplate;
use Mail;

use App\Models\DeviceMaster;
use Aws\Credentials\CredentialProvider;


class AdminMonthlyReportsController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        //  $this->middleware('auth');
        //$this->user = $guard->user();
    }

    public function reportListAdmin() {
        return view('report/reportList');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show() {
        $parking_spot = DB::table('parking_spot')->get();
        return view('parkingspot/parkingspotList', ['parking_spot' => $parking_spot]);
    }

    public function monthlyreport(){

        $query = "SELECT 
                        parking_spot.users_id,
                        DATE_FORMAT(booking.created_date,'%b-%Y') as booking_month,
                        SUM(booking.paid_amount) as total_booking_amount,
                        (
                            SELECT SUM(cancellation_fee) FROM booking as b
                            WHERE b.cancelled_by = 'Host'
                            AND MONTH(b.created_date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)
                            AND b.parking_spot_id = parking_spot.id
                        ) as cancellation_fee_by_host,
                        (
                            SELECT SUM(cancellation_fee) FROM booking as b
                            WHERE b.cancelled_by = 'User' 
                            AND MONTH(b.created_date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)
                            AND b.parking_spot_id = parking_spot.id
                        ) as cancellation_fee_by_renter,
                        (
                            SELECT SUM(surcharge_amount) FROM booking as b
                            WHERE b.is_surcharge_paid = 'Yes' 
                            AND MONTH(b.created_date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)
                            AND b.parking_spot_id = parking_spot.id
                        ) as surcharge_amount,
                        (
                            SELECT SUM(br.refund_amount) FROM booking as b
                            LEFT JOIN booking_refund as br ON br.booking_id = b.id
                            WHERE br.refund_amount_status = 'Funded' 
                            AND MONTH(b.created_date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)
                            AND b.parking_spot_id = parking_spot.id
                        ) as refunded_amount
                  FROM parking_spot 
                  LEFT JOIN users ON users.id = parking_spot.users_id
                  LEFT JOIN booking ON booking.parking_spot_id = parking_spot.id
                  WHERE MONTH(booking.created_date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)
                  GROUP BY parking_spot.users_id
                  ";
        $result = DB::select($query);

        if( isset($result) && !empty($result) ){
            foreach ($result as $single){
                $admin_commission = DB::table('general_settings')
                    ->select('commission_amount')
                    ->first();
                $Parking_spot_details = DB::table('admin_fund_managment')
                    ->select('id')
                    ->where('admin_fund_managment.report_by_month', '=', $single->booking_month)
                    ->where('admin_fund_managment.users_id', '=', $single->users_id)
                    ->first();

                $total_booking_amount = $single->total_booking_amount?$single->total_booking_amount:0;
                $cancellation_fee_by_host = $single->cancellation_fee_by_host?$single->cancellation_fee_by_host:0;
                $cancellation_fee_by_renter = $single->cancellation_fee_by_renter?$single->cancellation_fee_by_renter:0;
                $surcharge_amount = $single->surcharge_amount?$single->surcharge_amount:0;
                $refunded_amount = $single->refunded_amount?$single->refunded_amount:0;

                //$total_amount = (($total_booking_amount + $surcharge_amount ) - ($cancellation_fee_by_host + $cancellation_fee_by_renter)) - $refunded_amount;
                $total_amount = (($total_booking_amount ) - ($cancellation_fee_by_host + $cancellation_fee_by_renter)) - $refunded_amount;
                $admin_commission_amount = (($total_amount / 100) * $admin_commission->commission_amount);

                if(count($Parking_spot_details) == 0){
                    $admin_fund_managment = DB::table('admin_fund_managment')
                        ->insertGetId([
                            'users_id' => $single->users_id,
                            'report_by_month' => $single->booking_month,
                            'total_booking_amount' => $total_booking_amount,
                            'cancellation_fee_by_host' => $cancellation_fee_by_host,
                            'cancellation_fee_by_renter' => $cancellation_fee_by_renter,
                            'surcharge_amount' => $surcharge_amount,
                            'refunded_amount' => $refunded_amount,
                            'admin_commission_amount' => $admin_commission_amount,
                            'total_amount' => $total_amount - $admin_commission_amount,
                            'upload_bank_receipt' => '',
                            'payment_status' => 'Pending',
                            'created_date' => Helper::get_curr_datetime(),
                            'updated_date' => ''
                        ]);
                }
            }

        }
    }


    public function AjaxReportsList($order_by = "id", $sort_order = "asc", $search = "all", $offset = 0) {
        /* Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
         */
        $aColumns = array('id', 'name', 'contact_number','email', 'bank_name','bank_account_number','bank_routing_number','total_booking_amount','cancellation_fee_by_host','cancellation_fee_by_renter','surcharge_amount','refunded_amount','admin_commission_amount','total_amount', 'payment_status', 'report_by_month');
        $grid_data = Helper::get_search_data($aColumns);


        $sort_order = $grid_data['sort_order'];
        $order_by = $grid_data['order_by'];
        if ($grid_data['sort_order'] == '' && $grid_data['order_by'] == '') {
            $order_by = 'id';
            $sort_order = 'DESC';
        }

        /*
         * Paging
         */
        $limit = $grid_data['per_page'];
        $offset = $grid_data['offset'];


        $SearchType = $grid_data['SearchType'];
        $search_data = $grid_data['search_data'];


        $data = $this->trim_serach_data($search_data, $SearchType);

        $query = "SELECT 
                        admin_fund_managment.id,
                        admin_fund_managment.users_id,
                        users.firstname,
                        users.lastname,
                        users.contact_number,
                        users.email,
                        admin_fund_managment.payment_status,
                        admin_fund_managment.report_by_month,
                        bank_details.bank_name,
                        bank_details.bank_account_number,
                        bank_details.bank_routing_number,
                        admin_fund_managment.total_booking_amount,
                        admin_fund_managment.cancellation_fee_by_host,
                        admin_fund_managment.cancellation_fee_by_renter,
                        admin_fund_managment.surcharge_amount,
                        admin_fund_managment.refunded_amount,
                        admin_fund_managment.admin_commission_amount,
                        admin_fund_managment.total_amount
                  FROM admin_fund_managment 
                  LEFT JOIN users ON users.id = admin_fund_managment.users_id
                  LEFT JOIN bank_details ON bank_details.users_id = admin_fund_managment.users_id
                  ";

        if ($SearchType == 'ORLIKE') {
            $likeStr = Helper::or_like_search($data);
        }
        if ($SearchType == 'ANDLIKE') {
            $likeStr = Helper::and_like_search($data);
        }

        if ($likeStr) {
            $query .= ' Where ' . $likeStr;
        }


        $query .= ' order by ' . $order_by . ' ' . $sort_order;
        $query .= ' limit ' . $limit . ' OFFSET ' . $offset;


        $result = DB::select($query);

        /**/

        $data = array();
        if (count($result) > 0) {
            $data['result'] = $result;
            $data['totalRecord'] = $this->count_all_users_grid($search_data, $SearchType);
        }

        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => 0,
            "iTotalDisplayRecords" => 0,
            "aaData" => array()
        );
        if (isset($data) && !empty($data)) {
            if (isset($data['result']) && !empty($data['result'])) {
                $output = array(
                    "sEcho" => intval($_GET['sEcho']),
                    "iTotalRecords" => $data['totalRecord'],
                    "iTotalDisplayRecords" => $data['totalRecord'],
                    "aaData" => array()
                );
                foreach ($data['result'] AS $result_row) {
                    $row = array();
                    /*if ($result_row->verification_status == 'Yes') {
                        $verification_status = 'Verified';
                    } else if ($result_row->verification_status == 'No') {
                        $verification_status = 'Verification Pending';
                    }*/

                    $row[] = $result_row->id;
                    $row[] = $result_row->firstname.' '.$result_row->lastname;
                    $row[] = $result_row->contact_number;
                    $row[] = $result_row->email;
                    $row[] = $result_row->bank_name;
                    $row[] = $result_row->bank_account_number;
                    $row[] = $result_row->bank_routing_number;
                    $row[] = $result_row->total_booking_amount;
                    $row[] = $result_row->cancellation_fee_by_host;
                    $row[] = $result_row->cancellation_fee_by_renter;
                    $row[] = $result_row->surcharge_amount;
                    $row[] = $result_row->refunded_amount;
                    $row[] = $result_row->admin_commission_amount;
                    $row[] = $result_row->total_amount;
                    $row[] = $result_row->payment_status;
                    $row[] = $result_row->report_by_month;
                    $row[] = array();
                    $output['aaData'][] = $row;
                }
            }
        }
        //print_r(json_encode($output));exit;
        echo json_encode($output);
    }

    /* =============== Start : Trim search function ======================= */

    public function trim_serach_data($search_data, $SearchType) {
        $QueryStr = array();

        if (!empty($search_data)) {
            if ($SearchType == 'ANDLIKE') {
                $i = 0;
                foreach ($search_data as $key => $val) {
                    if ($key == 'firstname' && !empty($val)) {
                        $QueryStr[$i]['Field'] = 'users.firstname';
                        $QueryStr[$i]['Value'] = $val;
                        $QueryStr[$i]['Operator'] = 'LIKE';
                    }
                    if ($key == 'contact_number' && !empty($val)) {
                        $QueryStr[$i]['Field'] = 'users.contact_number';
                        $QueryStr[$i]['Value'] = $val;
                        $QueryStr[$i]['Operator'] = 'LIKE';
                    }

                    if ($key == 'payment_status' && !empty($val)) {
                        $QueryStr[$i]['Field'] = 'admin_fund_managment.payment_status';
                        $QueryStr[$i]['Value'] = $val;
                        $QueryStr[$i]['Operator'] = '=';

                    }
                    if ($key == 'report_by_month' && !empty($val)) {
                        $QueryStr[$i]['Field'] = 'admin_fund_managment.report_by_month';
                        $QueryStr[$i]['Value'] = $val;
                        $QueryStr[$i]['Operator'] = '=';
                    }
                    $i++;
                }
            }
        }
        return $QueryStr;
    }

    // =========== Start :  Count all Record in grid data =========//
    public function count_all_users_grid($search_data, $SearchType) {
        $data = $this->trim_serach_data($search_data, $SearchType);

        $query = "SELECT 
                        admin_fund_managment.id,
                        admin_fund_managment.users_id,
                        users.firstname,
                        users.lastname,
                        users.contact_number,
                        admin_fund_managment.payment_status,
                        admin_fund_managment.report_by_month
                  FROM admin_fund_managment 
                  LEFT JOIN users ON users.id = admin_fund_managment.users_id";

        if ($SearchType == 'ORLIKE') {
            $likeStr = Helper::or_like_search($data);
        }
        if ($SearchType == 'ANDLIKE') {
            $likeStr = Helper::and_like_search($data);
        }

        if ($likeStr) {
            $query .= ' Where ' . $likeStr;
        }

        $result = DB::select($query);
        if (count($result) > 0) {
            return count($result);
        }
        return 0;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request) {
        $id = $request->id;

        //Code to check if the id from the url exists in the table or not starts here. 
        //If id does not exists, redirect it to 404 page.
        $id_exists = Helper::check_id_exists($id, 'parking_spot');
        if (!$id_exists) {
            return view('error.404');
        }
        
        $fetchMuiltiimagedata = DB::select("select * from parking_spot_images Where parking_spot_id In($id)");
       
        foreach ($fetchMuiltiimagedata as $gallery) {
            $removegalleryimage = $gallery->uploaded_image;
            if (file_exists(public_path('uploads/parkingspot_images/'.$removegalleryimage))) {
                unlink(public_path('uploads/parkingspot_images/'.$removegalleryimage));
            }

        }

        $del_parking_spot_image = DB::delete('DELETE FROM `parking_spot_images` WHERE parking_spot_id IN (' . $id . ')');
        $del_parking_spot = DB::delete('DELETE FROM `parking_spot` WHERE id IN (' . $id . ')');

        if ($del_parking_spot) {
            echo '1';
        } else {
            echo '0';
        }
        exit;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function activeInactiveparkingspot(Request $request) {
        $id = $request->id;
        $status = $request->status;
        $mode = $request->mode;

        //Code to check if the id from the url exists in the table or not starts here. 
        //If id does not exists, redirect it to 404 page.
        $id_exists = Helper::check_id_exists($id, 'parking_spot');
        if (!$id_exists) {
            return view('error.404');
        }

        //Code to check id ends here
        $Update_status = DB::update('update parking_spot set status = "' . $status . '" Where id IN (' . $id . ')');

        if (count($Update_status) == 1) {
            echo count($Update_status);
        } else {
            echo 0;
        }
        exit;
    }

    public function PayAmountStatusAjaxSource(Request $request) {
        $id = $request->id;
        $status = $request->payment_status;
        $mode = $request->mode;

        $update = DB::table('admin_fund_managment')->where('id', $id)->update([
            'payment_status' => $request->payment_status
        ]);

        if ($update) {
            /* ----------- Start : Send verifiaction code mail to user --------- */
            /*$this->sendVerificationstatus($id);*/
            /* ----------- Start : Send verifiaction code mail to user --------- */
            echo 1;
        } else {
            //$parking_spot_details = DB::table('parking_spot')->select('verification_code')->where('id', $id)->where('verification_status', 'Yes')->first();
            echo 0;
        }
        exit;
    }

    public function receiptGallery($id) {
        $receipt = DB::table('bank_receipt')->where('admin_fund_managment_id', '=', $id)->get();
        return view('report/reportreceipt', ['receipt' => $receipt, 'admin_fund_managment_id' => $id]);
    }


    public function Savereceiptreports(Request $request, $admin_fund_managment_id) {
        if ($request->hasFile('uploaded_receipt')) {
            $UserId = $request->session()->get('userData');
            $user_id = $UserId->id;

            $files = $request->file('uploaded_receipt');
            foreach ($files as $file) {
                $last_inserted_id = $admin_fund_managment_id;
                $filename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $receipt = date('His') . $filename;
                $destinationPath = public_path('uploads/bank_receipt');
                $file->move($destinationPath, $receipt);

                $bank_receipt = DB::table('bank_receipt')->insert(
                    [
                        'admin_fund_managment_id' => $admin_fund_managment_id,
                        'uploaded_receipt' => $receipt,
                        'receipt_for' => 'AdminFund',
                        'created_date' => Helper::get_curr_datetime(),
                        'created_by' => $user_id
                    ]
                );
            }
        }
        return redirect('reports/receiptGallery/' . $admin_fund_managment_id);
    }

    public function deletereceiptGallery(Request $request) {
        $id = $request->id;

        //Code to check if the id from the url exists in the table or not starts here.
        //If id does not exists, redirect it to 404 page.
        $id_exists = Helper::check_id_exists($id, 'bank_receipt');
        if (!$id_exists) {
            return view('error.404');
        }
        //Code to check id ends here

        $fetchdata = DB::select("select * from bank_receipt Where id In($id)");
        $fetchReceiptName = $fetchdata[0]->uploaded_receipt;
        unlink(public_path('uploads/bank_receipt/' . $fetchReceiptName));

        $del_user = DB::delete('DELETE FROM `bank_receipt` WHERE id IN (' . $id . ')');

        if ($del_user) {
            echo '1';
        } else {
            echo '0';
        }
        exit;
    }

    public function veryfyParkingspot(Request $request) {
        $id = $request->id;
        $status = $request->status;
        $mode = $request->mode;
        
        DB::table('parking_spot')->where('id', $id)->update([
            'verification_status' => $request->status
        ]);

        if ($status == 'Yes') {
            /* ----------- Start : Send verifiaction code mail to user --------- */
            $this->sendVerificationstatus($id);
            /* ----------- Start : Send verifiaction code mail to user --------- */
            echo 1;
        } else {
            //$parking_spot_details = DB::table('parking_spot')->select('verification_code')->where('id', $id)->where('verification_status', 'Yes')->first();
            echo 0;
        }
        exit;
    }

    function sendVerificationstatus($id) {
        $parking_spot = DB::table('parking_spot')->where('id', $id)->first();
        if (count($parking_spot) > 0) {
            $generate_varification_code = $my_rand_strng = substr(str_shuffle("1234567890"), -4);
            DB::table('parking_spot')->where('id', $id)->update([
                'verification_code' => $generate_varification_code
            ]);

            $parking_spot_details = DB::table('parking_spot')->select('verification_code')->where('id', $id)->where('verification_status', 'Yes')->first();

            $user_details = DB::table('users')->select('id','email', 'firstname', 'lastname')->where('id', $parking_spot->users_id)->where('status', 'Active')->first();
            if (count($user_details) > 0) {
                $name = ucfirst($user_details->firstname) . ' ' . $user_details->lastname;
                $verification_code = $parking_spot_details->verification_code;
                $EmailTemplate = EmailTemplate::Where('id', 5)->Where('status', 'Active')->first();

                $Email_parking_spot_template = array('USERNAME' => $name, 'Email' => $user_details->email, 'VERIFICATIONCODE' => $verification_code);
                $data = array('Subject' => $EmailTemplate->subject, 'to_email' => $user_details->email);
                $message = '';
                //=== Mail Send Functionality
                Mail::send('admin.emails.parkingspotverifycode', ['Email_parking_spot_template' => $Email_parking_spot_template, 'message' => $message], function ($message) use ($data) {
                    $message->from('troodeveloper@gmail.com', $data['Subject']);
                    // $message->to('mayuri.patel@trootech.com')->subject($data['Subject']);
                    $message->to($data['to_email'])->subject($data['Subject']);
                });
                $notification = DB::table('notification')
                    ->join('user_notification', 'user_notification.id', '=', 'notification.push_notification_id')
                    ->Where('notification.users_id', $user_details->id)
                    ->get();
                if($notification[0]->notification_mode == "ON") {
                    $deviceDetails = DeviceMaster::Where('users_id', $user_details->id)->where('is_login', 'Yes')->first();
                    if (count($deviceDetails) > 0) {
                        $data = [
                            'notification' => $parking_spot->id,
                            'type' => 'Parking Spot Verification',
                            'message' => 'Parking Spot verified successfully.'
                        ];
                        $message_title = 'Admin Spot verification';

                        DeviceMaster::sendPushNotification($deviceDetails['gcm_arn'], $message_title, $data);

                    }
                }

            }
        }
    }

}
