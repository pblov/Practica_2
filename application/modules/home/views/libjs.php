<script src="<?= base_url() ?>assets/js/utilidades.js"></script>

<script>
    var token_pw = "<?php echo (!empty($token_pw)?$token_pw:'')?>";
</script>

<script src="<?= base_url() ?>assets/js/modules/home/home.js"></script>


<!-- FACEBOOK LOG IN -->
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/es_LA/sdk.js#xfbml=1&version=v5.0&appId=<?php echo(ID_CLIENTE_FACEBOOK)?>&autoLogAppEvents=1"></script>

<!-- GOOGLE SIGN IN -->
<script src="https://apis.google.com/js/platform.js?onload=init" async defer></script>

 