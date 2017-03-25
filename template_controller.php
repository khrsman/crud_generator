<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class #controller# extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('#model#');
    }

    function index(){
    $data['table_content'] = $this->#model#->get_table_data();
		$this->load->view('#view#',$data);
    }


}
?>
