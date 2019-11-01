
<!-- jQuery 2.2.0 -->
<script src="plugins/jQuery/jQuery-2.2.0.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="plugins/bootstrap/js/bootstrap.min.js"></script>
<!-- Slimscroll -->
<script src="plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="plugins/fastclick/fastclick.min.js"></script>

<script src="plugins/sweetalert/dist/sweetalert.min.js"></script>

<script src="plugins/pace/pace.min.js"></script>
<!-- AdminLTE App -->
<script src="plugins/plantilla/dist/js/app.min.js"></script>

<script src="js/app.min.js"></script>

<?php if(isset($alert)) alert($alert); ?>

<script type="text/javascript">

$(function() {

    $("button#salir-sistema").click(function() {
      swal({
        title: "Aviso!",
        text: "¿Desea salir del sistema?",
        type: "info",
        showCancelButton: true,
        closeOnConfirm: false
      },function(isConfirm){
        if (isConfirm){
          url = 'index.php?cmd=cerrar';
          $(location).attr('href', url);
        }
      });
    });

    $(window).scroll(function() {
        if ($(this).scrollTop() > 100) {
            $('a.scroll-top').fadeIn();
        } else {
            $('a.scroll-top').fadeOut();
        }
    });

    $('a.scroll-top').click(function() {
        $("html, body").animate({scrollTop: 0}, 600);
        return false;
    });

});
</script>
