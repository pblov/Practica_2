<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Categorias extends MY_Controller{
	function __construct() {
        parent::__construct();
		$this->load->model('categorias/Categorias_model');
        $this->load->model('Utilities_model');
        $this->load->library('Utilities');
        date_default_timezone_set('America/Santiago');
    }

	public function index(){
		if (!is_null($this->utilities->check_permisos()->datos_user)) {  
			$data['datalibrary'] = array('titulo' => "Categorías", 'vista' => array('index','modals'), 'libjs' => array('libjs'), 'active' => 'reporte_cumplimiento', 'active' => 'categorias');
			$this->load->view('estructura/body', $data);
		}else{
			redirect('');
		}

	}
	

		
	
	
		public function guardarCategoria(){
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
	
				$dataclave=new stdClass;
				$dataclave->FECHACREACION=date("Y-m-d H:i:s");
				$dataclave->ESTADO=1;
				//$dataclave->CLAVE=$this->security->xss_clean(trim($this->input->post('clave')));
	
				$dataclaveact=new stdClass;
				$dataclaveact->FECHAMODIFICACION=date("Y-m-d H:i:s");
				$dataclaveact->FECHABAJA=date("Y-m-d H:i:s"); //*
	
				$data=new stdClass;
				// $data->RUT=$this->security->xss_clean(trim($this->input->post('rut')));
				// $data->NOMBRE=$this->security->xss_clean(trim($this->input->post('nombre')));
				// $data->APELLIDOP=$this->security->xss_clean(trim($this->input->post('apaterno')));
				// $data->APELLIDOM=$this->security->xss_clean(trim($this->input->post('amaterno')));
				// $data->CORREO=$this->security->xss_clean(trim($this->input->post('email')));
				// $data->ID_TIPO=(int)$this->security->xss_clean(trim($this->input->post('tipo')));
				// $data->ID_EMPRESA=(int)$this->security->xss_clean(trim($this->input->post('empresa')));


			
				$data->CAMPO=$this->security->xss_clean(trim($this->input->post('campo')));

				$LOGO=$this->security->xss_clean(trim($this->input->post('foto')));

				

				$data->ESTADO=1;
				$data->FECHACREACION=date("Y-m-d H:i:s");
				$data->FECHABAJA=date("Y-m-d H:i:s");
				$data->LOGO="";
	
	
	
				
				
	
				$dataact=new stdClass;
				if(!empty($this->input->post('idusuario'))){
					$dataact->ID=$this->security->xss_clean((int)trim($this->input->post('idusuario')));
					//die();

				}else{
					$dataact->ID=-1;
				}

			
				
				$dataact->FECHAMODIFICACION=date("Y-m-d H:i:s");
				$dataact->FECHABAJA=date("Y-m-d H:i:s");
	
				//validaciones de datos recibidos


				// if(!empty($dataact->ID)){
				// 	array_push($info->errores, " ID :",$dataact->ID);
				// 	$info->proceso=0;
				// }
				

				if(empty($data->CAMPO)){
					array_push($info->errores, "El campo 'Nombre' es obligatorio",$data->CAMPO);
					$info->proceso=0;
				}


			




				// if(empty($data->RUT)){
				// 	array_push($info->errores, "El campo 'rut' es obligatorio");
				// 	$info->proceso=0;
				// }else{
				// 	if(!$this->utilities->valida_rut($data->RUT)){
				// 		array_push($info->errores, "El rut no es válido");
				// 		$info->proceso=0;
				// 	}else{
				// 		$data->RUT=$this->utilities->formatearRut($data->RUT);
				// 		$data->RUT_DV=substr($data->RUT, -1);
				// 		$data->RUT=substr($data->RUT, 0, strlen($data->RUT)-1);
				// 		$data->RUT=str_replace(['.', '-'], "", $data->RUT);
				// 	}
				// }
				// if(empty($data->NOMBRE)){
				// 	array_push($info->errores, "El campo 'nombre' es obligatorio");
				// 	$info->proceso=0;
				// }
				// if(empty($data->APELLIDOP)){
				// 	array_push($info->errores, "El campo 'apellido paterno' es obligatorio");
				// 	$info->proceso=0;
				// }
				/*if(empty($data->APELLIDOM)){ //validacion de apellidom
					array_push($info->errores, "El campo 'apellido materno' es obligatorio");
					$info->proceso=0;
				}*/
	
	
	
	
	
				//echo("Logo".$LOGO);
				if(!empty($LOGO)){
					$pathbase = "assets/images/categorias/";

					//validar imagen en caso de que se haya ingresado
					//validar si es b64
					$auxfotoperfil=explode('/', $LOGO);
					$auxfotoperfil=$auxfotoperfil[count($auxfotoperfil)-1];
					
					if($dataact->ID!=-1){
						if($result=$this->Categorias_model->getDataCategoria(" ID_CATEGORIA=?", array($dataact->ID))){
							$auxfoto=$result->result();
							
				
							
							if($auxfoto[0]->LOGO!=$auxfotoperfil){
				
								
								$pathbase = "assets/images/categorias/";
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
								
							
								

								
								if ($auxfotoperfil!="default.png") {
									$infoimagen = $this->utilities->almacenarImagenBase64($pathbase, $LOGO, true);
									if($infoimagen->estado=='success'){
										$data->LOGO=$infoimagen->filenombre;
									}
								}
									


							}else{
								
								$data->LOGO=$auxfotoperfil;
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
							$pathbase = "assets/images/categorias/";
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
					//datos validados y se hace insercion
					if(!isset($_POST['idusuario'])){
						
							if($result=$this->Utilities_model->insertar('CATEGORIA',$data)){//almacenar usuario
									//insertar clave 
								// $dataclave->CLAVE=$result."-".sha1($this->security->xss_clean($this->input->post('clave')));
								// $dataclave->ID_USUARIO=$result;
								// if($resultclave=$this->Utilities_model->insertar('USUARIO_CLAVE', $dataclave)){
								// 	echo json_encode($info);
								// }
								echo json_encode($info);
							}
						
					}else{//editar usuario
	
						
						
	
						if($data->LOGO==$imgdefault){
							$data->LOGO=NULL;
						}
	
	
	
	
						
							if($result=$this->Categorias_model->getDataCategoria(" ID_CATEGORIA=?", array($dataact->ID))){
								//$rutactual=$this->utilities->formatearRut($result->result()[0]->RUT);
								//$rutpost=$this->utilities->formatearRut($data->RUT.$data->RUT_DV);
	
								
									
										$set=array("CAMPO" => $data->CAMPO);
										if(!empty($data->LOGO)){
											//$set.=", FOTOPERFIL='".$data->FOTOPERFIL."' ";
											$set = array_merge($set, ["LOGO" => $data->LOGO]);
										}
										//if($this->Usuarios_model->actualizarUsuario($set, ' ID_USUARIO=?', array($dataact->ID))){
										
										if($this->Utilities_model->actualizar("CATEGORIA", "ID_CATEGORIA", $set, $dataact->ID)){
											// if(!empty($dataclave->CLAVE)){
											// 	$dataclaveact->CLAVE=$dataact->ID."-".sha1($this->security->xss_clean($this->input->post('clave')));
											// 	$this->Utilities_model->actualizar("USUARIO_CLAVE", "ID_USUARIO", array("CLAVE" => $dataclaveact->CLAVE, "FECHAMODIFICACION" => $dataclaveact->FECHAMODIFICACION), $dataclaveact->CLAVE);
											// 	//$this->Usuarios_model->actualizarClave("CLAVE='".$dataclaveact->CLAVE."', FECHAMODIFICACION='".$dataclaveact->FECHAMODIFICACION."'", "ID_USUARIO=?",array($dataclaveact->CLAVE));
											// }
										}else{
											$info->proceso=0;
											array_push($info->errores, "La Categoría no ha podido ser editado");
										}
									
													
							}
						
						
						echo json_encode($info);
	
					}
				}
			//}
		}
	






		public function getCategoria(){
			if (is_null($this->utilities->check_permisos()->datos_user )) {
				exit;
				}
			//if ($this->utilities->check_permisos()->tipo == 1) {
				$pathfotoperfil="assets/images/categorias/";
				$info = new stdClass();
				$info->proceso = 1; // 0: Hubo problemas, 1: Eliminación realizada
				$info->errores = [];
				$info->data=[];
				$id=$this->security->xss_clean($this->input->post('id'));
				$id=(int)$id;
				if(is_int($id)){
					if($result=$this->Categorias_model->getDataCategoria(" ID_CATEGORIA=?", array($id))){
						foreach ($result->result() as $r) {
							//$r->RUT=$this->utilities->formatearRut($r->RUT);
							if ($r->LOGO && file_exists(FCPATH . $pathfotoperfil.$r->LOGO) && file_exists(FCPATH . $pathfotoperfil.'s/'.$r->LOGO)  ) 
	
							{	
								$r->LOGO=base_url().$pathfotoperfil.$r->LOGO;
								array_push($info->data, $r);
							}else {
								$r->LOGO=base_url().$pathfotoperfil."default.png";
								array_push($info->data, $r);
							}
						}
						
					}else{
						array_push($info->errores, 'El canal no existe');
					}
				}else{
					$info->proceso=0;
					array_push($info->errores, "Ocurrio un error en el proceso, intente nuevamente");
				}
				echo json_encode($info);
			//}
		}
	
		public function eliminarCategoria(){
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
					//if($this->Usuarios_model->actualizarUsuario("ESTADO=0, FECHABAJA='".$fecha."'", ' ID_USUARIO=?', array($id))){
					if($this->Utilities_model->actualizar("CATEGORIA", "ID_CATEGORIA", array("ESTADO" => 0, "FECHABAJA" => $fecha), $id)){
						//eliminar la imagen acaaa
						
						if($foto=$this->Categorias_model->getImage($id)){
							if($foto->result()[0]->LOGO!='default.png' && $foto->result()[0]->LOGO!=NULL){
								$pathbase = "assets/images/categorias/";
								$this->utilities->borrarImagenFolder($pathbase,$foto->result()[0]->LOGO);
								$this->utilities->borrarImagenFolder($pathbase.'s/',$foto->result()[0]->LOGO);
							}
							
						}
						//$query=$query->result();
						
					}else{
						$info->proceso=0;
						array_push($info->errores, "La categoría no ha podido ser eliminada");
					}
				}else{
					$info->proceso=0;
					array_push($info->errores, "Ocurrio un error en el proceso, intente nuevamente");
				}
				echo json_encode($info);
			//}
		}
	
		
		public function getDataCategorias(){
			


			if (is_null($this->utilities->check_permisos()->datos_user )) {
				exit;
				}
			//if ($this->utilities->check_permisos()->tipo == 1) {
				
				$exportar=$this->security->xss_clean($this->input->post('exportar'));//revisar exportar
				$pathfotoperfil="assets/images/categorias/";
				$orderby='';
				$limit='';
				$where=1;
				$draw=1;
				$clauses=[];
				$columnas=array("LOGO", "CAMPO");
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
					//echo("que pasooooo".$value."***********");
					if(1 === preg_match('~[0-9]~', $value)){
						$value = str_replace(".", "", $value);
						//$value = preg_replace('/[^0-9.]+/', '', $value);
					}
					
					if($value){
						$where .= " AND (";
						$where .= " ";
						$where .= " C.CAMPO LIKE ?";
						$where .= " )";
						
	
					array_push($clauses,"%".$value."%");
								  
	
					}
	
				
					
				}


				if($result=$this->Categorias_model->getDataCategoria($where, $clauses, $orderby)){
					$tamanio=$result->num_rows();
				}
				if($result=$this->Categorias_model->getDataCategoria($where, $clauses, $orderby ,$limit )){
					//$tamanio=$result->num_rows();
					if($tamanio==0){
						$row=[];
					/*	$row = null;
						$row = new stdClass();
						$row->foto="";
						$row->rut="";
						$row->email="";
						$row->nombre="";
						$row->cargo="";
						$row->opciones="";*/
					   // array_push($response->data, $row);
					}
					foreach ($result->result() as $r) {
						if($exportar){
							$row =array(
								 $r->CAMPO
							);
							
						}else{
							$row = null;
							$row = new stdClass();

							//header("Location: http://example.com/myOtherPage.php");
				
							if ($r->LOGO && file_exists(FCPATH . $pathfotoperfil.$r->LOGO) && file_exists(FCPATH . $pathfotoperfil.'s/'.$r->LOGO)  ) 
							{	
								
								
								
								if ($r->LOGO === "default.png") {
									
									$row->logo="<div class='media'>"
										. "<a><img class='avatar rounded-circle imagen-circle imagentabla' src='".base_url().$pathfotoperfil.'s/'.$r->LOGO."' alt='avatar'></a>"
										. "</div>";
									
								}else {

									
										$row->logo="<div class='media'>"
										." <a data-fancybox='images' href='".base_url().$pathfotoperfil.$r->LOGO."'>
												<img class='avatar rounded-circle imagen-circle imagentabla' src='".base_url().$pathfotoperfil.'s/'.$r->LOGO."' alt='avatar'>
											</a>"
										. "</div>";	
								}

							}else {
								$row->logo="<div class='media'>"
										. "<a><img class='avatar rounded-circle imagen-circle imagentabla' src='".base_url().$pathfotoperfil.'s/'.'default.png'."' alt='avatar'></a>"
										. "</div>";
							}
								
							
									
							$row->id=$r->ID_CATEGORIA;
							$row->campo=$r->CAMPO;
							// $row->descripcion_larga=$r->DESCRIPCION_LARGA;
							

							
							

							
							$row->opciones='<div class="dropdown">
	                                         <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-cog"></i></button>
	                                         <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 33px, 0px); top: 0px; left: 0px; will-change: transform;">
	                                                <input type="hidden" name="id" value="'.$r->ID_CATEGORIA.'">
	                                                <input type="hidden" name="'.$this->security->get_csrf_token_name().'" value="'.$this->security->get_csrf_hash().'">
	                                            <button type="submit" class="dropdown-item ancho-100 editar_usuario" data-usuario="'.$r->ID_CATEGORIA.'"  style="cursor:pointer;width:93%;"><i class="fa fa-pencil"></i> Editar</button>
	                                            <button type="submit" class="dropdown-item ancho-100 borrar_usuario" data-usuario="'.$r->ID_CATEGORIA	.'" style="cursor:pointer;width:93%;" ><i class="fa fa-trash-o"></i> Eliminar</button>';
	                    
								
						}
						 array_push($response->data, $row);
					}
					$recordsFiltered = $tamanio;
					$response->estado=1;
					echo json_encode(array('data' => $response->data, "recordsTotal" => $tamanio, "columns"=>["CATEGORÍA"], "recordsFiltered" => $recordsFiltered, 'draw' => $draw));
				}else{
					$row=[];
					/*$row = null;
					$row = new stdClass();
					$row->foto="";
					$row->rut="";
					$row->email="";
					$row->nombre="";
					$row->cargo="";
					$row->opciones="";*/
				 //   array_push($response->data, $row);
					$recordsFiltered = 0;
					$response->estado=1;
					echo json_encode(array('data' => $response->data, "recordsTotal" => 0, "columns"=>["CATEGORÍA"], "recordsFiltered" => $recordsFiltered, 'draw' => $draw));
				}
			//}
		}


	
	}
	
	
	
	
	
	
	///////////////////////////////////////////AQUI