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
use Illuminate\Support\Facades\Cookie;
use App\Http\Helper;
use App\Models\Users;
use App\Models\EmailTemplate;
use App\Models\PromoCode;
use Mail;

class AdminPromocodeController extends Controller {

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
        return view('promocode/promocodeList');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show() {
        $promocodeList = DB::table('promocode')->get();
        return view('promocode/promocodeList', ['promocodeList' => $promocodeList]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('promocode/createpromocode');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $this->validate($request, [
            'promo_name' => 'required|max:255',
            'status' => 'required',
            'discount' => 'required'
        ]);

        $UserId = $request->session()->get('userData');
        $user_id = $UserId->id;

        $users = DB::table('promocode')->insertGetId(
                [
                    'promo_code' => substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890"), -6),
                    'promo_name' => $request->promo_name,
                    'status' => $request->status,
                    'discount' => $request->discount,
                    'promo_start_date' => date('Y-m-d', strtotime($request->promo_start_date)),
                    'promo_end_date' => date('Y-m-d', strtotime($request->promo_end_date)),
                    'created_date' => Helper::get_curr_datetime(),
                    'created_by' => $user_id
                ]
            );

            return redirect('promocode/promocodeList');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $promocode = DB::table('promocode')->where('id', $id)->first();
        return view('promocode/editpromocode', ['promocode' => $promocode]);
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
            'promo_name' => 'required|max:255',
            'status' => 'required',
            'discount' => 'required'
        ]);

        $UserId = $request->session()->get('userData');
        $user_id = $UserId->id;
        
        $users = DB::table('promocode')->where('id', $id)->update([
            'promo_name' => $request->get('promo_name'),
            'status' => $request->get('status'),
            'discount' => $request->get('discount'),
            'promo_start_date' => date('Y-m-d', strtotime($request->get('promo_start_date'))),
            'promo_end_date' => date('Y-m-d', strtotime($request->get('promo_end_date'))),
            'updated_date' => Helper::get_curr_datetime(),
            'updated_by' => $user_id
        ]);
        return redirect('promocode/promocodeList');
    }

    public function ajaxpromocodeList($order_by = "id", $sort_order = "asc", $search = "all", $offset = 0) {
        /* Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
         */

        $aColumns = array('id', 'promo_name', 'promo_code', 'discount', '', '' , 'status');
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

        $query = 'select promocode.* from promocode';

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
        
        // echo $query;die;
        
        $result = DB::select($query);


        $data = array();
        if (count($result) > 0) {
            $data['result'] = $result;
            $data['totalRecord'] = $this->count_all_Promo_grid($search_data, $SearchType);
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
                    $row[] = $result_row->promo_name;
                    $row[] = $result_row->promo_code;
                    $row[] = $result_row->discount;
                    $row[] = ($result_row->promo_start_date != '0000-00-00')? date('m/d/Y', strtotime($result_row->promo_start_date)) : '';
                    $row[] = ($result_row->promo_end_date != '0000-00-00')? date('m/d/Y', strtotime($result_row->promo_end_date)) : '';
                    $row[] = $result_row->status;
                    $row[] = array();
                    $output['aaData'][] = $row;
                }
            }
        }
       echo json_encode($output);
    }

    /* =============== Start : Trim search function ======================= */

    public function trim_serach_data($search_data, $SearchType) {
        $QueryStr = array();

        if (!empty($search_data)) {
            if ($SearchType == 'ANDLIKE') {
                $i = 0;
                foreach ($search_data as $key => $val) {
                    if ($key == 'promo_name' && !empty($val)) {
                        $QueryStr[$i]['Field'] = 'promo_name';
                        $QueryStr[$i]['Value'] = $val;
                        $QueryStr[$i]['Operator'] = 'LIKE';
                    }
                    if ($key == 'promo_code' && !empty($val)) {
                        $QueryStr[$i]['Field'] = 'promo_code';
                        $QueryStr[$i]['Value'] = $val;
                        $QueryStr[$i]['Operator'] = 'LIKE';
                    }
                    if ($key == 'discount' && !empty($val)) {
                        $QueryStr[$i]['Field'] = 'discount';
                        $QueryStr[$i]['Value'] = $val;
                        $QueryStr[$i]['Operator'] = '=';
                    }
                    if ($key == 'status' && !empty($val)) {
                        $QueryStr[$i]['Field'] = 'status';
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
    public function count_all_Promo_grid($search_data, $SearchType) {
        $data = $this->trim_serach_data($search_data, $SearchType);

        $query = 'select promocode.* from promocode';

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
        $id_exists = Helper::check_id_exists($id, 'promocode');
        if (!$id_exists) {
            return view('error.404');
        }
        //Code to check id ends here
        $del_user = DB::delete('DELETE FROM `promocode` WHERE id IN (' . $id . ')');

        if ($del_user) {
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
    public function activeInactivepromocode(Request $request) {
        $id = $request->id;
        $status = $request->status;
        $mode = $request->mode;

        //Code to check if the id from the url exists in the table or not starts here. 
        //If id does not exists, redirect it to 404 page.
        $id_exists = Helper::check_id_exists($id, 'promocode');
        if (!$id_exists) {
            return view('error.404');
        }

        //Code to check id ends here
        $Update_status = DB::update('update promocode set status = "' . $status . '" Where id IN (' . $id . ')');

        if (count($Update_status) == 1) {
            echo count($Update_status);
        } else {
            echo 0;
        }
        exit;
    }
    
    public function sendPromocodeSource(Request $request){
        $id = $request->id;
        $EmailTemplate = EmailTemplate::Where('id', 3)->Where('status', 'Active')->first();
        $UsersList = Users::Where('status', 'Active')->get();
        $GetPromocode = PromoCode::Where('id', $id)->Where('status', 'Active')->first();
       
        if(count($UsersList) > 0){
            foreach($UsersList as $users){
                $EmailTemplate1 = array('SUBJECT'=>$EmailTemplate->subject, 'Username' => $users->username, 'PROMOCODE' => $GetPromocode->promo_code);
                $data = array('Subject'=> $EmailTemplate->subject, 'to_email' => $users->email);
                $message = '';
                //=== Mail Send Functionality
                Mail::send('admin.emails.send_promocode_users', ['EmailTemplate' => $EmailTemplate1, 'message' => $message], function ($message) use ($data) {
                    $message->from('troodeveloper@gmail.com', $data['Subject']);
                    //$message->to('mayuri.patel@trootech.com')->subject($data['Subject']);
                    $message->to($data['to_email'])->subject($data['Subject']);
                }); 
            }
            return 1;
        }
        return 0;
    }
}
