<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class #model# extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
//Do your magic here
    }

    public function get_data(){
        $query = $this->db->get('#table_name#');
        return $query->result_array();
    }


}
