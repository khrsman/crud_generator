<?php


if (!defined('BASEPATH')) exit('No direct script access allowed');

class Editor extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        //$this->load->model('m_chat');

    }

    function switch_db($name_db)
    {
        $config_app['hostname'] = 'localhost';
        $config_app['username'] = 'root';
        $config_app['password'] = '';
        $config_app['database'] = $name_db;
        $config_app['dbdriver'] = 'mysqli';
        $config_app['dbprefix'] = '';
        $config_app['pconnect'] = FALSE;
        $config_app['db_debug'] = TRUE;
        return $config_app;
    }

    function index()
    {
        $data['table'] = '';
        $this->load->view('all.php', $data);
    }

    #ambil nama kolom dari table yang dipilih
    function get_column_name(){
        #parameter untuk konek database baru
        $database = $this->input->post('db_name');
        $table = $this->input->post('table_name');
        $db_param = $this->switch_db($database);

        #konek ke database dengan parameter yang disediakan
        $db_conn = $this->load->database($db_param, TRUE);
        $db_conn->select('column_name');
        $db_conn->from(' information_schema.columns');
        $db_conn->where('TABLE_NAME',$table);
        $db_conn->where('TABLE_SCHEMA',$database);
        $data = $db_conn->get()->result_array();

        #return column name
        return $data;
    }

      #ambil semua data dari table yang dipilih
      function get_table_content(){
        #parameter untuk konek database baru
        $database = $this->input->post('db_name');
        $table = $this->input->post('table_name');
        $db_param = $this->switch_db($database);

        #konek ke database dengan parameter yang disediakan
        $db_conn = $this->load->database($db_param, TRUE);

        $db_conn->select('*');
        $db_conn->from($table);
        $db_conn->limit(30);
        $data = $db_conn->get()->result_array();

        return $data;
    }

    #generate data untuk form
    function generate_view_add()
    {
        $tipe = $this->input->post('tipe');
        $data = $this->get_column_name();
        $action = site_url() . '';
        $html = '
         	<style>
        	.input-group-addon {
        	    min-width:150px;// if you want width please write here //
        	    text-align:left;
        	}
          .input-group{
            padding: 5px;
          }
        	</style>';
        $html .= '      <form class="form-horizontal" role="form" action="' . $action . '" method="POST">';
        foreach ($data as $key => $value) {
            $label = ucfirst($value['column_name']);
            $label = str_replace("_"," ",$label);
            $name_id = $value['column_name'];
            //$html .= '<form class="form-horizontal" role="form" action="'.$action." method="POST" enctype="multipart/form-data">';
            $html .= '
                <div class="input-group">
                  <span class="input-group-addon" id="basic-addon1">' . $label . '</span>
                  <input type="text" class="form-control" placeholder="" name="' . $name_id . '" id="' . $name_id . '" aria-describedby="basic-addon1">
                </div>
               ';
        }
        $html .= '<input type="submit" value="Save" class="btn btn-primary" style="float: right">';
        $html .= '
     </div>';
        $plain = '<textarea rows="20" style="width: 100%;  border:none;"> ' . $html . '</textarea>';

        switch ($tipe){
            case 'field' :
                echo json_encode($html);
            break;
            case 'plain' :
                echo json_encode($plain);
                break;
        }
    }

    #generate data untuk list_view
    function generate_view_list()
    {
        $tipe = $this->input->post('tipe');
        $data = $this->get_column_name();
        $action = site_url() . '';
        $header = '';

        foreach ($data as $key => $value) {
            $label = ucfirst($value['column_name']);
            $label = str_replace("_"," ",$label);
            $header .= '<th>'.$label.'</th>';
        }

        $data_table = $this->get_table_content();
        $tr = '';

        foreach ($data_table as $key => $value) {
            $td = '';
            foreach ($value as $k => $v) {
                $td .= '<td>'.$v.'</td>';
            }
            $tr .= '<tr>'.$td.'</tr>';
        }

            $html = '<script>
    $(document).ready(function () {
        $("#example").DataTable();
    });
</script>
                        <table id="example" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
                               width="100%">
                            <thead>
                            <tr>
                                '.$header.'
                        </tr>
                        </thead>
                        <tbody>
                            '.$tr.'
                        </tbody>
                        </table>';

        $plain = '<textarea rows="50" style="width: 100%;  border:none;"> ' . $html . '</textarea>';
        switch ($tipe){
            case 'field' :
                echo json_encode($html);
                break;
            case 'plain' :
                echo json_encode($plain);
                break;
        }

    }

    #generate semua kedalam file
    function generate_file()
    {
        $table = $this->input->post('table_name');
        $data = $this->get_column_name();
        $data_table = $this->get_table_content();
        $isi_table = $variable = $table_header = $content_data = $form_data = $parameter = $url_add = '';

        foreach ($data as $key => $value) {
            #controller & model
            $nama = "'".$value['column_name']."'";
            $isi = '$'.$value['column_name'];
            $isi_table .=  "\n\t\t\t\t".$nama." => ".$isi.",";
            $variable .= $isi." = $"."this->input->post(".$nama.");\n\t\t\t";
            #view
            $table_label = ucfirst($value['column_name']);
            $table_label = str_replace("_"," ",$table_label);
            $table_header .= "\n\t\t\t\t\t<th>$table_label</th>";
        }
        $table_header .= "\n\t\t\t\t\t<th>Action</th>";
          $controller = ucfirst($table);
          $model = 'M_'.$table;
          $view = 'v_'.$table;
          $view_add = 'v_add_'.$table;

        #definisi folder target
          $folder_parent = realpath(APPPATH.'../generated/')."/$table";
          $folder_controller = $folder_parent."/controllers";
          $folder_model = $folder_parent."/models";
          $folder_view = $folder_parent."/views";

        #buat folder
          $create_folder = (is_dir($folder_parent)) ? '' : mkdir($folder_parent);
          $create_folder = (is_dir($folder_controller)) ? '' : mkdir($folder_controller);
          $create_folder = (is_dir($folder_model)) ? '' : mkdir($folder_model);
          $create_folder = (is_dir($folder_view)) ? '' : mkdir($folder_view);

        #ganti permission folder
          chmod($folder_parent, 0777);
          chmod($folder_controller, 0777);
          chmod($folder_model, 0777);
          chmod($folder_view, 0777);

        #view - list
          $content_header = "\n\t\t\t\t".'<?php foreach($table_content as $key => $value){ ?>';
          $content_data = "\n\t\t\t\t\t<tr>";
          foreach ($data as $key => $value) {
              $content_data .= "\n\t\t\t\t\t\t".'<td><?php echo $value["'.$value['column_name'].'"] ?></td>';
          }
          $content_data .= "\n\t\t\t\t\t\t".'<td>edit - hapus</td>';
          $content_data .= "\n\t\t\t\t\t</tr>";
          $content_footer = "\n\t\t\t\t".'<?php } ?>';
          $content = $content_header.$content_data.$content_footer;
          $template = file_get_contents(realpath(APPPATH.'../generated/').'/template_view.php');
          $template = str_replace("#header#", $table_header, $template);
          $template = str_replace("#isi#", $content, $template);
          $template = str_replace("#link_add#", 'add', $template);
          $newFile = fopen($folder_view.'/'.$view.'.php', 'w');
          fwrite($newFile, $template);
          fclose($newFile);

          #view - add
            foreach ($data as $key => $value) {
              $label = ucfirst($value['column_name']);
              $label = str_replace("_"," ",$label);
              $parameter .= $value['column_name'].":".$value['column_name'].",";
                $form_data .= "\n\t\t\t\t\t\t\t\t\t\t\t\t".'<div class="input-group">
                          <span class="input-group-addon" id="basic-addon1">' . $label . '</span>
                          <input type="text" class="form-control" placeholder="" name="' . $value['column_name'] . '" id="' . $value['column_name'] . '" aria-describedby="basic-addon1">
                        </div>';
            }
            $parameter = rtrim($parameter,',');
            $url_add = $table."/add";
            $template = file_get_contents(realpath(APPPATH.'../generated/').'/template_add.php');
            $template = str_replace("#parameter_ajax#", $parameter, $template);
            $template = str_replace("#controller#", $table, $template);
            $template = str_replace("#url_add_ajax#", $url_add, $template);
            $template = str_replace("#form_input#", $form_data, $template);
            $newFile = fopen($folder_view.'/'.$view_add.'.php', 'w');
            fwrite($newFile, $template);
            fclose($newFile);

        #controller
          $template_controller = file_get_contents(realpath(APPPATH.'../generated/').'/template_controller.php');
          $template_controller = str_replace("#controller#", $controller, $template_controller);
          $template_controller = str_replace("#model#", $model, $template_controller);
          $template_controller = str_replace("#view#", $view, $template_controller);
          $template_controller = str_replace("#view_add#", $view_add, $template_controller);
          $newFile = fopen($folder_controller.'/'.$controller.'.php', 'w');
          fwrite($newFile, $template_controller);
          fclose($newFile);

        #model
          $template_controller = file_get_contents(realpath(APPPATH.'../generated/').'/template_model.php');
          $template_controller = str_replace("#model#", $model, $template_controller);
          $template_controller = str_replace("#table_name#", $table, $template_controller);
          $newFile = fopen($folder_model.'/'.$model.'.php', 'w');
          fwrite($newFile, $template_controller);
          fclose($newFile);

        #modifikasi permission file (*linux)
          chmod($folder_model.'/'.$model.'.php', 0777);
          chmod($folder_controller.'/'.$controller.'.php', 0777);
          chmod($folder_view.'/'.$view.'.php', 0777);
          chmod($folder_view.'/'.$view_add.'.php', 0777);
    }


    # -------------------------------- Untuk AJAX ----------------------------------------------------
    function select_database()
    {
        #ambil nama database dari input
        $database = $this->input->post('db_name');
        #parameter untuk konek database baru
        $db_param = $this->switch_db($database);

        #konek ke database dengan parameter yang disediakan
        $db_conn = $this->load->database($db_param, TRUE);
        $data = $db_conn->query('show tables')->result_array();

        echo json_encode($data);
    }

    function fill_database()
    {
        #parameter untuk konek database baru
        #konek ke database dengan parameter yang disediakan
        $data = $this->db->query('show databases')->result_array();

        #return data dalam bentuk json object
        echo json_encode($data);
    }


}

?>
