<?php

class Empresas_model extends CI_Model {
	public function __construct() {
        parent::__construct();
    }

	public function getEmpresas($tabla, $columnas){
		$sql="SELECT ".
				  $columnas.
			   " FROM ".
			   	  $tabla.
			   " WHERE estado=1";
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0)
            return $query;
        else
            return false;
	}
    
	public function getDataCompany($where = 1, $clauses = [], $ORDER_BY = "", $LIMIT = ""){
		$sql="SELECT 
        E.ID_EMPRESA AS ID,
        IFNULL(E.LOGO, 'default.png') AS LOGO,
        E.CAMPO AS EMPRESA_NOMBRE
        FROM EMPRESA E
            WHERE E.ESTADO=1 AND ".$where." ".$ORDER_BY." ".$LIMIT;

		$query = $this->db->query($sql, $clauses);
		if ($query->num_rows() > 0)
            return $query;
        else
            return false;
	}

	public function getImage($id){//obtener foto para eliminarla fisicamente	
		$query = $this->db->query("SELECT LOGO FROM EMPRESA WHERE ID_EMPRESA=? AND ESTADO=0", array($id));
		if ($query->num_rows() > 0)
            return $query;
        else
            return false;
	}
}