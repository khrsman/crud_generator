<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gerai_operator extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('M_gerai_operator');
    }

    function index(){
    $data['table_content'] = $this->M_gerai_operator->get();
		$this->load->view('v_gerai_operator',$data);
    }

    // tambah data
    function add(){
      // periksa method
      // apabila post -> insert data, get -> halaman view
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // ambil data hasil serialize jquery, kemudian masukan kedalam array $data
        $data = array();
        parse_str($_POST['data'], $data);
        $insert = $this->M_gerai_operator->insert($data);

        if (!$insert) {
            $msg = $this->db->_error_message();
            $num = $this->db->_error_number();
          }

      }else{
        $this->load->view('v_add_gerai_operator');
      }
    }

    // halaman edit
    function edit(){
      $id = $this->input->post('id');
      $data['table_content'] = $this->M_gerai_operator->get($id);
      $this->load->view('v_edit_gerai_operator', $data);
    }

    // proses edit
    function update(){
      $data = array();
      parse_str($_POST['data'], $data);
      $id = $data['#primary_key'];
      $update = $this->M_gerai_operator->update_by_id($data,$id);
    }

    // proses hapus
    function delete(){
      $id = $this->input->get('id');
      $delete = $this->M_gerai_operator->delete_by_id($id);
    }

}
?>
