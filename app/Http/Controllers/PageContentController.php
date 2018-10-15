<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Helper;

class PageContentController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show() {
        return view('staticpages/staticpageslist');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('staticpages/addstaticpages');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request   
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {

        $this->validate($request, [
            'page_name' => 'required|max:255',
            'page_title' => 'required|max:255',
            'meta_title' => 'required|max:255',
            'meta_keyword' => 'required|max:255',
            'status' => 'required',
            'meta_description' => 'required'
        ]);

        $UserId = $request->session()->get('userData');
        $user_id = $UserId->id;

        $page_content = DB::table('page_content')->insert(
                [
                    'page_name' => $request->page_name,
                    'page_title' => $request->page_title,
                    'meta_title' => $request->meta_title,
                    'meta_keyword' => $request->meta_keyword,
                    'status' => $request->status,
                    'meta_description' => $request->meta_description,
                    'page_description' => $request->page_description,
                    'created_date' => Helper::get_curr_datetime(),
                    'created_by' => $user_id
                ]
        );
        return redirect('staticpages/staticpagelist');
    }

    public function ajaxstaticPageList($order_by = "id", $sort_order = "asc", $search = "all", $offset = 0) {
        $aColumns = array('id', 'page_name', 'page_title','','','','status');
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

        $query = 'select page_content.* from page_content';

        if ($SearchType == 'ORLIKE') {
            $likeStr = Helper::or_like_search($data);
        }
        if ($SearchType == 'ANDLIKE') {
            $likeStr = Helper::and_like_search($data);
        }

        if ($likeStr) {
            $query .= ' Where ' . $likeStr;
        }
        
       // echo $query;die;

        $query .= ' order by ' . $order_by . ' ' . $sort_order;
        $query .= ' limit ' . $limit . ' OFFSET ' . $offset;
        //  echo $query;exit;
        $result = DB::select($query);

        $data = array();
        if (count($result) > 0) {
            $data['result'] = $result;
            $data['totalRecord'] = $this->count_all_page_content_grid($search_data, $SearchType);
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
                    $row[] = $result_row->page_name;
                    $row[] = $result_row->page_title;
                    $row[] = $result_row->meta_title;
                    $row[] = $result_row->meta_keyword;
                    $row[] = $result_row->page_description;
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
                    if ($key == 'page_name' && !empty($val)) {
                        $QueryStr[$i]['Field'] = 'page_name';
                        $QueryStr[$i]['Value'] = $val;
                        $QueryStr[$i]['Operator'] = 'LIKE';
                    }
                    if ($key == 'page_title' && !empty($val)) {
                        $QueryStr[$i]['Field'] = 'page_title';
                        $QueryStr[$i]['Value'] = $val;
                        $QueryStr[$i]['Operator'] = 'LIKE';
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
    public function count_all_page_content_grid($search_data, $SearchType) {
        $data = $this->trim_serach_data($search_data, $SearchType);

        $query = 'select page_content.* from page_content';

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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $page_content = DB::table('page_content')->where('id', $id)->first();
        return view('staticpages/editstaticpages', ['page_content' => $page_content]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        
        $this->validate($request, [
            'page_name' => 'required|max:255',
            'page_title' => 'required|max:255',
            'meta_title' => 'required|max:255',
            'meta_keyword' => 'required|max:255',
            'status' => 'required',
            'meta_description' => 'required'
        ]);

        $UserId = $request->session()->get('userData');
        $user_id = $UserId->id;
        
        $page_content = DB::table('page_content')->where('id', $id)->update([
            'page_name' => $request->get('page_name'),
            'page_title' => $request->get('page_title'),
            'meta_title' => $request->get('meta_title'),
            'meta_keyword' => $request->get('meta_keyword'),
            'status' => $request->get('status'),
            'meta_description' => $request->get('meta_description'),
            'page_description' => $request->get('page_description'),
            'updated_date' => Helper::get_curr_datetime(),
            'updated_by' => $user_id
        ]);
        
        return redirect('staticpages/staticpagelist');
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
        $id_exists = Helper::check_id_exists($id, 'page_content');
        if (!$id_exists) {
            return view('error.404');
        }
        //Code to check id ends here

        $del_user = DB::delete('DELETE FROM `page_content` WHERE id IN (' . $id . ')');

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
    public function activeInactiveStatus(Request $request) {
        $id = $request->id;
        $status = $request->status;
        $mode = $request->mode;

        //Code to check if the id from the url exists in the table or not starts here. 
        //If id does not exists, redirect it to 404 page.
        $id_exists = Helper::check_id_exists($id, 'page_content');
        if (!$id_exists) {
            return view('error.404');
        }
        //Code to check id ends here

        $Update_status = DB::update('update page_content set status = "' . $status . '" Where id IN (' . $id . ')');

        if (count($Update_status) == 1) {
            echo count($Update_status);
        } else {
            echo 0;
        }
        exit;
    }

}
