<?php


if (!defined('BASEPATH')) exit('No direct script access allowed');

class Generate extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        //$this->load->model('m_chat');
    }

    function generate()
    {
        $controller = 5;
        $model = 5;
        $view = 5;
        $table = 5;
        $template = file_get_contents(realpath(APPPATH.'../generated/').'/v_template.php');
        $template = str_replace("#THEVAR#", $value, $template);
        $newFile = fopen(realpath(APPPATH.'../generated/').'/v_baru.php', 'w');
        fwrite($newFile, $template);
        fclose($newFile);

}

}

?>