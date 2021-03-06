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


class AdminRefundController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        //  $this->middleware('auth');
        //$this->user = $guard->user();
    }

    public function RefundListAdmin() {
        return view('refund/refundList');
    }


    public function AjaxRefundsList($order_by = "id", $sort_order = "asc", $search = "all", $offset = 0) {
        /* Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
         */

        $aColumns = array('id','name','bank_name','bank_account_number','bank_routing_number', 'generated_booking_id', 'booking_date', 'refund_amount', 'refund_amount_status', 'upload_bank_receipt_status');
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
                      booking_refund.id,
                      users.firstname,
                      users.lastname,
                      booking.generated_booking_id,
                      booking.booking_date,
                      booking_refund.refund_amount,
                      booking_refund.refund_amount_status,
                      booking_refund.upload_bank_receipt_status,
                      bank_details.bank_name,
                      bank_details.bank_account_number,
                      bank_details.bank_routing_number
                  FROM booking_refund 
                  LEFT JOIN users ON users.id = booking_refund.users_id 
                  LEFT JOIN booking ON booking.id = booking_refund.booking_id
                  LEFT JOIN bank_details ON bank_details.users_id = booking_refund.users_id
                  WHERE booking_refund.booking_refund_for = 'Booking_cancel' ";

        if ($SearchType == 'ORLIKE') {
            $likeStr = Helper::or_like_search($data);
        }
        if ($SearchType == 'ANDLIKE') {
            $likeStr = Helper::and_like_search($data);
        }

        if ($likeStr) {
            $query .= ' AND ' . $likeStr;
        }

        $query .= ' order by booking_refund.refund_amount_status, ' . $order_by . ' ' . $sort_order;
        $query .= ' limit ' . $limit . ' OFFSET ' . $offset;

        $result = DB::select($query);
        
        /*echo '<pre>';
        print_r($result); exit;*/

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

                    $row[] = $result_row->id;
                    $row[] = $result_row->firstname.' '.$result_row->lastname;
                    $row[] = $result_row->bank_name;
                    $row[] = $result_row->bank_account_number;
                    $row[] = $result_row->bank_routing_number;
                    $row[] = $result_row->generated_booking_id;
                    $row[] = $result_row->booking_date;
                    $row[] = $result_row->refund_amount;
                    $row[] = $result_row->refund_amount_status;
                    $row[] = $result_row->upload_bank_receipt_status;
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
                    if ($key == 'name' && !empty($val)) {
                        $QueryStr[$i]['Field'] = 'users.firstname';
                        $QueryStr[$i]['Value'] = $val;
                        $QueryStr[$i]['Operator'] = 'LIKE';
                    }
                    if ($key == 'generated_booking_id' && !empty($val)) {
                        $QueryStr[$i]['Field'] = 'booking.generated_booking_id';
                        $QueryStr[$i]['Value'] = $val;
                        $QueryStr[$i]['Operator'] = 'LIKE';
                    }
                    if ($key == 'refund_amount_status' && !empty($val)) {
                        $QueryStr[$i]['Field'] = 'booking_refund.refund_amount_status';
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
                      booking_refund.id,
                      users.firstname,
                      users.lastname,
                      booking.generated_booking_id,
                      booking.booking_date,
                      booking_refund.refund_amount,
                      booking_refund.refund_amount_status,
                      booking_refund.upload_bank_receipt_status
                  FROM booking_refund 
                  LEFT JOIN users ON users.id = booking_refund.users_id 
                  LEFT JOIN booking ON booking.id = booking_refund.booking_id";

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
    public function AjaxBankReceiptStatus(Request $request) {
        $id = $request->id;
        $status = $request->status;
        $mode = $request->mode;

        //Code to check if the id from the url exists in the table or not starts here. 
        //If id does not exists, redirect it to 404 page.
        $id_exists = Helper::check_id_exists($id, 'booking_refund');
        if (!$id_exists) {
            return view('error.404');
        }

        //Code to check id ends here
        $Update_status = DB::update('update booking_refund set upload_bank_receipt_status = "' . $status . '" Where id IN (' . $id . ')');

        if (count($Update_status) == 1) {
            echo count($Update_status);
        } else {
            echo 0;
        }
        exit;
    }

    public function receiptGallery($id) {
        $receipt = DB::table('bank_receipt')->where('booking_refund_id', '=', $id)->get();
        return view('refund/refundreceipt', ['receipt' => $receipt, 'booking_refund_id' => $id]);
    }

    public function Savereceipt(Request $request, $booking_refund_id) {
        if ($request->hasFile('uploaded_receipt')) {
            $UserId = $request->session()->get('userData');
            $user_id = $UserId->id;

            $files = $request->file('uploaded_receipt');
            foreach ($files as $file) {
                $last_inserted_id = $booking_refund_id;
                $filename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $receipt = date('His') . $filename;
                $destinationPath = public_path('uploads/bank_receipt');
                $file->move($destinationPath, $receipt);

                $bank_receipt = DB::table('bank_receipt')->insert(
                        [
                            'booking_refund_id' => $booking_refund_id,
                            'uploaded_receipt' => $receipt,
                            'receipt_for' => 'Refund',
                            'created_date' => Helper::get_curr_datetime(),
                            'created_by' => $user_id
                        ]
                );
            }
        }
        return redirect('refund/receiptGallery/' . $booking_refund_id);
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

    public function RefundAmountStatusAjaxSource(Request $request) {
        $id = $request->id;
        $status = $request->refund_amount_status;
        $mode = $request->mode;
        
        $update = DB::table('booking_refund')->where('id', $id)->update([
            'refund_amount_status' => $request->refund_amount_status
        ]);
        $booking_refund = DB::table('booking_refund')->where('id',$id)->first();

        if ($update) {
            $notification = DB::table('notification')
                ->join('user_notification', 'user_notification.id', '=', 'notification.push_notification_id')
                ->Where('notification.users_id', $booking_refund->users_id)
                ->get();
            if(isset($notification[11]) && $notification[11]->notification_mode == "ON"){
                $deviceDetails = DeviceMaster::Where('users_id', $booking_refund->users_id)->where('is_login', 'Yes')->first();
                if(count($deviceDetails) > 0){
                    $data = [
                        'notification' => $booking_refund->booking_id,
                        'type' => "booking_refund",
                        'message'=> 'Your cancelled booking amount hase been refunded by admin'
                    ];
                    //$message_title = "Booking refund \r\nYour cancelled booking amount hase been refunded by admin";
                    $message_title = "Booking refund \r\nYou have received a refund due to the cancellation, we apologize.";

                    $AmazonConfigs = config('aws');
                    $aws = new SnsClient([
                        'region' => 'us-west-1',
                        'version' => '2010-03-31',
                        'credentials' => CredentialProvider::env()
                    ]);
                    $gcm_arn = $deviceDetails['gcm_arn'];
                    $endpointAtt = $aws->getEndpointAttributes(array('EndpointArn' => $gcm_arn));

                    if (!empty($endpointAtt) && $endpointAtt['Attributes']['Enabled'] == 'true') {
                        DeviceMaster::sendPushNotification($gcm_arn, $message_title, $data);
                    } else if (!empty($endpointAtt) && $endpointAtt['Attributes']['Enabled'] == 'false') {
                        $del_device_master = DB::delete('DELETE FROM `device_master` WHERE gcm_arn = "' . $gcm_arn . '"');
                    }

                }
            }
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
