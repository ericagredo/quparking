<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use App\Http\Requests;
use App\Http\Helper;
use App\Models\Users;
use App\Models\ParkingspotImages;
use App\Models\ParkingSpot;
use App\Models\Pricing;
use App\Models\SurchargeAmount;
use Mail;
use DateTime;

use App\Models\DeviceMaster;
use Aws\Sns\SnsClient;
use Aws\Credentials\CredentialProvider;
use Barryvdh\DomPDF\PDF as PDF;


class BraintreeController extends Controller
{

    public function pdfGenerate(Request $request, $keywordID, $range = null)
    {
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', 0);
        $data = $this->CommontopreportCode($request, $keywordID, $range = null);
        $pdf = PDF::loadView('reports.byalltopreportpdf', $data);
        // print_r($pdf);exit;
        return $pdf->download('Linkboard Intelligence - Report Top Connections Rituximab.pdf');
    }

    public function Booking_invoice(Request $request) {
        $Apikey = $request->Apikey;
        if ($Apikey == APIKEY) {
            $payment_id = $request->payment_id;
            $details = "SELECT
                          h.*,
                          b.id as booking_id,
                          b.total_time,
                          b.booking_status,
                          b.booking_date,
                          b.booking_time,
                          b.generated_booking_id,
                          b.paid_amount,
                          b.additional_credited_amount,
                          b.booking_transaction_id,
                          b.booking_start_date_time,
                          b.booking_end_date_time,
                          b.parking_spot_id as spot_id,
                          parking_spot.address as spot_address,
                          parking_spot.location as spot_location,
                          parking_spot.postal_code,
                          parking_spot.city_name,
                          country.country_name
                        FROM payment_history as h
                        LEFT JOIN booking as b ON b.booking_transaction_id = h.transaction_id
                        LEFT JOIN parking_spot ON parking_spot.id = b.parking_spot_id
                        LEFT JOIN country ON country.id = parking_spot.country_id
                        WHERE h.id = $payment_id ";
            $details = DB::Select($details);

            if(isset($details) && !empty($details)){
                ini_set('memory_limit', -1);
                ini_set('max_execution_time', 0);
                $data['data'] = $details[0];
                $pdf = \PDF::loadView('invoice',$data);
                $output = $pdf->output();
                file_put_contents("order_email.pdf", $output);
                //$pdf->save(storage_path(public_path('uploads/parkingspot_images/invoice.pdf')));
                //$invoice =  $pdf->download('invoice.pdf');
                $msg = 'Success';
                $code = 200;
                //return $invoice;
            }else{
                $msg = 'Your booking invoice is not gangrened, Please ask to admin';
                $code = 101;
                return response()->json(['message' => $msg, 'code' => $code]);
            }
        }else{
            return response()->json(['message' => 'Unauthorised Call', 'code' => 101]);
        }



        /*$destinationPath = public_path('uploads/parkingspot_images');
        $test->move($destinationPath, 'dfdsf');*/
        //return \PDF::loadFile('index')->save(public_path('uploads/parkingspot_images'))->stream('download.pdf');
    }

    public function checkout()
    {

        //$clientToken = Braintree_ClientToken::generate();
        $clientToken = \Braintree_ClientToken::generate();
        if (isset($clientToken) && !empty($clientToken)) {
            return $clientToken;
        } else {
            return '';
        }
    }

    public function checkout_post()
    {
        $userID = $_POST["userID"];
        $parking_spot_id = $_POST["parking_spot_id"];
        $amount = $_POST["amount"];
        $nonceFromTheClient = $_POST["payment_method_nonce"];
        $result = \Braintree_Transaction::sale([
            'amount' => $amount,
            'paymentMethodNonce' => $nonceFromTheClient,
            'options' => [
                'submitForSettlement' => True
            ]
        ]);

        if ($result->success || !is_null($result->transaction)) {
            $transaction = $result->transaction;
            $payment_history = DB::table('payment_history')->insertGetId(
                [
                    'users_id' => $userID,
                    'transaction_id' => $transaction->id,
                    'transaction_details' => $transaction,
                    'payment_type' => 'booking_amount',
                    'amount' => $amount,
                    'created_date' => Helper::get_curr_datetime(),
                    'status' => 'Pending'
                ]
            );
            if(isset($parking_spot_id) && !empty($parking_spot_id) && isset($userID) && !empty($userID)){
                $host_user_id = DB::table('parking_spot')->select('users_id')->Where('id', $parking_spot_id)->first();
                $tran_entry_for_renter = DB::table('transaction_history')->insertGetId([
                    'users_id' => $userID,
                    'transaction_id' => $payment_history,
                    'parking_spot_id' => $parking_spot_id,
                    'transaction_for' => 'Booking',
                    'amount' => $amount,
                    'created_date' => Helper::get_curr_datetime(),
                    'created_by' => $userID
                ]);
                $tran_entry_for_host = DB::table('transaction_history')->insertGetId([
                    'users_id' => $host_user_id->users_id,
                    'transaction_id' => $payment_history,
                    'parking_spot_id' => $parking_spot_id,
                    'transaction_for' => 'Reservation',
                    'amount' => $amount,
                    'created_date' => Helper::get_curr_datetime(),
                    'created_by' => $userID
                ]);
            }
            return $transaction->id;

        } else {
            $errorString = "";
            foreach ($result->errors->deepAll() as $error) {
                $errorString .= 'Error: ' . $error->code . ": " . $error->message . "\n";
            }
            return $errorString;
        }

    }

    public function checkout_post_surcharge()
    {
        $userID = $_POST["userID"];
        $booking_id = $_POST["booking_id"];
        $parking_spot_id = $_POST["parking_spot_id"];
        $amount = $_POST["amount"];
        $nonceFromTheClient = $_POST["payment_method_nonce"];
        $result = \Braintree_Transaction::sale([
            'amount' => $amount,
            /*'paymentMethodNonce' => 'fake-valid-nonce',*/
            'paymentMethodNonce' => $nonceFromTheClient,
            'options' => [
                'submitForSettlement' => True
            ]
        ]);

        if ($result->success || !is_null($result->transaction)) {
            $transaction = $result->transaction;
            $payment_history = DB::table('payment_history')->insertGetId(
                [
                    'users_id' => $userID,
                    'transaction_id' => $transaction->id,
                    'transaction_details' => $transaction,
                    'payment_type' => 'surcharge_amount',
                    'amount' => $amount,
                    'created_date' => Helper::get_curr_datetime(),
                    'status' => 'Complete'
                ]
            );
            if(isset($parking_spot_id) && !empty($parking_spot_id) && isset($userID) && !empty($userID)){
                $tran_entry_for_renter = DB::table('transaction_history')->insertGetId([
                    'users_id' => $userID,
                    'transaction_id' => $payment_history,
                    'parking_spot_id' => $parking_spot_id,
                    'transaction_for' => 'surcharge',
                    'amount' => $amount,
                    'created_date' => Helper::get_curr_datetime(),
                    'created_by' => $userID
                ]);
                $update = DB::table('booking')
                    ->where('id', $booking_id)
                    ->update([
                        'is_surcharge_paid' => 'Yes',
                        'surcharge_transaction_id' => $transaction->id
                    ]);

            }
            $user_id_of_spot = DB::table('parking_spot')
                ->select('parking_spot.users_id')
                ->Where('parking_spot.id', $parking_spot_id)
                ->first();
            $notification = DB::table('notification')
                ->join('user_notification', 'user_notification.id', '=', 'notification.push_notification_id')
                ->Where('notification.users_id', $user_id_of_spot->users_id)
                ->get();
            if(isset($notification[6]) && $notification[6]->notification_mode == "ON"){
                $deviceDetails = DeviceMaster::Where('users_id', $user_id_of_spot->users_id)->where('is_login', 'Yes')->first();
                if(count($deviceDetails) > 0){
                    $data = [
                        'notification' => $booking_id,
                        'type' => 'surchage_amount_paid',
                        'message'=> 'Surcharge amount is added on your reservation'
                    ];
                    //$message_title = "Surcharge Paid \r\nSurcharge amount is added on your reservation";
                    $message_title = "Surcharge Paid \r\nYou are being paid a surcharge for the Renter who has not yet exited your Parking Spot.";

                    $AmazonConfigs = config('aws');
                    $aws = new SnsClient([
                        'region' => 'us-east-1',
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
            return response()->json(['message' => 'Success', 'code' => 200], 200, [], JSON_NUMERIC_CHECK);

        } else {
            $errorString = "";
            foreach ($result->errors->deepAll() as $error) {
                $errorString .= 'Error: ' . $error->code . ": " . $error->message . "\n";
            }
            return $errorString;
        }

    }

    public function transaction_details(Request $request){


        $Apikey = $request->Apikey;
        if ($Apikey == APIKEY) {
            $limit = $request->page_limit;
            $offset = $request->page_offset;
            $userID = $request->userID;
            $date = $request->date;
            $AND = '';
            if(!empty($date)){
               $AND = " AND DATE_FORMAT(transaction_history.created_date, '%Y-%m-%d') = '$date' ";
            }

            if ($offset) {
                $offset1 = $offset + 1;
                $start = ($offset1 - 1) * $limit;
            } else {
                $start = 0;
            }

            $transaction_details = "SELECT 
                                      transaction_history.*
                                FROM transaction_history 
                                WHERE transaction_history.users_id = $userID
                                $AND
                                ORDER BY transaction_history.id DESC 
                                LIMIT $start,$limit ";
            //echo $transaction_details; exit;
            $transaction_details = DB::Select($transaction_details);

            if(count($transaction_details) > 0){
                foreach ($transaction_details as $single){
                    $single->id = !empty($single->id) ? (int) $single->id : 0;
                    $single->users_id = !empty($single->users_id) ? (int) $single->users_id : 0;
                    $single->transaction_id = !empty($single->transaction_id) ? (int) $single->transaction_id : 0;
                    $single->parking_spot_id = !empty($single->parking_spot_id) ? (int) $single->parking_spot_id : 0;
                    $single->amount = !empty($single->amount) ? (int) $single->amount : 0;

                    if($single->transaction_for == 'Refund_Booking'){
                        $details = "SELECT 
                                          b.id as booking_id,
                                          b.total_time,
                                          b.booking_status,
                                          b.booking_date,
                                          b.booking_time,
                                          b.generated_booking_id,
                                          b.paid_amount,
                                          b.cancelled_by,
                                          b.cancellation_fee,
                                          b.cancellation_date,
                                          b.parking_spot_id as spot_id,
                                          parking_spot.address as spot_address,
                                          parking_spot.location as spot_location,
                                          parking_spot.postal_code,
                                          parking_spot.city_name,
                                          country.country_name,
                                          r.id as booking_refund_id,
                                          r.refund_amount,
                                          r.refund_amount_status
                                        FROM booking_refund as r
                                        LEFT JOIN booking as b ON b.id = r.booking_id
                                        LEFT JOIN parking_spot ON parking_spot.id = b.parking_spot_id
                                        LEFT JOIN country ON country.id = parking_spot.country_id
                                        WHERE r.id = $single->transaction_id";
                        $details = DB::Select($details);
                        if(count($details) > 0){
                            $receipt = DB::table('bank_receipt')->select('uploaded_receipt')->Where('booking_refund_id', $details[0]->booking_refund_id)->get();
                            $details[0]->bank_receipt = '';
                            $details[0]->invoice = '';
                            if(isset($receipt) && !empty($receipt) && count($receipt) >0){
                                $details[0]->bank_receipt = !empty($receipt[0]->uploaded_receipt)? asset('uploads/bank_receipt/'.$receipt[0]->uploaded_receipt): "";
                                /*foreach ($receipt as $image){
                                    if (file_exists(public_path('uploads/bank_receipt/'.$image->uploaded_receipt))) {
                                        array_push($details[0]->bank_receipt,asset('uploads/bank_receipt/'.$image->uploaded_receipt));
                                    }
                                }*/
                            }
                        }
                        if(isset($details)&&!empty($details)){
                            foreach ($details as $s){
                                $s->payment_id = 0;
                                $s->additional_credited_amount = 0;
                                $s->booking_id = !empty($s->booking_id) ? (int) $s->booking_id : 0;
                                $s->paid_amount = !empty($s->paid_amount) ? (int) $s->paid_amount : 0;
                                $s->cancellation_fee = !empty($s->cancellation_fee) ? (int) $s->cancellation_fee : 0;
                                $s->spot_id = !empty($s->spot_id) ? (int) $s->spot_id : 0;
                                $s->booking_refund_id = !empty($s->booking_refund_id) ? (int) $s->booking_refund_id : 0;
                                $s->refund_amount = !empty($s->refund_amount) ? (int) $s->refund_amount : 0;
                                $s->spot_address = !empty($s->spot_address) ? (string) $s->spot_address : '';
                                $s->spot_location = !empty($s->spot_location) ? (string) $s->spot_location : '';
                                $s->generated_booking_id = !empty($s->generated_booking_id) ? (string) $s->generated_booking_id : '';
                                $s->postal_code = !empty($s->postal_code) ? (string) $s->postal_code : '';
                                $s->city_name = !empty($s->city_name) ? (string) $s->city_name : '';
                                $s->country_name = !empty($s->country_name) ? (string) $s->country_name : '';
                            }
                        }
                    }elseif ($single->transaction_for == 'Refund_Reservation'){
                        $details = "SELECT 
                                          b.id as booking_id,
                                          b.total_time,
                                          b.booking_status,
                                          b.booking_date,
                                          b.booking_time,
                                          b.generated_booking_id,
                                          b.paid_amount,
                                          b.cancelled_by,
                                          b.cancellation_fee,
                                          b.cancellation_date,
                                          b.parking_spot_id as spot_id,
                                          parking_spot.address as spot_address,
                                          parking_spot.location as spot_location,
                                          parking_spot.postal_code,
                                          parking_spot.city_name,
                                          country.country_name,
                                          r.id as booking_refund_id,
                                          r.refund_amount
                                        FROM booking_refund as r
                                        LEFT JOIN booking as b ON b.id = r.booking_id
                                        LEFT JOIN parking_spot ON parking_spot.id = b.parking_spot_id
                                        LEFT JOIN country ON country.id = parking_spot.country_id
                                        WHERE r.id = $single->transaction_id";
                        $details = DB::Select($details);
                        if(isset($details)&&!empty($details)){
                            $details[0]->bank_receipt = '';
                            $details[0]->invoice = '';
                            foreach ($details as $srr){
                                $srr->refund_amount_status = "";
                                $srr->additional_credited_amount = 0;
                                $srr->payment_id = 0;
                                $srr->booking_id = !empty($srr->booking_id) ? (int) $srr->booking_id : 0;
                                $srr->paid_amount = !empty($srr->paid_amount) ? (int) $srr->paid_amount : 0;
                                $srr->cancellation_fee = !empty($srr->cancellation_fee) ? (int) $srr->cancellation_fee : 0;
                                $srr->spot_id = !empty($srr->spot_id) ? (int) $srr->spot_id : 0;
                                $srr->booking_refund_id = !empty($srr->booking_refund_id) ? (int) $srr->booking_refund_id : 0;
                                $srr->refund_amount = !empty($srr->refund_amount) ? (int) $srr->refund_amount : 0;
                                $srr->spot_address = !empty($srr->spot_address) ? (string) $srr->spot_address : '';
                                $srr->spot_location = !empty($srr->spot_location) ? (string) $srr->spot_location : '';
                                $srr->generated_booking_id = !empty($srr->generated_booking_id) ? (string) $srr->generated_booking_id : '';
                                $srr->postal_code = !empty($srr->postal_code) ? (string) $srr->postal_code : '';
                                $srr->city_name = !empty($srr->city_name) ? (string) $srr->city_name : '';
                                $srr->country_name = !empty($srr->country_name) ? (string) $srr->country_name : '';
                            }
                        }
                    }elseif ($single->transaction_for == 'surcharge'){
                        $details = "SELECT 
                                          b.id as booking_id,
                                          b.total_time,
                                          b.booking_status,
                                          b.booking_date,
                                          b.booking_time,
                                          b.generated_booking_id,
                                          b.paid_amount,
                                          b.surcharge_amount,
                                          b.is_surcharge_paid,
                                          b.surcharge_transaction_id,
                                          b.parking_spot_id as spot_id,
                                          parking_spot.address as spot_address,
                                          parking_spot.location as spot_location,
                                          parking_spot.postal_code,
                                          parking_spot.city_name,
                                          country.country_name
                                        FROM payment_history as h
                                        LEFT JOIN booking as b ON b.surcharge_transaction_id = h.transaction_id
                                        LEFT JOIN parking_spot ON parking_spot.id = b.parking_spot_id
                                        LEFT JOIN country ON country.id = parking_spot.country_id
                                        WHERE h.id = $single->transaction_id";
                        $details = DB::Select($details);
                        if(isset($details)&&!empty($details)){
                            $details[0]->bank_receipt = '';
                            $details[0]->invoice = '';
                            if(empty($single->invoice) && $single->transaction_for == "Booking"){
                                $invoice = "SELECT
                                                  h.*,
                                                  b.id as booking_id,
                                                  b.total_time,
                                                  b.booking_status,
                                                  b.booking_date,
                                                  b.booking_time,
                                                  b.generated_booking_id,
                                                  b.paid_amount,
                                                  b.additional_credited_amount,
                                                  b.booking_transaction_id,
                                                  b.booking_start_date_time,
                                                  b.booking_end_date_time,
                                                  b.parking_spot_id as spot_id,
                                                  parking_spot.address as spot_address,
                                                  parking_spot.location as spot_location,
                                                  parking_spot.postal_code,
                                                  parking_spot.city_name,
                                                  country.country_name
                                                FROM payment_history as h
                                                LEFT JOIN booking as b ON b.booking_transaction_id = h.transaction_id
                                                LEFT JOIN parking_spot ON parking_spot.id = b.parking_spot_id
                                                LEFT JOIN country ON country.id = parking_spot.country_id
                                                WHERE h.id = $single->surcharge_transaction_id";
                                $invoice = DB::Select($invoice);
                                if(isset($invoice) && !empty($invoice)){
                                    ini_set('memory_limit', -1);
                                    ini_set('max_execution_time', 0);
                                    $data['data'] = $invoice[0];
                                    $pdf = \PDF::loadView('invoice',$data);
                                    $output = $pdf->output();
                                    file_put_contents("uploads/invoice/".$invoice[0]->transaction_id."-invoice.pdf", $output);
                                    $invoice_name = DB::table('transaction_history')
                                        ->where('id', $single->id)
                                        ->update([
                                            'invoice' => $invoice[0]->transaction_id."-invoice.pdf"
                                        ]);
                                    $details[0]->invoice = asset("uploads/invoice/".$invoice[0]->transaction_id."-invoice.pdf");
                                }
                            }else{
                                $details[0]->invoice = !empty($single->invoice)? asset("uploads/invoice/".$single->invoice): "";
                            }
                            //$details[0]->invoice = '';
                            foreach ($details as $sc){
                                $sc->refund_amount_status = "";
                                $sc->additional_credited_amount = 0;
                                $sc->payment_id = 0;
                                $sc->booking_id = !empty($sc->booking_id) ? (int) $sc->booking_id : 0;
                                $sc->paid_amount = !empty($sc->paid_amount) ? (int) $sc->paid_amount : 0;
                                $sc->spot_id = !empty($sc->spot_id) ? (int) $sc->spot_id : 0;
                                $sc->spot_address = !empty($sc->spot_address) ? (string) $sc->spot_address : '';
                                $sc->spot_location = !empty($sc->spot_location) ? (string) $sc->spot_location : '';
                                $sc->generated_booking_id = !empty($sc->generated_booking_id) ? (string) $sc->generated_booking_id : '';
                                $sc->surcharge_transaction_id = !empty($sc->surcharge_transaction_id) ? (string) $sc->surcharge_transaction_id : '';
                                $sc->surcharge_amount = !empty($sc->surcharge_amount) ? (int) $sc->surcharge_amount : 0;
                                $sc->postal_code = !empty($sc->postal_code) ? (string) $sc->postal_code : '';
                                $sc->city_name = !empty($sc->city_name) ? (string) $sc->city_name : '';
                                $sc->country_name = !empty($sc->country_name) ? (string) $sc->country_name : '';
                            }
                        }
                    }elseif($single->transaction_for == 'Booking' && $single->is_transaction == 'No'){
                        $payment_history = DB::table('payment_history')->Where('id', $single->transaction_id)->first();
                        if(!empty($payment_history) && $payment_history->status == "Pending"){
                            $single->transaction_for = 'Failed_Booking';
                            $details = "SELECT 
                                          parking_spot.address as spot_address,
                                          parking_spot.location as spot_location,
                                          parking_spot.postal_code,
                                          parking_spot.city_name,
                                          country.country_name
                                        FROM parking_spot
                                        LEFT JOIN country ON country.id = parking_spot.country_id
                                        WHERE parking_spot.id = $single->parking_spot_id";
                            $details = DB::Select($details);
                            if(count($details) > 0){
                                $receipt = "SELECT bank_receipt.uploaded_receipt,booking_refund.*
                                            FROM booking_refund
                                            LEFT JOIN bank_receipt ON bank_receipt.booking_refund_id = booking_refund.id
                                            WHERE booking_refund.payment_history_id = $payment_history->id";
                                $receipt = DB::Select($receipt);
                                $details[0]->bank_receipt = '';
                                $details[0]->invoice = '';
                                if(isset($receipt) && !empty($receipt) && count($receipt) >0){
                                    $details[0]->bank_receipt = !empty($receipt[0]->uploaded_receipt)?asset('uploads/bank_receipt/'.$receipt[0]->uploaded_receipt):"";
                                }
                            }
                            if(isset($details)&&!empty($details)){
                                foreach ($details as $s){
                                    $s->payment_id = 0;
                                    $s->additional_credited_amount = 0;
                                    $s->booking_id = !empty($s->booking_id) ? (int) $s->booking_id : 0;
                                    $s->paid_amount = !empty($payment_history->amount) ? (int) $payment_history->amount : 0;
                                    $s->cancellation_fee = !empty($s->cancellation_fee) ? (int) $s->cancellation_fee : 0;
                                    $s->spot_id = !empty($s->spot_id) ? (int) $s->spot_id : 0;
                                    $s->booking_refund_id = !empty($s->booking_refund_id) ? (int) $s->booking_refund_id : 0;
                                    $s->refund_amount = !empty($payment_history->amount) ? (int) $payment_history->amount : 0;
                                    $s->spot_address = !empty($s->spot_address) ? (string) $s->spot_address : '';
                                    $s->spot_location = !empty($s->spot_location) ? (string) $s->spot_location : '';
                                    $s->generated_booking_id = !empty($s->generated_booking_id) ? (string) $s->generated_booking_id : '';
                                    $s->postal_code = !empty($s->postal_code) ? (string) $s->postal_code : '';
                                    $s->city_name = !empty($s->city_name) ? (string) $s->city_name : '';
                                    $s->country_name = !empty($s->country_name) ? (string) $s->country_name : '';
                                    $s->refund_amount_status = 'Pending';
                                    if(isset($receipt) && !empty($receipt) && count($receipt) >0){
                                        $s->refund_amount_status = !empty($receipt[0]->refund_amount_status) ? (string) $receipt[0]->refund_amount_status : 'Pending';
                                    }
                                }
                            }
                        }
                    }else{
                        $details = "SELECT 
                                          h.id as payment_id,
                                          b.id as booking_id,
                                          b.total_time,
                                          b.booking_status,
                                          b.booking_date,
                                          b.booking_time,
                                          b.generated_booking_id,
                                          b.paid_amount,
                                          b.additional_credited_amount,
                                          b.booking_transaction_id,
                                          b.parking_spot_id as spot_id,
                                          parking_spot.address as spot_address,
                                          parking_spot.location as spot_location,
                                          parking_spot.postal_code,
                                          parking_spot.city_name,
                                          country.country_name
                                        FROM payment_history as h
                                        LEFT JOIN booking as b ON b.booking_transaction_id = h.transaction_id
                                        LEFT JOIN parking_spot ON parking_spot.id = b.parking_spot_id
                                        LEFT JOIN country ON country.id = parking_spot.country_id
                                        WHERE h.id = $single->transaction_id";
                        $details = DB::Select($details);
                        if(isset($details)&&!empty($details)){
                            $details[0]->invoice = '';
                            if(empty($single->invoice) && $single->transaction_for == "Booking"){
                                $invoice = "SELECT
                                                  h.*,
                                                  b.id as booking_id,
                                                  b.total_time,
                                                  b.booking_status,
                                                  b.booking_date,
                                                  b.booking_time,
                                                  b.generated_booking_id,
                                                  b.paid_amount,
                                                  b.additional_credited_amount,
                                                  b.booking_transaction_id,
                                                  b.booking_start_date_time,
                                                  b.booking_end_date_time,
                                                  b.parking_spot_id as spot_id,
                                                  parking_spot.address as spot_address,
                                                  parking_spot.location as spot_location,
                                                  parking_spot.postal_code,
                                                  parking_spot.city_name,
                                                  country.country_name
                                                FROM payment_history as h
                                                LEFT JOIN booking as b ON b.booking_transaction_id = h.transaction_id
                                                LEFT JOIN parking_spot ON parking_spot.id = b.parking_spot_id
                                                LEFT JOIN country ON country.id = parking_spot.country_id
                                                WHERE h.id = $single->transaction_id ";
                                $invoice = DB::Select($invoice);
                                if(isset($invoice) && !empty($invoice)){
                                    ini_set('memory_limit', -1);
                                    ini_set('max_execution_time', 0);
                                    $data['data'] = $invoice[0];
                                    $pdf = \PDF::loadView('invoice',$data);
                                    $output = $pdf->output();
                                    file_put_contents("uploads/invoice/".$invoice[0]->generated_booking_id."-invoice.pdf", $output);
                                    $invoice_name = DB::table('transaction_history')
                                        ->where('id', $single->id)
                                        ->update([
                                            'invoice' => $invoice[0]->generated_booking_id."-invoice.pdf"
                                        ]);
                                    $details[0]->invoice = asset("uploads/invoice/".$invoice[0]->generated_booking_id."-invoice.pdf");
                                }
                            }else{
                                $details[0]->invoice = !empty($single->invoice)?asset("uploads/invoice/".$single->invoice):"";
                            }
                            $details[0]->bank_receipt = '';
                            foreach ($details as $br){
                                $br->refund_amount_status = "";
                                $br->payment_id = !empty($br->payment_id) ? (int) $br->payment_id : 0;
                                $br->booking_id = !empty($br->booking_id) ? (int) $br->booking_id : 0;
                                $br->paid_amount = !empty($br->paid_amount) ? (int) $br->paid_amount : 0;
                                $br->additional_credited_amount = !empty($br->additional_credited_amount) ? (int) $br->additional_credited_amount : 0;
                                $br->spot_id = !empty($br->spot_id) ? (int) $br->spot_id : 0;
                                $br->spot_address = !empty($br->spot_address) ? (string) $br->spot_address : '';
                                $br->spot_location = !empty($br->spot_location) ? (string) $br->spot_location : '';
                                $br->generated_booking_id = !empty($br->generated_booking_id) ? (string) $br->generated_booking_id : '';
                                $br->booking_transaction_id = !empty($br->booking_transaction_id) ? (string) $br->booking_transaction_id : '';
                                $br->postal_code = !empty($br->postal_code) ? (string) $br->postal_code : '';
                                $br->city_name = !empty($br->city_name) ? (string) $br->city_name : '';
                                $br->country_name = !empty($br->country_name) ? (string) $br->country_name : '';
                            }
                        }
                    }
                    /*echo '<pre>';
                    print_r($details);
                    echo '</pre>';
                    exit;*/
                    if(isset($details[0]) && !empty($details[0])){
                        $single->details = $details[0];
                    }else{
                        $single->details = (object)array();
                    }

                }
            }

            if(count($transaction_details) > 0){
                return response()->json(['message' => 'Success', 'code' => 200, 'data' => $transaction_details]);
            }else{
                return response()->json(['message' => 'Transactions not available', 'code' => 101]);
            }
        }else{
            return response()->json(['message' => 'Unauthorised Call', 'code' => 101]);
        }
    }
}
