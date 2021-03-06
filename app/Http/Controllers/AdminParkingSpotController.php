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
use Aws\Sns\SnsClient;
use Aws\Credentials\CredentialProvider;


class AdminParkingSpotController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        //  $this->middleware('auth');
        //$this->user = $guard->user();
    }

    public function index() {
        return view('parkingspot/parkingspotList');
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('parkingspot/createparkingspot');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $this->validate($request, [
            'address' => 'required|max:255',
            'postal_code' => 'required|max:255',
            'country_name' => 'required|max:255',
            'state_name' => 'required',
            'city_name' => 'required',
            'verification_status' => 'required',
            'number_of_space_spot' => 'required',
            'description' => 'required',
            /* 'location' => 'required', */
            'status' => 'required'
        ]);

        $UserId = $request->session()->get('userData');
        $user_id = $UserId->id;

        $parking_spot = DB::table('parking_spot')->insertGetId(
                [
                    'address' => $request->address,
                    'postal_code' => $request->postal_code,
                    'country_name' => $request->country_name,
                    'state_name' => $request->state_name,
                    'status' => $request->status,
                    'city_name' => $request->city_name,
                    'verification_status' => $request->verification_status,
                    'number_of_space_spot' => $request->number_of_space_spot,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                    'location' => $request->location,
                    'description' => $request->description,
                    'created_date' => Helper::get_curr_datetime(),
                    'created_by' => $user_id
                ]
        );

        return redirect('parkingspot/parkingspotList');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $Country = new \App\Models\Country();
        
        $parking_spot = DB::table('parking_spot')->where('id', $id)->first();
        
        $country_list = $Country->get_all_active_country_list();

        return view('parkingspot/editparkingspot', ['parking_spot' => $parking_spot, 'country' => $country_list]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminreate\Http\Response
     */
    public function update(Request $request, $id) {

        $this->validate($request, [
            'address' => 'required|max:255',
            'postal_code' => 'required|max:255',
            'country_id' => 'required',
            'state_id' => 'required',
            'city_name' => 'required',
            'verification_status' => 'required',
            'number_of_space_spot' => 'required',
            'description' => 'required',
            /* 'location' => 'required', */
            'status' => 'required'
        ]);
        
        /*echo 'c_id'.$request->get('country_id').'<br>';
        echo 's_is'.$request->get('state_id');        exit;*/

        $UserId = $request->session()->get('userData');
        $user_id = $UserId->id;


        $parking_spot = DB::table('parking_spot')->where('id', $id)->update(
                [
                    'address' => $request->get('address'),
                    'postal_code' => $request->get('postal_code'),
                    'country_id' => $request->get('country_id'),
                    'state_id' => $request->get('state_id'),
                    'status' => $request->get('status'),
                    'city_name' => $request->get('city_name'),
                    'verification_status' => $request->get('verification_status'),
                    'number_of_space_spot' => $request->get('number_of_space_spot'),
                    'latitude' => $request->get('latitude'),
                    'longitude' => $request->get('longitude'),
                    'location' => $request->get('location'),
                    'description' => $request->get('description'),
                    'created_date' => Helper::get_curr_datetime(),
                    'created_by' => $user_id
                ]
        );
        /* ----------- Start : Send verifiaction code mail to user --------- */
        $this->sendVerificationstatus($id);
        /* ----------- Start : Send verifiaction code mail to user --------- */
        return redirect('parkingspot/parkingspotList');
    }

    public function ajaxparkingspotList($order_by = "id", $sort_order = "asc", $search = "all", $offset = 0) {
        /* Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
         */

        $aColumns = array('id', 'address', 'postal_code', 'country_name', 'state_name', 'city_name', 'verification_status', 'number_of_space_spot', 'status');
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

        $query = "select 
                  parking_spot.*,
                  country.country_name,
                  state.state_name 
                  from parking_spot 
                  left join country on country.id = parking_spot.country_id 
                  left join state on state.id = parking_spot.state_id";

        if ($SearchType == 'ORLIKE') {
            $likeStr = Helper::or_like_search($data);
        }
        if ($SearchType == 'ANDLIKE') {
            $likeStr = Helper::and_like_search($data);
        }

        if ($likeStr) {
            $query .= " Where parking_spot.is_delete='No' AND" . $likeStr;
        }else{
            $query .= " Where parking_spot.is_delete='No' ";
        }

        $query .= ' order by ' . $order_by . ' ' . $sort_order;
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
                    if ($result_row->verification_status == 'Yes') {
                        $verification_status = 'Verified';
                    } else if ($result_row->verification_status == 'No') {
                        $verification_status = 'Verification Pending';
                    }

                    $row[] = $result_row->id;
                    $row[] = $result_row->address;
                    $row[] = $result_row->postal_code;
                    $row[] = $result_row->country_name;
                    $row[] = $result_row->state_name;
                    $row[] = $result_row->city_name;
                    $row[] = $verification_status;
                    $row[] = $result_row->number_of_space_spot;
                    $row[] = $result_row->status;
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
                    if ($key == 'address' && !empty($val)) {
                        $QueryStr[$i]['Field'] = 'parking_spot.address';
                        $QueryStr[$i]['Value'] = $val;
                        $QueryStr[$i]['Operator'] = 'LIKE';
                    }
                    if ($key == 'postal_code' && !empty($val)) {
                        $QueryStr[$i]['Field'] = 'parking_spot.postal_code';
                        $QueryStr[$i]['Value'] = $val;
                        $QueryStr[$i]['Operator'] = 'LIKE';
                    }
                    if ($key == 'country_name' && !empty($val)) {
                        $QueryStr[$i]['Field'] = 'country.country_name';
                        $QueryStr[$i]['Value'] = $val;
                        $QueryStr[$i]['Operator'] = 'LIKE';
                    }
                    if ($key == 'state_name' && !empty($val)) {
                        $QueryStr[$i]['Field'] = 'state.state_name';
                        $QueryStr[$i]['Value'] = $val;
                        $QueryStr[$i]['Operator'] = 'LIKE';
                    }
                    if ($key == 'city_name' && !empty($val)) {
                        $QueryStr[$i]['Field'] = 'parking_spot.city_name';
                        $QueryStr[$i]['Value'] = $val;
                        $QueryStr[$i]['Operator'] = 'LIKE';
                    }
                    if ($key == 'verification_status' && !empty($val)) {
                        $QueryStr[$i]['Field'] = 'parking_spot.verification_status';
                        $QueryStr[$i]['Value'] = $val;
                        $QueryStr[$i]['Operator'] = '=';
                    }
                    if ($key == 'status' && !empty($val)) {
                        $QueryStr[$i]['Field'] = 'parking_spot.status';
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

        //$query = 'select parking_spot.*,country.country_name,state.state_name from parking_spot left join country on country.id = parking_spot.country_id left join state on state.id = parking_spot.state_id';
        $query = "select 
                  parking_spot.*,
                  country.country_name,
                  state.state_name 
                  from parking_spot 
                  left join country on country.id = parking_spot.country_id 
                  left join state on state.id = parking_spot.state_id";

        if ($SearchType == 'ORLIKE') {
            $likeStr = Helper::or_like_search($data);
        }
        if ($SearchType == 'ANDLIKE') {
            $likeStr = Helper::and_like_search($data);
        }

        if ($likeStr) {
            $query .= " Where parking_spot.is_delete='No' AND" . $likeStr;
        }else{
            $query .= " Where parking_spot.is_delete='No' ";
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
        $del_parking_spot = DB::table('parking_spot')
            ->where('id', $id)
            ->update([
                'is_delete' => 'Yes'
            ]);
        
        /*$fetchMuiltiimagedata = DB::select("select * from parking_spot_images Where parking_spot_id In($id)");
       
        foreach ($fetchMuiltiimagedata as $gallery) {
            $removegalleryimage = $gallery->uploaded_image;
            if (file_exists(public_path('uploads/parkingspot_images/'.$removegalleryimage))) {
                unlink(public_path('uploads/parkingspot_images/'.$removegalleryimage));
            }

        }

        $del_parking_spot_image = DB::delete('DELETE FROM `parking_spot_images` WHERE parking_spot_id IN (' . $id . ')');
        $del_parking_spot = DB::delete('DELETE FROM `parking_spot` WHERE id IN (' . $id . ')');*/

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

    public function manageparkingspotGallery($id) {
        $parking_spot_images = DB::table('parking_spot_images')->where('parking_spot_id', '=', $id)->get();
        return view('parkingspot/manageparkingspotImage', ['parking_spot_images' => $parking_spot_images, 'parking_spot_id' => $id]);
    }

    public function Saveparkingspot(Request $request, $parking_spot_id) {
        if ($request->hasFile('uploaded_image')) {
            $UserId = $request->session()->get('userData');
            $user_id = $UserId->id;

            $files = $request->file('uploaded_image');
            foreach ($files as $file) {
                $last_inserted_id = $parking_spot_id;
                $filename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $picture = date('His') . $filename;
                $destinationPath = public_path('uploads/parkingspot_images');
                $file->move($destinationPath, $picture);

                $parking_spot_images_id = DB::table('parking_spot_images')->insert(
                        [
                            'parking_spot_id' => $last_inserted_id,
                            'uploaded_image' => $picture,
                            'created_date' => Helper::get_curr_datetime(),
                            'created_by' => $user_id
                        ]
                );
            }
        }
        return redirect('parkingspot/manageparkingspotGallery/' . $parking_spot_id);
    }

    public function deleteparkingspotGallery(Request $request) {
        $id = $request->id;

        //Code to check if the id from the url exists in the table or not starts here. 
        //If id does not exists, redirect it to 404 page.
        $id_exists = Helper::check_id_exists($id, 'parking_spot_images');
        if (!$id_exists) {
            return view('error.404');
        }
        //Code to check id ends here

        $fetchdata = DB::select("select * from parking_spot_images Where id In($id)");
        $fetchImageName = $fetchdata[0]->uploaded_image;
        unlink(public_path('uploads/parkingspot_images/' . $fetchImageName));

        $del_user = DB::delete('DELETE FROM `parking_spot_images` WHERE id IN (' . $id . ')');

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

            $parking_spot_details = DB::table('parking_spot')->select('verification_code','location')->where('id', $id)->where('verification_status', 'Yes')->first();

            $user_details = DB::table('users')->select('id','email', 'firstname', 'lastname')->where('id', $parking_spot->users_id)->where('status', 'Active')->first();
            if (count($user_details) > 0) {
                $name = ucfirst($user_details->firstname) . ' ' . $user_details->lastname;
                $verification_code = $parking_spot_details->verification_code;
                $EmailTemplate = EmailTemplate::Where('id', 5)->Where('status', 'Active')->first();
                $location = $parking_spot_details->location;

                $Email_parking_spot_template = array('USERNAME' => $name, 'Email' => $user_details->email, 'VERIFICATIONCODE' => $verification_code, 'location' => $location);
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
                            'type' => 'spot_admin_varification',
                            'message' => 'Parking Spot verified successfully.'
                        ];
                        /*$message_title = "Parking Spot \r\nYour Parking spot has been verified by admin";*/
                        $message_title = "Parking Spot \r\nYour Parking Spot has been verified by Qu!";

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

            }
        }
    }

}
