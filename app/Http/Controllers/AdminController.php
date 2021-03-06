<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\validate;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Helper;

use Mail;

class AdminController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        //  $this->middleware('auth');
        //$this->user = $guard->user();
    }
    
    public function index(Request $request) {
        $data = array();
        $spot_count = "SELECT 
                             count(id) as spot_count
                      FROM parking_spot 
                      WHERE is_delete = 'No' ";
        $spot_count = DB::Select($spot_count);
        array_push($data,$spot_count[0]);
        $active_booking = "SELECT 
                             count(id) as booking_count
                      FROM booking 
                      WHERE booking_status = 'Upcoming' ";
        $active_booking = DB::Select($active_booking);
        array_push($data,$active_booking[0]);
        $active_users = "SELECT 
                             count(id) as users_count
                      FROM users 
                      WHERE status = 'Active' ";
        $active_users = DB::Select($active_users);
        array_push($data,$active_users[0]);
        $cancel_booking = "SELECT 
                             count(id) as booking_count
                      FROM booking 
                      WHERE booking_status = 'Cancelled' ";
        $cancel_booking = DB::Select($cancel_booking);
        array_push($data,$cancel_booking[0]);
        /*echo '<pre>';
        print_r($data);
        echo '</pre>';
        exit;*/
        return view('admin/dashboard',['data'=>$data]);
    }

    public function notification_admin(){
         $data = array('new_spot'=>'','new_booking'=>'','cancelled_booking'=>'','surcharge_amount'=>'');
         foreach ($data as $key=>$val){
             $admin_new_spot = "SELECT 
                                COUNT(id) as counts
                                FROM admin_notification_management as p
                                WHERE p.notification_for= '$key' 
                                AND p.is_show = 'No'";
             $admin_new_spot = DB::Select($admin_new_spot);
             $data[$key] = $admin_new_spot[0]->counts;
         }
        $count_all = DB::table('admin_notification_management')->where('is_show', 'No')->count();
        $data['all_count'] = $count_all;
        return json_encode($data,1);
    }

    public function notification_admin_show(Request $request){

            $management = DB::table('admin_notification_management')
                ->where('notification_for', $request->notification_for)
                ->update([
                    'is_show' => 'Yes'
            ]);
            if($management){
                echo 1;
            }else{
                echo 0;
            }
    }


    public function adminLogin(Request $request) {
        return view('admin/login');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard(Request $request) {
        $email = $request->email;
        $password = md5($request->password);
        
        if ($request->remember_me == 1) {
            $cookie =  Cookie::queue('username', $email, time() + 31536000);
        } else {
            $cookie =  Cookie::queue('username', '', time() - 100);
        }

        $user = DB::table('tbl_adminuser')->where('email_address', $email)->where('password', $password)->first();
        $request->session()->put('userData', $user);
                
        if (count($user) > 0) {
            return redirect('admin/adminusers');
        } else {
            return redirect('admin')->with('message', 'Your email and password is not correct.');
        } 
    }

    public function forgotpassword(Request $request) {
        $email = $request->email;

        $user = DB::table('tbl_adminuser')->where('email_address', $email)->first();
        
        if (count($user) > 0) {
            $userID = $user->id;
            $token = sha1(uniqid(rand(), true) . 'Fu+uR3l1fE5EcuR!+y!');

            DB::table('tbl_adminuser')
                    ->where('id', $user->id)
                    ->update(['remember_token' => $token]);

            $user_token = DB::table('tbl_adminuser')->select('remember_token')->where('id', $user->id)->first();
            $final_token = $user_token->remember_token;
            $message = 'Send mail to set reset password Link.';

            //=== Mail Send Functionality
            Mail::send('admin.emails.resetpassword', ['userID' => $final_token, 'users' => $user, 'message' => $message], function ($message) use ($user) {
                $message->from('troodeveloper@gmail.com', 'Reset Password Link.');
                //$message->to('mayuri.patel@trootech.com')->subject('Reset Password Link.');
                $message->to($user->email_address)->subject('Reset Password Link.');
            });
            return redirect('admin')->with('success', 'Mail send successfully. please check mail.');
            //return view('admin/resetpasswordpage');
        } else {
            return redirect('admin')->with('message', 'Your email isnot correct.');
        }
    }

    public function resetpasswordpage(Request $request) {
        $token = $request->token;
        $user = DB::table('tbl_adminuser')->where('remember_token', $token)->first();
        return view('admin/resetpasswordpage', ['user' => $user]);
    }

    public function changepassword(Request $request) {
       
        $token = $request->check_token;
        $password = md5($request->password);

        $user = DB::table('tbl_adminuser')
                ->where('remember_token', $token)
                ->update(['password' => $password]);

        $updated_id = DB::table('tbl_adminuser')->where('remember_token', $token)->first();

        $user = DB::table('tbl_adminuser')
                ->where('id', $updated_id->id)
                ->update(['remember_token' => '']);

        // return view('admin/login');
        return redirect('admin')->with('success', 'Your password change successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show() {
        $AdminuserList = DB::table('tbl_adminuser')->get();
        return view('admin/adminuserList', ['adminUserList' => $AdminuserList]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('admin/createadmiuser');
    }
    
    /* Start - This function for check exist email record when do add / edit action*/
    public function CheckAdminEmailExist(Request $request) {
        $email = $request->email_address;

        $id = $request->id;
        if ($id != '') {
            $EmailResArray = DB::table('tbl_adminuser')->where('email_address', '=', $email)->where('id', '!=', $id)->first();
        } else {
            $EmailResArray = DB::table('tbl_adminuser')->where('email_address', '=', $email)->first();
        }

        if (count($EmailResArray) > 0) {
            echo '1';
            die;
        } else {
            echo '0';
            die;
        }
    }
    /* End - This function for check exist email record when do add / edit action*/

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {

         $this->validate($request,[
          'firstname'=>'required|max:255',
          'last_name'=>'required|max:255',
          'email_address'=>'required|email',
          'password'=>'required'
          ]);
         
        $count = DB::table('tbl_adminuser')->where('email_address', '=', $request->email_address)->count();
        if ($count == 0) {
            $users = DB::table('tbl_adminuser')->insert(
                    [
                        'first_name' => $request->firstname,
                        'last_name' => $request->last_name,
                        'email_address' => $request->email_address,
                        'password' => md5($request->password),
                        'status' => $request->status,
                        'role_id' => '1',
                        'createdDate' => date('Y-m-d H:i:s'),
                        'updatedDate' => date('Y-m-d H:i:s')
                    ]
            );
            return redirect('admin/adminusers');
        } else {
            $message = 'Email already exist.';
            Session::put('SUCCESS','FALSE');
            Session::put('MESSAGE', $message);
            return redirect()->back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $user = DB::table('tbl_adminuser')->where('id', $id)->first();
        return view('admin/editadmiuser', ['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminreate\Http\Response
     */
    public function update(Request $request, $id) {
        $count = DB::table('tbl_adminuser')->where('email_address', '=', $request->email_address)->where('id', '!=', $id)->count();
        if ($count == 0) {
            $users = DB::table('tbl_adminuser')->where('id', $id)->update([
                'first_name' => $request->get('firstname'),
                'last_name' => $request->get('last_name'),
                'email_address' => $request->get('email_address'),
                'status' => $request->get('status'),
                'updatedDate' => date('Y-m-d H:i:s')
            ]);
            return redirect('admin/adminusers');
        }else {
            $message = 'Email already exist.';
            Session::put('SUCCESS','FALSE');
            Session::put('MESSAGE', $message);
            return redirect()->back();
        }
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
        $id_exists = Helper::check_id_exists($id, 'tbl_adminuser');
        if (!$id_exists){
            return view('error.404'); 
        }
         
        //Code to check id ends here

        $del_user = DB::delete('DELETE FROM `tbl_adminuser` WHERE id IN (' . $id . ')');

        if ($del_user) {
            echo '1';
        } else {
            echo '0';
        }
        exit;
    }

    public function adminAjaxList($order_by = "id", $sort_order = "asc", $search = "all", $offset = 0) {
        /* Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
         */

        $aColumns = array('id', 'first_name', 'email_address', 'status', '');
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

        $query = 'select tbl_adminuser.* from tbl_adminuser';

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
            $data['totalRecord'] = $this->count_all_customer_grid($search_data, $SearchType);
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
                    $row[] = $result_row->email_address;
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
                    if ($key == 'first_name' && !empty($val)) {
                        $QueryStr[$i]['Field'] = 'first_name';
                        $QueryStr[$i]['Value'] = $val;
                        $QueryStr[$i]['Operator'] = 'LIKE';
                    }
                    if ($key == 'email_address' && !empty($val)) {
                        $QueryStr[$i]['Field'] = 'email_address';
                        $QueryStr[$i]['Value'] = $val;
                        $QueryStr[$i]['Operator'] = 'LIKE';
                    }
                    if ($key == 'status' && !empty($val)) {
                        $QueryStr[$i]['Field'] = 'tbl_adminuser.status';
                        $QueryStr[$i]['Value'] = $val;
                        $QueryStr[$i]['Operator'] = '=';
                    }


                    $i++;
                }
            } else {
                !empty($search_data['first_name']) ? $QueryStr['first_name'] = $search_data['first_name'] : "";
                !empty($search_data['email_address']) ? $QueryStr['email_address'] = $search_data['email_address'] : "";
                !empty($search_data['status']) ? $QueryStr['status'] = $search_data['status'] : "";
            }
        }
        return $QueryStr;
    }

    // =========== Start :  Count all Record in grid data =========//
    public function count_all_customer_grid($search_data, $SearchType) {
        $data = $this->trim_serach_data($search_data, $SearchType);

        $query = 'select tbl_adminuser.* from tbl_adminuser';
        
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
    public function activeInactiveStatus(Request $request) {

        $id = $request->id;
        $status = $request->status;
        $mode = $request->mode;

        //Code to check if the id from the url exists in the table or not starts here. 
        //If id does not exists, redirect it to 404 page.
        $id_exists = Helper::check_id_exists($id, 'tbl_adminuser');
        if (!$id_exists){
             return view('error.404');
        }
        //Code to check id ends here


        $Update_status = DB::update('update tbl_adminuser set status = "' . $status . '" Where id IN (' . $id . ')');

        if (count($Update_status) == 1) {
            echo count($Update_status);
        } else {
            echo 0;
        }
        exit;
    }

    public function editProfile(Request $request) {
       // $id = 1;
        $UserId = $request->session()->get('userData');
        $id = $UserId->id;
        $user = DB::table('tbl_adminuser')->where('id', $id)->first();
        return view('admin/editProfile', ['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminreate\Http\Response
     */
    public function updateProfile(Request $request) {
        //$id = 1;
        $UserId = $request->session()->get('userData');
        $id = $UserId->id;
        $users = DB::table('tbl_adminuser')->where('id', $id)->update([
            'first_name' => $request->get('firstname'),
            'last_name' => $request->get('last_name'),
            'email_address' => $request->get('email_address'),
            'status' => $request->get('status'),
            'updatedDate' => date('Y-m-d H:i:s')
        ]);
        return redirect('admin/viewProfile');
    }

    public function viewProfile(Request $request) {
        //$id = 1;
        $UserId = $request->session()->get('userData');
        $id = $UserId->id;
        $user = DB::table('tbl_adminuser')->where('id', $id)->first();
        return view('admin/viewProfile', ['user' => $user]);
    }

    public function profilechangePassword() {
        return view('admin/profilechangePassword');
    }

    public function changepwdProfile(Request $request) {
        $UserId = $request->session()->get('userData');
        $id = $UserId->id;
        $old_password = md5($request->old_password);
        $new_password = md5($request->new_password);

        $checkExistPwd = DB::table('tbl_adminuser')->where('password', $old_password)->first();


        if (count($checkExistPwd) > 0) {

            $Password = $checkExistPwd->password;

            if ($old_password == $Password) {
                $user = DB::table('tbl_adminuser')
                        ->where('id', $checkExistPwd->id)
                        ->update(['password' => $new_password]);

                // return view('admin/login');
                return redirect('admin/viewProfile')->with('success', 'Your password change successfully.');
            } else {
                return redirect('admin/viewProfile')->with('message', 'Your Password Does not match. Please Try Again');
            }
        } else {
            return redirect('admin/viewProfile')->with('message', 'Your Password Does not match. Please Try Again');
        }
    }

    public function logout(Request $request) {
       // $this->guard()->logout();
        $request->session()->flush();
        $request->session()->regenerate();
        return redirect('admin');
    }
    
   

}
