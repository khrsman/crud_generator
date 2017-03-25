<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_detail_transaksi extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
//Do your magic here
    }

    public function get_data(){
        $query = $this->db->get('detail_transaksi');
        return $query->result_array();
    }


}
