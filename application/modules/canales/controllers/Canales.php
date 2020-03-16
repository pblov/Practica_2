<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Canales extends MY_Controller{
	function __construct() {
        parent::__construct();
		$this->load->model('canales/Canales_model');
        $this->load->model('Utilities_model');
        $this->load->library('Utilities');
        date_default_timezone_set('America/Santiago');
    }

	public function index(){
		if (!is_null($this->utilities->check_permisos()->datos_user)) {  
			$data['datalibrary'] = array('titulo' => "Canales", 'vista' => array('index','modals'), 'libjs' => array('libjs'), 'active' => 'reporte_cumplimiento', 'active' => 'canales');
			$this->load->view('estructura/body', $data);
		}else{
			redirect('');
		}

	}
	

	
	
	
	
	public function guardarCanal(){
		if (is_null($this->utilities->check_permisos()->datos_user )) {
			exit;
		}

	
			
		//if ($this->utilities->check_permisos()->tipo == 1) {
			$imgdefault='default.png';
			$info = new stdClass();
			$info->proceso = 1; // 0:no se pudo procesar, 1: procesorealizado correctamente
			$info->errores = []; // Errores encontrados en el proceso
			$info->id = null; //Id del canal creado/editado
			$info->data = null; // Errores encontrados en el proceso

			$dataclave=new stdClass;
			$dataclave->FECHACREACION=date("Y-m-d H:i:s");
			$dataclave->ESTADO=1;
			//$dataclave->CLAVE=$this->security->xss_clean(trim($this->input->post('clave')));

			$dataclaveact=new stdClass;
			$dataclaveact->FECHAMODIFICACION=date("Y-m-d H:i:s");
			$dataclaveact->FECHABAJA=date("Y-m-d H:i:s"); //*

			$data=new stdClass;
	


			$data->NOMBRE=$this->security->xss_clean(trim($this->input->post('nombre')));
			$data->DESCRIPCION_CORTA=$this->security->xss_clean(trim($this->input->post('descripcion_corta')));
			$data->DESCRIPCION_LARGA=$this->security->xss_clean(trim($this->input->post('descripcion_larga')));

			$LOGO=$this->security->xss_clean(trim($this->input->post('foto')));

			

			$data->ESTADO=1;
			$data->FECHACREACION=date("Y-m-d H:i:s");
			$data->FECHABAJA=date("Y-m-d H:i:s");
			$data->LOGO="";



			
			

			$dataact=new stdClass;
			if(!empty($this->input->post('id_canal'))){
				$dataact->ID=$this->security->xss_clean((int)trim($this->input->post('id_canal')));
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
			

			if(empty($data->DESCRIPCION_CORTA)){
				array_push($info->errores, "El campo 'Descripcion corta' es obligatorio");
				$info->proceso=0;
			}

			if(empty($data->NOMBRE)){
				array_push($info->errores, "El campo 'Nombre' es obligatorio");
				$info->proceso=0;
			}

			if(empty($data->DESCRIPCION_LARGA)){
				array_push($info->errores, "El campo 'Descripcion larga' es obligatorio");
				$info->proceso=0;
			}


		





			//echo("Logo".$LOGO);
			if(!empty($LOGO)){
				$pathbase = "assets/images/canales/";
				// if  (!file_exists(FCPATH .$pathbase.$auxfoto[0]->LOGO) || !file_exists(FCPATH . $pathbase.'s/'.$auxfoto[0]->LOGO)  ) 
						// {
						// 	$auxfoto[0]->LOGO=$auxfotoperfil;
						// }

				//validar imagen en caso de que se haya ingresado
				//validar si es b64
				$auxfotoperfil=explode('/', $LOGO);
				$auxfotoperfil=$auxfotoperfil[count($auxfotoperfil)-1];
				
				if($dataact->ID!=-1){
					if($result=$this->Canales_model->getDataCanal(" ID_CANAL=?", array($dataact->ID))){
						$auxfoto=$result->result();
						
						
						if($auxfoto[0]->LOGO!=$auxfotoperfil){
			
							
							$pathbase = "assets/images/canales/";
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
						$pathbase = "assets/images/canales/";
						$infoimagen = $this->almacenarImagenBase64($pathbase, $LOGO, true);
						if($infoimagen->estado=='success'){
							$data->LOGO=$infoimagen->filenombre;
						}
					}
				}else{

					if(strlen($LOGO)>100){
						$pathbase = "assets/images/canales/";
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
				if(!isset($_POST['id_canal'])){
					
						if($result=$this->Utilities_model->insertar('CANAL',$data)){//almacenar canal
							
							echo json_encode($info);
						}
					
				}else{//editar canal

					
					

					if($data->LOGO==$imgdefault){
						$data->LOGO=NULL;
					}




					
						if($result=$this->Canales_model->getDataCanal(" ID_CANAL=?", array($dataact->ID))){
				

							
								
									$set=array("DESCRIPCION_CORTA" => $data->DESCRIPCION_CORTA, "DESCRIPCION_LARGA" => $data->DESCRIPCION_LARGA,"NOMBRE"=> $data->NOMBRE);
									if(!empty($data->LOGO)){
										//$set.=", FOTOPERFIL='".$data->FOTOPERFIL."' ";
										$set = array_merge($set, ["LOGO" => $data->LOGO]);
									}
									//if($this->Canal_model->actualizarCanal($set, ' ID_CANAL=?', array($dataact->ID))){
									
									if($this->Utilities_model->actualizar("CANAL", "ID_CANAL", $set, $dataact->ID)){
										
									}else{
										$info->proceso=0;
										array_push($info->errores, "El CANAL no ha podido ser editado");
									}
								
												
						}
					
					
					echo json_encode($info);

				}
			}
		//}
	}






	public function guardarContenido(){//procesar
		if (is_null($this->utilities->check_permisos()->datos_user )) {
			exit;
			}

		
	
			$info = new stdClass();
			$info->proceso = 1; // 0:no se pudo procesar, 1: procesorealizado correctamente
			$info->errores = []; // Errores encontrados en el proceso
			$info->id = null; //Id del canal creado/editado
			$info->data = null; // Errores encontrados en el proceso

			$dataclave=new stdClass;
			$dataclave->FECHACREACION=date("Y-m-d H:i:s");
			$dataclave->ESTADO=1;
			//$dataclave->CLAVE=$this->security->xss_clean(trim($this->input->post('clave')));

			$dataclaveact=new stdClass;
			$dataclaveact->FECHAMODIFICACION=date("Y-m-d H:i:s");
			$dataclaveact->FECHABAJA=date("Y-m-d H:i:s"); //*

			$data=new stdClass;
			$dataContCat=new stdClass;



			$data->DESCRIPCION=$this->security->xss_clean(trim($this->input->post('descripcion')));
			$data->NOMBRE=$this->security->xss_clean(trim($this->input->post('nombre')));
			$data->ESCENA=$this->security->xss_clean(trim($this->input->post('escena')));
			$data->CLAVE=$this->security->xss_clean(trim($this->input->post('clave')));
			$data->ID_CANAL=$this->security->xss_clean(trim($this->input->post('canalActual')));
			$data->VISIBILIDAD=$this->security->xss_clean(trim($this->input->post('visibilidad')));
			$dataContCat->ID_CATEGORIA=$this->security->xss_clean(trim($this->input->post('categoria')));
			
			

			$data->ESTADO=1;
			$data->FECHACREACION=date("Y-m-d H:i:s");
			$data->FECHABAJA=date("Y-m-d H:i:s");
			$dataContCat->FECHACREACION=date("Y-m-d H:i:s");



			/*Imagen destacada*/
			$datadestacada = new stdClass();
			$IMG_D=$this->security->xss_clean(trim($this->input->post('foto')));
			$datadestacada->SRC="";
			$datadestacada->TIPO=1;
			$datadestacada->ESTADO=1;
			$datadestacada->FECHACREACION=date("Y-m-d H:i:s");
			$imgdefault='default.png';
			
			/* Galeria */

			$dataorden = new stdClass;
			$fecha = date("Y-m-d H:i:s");
			$dataorden->ADJUNTOS_ELIMINAR=json_decode($this->input->post('adjuntos_eliminar'));
			
			
			$dataorden->listaorden = [];
			if (!empty($this->input->post('identificador'))) {
				$dataorden->listaorden = $this->input->post('identificador');
				$dataorden->listaorden = json_decode($dataorden->listaorden);
				if(!is_array($dataorden->listaorden)){
					$dataorden->listaorden = [];
				}
			}




			$dataact=new stdClass;
			if(!empty($this->input->post('idcontenido'))){
				$dataact->ID=$this->security->xss_clean((int)trim($this->input->post('idcontenido')));
				//die();

			}else{
				$dataact->ID=-1;
			}

		
			
			$dataact->FECHAMODIFICACION=date("Y-m-d H:i:s");
			$dataact->FECHABAJA=date("Y-m-d H:i:s");

	

			if(empty($data->DESCRIPCION)){
				array_push($info->errores, "El campo 'Descripcion' es obligatorio");
				$info->proceso=0;
			}

			if(empty($data->ESCENA)){
				array_push($info->errores, "El campo 'Escena' es obligatorio");
				$info->proceso=0;
			}
			
			if(empty($data->NOMBRE)){
				array_push($info->errores, "El campo 'Título' es obligatorio");
				$info->proceso=0;
			}

			 if (empty($dataContCat->ID_CATEGORIA)) {
				array_push($info->errores, "El campo 'Categoria' es obligatorio");
				$info->proceso=0;
			
			}

			

			$dataContCat->ID_CATEGORIA = explode(",",$dataContCat->ID_CATEGORIA);




			/* Imagen destacada */
			if(!empty($IMG_D)){


				//validar imagen en caso de que se haya ingresado
				//validar si es b64
				$auxfotoperfildestacada=explode('/', $IMG_D);
				$auxfotoperfildestacada=$auxfotoperfildestacada[count($auxfotoperfildestacada)-1];
				

				if($dataact->ID!=-1){
					if($result=$this->Canales_model->getContenidoGaleria(" CCG.ID_CONTENIDO = ? AND CCG.TIPO = 1", array($dataact->ID))){
						$auxfoto=$result->result();
						
						
								
						
						
						if($auxfoto[0]->SRC!=$auxfotoperfildestacada){
							
							
							
							$pathbase = "assets/images/canales/contenido_galeria/";
							if($auxfoto[0]->SRC!=$imgdefault){
								if  (file_exists(FCPATH .$pathbase.$auxfoto[0]->SRC)){
									$this->utilities->borrarImagenFolder($pathbase,$auxfoto[0]->SRC);
								}

								if  (file_exists(FCPATH . $pathbase.'s/'.$auxfoto[0]->SRC) ) {
									$this->utilities->borrarImagenFolder($pathbase.'s/',$auxfoto[0]->SRC);
								}

							}
					
							
							if ($auxfotoperfildestacada!="default.png") {
								$infoimagendestacada = $this->utilities->almacenarImagenBase64($pathbase, $IMG_D, true);
								if($infoimagendestacada->estado=='success'){
									$datadestacada->SRC=$infoimagendestacada->filenombre;
								}
							}
							

						}else{
							
							$datadestacada->SRC=$auxfotoperfildestacada;
						}
				//	die();
						//$pos=strpos($);
					}else{
						$pathbase = "assets/images/canales/contenido_galeria/";
						$infoimagendestacada = $this->utilities->almacenarImagenBase64($pathbase, $IMG_D, true);
						if($infoimagendestacada->estado=='success'){
							$datadestacada->SRC=$infoimagendestacada->filenombre;
							
						}
					}
				}else{

					if(strlen($IMG_D)>100){
						$pathbase = "assets/images/canales/contenido_galeria/";
						$infoimagendestacada = $this->utilities->almacenarImagenBase64($pathbase, $IMG_D, true);
						if($infoimagendestacada->estado=='success'){
							$datadestacada->SRC=$infoimagendestacada->filenombre;
						}
					}else{
						$aux=explode('/', $IMG_D);
						$datadestacada->SRC=null;
					}
				}
			}
		
		

			if($info->proceso==0){//no se insertan datos
				echo json_encode($info);
			}else{
				//datos validados y se hace insercion
				

				if(!isset($_POST['idcontenido'])){
					
						if($result=$this->Utilities_model->insertar('CANAL_CONTENIDO',$data)){//almacenar canal

							$dataact->ID = $result;

							echo json_encode($info);

					//insercion de CANAL_CONTENIDO_CAT
							
							$last_id = $this->db->insert_id();
							$dataContCat->ID_CONTENIDO = $last_id;

							$ID_CATEGORIA_AUX = $dataContCat->ID_CATEGORIA;

						
							for ($i=0; $i < count($ID_CATEGORIA_AUX) ; $i++) { 
								
								$dataContCat->ID_CATEGORIA = $ID_CATEGORIA_AUX[$i];
								if($result3=$this->Utilities_model->insertar('CANAL_CONTENIDO_CAT',$dataContCat)){ 

								}
							}
	
						}
				
				
						
						$datadestacada->ID_CONTENIDO=$result;
						$this->Utilities_model->insertar('CANAL_CONTENIDO_GALERIA',$datadestacada);
				
					
				}else{//editar contenido

					
						
						if($datadestacada->SRC==$imgdefault){
							$datadestacada->SRC=NULL;
						}
					


						if($result=$this->Canales_model->getContenidoGaleria(" CCG.ID_CONTENIDO = ? AND CCG.TIPO = 1",array($dataact->ID))){
			
							$result = $result -> row();
							
							$set=array("SRC" => $datadestacada->SRC );
							if(!empty($datadestacada->SRC)){

								$set = array_merge($set, ["SRC" => $datadestacada->SRC, "FECHAMODIFICACION" => $fecha]);
								
							}
							

							if($this->Utilities_model->actualizar("CANAL_CONTENIDO_GALERIA", "ID_GALERIA", $set, $result->ID_GALERIA)){
					
							}else{
								$info->proceso=0;
								array_push($info->errores, "El Contenido no ha podido ser editado");

							}
							
						}  
					
					
						if($result=$this->Canales_model->getDataContenido(" C.ID_CONTENIDO=?", array($dataact->ID))){
		
							
								
									//descomentar al finalizar.

									$set=array("DESCRIPCION" => $data->DESCRIPCION, "NOMBRE" => $data->NOMBRE, "ESCENA" => $data->ESCENA, "CLAVE" => $data->CLAVE,"VISIBILIDAD" => $data->VISIBILIDAD);
									$setCat=array("ID_CATEGORIA" => $dataContCat->ID_CATEGORIA);

									
									//die (print_r($setCat));
									//  $final="";	
									// foreach ($setCat['ID_CATEGORIA'] as &$valor) {
									// 	$final = $final.$valor;
									// }
									// die ($final);

									
									if($this->Utilities_model->actualizar("CANAL_CONTENIDO", "ID_CONTENIDO", $set, $dataact->ID)){
										
									}else{
										$info->proceso=0;
										array_push($info->errores, "El Contenido no ha podido ser editado");
									}
	
									if($result=$this->Canales_model->deleteContenidoCat($dataact->ID)){   
										//elimina contenido_cat anterior 
									}else {
										array_push($info->errores, "El Contenido_Categoria no ha podido ser eliminado");
									}; 

									//inserta contenido_categoria por cada categoria
									foreach ($setCat['ID_CATEGORIA'] as &$valor) {
										$data = array(
											'ID_CONTENIDO' => $dataact->ID,
											'ID_CATEGORIA' => $valor,
											'FECHACREACION' => $dataContCat->FECHACREACION
											);

										if($result=$this->Utilities_model->insertar('CANAL_CONTENIDO_CAT',$data)){ 
											//die("data:".$this->db->last_query());	
										}
										
									}


									
								
									/* $set=array("DESCRIPCION" => $data->DESCRIPCION, "NOMBRE" => $data->NOMBRE, "ESCENA" => $data->ESCENA, "CLAVE" => $data->CLAVE,"VISIBILIDAD" => $data->VISIBILIDAD); */
								
									
									if($this->Utilities_model->actualizar("CANAL_CONTENIDO", "ID_CONTENIDO", $set, $dataact->ID)){

									}else{
										$info->proceso=0;
										array_push($info->errores, "El Contenido no ha podido ser editado");
									}
										
						}

						
						foreach($dataorden->ADJUNTOS_ELIMINAR as $adjunto_eliminar){

							

							if($foto=$this->Canales_model->getImageContenidoGaleria($adjunto_eliminar)){
						
								if($foto->row()->SRC!=NULL){

									$pathbasegaleria = "assets/images/canales/contenido_galeria/";
									$this->utilities->borrarImagenFolder($pathbasegaleria,$foto->row()->SRC);
									$this->utilities->borrarImagenFolder($pathbasegaleria.'s/',$foto->row()->SRC);
	
								}

								$this->Utilities_model->actualizar("CANAL_CONTENIDO_GALERIA", "ID_GALERIA", array("FECHABAJA" => $fecha, "ESTADO" => 0), $adjunto_eliminar);
								
							}

						}
						

					echo json_encode($info);
				}

					

					/*Dropzone sección*/

					$fecha = date('Y-m-d H:i:s');
				
				
				
										
					// Crear objeto con información necesaria
					$dataarchivo = new stdClass;
					$dataarchivo->id = null;
					
					$dataarchivo->archivo = null;

					
				
					


					if (!empty($this->input->post('archivo'))) {
						$dataarchivo->archivo = $this->input->post('archivo');
						$dataarchivo->archivo = json_decode($dataarchivo->archivo);
					}
					
					/* galeria */
					if(sizeof($info->errores) == 0){
												$result = false;
 
												

												if (is_numeric($dataact->ID)) {
				
													$array_id_imagenes = array();
								
													if ($query_img = $this->Canales_model->getContenidoGaleria("CCG.ID_CONTENIDO = ? AND CCG.TIPO = 2", array($dataact->ID), "ORDER BY CCG.ORDEN DESC")) {
														foreach ($query_img->result() as $q) {
																$array_id_imagenes[] = $q->ID_GALERIA;
														}
									
														
													}

													/*  print("\n----- datos gestion\n");
																print_r($array_id_imagenes);
																print(" \n-----\n");
																die; 		 */
												
												
													//galeria 
													if ($dataarchivo->archivo) {
														
														$pathbasegaleria = "assets/images/canales/contenido_galeria/";
														foreach ($dataarchivo->archivo as $imagen) {
															$infoimagen = $this->utilities->almacenarImagenBase64($pathbasegaleria, $imagen->image, false);
															if ($infoimagen->estado == "success") {
																$datos_gestion = array(
																	'ID_CONTENIDO' => $dataact->ID, //id del contenido
																	'SRC' => $infoimagen->filenombre,
																	'NOMBRE' => $imagen->name,
																	'PESO' => $imagen->size,
																	'TIPO' => 2,
																	'ORDEN' => $imagen->orden
																
																);
																
															
																if (isset($imagen->id)) {
				
																	$array_id_imagenes = array_diff($array_id_imagenes, array($imagen->id));
																	$datos_gestion = array_merge($datos_gestion, array("FECHAMODIFICACION" => $fecha));

																	if ($_query = $this->Canales_model->getContenidoGaleria("CCG.ID_GALERIA = ? AND CCG.TIPO = 2 ", array($imagen->id))) {
																	
																		$_query = $_query->row();
																		if ($_query->SRC) {
																			
																			$pathgaleria = "assets/images/canales/contenido_galeria/";
																			$file = $_query->SRC;
																			
																			$this->utilities->borrarImagenFolder($pathgaleria, $file);
																		}
																	}

																	$this->Utilities_model->actualizar("CANAL_CONTENIDO_GALERIA", "ID_GALERIA", $datos_gestion, $imagen->id);
																} else {

																	$datos_gestion = array_merge($datos_gestion, array("FECHACREACION" => $fecha, "ESTADO" => 1));
																	if($galeria_id = $this->Utilities_model->insertar("CANAL_CONTENIDO_GALERIA", $datos_gestion)){
																		$posicion = array_search($imagen->uuid,$dataorden->listaorden);
																		if(is_numeric($posicion)){
																			$dataorden->listaorden[$posicion] = $galeria_id;

																			
																		}
																		
																	}
																	
																}
															}	
														}

														

														
														
													}

													foreach($dataorden->listaorden as $key => $galeria_orden){
														$ar_orden = array("ORDEN" => ($key +1), "FECHAMODIFICACION" => $fecha);
														$this->Utilities_model->actualizar("CANAL_CONTENIDO_GALERIA", "ID_GALERIA", $ar_orden, $galeria_orden);
													}
													
												 	 /* print("\n----- array imagenes\n");
													print_r($array_id_imagenes);
													print(" \n-----\n");
													die;   */
													
								
													/*if (count($array_id_imagenes) > 0) {
														foreach ($array_id_imagenes as $id_img) {
															if ($_query = $this->Canales_model->getContenidoGaleria("CCG.ID_GALERIA = ? AND CCG.TIPO = 2", array($id_img))) {
																$_query = $_query->row();
																if ($_query->SRC) {
																	$pathgaleria = "assets/images/canales/contenido_galeria/";
																	$file = $_query->SRC;
																	$this->utilities->borrarImagenFolder($pathgaleria, $file);
																}
																$this->Utilities_model->actualizar("CANAL_CONTENIDO_GALERIA", "ID_GALERIA", array("FECHABAJA" => $fecha, "ESTADO" => 0), $id_img);
															}
														}
													}*/
				
												}
					}
											
					$result = false;
					if (is_numeric($dataact->ID) && sizeof($info->errores) == 0){
						$result = true;
					}	


			}
	}
	






		public function getCanal(){
			if (is_null($this->utilities->check_permisos()->datos_user )) {
				exit;
				}
			//if ($this->utilities->check_permisos()->tipo == 1) {
				$pathfotoperfil="assets/images/canales/";
				$info = new stdClass();
				$info->proceso = 1; // 0: Hubo problemas, 1: Eliminación realizada
				$info->errores = [];
				$info->data=[];
				$id=$this->security->xss_clean($this->input->post('id'));
				$id=(int)$id;
				if(is_int($id)){
					if($result=$this->Canales_model->getDataCanal(" ID_CANAL=?", array($id))){
						foreach ($result->result() as $r) {

							if($r->LOGO != "default.png" && !file_exists(FCPATH . $pathfotoperfil .$r->LOGO)  && 
							!file_exists(FCPATH . $pathfotoperfil.'/s/' .$r->LOGO)){

							$r->LOGO="default.png";

							}
							
							$r->LOGO=base_url().$pathfotoperfil.$r->LOGO;
							array_push($info->data, $r);
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



		
		public function getContenido(){
			if (is_null($this->utilities->check_permisos()->datos_user )) {
				exit;
				}
			//if ($this->utilities->check_permisos()->tipo == 1) {
				$info = new stdClass();
				$info->proceso = 1; // 0: Hubo problemas, 1: Eliminación realizada
				$info->errores = [];
				$info->data=[];
				$info->galeria=[];
				$info->imagendestacada=[];
				$pathimagendestacada = "assets/images/canales/contenido_galeria/";

				$id=$this->security->xss_clean($this->input->post('id'));
				$id=(int)$id;
				if(is_int($id)){
					if($result=$this->Canales_model->getDataContenido(" C.ID_CONTENIDO=?", array($id))){
						
						foreach ($result->result() as $r) {
		
						
								array_push($info->data, $r);
													
						}
						
					}
					
					//galería
					if($result=$this->Canales_model->getContenidoGaleria(" CCG.ID_CONTENIDO = ? AND CCG.TIPO = 2", array($id))){
						foreach ($result->result() as $r){

							$galeria = new stdClass();
							$galeria->id = $r->ID_GALERIA;
							$galeria->name = $r->NOMBRE;
							$galeria->src = $r->SRC;
							$galeria->orden = $r->ORDEN;
							$galeria->peso = $r->PESO;
							$galeria->tipo = $r->TIPO;
							$galeria->url = base_url() . "assets/images/canales/contenido_galeria/" . $r->SRC;

							array_push($info->galeria, $galeria);

							
						}
					}
					

					//imagen destacada
					if($result=$this->Canales_model->getContenidoGaleria(" CCG.ID_CONTENIDO = ? AND CCG.TIPO = 1", array($id))){
						foreach ($result->result() as $r){

							

							$imagendestacada = new stdClass();
							$imagendestacada->id = $r->ID_GALERIA;
							$imagendestacada->name = $r->NOMBRE;
							$imagendestacada->src = $r->SRC;
						//	$imagendestacada->orden = $r->ORDEN;
							$imagendestacada->tipo = $r->TIPO;
							$imagendestacada->peso = $r->PESO;
							$imagendestacada->url = base_url() . "assets/images/canales/contenido_galeria/" . $r->SRC;
							if($r->SRC != "default.png" && $r->SRC == NULL && !file_exists(FCPATH . $pathimagendestacada .$r->SRC)  && 
																			  !file_exists(FCPATH . $pathimagendestacada.'/s/' .$r->SRC)){

							$r->SRC="default.png";

							}
							

							$r->SRC=base_url().$pathimagendestacada.$r->SRC;


							array_push($info->imagendestacada, $imagendestacada);
							

							
						}
					}


					else{
						array_push($info->errores, 'El canal no existe');
					}
				}else{
					$info->proceso=0;
					array_push($info->errores, "Ocurrio un error en el proceso, intente nuevamente");
				}
				echo json_encode($info);
			//}
		}
		

	

		public function getCategoria(){
			if (is_null($this->utilities->check_permisos()->datos_user )) {
				exit;
			}


				if($result=$this->Canales_model->getDataCategoria()){
					echo json_encode($result);
				}
				
		}

		


		public function getContenidoCat(){
			if (is_null($this->utilities->check_permisos()->datos_user )) {
				exit;
			}

				$info = new stdClass();

				$id=$this->security->xss_clean($this->input->post('id'));
				//die("ID :".$id);
				if($result=$this->Canales_model->getDataContenidoCat( " C.ID_CONTENIDO=?", array($id))  ){
					echo json_encode($result);
				
				}
			
				
		}

		
	
		public function eliminarCanal(){
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
					if($this->Utilities_model->actualizar("CANAL", "ID_CANAL", array("ESTADO" => 0, "FECHABAJA" => $fecha), $id)){
						//eliminar la imagen acaaa
						
						

						if($foto=$this->Canales_model->getImage($id)){
							if($foto->result()[0]->LOGO!='default.png' && $foto->result()[0]->LOGO!=NULL){
								$pathbase = "assets/images/canales/";
								$this->utilities->borrarImagenFolder($pathbase,$foto->result()[0]->LOGO);
								$this->utilities->borrarImagenFolder($pathbase.'s/',$foto->result()[0]->LOGO);
							}
							
						}
						//$query=$query->result();
						
					}else{
						$info->proceso=0;
						array_push($info->errores, "El canal no ha podido ser eliminado");
					}
				}else{
					$info->proceso=0;
					array_push($info->errores, "Ocurrio un error en el proceso, intente nuevamente");
				}
				echo json_encode($info);
			//}
		}


		public function eliminarImagenContenido(){
			if (is_null($this->utilities->check_permisos()->datos_user)){
				exit;
			}

			$info = new stdClass();
			$info->proceso = 1; // 0: Hubo problemas, 1: Eliminación realizada
			$info->errores = [];
			$idgaleria = null; 
			$fecha = date ('Y-m-d H:i:s');
			$id = $this->security->xss_clean($this->input->post('id'));
			$id = (int)$id;
			if(is_int($id)){
				if($this->Utilities_model->actualizar("CANAL_CONTENIDO_GALERIA","ID_GALERIA", array("ESTADO" => 0, "FECHABAJA" => $fecha), $id)){
					if($foto=$this->Canales_model->getImageContenidoGaleria($id)){

						if($foto->result()[0]->SRC!=NULL){
							$pathbasegaleria = "assets/images/canales/contenido_galeria/";
							$this->utilities->borrarImagenFolder($pathbasegaleria,$foto->result()[0]->SRC);
							$this->utilities->borrarImagenFolder($pathbasegaleria.'s/',$foto->result()[0]->SRC);


						}
						
					}
				
				}else{
					$info->proceso=0;
					array_push($info->errores, "La imagen no ha podido ser eliminada");
				}
			}else{
					$info->proceso=0;
					array_push($info->errores, "Ocurrio un error en el proceso, intente nuevamente");
				}
				echo json_encode($info);

		}


			
		public function eliminarContenido(){
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
					if($this->Utilities_model->actualizar("CANAL_CONTENIDO", "ID_CONTENIDO", array("ESTADO" => 0, "FECHABAJA" => $fecha), $id)){

						$this->Utilities_model->actualizar("CANAL_CONTENIDO_GALERIA", "ID_CONTENIDO", array("ESTADO" => 0, "FECHABAJA" => $fecha), $id);

							
							//elimina foto destacada
							if($foto=$this->Canales_model->getImageContenido($id)){

							
								foreach ($foto->result() as $f){
									if($f->SRC!='default.png' && $f->SRC!=NULL){
										$pathbase = "assets/images/canales/contenido_galeria/";
										$this->utilities->borrarImagenFolder($pathbase,$f->SRC);
										//$this->utilities->borrarImagenFolder($pathbase.'s/',$foto->result()[0]->SRC);
									}

								}
								/* if($foto->result()[0]->SRC!='default.png' && $foto->result()[0]->SRC!=NULL){
									$pathbase = "assets/images/canales/contenido_galeria/";
									$this->utilities->borrarImagenFolder($pathbase,$foto->result()[0]->SRC);
									$this->utilities->borrarImagenFolder($pathbase.'s/',$foto->result()[0]->SRC);
	
	
								}
								 */
							}
						

				

						//$query=$query->result();
						
					}else{
						$info->proceso=0;
						array_push($info->errores, "El contenido no ha podido ser eliminado");
					}
				}else{
					$info->proceso=0;
					array_push($info->errores, "Ocurrio un error en el proceso, intente nuevamente");
				}
				echo json_encode($info);
			//}
	}
	
		
		public function getDataCanales(){

			$max_description_length=100;

			if (is_null($this->utilities->check_permisos()->datos_user )) {
				exit;
				}
			//if ($this->utilities->check_permisos()->tipo == 1) {
				
				$exportar=$this->security->xss_clean($this->input->post('exportar'));//revisar exportar
				$pathfotoperfil="assets/images/canales/";
				$orderby='';
				$limit='';
				$where=1;
				$draw=1;
				$clauses=[];
				$columnas=array("LOGO","NOMBRE", "DESCRIPCION_CORTA", "DESCRIPCION_LARGA");


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
						$where .= " C.NOMBRE LIKE ?";
						$where .= " OR C.DESCRIPCION_CORTA LIKE ?";
						$where .= " OR C.DESCRIPCION_LARGA LIKE ?";
						$where .= " )";
						
	
					array_push($clauses,"%".$value."%","%".$value."%","%".$value."%");
								  
	
					}
	
				
					
				}


				if($result=$this->Canales_model->getDataCanal($where, $clauses, $orderby)){
					$tamanio=$result->num_rows();
				}
				if($result=$this->Canales_model->getDataCanal($where, $clauses, $orderby ,$limit )){
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
								 $r->NOMBRE, $r->DESCRIPCION_CORTA, $r->DESCRIPCION_LARGA
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
								
							
									
							$row->id=$r->ID_CANAL;
							$row->descripcion_corta=$r->DESCRIPCION_CORTA;
							$row->nombre= $r->NOMBRE;
							// $row->descripcion_larga=$r->DESCRIPCION_LARGA;
							

							if (strlen($r->DESCRIPCION_LARGA)>$max_description_length) {
								$row->descripcion_larga=substr($r->DESCRIPCION_LARGA,0,$max_description_length-3)."...";
							}else{
								$row->descripcion_larga=$r->DESCRIPCION_LARGA;	
							}
							

							
							$row->opciones='<div class="dropdown">
	                                         <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-cog"></i></button>
	                                         <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 33px, 0px); top: 0px; left: 0px; will-change: transform;">
	                                                <input type="hidden" name="id" value="'.$r->ID_CANAL.'">
	                                                <input type="hidden" name="'.$this->security->get_csrf_token_name().'" value="'.$this->security->get_csrf_hash().'">
	                                            <button type="submit" class="dropdown-item ancho-100 editar_canal" data-canal="'.$r->ID_CANAL.'"  style="cursor:pointer;width:93%;"><i class="fa fa-pencil"></i> Editar</button>
	                                            <button type="submit" class="dropdown-item ancho-100 borrar_canal" data-canal="'.$r->ID_CANAL	.'" style="cursor:pointer;width:93%;" ><i class="fa fa-trash-o"></i> Eliminar</button>';
						
							$row->contenido='
						<div class="text-center">
							<button  data-nombre="'.$r->NOMBRE.'" data-canal="'.$r->ID_CANAL.'" class="btn btn-secondary mostrar_contenido" type="submit"  > <i class="fa fa-eye"></i></button>
							<input type="hidden" name="id" value="'.$r->ID_CANAL.'">
							<input type="hidden" name="'.$this->security->get_csrf_token_name().'" value="'.$this->security->get_csrf_hash().'">
						</div>';
	                    
								
						}
						 array_push($response->data, $row);
					}
					$recordsFiltered = $tamanio;
					$response->estado=1;
					echo json_encode(array('data' => $response->data, "recordsTotal" => $tamanio, "columns"=>["NOMBRE","DESCRIPCIÓN CORTA", "DESCRIPCIÓN LARGA"], "recordsFiltered" => $recordsFiltered, 'draw' => $draw));
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
					echo json_encode(array('data' => $response->data, "recordsTotal" => 0, "columns"=>["NOMBRE","DESCRIPCIÓN CORTA", "DESCRIPCIÓN LARGA"], "recordsFiltered" => $recordsFiltered, 'draw' => $draw));
				}
			//}
		}
		




		public function getDataContenidos(){


			
			$arrContextOptions=array(
				"ssl"=>array(
					"verify_peer"=>false,
					"verify_peer_name"=>false,
				),
			); 


			$max_description_length=100;

			$canal_actual = isset($_POST['canalActual']) ? $_POST['canalActual'] : '';
			if (is_null($this->utilities->check_permisos()->datos_user )) {
				exit;
				}
			//if ($this->utilities->check_permisos()->tipo == 1) {
				$pathimagendestacada = "assets/images/canales/contenido_galeria/";
				$exportar=$this->security->xss_clean($this->input->post('exportar'));//revisar exportar
				$pathfotoperfil="assets/images/canales/";
				$orderby='';
				$limit='';
				$where=1;
				$draw=1;
				$clauses=[];
				$columnas=array("NOMBRE","DESCRIPCION", "ESCENA", "CLAVE","VISIBILIDAD");
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
						$where .= " C.NOMBRE LIKE ?";
						$where .= " OR C.DESCRIPCION LIKE ?";
						$where .= " OR C.ESCENA LIKE ?";
						$where .= " )";			
						
					array_push($clauses,"%".$value."%","%".$value."%","%".$value."%");
								  
	
					}
	
				
					
				}

				

				$where .= " AND (  C.ID_CANAL=? )";
				array_push($clauses,$canal_actual);

				if($result=$this->Canales_model->getDataContenido($where, $clauses, $orderby)){
					$tamanio=$result->num_rows();
				}
				if($result=$this->Canales_model->getDataContenido($where, $clauses, $orderby ,$limit )){
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

							if (empty($r->CLAVE)) {
								$BOOLCLAVE='Sin clave acceso';
							} else {
								$BOOLCLAVE='Con clave acceso';
							}

							if ($r->VISIBILIDAD==1) {
								$BOOLVISIBILIDAD = 'Visible'; 
							} else {
								$BOOLVISIBILIDAD = 'No visible'; 
							}
							
							
							$row =array(
								 $r->NOMBRE, $r->DESCRIPCION, $r->ESCENA, $BOOLCLAVE, $BOOLVISIBILIDAD
								);
							
						}else{
							$row = null;
							$row = new stdClass();
							
							//header("Location: http://example.com/myOtherPage.php");
				
								
							
									
							$row->id=$r->ID_CONTENIDO;
							// $row->descripcion=$r->DESCRIPCION;
							$row->nombre= $r->NOMBRE;
							$row->escena= $r->ESCENA;

							if (empty($r->CLAVE)) {
								$row->clave='Sin clave acceso';
							} else {
								$row->clave='Con clave acceso';
							}

							if ($r->VISIBILIDAD==1) {
								$row->visibilidad= 'Visible';
							} else {
								$row->visibilidad= 'No visible';
							}

							
							
							
							if (strlen($r->DESCRIPCION)>$max_description_length) {
								$row->descripcion=substr($r->DESCRIPCION,0,$max_description_length-3)."...";
							}else{
								$row->descripcion=$r->DESCRIPCION;	
							}
							


							
							// $row->descripcion_larga=$r->DESCRIPCION_LARGA;
							

							/* Galería */
							$imagenes = array();

							if ($query_img = $this->Canales_model->getContenidoGaleria("CCG.ID_CONTENIDO = ? AND CCG.TIPO = 2", array($row->id), "ORDER BY CCG.ORDEN ASC")) {
							
								foreach ($query_img->result() as $img) {
									$imgs = new stdClass();
									if ($img->SRC && file_exists(FCPATH . "assets/images/canales/contenido_galeria/" . $img->SRC)) {
										$imgs->url = base_url() . "assets/images/canales/contenido_galeria/" . $img->SRC;
										$imgs->url_base_64 = 'data:image/png;base64,' . base64_encode(file_get_contents($imgs->url, false, stream_context_create($arrContextOptions)));
										$imgs->src = $img->SRC;
										$imgs->id = $img->ID_GALERIA;
										$imgs->name = $img->NOMBRE;
										$imgs->size = $img->PESO;
										$imgs->orden = $img->ORDEN;
										$imgs->tipo = $img->TIPO;
							
										$imagenes[] = $imgs;
									}
								}
							}
					
							
							$row->opciones='<div class="dropdown">
	                                         <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-cog"></i></button>
	                                         <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 33px, 0px); top: 0px; left: 0px; will-change: transform;">
	                                                <input type="hidden" name="id" value="'.$r->ID_CONTENIDO.'">
	                                                <input type="hidden" name="'.$this->security->get_csrf_token_name().'" value="'.$this->security->get_csrf_hash().'">
	                                            <button type="submit" class="dropdown-item ancho-100 editar_contenido" data-canal="'.$r->ID_CONTENIDO.'"  style="cursor:pointer;width:93%;"><i class="fa fa-pencil"></i> Editar</button>
	                                            <button type="submit" class="dropdown-item ancho-100 borrar_contenido" data-canal="'.$r->ID_CONTENIDO	.'" style="cursor:pointer;width:93%;" ><i class="fa fa-trash-o"></i> Eliminar</button>';
						
	                    
								
							}
						 array_push($response->data, $row);
					}
					$recordsFiltered = $tamanio;
					$response->estado=1;
					echo json_encode(array('data' => $response->data, "recordsTotal" => $tamanio, "columns"=>["NOMBRE","DESCRIPCIÓN", "ESCENA", "CLAVE", "VISIBILIDAD"], "recordsFiltered" => $recordsFiltered, 'draw' => $draw));
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
					echo json_encode(array('data' => $response->data, "recordsTotal" => 0, "columns"=>["NOMBRE","DESCRIPCIÓN", "ESCENA", "CLAVE", "VISIBILIDAD"], "recordsFiltered" => $recordsFiltered, 'draw' => $draw));
				}
			//}
		}
		


		


	
	}
	
	
	
	
	
	
	////////////////////////////////////////////////////////////////AQUI