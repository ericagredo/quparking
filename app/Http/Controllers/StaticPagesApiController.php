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
use App\Models\PageContent;
use App\Models\EmailTemplate;
use Mail;

class StaticPagesApiController extends Controller {
    /*
     * Request Parameter :  [Apikey, page_key('About Us', 'How It Works', 'Terms and Conditions', 'Privacy Policy')]
     * Method : POST
     * Request Api Url : "/api/staticPagesDetails"
     * Request Controller & Method : StaticPagesApiController/staticPagesDetails
     * Success response : [ message : Array Of StaticPages Details.,  code : 200]
     * Error response : 
      1)[ message : Page details are not available., code : 101]
      2)[ message : Unauthorised Call, code : 101]
     */

    public function staticPagesDetails(Request $request) {
        $page_key = $request->page_key;
        $Apikey = $request->Apikey;
        if ($Apikey == APIKEY) {
            $page_content_details = PageContent::where('page_name', '=', $page_key)->where('status', 'Active')->first();
            if (count($page_content_details) > 0) {
                $page_content_details->id = !empty($page_content_details->id) ? (int) $page_content_details->id : '';
                $msg = 'success';
                $code = 200;
                return response()->json(['message' => $msg, 'code' => $code, 'PageDetails' => $page_content_details]);
            } else {
                return response()->json(['message' => 'Page details are not available.', 'code' => 101]);
            }
        } else {
            return response()->json(['message' => 'Unauthorised Call', 'code' => 101]);
        }
    }

    /*
     * Request Parameter :  [Apikey, firstname, lastname, email, contact_number, state, message]
     * Method : POST
     * Request Api Url : "/api/contactus"
     * Request Controller & Method : StaticPagesApiController/contactus
     * Success response : [ message : Contact us mail send to admin successfully.,  code : 200]
     * Error response : 
      1)[ message : Contact us details arenot available., code : 101]
      2)[ message : Unauthorised Call, code : 101]
     */

    public function contactus(Request $request) {
        $firstname = $request->firstname;
        $lastname = $request->lastname;
        $email = $request->email;
        $contact_number = $request->contact_number;
        $state = $request->state;
        $message = $request->message;
        $Apikey = $request->Apikey;
        if ($Apikey == APIKEY) {
            $contactus = DB::table('contact_us')->insertGetId([
                'first_name' => $firstname,
                'last_name' => $lastname,
                'email' => $email,
                'contact_number' => $contact_number,
                'state' => $state,
                'message_description' => $message,
                'created_date' => Helper::get_curr_datetime()
            ]);


            $contactUsDetails = DB::table('contact_us')->Where('id', $contactus)->first();
            if(count($contactUsDetails) > 0){
                $name = $contactUsDetails->first_name.' '.$contactUsDetails->last_name;
                $EmailTemplate = EmailTemplate::Where('id', 6)->Where('status', 'Active')->first(); 
                $ContactPageArray = array('FIRSTNAME' => $contactUsDetails->first_name, 'LASTNAME' => $contactUsDetails->last_name, 'EMAIL' => $contactUsDetails->email, 'CONTACTNUMBER' => $contactUsDetails->contact_number, 'STATE'=> $contactUsDetails->state, 'MESSAGE' => $contactUsDetails->message_description);
                $data = array('Subject'=> $EmailTemplate->subject);
                $message = '';
                //=== Mail Send Functionality
                Mail::send('emails.contactus', ['ContactPage' => $ContactPageArray, 'message' => $message], function ($message) use ($data) {
                    $message->from('troodeveloper@gmail.com', $data['Subject']);
                    $message->to('mayuri.patel@trootech.com')->subject($data['Subject']);
                }); 

                return response()->json(['message' => 'Thank you! Someone in our team will be contacting you shortly.', 'code' => 200]);
            }else{
                return response()->json(['message' => 'Contact us details arenot available.', 'code' => 101]);
            }
        } else {
            return response()->json(['message' => 'Unauthorised Call', 'code' => 101]);
        } 
    } 
}
