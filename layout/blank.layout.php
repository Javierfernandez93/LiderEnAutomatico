<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="author" content="<?php echo HCStudio\Connection::$proyect_name;?> all rights reserved 2016">
    %metadata%
    <title>%title%</title>
    <!--     Fonts and icons     -->
    <!-- <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" /> -->
    <!-- Nucleo Icons -->
    <link rel="stylesheet" href="<?php echo HCStudio\Connection::getMainPath();?>/src/css/nucleo-icons.css" />
    <link rel="stylesheet" href="<?php echo HCStudio\Connection::getMainPath();?>/src/css/nucleo-svg.css" />
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="<?php echo HCStudio\Connection::getMainPath();?>/src/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?php echo HCStudio\Connection::getMainPath();?>/src/css/general.css" />
    <link rel="stylesheet" href="<?php echo HCStudio\Connection::getMainPath();?>/src/css/nucleo-svg.css" />
    <!-- CSS Files -->
    
    <link id="pagestyle" href="<?php echo HCStudio\Connection::getMainPath();?>/src/css/soft-ui-dashboard.css?v=1.0.6" rel="stylesheet" />
    %css_scripts%
  </head>
  <body>
    %content%

    <script src="<?php echo HCStudio\Connection::getMainPath();?>/src/js/core/bootstrap.bundle.min.js" type="text/javascript"></script>
    <!--   Core JS Files   -->
    <script src="<?php echo HCStudio\Connection::getMainPath();?>/src/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="<?php echo HCStudio\Connection::getMainPath();?>/src/js/plugins/smooth-scrollbar.min.js"></script>
    <script src="<?php echo HCStudio\Connection::getMainPath();?>/src/js/plugins/chartjs.min.js"></script>
    <script src="<?php echo HCStudio\Connection::getMainPath();?>/src/js/42d5adcbca.js" type="text/javascript"></script>
    <script src="<?php echo HCStudio\Connection::getMainPath();?>/src/js/jquery-3.1.1.js" type="text/javascript"></script>
    <script src="<?php echo HCStudio\Connection::getMainPath();?>/src/js/alertCtrl.js?t=1" type="text/javascript"></script>
    <script src="<?php echo HCStudio\Connection::getMainPath();?>/src/js/general.js?t=2" type="text/javascript"></script>

    <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
        var options = {
            damping: '0.5'
        }
        Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
    </script>
    <!-- Github buttons -->
    <script async defer src="<?php echo HCStudio\Connection::getMainPath();?>/src/js/buttons.js"></script>
    <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="<?php echo HCStudio\Connection::getMainPath();?>/src/js/soft-ui-dashboard.min.js?v=1.0.6"></script>
    
    <script src="<?php echo HCStudio\Connection::getMainPath();?>/src/js/vue.js"></script>
    
    %js_scripts%
  </body>
</html>