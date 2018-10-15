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
use Mail;

class AdminPricingController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
      
    }

    public function index() {
        return view('pricing/pricingList');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show() {
        $pricingList = DB::table('pricing')->get();
        return view('pricing/pricingList', ['pricingList' => $pricingList]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('pricing/createpricing');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $this->validate($request, [
            'status' => 'required'
        ]);

        $UserId = $request->session()->get('userData');
        $user_id = $UserId->id;

            $users = DB::table('pricing')->insertGetId(
                    [
                        'no_of_hours' => $request->no_of_hours,
                        'hourly_price' => $request->hourly_price,
                        'no_of_days' => $request->no_of_days,
                        'daily_price' => $request->daily_price,
                        'no_of_month' => $request->no_of_month,
                        'monthly_price' => $request->monthly_price,
                        'status' => $request->status,
                        'created_date' => Helper::get_curr_datetime(),
                        'created_by' => $user_id
                    ]
            );
            return redirect('pricing/pricingList');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $pricing = DB::table('pricing')->where('id', $id)->first();
        return view('pricing/editpricing', ['pricing' => $pricing]);
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
            'status' => 'required'
        ]);

        $UserId = $request->session()->get('userData');
        $user_id = $UserId->id;
        
        $users = DB::table('pricing')->where('id', $id)->update([
                'no_of_hours' => $request->get('no_of_hours'),
                'hourly_price' => $request->get('hourly_price'),
                'no_of_days' => $request->get('no_of_days'),
                'daily_price' => $request->get('daily_price'),
                'no_of_month' => $request->get('no_of_month'),
                'monthly_price' => $request->get('monthly_price'),
                'status' => $request->get('status'),
                'updated_date' => Helper::get_curr_datetime(),
                'updated_by' => $user_id
            ]);
            return redirect('pricing/pricingList');
    }

    public function ajaxPricingList($order_by = "id", $sort_order = "asc", $search = "all", $offset = 0) {
        /* Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
         */

        $aColumns = array('id', 'no_of_hours', 'hourly_price', 'no_of_days', 'daily_price', 'no_of_month', 'monthly_price', 'status');
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

        $query = 'select pricing.* from pricing';

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


        $data = array();
        if (count($result) > 0) {
            $data['result'] = $result;
            $data['totalRecord'] = $this->count_all_pricing_grid($search_data, $SearchType);
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
                    $row[] = $result_row->no_of_hours;
                    $row[] = $result_row->hourly_price;
                    $row[] = $result_row->no_of_days;
                    $row[] = $result_row->daily_price;
                    $row[] = $result_row->no_of_month;
                    $row[] = $result_row->monthly_price;
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
    public function count_all_pricing_grid($search_data, $SearchType) {
        $data = $this->trim_serach_data($search_data, $SearchType);

        $query = 'select pricing.* from pricing';

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
        $id_exists = Helper::check_id_exists($id, 'pricing');
        if (!$id_exists) {
            return view('error.404');
        }
        //Code to check id ends here
        $del_user = DB::delete('DELETE FROM `pricing` WHERE id IN (' . $id . ')');

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
    public function activeInactivepricing(Request $request) {
        $id = $request->id;
        $status = $request->status;
        $mode = $request->mode;

        //Code to check if the id from the url exists in the table or not starts here. 
        //If id does not exists, redirect it to 404 page.
        $id_exists = Helper::check_id_exists($id, 'pricing');
        if (!$id_exists) {
            return view('error.404');
        }

        //Code to check id ends here
        $Update_status = DB::update('update pricing set status = "' . $status . '" Where id IN (' . $id . ')');

        if (count($Update_status) == 1) {
            echo count($Update_status);
        } else {
            echo 0;
        }
        exit;
    }
}
