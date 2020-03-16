<?php

class Categorias_model extends CI_Model {
	public function __construct() {	
        parent::__construct();
    }

	
	public function getDataCategoria($where = 1, $clauses = [], $ORDER_BY = "", $LIMIT = ""){



		$sql="SELECT 
		C.ID_CATEGORIA,
		IFNULL(C.LOGO, 'default.png') AS LOGO, 
		C.CAMPO
		FROM CATEGORIA C 
		WHERE C.ESTADO=1 AND ".$where." ".$ORDER_BY." ".$LIMIT;



		
		$query = $this->db->query($sql, $clauses);
		if ($query->num_rows() > 0)
            return $query;
        else
            return false;
	}

	public function getImage($id){//obtener foto para eliminarla fisicamente	
		$query = $this->db->query("SELECT LOGO FROM CANAL WHERE ID_CANAL=? AND ESTADO=0", array($id));
		if ($query->num_rows() > 0)
            return $query;
        else
            return false;
	}
}