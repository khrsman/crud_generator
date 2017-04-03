<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class #controller# extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('#model#');
    }

    function index(){
    $data['table_content'] = $this->#model#->get();
		$this->load->view('#view#',$data);
    }

    // tambah data
    function add(){
      // periksa method
      // apabila post -> insert data, get -> halaman view
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // ambil data hasil serialize jquery, kemudian masukan kedalam array $data
        $data = array();
        parse_str($_POST['data'], $data);
        $insert = $this->#model#->insert($data);

        if (!$insert) {
            $msg = $this->db->_error_message();
            $num = $this->db->_error_number();
          }

      }else{
        $this->load->view('#view_add#');
      }
    }

    // halaman edit
    function edit(){
      $id = $this->input->get('id');
      $data['table_content'] = $this->#model#->get($id);
      $this->load->view('#view_edit#', $data);
    }

    // proses edit
    function update(){
      $data = array();
      parse_str($_POST['data'], $data);
      $id = $data['#primary_key'];
      $update = $this->#model#->update_by_id($data,$id);
    }

    // proses hapus
    function delete(){
      $id = $this->input->get('id');
      $delete = $this->#model#->delete_by_id($id);
    }

}
?>
