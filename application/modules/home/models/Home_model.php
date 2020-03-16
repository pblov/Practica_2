<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home_model extends CI_Model{

    function __construct() {
        parent::__construct();
    }

    function getUsuario($where,$clauses){

        $sql = "SELECT U.ID_USUARIO,
                       U.RUT,
                       U.CORREO,
                       CONCAT(IFNULL(U.NOMBRE,''), ' ', IFNULL(U.APELLIDOP,''), ' ', IFNULL(U.APELLIDOM,'')) AS NOMBRE,
                       DATE_FORMAT(U.FECHAULTIMOACCESO,'%d-%m-%Y %H:%i') AS FECHAULTIMOACCESO,
                        U.FOTOPERFIL, 
                        U.ID_TIPO,
                        U.TOKEN_FECHACREACION AS TOKEN_FECHACREACION
                 FROM USUARIO U
                 WHERE U.ESTADO=1 AND $where";

           $query = $this->db->query($sql, $clauses);

        if ($query->num_rows() > 0)
            return $query->row();
        else
            return false;
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

   
      function clavesUsuario($where,$clauses){

        $sql = "SELECT U.ID_USUARIO,
                     COUNT(USC.ID_CLAVE) AS CANTIDAD,
                     UC.FECHACREACION AS ACTUAL_FECHACREACION,
                     UC.CLAVE AS ACTUAL_CLAVE
                FROM USUARIO U
                JOIN USUARIO_CLAVE UC ON UC.ID_USUARIO=U.ID_USUARIO AND UC.ESTADO=1
                JOIN USUARIO_CLAVE USC ON USC.ID_USUARIO=U.ID_USUARIO
                WHERE U.ESTADO=1 AND $where
                GROUP BY U.ID_USUARIO";

           $query = $this->db->query($sql, $clauses);

        if ($query->num_rows() > 0)
            return $query;
        else
            return false;
    }

    function claveUsuario($where,$clauses){

        $sql = "SELECT U.ID_USUARIO,
                       UC.ID_CLAVE,
                       UC.CLAVE
                 FROM USUARIO U
                 JOIN USUARIO_CLAVE UC ON UC.ID_USUARIO=U.ID_USUARIO AND UC.ESTADO=1
                 WHERE U.ESTADO=1 AND $where";

           $query = $this->db->query($sql, $clauses);

        if ($query->num_rows() > 0)
            return $query;
        else
            return false;
    }


}


?>
