<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class #model# extends CI_Model
{

    public function __construct()
    {
        parent::__construct();

    }

    public function get($id = NULL){
      if($id){
        $this->db->limit(1);
      }
        $query = $this->db->get('#table_name#');
        return $query->result_array();
    }

    public function insert($data){
        $query = $this->db->insert('#table_name#',$data);
        return $query;
    }

    public function update_by_id($data, $id){
        $this->db->where('#primary_key#','$id');
        $query = $this->db->update('#table_name#',$data);
    }

    public function delete_by_id($id){
        $this->db->where('#primary_key#',$id);
        $query = $this->db->delete('#table_name#');
    }


}
