<?php

class Publicidad_model extends CI_Model {
	public function __construct() {
        parent::__construct();
    }

    
	public function getDataType($where = 1, $clauses = [], $ORDER_BY = "", $LIMIT = ""){
		$sql="SELECT 
        PT.ID_TIPO AS ID,
        PT.CAMPO AS TIPO
        FROM PUBLICIDAD_TIPO PT
            WHERE PT.ESTADO=1 AND ".$where." ".$ORDER_BY." ".$LIMIT;

        $query = $this->db->query($sql, $clauses);
      //  die($this->db->last_query());
		if ($query->num_rows() > 0)
            return $query;
        else
            return false;

	}





    public function getDataPublicidad($where = 1, $clauses = [], $ORDER_BY = "", $LIMIT = ""){

            $sql="SELECT 
                        P.ID_PUBLICIDAD AS ID,
                        IFNULL(P.SRC, 'default.png') AS FOTO, 
                        P.TITULO AS TITULO,
                        P.CUERPO AS CUERPO,
                        IFNULL(P.MONEDA,NULL) AS 'MONEDA',
                        IFNULL(P.MONTO,NULL) AS 'MONTO',
                        DATE_FORMAT(P.FECHA_INICIO,'%Y-%m-%d') AS FECHA_INICIO,
                        DATE_FORMAT(P.FECHA_FIN,'%Y-%m-%d') AS FECHA_FIN,
                        U.ID_USUARIO AS ID_USUARIO,
                        CONCAT(IFNULL(U.NOMBRE, ''), ' ', IFNULL(U.APELLIDOP, ' '), ' ', IFNULL(APELLIDOM,'')) AS USUARIO_NOMBRE, 
                        IFNULL(C.ID_CANAL,NULL) AS ID_CANAL,
                        C.NOMBRE AS CANAL_NOMBRE,
                        IFNULL(CC.ID_CONTENIDO,NULL) AS ID_CONTENIDO,
                        CC.NOMBRE AS CONTENIDO_NOMBRE,
                        PT.ID_TIPO AS ID_TIPO,
                        PT.CAMPO AS TIPO_NOMBRE
            FROM
                    PUBLICIDAD P

                LEFT JOIN USUARIO U ON U.ID_USUARIO = P.ID_USUARIO AND U.ESTADO = 1
                LEFT JOIN CANAL C   ON C.ID_CANAL = P.ID_CANAL AND C.ESTADO = 1
                LEFT JOIN CANAL_CONTENIDO CC ON CC.ID_CONTENIDO = P.ID_CONTENIDO AND CC.ESTADO = 1
                LEFT JOIN PUBLICIDAD_TIPO PT ON PT.ID_TIPO = P.ID_TIPO AND PT.ESTADO = 1

            WHERE
                P.ESTADO=1 AND ".$where." ".$ORDER_BY." ".$LIMIT;
   
            $query = $this->db->query($sql, $clauses);
           
            
            // die($this->db->last_query());
            if ($query->num_rows() > 0)
                return $query;
            else
                return false;
        }
        

        public function getTotalPublicidad($where = 1, $clauses = [], $ORDER_BY = "", $LIMIT = ""){

                $sql = "SELECT PT.ID_TIPO AS ID, 
                               PT.CAMPO AS TIPO, 
                               COUNT(P.ID_PUBLICIDAD) AS TOTAL_PUBLICIDAD
                        FROM PUBLICIDAD_TIPO PT
                        LEFT JOIN PUBLICIDAD P ON P.ID_TIPO=PT.ID_TIPO AND P.ESTADO=1
                        WHERE $where
                        GROUP BY PT.ID_TIPO " . $ORDER_BY. " ". $LIMIT;
        
                $query = $this->db->query($sql, $clauses);
        
             //  die($this->db->last_query());
                if ($query->num_rows() > 0)
                    return $query;
                else
                    return false;

        
        }
    
    //Obtener contenido para selectores.
    public function getPublicidadTipo(){
		$sql="SELECT 
					ID_TIPO, 
					CAMPO
			  FROM 
			  		PUBLICIDAD_TIPO 
			  WHERE 
					  ESTADO=1
			ORDER BY CAMPO";
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0)
            return $query->result();
        else
            return false;
	}


    public function getCanal(){
		$sql="SELECT 
					ID_CANAL, 
					NOMBRE
			  FROM 
			  		CANAL 
			  WHERE 
					  ESTADO=1
			ORDER BY NOMBRE";
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0)
            return $query->result();
        else
            return false;
    }

    public function getContenido(){

        $sql="SELECT 
                    ID_CONTENIDO, 
                    NOMBRE
                FROM 
                        CANAL_CONTENIDO 

                WHERE 
                        ESTADO=1

                ORDER BY NOMBRE";


                $query = $this->db->query($sql);
                if ($query->num_rows() > 0)
                return $query->result();
                else
                return false;

    }

    public function getUsuario(){

            $sql="SELECT 
                        U.ID_USUARIO, 
                        IFNULL(U.NOMBRE, '') NOMBRE,
                        IFNULL(U.APELLIDOP, '') AS APELLIDOP,
                        IFNULL(U.APELLIDOM, '') AS APELLIDOM
                        
                  FROM 
                          USUARIO U 
                  WHERE 
                          ESTADO=1

                  ORDER BY NOMBRE";
    
            $query = $this->db->query($sql);
            if ($query->num_rows() > 0)
                return $query->result();
            else
                return false;
        
    }

    public function getImage($id){//obtener foto para eliminarla fisicamente	
		$query = $this->db->query("SELECT SRC AS FOTO
                                    FROM PUBLICIDAD
                                     WHERE ID_PUBLICIDAD = ? 
                                     AND ESTADO = 0", array($id));
		if ($query->num_rows() > 0)
            return $query;
        else
            return false;
	}
    




}