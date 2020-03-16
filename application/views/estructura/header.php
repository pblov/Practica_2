<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" href="<?=base_url()?>assets/images/favicon.ico">

        <title><?= $titulo ?> - IMPACT</title>

        <!-- Bootstrap 4.0-->
        <link rel="stylesheet" href="<?= base_url() ?>assets/style/vendor_components/bootstrap/dist/css/bootstrap.css">
 
        <!-- theme style -->
        <link rel="stylesheet" href="<?= base_url() ?>assets/style/css/horizontal-menu.css">	
        
        <!-- theme style -->
        <link rel="stylesheet" href="<?= base_url() ?>assets/style/css/style.css">	
        
        
        <!-- CrmX Admin skins -->
        <link rel="stylesheet" href="<?= base_url() ?>assets/style/css/skin_color.css">	
        
         <!-- daterange picker -->
         <link rel="stylesheet" href="<?=base_url()?>assets/style/vendor_components/bootstrap-daterangepicker/daterangepicker.css">

   
        
        
        <!-- Toast -->
        <link  rel="stylesheet" type="text/css" href="<?=base_url()?>assets/style/vendor_components/jquery-toast-plugin-master/src/jquery.toast.css">
        
        <!-- Loading --> 
        <link  rel="stylesheet" type="text/css" href="<?=base_url()?>assets/libraries/loading/jquery.loading.min.css">


        <link  rel="stylesheet" type="text/css" href="<?=base_url()?>assets/css/preloader.css">


        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

        <?php
       
    if (array_key_exists('libcss',$datalibrary)) {
        foreach ($datalibrary['libcss'] as $vista) {
            $this->load->view($vista);
        }
    } else {
        $this->load->view('libcss');
    }
    ?>

             
 
        <!-- END Page Level CSS-->
        <!-- BEGIN Custom CSS-->
        <link rel="stylesheet" href="<?= base_url() ?>assets/css/custom.css">	
         <!-- END Custom CSS-->

    </head>
    
    <div id="preloader">
        <div id="status"></div>
    </div>
    
<!--    <style>
    
    .theme-leaf .bg-gradient-leaf, 
    .theme-leaf .art-bg,
    .theme-leaf.fixed .main-header,
    .theme-leaf.onlyheader .main-header,
    .bg-gradient-leaf{
            background: #80BC00 !important;
    }
    
</style>-->

   