<?php
class Utilities_model extends CI_Model{
	public function insertar($tabla, $data){
		$query = $this->db->insert($tabla, $data);
        if ($query)
            return $this->db->insert_id();
        else
            return false;
	}

	public function actualizar($tabla, $comparar, $datos, $id){
		$this->db->where($comparar, $id);
        $result = $this->db->update($tabla, $datos);
        if ($result)
            return true;
        else
            return false;
    }
    

    
    public function login($id, $estado){

        $sql = "SELECT U.ID_USUARIO,
                       U.RUT,
                       U.CORREO,
                       CONCAT(IFNULL(U.NOMBRE,''), ' ', IFNULL(U.APELLIDOP,''), ' ', IFNULL(U.APELLIDOM,'')) AS NOMBRE, 
                       DATE_FORMAT(U.FECHAULTIMOACCESO,'%d-%m-%Y %H:%i') AS FECHAULTIMOACCESO,
                       IFNULL(U.FOTOPERFIL,'default.png') AS FOTOPERFIL, 
                       U.ID_TIPO
                 FROM USUARIO U
                 JOIN USUARIO_CLAVE UC ON UC.ID_USUARIO=U.ID_USUARIO AND UC.ESTADO=1
                 WHERE U.ESTADO=1 AND $id";

                 
           $query = $this->db->query($sql, $estado);
        //   die($this->db->last_query());
           
        if ($query->num_rows() > 0)
            return $query->row();
        else
            return false;

    }




}