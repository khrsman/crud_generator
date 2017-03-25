<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class #controller# extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('#model#');
    }

    function index(){
    $data['table_content'] = $this->#model#->get_data();
		$this->load->view('#view#',$data);
    }

    function add(){
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        #ambil data
        $params = array();
        parse_str($_POST['data'], $params);
      }else{
        $this->load->view('#view_add#');
      }

    }


}
?>
