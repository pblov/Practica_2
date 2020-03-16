<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

    function __construct() {
        parent::__construct();
          $this->load->model('dashboard/dashboard_model');
        date_default_timezone_set('America/Santiago');
            
            
    }

    public function index() {   
           $data['datalibrary'] = array('titulo' => "Dashboard", 'vista' => array('index'), 'active' => 'dashboard', 'libjs' => array('libjs'),'libcss'=> array('libcss'));
            $this->load->view('estructura/body', $data);
            
                
    }

   

}
