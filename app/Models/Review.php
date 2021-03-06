<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use App\Http\Helper;


class Review extends Model{
    
    protected $table = 'review';
    
    protected $id = '';
    protected $booking_id = '';
    protected $users_id = '';
    protected $questions_answer = '';
    protected $parking_spot_id = '';
    protected $rating = '';
    protected $status = '';
    protected $feedback_description = '';
    protected $created_date = '';
    protected $updated_date = '';
    protected $created_by = '';
    protected $updated_by = '';
    
    protected $data = '';
    protected $SearchType = '';
    protected $order_by = '';
    protected $sort_order = '';
    protected $limit = '';
    protected $offset = '';
    
    function set_id($val){ $this->id=$val; }
    function set_booking_id($val){ $this->booking_id=$val; }
    function set_users_id($val){ $this->users_id=$val; }
    function set_questions_answer($val){ $this->questions_answer=$val; }
    function set_parking_spot_id($val){ $this->parking_spot_id=$val; }
    function set_rating($val){ $this->rating=$val; }
    function set_status($val){ $this->status=$val; }
    function set_feedback_description($val){ $this->feedback_description=$val; }
    function set_created_date($val){ $this->created_date=$val; }
    function set_updated_date($val){ $this->updated_date=$val; }
    function set_created_by($val){ $this->created_by=$val; }
    function set_updated_by($val){ $this->updated_by=$val; }
    
    function set_data($val){ $this->data=$val; }
    function set_SearchType($val){ $this->SearchType=$val; }
    function set_order_by($val){ $this->order_by=$val; }
    function set_sort_order($val){ $this->sort_order=$val; }
    function set_limit($val){ $this->limit=$val; }
    function set_offset($val){ $this->offset=$val; }
    
    function set_fields($val){ $this->fields=$val; }
    
    public function review_reting_details_by_renter_id_and_booking_id($booking_id,$user_id){
        $sql="
               SELECT 
                    $this->table.questions_answer,
                    $this->table.parking_spot_id,
                    $this->table.rating,
                    $this->table.status,
                    $this->table.feedback_description,
                    $this->table.created_date,
                    `users`.id,    
                    `users`.firstname,
                    `users`.lastname
               FROM $this->table
               LEFT JOIN `users` ON `users`.id = '$user_id'     
               WHERE $this->table.booking_id = '$booking_id'
               AND $this->table.users_id = '$user_id'    
               ";
           $results = DB::select( DB::raw($sql) );
           return $results;
    }
    
    public function review_reting_details_by_booking_id_for_host($booking_id){
        $sql="
               SELECT 
                    $this->table.questions_answer,
                    $this->table.parking_spot_id,
                    $this->table.rating,
                    $this->table.status,
                    $this->table.feedback_description,
                    $this->table.created_date,
                    `users`.id,    
                    `users`.firstname,
                    `users`.lastname
               FROM $this->table
               LEFT JOIN `booking` ON `booking`.id = '$booking_id'
               LEFT JOIN `parking_spot` ON `parking_spot`.id = `booking`.parking_spot_id    
               LEFT JOIN `users` ON `users`.id = `parking_spot`.users_id
               WHERE $this->table.booking_id = '$booking_id'
               AND $this->table.parking_spot_id = `booking`.parking_spot_id
               AND $this->table.users_id = `parking_spot`.users_id 
               ";
           $results = DB::select( DB::raw($sql) );
           return $results;
    }
    
    public function get_question_by_id($id){
        $sql="
               SELECT 
                    id,
                    questionnaires_title,
                    status
               FROM `review_questionnaires`
               WHERE id = '$id'
               ";
           $results = DB::select( DB::raw($sql) );
           return $results;
    }
    
 
}
