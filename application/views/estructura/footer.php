<footer class="main-footer">
    <div class="pull-right d-none d-sm-inline-block">
        <ul class="nav nav-primary nav-dotted nav-dot-separated justify-content-center justify-content-md-end">
            
            <li class="nav-item">
                <a class="nav-link" href="https://impact.cl">Creado por Impact</a>
            </li>
        </ul>
    </div>
    &copy; 2019 <a href="">IMPACT</a>. All Rights Reserved.
</footer>



<!-- Add the sidebar's background. This div must be placed immediately after the control sidebar -->
<div class="control-sidebar-bg"></div>

</div>
<!-- ./wrapper -->






<script>
    var base_url = "<?= base_url(); ?>";
</script>




<!-- jQuery 3 -->
<script src="<?= base_url() ?>assets/style/vendor_components/jquery-3.3.1/jquery-3.3.1.js"></script>

<!-- fullscreen -->
<script src="<?= base_url() ?>assets/style/vendor_components/screenfull/screenfull.js"></script>

<!-- popper -->
<script src="<?= base_url() ?>assets/style/vendor_components/popper/dist/popper.min.js"></script>


<!-- Bootstrap 4.0-->
<script src="<?= base_url() ?>assets/style/vendor_components/bootstrap/dist/js/bootstrap.js"></script>	

<!-- Slimscroll -->
<script src="<?= base_url() ?>assets/style/vendor_components/jquery-slimscroll/jquery.slimscroll.js"></script>

<!-- FastClick -->
<script src="<?= base_url() ?>assets/style/vendor_components/fastclick/lib/fastclick.js"></script>

 


<!-- CrmX Admin App -->
<script src="<?= base_url() ?>assets/style/js/jquery.smartmenus.js"></script>
<script src="<?= base_url() ?>assets/style/js/menus.js"></script>
<script src="<?= base_url() ?>assets/style/js/template.js"></script>



<!-- daterangepicker -->
<script src="<?=base_url()?>assets/style/vendor_components/moment/min/moment.min.js"></script>
<script src="<?=base_url()?>assets/style/vendor_components/bootstrap-daterangepicker/daterangepicker.js"></script>

 <!-- Toast -->
<script src="<?= base_url() ?>assets/style/vendor_components/jquery-toast-plugin-master/src/jquery.toast.js" type="text/javascript"></script>

 <script src="<?=base_url()?>assets/js/preloader.js" type="text/javascript"></script>


<!-- Loading -->
<script src="<?= base_url() ?>assets/libraries/loading/jquery.loading.min.js" type="text/javascript"></script>

<?php
       
    // $this->load->view("tipo_libjs")
    if (array_key_exists('libjs',$datalibrary)) {
        
        foreach ($datalibrary['libjs'] as $vista) {

            $this->load->view($vista);
        }
    } else {
        $this->load->view('libjs');
    }
    ?>

<script>
    $(document).ready(function () {
            $('body').css("overflow", "");
        });
  var token_name = '<?= $this->security->get_csrf_token_name(); ?>';
  var token_hash = '<?= $this->security->get_csrf_hash(); ?>';
</script>
</body>
</html>
