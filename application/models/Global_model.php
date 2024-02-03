<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

#[AllowDynamicProperties]
class Global_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        
        $this->load->database();        
    }

    public function query($query, $type = "") {
        if (substr($query, 0, 6) == "SELECT") {
            $query = $this->db->query($query);
            $result = ($query->num_rows() > 0) ? $query->result_array() : [];

            if ($type == "count") {
                return count($result);
            } else if ($type == "single") {
                return count($result) == 0 ? false : $result[0];
            }
            return $result;
        } else if (substr($query, 0, 6) == "INSERT") {
            $query = $this->db->query($query);
            return $query ? $this->db->insert_id() : false;
        } else if (substr($query, 0, 6) == "UPDATE") {
            $query = $this->db->query($query);
            return $query ? true : false;
        } else if (substr($query, 0, 6) == "DELETE") {
            $query = $this->db->query($query);
            return $query ? true : false;
        }
    }

}