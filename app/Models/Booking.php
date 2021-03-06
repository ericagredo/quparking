<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use App\Http\Helper;


class Booking extends Model{
    
    protected $table = 'booking';
     
    protected $id = '';
    protected $parking_spot_id = '';
    protected $users_id = '';
    protected $entry_date_time = '';
    protected $exit_date_time = '';
    protected $total_time = '';
    protected $booking_status = '';
    protected $status = '';
    protected $booking_amount = '';
    protected $booking_date = '';
    protected $booking_time = '';
    protected $generated_booking_id = '';
    protected $booking_hours = '';
    protected $booking_days = '';
    protected $booking_month = '';
    protected $booking_type = '';
    protected $cancelled_by = '';
    protected $cancellation_fee = '';
    protected $additional_credited_amount = '';
    protected $paid_amount = '';
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
    function set_parking_spot_id($val){ $this->parking_spot_id=$val; }
    function set_users_id($val){ $this->users_id=$val; }
    function set_entry_date_time($val){ $this->entry_date_time=$val; }
    function set_total_time($val){ $this->total_time=$val; }
    function set_booking_status($val){ $this->booking_status=$val; }
    function set_status($val){ $this->status=$val; }
    function set_booking_amount($val){ $this->booking_amount=$val; }
    function set_booking_date($val){ $this->booking_date=$val; }
    function set_booking_time($val){ $this->booking_time=$val; }
    function set_generated_booking_id($val){ $this->generated_booking_id=$val; }
    function set_booking_hours($val){ $this->booking_hours=$val; }
    function set_booking_days($val){ $this->booking_days=$val; }
    function set_booking_month($val){ $this->booking_month=$val; }
    function set_booking_type($val){ $this->booking_type=$val; }
    function set_cancelled_by($val){ $this->cancelled_by=$val; }
    function set_cancellation_fee($val){ $this->cancellation_fee=$val; }
    function set_additional_credited_amount($val){ $this->additional_credited_amount=$val; }
    function set_paid_amount($val){ $this->paid_amount=$val; }
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
    
    
    public function get_data_for_booking(){
        $sql = 'SELECT 
                    '.$this->table.'.id,
                    users.firstname,
                    users.lastname,
                    users.profile_image,
                    users.contact_number,
                    host.firstname as host_firstname,
                    host.lastname as host_lastname,
                    host.profile_image as host_profile_image,
                    host.contact_number as host_contact_number,
                    parking_spot.address,
                    parking_spot.postal_code,
                    parking_spot.country_id,
                    parking_spot.state_id,
                    parking_spot.city_name,
                    '.$this->table.'.generated_booking_id,
                    '.$this->table.'.booking_date,
                    '.$this->table.'.booking_time,
                    '.$this->table.'.booking_amount,
                    '.$this->table.'.booking_hours,
                    '.$this->table.'.booking_days,
                    '.$this->table.'.booking_month,
                    '.$this->table.'.booking_type,
                    '.$this->table.'.booking_status,
                    '.$this->table.'.status,
                    '.$this->table.'.users_id,
                    country.country_name,
                    state.state_name    
                FROM '.$this->table.'
                LEFT JOIN users ON '.$this->table.'.users_id = users.id
                LEFT JOIN parking_spot ON '.$this->table.'.parking_spot_id = parking_spot.id
                LEFT JOIN users as host ON parking_spot.users_id = host.id
                LEFT JOIN country ON country.id = parking_spot.country_id
                LEFT JOIN state ON state.id = parking_spot.state_id
                ';

           if ($this->SearchType == 'ORLIKE') {
               $likeStr = Helper::or_like_search($this->data);
           }
           if ($this->SearchType == 'ANDLIKE') {
               $likeStr = Helper::and_like_search($this->data);
           }
           

           if ($likeStr) {
               $sql .= ' Where ' . $likeStr;
           }
           $sql .= ' order by ' .$this->order_by . ' ' . $this->sort_order;
           $sql .= ' limit ' . $this->limit . ' OFFSET ' . $this->offset;
           //echo  $sql;
           $results = DB::select( DB::raw($sql) );
           return $results;
    }
    
    public function count_all_booking_grid() {
        $sql = 'SELECT 
                    '.$this->table.'.id,
                    users.firstname,
                    users.lastname,
                    users.profile_image,
                    users.contact_number,
                    host.firstname as host_firstname,
                    host.lastname as host_lastname,
                    host.profile_image as host_profile_image,
                    host.contact_number as host_contact_number,
                    parking_spot.address,
                    parking_spot.postal_code,
                    parking_spot.country_id,
                    parking_spot.state_id,
                    parking_spot.city_name,
                    '.$this->table.'.generated_booking_id,
                    '.$this->table.'.booking_date,
                    '.$this->table.'.booking_time,
                    '.$this->table.'.booking_amount,
                    '.$this->table.'.booking_hours,
                    '.$this->table.'.booking_days,
                    '.$this->table.'.booking_month,
                    '.$this->table.'.booking_type,
                    '.$this->table.'.booking_status,
                    '.$this->table.'.status,    
                    '.$this->table.'.users_id,
                    country.country_name,
                    state.state_name
                FROM '.$this->table.'
                LEFT JOIN users ON '.$this->table.'.users_id = users.id
                LEFT JOIN parking_spot ON '.$this->table.'.parking_spot_id = parking_spot.id
                LEFT JOIN users as host ON parking_spot.users_id = host.id
                LEFT JOIN country ON country.id = parking_spot.country_id
                LEFT JOIN state ON state.id = parking_spot.state_id
                ';

           if ($this->SearchType == 'ORLIKE') {
               $likeStr = Helper::or_like_search($this->data);
           }
           if ($this->SearchType == 'ANDLIKE') {
               $likeStr = Helper::and_like_search($this->data);
           }
           

           if ($likeStr) {
               $sql .= ' Where ' . $likeStr;
           }
        
        $results = DB::select( DB::raw($sql) );
        if (count($results) > 0) {
            return count($results);
        }
        return 0;
       
    }
    
    public function delete_record_of_booking_id_ids($id){
        return DB::delete('DELETE FROM '.$this->table.' WHERE id IN (' . $id . ')');
    }
    
    public function get_parking_spot_image($generated_booking_id){
        $sql="
               SELECT 
                    parking_spot_images.id,
                    $this->table.parking_spot_id,
                    parking_spot_images.uploaded_image
               FROM $this->table
               LEFT JOIN parking_spot_images ON parking_spot_images.parking_spot_id = $this->table.parking_spot_id       
               WHERE $this->table.generated_booking_id = '$generated_booking_id'    
               ";
           $results = DB::select( DB::raw($sql) );
           return $results;
    }
    
 
}
