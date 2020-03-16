<?php

class Usuarios_model extends CI_Model {
	public function __construct() {
        parent::__construct();
    }

	public function getUsuarios($tabla, $columnas){
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

	public function getTipoUsuario(){
		$sql="SELECT 
					ID_TIPO, 
					CAMPO
			  FROM 
			  		USUARIO_TIPO 
			  WHERE 
					  ESTADO=1
			  ORDER BY CAMPO";

		$query = $this->db->query($sql);
		if ($query->num_rows() > 0)
            return $query->result();
        else
            return false;
	}

	
	public function getEmpresa(){
		$sql="SELECT 
					ID_EMPRESA, 
					CAMPO
			  FROM 
			  		EMPRESA 
			  WHERE 
					  ESTADO=1
			ORDER BY CAMPO";
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0)
            return $query->result();
        else
            return false;
	}


	public function getDataUser($where = 1, $clauses = [], $ORDER_BY = "", $LIMIT = ""){
		$sql="SELECT 
		U.ID_USUARIO AS ID,
		IFNULL(U.FOTOPERFIL, 'default.png') AS FOTO, 
		CONCAT(IFNULL(U.RUT, ''), IFNULL(RUT_DV, '')) AS RUT, 
		U.CORREO AS EMAIL, 
		CONCAT(IFNULL(U.NOMBRE, ''), ' ', IFNULL(U.APELLIDOP, ' '), ' ', IFNULL(APELLIDOM, ' ')) AS NOMBRE, 
		U.APELLIDOP AS APELLIDOP,
		U.APELLIDOM AS APELLIDOM, 
		C.CAMPO AS CARGO,
		C.ID_TIPO AS IDCARGO,
		E.CAMPO AS EMPRESA_NOMBRE,
		E.ID_EMPRESA AS EMPRESA_ID
		

   FROM
		 USUARIO U
  LEFT JOIN EMPRESA E ON E.ID_EMPRESA=U.ID_EMPRESA AND E.ESTADO=1
  INNER JOIN
		 USUARIO_TIPO C 
  ON
		  U.ID_TIPO=C.ID_TIPO
  WHERE
		U.ESTADO=1 AND ".$where." ".$ORDER_BY." ".$LIMIT;

		
		$query = $this->db->query($sql, $clauses);
		// die($this->db->last_query());
		if ($query->num_rows() > 0)
            return $query;
        else
            return false;
	}

	public function getImage($id){//obtener foto para eliminarla fisicamente	
		$query = $this->db->query("SELECT FOTOPERFIL FROM USUARIO WHERE ID_USUARIO=? AND ESTADO=0", array($id));
		if ($query->num_rows() > 0)
            return $query;
        else
            return false;
	}
}