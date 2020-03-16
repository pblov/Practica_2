<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('cookie');
        $this->load->library('My_SendGrid');
        $this->load->model('Utilities_model');
        $this->load->model('home/home_model');
        date_default_timezone_set('America/Santiago');
        $this->NOMBRE_SISTEMA = NOMBRE_SISTEMA_CON;
            
            
    }

  /*  public function index() {  // ES EL LOGIN
         $data['datalibrary'] = array('titulo' => "Login", 'vista' => array('login'), "libjs"=>array('libjs'),"libcss" => array("libcss"));
            $this->load->view('estructura/body_simple', $data);
            
//            
//        if (!is_null($this->utilities->check_permisos()->datos_user)) {
//            $tipo = $this->utilities->check_permisos()->datos_user->ID_TIPO;
//
//
//
//            switch ($tipo) {
//                case 1:
//                    redirect();
//                    break;
//                case 2:
//                    redirect();
//                    break;
//                case 3:
//                    redirect();
//                    break;
//                default:
//                    redirect('');
//                    break;
//            }
//        } else {
//
//            $data['datalibrary'] = array('titulo' => "Login", 'vista' => array('login', 'modal'));
//            $this->load->view('estructura/body_simple', $data);
//        }
    }*/


    public function index() {  // ES EL LOGIN
       // $data['datalibrary'] = array('titulo' => "Login", 'vista' => array('login'), "libjs"=>array('libjs'),"libcss" => array("libcss"));
         //   $this->load->view('estructura/body_simple', $data);
        if (!is_null($this->utilities->check_permisos()->datos_user)){
        //$tipo = $this->utilities->check_permisos()->datos_user->ID_TIPO; 
            redirect("dashboard");
         
        } else {
            /*if ($this->session->userdata('cambioclave')) {
                redirect('home/cambioClave');
            } else {
                
 
            }*/
        $data['datalibrary'] = array('titulo' => "Login","libjs"=>array('libjs'),"libcss", 'vista' => array('login', 'modal'));
        $this->load->view('estructura/body_simple', $data);
                
        }
        
        
    }



    public function ingresar() {

        $info = new stdClass();
        $info->errores = [];
        $info->url = null;
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $correo = null;
            $rut = null;
            $ruc = null;
            $clave = null;
            $tipo = null;
            $where = "1";
            $valor_login = "";

            if (!empty($this->input->post('tipo'))) {
                $tipo = (int) trim($this->security->xss_clean($this->input->post('tipo', TRUE)));
            }

            $tipo=1;

            switch ($tipo) {
     
                case 1:
                    if (!empty($this->input->post('correo'))) {
                        $correo = $this->security->xss_clean($this->input->post('correo', TRUE));
                        $correo = trim($correo);
                        $largo = strlen($correo);
                        $valor_login = $correo;
                        $where = "U.CORREO=?";
                        if ($largo > 0) {
                            $validar = (!preg_match("/^([a-z0-9ñÑ\+_\-]+)(\.[a-z0-9ñÑ\+_\-]+)*@([a-z0-9ñÑ\-]+\.)+[a-z]{2,6}$/ix", $correo)) ? FALSE : TRUE;
                            if ($validar) {
                                //Correo es válido
                            } else {
                                $info->errores[] = "El campo 'Correo' no es un correo válido";
                            }
                        } else {
                            $info->errores[] = "El campo 'Correo' no puede quedar en blanco";
                        }
                    } else {
                        $info->errores[] = "El campo 'Correo' no puede quedar en blanco";
                    }
                    break;

                default:
                    break;
            }



            if (!empty($this->input->post('clave'))) {
                $clave = $this->security->xss_clean($this->input->post('clave', TRUE));
                $clave = trim($clave);
                $largo = strlen($clave);
                if (6 <= $largo && $largo <= 12) {
                    $validar = (!preg_match("/^([a-z0-9\_\-\!\¡\#\¿\?\*\+\(\)\{\}\$\%\&\[\]])/i", $clave)) ? FALSE : TRUE;
                    if ($validar) {
                        //Clave es valida
                    } else {
                        $info->errores[] = "El campo 'Contraseña' posee elementos no permitidos";
                    }
                } else {
                    $info->errores[] = "El campo 'Contraseña' debe poseer de 6 a 12 elementos";
                }
            } else {
                $info->errores[] = "El campo 'Contraseña' no puede quedar en blanco";
            }


            if ( $correo && $clave && sizeof($info->errores) == 0) {



                if ($check_usuario = $this->home_model->getUsuario($where, array($valor_login))) {
                    $clave = $check_usuario->ID_USUARIO . "-" . sha1($clave);
                    // die($clave);
                    if ($usuario = $this->Utilities_model->login($where . " AND UC.CLAVE=?", array($valor_login, $clave))) {

                       
                        
                       
                      /* 
                        switch($usuario->ID_TIPO){
                            case '1':
                                         break;
                            case '2': 

                                         break;
                            case '3':  
                                         break;

                        } */
                        
                      
                        //Obtener información sobre las claves del usuario
                        if ($claves = $this->home_model->clavesUsuario("U.ID_USUARIO=?", array($usuario->ID_USUARIO))) {
                            $claves = $claves->row();

                            
                            //Actualizar fecha de ultimo acceso
                            $datos = array('FECHAULTIMOACCESO' => date('Y-m-d H:i:s'));
                            $this->home_model->actualizar("USUARIO", "ID_USUARIO", $datos, $usuario->ID_USUARIO);
                           

                            //Comprobar, en caso de estar habilitado, si la clave pasoó su fecha de caducidad
                            $cambiar_clave = false;
                            if (CLAVE_ACTUALIZAR) {
                                $fecha_ultimo_cambio_DT = DateTime::createFromFormat('Y-m-d H:i:s', $claves->ACTUAL_FECHACREACION);
                                $fecha_caduca_clave_DT = DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));
                                $fecha_caduca_clave_DT->sub(new DateInterval('P' . CLAVE_CADUCA_DIAS . 'D'));
                                if ($fecha_caduca_clave_DT > $fecha_ultimo_cambio_DT) {
                                    $cambiar_clave = true;
                                }
                            }

                            if ($claves->CANTIDAD == 1 || $cambiar_clave) {
                                $RECORDAR = NULL;
                                if (!empty($this->input->post('recordar')) && $this->input->post('recordar') === "true") {
                                    $RECORDAR = TRUE;
                                }

                                $datosUsuario = (object) array(
                                            'ID_USUARIO' => $usuario->ID_USUARIO,
                                            'RECORDAR' => $RECORDAR
                                );
                                $this->session->set_userdata("cambioclave", $datosUsuario);

                                $info->url = base_url() . "home/cambioClave";
                            } else {

                                //Información a almacenar en variable de sesión
                                $datosUsuario = (object) array(
                                            'ID_USUARIO' => $usuario->ID_USUARIO,
                                            'USUARIO_PREVIO' => null,
                                            'ID_TIPO' => (int) $usuario->ID_TIPO,
                                            'NOMBRE' => $usuario->NOMBRE,
                                            'CORREO' => $usuario->CORREO,
                                            'AVATAR' => $usuario->FOTOPERFIL,
                                            'ULTIMO_ACCESSO' => $usuario->FECHAULTIMOACCESO,
                                            'MENU' => (object) array(

                                            ));

                                //Almacenar información en sesión
                                $this->session->set_userdata("usuario", $datosUsuario);

                                //Almacenar información en cookies
                                if (!empty($this->input->post('recordar')) && $this->input->post('recordar') === "true") {
                                    $cookie = array(
                                        'name' => "usuario",
                                        'value' => serialize($datosUsuario),
                                        'expire' => '2592000'
                                    );
                                    $this->input->set_cookie($cookie);
                                }
                                  $info->url = base_url();
                            }
                        } else {
                            $info->errores[] = 'No fue posible validar los datos ingresados. Intente nuevamente.';
                        }
                    } else {
                        $info->errores[] = 'La información no pertenece a ningún usuario registrado. Intente nuevamente.';
                    }
                } else {
                    $info->errores[] = 'La información no pertenece a ningún usuario registrado. Intente nuevamente.';
                }
            } else {
                // $info->errores[] = 'No fue posible validar los datos ingresados. Intente nuevamente.';
            }
        } else {
            $info->errores[] = 'No fue posible validar los datos ingresados. Intente nuevamente.';
        }
        $info = json_encode($info);
        echo $info;
        
    }



    //verificar que email se encuentre en la BDD luego de ser autenticado el usuario en Gmail o Facebook desde el front-end
    public function verificarEmail(){


        $info = new stdClass();
        $info->errores = [];
        $info->url = null;
            


        $valor_login = $this->security->xss_clean($this->input->post('correo', TRUE));

        if ($valor_login !== 'undefined') {
           
        
            
                // if (!($valor_login)) {
                //     die("falta el correo");
                // }
                // die("correo : ".$valor_login);
            
                $where = "U.CORREO=?";

                if ($check_usuario = $this->home_model->getUsuario($where, array($valor_login))) { 
                    

                    $datosUsuario = (object) array(
                        'ID_USUARIO' => $check_usuario->ID_USUARIO,
                        'USUARIO_PREVIO' => null,
                        'ID_TIPO' => (int) $check_usuario->ID_TIPO,
                        'NOMBRE' => $check_usuario->NOMBRE,
                        'CORREO' => $check_usuario->CORREO,
                        'AVATAR' => $check_usuario->FOTOPERFIL,
                        'ULTIMO_ACCESSO' => $check_usuario->FECHAULTIMOACCESO,
                        'MENU' => (object) array(
                        ));


                        //Almacenar información en sesión
                        $this->session->set_userdata("usuario", $datosUsuario);

                        $info->url = base_url();

                }else{
                    $info->errores[] = "La información no pertenece a ningún usuario registrado. Intente nuevamente.";
                }

        }else{
            $info->errores[] = "Es necesario que proporcione su dirección de correo electrónico";
        }
        $info = json_encode($info);
        echo $info;
    }

    function check_rut($campo) {

        $rut = preg_replace('/[^k0-9]/i', '', $campo);
        $dv = substr($rut, -1);
        $numero = substr($rut, 0, strlen($rut) - 1);
        $i = 2;
        $suma = 0;
        foreach (array_reverse(str_split($numero)) as $v) {
            if ($i == 8)
                $i = 2;
            $suma += $v * $i;
            ++$i;
        }
        $dvr = 11 - ($suma % 11);

        if ($dvr == 11)
            $dvr = 0;
        if ($dvr == 10)
            $dvr = 'K';

        if ($dvr == strtoupper($dv)) {
            $valido = true;
        } else {
            $valido = false;
        }

        return $valido;
    }

    public function cambioClave() {
        if ($session = $this->session->userdata('cambioclave')) {
            $data['token_pw'] = 0;
            $data['datalibrary'] = array('titulo' => "Cambio de clave", 'vista' => array('cambio_clave'));
            $this->load->view('estructura/body_simple', $data);
        } else {
            redirect('Home');
        }
    }

    public function olvido_clave() {
        $HORAS_CADUCIDAD = 48;
        $form = new stdClass();
        $form->token = null;
        $fecha = date('Y-m-d H:i:s');
        if (!empty($this->input->get('token'))) {
            $form->token = trim($this->security->xss_clean($this->input->get('token', TRUE)));
            $form->token = trim($form->token);
            if (empty($form->token)) {
                $this->session->unset_userdata("token");
                redirect('');
            } else {

                if ($query = $this->home_model->getUsuario("U.TOKEN_PWD = ?", array($form->token))) {

                    $fecha1 =  DateTime::createFromFormat('Y-m-d H:i:s',$query->TOKEN_FECHACREACION);
                    $fecha2 = new DateTime($fecha); //fecha de cierre
                    $cantidad_horas = $this->tiempoCaducidad($fecha1, $fecha2);
                    if ($HORAS_CADUCIDAD > $cantidad_horas) {
                        $data['datalibrary'] = array('titulo' => "Cambio de clave", 'vista' => array('cambio_clave'));
                        $data['token_pw'] = "1";
                        $this->load->view('estructura/body_simple', $data);
                        $this->session->set_userdata("token", $form->token);
                    } else { //Caduco el token....
                        $this->session->unset_userdata("token");
                        redirect('');
                    }
                } else {
                    $this->session->unset_userdata("token");
                    redirect('');
                }
            }
        } else {
            $this->session->unset_userdata("token");
            redirect('');
        }
    }

    public function resetearclave() {
        $info = new stdClass();
        $info->proceso = 0;
        $info->url = null;
        $info->errores = [];
        $usuario_info = null;
        $fecha = date('Y-m-d H:i:s');
        $clave = null;
        if (!empty($this->input->post('clave'))) {
            $clave = $this->input->post('clave');
            $clave = trim($clave);
            $largo = strlen($clave);
            if (6 <= $largo && $largo <= 12) {
                $validar = (!preg_match("/^([a-z0-9\_\-\!\¡\#\¿\?\*\+\(\)\{\}\$\%\&\[\]])/i", $clave)) ? FALSE : TRUE;
                if ($validar) {
                    //Clave es valida
                } else {
                    $info->errores[] = "El campo 'Contraseña' posee elementos no permitidos";
                }
            } else {
                $info->errores[] = "El campo 'Contraseña' debe poseer de 6 a 12 elementos";
            }
        } else {
            $info->errores[] = "El campo 'Contraseña' no puede quedar en blanco";
        }


        if ($clave && sizeof($info->errores) == 0) {
            $where = "U.TOKEN_PWD=?";
            $token = $this->session->userdata("token");

            
            if ($usuario_info = $this->home_model->getUsuario($where, array($token))) {

              
              $ingreso = null;
              if ($clave_info = $this->home_model->claveUsuario("UC.ID_USUARIO=?", array($usuario_info->ID_USUARIO))) {
                $clave_info = $clave_info->row();
                $clave = $clave_info->ID_USUARIO . "-" . sha1($clave);
                if ($clave != $clave_info->CLAVE) {
                  $datos = array(
                      "ID_USUARIO" => $usuario_info->ID_USUARIO,
                      "CLAVE" => $clave,
                      "FECHACREACION" => $fecha,
                      "ESTADO" => 1
                  );
                  if($ingreso = $this->home_model->insertar("USUARIO_CLAVE", $datos)){
                    $datos = array('FECHABAJA' => $fecha, 'ESTADO' => 0);
                    $this->home_model->actualizar("USUARIO_CLAVE", "ID_CLAVE", $datos, $clave_info->ID_CLAVE);
                  }
                }else{
                  $info->errores[] = "Su nueva clave debe ser diferente a la actual";
                }
              }else{
                $clave = $usuario_info->ID_USUARIO . "-" . sha1($clave);
                $datos = array(
                    "ID_USUARIO" => $usuario_info->ID_USUARIO,
                    "CLAVE" => $clave,
                    "FECHACREACION" => $fecha,
                    "ESTADO" => 1
                );
                $ingreso = $this->home_model->insertar("USUARIO_CLAVE", $datos);
              }



              if ($ingreso) {
                  $this->home_model->actualizar("USUARIO", "ID_USUARIO", array("TOKEN_PWD" => NULL, "TOKEN_FECHACREACION" => NULL), $usuario_info->ID_USUARIO);
                  $info->proceso = 1;


                  $correo_receptor = $usuario_info->CORREO;
                  $nombre_receptor = $usuario_info->NOMBRE;
                  $subject = "Cambio de clave - " . $this->NOMBRE_SISTEMA;

                  $mensaje = "<div style='font-family:Muli,BlinkMacSystemFont, Segoe UI, Roboto, Helvetica Neue, Arial, sans-serif'>
                      <div style='padding:3% 5% 5% 3%;color:#005CFF'>
                              <div style='width:100%;text-align:center;color:#005CFF'>
                              <h2>Cambio de clave</h2>
                            </div>
                              <p style='color:#005CFF'>Estimado Usuario/a  $nombre_receptor,</p>
                            <p style='color:#005CFF'>se cambió su clave de forma exitosa.</p>
                            <br>
                            <p style='color:#005CFF'>Atentamente equipo " . $this->NOMBRE_SISTEMA . "</p>
                      </div>
                    </div>";
                  $this->enviarCorreo(MAIL_EMISOR, $correo_receptor, $subject, $mensaje);
              } else {
                  $info->errores[] = "No fue posible realizar el cambio de contraseña. Intente más tarde";
              }

            } else {
                $info->errores[] = "No fue posible realizar el cambio de contraseña. Intente más tarde";
            }
        }


        $info = json_encode($info);
        echo $info;
    }

    public function envio_correo_olvido_clave() {
        $info = new stdClass();
        $HORAS_CADUCIDAD = 48;
        $info->proceso = 0; // 0:no se pudo procesar, 1: proceso realizado correctamente
        $info->errores = []; // Errores encontrados en el proceso
        // Crear objeto con información necesaria
        $form = new stdClass();
        $form->campo = null;
        $form->idusuario = null;
        $form->correo_usuario = null;
        $form->nombre_usuario = "";

        $fecha = date('Y-m-d H:i:s');

        if (!empty($this->input->post('tipo'))) {

            $tipo = (int) trim($this->security->xss_clean($this->input->post('tipo', TRUE)));
            $tipo = 1;
            switch ($tipo) {
                case 1:
                    if (!empty($this->input->post('campo'))) {
                        $correo = $this->security->xss_clean($this->input->post('campo', TRUE));
                        $correo = trim($correo);
                        $largo = strlen($correo);
                        $valor_login = $correo;
                        $where = "U.CORREO=?";
                        if ($largo > 0) {
                            $validar = (!preg_match("/^([a-z0-9ñÑ\+_\-]+)(\.[a-z0-9ñÑ\+_\-]+)*@([a-z0-9ñÑ\-]+\.)+[a-z]{2,6}$/ix", $correo)) ? FALSE : TRUE;
                            if ($validar) {
                                //Correo es válido
                            } else {
                                $info->errores[] = "El campo 'Correo' no es un correo válido";
                            }
                        } else {
                            $info->errores[] = "El campo 'Correo' no puede quedar en blanco";
                        }
                    } else {
                        $info->errores[] = "El campo 'Correo' no puede quedar en blanco";
                    }
                    break;

                default:
                    break;
            }

            if (sizeof($info->errores) == 0) {
                $form->campo = $valor_login;
                $where = array($form->campo);
                $where[] = $form->campo;
                if ($query = $this->home_model->getUsuario(" ( U.CORREO = ? OR U.RUT = ? ) ", $where)) {
                    $form->idusuario = $query->ID_USUARIO;
                    $form->correo_usuario = $query->CORREO;
                    $form->nombre_usuario = $query->NOMBRE;
                    if ( !$this->validarCampo('correo',$form->correo_usuario)  ) {
                        $info->errores[] = "No posee correo registrado.";
                    } else {
                        if ($query->TOKEN_FECHACREACION) {
                            //$fecha1 = new DateTime($query->TOKEN_FECHACREACION); //fecha inicial

                            $fecha1 =  DateTime::createFromFormat('Y-m-d H:i:s',$query->TOKEN_FECHACREACION);


                             //var_dump($query);
                            $fecha2 = new DateTime($fecha); //fecha de cierre

                            $cantidad_horas = $this->tiempoCaducidad($fecha1, $fecha2);

                            if ($HORAS_CADUCIDAD > $cantidad_horas) {
                                $info->errores[] = "Ya fue enviada una solicitud para recuperar la clave. Puede enviar otra después de 48 horas de la primera petición.";
                            }
                        }
                    }
                } else {
                    $info->errores[] = "No existen datos registrados.";
                }
            }

        } else {
            $info->errores[] = "No fue posible validar los datos ingresados. Intente nuevamente.";
        }


        if (sizeof($info->errores) == 0) {
            $rand_part = false;
            while (!$rand_part) {
                $rand_part = $this->generarCodigo();
                if ($this->home_model->getUsuario("U.TOKEN_PWD = ?", array($rand_part))) {
                    $rand_part = false;
                }
            }

            $datos = array(
                "TOKEN_PWD" => $rand_part,
                "TOKEN_FECHACREACION" => $fecha
            );
            if ($this->home_model->actualizar("USUARIO", "ID_USUARIO", $datos, $form->idusuario)) {
                $info->proceso = 1;
                if (ENVIAR_CORREO) {
                    $correo_receptor = $form->correo_usuario;
                    $subject = "Recuperar clave - " . $this->NOMBRE_SISTEMA;
                    $link = base_url() . "home/olvido_clave?token=" . $rand_part;

                    $mensaje = "<div style='font-family:Muli,BlinkMacSystemFont, Segoe UI, Roboto, Helvetica Neue, Arial, sans-serif'>
                      	<div style='padding:3% 5% 5% 3%;color:#005CFF'>
                          	<div style='width:100%;text-align:center;color:#005CFF'>
                              	<h2>Recuperación de Contraseña</h2>
                              </div>
                      		<p style='color:#005CFF'>Estimado Usuario/a $form->nombre_usuario ,</p>
                              <p style='color:#005CFF'>se ha solicitado recuperar tú contraseña, si no has sido tu ignora este mensaje, de lo contrario, presiona el link de más abajo.</p>
                              <p ><a href='$link' style='color:#005CFF'>Link</a></p>
                              <br>
                              <p style='color:#005CFF'>En caso de no poder ingresar al link, copia y pega esta dirección en tu navegador.</p>
                              <p style='color:#005CFF'><a href='$link' style='color:#005CFF'>$link</a></p>
                              <br>
                              <p style='color:#005CFF'>Atentamente equipo " . $this->NOMBRE_SISTEMA . "</p>
                      	</div>
                      </div>";
//                    die($mensaje);
                    $this->enviarCorreo(MAIL_EMISOR, $correo_receptor, $subject, $mensaje);
                }
            }
        } else {
            // echo "tudo mal";
        }

        $info = json_encode($info);
        echo $info;
    }

    private function tiempoCaducidad($fecha1, $fecha2) {
        $intervalo = $fecha1->diff($fecha2);
        $dias = intval($intervalo->format('%d'));
        $horas = intval($intervalo->format('%h'));

        $cantidad_horas = ($dias * 24) + ($horas);

        return $cantidad_horas;
    }
    public function validarCampo($tipo,$campo){
      //$tipo: tipo de campo a testear, $campo: campo a testear
      $valido = false;
    
      switch ($tipo) {
        case 'telefono':
          if (preg_match("/[+]{1}[0-9]{11}/i", $campo)) {
            $valido = true;
          } else {
            $valido = false;
          }
          break;
        case 'correo':
          $valido =  (!preg_match("/^([a-z0-9ÁéÉíÍóÓúÚñÑ\+_\-]+)(\.[a-z0-9ÁéÉíÍóÓúÚñÑ\+_\-]+)*@([a-z0-9ÁéÉíÍóÓúÚñÑ\-]+\.)+[a-z]{2,6}$/ix", $campo)) ? FALSE : TRUE;
          break;
        case 'rut':
          $rut = preg_replace('/[^k0-9]/i', '', $campo);
          $dv = substr($rut, -1);
          $numero = substr($rut, 0, strlen($rut) - 1);
          $i = 2;
          $suma = 0;
          foreach (array_reverse(str_split($numero)) as $v) {
              if ($i == 8)
                  $i = 2;
                  $suma += $v * $i;
                  ++$i;
              }
              $dvr = 11 - ($suma % 11);

              if ($dvr == 11)
                  $dvr = 0;
              if ($dvr == 10)
                  $dvr = 'K';

              if ($dvr == strtoupper($dv)){
                $valido = true;
              }
              else{
                $valido = false;
              }

          ////
          break;
      }

      return $valido;
    }

    private function generarCodigo() {
//        $length = 10;
//        $inviteCode = "";
//        $characters = "0123456789abcdefghijklmnopqrstuvwxyz";
//        for ($p = 0; $p < $length; $p++) {
//            $inviteCode .= $characters[mt_rand(0, (strlen($characters) - 1))];
//        }

        return str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789" . uniqid());
    }

    public function actualizarclave() {
        $info = new stdClass();
        $info->url = null;
        $info->errores = [];
        $usuario_info = null;
        $fecha = date('Y-m-d H:i:s');

        $reporte_cluster = 0;
        $reporte_que_hablan = 0;

        if ($usuario_info = $this->session->userdata('cambioclave')) {

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $clave = null;
                if (!empty($this->input->post('clave'))) {
                    $clave = $this->input->post('clave');
                    $clave = trim($clave);
                    $largo = strlen($clave);
                    if (6 <= $largo && $largo <= 12) {
                        $validar = (!preg_match("/^([a-z0-9\_\-\!\¡\#\¿\?\*\+\(\)\{\}\$\%\&\[\]])/i", $clave)) ? FALSE : TRUE;
                        if ($validar) {
                            //Clave es valida
                        } else {
                            $info->errores[] = "El campo 'Contraseña' posee elementos no permitidos";
                        }
                    } else {
                        $info->errores[] = "El campo 'Contraseña' debe poseer de 6 a 12 elementos";
                    }
                } else {
                    $info->errores[] = "El campo 'Contraseña' no puede quedar en blanco";
                }

                if ($clave && sizeof($info->errores) == 0) {
                    $where = "U.ID_USUARIO=?";
                    if ($clave_info = $this->home_model->claveUsuario($where, array($usuario_info->ID_USUARIO))) {
                        $clave_info = $clave_info->row();
                        $clave = $clave_info->ID_USUARIO . "-" . sha1($clave);
                        if ($clave != $clave_info->CLAVE) {
                            $datos = array(
                                "ID_USUARIO" => $usuario_info->ID_USUARIO,
                                "CLAVE" => $clave,
                                "FECHACREACION" => $fecha,
                                "ESTADO" => 1
                            );
                            $ingreso = $this->home_model->insertar("USUARIO_CLAVE", $datos);
                            if ($ingreso) {

                                $datos = array('FECHABAJA' => $fecha, 'ESTADO' => 0);
                                $this->home_model->actualizar("USUARIO_CLAVE", "ID_CLAVE", $datos, $clave_info->ID_CLAVE);

                                if ($usuario = $this->home_model->getUsuario("U.ID_USUARIO=?", array($usuario_info->ID_USUARIO))) {


                                  /*if($cluster_check = $this->home_model->comprobarUsuarioCluster("U.ID_USUARIO=?",array($usuario->ID_USUARIO))){
                                    $reporte_cluster = 1;
                                  }
                                  */

                               /* if($permiso = $this->home_model->permiso_de_que_hablan("JP.ID_JERARQUIA = ?",
                                    array($usuario->ID_JERARQUIA)) ){
                                    # obtengo el permiso si es 0 no vera el menu de
                                    $reporte_que_hablan = 1;
                                }*/

                                    //Información a almacenar en variable de sesión
                                    $datosUsuario = (object) array(
                                                'ID_USUARIO' => $usuario->ID_USUARIO,
                                                'USUARIO_PREVIO' => null,
                                                'ID_TIPO' => (int) $usuario->ID_TIPO,
                                                'NOMBRE' => $usuario->NOMBRE,
                                                'CORREO' => $usuario->CORREO,
                                                'AVATAR' => $usuario->FOTOPERFIL,
                                                'ULTIMO_ACCESSO' => $usuario->FECHAULTIMOACCESO,
                                                'MENU' => (object) array(
                                                /*  "REPORTE_CLUSTER" => $reporte_cluster,
                                                  "REPORTE_QUE_HABLAN" => $reporte_que_hablan*/
                                                ));

                                                

                                    //Almacenar información en sesión
                                    $this->session->set_userdata("usuario", $datosUsuario);

                                    //Almacenar información en cookies
                                    if (!is_null($usuario_info->RECORDAR)) {
                                        // echo "guardar cookie";
                                        // die();
                                        $cookie = array(
                                            'name' => "usuario",
                                            'value' => serialize($datosUsuario),
                                            'expire' => '2592000'
                                        );
                                        $this->input->set_cookie($cookie);
                                    }



                                    $this->session->unset_userdata('cambioclave');

                                    $info->url = base_url() . "home";
                                } else {
                                    $info->errores[] = "Hubo un problema al procesar la solicitud. Intente más tarde";
                                }
                            } else {
                                $info->errores[] = "No fue posible realizar el cambio de contraseña. Intente más tarde";
                            }
                        } else {
                            $info->errores[] = "Su nueva clave debe ser diferente a la actual";
                        }
                    } else {
                        $info->errores[] = "No fue posible realizar el cambio de contraseña. Intente más tarde";
                    }
                }
            } else {
                $info->errores[] = "No fue posible realizar el cambio de contraseña. Intente más tarde";
            }
        } else {
            $info->errores[] = "No fue posible realizar el cambio de contraseña. Intente más tarde";
        }

        $info = json_encode($info);
        echo $info;
    }

    private function enviarCorreo($from, $to, $subject, $content) {
        if (ENVIAR_CORREO) {
            //From de correo
            //$from = new SendGrid\Email("Sistema " . $this->NOMBRE_SISTEMA, $from);
            $from = new SendGrid\Email($this->NOMBRE_SISTEMA, $from);
            //API KEY SendGrid
            $apiKey = API_KEY_SENDGRID;
            $sg = new \SendGrid($apiKey);

            $to = new SendGrid\Email("Usuario", $to);

            //Contenido
            $content = new SendGrid\Content("text/html", $content);

            //Creación del correo
            $mail = new SendGrid\Mail($from, $subject, $to, $content);

            //Envio y obtenición de respuesta
            $sg->client->mail()->send()->post($mail);
        }
    }

    public function salir() {
        $this->session->sess_destroy();

        if ($this->session->userdata('usuario')) {
            delete_cookie("usuario");
        }


        redirect('');
    }

}
