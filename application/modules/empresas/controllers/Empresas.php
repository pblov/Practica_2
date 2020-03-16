<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Empresas extends MY_Controller{
    function __construct(){
        parent::__construct();
        $this->load->model('empresas/Empresas_model');
        $this->load->model('Utilities_model');
        $this->load->library('Utilities');
        date_default_timezone_set('America/Santiago');
    }

    public function index(){
		if(!is_null($this->utilities->check_permisos()->datos_user)){
			$data['datalibrary'] = array('titulo' => "Empresas", 'vista' => array('index','modals'), 'libjs' => array('libjs'), 'active' => 'empresas');
			$this->load->view('estructura/body', $data);
		}else{
			redirect('');
		}
	}   
	
    public function guardarEmpresas(){
			if (is_null($this->utilities->check_permisos()->datos_user )) {
				exit;
			}
            //if ($this->utilities->check_permisos()->tipo == 1) {
			$imgdefault='default.png';
		    $info = new stdClass();
            $info->proceso = 1; // 0:no se pudo procesar, 1: procesorealizado correctamente
            $info->errores = []; // Errores encontrados en el proceso
            $info->id = null; //Id del usuario creado/editado
            $info->data = null; // Errores encontrados en el proceso

            $data = new stdClass;
            $data->CAMPO=$this->security->xss_clean(trim($this->input->post('nombre')));
            $data->ESTADO=1;
			$LOGO=$this->security->xss_clean(trim($this->input->post('foto')));
			$data->LOGO = "";
            $data->FECHACREACION=date("Y-m-d H:i:s");
			$data->FECHABAJA=date("Y-m-d H:i:s");

            $dataact = new stdClass();
            if(!empty($this->input->post('idempresas'))){
				$dataact->ID=$this->security->xss_clean((int)trim($this->input->post('idempresas')));
			}else{
				$dataact->ID=-1;
			}
			$dataact->FECHAMODIFICACION=date("Y-m-d H:i:s");
            $dataact->FECHABAJA=date("Y-m-d H:i:s");

			//validaciones de datos recibidos

            if(empty($data->CAMPO)){
                array_push($info->errores, "El campo 'empresa' es obligatorio");
				$info->proceso=0;
            }

            if(!empty($LOGO)){
				//validar imagen en caso de que se haya ingresado
				//validar si es b64
				$pathbase = "assets/images/canales/";
				$auxlogo=explode('/', $LOGO);
				$auxlogo=$auxlogo[count($auxlogo)-1];
	/* 			if($dataact->ID!=-1){
					if($result=$this->Empresas_model->getDataCompany(" ID_EMPRESA=?", array($dataact->ID))){
						$auxfoto=$result->result();
						//$auxfoto=$auxfoto->FOTO;
						//echo $auxfoto[0]->FOTO;
						//echo $auxlogo;
						//die();
						if($auxfoto[0]->LOGO!=$auxlogo){
							
							$pathbase = "assets/images/empresas/";
							if($auxfoto[0]->LOGO!=$imgdefault){
								$this->utilities->borrarImagenFolder($pathbase,$auxfoto[0]->LOGO);
								$this->utilities->borrarImagenFolder($pathbase.'s/',$auxfoto[0]->LOGO);
							}
							
							
			                $infoimagen = $this->utilities->almacenarImagenBase64($pathbase, $LOGO, true);
			                if($infoimagen->estado=='success'){
			                	$data->LOGO=$infoimagen->filenombre;
			                }
						}else{
							$data->LOGO=$auxlogo;
						}
				//	die();
						//$pos=strpos($);
					}else{
						$pathbase = "assets/images/empresas/";
		                $infoimagen = $this->utilities->almacenarImagenBase64($pathbase, $LOGO, true);
		                if($infoimagen->estado=='success'){
		                	$data->LOGO=$infoimagen->filenombre;
		                }
					}
				}  */
				
				if($dataact->ID!=-1){
					if($result=$this->Empresas_model->getDataCompany(" ID_EMPRESA=?", array($dataact->ID))){

						$auxfoto=$result->result();
						if($auxfoto[0]->LOGO!=$auxlogo){
			
							
							$pathbase = "assets/images/empresas/";
							if($auxfoto[0]->LOGO!=$imgdefault){
								if  (file_exists(FCPATH .$pathbase.$auxfoto[0]->LOGO)) 
								{
									$this->utilities->borrarImagenFolder($pathbase,$auxfoto[0]->LOGO);
								}

								if  (file_exists(FCPATH . $pathbase.'s/'.$auxfoto[0]->LOGO) ) 
								{
									$this->utilities->borrarImagenFolder($pathbase.'s/',$auxfoto[0]->LOGO);
								}

							}
							
						
							

							
							if ($auxlogo!="default.png") {
								$infoimagen = $this->utilities->almacenarImagenBase64($pathbase, $LOGO, true);
								if($infoimagen->estado=='success'){
									$data->LOGO=$infoimagen->filenombre;
								}
							}
								


						}else{
							
							$data->LOGO=$auxlogo;
						}
				//	die();
						//$pos=strpos($);
					}else{
						$pathbase = "assets/images/categorias/";
						$infoimagen = $this->utilities->almacenarImagenBase64($pathbase, $LOGO, true);
						if($infoimagen->estado=='success'){
							$data->LOGO=$infoimagen->filenombre;
						}
					}
				}else{

					if(strlen($LOGO)>100){
						$pathbase = "assets/images/empresas/";
		                $infoimagen = $this->utilities->almacenarImagenBase64($pathbase, $LOGO, true);
		                if($infoimagen->estado=='success'){
		                	$data->LOGO=$infoimagen->filenombre;
		                }
					}else{
						$aux=explode('/', $LOGO);
						$data->LOGO=null;//$aux[count($aux)-1];
					}
                }
            }
            
            if($info->proceso==0){//no se insertan datos
				echo json_encode($info);
			}else{
				//datos validados y se hace inserción
				if(!isset($_POST['idempresas'])){

						$result = $this->Utilities_model->insertar('EMPRESA',$data);
						echo json_encode($info);

				}else{//editar empresa
					
					
					if($data->LOGO==$imgdefault){
						$data->LOGO=NULL;
						
					}
						
					$set=array("CAMPO" => $data->CAMPO,"FECHAMODIFICACION"=>$dataact->FECHAMODIFICACION);
					if(!empty($data->LOGO)){
							$set = array_merge($set,["LOGO" => $data->LOGO]);
						}					
					$result = $this->Utilities_model->actualizar("EMPRESA","ID_EMPRESA",$set, $dataact->ID);	
					echo json_encode($info);

				}
			}
            
    }


	public function getEmpresas(){
		if (is_null($this->utilities->check_permisos()->datos_user )) {
			exit;
		}
		//if ($this->utilities->check_permisos()->tipo == 1) {
			$pathlogoempresa="assets/images/empresas/";
			$info = new stdClass();
        	$info->proceso = 1; // 0: Hubo problemas, 1: Eliminación realizada
        	$info->errores = [];
        	$info->data=[];
        	$id=$this->security->xss_clean($this->input->post('id'));
			$id=(int)$id;
			if(is_int($id)){
				if($result=$this->Empresas_model->getDataCompany(" ID_EMPRESA=?", array($id))){
					foreach ($result->result() as $r) {
						if($r->LOGO != "default.png" && !file_exists(FCPATH . $pathlogoempresa .$r->LOGO)  && 
						!file_exists(FCPATH . $pathlogoempresa.'/s/' .$r->LOGO)){

							$r->LOGO="default.png";

						}

						$r->LOGO=base_url().$pathlogoempresa.$r->LOGO;
						array_push($info->data, $r);
					}
					
				}else{
					array_push($info->errores, 'La empresa no existe');
				}
			}else{
				$info->proceso=0;
				array_push($info->errores, "Ocurrio un error en el proceso, intente nuevamente");
			}
			echo json_encode($info);
		//}
	}


	public function eliminarEmpresas(){
		if (is_null($this->utilities->check_permisos()->datos_user )) {
			exit;
		}
		$info = new stdClass();
        $info->proceso = 1; // 0: Hubo problemas, 1: Eliminación realizada
        $info->errores = [];
        $id = null;
        $fecha = date('Y-m-d H:i:s');
		//if ($this->utilities->check_permisos()->tipo == 1) {
			$id=$this->security->xss_clean($this->input->post('id'));
			$id=(int)$id;
			if(is_int($id)){
				if($this->Utilities_model->actualizar("EMPRESA", "ID_EMPRESA", array("ESTADO" => 0, "FECHABAJA" => $fecha), $id)){
					//eliminar la imagen acaaa
					
					if($foto=$this->Empresas_model->getImage($id)){
						if($foto->result()[0]->LOGO!='default.png' && $foto->result()[0]->LOGO!=NULL){
							$pathbase = "assets/images/empresas/";
							$this->utilities->borrarImagenFolder($pathbase,$foto->result()[0]->LOGO);
							$this->utilities->borrarImagenFolder($pathbase.'s/',$foto->result()[0]->LOGO);
						}
						
					}
					//$query=$query->result();
					
				}else{
					$info->proceso=0;
					array_push($info->errores, "La empresa no ha podido ser eliminada");
				}
			}else{
				$info->proceso=0;
				array_push($info->errores, "Ocurrio un error en el proceso, intente nuevamente");
			}
			echo json_encode($info);
		//}
	}

	
	public function getDataEmpresas(){
			if (is_null($this->utilities->check_permisos()->datos_user )) {
				exit;
			}
		//if ($this->utilities->check_permisos()->tipo == 1) {
			$exportar=$this->security->xss_clean($this->input->post('exportar'));//revisar exportar
			$pathlogoempresa="assets/images/empresas/";
			$orderby='';
			$limit='';
			$where=1;
			$draw=1;
			$clauses=[];
			$columnas=array("LOGO","EMPRESA_NOMBRE");
			$response=new stdClass;
			$response->data=[];
			if (isset($_POST['draw'])) {
                $draw = intval($_POST['draw']);
            }
			if (isset($_POST['order'])) {
                $posicion = $_POST['order'][0]['column'];
                $order = $_POST['order'][0]['dir'];
                $orderby = "";
                if ($posicion <= count($columnas)) {
                    if ($columnas[$posicion] != "") {
                        $orderby = "ORDER BY " . $columnas[$posicion] . " " . $order;
                    }
                }
            }
            if(!$exportar){
	            if(isset($_POST['start']) && isset($_POST['length'])){
	            	$desde=$_POST['start'];
	            	$hasta=$_POST['length'];
	            	$limit="LIMIT ".$desde.", ".$hasta;
	            }
            }
            
            if (isset($_POST['search']['value'])) {
                $value = str_replace("'", "", $_POST['search']['value']);
				$value = trim($value);
			
                if($value){
					$where .= " AND";
					$where .= " E.CAMPO LIKE ?";
	                array_push($clauses,"%".$value."%");
                }
            }
            if($result=$this->Empresas_model->getDataCompany($where, $clauses, $orderby )){
            	$tamanio=$result->num_rows();
            }
			if($result=$this->Empresas_model->getDataCompany($where, $clauses, $orderby ,$limit )){
				//$tamanio=$result->num_rows();
				if($tamanio==0){
					$row=[];
				}
				
				foreach ($result->result() as $r) {
					if($exportar){
						$row =array(
							$r->EMPRESA_NOMBRE
						);
						
					}else{
						$row = null;
						$row = new stdClass();
						
						
						if($r->LOGO != "default.png" && !file_exists(FCPATH . $pathlogoempresa .$r->LOGO)  && 
														!file_exists(FCPATH . $pathlogoempresa.'/s/' .$r->LOGO)){

							$r->LOGO="default.png";

						}
						
						if ($r->LOGO === "default.png") {

							$row->foto="<div class='media'>"
	                            . "<a><img class='avatar rounded-circle imagen-circle imagentabla' src='".base_url().$pathlogoempresa.'s/'.$r->LOGO."' alt='avatar'></a>"
								. "</div>";
							
						}else {
							$row->foto="<div class='media'>"
	                            . "<a data-fancybox='images' href='".base_url().$pathlogoempresa.$r->LOGO."'><img class='avatar rounded-circle imagen-circle imagentabla' src='".base_url().$pathlogoempresa.'s/'.$r->LOGO."' alt='avatar'></a>"
								. "</div>";
						}
						$row->empresa=$r->EMPRESA_NOMBRE;
	                    $row->opciones='<div class="dropdown">
	                                         <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-cog"></i></button>
	                                         <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 33px, 0px); top: 0px; left: 0px; will-change: transform;">
	                                                <input type="hidden" name="id" value="'.$r->ID.'">
	                                                <input type="hidden" name="'.$this->security->get_csrf_token_name().'" value="'.$this->security->get_csrf_hash().'">
	                                            <button type="submit" class="dropdown-item ancho-100 editar_empresas" data-empresas="'.$r->ID.'"  style="cursor:pointer;width:93%;"><i class="fa fa-pencil"></i> Editar</button>
	                                            <button type="submit" class="dropdown-item ancho-100 borrar_empresas" data-empresas="'.$r->ID.'" style="cursor:pointer;width:93%;" ><i class="fa fa-trash-o"></i> Eliminar</button>';
	                    
					}
					 array_push($response->data, $row);
				}
				$recordsFiltered = $tamanio;
				$response->estado=1;
				echo json_encode(array('data' => $response->data, "recordsTotal" => $tamanio, "columns"=>["EMPRESA"], "recordsFiltered" => $recordsFiltered, 'draw' => $draw));
			}else{
				$row=[];
                $recordsFiltered = 0;
				$response->estado=1;
				echo json_encode(array('data' => $response->data, "recordsTotal" => 0, "columns"=>["EMPRESA"], "recordsFiltered" => $recordsFiltered, 'draw' => $draw));
			}
		//}
	
	}


}