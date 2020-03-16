<?php

class Canales_model extends CI_Model {
	public function __construct() {	
        parent::__construct();
    }



	public function getDataCanal($where = 1, $clauses = [], $ORDER_BY = "", $LIMIT = ""){

		$sql="SELECT 
		C.ID_CANAL,
		IFNULL(C.LOGO, 'default.png') AS LOGO, 
		C.NOMBRE,
		C.DESCRIPCION_CORTA,	
		C.DESCRIPCION_LARGA
		FROM CANAL C 
		WHERE C.ESTADO=1 AND ".$where." ".$ORDER_BY." ".$LIMIT;


		
		$query = $this->db->query($sql, $clauses);
		//die($this->db->last_query() );
			if ($query->num_rows() > 0)
            return $query;
        else
            return false;
	}


	public function getDataContenido($where = 1, $clauses = [], $ORDER_BY = "", $LIMIT = ""){

		$sql="SELECT 
		C.ID_CONTENIDO,
        C.ID_CANAL,
        C.NOMBRE,
     	C.DESCRIPCION,
        C.ESCENA,
        C.CLAVE,
        C.VISIBILIDAD
		FROM CANAL_CONTENIDO C
		WHERE C.ESTADO=1 AND ".$where." ".$ORDER_BY." ".$LIMIT;


		
		$query = $this->db->query($sql, $clauses);
		if ($query->num_rows() > 0)
            return $query;
        else
            return false;
	}



	public function getDataCategoria(){
		$sql="SELECT 
					C.ID_CATEGORIA, 
					C.CAMPO
			  FROM 
			  		CATEGORIA C 
			  WHERE 
					  ESTADO=1
			ORDER BY CAMPO";
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0)
            return $query->result();
        else
            return false;
	}



	public function deleteContenidoCat($id){

		  // Produces: // DELETE FROM mytable  // WHERE id = $id
        if ($this->db->delete('CANAL_CONTENIDO_CAT', array('ID_CONTENIDO' => $id)))
            return true;
        else
			return false;
		}


	
	public function getDataContenidoCat($where = 1,$clauses = []){
		$sql="SELECT 				
				C.ID_CONTENIDO,
				C.ID_CATEGORIA
 			 FROM 
				  CANAL_CONTENIDO_CAT C
			WHERE ".$where."";
				  
		$query = $this->db->query($sql,$clauses);
		if ($query->num_rows() > 0)
            return $query->result();
        else
            return false;
	}


	public function getContenidoGaleria($where = 1, $clauses = []){
		$sql = "SELECT 
						CCG.ID_GALERIA,
						CCG.ID_CONTENIDO,
						CCG.ORDEN,
						CCG.TIPO,
						CCG.NOMBRE,
						IFNULL(CCG.SRC, 'default.png') AS SRC, 
						CCG.PESO
						
					FROM CANAL_CONTENIDO_GALERIA CCG
					WHERE CCG.ESTADO = 1 AND $where
					ORDER BY CCG.ORDEN ASC";
				
		$query = $this->db->query($sql, $clauses);

	
		//die($this->db->last_query());

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

	public function getImageContenidoGaleria($id){//foto de galería
		$query = $this->db->query("SELECT SRC FROM CANAL_CONTENIDO_GALERIA WHERE ID_GALERIA = ? AND ESTADO = 1", array($id));
	//	die($this->db->last_query());
		if ($query->num_rows() > 0)
			return $query;
		else
			return false;	
	}

	public function getImageContenido($id){//obtener imagen destacada	
		$query = $this->db->query("SELECT SRC FROM CANAL_CONTENIDO_GALERIA WHERE ID_CONTENIDO=? AND ESTADO=0", array($id));

		//die($this->db->last_query());
		if ($query->num_rows() > 0)
            return $query;
        else
            return false;
	}





}