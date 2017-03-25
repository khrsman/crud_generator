<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Detail_transaksi extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('M_detail_transaksi');
    }

    function index(){
    $data['table_content'] = $this->M_detail_transaksi->get_data();
		$this->load->view('v_detail_transaksi',$data);
    }

    function add(){
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        #ambil data
        $params = array();
        parse_str($_POST['data'], $params);
      }else{
        $this->load->view('v_add_detail_transaksi');
      }

    }


}
?>
