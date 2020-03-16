<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Politicas extends MY_Controller{
    function __construct(){
        parent::__construct();

        date_default_timezone_set('America/Santiago');
    }

    public function index(){
			$data['datalibrary'] = array('titulo' => "Politicas", 'vista' => array('index','modals'), 'libjs' => array('libjs'), 'active' => 'politicas');
			$this->load->view('estructura/body', $data);
	}   
}

?>


