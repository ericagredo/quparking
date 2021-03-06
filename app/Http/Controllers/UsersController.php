<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use App\Http\Helper;
use App\Http\Requests;
use App\Models\Users;
use App\Models\BankDetails;
use Mail;

use Aws\Credentials\CredentialProvider;
use Aws\Sns\SnsClient;
use App\Models\DeviceMaster;


class UsersController extends Controller {



    /**
     * Request Parameter : [Apikey, email, password]
     * Request Api Url : "/api/login"
     * Request Controller & Method : UsersController/login
     * Success response : [ message : success,  code : 200,  data : User details Array]
     * Error response : 
      1) [message : Your account is Inactive, Please contact Site Admin..,  code : 101]
      2)[ message : Your Email and Password does not match. , code : 101]
     * 3)[ message : Unauthorised Call. , code : 101]
     * Method  : POST
     */
    public function login(Request $request) {

        $email = $request->email;
        $password_simple = $request->password;
        $password = md5($password_simple);
        $timezone = $request->timezone;
        $Apikey = $request->Apikey;
        if ($Apikey == APIKEY) {
            if (!empty($email)) {
                $UserLoginSql = 'SELECT users.*, country.country_name FROM `users` join country On country.id = users.country_id WHERE users.email ="' . $email . '" and users.password="' . $password . '" and users.status="Active"';
                $UserLoginArray = DB::select($UserLoginSql);
                if (count($UserLoginArray) > 0) {
                    $user = $UserLoginArray[0];
                    if ($user->status == 'Inactive') {
                        $msg = 'Your account is Inactive, Please contact Site Admin.';
                        $code = 101;
                        return response()->json(['message' => $msg, 'code' => $code]);
                    } else if (count($user) > 0) {
                        $user->id = !empty($user->id) ? (int) $user->id : 0;
                        $user->country_id = !empty($user->country_id) ? (int) $user->country_id : 0;
                        $user->profile_image = !empty($user->profile_image) ? asset('uploads/user_profile_image/' . $user->profile_image) : '';
                        $user->contact_number = !empty($user->contact_number) ? (int)$user->contact_number : 0;
                        $user->longitude = !empty($user->longitude) ? (float)$user->longitude : 0.0;
                        $user->latitude = !empty($user->latitude) ? (float)$user->latitude : 0.0;
                        $user->zipcode = !empty($user->zipcode) ? (string)$user->zipcode : '';

                        $os_type = $request->os_type;
                        $device_token = $request->device_token;
                        $gcm_key = $request->gcm_key;
                        $this->PushDetails_Add_Update($os_type,$device_token,$gcm_key,$user->id);

                        $update = DB::table('users')
                            ->where('id', $user->id)
                            ->update([
                                'timezone' => $timezone
                            ]);
                        $bank_details = (object) array();
                        $bank = DB::table('bank_details')->where('users_id', $user->id)->first();
                        if(count($bank) > 0){
                            $bank->id = !empty($bank->id) ? (int)$bank->id : 0;
                            $bank->users_id = !empty($bank->users_id) ? (int)$bank->users_id : 0;
                            $bank->bank_name = !empty($bank->bank_name) ? (string)$bank->bank_name : '';
                            $bank->bank_account_number = !empty($bank->bank_account_number) ? (string)$bank->bank_account_number : '';
                            $bank->bank_routing_number = !empty($bank->bank_routing_number) ? (string)$bank->bank_routing_number : '';
                            $bank_details = $bank;
                        }
                        $msg = 'success';
                        $code = 200;
                        return response()->json(['message' => $msg, 'code' => $code, 'data' => $user,'bank_details' =>$bank_details]);
                    }
                } else {
                    $msg = 'Your Email and Password does not match.';
                    $code = 101;
                    return response()->json(['message' => $msg, 'code' => $code]);
                }
            }


        } else {
            return response()->json(['message' => 'Unauthorised Call', 'code' => 101]);
        } 
    }

    /*
     * Request Parameter :  [Apikey, firstname, lastname, contact_number, email, password, country_id, zipcode, location, latitude, longitude]
     * Method : POST
     * Request Api Url : "/api/signup"
     * Request Controller & Method : UsersController/signup
     * Success response : [ message : User save SuccessFully.,  code : 200]
     * Error response : 
      1)[ message : User Already Exists, code : 101]
      2)[ message : Unauthorised Call. , code : 101]
     */

    public function signup(Request $request) {
        $firstname = $request->firstname;
        $lastname = $request->lastname;
        $contact_number = $request->contact_number;
        $email = $request->email;
        $password = $request->password;
        $country_name = $request->country_id;
        $zipcode = $request->zipcode;
        $latitude = $request->latitude;
        $longitude = $request->longitude;
        $location = $request->location;
        $timezone = $request->timezone;

        $Apikey = $request->Apikey;
        if ($Apikey == APIKEY) {
            $count = DB::table('users')->where('email', '=', $email)->count();
            $count2 = DB::table('users')->where('contact_number', '=', $contact_number)->count();
            if($count == 0 && $count2 ==0) {
                $users = DB::table('users')->insertGetId([
                    'firstname' => $firstname,
                    'lastname' => $lastname,
                    'email' => $email,
                    'password' => md5($password),
                    'contact_number' => $contact_number,
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'country_id' => $country_name,
                    'zipcode' => $zipcode,
                    'location' => $location,
                    'status' => 'Active',
                    'created_at' => date('Y-m-d H:i:s')
                ]);

                DB::table('users')->Where('id', $users)->update([
                    'created_by' => $users,
                    'timezone' => $timezone
                ]);
                
                if(isset($users) && !empty($users)){
                    $push_notification = DB::table('user_notification')->get();
                    foreach($push_notification as $_noti){
                                DB::table('notification')->insertGetId([
                                    'users_id' => $users,
                                    'push_notification_id' => $_noti->id,
                                    'notification_mode' => 'ON',
                                    'title' => '',
                                    'description' => '',
                                    'created_date' => Helper::get_curr_datetime(),
                                    'updated_date' => Helper::get_curr_datetime(),
                                    'created_by' => 0
                                 ]);
                    }
                }

                $users_details = DB::table('users')->select('users.*','country.country_name')->where('users.id', $users)->join('country','users.country_id','=','country.id')->first();
                //$users_details = DB::table('users')->where('id', $users)->first();

                $users_details->id = !empty($users_details->id) ? (int) $users_details->id : 0;
                $users_details->country_id = !empty($users_details->country_id) ? (int) $users_details->country_id : 0;
                $users_details->country_name = !empty($users_details->country_name) ? (string) $users_details->country_name : '';
                $users_details->profile_image = !empty($users_details->profile_image) ? asset('uploads/user_profile_image/' . $users_details->profile_image) : '';
                $users_details->contact_number = !empty($users_details->contact_number) ? (int)$users_details->contact_number : 0;
                $users_details->longitude = !empty($users_details->longitude) ? (float)$users_details->longitude : 0.0;
                $users_details->latitude = !empty($users_details->latitude) ? (float)$users_details->latitude : 0.0;
                $users_details->zipcode = !empty($users_details->zipcode) ? (string)$users_details->zipcode : '';

                $os_type = $request->os_type;
                $device_token = $request->device_token;
                $gcm_key = $request->gcm_key;

                $this->PushDetails_Add_Update($os_type,$device_token,$gcm_key,$users_details->id);

                $bank_details = (object) array();
                $bank = DB::table('bank_details')->where('users_id', $users_details->id)->first();
                if(count($bank) > 0){
                    $bank->id = !empty($bank->id) ? (int)$bank->id : 0;
                    $bank->users_id = !empty($bank->users_id) ? (int)$bank->users_id : 0;
                    $bank->bank_name = !empty($bank->bank_name) ? (string)$bank->bank_name : '';
                    $bank->bank_account_number = !empty($bank->bank_account_number) ? (string)$bank->bank_account_number : '';
                    $bank->bank_routing_number = !empty($bank->bank_routing_number) ? (string)$bank->bank_routing_number : '';
                    $bank_details = $bank;
                }
                $msg = 'User save SuccessFully.';
                $code = 200;
                return response()->json(['message' => $msg, 'code' => $code, 'data' => $users_details, 'bank_details'=>$bank_details]);
            }else{
                return response()->json(['message' => 'User Already Exists', 'code' => 101]);
            }
        } else {
            return response()->json(['message' => 'Unauthorised Call', 'code' => 101]);
        }    
    }

    /*
     * Request Parameter :  [Apikey,userID]
     * Method : POST
     * Request Api Url : "/api/fetchuserdetails"
     * Request Controller & Method : UsersController/fetchUserDetails
     * Success response : [ message : Array Of User Details Using UserID.,  code : 200]
     * Error response : 
      1)[ message : User Not Found., code : 101]
      2)[ message : Unauthorised Call. , code : 101]
     */

    public function fetchUserDetails(Request $request) {
        $userID = $request->userID;
        $Apikey = $request->Apikey;
        if ($Apikey == APIKEY) {
            $userDetailsArray = Users::select('users.*', 'country.country_name')->Where('users.id', '=', $userID)->join('country', 'country.id', '=', 'users.country_id')->first();
           // print_r($userDetailsArray);die;
            if (count($userDetailsArray) > 0) {
                $userDetailsArray->id = !empty($userDetailsArray->id) ? (int) $userDetailsArray->id : 0;
                $userDetailsArray->country_id = !empty($userDetailsArray->country_id) ? (int) $userDetailsArray->country_id : 0;
                $userDetailsArray->country_name = !empty($userDetailsArray->country_name) ? (string) $userDetailsArray->country_name : '';
                $userDetailsArray->profile_image = !empty($userDetailsArray->profile_image) ? asset('uploads/user_profile_image/' . $userDetailsArray->profile_image) : '';
                $userDetailsArray->zipcode = !empty($userDetailsArray->zipcode) ? (string) $userDetailsArray->zipcode : '';
                $userDetailsArray->contact_number = !empty($userDetailsArray->contact_number) ? (int) $userDetailsArray->contact_number : 0;
                $userDetailsArray->longitude = !empty($userDetailsArray->longitude) ? (float)$userDetailsArray->longitude : 0.0;
                $userDetailsArray->latitude = !empty($userDetailsArray->latitude) ? (float)$userDetailsArray->latitude : 0.0;

                $msg = 'success';
                $code = 200;
                return response()->json(['message' => $msg, 'code' => $code, 'data' => $userDetailsArray]);
            } else {
                return response()->json(['message' => 'User Not Found.', 'code' => 101]);
            }
        } else {
            return response()->json(['message' => 'Unauthorised Call', 'code' => 101]);
        }    
    }

    /*
     * Request Parameter :  [Apikey, old_password, new_password, userID]
     * Method : POST
     * Request Api Url : "/api/changePasswordApi"
     * Request Controller & Method : UsersController/changePasswordApi
     * Success response : [ message : Password Change Successfully.',  code : 200]
     * Error response : 
      1)[ message : Old Password does not Match., code : 101]
      2)[ message : User does not Exist., code : 101]
      3)[ message : Unauthorised Call. , code : 101]
     */

    public function changePasswordApi(Request $request) {
        $old_password_simple = $request->old_password;
        $new_password_simple = $request->new_password;
        $userID = $request->userID;

        $old_password = md5($old_password_simple);
        $new_password = md5($new_password_simple);

        $Apikey = $request->Apikey;
        if ($Apikey == APIKEY) {
            $checkExistPwd = DB::table('users')->where('id', $userID)->where('status', 'Active')->first();
            if (count($checkExistPwd) > 0) {
                $Password = $checkExistPwd->password;
                if ($old_password == $Password) {
                    $user = DB::table('users')
                            ->where('id', $checkExistPwd->id)
                            ->update(['password' => $new_password]);
                    return response()->json(['message' => 'Password Change Successfully.', 'code' => 200]);
                } else {
                    return response()->json(['message' => 'Old Password does not match', 'code' => 101]);
                }
            } else {
                return response()->json(['message' => 'User does not Exist.', 'code' => 101]);
            }
        } else {
            return response()->json(['message' => 'Unauthorised Call', 'code' => 101]);
        }
    }

    /*
     * Request Parameter :  [Apikey, firstname, lastname, contact_number, country_id, zipcode, profile_image, userID]
     * Method : POST
     * Request Api Url : "/api/userProfileEdit"
     * Request Controller & Method : UsersController/userProfileEdit
     * Success response : [ message : Profile Change SuccessFully.,  code : 200]
     * Error response : 
      1)[ message : User does not Exists, code : 101]
     * 2)[ message : Unauthorised Call. , code : 101]
     */

    public function userProfileEdit(Request $request) {
        $firstname = $request->firstname;
        $lastname = $request->lastname;
        $contact_number = $request->contact_number;
        $country_name = $request->country_id;
        $zipcode = $request->zipcode;
        $userID = $request->userID;
        $email = $request->email;

        $Apikey = $request->Apikey;
        if ($Apikey == APIKEY) {
            $edited_res = DB::table('users')->where('id', '=', $userID)->first();
            $imageName = '';
            if (count($edited_res) > 0) {
                $imageName = $edited_res->profile_image;
            }

            if ($request->profile_image != '') {
                $imageName = time() . '.' . $request->profile_image->getClientOriginalExtension();
                $request->profile_image->move(public_path('uploads/user_profile_image'), $imageName);
            }

            $count = DB::table('users')->where('email', '=', $email)->where('id', '!=', $userID)->count();
            $count2 = DB::table('users')->where('contact_number', '=', $contact_number)->where('id', '!=', $userID)->count();
            //echo $count; exit;
            if ($count == 0 && $count2 == 0) {
                $users = DB::table('users')->where('id', $userID)->update([
                    'firstname' => $firstname,
                    'lastname' => $lastname,
                    'contact_number' => $contact_number,
                    'profile_image' => $imageName,
                    'country_id' => $country_name,
                    'zipcode' => $zipcode,
                    'email' => $email
                ]);

                $userDetailsArray = Users::select('users.*', 'country.country_name')->Where('users.id', '=', $userID)->join('country', 'country.id', '=', 'users.country_id')->first();
                if (count($userDetailsArray) > 0) {
                    $userDetailsArray->id = !empty($userDetailsArray->id) ? (int) $userDetailsArray->id : 0;
                    $userDetailsArray->country_id = !empty($userDetailsArray->country_id) ? (int) $userDetailsArray->country_id : 0;
                    $userDetailsArray->country_name = !empty($userDetailsArray->country_name) ? (string) $userDetailsArray->country_name : '';
                    $userDetailsArray->profile_image = !empty($userDetailsArray->profile_image) ? asset('uploads/user_profile_image/' . $userDetailsArray->profile_image) : '';
                    $userDetailsArray->contact_number = !empty($userDetailsArray->contact_number) ? (int)$userDetailsArray->contact_number : 0;
                    $userDetailsArray->longitude = !empty($userDetailsArray->longitude) ? (float)$userDetailsArray->longitude : 0.0;
                    $userDetailsArray->latitude = !empty($userDetailsArray->latitude) ? (float)$userDetailsArray->latitude : 0.0;
                    $userDetailsArray->zipcode = !empty($userDetailsArray->zipcode) ? (string)$userDetailsArray->zipcode : '';



                    $msg = 'success';
                    $code = 200;
                    return response()->json(['message' => $msg, 'code' => $code, 'data' => $userDetailsArray]);
                } else {
                    return response()->json(['message' => 'User Not Found.', 'code' => 101]);
                }
            }else{
                $msg = "User's Email or Contact Number already exist.";
                return response()->json(['message' => $msg, 'code' => 101]);
            }

        } else {
            return response()->json(['message' => 'Unauthorised Call', 'code' => 101]);
        }    
    }

    /*
     * Request Parameter :  [Apikey, email]
     * Method : POST
     * Request Api Url : "/api/forgotPassword"
     * Request Controller & Method : UsersController/forgotPassword
     * Success response : [ message : Mail Send Successfully. Please Check Your email.',  code : 200]
     * Error response : 
      1)[ message : Email Address does not Match., code : 101]
     * 2)[ message : Unauthorised Call. , code : 101]
     */

    public function forgotPassword(Request $request) {
        $email = $request->email;
        $Apikey = $request->Apikey;
        if ($Apikey == APIKEY) {
            $users = DB::table('users')->where('email', '=', $email)->first();
            if (count($users) > 0) {
                $userID = $users->id;
                $token = sha1(uniqid(rand(), true) . 'Fu+uR3l1fE5EcuR!+y!');

                DB::table('users')
                        ->where('id', $users->id)
                        ->update(['remember_token' => $token]);

                $user_token = DB::table('users')->select('remember_token')->where('id', $users->id)->first();
                $final_token = $user_token->remember_token;

                $message = 'Send mail to set reset password Link.';

                //=== Mail Send Functionality
                Mail::send('emails.resetpassword', ['userID' => $final_token, 'users' => $users, 'message' => $message], function ($message) use ($users) {
                    $message->from('troodeveloper@gmail.com', 'Reset Password Link.');
                    $message->to($users->email)->subject('Reset Password Link.');
                });

                return response()->json(['message' => 'Mail Send Successfully. Please Check Your email', 'code' => 200]);
            } else {
                return response()->json(['message' => 'Email Address does not Match.', 'code' => 101]);
            }
        } else {
            return response()->json(['message' => 'Unauthorised Call', 'code' => 101]);
        }
    }

    public function resetPassword($token) {
        $users = DB::table('users')->where('remember_token', $token)->first();

        if (count($users) > 0) {
            return view('api.users.resetPasswordForm', ['users' => $users]);
        } else {
            return response()->json(['message' => 'Email Address does not Match.', 'code' => 101]);
        }
    }

    public function changePassword(Request $request) {
        $remember_token = $request->remember_token;
        $password = md5($request->password);
        $confirm_password = md5($request->confirm_password);

        if ($password == $confirm_password) {
            $user = DB::table('users')
                    ->where('remember_token', $remember_token)
                    ->update(['password' => $password]);

            $updated_id = DB::table('users')->where('remember_token', $remember_token)->first();
            $users_token = DB::table('users')
                    ->where('id', $updated_id->id)
                    ->update(['remember_token' => '']);

            if ($user > 0) {
                echo 'Password has been Change Successfully.';
                die;
            } else {
                echo 'Password hasnot been Change.';
                die;
            }
        }
    }

    /*
     * Request Parameter :  [Apikey, firstname, lastname , email, latitude, longitude, location, social_id, social_type('google plus', 'facebook'), gender]
     * Method : POST
     * Request Api Url : "/api/socialSignup"
     * Request Controller & Method : UsersController/socialSignupFacebookGooglePlus
     * Success response : [ message : User save SuccessFully.,  code : 200]
     * Error response : 
      1)[ message : Your account is Inactive, Please contact Site Admin.., code : 101]
     * 2)[ message : Unauthorised Call. , code : 101]
     */

    public function socialSignupFacebookGooglePlus(Request $request) {
        $firstname = $request->firstname;
        $lastname = $request->lastname;
        $email = $request->email;
        $latitude = $request->latitude;
        $longitude = $request->longitude;
        $location = $request->location;
        $social_id = $request->social_id;
        $social_type = $request->social_type;
        $gender = $request->gender;
        $timezone = $request->timezone;

        $Apikey = $request->Apikey;
        if ($Apikey == APIKEY) {
            $ExistUser = DB::table('users')->where('email', '=', $email)->orWhere('social_id', '=', $social_id)->where('social_type', '=', $social_type)->first();
            if (count($ExistUser) > 0) {
                if ($ExistUser->status == 'Inactive') {
                    $msg = 'Your account is Inactive, Please contact Site Admin..';
                    $code = 101;
                    return response()->json(['message' => $msg, 'code' => $code]);
                } else if (count($ExistUser) > 0) {
                    $ExistUser->id = !empty($ExistUser->id) ? (int) $ExistUser->id : 0;
                    $ExistUser->country_id = !empty($ExistUser->country_id) ? (int) $ExistUser->country_id : 0;
                    $ExistUser->profile_image = !empty($ExistUser->profile_image) ? asset('uploads/user_profile_image/' . $ExistUser->profile_image) : '';
                    $ExistUser->contact_number = !empty($ExistUser->contact_number) ? (int)$ExistUser->contact_number : 0;
                    $ExistUser->longitude = !empty($ExistUser->longitude) ? (float)$ExistUser->longitude : 0.0;
                    $ExistUser->latitude = !empty($ExistUser->latitude) ? (float)$ExistUser->latitude : 0.0;
                    $ExistUser->zipcode = !empty($ExistUser->zipcode) ? (string)$ExistUser->zipcode : '';
                    $ExistUser->country_name = !empty($ExistUser->country_name) ? (string)$ExistUser->country_name : '';
                    $update = DB::table('users')
                        ->where('id', $ExistUser->id)
                        ->update([
                            'timezone' => $timezone
                        ]);

                    $os_type = $request->os_type;
                    $device_token = $request->device_token;
                    $gcm_key = $request->gcm_key;

                    $this->PushDetails_Add_Update($os_type,$device_token,$gcm_key,$ExistUser->id);
                    $bank_details = (object) array();
                    $bank = DB::table('bank_details')->where('users_id', $ExistUser->id)->first();
                    if(count($bank) > 0){
                        $bank->id = !empty($bank->id) ? (int)$bank->id : 0;
                        $bank->users_id = !empty($bank->users_id) ? (int)$bank->users_id : 0;
                        $bank->bank_name = !empty($bank->bank_name) ? (string)$bank->bank_name : '';
                        $bank->bank_account_number = !empty($bank->bank_account_number) ? (string)$bank->bank_account_number : '';
                        $bank->bank_routing_number = !empty($bank->bank_routing_number) ? (string)$bank->bank_routing_number : '';
                        $bank_details = $bank;
                    }

                    $msg = 'success';
                    $code = 200;
                    return response()->json(['message' => $msg, 'code' => $code, 'data' => $ExistUser,'bank_details'=>$bank_details]);
                }
            } else {
                $users = DB::table('users')->insertGetId([
                    'firstname' => $firstname,
                    'lastname' => $lastname,
                    'email' => $email,
                    'contact_number' => rand(10, 9999999999),
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'location' => $location,
                    'social_id' => $social_id,
                    'social_type' => $social_type,
                    'gender' => $gender?$gender:'',
                    'status' => 'Active',
                    'created_at' => date('Y-m-d H:i:s')
                ]);

                DB::table('users')->Where('id', $users)->update([
                    'created_by' => $users,
                    'timezone' => $timezone
                ]);
                
                if(isset($users) && !empty($users)){
                    $push_notification = DB::table('user_notification')->get();
                    foreach($push_notification as $_noti){
                                DB::table('notification')->insertGetId([
                                    'users_id' => $users,
                                    'push_notification_id' => $_noti->id,
                                    'notification_mode' => 'ON',
                                    'title' => '',
                                    'description' => '',
                                    'created_date' => Helper::get_curr_datetime(),
                                    'updated_date' => Helper::get_curr_datetime(),
                                    'created_by' => 0
                                 ]);
                    }
                    $update = DB::table('users')
                        ->where('id', $users)
                        ->update([
                            'timezone' => $timezone
                        ]);
                }


                $users_details = DB::table('users')->where('id', $users)->first();
                //$users_details = DB::table('users')->select('users.*','country.country_name')->where('users.id', $users)->join('country','users.country_id','=','country.id')->first();
                if (count($users_details) > 0) {
                    $users_details->id = !empty($users_details->id) ? (int) $users_details->id : 0;
                    $users_details->country_id = !empty($users_details->country_id) ? (int) $users_details->country_id : 0;
                    $users_details->profile_image = !empty($users_details->profile_image) ? asset('uploads/user_profile_image/' . $users_details->profile_image) : '';
                    $users_details->contact_number = !empty($users_details->contact_number) ? (int)$users_details->contact_number : 0;
                    $users_details->longitude = !empty($users_details->longitude) ? (float)$users_details->longitude : 0.0;
                    $users_details->latitude = !empty($users_details->latitude) ? (float)$users_details->latitude : 0.0;
                    $users_details->zipcode = !empty($users_details->zipcode) ? (string)$users_details->zipcode : '';
                    $users_details->country_name = '';
                }
                $os_type = $request->os_type;
                $device_token = $request->device_token;
                $gcm_key = $request->gcm_key;

                $this->PushDetails_Add_Update($os_type,$device_token,$gcm_key,$users_details->id);

                $bank_details = (object) array();
                $bank = DB::table('bank_details')->where('users_id', $users_details->id)->first();
                if(count($bank) > 0){
                    $bank->id = !empty($bank->id) ? (int)$bank->id : 0;
                    $bank->users_id = !empty($bank->users_id) ? (int)$bank->users_id : 0;
                    $bank->bank_name = !empty($bank->bank_name) ? (string)$bank->bank_name : '';
                    $bank->bank_account_number = !empty($bank->bank_account_number) ? (string)$bank->bank_account_number : '';
                    $bank->bank_routing_number = !empty($bank->bank_routing_number) ? (string)$bank->bank_routing_number : '';
                    $bank_details = $bank;
                }
                $message = 'User saved SuccessFully.';
                $code = 200;
                return response()->json(['message' => $message, 'code' => $code, 'data' => $users_details,'bank_details'=>$bank_details]);
            }
        } else {
            return response()->json(['message' => 'Unauthorised Call', 'code' => 101]);
        }     
    }

    /*
     * Request Parameter :  [Apikey, userID, bank_account_number, bank_name, bank_routing_number]
     * Method : POST
     * Request Api Url : "/api/bankdetailsettings"
     * Request Controller & Method : UsersController/bankdetailsettings
     * Success response : [ message : Success,  code : 200, bankdetails : Array of Bank details for users]
     * Error response : 
      1)[ message : user does not exist., code : 101]
     * 2)[ message : Unauthorised Call. , code : 101]
     */
    
    public function bankdetailsettings(Request $request){
       $userID = $request->userID; 
       $bank_account_number = $request->bank_account_number; 
       $bank_name = $request->bank_name; 
       $bank_routing_number = $request->bank_routing_number; 
       
       $Apikey = $request->Apikey;
        if ($Apikey == APIKEY) {
            $ExistUserbankdetails = BankDetails::where('users_id', $userID)->get();
            if (count($ExistUserbankdetails) > 0) {
                 $bankdetails_update = DB::table('bank_details')->update([
                     'users_id' => $userID,
                     'bank_account_number' => $bank_account_number,
                     'bank_name' => $bank_name,
                     'bank_routing_number' => $bank_routing_number,
                     'updated_date' => Helper::get_curr_datetime(),
                     'updated_by' => $userID,
                 ]);
             } else {
                 DB::table('bank_details')->insert(
                     [
                         'users_id' => $userID,
                         'bank_account_number' => $bank_account_number,
                         'bank_name' => $bank_name,
                         'bank_routing_number' => $bank_routing_number,
                         'created_date' => Helper::get_curr_datetime(),
                         'created_by' => $userID,
                     ]
                 );
             }

            $Getbankdetails = BankDetails::where('users_id', $userID)->first();
            if(count($Getbankdetails) > 0){
                $Getbankdetails->id = !empty($Getbankdetails->id) ? (int)$Getbankdetails->id : 0;
                $Getbankdetails->users_id = !empty($Getbankdetails->users_id) ? (int)$Getbankdetails->users_id : 0;
                $Getbankdetails->bank_name = !empty($Getbankdetails->bank_name) ? (string)$Getbankdetails->bank_name : '';
                $Getbankdetails->bank_account_number = !empty($Getbankdetails->bank_account_number) ? (string)$Getbankdetails->bank_account_number : '';
                $Getbankdetails->bank_routing_number = !empty($Getbankdetails->bank_routing_number) ? (string)$Getbankdetails->bank_routing_number : '';
                $message = 'Success';
                $code = 200;
                return response()->json(['message' => $message, 'code' => $code, 'bankDetails' => $Getbankdetails]);
            }else{
                return response()->json(['message' => 'Bank details not registered', 'code' => 101]);
            }
        } else {
            return response()->json(['message' => 'Unauthorised Call', 'code' => 101]);
        }     
    }

    /*
         * Request Parameter :  [Apikey, userID]
         * Method : POST
         * Request Api Url : "/api/logout"
         * Request Controller & Method : UsersController/logout
         * Success response : [ message : Success,  code : 200, bankdetails : Logout successfully]
         * Error response :
         * 1)[ message : Unauthorised Call. , code : 101]
         */
    public function logout(Request $request) {
        $Apikey = $request->Apikey;

        if ($Apikey == APIKEY) {
            $userID = $request->userID;
            $users_id = DB::table('device_master')->where('users_id', $userID)->update([
                'is_login' => 'No'
            ]);

            return response()->json(['message' => 'Logout successfully.', 'code' => 200]);
        } else {
            return response()->json(['message' => 'Unauthorised Call', 'code' => 101]);
        }
    }

    public function PushDetails_Add_Update($os_type,$device_token,$gcm_key,$user_id){
        if (isset($os_type) && !empty($os_type) &&
            isset($device_token) && !empty($device_token) &&
            isset($gcm_key) && !empty($gcm_key)
        ) {
            $AmazonConfigs = config('aws');
            if (!empty($AmazonConfigs)) {
                //$access_key = $AmazonConfigs['credentials']['key'];
                //$secret_access_key = $AmazonConfigs['credentials']['secret'];

                $platformApplicationArnIOS = $AmazonConfigs['platformApplicationArnIOS'];
                //$platformApplicationArnAndroid = $AmazonConfigs['platformApplicationArnAndroid'];

                $topicArn = $AmazonConfigs['topicArn'];

                // IOS
                if ($os_type == 'ios') {
                    $platformApplicationArn = $platformApplicationArnIOS;
                }

                $client = new SnsClient([
                    'region' => 'us-east-1',
                    'version' => '2010-03-31',
                    'credentials' => CredentialProvider::env()
                ]);

                $resultEndpoint = $client->createPlatformEndpoint(array(
                    'PlatformApplicationArn' => $platformApplicationArn,
                    'Token' => $gcm_key
                ));

                $gcm_arn = $resultEndpoint['EndpointArn'];

                $client->subscribe(array(
                    'TopicArn' => $topicArn,
                    'Protocol' => 'application',
                    'Endpoint' => $gcm_arn
                ));

                // Check if user's device is already registered in database
                $device = DeviceMaster::where('users_id', '=', $user_id)->first();

                if ($device) {
                    $device->update([
                        'user_type' => $os_type,
                        'device_token' => $device_token,
                        'gcm_key' => $gcm_key,
                        'gcm_arn' => $gcm_arn,
                        'is_login' => 'Yes'
                    ]);
                } else {
                    $deviceData = [
                        'users_id' => $user_id,
                        'user_type' => $os_type,
                        'device_token' => $device_token,
                        'gcm_key' => $gcm_key,
                        'gcm_arn' => $gcm_arn,
                        'is_login' => 'Yes'
                    ];

                    DeviceMaster::insert($deviceData);
                }
            }
        }
    }
}
