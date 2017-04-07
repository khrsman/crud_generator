<?php


if (!defined('BASEPATH')) exit('No direct script access allowed');

class Editor extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        //$this->load->model('m_chat');
        $this->table_name ='';
        $this->table_data ='';
        $this->field_options ='';
        $this->field_type = '';
        $this->field_type_variable = '';
        $this->field_primary_key = '';
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
        //$this->load->view('all.php', $data);
        $this->load->view('form.php');
    }


        function theme()
        {
            $this->load->view('form.php');
        }

    // ambil nama kolom dari table yang dipilih
    function get_column_name(){
        // parameter untuk konek database baru
        $database = $this->input->post('db_name');
        $table = $this->input->post('table_name');
        $db_param = $this->switch_db($database);

        // konek ke database dengan parameter yang disediakan
        $db_conn = $this->load->database($db_param, TRUE);
        $db_conn->select('column_name');
        $db_conn->from(' information_schema.columns');
        $db_conn->where('TABLE_NAME',$table);
        $db_conn->where('TABLE_SCHEMA',$database);
        $data = $db_conn->get()->result_array();

        // return column name
        return $data;
    }

      // ambil semua data dari table yang dipilih
      function get_table_content(){
        // parameter untuk konek database baru
        $database = $this->input->post('db_name');
        $table = $this->input->post('table_name');
        $db_param = $this->switch_db($database);

        // konek ke database dengan parameter yang disediakan
        $db_conn = $this->load->database($db_param, TRUE);

        $db_conn->select('*');
        $db_conn->from($table);
        $db_conn->limit(30);
        $data = $db_conn->get()->result_array();

        return $data;
    }

    // generate data untuk form
    function generate_view_add()
    {
        $tipe = $this->input->post('tipe');
        $data = $this->get_column_name();
        $action = site_url() . '';
        $html = '
         	<style>
        	.input-group-addon {
        	    min-width:150px;
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

    // generate data untuk list_view
    function generate_view_list()
    {
        $tipe = $this->input->post('tipe');
        $data = $this->get_column_name();
        $action = site_url() . '';
        $header = $tr = '';

        // ambil data untuk header, ganti underscore dengan spasi
        foreach ($data as $key => $value) {
            $label = ucfirst($value['column_name']);
            $label = str_replace("_"," ",$label);
            $header .= '<th>'.$label.'</th>';
        }

        $data_table = $this->get_table_content();

        // isi table, untuk datatable
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

    function generate_editor()
    {
        $table = $this->input->post('table_name');
        //$tipe = $this->input->post('tipe');
        $data = $this->get_column_name();
        $action = site_url() . '';
        $html = '
         	<style>
        	.input-group-addon {
        	    min-width:150px;
        	    text-align:left;
        	}
          .input-group{
            padding: 5px;
          }
        	</style>';
          $tr_pertama = $tr_kedua = '';
        foreach ($data as $key => $value) {
            $label = ucfirst($value['column_name']);
            $label = str_replace("_"," ",$label);
            $name_id = $value['column_name'];
            $html .= '';
            $tr_pertama .= '
            <tr>
                <td align="center" style="vertical-align:middle">
                <div class="radio">
                    <input type="radio" name="radio" id="radio'.$name_id.'" value="'.$name_id.'">
                    <label for="radio'.$name_id.'">
                    </label>
                </div>
                </td>
                <td style="vertical-align:middle">'.$label.'</td>
                <td>
                    <select data-id = "'.$name_id.'" onchange="getval(this);" class="form-control" name="type['.$name_id.']">
                       <option value="text" selected>Text</option>
                       <option value="select">Select</option>
                       <option value="radio">Radio</option>
                     </select>
                     <div class="row" id="options_'.$name_id.'" style="display:none">

                    <div class="col-md-12">
                    <div class="input-group">
                        <input name="type_variable['.$name_id.'][0][value]" placeholder="value" class="form-control" style="float: left; width: 50%;" title="Prefix" type="text">
                        <input name="type_variable['.$name_id.'][0][name]" placeholder="name" class="form-control" style="float: left; width: 50%;" type="text">
                        <span class="input-group-btn">
                            <button style="padding:9px" class="btn btn-default" type="button" onclick="removeOptionsRow(this);">
                                <span class="glyphicon glyphicon-minus"></span>
                            </button>
                        </span>
                    </div>
                    <button style="margin:0 0 0 6px" type="button" id="'.$name_id.'" class="btn btn-warning btn-sm" onclick="addOptionsRow(this);">
                        <span class="glyphicon glyphicon-plus"></span>
                        Click to add more
                    </button>
                    </div>
                  </div>
                  </div>
                </td>
                <td>
                <select class="form-control" name="options['.$name_id.']">
                <option selected> - </option>
                   <option value="required">Required</option>
                   <option value="number">Number</option>
                   <option value="hidden">hidden</option>
                   <option value="password">Password</option>
                 </select>
                </td>
                <td align="center"><input type="checkbox" name="generate['.$name_id.']" value="'.$name_id.'" checked></td>
            </tr>';
        }
      //  $html .= '<input type="submit" value="Save" class="btn btn-primary" style="float: right">';
        $html .= '
        <form>
        <input type="hidden" name="table" value="'.$table.'">
        <table class="table">
    <thead>
      <tr>
        <th style="width:10%">PK</th>
        <th style="width:20%">Field</th>
        <th style="width:30%">Type</th>
        <th style="width:30%">Option</th>
        <th style="width:10%">Generate</th>
      </tr>
    </thead>
    <tbody>
    '.$tr_pertama.'
    </tbody>
  </table>
    </form>
        ';
        echo json_encode($html);

    }

    // generate semua kedalam file
    function generate_file()
    {
        $table = $this->input->post('table_name');
        $data = $this->get_column_name();
        $data_table = $this->get_table_content();
        $isi_table = $variable = $table_header = $content_data = $parameter = $url_add = '';

        foreach ($data as $key => $value) {
            // controller & model
            $nama = "'".$value['column_name']."'";
            $isi = '$'.$value['column_name'];
            $isi_table .=  "\n\t\t\t\t".$nama." => ".$isi.",";
            $variable .= $isi." = $"."this->input->post(".$nama.");\n\t\t\t";
            // view
            $table_label = ucfirst($value['column_name']);
            $table_label = str_replace("_"," ",$table_label);
            $table_header .= "\n\t\t\t\t\t<th>$table_label</th>";
        }
        $table_header .= "\n\t\t\t\t\t<th>Action</th>";
          $controller = ucfirst($table);
          $model = 'M_'.$table;
          $view = 'v_'.$table;
          $view_add = 'v_add_'.$table;
          $view_edit = 'v_edit_'.$table;

        // definisi folder target
          $folder_parent = realpath(APPPATH.'../generated/')."/$table";
          $folder_controller = $folder_parent."/controllers";
          $folder_model = $folder_parent."/models";
          $folder_view = $folder_parent."/views";

        // buat folder
          $create_folder = (is_dir($folder_parent)) ? '' : mkdir($folder_parent);
          $create_folder = (is_dir($folder_controller)) ? '' : mkdir($folder_controller);
          $create_folder = (is_dir($folder_model)) ? '' : mkdir($folder_model);
          $create_folder = (is_dir($folder_view)) ? '' : mkdir($folder_view);

        // ganti permission folder
          chmod($folder_parent, 0777);
          chmod($folder_controller, 0777);
          chmod($folder_model, 0777);
          chmod($folder_view, 0777);

        // view - list
          $content_header = "\n\t\t\t\t".'<?php foreach($table_content as $key => $value){ ?>';
          $content_data = "\n\t\t\t\t\t<tr>";
          foreach ($data as $key => $value) {
              $content_data .= "\n\t\t\t\t\t\t".'<td><?php echo $value["'.$value['column_name'].'"] ?></td>';
          }
          $content_data .= "\n\t\t\t\t\t\t".'<td> <a href="'.$table.'/edit?id=" >Edit </a> - <a href="'.$table.'/delete?id=" >Hapus </a></td>';
          $content_data .= "\n\t\t\t\t\t</tr>";
          $content_footer = "\n\t\t\t\t".'<?php } ?>';
          $content = $content_header.$content_data.$content_footer;
          $template = file_get_contents(realpath(APPPATH.'../generated/').'/template_view.php');
          $template = str_replace("#header#", $table_header, $template);
          $template = str_replace("#isi#", $content, $template);
          $template = str_replace("#link_add#", $table.'/add', $template);
          $newFile = fopen($folder_view.'/'.$view.'.php', 'w');
          fwrite($newFile, $template);
          fclose($newFile);

          // view - add
            foreach ($data as $key => $value) {
              $label = ucfirst($value['column_name']);
              $label = str_replace("_"," ",$label);
              //$parameter .= $value['column_name'].":".$value['column_name'].",";
                $form_data .= "\n\t\t\t\t\t\t\t\t\t\t\t\t".'<div class="input-group">
                          <span class="input-group-addon" id="basic-addon1">' . $label . '</span>
                          <input type="text" class="form-control" placeholder="" name="' . $value['column_name'] . '" id="' . $value['column_name'] . '"  aria-describedby="basic-addon1">
                        </div>';
            }
            $parameter = rtrim($parameter,',');
            $url_add = $table."/add";
            $template = file_get_contents(realpath(APPPATH.'../generated/').'/template_add.php');
            //$template = str_replace("#parameter_ajax#", $parameter_edit, $template);
            $template = str_replace("#controller#", $table, $template);
            $template = str_replace("#url_add_ajax#", $url_add, $template);
            $template = str_replace("#form_input#", $form_data, $template);
            $newFile = fopen($folder_view.'/'.$view_add.'.php', 'w');
            fwrite($newFile, $template);
            fclose($newFile);


            // view - edit
            $form_data =  ' ';
              foreach ($data as $key => $value) {
                $label = ucfirst($value['column_name']);
                $label = str_replace("_"," ",$label);
                //$parameter .= $value['column_name'].":".$value['column_name'].",";
                  $form_data .= "\n\t\t\t\t\t\t\t\t\t\t\t\t".'<div class="input-group">
                            <span class="input-group-addon" id="basic-addon1">' . $label . '</span>
                            <input type="text" class="form-control" placeholder="" name="' . $value['column_name'] . '" id="' . $value['column_name'] . '" value="<?php echo $table_content[0]["' . $value['column_name'] . '"]; ?> " aria-describedby="basic-addon1">
                          </div>';
              }
              $parameter = rtrim($parameter,',');
              $url_edit = $table."/update";
              $template = file_get_contents(realpath(APPPATH.'../generated/').'/template_edit.php');
              //$template = str_replace("#parameter_ajax#", $parameter, $template);
              $template = str_replace("#controller#", $table, $template);
              $template = str_replace("#url_edit_ajax#", $url_edit, $template);
              $template = str_replace("#form_input#", $form_data, $template);
              $newFile = fopen($folder_view.'/'.$view_edit.'.php', 'w');
              fwrite($newFile, $template);
              fclose($newFile);

        // controller
          $template_controller = file_get_contents(realpath(APPPATH.'../generated/').'/template_controller.php');
          $template_controller = str_replace("#controller#", $controller, $template_controller);
          $template_controller = str_replace("#model#", $model, $template_controller);
          $template_controller = str_replace("#view#", $view, $template_controller);
          $template_controller = str_replace("#view_add#", $view_add, $template_controller);
          $template_controller = str_replace("#view_edit#", $view_edit, $template_controller);
          $newFile = fopen($folder_controller.'/'.$controller.'.php', 'w');
          fwrite($newFile, $template_controller);
          fclose($newFile);

        // model
          $template_controller = file_get_contents(realpath(APPPATH.'../generated/').'/template_model.php');
          $template_controller = str_replace("#model#", $model, $template_controller);
          $template_controller = str_replace("#table_name#", $table, $template_controller);
          $newFile = fopen($folder_model.'/'.$model.'.php', 'w');
          fwrite($newFile, $template_controller);
          fclose($newFile);

        // modifikasi permission file (*linux)
          chmod($folder_model.'/'.$model.'.php', 0777);
          chmod($folder_controller.'/'.$controller.'.php', 0777);
          chmod($folder_view.'/'.$view.'.php', 0777);
          chmod($folder_view.'/'.$view_add.'.php', 0777);
          chmod($folder_view.'/'.$view_edit.'.php', 0777);
    }

    function generate_from_editor()
    {
        $post = array();
        parse_str($this->input->post('data'), $post);


        // inisialisasi global variable
        $this->table_name =  $post['table'];
        $this->table_data =  $post['generate'];
        $this->field_primary_key =  $post['radio'];
        $this->field_options =  $post['options'];
        $this->field_type =  $post['type'];
        $this->field_type_variable = $post['type_variable'];

        $variable =  $content_data = $form_data = $parameter = $url_add = '';

        foreach ($this->table_data as $key => $value) {
            // inisialisasi variable untuk controller & model
            $var = "'".$key."'";
            $content = '$'.$key;
            $variable .= $content." = $"."this->input->post(".$var.");\n\t\t\t";
        }

          // Inisialisasi nama file
          $controller = ucfirst($this->table_name);
          $model = 'M_'.$this->table_name;
          $view = 'v_'.$this->table_name;
          $view_add = 'v_add_'.$this->table_name;
          $view_edit = 'v_edit_'.$this->table_name;

        // definisi folder target
          $folder_parent = realpath(APPPATH.'../generated/')."/$this->table_name";
          $folder_controller = $folder_parent."/controllers";
          $folder_model = $folder_parent."/models";
          $folder_view = $folder_parent."/views";

          $generated_file_view = $folder_view.'/'.$view.'.php';
          $generated_file_add = $folder_view.'/'.$view_add.'.php';
          $generated_file_edit = $folder_view.'/'.$view_edit.'.php';
          $generated_file_controller = $folder_controller.'/'.$controller.'.php';
          $generated_file_model = $folder_model.'/'.$model.'.php';

        // buat folder dan ganti permissionnya
          $create_folder = (is_dir($folder_parent)) ? '' : mkdir($folder_parent);
          $create_folder = (is_dir($folder_controller)) ? '' : mkdir($folder_controller);
          $create_folder = (is_dir($folder_model)) ? '' : mkdir($folder_model);
          $create_folder = (is_dir($folder_view)) ? '' : mkdir($folder_view);
          chmod($folder_parent, 0777);
          chmod($folder_controller, 0777);
          chmod($folder_model, 0777);
          chmod($folder_view, 0777);




        // generate list view
        $this->create_view_list($generated_file_view);
        // generate add view
        $this->create_view_add($generated_file_add);
        // generate edit view
        $this->create_view_edit($generated_file_edit);
        // generate controller
        $this->create_controller($generated_file_controller,$controller,$model,$view,$view_add,$view_edit);
        // generate model
        $this->create_model($generated_file_model,$model);

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

    function create_view_list($generated_file_view){

      $table_name = $this->table_name;
      $table_data = $this->table_data;
      $primary_key = $this->field_primary_key;

      $generated_content = "";
      $generated_header = "";
      foreach ($table_data as $key => $value) {
        $generated_label = str_replace("_"," ",ucfirst($key));
        $generated_header .= "\n\t\t\t\t\t<th>$generated_label</th>";
        $generated_content .= "\n\t\t\t\t\t\t".'<td><?php echo $value["'.$key.'"] ?></td>';
      }

      // tambahkan action
      $generated_header .= "\n\t\t\t\t\t<th>Action</th>";
      $generated_content .= "\n\t\t\t\t\t\t".'<td> <a href="'.$table_name.'/edit?id=<?php echo $value["'.$primary_key.'"] ?>" >Edit </a> - <a href="'.$table_name.'/delete?id=<?php echo $value["'.$primary_key.'"] ?>" >Hapus </a></td>';

      // masukan kedalam template
      $template = file_get_contents(realpath(APPPATH.'../template/').'/template_view.php');
      $template = str_replace("#header#", $generated_header, $template);
      $template = str_replace("#content#", $generated_content, $template);
      $template = str_replace("#link_add#", $table_name.'/add', $template);

      // create file
      $newFile = fopen($generated_file_view, 'w');
      fwrite($newFile, $template);
      fclose($newFile);
      chmod($generated_file_view, 0777);
    }

    function create_view_add($generated_file_add){
      $form_data = '';
      foreach ($this->table_data as $key => $value) {
        $label = str_replace("_"," ",ucfirst($key));
        //$parameter .= $value['column_name'].":".$value['column_name'].",";

          switch ($this->field_type[$key]) {
            case 'text':
            $form_data .= "\n\t\t\t\t\t\t\t\t\t\t\t\t".
                    '<div class="input-group">
                      <span class="input-group-addon">' . $label . '</span>
                      <input type="text" class="form-control" placeholder="" name="' . $key . '" id="' . $key . '" >
                    </div>';
              break;
              case 'select':

              $options = '<option selected> - </option>';
              foreach ($this->field_type_variable[$key] as $keyy => $valuee) {
                $options .='<option value="'.$valuee['value'].'">'.$valuee['name'].'</option>';
              }
              $form_data .= "\n\t\t\t\t\t\t\t\t\t\t\t\t".
                      '<div class="input-group">
                      <span class="input-group-addon">' . $label . '</span>
                      <select class="form-control" name="type['.$key.']">
                      '.$options.'
                       </select>
                      </div>';
              break;
              case 'radio':
              $form_data .= "\n\t\t\t\t\t\t\t\t\t\t\t\t".
                      '<div class="input-group">
                      <span class="input-group-addon">' . $label . '</span>
                      <div class="radio">
                        <label><input type="radio" name="['.$key.']" value="">Option 1</label>
                      </div>
                      <div class="radio">
                        <label><input type="radio" name="['.$key.']" value="">Option 2</label>
                      </div>
                      <div class="radio">
                        <label><input type="radio" name="['.$key.']" value="">Option 3</label>
                      </div>
                      </div>';
              break;
              default:
              # code...
              break;
          }
      }

      $url_add = $this->table_name."/add";
      $template = file_get_contents(realpath(APPPATH.'../template/').'/template_add.php');
      $template = str_replace("#controller#", $this->table_name, $template);
      $template = str_replace("#url_add_ajax#", $url_add, $template);
      $template = str_replace("#form_input#", $form_data, $template);
      $newFile = fopen($generated_file_add, 'w');
      fwrite($newFile, $template);
      fclose($newFile);
      chmod($generated_file_add, 0777);
    }

    function create_view_edit($generated_file_edit){
      $form_data =  '';
        foreach ($this->table_data as $key => $value) {
          $label = ucfirst($key);
          $label = str_replace("_"," ",$label);
          //$parameter .= $value['column_name'].":".$value['column_name'].",";
            $form_data .= "\n\t\t\t\t\t\t\t\t\t\t\t\t".'<div class="input-group">
                      <span class="input-group-addon">' . $label . '</span>
                      <input type="text" class="form-control" placeholder="" name="' . $key . '" id="' . $key . '" value="<?php echo $table_content[0]["' . $key . '"]; ?> " >
                    </div>';
        }
        $url_edit = $this->table_name."/update";
        $template = file_get_contents(realpath(APPPATH.'../template/').'/template_edit.php');
        $template = str_replace("#controller#", $this->table_name, $template);
        $template = str_replace("#url_edit_ajax#", $url_edit, $template);
        $template = str_replace("#form_input#", $form_data, $template);
        $newFile = fopen($generated_file_edit, 'w');
        fwrite($newFile, $template);
        fclose($newFile);
        chmod($generated_file_edit, 0777);
    }

    function create_controller($generated_file_controller,$controller,$model,$view,$view_add,$view_edit){
      $template_controller = file_get_contents(realpath(APPPATH.'../template/').'/template_controller.php');
      $template_controller = str_replace("#controller#", $controller, $template_controller);
      $template_controller = str_replace("#model#", $model, $template_controller);
      $template_controller = str_replace("#view#", $view, $template_controller);
      $template_controller = str_replace("#view_add#", $view_add, $template_controller);
      $template_controller = str_replace("#view_edit#", $view_edit, $template_controller);
      $newFile = fopen($generated_file_controller, 'w');
      fwrite($newFile, $template_controller);
      fclose($newFile);
      chmod($generated_file_controller, 0777);
    }

    function create_model($generated_file_model,$model){
      $template_controller = file_get_contents(realpath(APPPATH.'../template/').'/template_model.php');
      $template_controller = str_replace("#model#", $model, $template_controller);
      $template_controller = str_replace("#table_name#", $this->table_name, $template_controller);
      $template_controller = str_replace("#primary_key#", $this->field_primary_key, $template_controller);
      $newFile = fopen($generated_file_model, 'w');
      fwrite($newFile, $template_controller);
      fclose($newFile);
      chmod($generated_file_model, 0777);
    }


}

?>
