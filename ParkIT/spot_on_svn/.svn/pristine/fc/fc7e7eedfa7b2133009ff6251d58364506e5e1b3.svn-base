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
use App\Models\EmailTemplate;
use Mail;

class AdminContactUsController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
    }

    public function index() {
        return view('contactus/contactusList');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function replytousercontactus(Request $request) {
        $id = $request->id;
        $description = $request->description;
        $contact_us = DB::table('contact_us')->where('id', $id)->first();
        if(count($contact_us) > 0){
            $name = $contact_us->first_name.' '.$contact_us->last_name;
            $EmailTemplate = EmailTemplate::Where('id', 4)->Where('status', 'Active')->first(); 
            
            $EmailTemplate1 = array('Name' => $name, 'Description' => $description);
            $data = array('Subject'=> $EmailTemplate->subject, 'to_email' => $contact_us->email);
            $message = '';
            //=== Mail Send Functionality
            Mail::send('admin.emails.contactusreplay', ['EmailTemplate' => $EmailTemplate1, 'message' => $message], function ($message) use ($data) {
                $message->from('troodeveloper@gmail.com', $data['Subject']);
                //$message->to('mayuri.patel@trootech.com')->subject($data['Subject']);
                $message->to($data['to_email'])->subject($data['Subject']);
            }); 
            return redirect('contactus/contactusList')->with('success', 'Reply to '.ucfirst($name).' mail Send Successfully.');
        }else{
            return 0;
        }
    }
    
    public function edit(Request $request) {
        $id = $request->id;
        return view('contactus/editcontactus', ['id'=> $id]);
    }

    public function ajaxcontactusList($order_by = "id", $sort_order = "asc", $search = "all", $offset = 0) {
         /* Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
         */

        $aColumns = array('id', 'first_name', 'last_name' , 'email', 'contact_number', 'state');
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

        $query = 'select contact_us.* from contact_us';

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
            $data['totalRecord'] = $this->count_all_contact_us_grid($search_data, $SearchType);
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
                    $row[] = $result_row->first_name;
                    $row[] = $result_row->last_name;
                    $row[] = $result_row->email;
                    $row[] = $result_row->contact_number;
                    $row[] = $result_row->state;
                    $row[] = $result_row->message_description;
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
                    if ($key == 'first_name' && !empty($val)) {
                        $QueryStr[$i]['Field'] = 'first_name';
                        $QueryStr[$i]['Value'] = $val;
                        $QueryStr[$i]['Operator'] = 'LIKE';
                    }
                    if ($key == 'last_name' && !empty($val)) {
                        $QueryStr[$i]['Field'] = 'last_name';
                        $QueryStr[$i]['Value'] = $val;
                        $QueryStr[$i]['Operator'] = 'LIKE';
                    }
                    if ($key == 'email' && !empty($val)) {
                        $email = str_replace('%40', '@', $val);
                        $QueryStr[$i]['Field'] = 'email';
                        $QueryStr[$i]['Value'] = $email;
                        $QueryStr[$i]['Operator'] = 'LIKE';
                    }
                    if ($key == 'contact_number' && !empty($val)) {
                        $QueryStr[$i]['Field'] = 'contact_number';
                        $QueryStr[$i]['Value'] = $val;
                        $QueryStr[$i]['Operator'] = 'LIKE';
                    }
                    if ($key == 'state' && !empty($val)) {
                        $location = str_replace('%2C ', ', ', $val);
                        $QueryStr[$i]['Field'] = 'state';
                        $QueryStr[$i]['Value'] = $location;
                        $QueryStr[$i]['Operator'] = '=';
                    }
                    $i++;
                }
            } 
        }
        return $QueryStr;
    }

    // =========== Start :  Count all Record in grid data =========//
    public function count_all_contact_us_grid($search_data, $SearchType) {
         $data = $this->trim_serach_data($search_data, $SearchType);

        $query = 'select contact_us.* from contact_us';

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
        $id_exists = Helper::check_id_exists($id, 'contact_us');
        if (!$id_exists) {
            return view('error.404');
        }
        //Code to check id ends here
        $del_user = DB::delete('DELETE FROM `contact_us` WHERE id IN (' . $id . ')');

        if ($del_user) {
            echo '1';
        } else {
            echo '0';
        }
        exit;
    }
}
