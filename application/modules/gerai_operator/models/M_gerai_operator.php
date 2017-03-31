<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_gerai_operator extends CI_Model
{

    public function __construct()
    {
        parent::__construct();

    }

    public function get($id = NULL){
      if($id){
        $this->db->limit(1);
      }
        $query = $this->db->get('gerai_operator');
        return $query->result_array();
    }

    public function insert($data){
        $query = $this->db->insert('gerai_operator',$data);
        return $query;
    }

    public function update_by_id($data, $id){
        $this->db->where('#primary_key#','$id');
        $query = $this->db->update('gerai_operator',$data);
    }

    public function delete_by_id($id){
        $this->db->where('#primary_key#',$id);
        $query = $this->db->update('gerai_operator',$data);
    }


}
