<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

#[AllowDynamicProperties]
class User_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        
        $this->load->database();        
        $this->table = 'users';
    }

    function getRows($params = array()){
        $this->db->select('*');
        $this->db->from($this->table);
        
        if(array_key_exists("conditions", $params)) {
            foreach($params['conditions'] as $key => $value) {
                $this->db->where($key, $value);
            }
        }

        if(array_key_exists("id", $params)){
            $this->db->where('id', $params['id']);
            $query = $this->db->get();
            $result = $query->row_array();
        } else {
            if(array_key_exists("start", $params) && array_key_exists("limit", $params)) {
                $this->db->limit($params['limit'], $params['start']);
            } else if(!array_key_exists("start", $params) && array_key_exists("limit", $params)) {
                $this->db->limit($params['limit']);
            }
            
            if(array_key_exists("returnType", $params) && $params['returnType'] == 'count') {
                $result = $this->db->count_all_results();    
            } else if(array_key_exists("returnType", $params) && $params['returnType'] == 'single') {
                $query = $this->db->get();
                $result = ($query->num_rows() > 0)?$query->row_array():false;
            } else {
                $query = $this->db->get();
                $result = ($query->num_rows() > 0)?$query->result_array():[];
            }
        }

        return $result;
    }
    
    public function insert($data){
        if(!array_key_exists("create_date", $data)) {
            $data['create_date'] = date("Y-m-d H:i:s");
        }
        if(!array_key_exists("update_date", $data)) {
            $data['update_date'] = date("Y-m-d H:i:s");
        }        
        $insert = $this->db->insert($this->table, $data);        
        return $insert ? $this->db->insert_id() : false;
    }

    public function update($data, $id){
        if(!array_key_exists('update_date', $data)) {
            $data['update_date'] = date("Y-m-d H:i:s");
        }        
        $update = $this->db->update($this->table, $data, array('id'=>$id));        
        return $update ? true : false;
    }
    
    public function delete($id){
        $delete = $this->db->delete('users', array('id' => $id));
        return $delete ? true : false;
    }

}