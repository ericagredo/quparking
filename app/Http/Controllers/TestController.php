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
use Mail;

class TestController extends Controller {
    
    public  $param=array();
    
    public function __construct() {
        parent::login_user_details();
    }
    
    
    public function test() {
        echo "hello";
        exit;
    }
}