<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Usuarios extends MY_Controller{
	function __construct() {
        parent::__construct();
        $this->load->model('usuarios/Usuarios_model');
        $this->load->model('Utilities_model');
        $this->load->library('Utilities');
        date_default_timezone_set('America/Santiago');
    }

	public function index(){
		if (!is_null($this->utilities->check_permisos()->datos_user)) {
			/*$data['datalibrary'] = array('libjs' => array('libjs'));
			$data['sad']=$this->Usuarios_model->getUsuarios("USUARIOS", "NOMBRE");
			$this->load->view('index', $data);*/
			$data['datalibrary'] = array('titulo' => "Usuarios", 'vista' => array('index','modals'), 'libjs' => array('libjs'), 'active' => 'reporte_cumplimiento', 'active' => 'usuarios');

			
			$this->load->view('estructura/body', $data);
		}else{
			redirect('');
		}
	}

	public function getTipoUsuario(){
		if (is_null($this->utilities->check_permisos()->datos_user )) {
			exit;
			}
		if($result=$this->Usuarios_model->getTipoUsuario()){
			if (is_null($this->utilities->check_permisos()->datos_user )) {
				exit;
				}
			echo json_encode($result);
		}
	}

	public function getEmpresa(){
		if (is_null($this->utilities->check_permisos()->datos_user )) {
			exit;
			}
		if($result=$this->Usuarios_model->getEmpresa()){
			echo json_encode($result);
		}
	}

	public function guardarUsuarios(){
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
			$dataclave->CLAVE=$this->security->xss_clean(trim($this->input->post('clave')));

			$dataclaveact=new stdClass;
			$dataclaveact->FECHAMODIFICACION=date("Y-m-d H:i:s");
			$dataclaveact->FECHABAJA=date("Y-m-d H:i:s"); //*

			$data=new stdClass;
			$data->RUT=$this->security->xss_clean(trim($this->input->post('rut')));
			$data->NOMBRE=$this->security->xss_clean(trim($this->input->post('nombre')));
			$data->APELLIDOP=$this->security->xss_clean(trim($this->input->post('apaterno')));
			$data->APELLIDOM=$this->security->xss_clean(trim($this->input->post('amaterno')));
			$data->CORREO=$this->security->xss_clean(trim($this->input->post('email')));
			$data->ID_TIPO=(int)$this->security->xss_clean(trim($this->input->post('tipo')));
			$data->ID_EMPRESA=(int)$this->security->xss_clean(trim($this->input->post('empresa')));
			$FOTOPERFIL=$this->security->xss_clean(trim($this->input->post('foto')));
			$data->ESTADO=1;
			$data->FECHACREACION=date("Y-m-d H:i:s");
			$data->FECHABAJA=date("Y-m-d H:i:s");
			$data->FOTOPERFIL="";





			$dataact=new stdClass;
			if(!empty($this->input->post('idusuario'))){
				$dataact->ID=$this->security->xss_clean((int)trim($this->input->post('idusuario')));
			}else{
				$dataact->ID=-1;
			}
			
			$dataact->FECHAMODIFICACION=date("Y-m-d H:i:s");
			$dataact->FECHABAJA=date("Y-m-d H:i:s");

			//validaciones de datos recibidos
			if(empty($data->RUT)){
				array_push($info->errores, "El campo 'rut' es obligatorio");
				$info->proceso=0;
			}else{
				if(!$this->utilities->valida_rut($data->RUT)){
					array_push($info->errores, "El rut no es válido");
					$info->proceso=0;
				}else{
					$data->RUT=$this->utilities->formatearRut($data->RUT);
					$data->RUT_DV=substr($data->RUT, -1);
					$data->RUT=substr($data->RUT, 0, strlen($data->RUT)-1);
					$data->RUT=str_replace(['.', '-'], "", $data->RUT);
				}
			}
			if(empty($data->NOMBRE)){
				array_push($info->errores, "El campo 'nombre' es obligatorio");
				$info->proceso=0;
			}
			if(empty($data->APELLIDOP)){
				array_push($info->errores, "El campo 'apellido paterno' es obligatorio");
				$info->proceso=0;
			}
			/*if(empty($data->APELLIDOM)){ //validacion de apellidom
				array_push($info->errores, "El campo 'apellido materno' es obligatorio");
				$info->proceso=0;
			}*/






			if(!empty($FOTOPERFIL)){
				//validar imagen en caso de que se haya ingresado
				//validar si es b64
				$pathbase = "assets/images/fotoperfiles/";
				$auxfotoperfil=explode('/', $FOTOPERFIL);
				$auxfotoperfil=$auxfotoperfil[count($auxfotoperfil)-1];
	/* 			if($dataact->ID!=-1){
					if($result=$this->Usuarios_model->getDataUser(" ID_USUARIO=?", array($dataact->ID))){
						$auxfoto=$result->result();
						//$auxfoto=$auxfoto->FOTO;
						//echo $auxfoto[0]->FOTO;
						//echo $auxfotoperfil;
						//die();
						if($auxfoto[0]->FOTO!=$auxfotoperfil){
							
							$pathbase = "assets/images/fotoperfiles/";
							if($auxfoto[0]->FOTO!=$imgdefault){
								$this->utilities->borrarImagenFolder($pathbase,$auxfoto[0]->FOTO);
								$this->utilities->borrarImagenFolder($pathbase.'s/',$auxfoto[0]->FOTO);
							}
							
							
			                $infoimagen = $this->utilities->almacenarImagenBase64($pathbase, $FOTOPERFIL, true);
			                if($infoimagen->estado=='success'){
			                	$data->FOTOPERFIL=$infoimagen->filenombre;
			                }
						}else{
							$data->FOTOPERFIL=$auxfotoperfil;
						}
				//	die();
						//$pos=strpos($);
					}else{
						$pathbase = "assets/images/fotoperfiles/";
		                $infoimagen = $this->utilities->almacenarImagenBase64($pathbase, $FOTOPERFIL, true);
		                if($infoimagen->estado=='success'){
		                	$data->FOTOPERFIL=$infoimagen->filenombre;
		                }
					}
				} */
				
				
				
				
				
				
				if($dataact->ID!=-1){
					if($result=$this->Usuarios_model->getDataUser(" ID_USUARIO=?", array($dataact->ID))){
						$auxfoto=$result->result();
						
			
						
						if($auxfoto[0]->FOTO!=$auxfotoperfil){
			
							
							$pathbase = "assets/images/fotoperfiles/";
							if($auxfoto[0]->FOTO!=$imgdefault){
								if  (file_exists(FCPATH .$pathbase.$auxfoto[0]->FOTO)) 
								{
									$this->utilities->borrarImagenFolder($pathbase,$auxfoto[0]->FOTO);
								}

								if  (file_exists(FCPATH . $pathbase.'s/'.$auxfoto[0]->FOTO) ) 
								{
									$this->utilities->borrarImagenFolder($pathbase.'s/',$auxfoto[0]->FOTO);
								}

							}
							
						
							

							
							if ($auxfotoperfil!="default.png") {
								$infoimagen = $this->utilities->almacenarImagenBase64($pathbase, $FOTOPERFIL, true);
								if($infoimagen->estado=='success'){
									$data->FOTOPERFIL=$infoimagen->filenombre;
								}
							}
								


						}else{
							
							$data->FOTO=$auxfotoperfil;
						}
				//	die();
						//$pos=strpos($);
					}else{
						$pathbase = "assets/images/fotoperfiles/";
						$infoimagen = $this->utilities->almacenarImagenBase64($pathbase, $FOTOPERFIL, true);
						if($infoimagen->estado=='success'){
							$data->FOTO=$infoimagen->filenombre;
						}
					}
				}else{

					if(strlen($FOTOPERFIL)>100){
						$pathbase = "assets/images/fotoperfiles/";
		                $infoimagen = $this->utilities->almacenarImagenBase64($pathbase, $FOTOPERFIL, true);
		                if($infoimagen->estado=='success'){
		                	$data->FOTOPERFIL=$infoimagen->filenombre;
		                }
					}else{
						$aux=explode('/', $FOTOPERFIL);
						$data->FOTOPERFIL=null;//$aux[count($aux)-1];
					}
				}

				

			}
			if(empty($data->CORREO)){
				array_push($info->errores, "El campo 'correo' es obligatorio");
				$info->proceso=0;
			}else{
				$valido =  (!preg_match("/^([a-z0-9ÁéÉíÍóÓúÚñÑ\+_\-]+)(\.[a-z0-9ÁéÉíÍóÓúÚñÑ\+_\-]+)*@([a-z0-9ÁéÉíÍóÓúÚñÑ\-]+\.)+[a-z]{2,6}$/ix", $data->CORREO)) ? FALSE : TRUE;
				if(!$valido){
					array_push($info->errores, "El campo 'correo' no es valido");
					$info->proceso=0;
				}
				if($dataact->ID==-1){
					if($result=$this->Usuarios_model->getDataUser(" CORREO=?", array($data->CORREO))){
						array_push($info->errores, "El correo no se encuentra disponible");
						$info->proceso=0;
					}
				}
				
			}
			if(!isset($dataact->ID)){
				if(empty($dataclave->CLAVE)){
					array_push($info->errores, "El campo 'clave' es obligatorio");
					$info->proceso=0;
				}
			}
			
			// if(!isset($dataact->ID)){
			// 	if($dataclave->CLAVE){
			// 		array_push($info->errores, "El campo 'clave' debe poseer de 6 a 12 elementos");
			// 		$info->proceso=0;
			// 	}
			// }
			
			if($info->proceso==0){//no se insertan datos
				echo json_encode($info);
			}else{
				//datos validados y se hace insercion
				if(!isset($_POST['idusuario'])){
					if($result=$this->Usuarios_model->getDataUser(" RUT=?", array($data->RUT))){
						$info->proceso=0;
						array_push($info->errores, "El rut no se encuentra disponible!!");
						echo json_encode($info);
					}else{
						if($result=$this->Utilities_model->insertar('USUARIO',$data)){//almacenar usuario
								//insertar clave 
							$dataclave->CLAVE=$result."-".sha1($this->security->xss_clean($this->input->post('clave')));
							$dataclave->ID_USUARIO=$result;
							if($resultclave=$this->Utilities_model->insertar('USUARIO_CLAVE', $dataclave)){
								echo json_encode($info);
							}
						}
					}
				}else{//editar usuario

					
					

					
					

					

					
					if($data->FOTOPERFIL==$imgdefault){
						$data->FOTOPERFIL=NULL;
					}


					


					if($result=$this->Usuarios_model->getDataUser(" (CORREO=? AND ID_USUARIO!=?) ", array($data->CORREO, $dataact->ID))){
						//if($result=$this->Usuarios_model->getDataUser(" CORREO=?", array($data->CORREO))){//NO PUEDO EDITAR MI CORREO
							array_push($info->errores, "El correo no se encuentra disponible");
							$info->proceso=0;
					//	}
					}else{

						
						if($result=$this->Usuarios_model->getDataUser(" ID_USUARIO=?", array($dataact->ID))  ){
							
							
						
							$rutactual=$this->utilities->formatearRut($result->result()[0]->RUT);
							$rutpost=$this->utilities->formatearRut($data->RUT.$data->RUT_DV);

							if($rutactual==$rutpost){
								

								
								$set=array( "NOMBRE" => $data->NOMBRE, "APELLIDOP" => $data->APELLIDOP, "APELLIDOM" => $data->APELLIDOM, "CORREO" => $data->CORREO, "RUT" => $data->RUT, "RUT_DV" => $data->RUT_DV,"FECHAMODIFICACION" => $dataact->FECHAMODIFICACION, "ID_TIPO" => $data->ID_TIPO,"ID_EMPRESA" => $data->ID_EMPRESA);



								
								//$set="NOMBRE='".$data->NOMBRE."', APELLIDOP='".$data->APELLIDOP."', APELLIDOM='".$data->APELLIDOM."', ";
								//$set.="CORREO='".$data->CORREO."', RUT='".$data->RUT."', RUT_DV='".$data->RUT_DV."', FECHAMODIFICACION='".$dataact->FECHAMODIFICACION."', ";
								//$set.="ID_TIPO=".$data->ID_TIPO;
								if(!empty($data->FOTOPERFIL)){
									//$set.=", FOTOPERFIL='".$data->FOTOPERFIL."' ";
									$set = array_merge($set, ["FOTOPERFIL" => $data->FOTOPERFIL]);
									//array_push($set, ["FOTOPERFIL" => $data->FOTOPERFIL]);
										
								}

								
								//if($this->Usuarios_model->actualizarUsuario($set, ' ID_USUARIO=?', array($dataact->ID))){
									
								
								if($this->Utilities_model->actualizar("USUARIO", "ID_USUARIO", $set, $dataact->ID)){
									if(!empty($dataclave->CLAVE)){
										$dataclaveact->CLAVE=$dataact->ID."-".sha1($this->security->xss_clean($this->input->post('clave')));
										//$this->Usuarios_model->actualizarClave("CLAVE='".$dataclaveact->CLAVE."', FECHAMODIFICACION='".$dataclaveact->FECHAMODIFICACION."'", "ID_USUARIO=?",array($dataclaveact->CLAVE));

										
										$this->Utilities_model->actualizar("USUARIO_CLAVE", "ID_USUARIO", array("FECHABAJA" => $dataclaveact->FECHABAJA,"ESTADO" => $dataclaveact->ESTADO='0'), $dataclaveact->CLAVE);
										$dataclave->ID_USUARIO= $dataact->ID;
										$dataclave->CLAVE=$dataclave->ID_USUARIO."-".sha1($this->security->xss_clean($this->input->post('clave')));
										$this->Utilities_model->insertar('USUARIO_CLAVE', $dataclave);
										
								
									}
										
								}
							
								
								else{
									$info->proceso=0;
									array_push($info->errores, "El usuario no ha podido ser editado");
								}


								$datossesion = $this->utilities->check_permisos()->datos_user;
								$idactual = $dataact->ID;
							
								if($datossesion->ID_USUARIO == $idactual){
									$this->utilities->actualizarSession($idactual);
								}

								/* print_r("dps");
								print_r($datossesion);
								die;
 */


							}else{
								if(!$result=$this->Usuarios_model->getDataUser(" RUT=? AND RUT_DV=?", array($data->RUT, $data->RUT_DV))){
									//$set="NOMBRE='".$data->NOMBRE."', APELLIDOP='".$data->APELLIDOP."', APELLIDOM='".$data->APELLIDOM."', ";
									//$set.="CORREO='".$data->CORREO."', RUT='".$data->RUT."', RUT_DV='".$data->RUT_DV."', FECHAMODIFICACION='".$dataact->FECHAMODIFICACION."', ";
									//$set.="ID_TIPO=".$data->ID_TIPO;
									$set=array("NOMBRE" => $data->NOMBRE, "APELLIDOP" => $data->APELLIDOP, "APELLIDOM" => $data->APELLIDOM, "CORREO" => $data->CORREO, "RUT" => $data->RUT, "RUT_DV" => $data->RUT_DV,
												"FECHAMODIFICACION" => $dataact->FECHAMODIFICACION, "ID_TIPO" => $data->ID_TIPO,"ID_EMPRESA" => $data->ID_EMPRESA);
									if(!empty($data->FOTOPERFIL)){
										//$set.=", FOTOPERFIL='".$data->FOTOPERFIL."' ";
										$set = array_merge($set, ["FOTOPERFIL" => $data->FOTOPERFIL]);
									}
									//if($this->Usuarios_model->actualizarUsuario($set, ' ID_USUARIO=?', array($dataact->ID))){
									
									if($this->Utilities_model->actualizar("USUARIO", "ID_USUARIO", $set, $dataact->ID)){
										if(!empty($dataclave->CLAVE)){
											$dataclaveact->CLAVE=$dataact->ID."-".sha1($this->security->xss_clean($this->input->post('clave')));
											$this->Utilities_model->actualizar("USUARIO_CLAVE", "ID_USUARIO", array("CLAVE" => $dataclaveact->CLAVE, "FECHAMODIFICACION" => $dataclaveact->FECHAMODIFICACION), $dataclaveact->CLAVE);
											//$this->Usuarios_model->actualizarClave("CLAVE='".$dataclaveact->CLAVE."', FECHAMODIFICACION='".$dataclaveact->FECHAMODIFICACION."'", "ID_USUARIO=?",array($dataclaveact->CLAVE));
										}

										
										
									}else{
										$info->proceso=0;
										array_push($info->errores, "El usuario no ha podido ser editado");
									}
								}else{
									$info->proceso=0;
									array_push($info->errores, "El rut ingresado ya existe!!");
								}
							}						
						}

						
					}
					
					echo json_encode($info);

				}
			}
		//}
	}

	public function getUsuario(){
		if (is_null($this->utilities->check_permisos()->datos_user )) {
			exit;
			}
		//if ($this->utilities->check_permisos()->tipo == 1) {
			$pathfotoperfil="assets/images/fotoperfiles/";
			$info = new stdClass();
        	$info->proceso = 1; // 0: Hubo problemas, 1: Eliminación realizada
        	$info->errores = [];
        	$info->data=[];
        	$id=$this->security->xss_clean($this->input->post('id'));
			$id=(int)$id;
			if(is_int($id)){
				if($result=$this->Usuarios_model->getDataUser(" ID_USUARIO=?", array($id))){
					foreach ($result->result() as $r) {
						if($r->FOTO != "default.png" && !file_exists(FCPATH . $pathfotoperfil .$r->FOTO)  && 
														!file_exists(FCPATH . $pathfotoperfil.'/s/' .$r->FOTO)){

							$r->FOTO="default.png";

						}
						
						$r->RUT=$this->utilities->formatearRut($r->RUT);
						$r->FOTO=base_url().$pathfotoperfil.$r->FOTO;
						array_push($info->data, $r);
					}
					
				}else{
					array_push($info->errores, 'El usuario no existe');
				}
			}else{
				$info->proceso=0;
				array_push($info->errores, "Ocurrio un error en el proceso, intente nuevamente");
			}
			echo json_encode($info);
		//}
	}

	public function eliminarUsuario(){
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
				if($this->Utilities_model->actualizar("USUARIO", "ID_USUARIO", array("ESTADO" => 0, "FECHABAJA" => $fecha), $id)){
					//eliminar la imagen acaaa
					
					if($foto=$this->Usuarios_model->getImage($id)){
						if($foto->result()[0]->FOTOPERFIL!='default.png' && $foto->result()[0]->FOTOPERFIL!=NULL){
							$pathbase = "assets/images/fotoperfiles/";
							$this->utilities->borrarImagenFolder($pathbase,$foto->result()[0]->FOTOPERFIL);
							$this->utilities->borrarImagenFolder($pathbase.'s/',$foto->result()[0]->FOTOPERFIL);
						}
						
					}
					//$query=$query->result();
					
				}else{
					$info->proceso=0;
					array_push($info->errores, "El usuario no ha podido ser eliminado");
				}
			}else{
				$info->proceso=0;
				array_push($info->errores, "Ocurrio un error en el proceso, intente nuevamente");
			}
			echo json_encode($info);
		//}
	}

	
	public function getDataUsuarios(){
		if (is_null($this->utilities->check_permisos()->datos_user )) {
			exit;
			}
		//if ($this->utilities->check_permisos()->tipo == 1) {
			
			$exportar=$this->security->xss_clean($this->input->post('exportar'));//revisar exportar
			$pathfotoperfil="assets/images/fotoperfiles/";
			$orderby='';
			$limit='';
			$where=1;
			$draw=1;
			$clauses=[];
			$columnas=array("FOTO", "RUT", "CORREO", "NOMBRE","EMPRESA_NOMBRE");
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
				$value2 = str_replace("'","", $_POST['search']['value']);
				$value2 = trim($value2);
				if(1 === preg_match('~[0-9kK]~', $value2)){
					$value2 = str_replace(".", "", $value2);
					$value2 = preg_replace('/[^0-9.kK]+/', '', $value2);
				}
                if($value){
                	$where .= " AND (
	                   CONCAT(IFNULL(U.NOMBRE,''),' ',IFNULL(U.APELLIDOP,''),' ',IFNULL(U.APELLIDOM,'')) LIKE ?";
					$where .= " ";
					if($value2){
						$where .= " OR CONCAT(U.RUT,U.RUT_DV) LIKE ?";
					}	
	                $where .= " OR U.CORREO LIKE ?";
	                $where .= " OR U.NOMBRE LIKE ?";
					$where .= " OR C.CAMPO LIKE ?";
					$where .= " OR E.CAMPO LIKE ?";
					$where .= " )";
					

	                array_push($clauses,"%".$value."%","%".$value2."%","%".$value."%","%".$value."%",
							  "%".$value."%","%".$value."%");
							  

				}


				switch($_POST['tipo']){
					case '1':
						$where .= " AND (  C.ID_TIPO=? )";
						array_push($clauses,1);
						break;
					case '2':
						$where .= " AND (  C.ID_TIPO=? )";
						array_push($clauses,2);
						break;
					case '3':
						$where .= " AND (  C.ID_TIPO=? )";
						array_push($clauses,3);
						break;
				}

		
			
				
            }
            if($result=$this->Usuarios_model->getDataUser($where, $clauses, $orderby)){
            	$tamanio=$result->num_rows();
            }
			if($result=$this->Usuarios_model->getDataUser($where, $clauses, $orderby ,$limit )){
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
							$this->utilities->formatearRut($r->RUT), $r->EMAIL, $r->NOMBRE, $r->EMPRESA_NOMBRE
						);
						
					}else{
						$row = null;
						$row = new stdClass();
						if($r->FOTO != "default.png" && !file_exists(FCPATH . $pathfotoperfil .$r->FOTO)  && 
														!file_exists(FCPATH . $pathfotoperfil.'/s/' .$r->FOTO)){

							$r->FOTO="default.png";

						}
						if ($r->FOTO === "default.png") {

							$row->foto="<div class='media'>"
	                            . "<a><img class='avatar rounded-circle imagen-circle imagentabla' src='".base_url().$pathfotoperfil.'s/'.$r->FOTO."' alt='avatar'></a>"
								. "</div>";
							
						}else {
							$row->foto="<div class='media'>"
	                            . "<a data-fancybox='images' href='".base_url().$pathfotoperfil.$r->FOTO."'><img class='avatar rounded-circle imagen-circle imagentabla' src='".base_url().$pathfotoperfil.'s/'.$r->FOTO."' alt='avatar'></a>"
								. "</div>";
						}
						
								
	                    $row->rut=$this->utilities->formatearRut($r->RUT);
	                    $row->email=$r->EMAIL;
	                    $row->nombre=$r->NOMBRE;
	
						$row->empresa=$r->EMPRESA_NOMBRE;
	                    $row->opciones='<div class="dropdown">
	                                         <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-cog"></i></button>
	                                         <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 33px, 0px); top: 0px; left: 0px; will-change: transform;">
	                                                <input type="hidden" name="id" value="'.$r->ID.'">
	                                                <input type="hidden" name="'.$this->security->get_csrf_token_name().'" value="'.$this->security->get_csrf_hash().'">
	                                            <button type="submit" class="dropdown-item ancho-100 editar_usuario" data-usuario="'.$r->ID.'"  style="cursor:pointer;width:93%;"><i class="fa fa-pencil"></i> Editar</button>
	                                            <button type="submit" class="dropdown-item ancho-100 borrar_usuario" data-usuario="'.$r->ID.'" style="cursor:pointer;width:93%;" ><i class="fa fa-trash-o"></i> Eliminar</button>';
	                    
					}
					 array_push($response->data, $row);
				}
				$recordsFiltered = $tamanio;
				$response->estado=1;
				echo json_encode(array('data' => $response->data, "recordsTotal" => $tamanio, "columns"=>["RUT", "CORREO", "NOMBRE","EMPRESA"], "recordsFiltered" => $recordsFiltered, 'draw' => $draw));
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
				echo json_encode(array('data' => $response->data, "recordsTotal" => 0, "columns"=>["RUT", "CORREO", "NOMBRE","EMPRESA"], "recordsFiltered" => $recordsFiltered, 'draw' => $draw));
			}
		//}
	}
}






////////////////////////////////////////////////////////////////AQUI