<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Publicidad extends MY_Controller{

    function __construct(){
        parent::__construct();
        $this->load->model('publicidad/Publicidad_model');
        $this->load->model('Utilities_model');
        $this->load->library('Utilities');
        date_default_timezone_set('America/Santiago');
    }

    public function index(){
        if(!is_null($this->utilities->check_permisos()->datos_user)){
            $data['datalibrary'] = array('titulo' => "Publicidad", 'vista' => array('index','modals'), "libcss" => array('libcss'), "libjs" => array('libjs'),'active' => 'ver_publicidad');
			$this->load->view('estructura/body', $data);
		}else{
			redirect('');
		}
	}
	


    ///////////////INICIO PUBLICIDAD
    
    public function guardarPublicidad(){

        if (is_null($this->utilities->check_permisos()->datos_user )) {
			exit;
            }
			
			/* foreach (MONEDA as $moneda){
				print_r($moneda[0]);
			}

			die; */
            $imgdefault='default.png';
		    $info = new stdClass();
            $info->proceso = 1; // 0:no se pudo procesar, 1: procesorealizado correctamente
            $info->errores = []; // Errores encontrados en el proceso
            $info->id = null; //Id del usuario creado/editado
            $info->data = null; // Errores encontrados en el proceso

            $data = new stdClass;
            $data->SRC="";
            $data->TITULO=$this->security->xss_clean(trim($this->input->post('titulo')));
            $data->CUERPO=$this->security->xss_clean(trim($this->input->post('cuerpo')));
           // $data->MONEDA=$this->security->xss_clean(trim($this->input->post('moneda')));
           // $data->MONTO=$this->security->xss_clean(trim($this->input->post('monto')));
        	$data->FECHA_INICIO= null;
            $data->FECHA_FIN=null;
            $data->ID_USUARIO=(int)$this->security->xss_clean(trim($this->input->post('idusuario')));
            //$data->ID_CANAL=(int)$this->security->xss_clean(trim($this->input->post('idcanal')));
           // $data->ID_CONTENIDO=(int)$this->security->xss_clean(trim($this->input->post('idcontenido')));
            $data->ID_TIPO=(int)$this->security->xss_clean(trim($this->input->post('pub_actual')));
			$FOTO=$this->security->xss_clean(trim($this->input->post('foto')));
            $data->ESTADO = 1;
            $data->FECHACREACION=date("Y-m-d H:i:s");
		//	$data->FECHABAJA=date("Y-m-d H:i:s");

			$data->ID_CANAL = null;
			$data->ID_CONTENIDO = null;
			$data->MONEDA = null;
			$data->MONTO = null;

			if(is_numeric($this->input->post('idcanal'))){
				
				$data->ID_CANAL = $this->security->xss_clean($this->input->post('idcanal', TRUE));
			}else{
				$info->errores [] = "Error al obtener información de canal.";
			}

			
			if(is_numeric($this->input->post('idcontenido'))){
				
				$data->ID_CONTENIDO = $this->security->xss_clean($this->input->post('idcontenido', TRUE));
			}else{
				$info->errores [] = "Error al obtener información de contenido.";
			}

			if(is_numeric($this->input->post('moneda'))){
				
				$data->MONEDA = $this->security->xss_clean($this->input->post('moneda', TRUE));
			}else{
				$info->errores [] = "Error al obtener información de MONEDA.";
			}


			if (!empty($this->input->post('monto'))) {		
				$data->MONTO = $this->security->xss_clean($this->input->post('monto', TRUE));
			} else {

				$info->errores[] = "El monto no pudo ser procesado.";
			}

            $dataact = new stdClass;
            if(!empty($this->input->post('idpublicidad'))){
                $dataact->ID=$this->security->xss_clean((int)trim($this->input->post('idpublicidad')));
            }else{
                $dataact->ID = -1;
            }
			
			$dataact->FECHAMODIFICACION=date("Y-m-d H:i:s");
			$dataact->FECHABAJA=date("Y-m-d H:i:s");

			/* print_r($data->MONEDA);
			die; */

			/* print_r(strlen($data->MONTO));
			die; */
			switch($data->MONEDA){
				case '1':
							if(strlen($data->MONTO) > 15){
								$info->errores[] = "El largo del campo Monto perteneciente al Peso Chileno, excede lo permitido (15).";
							}
							break;
				case '2': 
							if(strlen($data->MONTO) > 12){
								$info->errores[] = "El largo del campo Monto perteneciente al Dolar, excede lo permitido (12).";
							}
							break;
			}

			//validaciones de datos recibidos
			/* Fecha de Inicio */
			if (!empty($this->input->post('fechainicio'))) {
				$data->FECHA_INICIO = $this->security->xss_clean($this->input->post('fechainicio', TRUE));
				$data->FECHA_INICIO = trim($data->FECHA_INICIO);			
				$data->FECHA_INICIO = date_create_from_format("Y-m-d",$data->FECHA_INICIO);		
				$data->FECHA_INICIO = date_format($data->FECHA_INICIO,"Y-m-d");
				
			} else {
				$info->errores[] = "El campo 'Fecha' es obligatorio";
			}

			if (!empty($this->input->post('fechafin'))) {
				$data->FECHA_FIN = $this->security->xss_clean($this->input->post('fechafin', TRUE));
				$data->FECHA_FIN = trim($data->FECHA_FIN);			
				$data->FECHA_FIN = date_create_from_format("Y-m-d",$data->FECHA_FIN);		
				$data->FECHA_FIN = date_format($data->FECHA_FIN,"Y-m-d");
				
			} else {
				$info->errores[] = "El campo 'Fecha' es obligatorio";
			}


			if(empty($data->TITULO)){
				array_push($info->errores, "El campo 'titulo' es obligatorio");
				$info->proceso=0;
            }
            
			if(empty($data->CUERPO)){
				array_push($info->errores, "El campo 'cuerpo' es obligatorio");
				$info->proceso=0;
            }
            
            /* if(empty($data->MONEDA)){
				array_push($info->errores, "El campo 'moneda' es obligatorio");
				$info->proceso=0;
			}
 */
           /*  if(empty($data->MONTO)){
				array_push($info->errores, "El campo 'monto' es obligatorio");
				$info->proceso=0;
			} */


			if(!empty($FOTO)){
				//validar imagen en caso de que se haya ingresado
				//validar si es b64
				$pathbase = "assets/images/publicidad/";
				$auxfotoperfil=explode('/', $FOTO);
				$auxfotoperfil=$auxfotoperfil[count($auxfotoperfil)-1];
				
				if($dataact->ID!=-1){
					if($result=$this->Publicidad_model->getDataPublicidad(" ID_PUBLICIDAD = ?", array($dataact->ID))){
						$auxfoto=$result->result();		
				
						
						if($auxfoto[0]->FOTO!=$auxfotoperfil){
			
							
							$pathbase = "assets/images/publicidad/";
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
								$infoimagen = $this->utilities->almacenarImagenBase64($pathbase, $FOTO, true);
								if($infoimagen->estado=='success'){
									$data->SRC=$infoimagen->filenombre;
								}
							}
								


						}else{
							
							$data->SRC=$auxfotoperfil;
						}
				
					}else{
						$pathbase = "assets/images/publicidad/";
						$infoimagen = $this->utilities->almacenarImagenBase64($pathbase, $FOTO, true);
						if($infoimagen->estado=='success'){
							$data->SRC=$infoimagen->filenombre;
						}
					}
				}else{

					if(strlen($FOTO)>100){
						$pathbase = "assets/images/publicidad/";
		                $infoimagen = $this->utilities->almacenarImagenBase64($pathbase, $FOTO, true);
		                if($infoimagen->estado=='success'){
		                	$data->SRC=$infoimagen->filenombre;
		                }
					}else{
						$aux=explode('/', $FOTO);
						$data->SRC=null;
					}
				}

				

			}



			if($info->proceso==0){//no se insertan datos

				
				echo json_encode($info);
			}else{
				//datos validados y se hace insercion
				if(!isset($_POST['idpublicidad'])){


						/* $num=$data->MONTO ;
						print_r($num);
						die; */

						if($result=$this->Utilities_model->insertar('PUBLICIDAD',$data)){//almacenar 
								
							
							echo json_encode($info);
						}
					
				}else{//editar PUBLICIDAD

					
					/* $num=$data->MONTO ;
						print_r($num);
						die; */


					if($data->SRC==$imgdefault){
						$data->SRC=NULL;
					}

					if($result=$this->Publicidad_model->getDataPublicidad(" P.ID_PUBLICIDAD =?", array($dataact->ID))){

									
									$set=array("TITULO" => $data->TITULO,"CUERPO" => $data->CUERPO, "MONEDA" => $data->MONEDA,"MONTO" => $data->MONTO, "ID_USUARIO" => $data->ID_USUARIO, "ID_CANAL" => $data->ID_CANAL, "ID_CONTENIDO" => $data->ID_CONTENIDO, "ID_TIPO" => $data->ID_TIPO, "FECHA_INICIO" => $data->FECHA_INICIO, "FECHA_FIN" => $data->FECHA_FIN);
									

									if(!empty($data->SRC)){
										//$set.=", FOTOPERFIL='".$data->FOTOPERFIL."' ";
										$set = array_merge($set, ["SRC" => $data->SRC]);
									}
									
									if($this->Utilities_model->actualizar("PUBLICIDAD", "ID_PUBLICIDAD", $set, $dataact->ID)){
										
									}else{
										$info->proceso=0;
										array_push($info->errores, "La publicidad no ha podido ser editada");
									}
					}
					
					
					echo json_encode($info);

				}
			}
		//}
    }

	public function getPublicidad(){
		if (is_null($this->utilities->check_permisos()->datos_user )) {
			exit;
			}			
			$info = new stdClass();
			$info->proceso = 1; // 0: Hubo problemas, 1: Eliminación realizada
			$info->errores = [];
			$info->data=[];
			$info->moneda=[];
			$pathfoto = "assets/images/publicidad/";
			$id=$this->security->xss_clean($this->input->post('id'));
			$id=(int)$id;
			$monedaflag = null;

			

			if(is_int($id)){
				if($result=$this->Publicidad_model->getDataPublicidad(" P.ID_PUBLICIDAD = ?", array($id))){
					foreach ($result->result() as $r) {

						$monedaflag = $r->MONEDA;	

						
						
						if($r->FOTO != "default.png" && !file_exists(FCPATH . $pathfoto .$r->FOTO)  && 
						!file_exists(FCPATH . $pathfoto.'/s/' .$r->FOTO)){

						$r->FOTO="default.png";

						}
						
						$r->FOTO=base_url().$pathfoto.$r->FOTO;
						$r->FECHA_INICIO = date('d/m/Y' , strtotime($r->FECHA_INICIO));
						$r->FECHA_FIN = date('d/m/Y' , strtotime($r->FECHA_FIN));
					
						array_push($info->data, $r);

						
						
						
					}
					
				}else{
					array_push($info->errores, 'La publicidad no existe');
				}
			}
			
			else{
				$info->proceso=0;
				array_push($info->errores, "Ocurrio un error en el proceso, intente nuevamente");
			}

			if(is_array(MONEDA)){
				foreach(MONEDA as $m){

					$moneda_data = new stdClass();
					$moneda_data->ID = $m['ID'];
					$moneda_data->TEXTO = $m['NOMBRE'];
					$moneda_data->ABREVIACION = $m['ABREVIACION'];
					$moneda_data->CHECK = ($m['ID'] == $monedaflag) ? true:false;
					array_push($info->moneda, $moneda_data);
					//var_dump($m);
				}
			}
			//die;
			

			echo json_encode($info);

	}

	public function getMoneda(){
		if (is_null($this->utilities->check_permisos()->datos_user )) {
			exit;
			}			
			$info = new stdClass();
			$info->proceso = 1; // 0: Hubo problemas, 1: Eliminación realizada
			$info->errores = [];
			$info->data=[];
			$info->moneda=[];
			$id=$this->security->xss_clean($this->input->post('id'));
			$id=(int)$id;
			$monedaflag = null;

			

			if(is_int($id)){
				if($result=$this->Publicidad_model->getDataPublicidad(" P.ID_PUBLICIDAD = ?", array($id))){
					foreach ($result->result() as $r) {
						$monedaflag = $r->MONEDA;	
						array_push($info->data, $r);		
					}
					
				}else{
					array_push($info->errores, 'No hubo error en el proceso.');
				}
			}
			
			else{
				$info->proceso=0;
				array_push($info->errores, "Ocurrio un error en el proceso, intente nuevamente");
			}

			if(is_array(MONEDA)){
				foreach(MONEDA as $m){

					$moneda_data = new stdClass();
					$moneda_data->ID = $m['ID'];
					$moneda_data->TEXTO = $m['NOMBRE'];
					$moneda_data->ABREVIACION = $m['ABREVIACION'];
					$moneda_data->CHECK = ($m['ID'] == $monedaflag) ? true:false;
					array_push($info->moneda, $moneda_data);
					//var_dump($m);
				}
			}
			//die;
			

			echo json_encode($info);

	}


    public function getUsuario(){
		if (is_null($this->utilities->check_permisos()->datos_user )) {
			exit;
			}
		if($result=$this->Publicidad_model->getUsuario()){
			echo json_encode($result);
		}
	}
	
    
    public function getContenido(){
		if (is_null($this->utilities->check_permisos()->datos_user )) {
			exit;
			}
		if($result=$this->Publicidad_model->getContenido()){
			echo json_encode($result);
		}
	}
	
	
    public function getCanal(){
		if (is_null($this->utilities->check_permisos()->datos_user )) {
			exit;
			}
		if($result=$this->Publicidad_model->getCanal()){
			echo json_encode($result);
		}
	}

	public function getPublicidadTipo(){
		if (is_null($this->utilities->check_permisos()->datos_user )) {
			exit;
			}
		if($result=$this->Publicidad_model->getPublicidadTipo()){
			echo json_encode($result);
		}
	}

	public function eliminarPublicidad(){
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
				if($this->Utilities_model->actualizar("PUBLICIDAD", "ID_PUBLICIDAD", array("ESTADO" => 0, "FECHABAJA" => $fecha), $id)){
					//Eliminar imagen

					if($foto=$this->Publicidad_model->getImage($id)){
						if($foto->result()[0]->FOTO!='default.png' && $foto->result()[0]->FOTO!=NULL){
							$pathbase = "assets/images/publicidad/";
							$this->utilities->borrarImagenFolder($pathbase,$foto->result()[0]->FOTO);
							$this->utilities->borrarImagenFolder($pathbase.'s/',$foto->result()[0]->FOTO);
						}
					}
				
					
				}else{
					$info->proceso=0;
					array_push($info->errores, "La publicidad no ha podido ser eliminada");
				}
			}else{
				$info->proceso=0;
				array_push($info->errores, "Ocurrio un error en el proceso, intente nuevamente");
			}
			echo json_encode($info);
		//}
	}

	public function getDataPublicidades(){

		$max_description_length=30;

		if (is_null($this->utilities->check_permisos()->datos_user )) {
			exit;
			}

			$signopeso = "$";
			$exportar=$this->security->xss_clean($this->input->post('exportar'));//revisar exportar
			$pathfotoperfil="assets/images/publicidad/";
			$orderby='';
			$limit='';
			$where=1;
			$draw=1;
			$clauses=[];
			$columnas=array("FOTO","TITULO","CUERPO","USUARIO_NOMBRE","TIPO_NOMBRE","MONEDA","MONTO", "CANAL_NOMBRE", "CONTENIDO_NOMBRE","FECHA_INICIO", "FECHA_FIN");


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
					$where .= " P.TITULO LIKE ?";
					$where .= " OR P.CUERPO LIKE ?";
					$where .= " OR CONCAT(IFNULL(U.NOMBRE,''),' ',IFNULL(U.APELLIDOP,''),' ',IFNULL(U.APELLIDOM,'')) LIKE ?";
					$where .= " OR PT.CAMPO LIKE ?";
					$where .= " OR P.MONEDA LIKE ?";
					$where .= " OR P.MONTO LIKE ?";
					$where .= " OR C.NOMBRE LIKE ?";
					$where .= " OR CC.NOMBRE LIKE ?";
					$where .= " OR P.FECHA_INICIO LIKE ?";
					$where .= " OR P.FECHA_FIN LIKE ?";
					$where .= " )";

				array_push($clauses,"%".$value."%","%".$value."%","%".$value."%","%".$value."%","%".$value."%","%".$value."%","%".$value."%","%".$value."%","%".$value."%","%".$value."%");
							  

				}

			
				
			}

			if($p = $_POST['pub_actual']){
				$where .= " AND ( PT.ID_TIPO = ?)";
			array_push($clauses,$p);
			}
			

			if($result=$this->Publicidad_model->getDataPublicidad($where, $clauses, $orderby)){
				$tamanio=$result->num_rows();
			}
			if($result=$this->Publicidad_model->getDataPublicidad($where, $clauses, $orderby ,$limit )){
				//$tamanio=$result->num_rows();
				if($tamanio==0){
					$row=[];
	
				}
				foreach ($result->result() as $r) {
					

					if($exportar){
						switch($r->MONEDA){
							case '0':
								$r->MONEDA = "";
								$r->MONTO = "";
								break;
							case '1': 
								$r->MONEDA = MONEDA[0]['ABREVIACION'];
								$r->MONTO = number_format($r->MONTO,0,".",",");
								break;
							case '2':
								$r->MONEDA = MONEDA[1]['ABREVIACION'];
								$r->MONTO = number_format($r->MONTO,2,".",",");
								break;
							default:
								$r->MONEDA = "";
								$r->MONTO = "";
								break;


						}
						
						$row =array(
							
							 $r->TITULO, $r->CUERPO,$r->USUARIO_NOMBRE,$r->TIPO_NOMBRE,$r->MONEDA, $r->MONTO,$r->CANAL_NOMBRE, $r->CONTENIDO_NOMBRE, date('d-m-Y', strtotime($r->FECHA_INICIO)), date('d-m-Y', strtotime($r->FECHA_FIN))
						);
						
						
					}else{
						$row = null;
						$row = new stdClass();

					
			
						if ($r->FOTO && file_exists(FCPATH . $pathfotoperfil.$r->FOTO) && file_exists(FCPATH . $pathfotoperfil.'s/'.$r->FOTO)  ) 
						{	
							
							
							
							if ($r->FOTO === "default.png") {
								
								$row->foto="<div class='media'>"
									. "<a><img class='avatar rounded-circle imagen-circle imagentabla' src='".base_url().$pathfotoperfil.'s/'.$r->FOTO."' alt='avatar'></a>"
									. "</div>";
								
							}else {

								
									$row->foto="<div class='media'>"
									." <a data-fancybox='images' href='".base_url().$pathfotoperfil.$r->FOTO."'>
											<img class='avatar rounded-circle imagen-circle imagentabla' src='".base_url().$pathfotoperfil.'s/'.$r->FOTO."' alt='avatar'>
										</a>"
									. "</div>";	
							}

						}else {
							$row->foto="<div class='media'>"
									. "<a><img class='avatar rounded-circle imagen-circle imagentabla' src='".base_url().$pathfotoperfil.'s/'.'default.png'."' alt='avatar'></a>"
									. "</div>";
						}
							
						
						$row->id=$r->ID;
						$row->titulo = $r->TITULO;
						$row->cuerpo = $r->CUERPO;
						$row->usuario = $r->USUARIO_NOMBRE;
						$row->tipo = $r->TIPO_NOMBRE;

						switch($r->MONEDA){
							case 0:
									$row->moneda = null;
									$row->monto = null;
									break;

							case 1:
									$row->moneda = MONEDA[0]['ABREVIACION'];
									$row->monto = $signopeso.number_format($r->MONTO,0,",",".");
									break;

							case 2:
									$row->moneda = MONEDA[1]['ABREVIACION'];
									$row->monto = $signopeso.number_format($r->MONTO,2,",",".");
									break;
							default:
									$row->moneda = null;
									$row->monto = $signopeso.number_format($r->MONTO,2,",",".");
						}


	
						$row->canal = $r->CANAL_NOMBRE;
						$row->contenido = $r->CONTENIDO_NOMBRE;
						$row->fechainicio = date('d-m-Y', strtotime($r->FECHA_INICIO));
						$row->fechafin = date('d-m-Y', strtotime($r->FECHA_FIN));


					/* 	print_r($row);
						die; */
						//$row->nombre= $r->NOMBRE;
						

						if (strlen($r->CUERPO)>$max_description_length) {
							$row->cuerpo=substr($r->CUERPO,0,$max_description_length-3)."...";
						}else{
							$row->cuerpo=$r->CUERPO;	
						}
						

						
						$row->opciones='<div class="dropdown">
										 <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-cog"></i></button>
										 <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 33px, 0px); top: 0px; left: 0px; will-change: transform;">
												<input type="hidden" name="id" value="'.$r->ID.'">
												<input type="hidden" name="'.$this->security->get_csrf_token_name().'" value="'.$this->security->get_csrf_hash().'">
											<button type="submit" class="dropdown-item ancho-100 editar_publicidad" data-publicidad="'.$r->ID.'"  style="cursor:pointer;width:93%;"><i class="fa fa-pencil"></i> Editar</button>
											<button type="submit" class="dropdown-item ancho-100 borrar_publicidad" data-publicidad="'.$r->ID	.'" style="cursor:pointer;width:93%;" ><i class="fa fa-trash-o"></i> Eliminar</button>';
					
											//BOTÓN VISUALIZAR
					
					
							
					}
					 array_push($response->data, $row);
				}
				$recordsFiltered = $tamanio;
				$response->estado=1;

				echo json_encode(array('data' => $response->data, "recordsTotal" => $tamanio, "columns"=>["TITULO","CUERPO","USUARIO","TIPO","MONEDA", "MONTO","CANAL", "CONTENIDO", "FECHA INICIO", "FECHA FIN"], "recordsFiltered" => $recordsFiltered, 'draw' => $draw));
			}else{
				$row=[];
				$recordsFiltered = 0;
				$response->estado=1;
				echo json_encode(array('data' => $response->data, "recordsTotal" => 0, "columns"=>["TITULO","CUERPO","USUARIO","TIPO","MONEDA", "MONTO","CANAL", "CONTENIDO", "FECHA INICIO", "FECHA FIN"],  "recordsFiltered" => $recordsFiltered, 'draw' => $draw));
			}
		//}
	}

    ///////////////FIN PUBLICIDAD
    
    /////////////// INICIO TIPO PUBLICIDAD
    
    public function tipo(){
		if(!is_null($this->utilities->check_permisos()->datos_user)){
            $data['datalibrary'] = array('titulo' => "Tipo Publicidad", 'vista' => array('tipo_index','tipo_modals'), "libcss" => array('tipo_libcss'), "libjs" => array('tipo_libjs'),'active' => 'tipo_publicidad');
			$this->load->view('estructura/body', $data);
		}else{
			redirect('');
		}


		
	}   
    
    public function guardarTipoPublicidad(){
        if (is_null($this->utilities->check_permisos()->datos_user )) {
            exit;
        }

        $info = new stdClass();
        $info->proceso = 1; // 0:no se pudo procesar, 1: procesorealizado correctamente
        $info->errores = []; // Errores encontrados en el proceso
        $info->id = null; //Id del usuario creado/editado
        $info->data = null; // Errores encontrados en el proceso

        $data = new stdClass;
        $data->CAMPO=$this->security->xss_clean(trim($this->input->post('nombre')));
        $data->ESTADO=1;
        $data->FECHACREACION=date("Y-m-d H:i:s");
        $dataact = new stdClass();
        if(!empty($this->input->post('idtipopublicidad'))){
            $dataact->ID=$this->security->xss_clean((int)trim($this->input->post('idtipopublicidad')));
        }else{
            $dataact->ID=-1;
        }
        $dataact->FECHAMODIFICACION=date("Y-m-d H:i:s");
        $dataact->FECHABAJA=date("Y-m-d H:i:s");

        //validaciones de datos recibidos

        if(empty($data->CAMPO)){
            array_push($info->errores, "El campo 'tipo' es obligatorio");
            $info->proceso=0;
        }
        
        if($info->proceso==0){//no se insertan datos
            echo json_encode($info);
        }else{
            //datos validados y se hace inserción
            if(!isset($_POST['idtipopublicidad'])){

                    $result = $this->Utilities_model->insertar('PUBLICIDAD_TIPO',$data);
                    echo json_encode($info);

            }else{//editar publicidad tipo
                    
                $set=array("CAMPO" => $data->CAMPO,"FECHAMODIFICACION"=>$dataact->FECHAMODIFICACION);				
                $result = $this->Utilities_model->actualizar("PUBLICIDAD_TIPO","ID_TIPO",$set, $dataact->ID);	
                echo json_encode($info);

            }
        }
        
    }

    public function getTipoPublicidad(){
		if (is_null($this->utilities->check_permisos()->datos_user )) {
			exit;
		}
		//if ($this->utilities->check_permisos()->tipo == 1) {
			$info = new stdClass();
        	$info->proceso = 1; // 0: Hubo problemas, 1: Eliminación realizada
        	$info->errores = [];
        	$info->data=[];
        	$id=$this->security->xss_clean($this->input->post('id'));
			$id=(int)$id;
            if(is_int($id)){
                if($result=$this->Publicidad_model->getDataType(" PT.ID_TIPO=?", array($id))){
                    foreach ($result->result() as $r) {

                        array_push($info->data, $r);

                    }
                    
                }else{
                    array_push($info->errores, 'El tipo de publicidad no existe');
                }
            }else{
                $info->proceso=0;
                array_push($info->errores, "Ocurrio un error en el proceso, intente nuevamente");
            }
            echo json_encode($info);
	}


    public function eliminarTipoPublicidad(){
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
				if($this->Utilities_model->actualizar("PUBLICIDAD_TIPO", "ID_TIPO", array("ESTADO" => 0, "FECHABAJA" => $fecha), $id)){
					//eliminar la imagen acaaa
				}else{
					$info->proceso=0;
					array_push($info->errores, "El tipo de publicidad no ha podido ser eliminado");
				}
			}else{
				$info->proceso=0;
				array_push($info->errores, "Ocurrio un error en el proceso, intente nuevamente");
			}
			echo json_encode($info);
		//}
    }
	


    public function getDataTipoPublicidad(){
		
		if (is_null($this->utilities->check_permisos()->datos_user )) {
			exit;
			}        
		$exportar=$this->security->xss_clean($this->input->post('exportar'));//revisar exportar
        $orderby='';
        $limit='';
        $where=1;
        $draw=1;
        $clauses=[];
        $columnas=array("TIPO");
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
                $where .= " PT.CAMPO LIKE ?";
                array_push($clauses,"%".$value."%");
            }
		}
		

        if($result=$this->Publicidad_model->getDataType($where, $clauses, $orderby )){
            $tamanio=$result->num_rows();
        }
        if($result=$this->Publicidad_model->getDataType($where, $clauses, $orderby ,$limit )){
            //$tamanio=$result->num_rows();
            if($tamanio==0){
                $row=[];
            }
            
            foreach ($result->result() as $r) {
                if($exportar){
                    $row =array(
                        $r->TIPO
                    );
                    
                }else{
                    $row = null;
                    $row = new stdClass();

                    $row->tipo=$r->TIPO;
                    $row->opciones='<div class="dropdown">
                                         <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-cog"></i></button>
                                         <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 33px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                <input type="hidden" name="id" value="'.$r->ID.'">
                                                <input type="hidden" name="'.$this->security->get_csrf_token_name().'" value="'.$this->security->get_csrf_hash().'">
                                            <button type="submit" class="dropdown-item ancho-100 editar_tipo_publicidad" data-tipo-publicidad="'.$r->ID.'"  data-nombre="'.$r->TIPO.'" style="cursor:pointer;width:93%;"><i class="fa fa-pencil"></i> Editar</button>
                                            <button type="submit" class="dropdown-item ancho-100 borrar_tipo_publicidad" data-tipo-publicidad="'.$r->ID.'" data-nombre="'.$r->TIPO.'" style="cursor:pointer;width:93%;" ><i class="fa fa-trash-o"></i> Eliminar</button>';
                }
                 array_push($response->data, $row);
            }
            $recordsFiltered = $tamanio;
            $response->estado=1;
            echo json_encode(array('data' => $response->data, "recordsTotal" => $tamanio, "columns"=>["TIPO"], "recordsFiltered" => $recordsFiltered, 'draw' => $draw));
        }else{
            $row=[];
            $recordsFiltered = 0;
            $response->estado=1;
            echo json_encode(array('data' => $response->data, "recordsTotal" => 0, "columns"=>["TIPO"], "recordsFiltered" => $recordsFiltered, 'draw' => $draw));
        }
    //}
	}
	
	public function getDataTipoPublicidadVista(){
		if (is_null($this->utilities->check_permisos()->datos_user )) {
            exit;
		}
		
		
        $exportar=$this->security->xss_clean($this->input->post('exportar'));//revisar exportar
        $orderby='';
        $limit='';
        $where=1;
        $draw=1;
        $clauses=[];
        $columnas=array("TIPO");
		$response=new stdClass;
		$where1 = "PT.ESTADO = 1" ;
		$clauses1=[];
		$orderby1='';
        $response->data=[];
        if (isset($_POST['draw'])) {
            $draw = intval($_POST['draw']);
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
                      $where1 .= " AND (PT.CAMPO LIKE ? ";
                      $where1 .= " )";
                      //
                      array_push($clauses1,"%".$value."%");
                    }


			}
			
			if (isset($_POST['order'])) {
				$posicion = $_POST['order'][0]['column'];
				$order = $_POST['order'][0]['dir'];
				$orderby = "";
				if ($posicion <= count($columnas)) {
					if ($columnas[$posicion] != "") {
						$orderby1 = "ORDER BY " . $columnas[$posicion] . " " . $order;
					}
				}
			}

			
        if($result=$this->Publicidad_model->getDataType($where, $clauses, $orderby1 )){
            $tamanio=$result->num_rows();
		}
		


		
		if($query = $this->Publicidad_model->getTotalPublicidad($where1,$clauses1,$orderby1,$limit)){
		
			foreach($query->result() as $r){
				
				if($exportar){
					$row = array(
						$r->TIPO,
						$r->TOTAL_PUBLICIDAD,
					);
				}else{
					$row=null;
					$row = new stdClass();
					$row->tipo=$r->TIPO;
					$row->publicidad='<div class="text-center">
					<button  data-nombre="'.$r->TIPO.'" data-tipo-publicidad="'.$r->ID.'" class="btn btn-secondary mostrar_publicidad btn_tipo_1" style="width:100%"type="submit"> <b> '.$r->TOTAL_PUBLICIDAD.'</b></button>
					<input type="hidden" name="id" value="'.$r->ID.'">
					<input type="hidden" name="'.$this->security->get_csrf_token_name().'" value="'.$this->security->get_csrf_hash().'">
				</div>'; 
				}

			array_push($response->data,$row);

			
			}
			
			/* print_r($r);
			die; */

			$recordsFiltered = $tamanio;
            $response->estado=1;
			echo json_encode(array('data' => $response->data, "recordsTotal" => $tamanio, "columns"=>["TIPO","PUBLICIDADES DISPONIBLES"], "recordsFiltered" => $recordsFiltered, 'draw' => $draw));
			
		}

		else{
            $row=[];
            $recordsFiltered = 0;
            $response->estado=1;
            echo json_encode(array('data' => $response->data, "recordsTotal" => 0, "columns"=>["TIPO","PUBLICIDADES DISPONIBLES"], "recordsFiltered" => $recordsFiltered, 'draw' => $draw));
        }
	//}
	
	}


    ////////////// FIN TIPO PUBLICIDAD


}