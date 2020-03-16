<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_model extends CI_Model{

    function __construct() {
        parent::__construct();
    }

 

    public function actualizar($tabla,$comparar,$datos, $id) {
        $this->db->where($comparar, $id);
        $result = $this->db->update($tabla, $datos);

        if ($result)
            return true;
        else
            return false;
    }

    function insertar($tabla,$data) {
        $query = $this->db->insert($tabla, $data);
        if ($query)
            return $this->db->insert_id();
        else
            return false;
      }

   



}


?>
