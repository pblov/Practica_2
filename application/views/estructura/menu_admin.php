<?php
$dashboard = "";
$usuarios = "";
$empresas = "";
$canales = "";
$categorias = "";
$tipo_publicidad = "";
$ver_publicidad = "";
$publicidad = "";


switch ($active) {
   case 'dashboard':
       $dashboard = "current";
       break;
   case 'usuarios':
       $usuarios = "current";
       break;
    case 'empresas':
        $empresas = "current";
        break;
    case 'canales':
        $canales = "current";
        break;
    case 'categorias':
        $categorias = "current";
        break;

    case 'ver_publicidad':
        $publicidad = "current";
        $ver_publicidad = "current";
        $tipo_publicidad = "";
        break;
    case 'tipo_publicidad':
        $tipo_publicidad = "current";
        $publicidad = "current";
        $ver_publicidad = "";
        break;

}
?>

<nav class="main-nav" role="navigation">

    <!-- Mobile menu toggle button (hamburger/x icon) -->
    <input id="main-menu-state" type="checkbox" />
    <label class="main-menu-btn" for="main-menu-state">
        <span class="main-menu-btn-icon"></span>
    </label>

    <!-- Sample menu definition -->
    <ul id="main-menu" class="sm sm-blue">
        
            <li><a href="<?= base_url() ?>dashboard" class="border border-primary <?=$dashboard?>"><i class="ti-dashboard mx-5"></i>DASHBOARD</a>
            <li class=""><a href="<?= base_url() ?>usuarios" class="border border-primary <?=$usuarios?>"><i class="ti-user mx-5"></i>USUARIOS</a>
            <li class=""><a href="<?= base_url() ?>empresas" class="border border-primary <?=$empresas?>"><i class="ti-briefcase mx-5"></i>EMPRESAS</a>
            <li class=""><a href="<?= base_url() ?>canales" class="border border-primary <?=$canales?>"><i class="ti-control-shuffle mx-5"></i>CANALES</a>
            <li class=""><a href="<?= base_url() ?>categorias" class="border border-primary <?=$categorias?>"><i class="ti-layout-list-thumb mx-5"></i>CATEGORÍAS</a>
            <!-- <li class=""><a href="<?= base_url() ?>publicidad" class="border border-primary <?=$publicidad?>"><i class="ti-layout-list-thumb mx-5"></i>PUBLICIDAD</a> -->


            <li class=""><a href="#" class="  border border-primary <?=$publicidad?>"><i class="ti-direction mx-5"></i>PUBLICIDAD</a>
                <ul>
                    <li class=""><a href="<?= base_url() ?>publicidad" class="  border border-primary  <?=$ver_publicidad?>"><i class="ti-arrow-circle-right mr-10"></i>VER PUBLICIDAD</a></li>
                    <li class=""><a href="<?= base_url() ?>publicidad/tipo" class="border border <?=$tipo_publicidad?> "><i class="ti-arrow-circle-right mr-10"></i>TIPO</a></li>
                </ul>
            </li>

            

    


    </ul>

            



</nav>

