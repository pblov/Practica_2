const ID_CLIENTE_FACEBOOK = "<?php echo ID_CLIENTE_FACEBOOK ?>" 
const ID_CLIENTE_GOOGLE = "<?php echo ID_CLIENTE_GOOGLE ?>" 
 // Variable Global para evitar auto-inicio de google
var clicked=false;

function init(){
    clicked=false;
      //Dar border radius a boton de google
    $("#google-button").css("border-radius","4px");
    $(".abcRioButtonLightBlue").css("border-radius","4px");


    $("#google-button").css("border-radius","4px");
    $(".abcRioButtonLightBlue").css("border-radius","4px");

    $(".abcRioButtonLightBlue").css("height","40px");
    $(".abcRioButtonLightBlue").css("padding-top","3px");

    //Cambar nombre de boton de google 
    $('span[id^="not_signed_"]').html(' ');
}

function ClickLogin()
{
    clicked=true;
}




var mayuscula_activado = false;
var form_ingreso = {
    correo: {campo: $("#correo")},
    clave: {campo: $("#clave")}

};
var form_cambio_clave = {
    clave: {campo: $("#cambio_clave")},
    clave_confirmar: {campo: $("#cambio_clave_confirmar")}
};

var form_olvido_clave = {
    campo: $("#formulario-olvido-campo"),
    correo: $("#correo_olvido"),
    rut: $("#rut_olvido"),
    ruc: $("#ruc_olvido"),
    url: base_url + "home/envio_correo_olvido_clave"
};
var form_cambio_clave_olvido = {
    id_form: "#formulario-cambio-clave",
    url: base_url + "home/resetearclave",
    clave: {campo: $("#cambio_clave")},
    clave_confirmar: {campo: $("#cambio_clave_confirmar")}
};

var modal_olvido_clave = "#modal-olvido-clave";

$(document).ready(function () {
    

    click_olvido_clave();
    enviarClave();
    submitClave();



    visualizarClave();
    detectarMayuscula();
    validarInput();
    ajustarTipoClave();
    enviarFormulario();

    //radio_ingreso();
    validainputRUT();
      $('#correo').val('');
});


function radio_ingreso() {

    $("input[name='login-form-ingreso']").change(function (e) {

        var valor = parseInt($(this).val());
        valor = 1;
        switch (valor) {
            case 1:
                $('#caja_correo').show();
                $('#correo').prop("required", true);
                form_ingreso = {
                    correo: {campo: $("#correo")},
                    clave: {campo: $("#clave")}
                };
                break;

            default:

                break;
        }
    });

   /* $("input[name='login-form-olvido']").change(function (e) {

        var valor = parseInt($(this).val());
        valor = 1;
        switch (valor) {
            case 1:
                $('#caja_correo_olvido').show();
                $('#correo_olvido').prop("required", true);
//                form_olvido_clave = {
//                    correo: {campo: $("#correo")},
//                    clave: {campo: $("#clave")}
//                };
                break;

            default:

                break;
        }
    });*/
}


function validainputRUT() {
    $(".validar").on("input", function () {
        // console.log("validar input");
        // Obtener información asociada al input
        var campo = $(this);
        var valor = campo.val();
        var tipo = campo.data("tipo");
        //Quitar espacios antes/despues
        // valor = valor.trim();
    

        if (valor) {
            switch (tipo) {
                case "rut":
                    // console.log("RUT=>",valor);
                    valor = valor.replace(/[^0-9k\-\.]/ig, "");
                    valor = valor.slice(0, 12);
                    if (valor.length > 3) {
                        var actual = valor.replace(/^0+/, "");
                        var rutPuntos = "";

                        var sinPuntos = actual.replace(/\./g, "");
                        var actualLimpio = sinPuntos.replace(/-/g, "");
                        var inicio = actualLimpio.substring(0, actualLimpio.length - 1);
                        // var rutPuntos = "";
                        var i = 0;
                        var j = 1;
                        for (i = inicio.length - 1; i >= 0; i--) {
                            var letra = inicio.charAt(i);
                            rutPuntos = letra + rutPuntos;
                            if (j % 3 == 0 && j <= inicio.length - 1) {
                                rutPuntos = "." + rutPuntos;
                            }
                            j++;
                        }
                        var dv = actualLimpio.substring(actualLimpio.length - 1);
                        rutPuntos = rutPuntos + "-" + dv;
                        valor = rutPuntos;
                    }
                    // console.log("rutPuntos");
                    break;

                default:
                    break;
            }
        }

        campo.val(valor);

    });
}
function detectarMayuscula() {
    $("input").on("keyup", function (e) {
        e.stopPropagation();

        /*if (e.originalEvent.getModifierState("CapsLock")) {
            $("#alerta-mayusculas").slideDown("slow", function () {
            });
        } else {
            $("#alerta-mayusculas").slideUp("slow", function () {
            });
        }*/

    });
}


function click_olvido_clave() {
    $("#olvido_clave").on("click", function (e) {

        $(modal_olvido_clave).modal();
        form_olvido_clave.correo.val("");
       // $("#login-form-olvido-correo").change();
       // $("input[name='login-form-olvido']").val([1]);
        $(modal_olvido_clave).find(".modal-submit").prop("disabled", false);
    });
}

function enviarClave() {
    $("#formulario-olvido-clave").on("submit", function (e) {
        e.preventDefault();
        //Bloquer btn submit
        $(modal_olvido_clave).find(".modal-submit").prop("disabled", true);

        //Validar campos
        var formulario = new FormData();
        formulario.append(token_name, token_hash);
        
        
      //  var check_tipo = parseInt($("input[name='login-form-olvido']:checked").val());
        var check_tipo = 1;

        switch (check_tipo) {
            case 1:
                formulario.append("campo", form_olvido_clave.correo.val());
                break;


            default:

                break;
        }
        
        formulario.append("tipo", check_tipo);

        $(modal_olvido_clave).loading({message: 'Enviando datos'});

        //Procesar
        getAjaxFormData(formulario, form_olvido_clave.url).then(function (result) {

            if (isJson(result)) {
                var obj = JSON.parse(result);
                if (obj.proceso) {
                    _toastr("success", "El correo fue enviado exitosamente", true);
                    $(modal_olvido_clave).modal("hide");
                } else {
                    mostrarErrores(obj.errores);
                }
            } else {
                _toastr("error", "La acción no pudo ser realizada", true);
            }


        }).fail(function (result) {

        }).always(function (result) {
            $(modal_olvido_clave).loading('toggle');
            $(modal_olvido_clave).find(".modal-submit").prop("disabled", false);
        });

    });
}

function submitClave() {
    $(form_cambio_clave_olvido.id_form).on("submit", function (e) {

        if(token_pw == 1){
            e.preventDefault();
            var confirmacion = false;
            if (form_cambio_clave_olvido.clave.campo.val() == form_cambio_clave_olvido.clave_confirmar.campo.val()) {
                confirmacion = true;
            }
            var clave = form_cambio_clave_olvido.clave.campo.val();
            
            if (confirmacion) {
                //Armar data
                var form_data = new FormData();
                form_data.append(token_name, token_hash);
                form_data.append("clave", clave);
                $(form_cambio_clave_olvido.id_form).find(".button-submit").prop("disabled", true);
                $(modal_olvido_clave).loading({message: 'Enviando datos'});
                //Procesar
                getAjaxFormData(form_data, form_cambio_clave_olvido.url).then(function (result) {
                    if (isJson(result)) {
                        var obj = JSON.parse(result);
                        if (obj.proceso) {
                            _toastr("success", "Cambio realizado correctamente", true);
                            setTimeout(function(){ window.location.href = base_url; }, 3000);
                        } else {
                            mostrarErrores(obj.errores);
                            $(form_cambio_clave_olvido.id_form).find(".button-submit").prop("disabled", false);
                        }
                    } else {
                        $(form_cambio_clave_olvido.id_form).find(".button-submit").prop("disabled", false);
                        _toastr("error", "La acción no pudo ser realizada", true);
                    }


                }).fail(function (result) {
                    $(form_cambio_clave_olvido.id_form).find(".button-submit").prop("disabled", false);
                    $(form_cambio_clave_olvido.id_form).loading('stop');
                }).always(function (result) {
                    $(form_cambio_clave_olvido.id_form).loading('stop');
                });
            } else {
                mostrarErrores(["'Contraseña' y 'Confirmar contraseña' deben ser iguales"]);
            }
        }

        if(token_pw == 0){
        e.preventDefault();
        //Bloquer btn submit
        $("#cambio_clave-form_btn-submit").prop("disabled", true);

        //Validar campos
        var validacion = validarFormulario(form_cambio_clave);
        if (validacion.estado) {
            //Comprobar clave y confirmación sean iguales
            var confirmacion = false;
            if (form_cambio_clave.clave.campo.val() == form_cambio_clave.clave_confirmar.campo.val()) {
                confirmacion = true;
            }
            if (confirmacion) {
                //Armar data
                var form_data = new FormData();
                form_data.append(token_name, token_hash);
                form_data.append("clave", form_cambio_clave.clave.campo.val());

                //Realizar consulta
                getAjaxFormData(form_data, base_url + 'home/actualizarClave').then(function (result) {
                    var data = JSON.parse(result);

                    if (data.url) {
                        _toastr("success", "Cambio realizado correctamente", true);
                        window.location.href = data.url;
                    } else {
                        mostrarErrores(data.errores);
                    }
                }).fail(function (result) {

                }).always(function (result) {
                    $("#cambio_clave-form_btn-submit").prop("disabled", false);
                });
            } else {
                mostrarErrores(["'Contraseña' y 'Confirmar contraseña' deben ser iguales"]);
            }
        } else {
            $("#cambio_clave-form_btn-submit").prop("disabled", false);
            mostrarErrores(validacion.errores);
        }

        }
    });
}

function cambiarClaveFormulario() {
    $(form_cambio_clave_olvido.id_form).on("submit", function (e) {
    });
}

function enviarFormulario() {
    $("#login-form").on("submit", function (e) {
        e.preventDefault();
        //Bloquear btn
        $("#login-form_btn-submit").prop("disabled", true);

        //Validar campos
        var validacion = validarFormulario(form_ingreso);

        if (validacion.estado) {
            //Armar data
            var form_data = new FormData();
            form_data.append(token_name, token_hash);

            var check_tipo = parseInt($("input[name='login-form-ingreso']:checked").val());


            form_data.append("tipo", check_tipo);
            check_tipo=1;

            switch (check_tipo) {
                case 1:
                    form_data.append("correo", form_ingreso.correo.campo.val());
                    break;
                default:

                    break;
            }


            form_data.append("clave", form_ingreso.clave.campo.val());
            var recordar = false;
            if ($("#recordar").prop("checked")) {
                recordar = true;
            }
            form_data.append("recordar", recordar);
            //Realizar consulta
            getAjaxFormData(form_data, base_url + 'home/ingresar').then(function (result) {
                var data = JSON.parse(result);
                if (data.url) {
                    window.location.href = data.url;
                } else {
                    mostrarErrores(data.errores);
                }

            }).fail(function (result) {

            }).always(function (result) {
                $("#login-form_btn-submit").prop("disabled", false);
            });
        } else {
            $("#login-form_btn-submit").prop("disabled", false);
            mostrarErrores(validacion.errores);
        }
    });
}

function ajustarTipoClave() {
    $("#clave,#cambio_clave,#cambio_clave_confirmar").prop('type', 'password');
}

function visualizarClave() {
    $(".clave-visualizar").css("pointer-events", 'initial');
    $(".clave-visualizar").on("click", function (e) {
        e.stopPropagation();
        var icono = $(this);
        var campo = $("#" + $(this).data("campo"));
        if (icono.hasClass("fa-eye")) {
            icono.removeClass("fa-eye").addClass("fa-eye-slash");
            campo.prop('type', 'password');
        } else {
            icono.removeClass("fa-eye-slash").addClass("fa-eye");
            campo.prop('type', 'text');
        }
    });
}

function validarInput() {
    $("#correo,#clave,#ruc").on("input", function () {
        var campo = $("#" + this.id);
        var tipo = campo.data("tipo");
        var valor = campo.val();
        switch (tipo) {
            case "correo":
                valor = valor.replace(/[^ñÑáÁéÉíÍóÓúÚA-Za-z0-9_\-.+@]/ig, "");
                break;
            case "clave":
                valor = valor.replace(/[^a-z0-9\_\-\!\¡\#\¿\?\*\+\(\)\{\}\$\%\&\[\]]/ig, "");
                break;
            case "ruc":
                valor = valor.replace(/[^0-9]/ig, "");
                break;
        }
        campo.val(valor);
    });
}

function validarFormulario(formulario) {
    //Declaración de variables
    var validacion = {estado: true, errores: []};
    var nombre = "";
    var valor = "";
    var largo = 0;
    var patron = null;

    //Recorrer elementos del formulario
    for (var campo in formulario) {

        //Declarar variables, "trimear" valor y traspasarlo al input
        nombre = formulario[campo].campo.data("nombre");
        tipo = formulario[campo].campo.data("tipo");
        valor = formulario[campo].campo.val().trim();
        if (tipo == "correo") {
            formulario[campo].campo.prop("type", "text");
            formulario[campo].campo.val(valor);
            formulario[campo].campo.prop("type", "email");
        } else {
            formulario[campo].campo.val(valor);
        }


        //Validar campos
        try {
            if (valor != "") {
                largo = valor.length;
                switch (tipo) {
                    case "correo":
                        patron = /^([ñÑáÁéÉíÍóÓúÚA-Za-z0-9_\-.+])+@([ñÑáÁéÉíÍóÓúÚA-Za-z0-9_\-.])+\.([ñÑáÁéÉíÍóÓúÚA-Za-z]{2,})$/;
                        if (!patron.test(valor)) {
                            throw("El campo '" + nombre + "' no es un correo válido");
                        }
                        break;
                    case "clave":
                        if (6 <= largo && largo <= 12) {
                            patron = /[a-z0-9\_\-\!\¡\#\¿\?\*\+\(\)\{\}\$\%\&\[\]]/;
                            if (!patron.test(valor)) {
                                throw("El campo '" + nombre + "' no es un correo válido");
                            }
                        } else {
                            throw("El campo '" + nombre + "' debe poseer de 6 a 12 elementos");
                        }
                        break;

                    default:
                        throw("El campo '" + nombre + "' no pudo ser validado");
                }
            } else {
                throw("El campo '" + nombre + "' no puede quedar en blancos");
            }
        } catch (error) {
            validacion.estado = false;
            validacion.errores.push(error);
        }
    }
    return validacion;
}

function mostrarErrores(errores) {
    if (errores.length > 0) {
        /*for (let error of errores) {
            _toastr("error", error, false);
        }*/
        for (var i = 0; i < errores.length; i++) {
             _toastr("error", errores[i], false);
        }
    }
}

function isNumber(n) {
    return !isNaN(parseFloat(n)) && isFinite(n);
}

function rutValidacion(variable) {
    let estado = false;
    let patron = /[0-9]{1,2}[.]{1}[0-9]{3}[.]{1}[0-9]{3}[-]{1}[0-9kK]{1}/;
    if ((variable.length == 11 || variable.length == 12) && patron.test(variable)) {
        variable = variable.replace(/[^0-9k\-]/ig, "");
        var Fn = {
            // Valida el rut con su cadena completa "XXXXXXXX-X"
            validaRut: function (rutCompleto) {
                rutCompleto = rutCompleto.replace("‐", "-");
                if (!/^[0-9]+[-|‐]{1}[0-9kK]{1}$/.test(rutCompleto))
                    return false;
                var tmp = rutCompleto.split('-');
                var digv = tmp[1];
                var rut = tmp[0];
                if (digv == 'K')
                    digv = 'k';

                return (Fn.dv(rut) == digv);
            },
            dv: function (T) {
                var M = 0, S = 1;
                for (; T; T = Math.floor(T / 10))
                    S = (S + T % 10 * (9 - M++ % 6)) % 11;
                return S ? S - 1 : 'k';
            }
        };
        if (Fn.validaRut(variable)) {
            estado = true;
        }
    }

    return estado;
}



  // Log in con Facebook

  //cargar e inicializar el SDK
  window.fbAsyncInit = function() {


    logout();
    FB.init({
      appId      : ID_CLIENTE_FACEBOOK,
      state      : false,
      cookie     : true,
      xfbml      : true,
      version    : 'v5.0'
    });

      
    FB.AppEvents.logPageView();   

    FB.getLoginStatus(function(response) {
        // console.log("status actual : ");
        // console.log(response);

        statusChangeCallback(response);
    });
  };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "https://connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));

   
function statusChangeCallback(response) {  // es llamado cuando FB.getLoginStatus().on.
    if (response.status === 'connected') {   // Logged en la  pagina y Facebook.
        logout();
    } 
}


function login(e) {
    e.preventDefault();
  FB.login(function(response) {
      

    // console.log("entró a fb login");
    if (response.status === 'connected') {

        // console.log('Welcome!  Fetching your information.... ');
        FB.api('/me?fields=id,name,email', function(response) {

            var form_data = new FormData();
                
            form_data.append(token_name, token_hash);
            // console.log("correo : ");
            // console.log(response.email);
            logout();
            if (response.email) {
                form_data.append("correo",  response.email);
            
                getAjaxFormData(form_data, base_url + 'home/verificarEmail').then(function (result) {        
                    var data = JSON.parse(result);
                    if (data.url) {
                        window.location.href = data.url;
                    } else {
                        mostrarErrores(data.errores);  
                    }
                });
            }else mostrarErrores(["Es necesario que proporcione su dirección de correo electrónico"]);
            // console.log("conectado");
            // console.log(response);
         });
    
       
    } else {
        logout();
        // console.log("no conectado");  
    }
    }, { auth_type: '', scope: 'public_profile, email'}); // auth_type: reauthenticate, scopes: read_stream,publish_stream,publish_actions,read_friendlists


    logout();
}

function logout(response){
    
    //eliminar permisos, para que sean vueltos a solicitar
    FB.api('/me/permissions', 'delete', function(response) {});

    FB.getLoginStatus(function(response) {
        if (response && response.status === 'connected') {
            FB.logout(function(response) {
                location.reload();
            });
        }
    });

}
// Fin Log in con Facebook




// Log-in con Gmail
function onSignIn(googleUser) {
    
    if (clicked) {
        //By default, the fetch_basic_profile parameter of gapi.auth2.init() is set to true, which will automatically add 'email profile openid' as scope.
        // gapi.load('auth2', function() {
        //     gapi.auth2.init({
        //         client_id: 'filler_text_for_client_id.apps.googleusercontent.com',
        //         scope: 'email'
        //     });
        // });

        var profile = googleUser.getBasicProfile();
        let correo = profile.getEmail();

        //var id_token = googleUser.getAuthResponse().id_token;


        //enviar email al controlador

        var form_data = new FormData();
        form_data.append(token_name, token_hash);
        form_data.append("correo", correo);
         
    
        getAjaxFormData(form_data, base_url + 'home/verificarEmail').then(function (result){
             
            var data = JSON.parse(result);
            if (data.url) {
                window.location.href = data.url;
            } else {
                mostrarErrores(data.errores);
            }
        });   
    }

    //borrar texto auto-generado por Google
    $('.abcRioButtonContents').children()[1].innerHTML="";
}

//fin log-in con Gmail


