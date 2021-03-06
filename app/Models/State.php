<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use App\Http\Helper;


class State extends Model{
    
    protected $table = 'state';
     
    protected $id = '';
    protected $country_id = '';
    protected $state_name = '';
    protected $status = '';
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
    function set_country_id($val){ $this->country_id=$val; }
    function set_state_name($val){ $this->state_name=$val; }
    function set_status($val){ $this->status=$val; }
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
    
    
    public function get_data_for_state(){
        $sql = 'SELECT 
                    '.$this->table.'.id,
                    country.country_name,
                    '.$this->table.'.state_name,
                    '.$this->table.'.status
                FROM '.$this->table.'
                LEFT JOIN country ON '.$this->table.'.country_id = country.id
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
 
    public function count_all_state_grid() {
        $sql = 'SELECT 
                    '.$this->table.'.id,
                    country.country_name,
                    '.$this->table.'.state_name,
                    '.$this->table.'.status
                FROM '.$this->table.'
                LEFT JOIN country ON '.$this->table.'.country_id = country.id
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
    
    public function update_status_of_state($id,$status){
        return DB::update('update '.$this->table.' set status = "' . $status . '" Where id IN (' . $id . ')');
    }
    
    public function delete_record_of_state_id_ids($id){
        return DB::delete('DELETE FROM '.$this->table.' WHERE id IN (' . $id . ')');
    }
    
    public function insert_state(){
        return DB::table($this->table)->insertGetId([
            'country_id' => $this->country_id,
            'state_name' => $this->state_name,
            'status' => $this->status,
            'created_date' => $this->created_date,
            'updated_date' => $this->updated_date,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by
        ]);
    }
    
    public function update_state(){
        return DB::table($this->table)->where('id', $this->id)->update([
                'country_id' => $this->country_id,
                'state_name' => $this->state_name,
                'status' => $this->status,
                'updated_date' => $this->updated_date,
                'updated_by' => $this->updated_by
            ]);
    }


    public function get_state_list_by_country_id($country_id){
        $sql="
               SELECT 
                    id,
                    state_name
               FROM $this->table
               WHERE status = 'Active'
               AND country_id = '$country_id'
               ";
           $results = DB::select( DB::raw($sql) );
           return $results;
    }
    
    

    public function testing2(){
         $sql="
               SELECT *
               FROM $this->table
               ";
           $results = DB::select( DB::raw($sql) );
           return $results;
    }
    
 
}
