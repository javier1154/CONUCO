<!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

      <div class="user-panel">
        
        <div class="image text-center">
          <img src="<?php echo $_SESSION[ 'CENSOimagen' ]; ?>" class="img-circle" alt="User Image">
        </div>
        <div class="info nombre">
          <p><?php echo $_SESSION["CENSOnombres"]; ?></p>

          <div class="salir">
            <button id="salir-sistema" class="btn btn-danger btn-block btn-sm"><b>Salir <i class="fa fa-sign-out"></i></b></button>
          </div>
        </div>
      </div>
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
        <li class="header">Panel Administrador</li>
        <li class="inicio"><a href="inicio.php"><i class="fa fa-home"></i> <span>Inicio</span></a></li>
        <li class="comunas"><a href="comunas.php"><i class="fa fa-cubes"></i> <span>Comunas</span></a></li>
        <li class="consejos"><a href="consejos.php"><i class="fa fa-cube"></i> <span>Consejos</span></a></li>
        <li class="productores"><a href="productores.php"><i class="fa fa-users"></i> <span>Productores</span></a></li>
        <li class="rubros"><a href="rubros.php"><i class="fa fa-shopping-basket"></i> <span>Rubros</span></a></li>
        <li class="clasificaciones"><a href="clasificaciones.php"><i class="fa fa-list-alt"></i> <span>Clasificaciones</span></a></li>
        <li class="permisologias"><a href="permisologias.php"><i class="fa fa-tags"></i> <span>Permisologías</span></a></li>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>