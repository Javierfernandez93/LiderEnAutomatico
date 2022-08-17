<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="../../src/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../../src/img/favicon.png">
    <title>
        %title%
    </title>
    <!--     Fonts and icons     -->
    <!-- <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" /> -->
    <!-- Nucleo Icons -->
    <link rel="stylesheet" href="../../src/css/nucleo-icons.css" />
    <link rel="stylesheet" href="../../src/css/nucleo-svg.css" />
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="../../src/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../src/css/general.css" />
    <link rel="stylesheet" href="../../src/css/nucleo-svg.css" />
    <!-- CSS Files -->
    
    <link id="pagestyle" href="../../src/css/soft-ui-dashboard.css?v=1.0.6" rel="stylesheet" />
    %css_scripts%
</head>
<body class="g-sidenav-show  bg-white">
    <aside class="sidenav navbar navbar-vertical navbar-expand-xs fixed-start border-end"
        id="sidenav-main">
        <div class="sidenav-header">
            <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
            <a class="navbar-brand m-0" href=""
                target="_blank">
                <img src="../../src/img/logo.png" class="navbar-brand-" alt="main_logo">
            </a>
        </div>
        <hr class="horizontal dark mt-0">
        <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
            <ul class="navbar-nav">
                <?php if($UserLogin->_loaded) { ?>
                    <li class="nav-item">
                        <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Negocio</h6>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php if(in_array($route,[JFStudio\Router::Backoffice,JFStudio\Router::Notifications,JFStudio\Router::AddFunds])){?>active<?php } ?>"
                            href="../../apps/backoffice">
                            <i class="bi bi-cup"></i>
                            <span class="nav-link-text ms-1"><?php echo JFStudio\Router::getName(JFStudio\Router::Backoffice);?></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php if($route == JFStudio\Router::Referrals){?>active<?php } ?>" href="../../apps/referrals">
                            <i class="bi bi-people-fill"></i>
                            <span class="nav-link-text ms-1"><?php echo JFStudio\Router::getName(JFStudio\Router::Referrals);?></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php if($route == JFStudio\Router::Range){?>active<?php } ?>" href="../../apps/backoffice/range">
                            <i class="bi bi-bookmark-star-fill"></i>
                            <span class="nav-link-text ms-1"><?php echo JFStudio\Router::getName(JFStudio\Router::Range);?></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php if(in_array($route,[JFStudio\Router::ListFunds,JFStudio\Router::Stripe,JFStudio\Router::Invoice,JFStudio\Router::Registration])){?>active<?php } ?>" href="../../apps/wallet/allFunds">
                            <i class="bi bi-credit-card-2-back-fill"></i>
                            <span class="nav-link-text ms-1"><?php echo JFStudio\Router::getName(JFStudio\Router::ListFunds);?></span>
                        </a>
                    </li>
                    <li class="nav-item d-none">
                        <a class="nav-link <?php if($route == JFStudio\Router::Gains){?>active<?php } ?>" href="../../apps/gains">
                            <i class="bi bi-currency-exchange"></i>
                            <span class="nav-link-text ms-1"><?php echo JFStudio\Router::getName(JFStudio\Router::Gains);?></span>
                        </a>
                    </li>
                    <li class="nav-item d-none">
                        <a class="nav-link <?php if($route == JFStudio\Router::TradingView){?>active<?php } ?>" href="../../apps/trading">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <svg width="12px" height="12px" viewBox="0 0 43 36" version="1.1"
                                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                    <title>credit-card</title>
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <g transform="translate(-2169.000000, -745.000000)" fill="#FFFFFF"
                                            fill-rule="nonzero">
                                            <g transform="translate(1716.000000, 291.000000)">
                                                <g transform="translate(453.000000, 454.000000)">
                                                    <path class="color-background opacity-6"
                                                        d="M43,10.7482083 L43,3.58333333 C43,1.60354167 41.3964583,0 39.4166667,0 L3.58333333,0 C1.60354167,0 0,1.60354167 0,3.58333333 L0,10.7482083 L43,10.7482083 Z">
                                                    </path>
                                                    <path class="color-background"
                                                        d="M0,16.125 L0,32.25 C0,34.2297917 1.60354167,35.8333333 3.58333333,35.8333333 L39.4166667,35.8333333 C41.3964583,35.8333333 43,34.2297917 43,32.25 L43,16.125 L0,16.125 Z M19.7083333,26.875 L7.16666667,26.875 L7.16666667,23.2916667 L19.7083333,23.2916667 L19.7083333,26.875 Z M35.8333333,26.875 L28.6666667,26.875 L28.6666667,23.2916667 L35.8333333,23.2916667 L35.8333333,26.875 Z">
                                                    </path>
                                                </g>
                                            </g>
                                        </g>
                                    </g>
                                </svg>
                            </div>
                            <span class="nav-link-text ms-1"><?php echo JFStudio\Router::getName(JFStudio\Router::TradingView);?></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php if($route == JFStudio\Router::Wallet){?>active<?php } ?>" href="../../apps/wallet/">
                            <i class="bi bi-wallet2"></i>
                            <span class="nav-link-text ms-1"><?php echo JFStudio\Router::getName(JFStudio\Router::Wallet);?></span>
                        </a>
                    </li>
                    <li class="nav-item mt-5 d-none">
                        <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Trading</h6>
                    </li>
                    <li class="nav-item d-none">
                        <a class="nav-link  <?php if($route == JFStudio\Router::TradingView){?>active<?php } ?>"
                            href="../../apps/trading/">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <svg width="12px" height="12px" viewBox="0 0 46 42" version="1.1"
                                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                    <title>customer-support</title>
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <g transform="translate(-1717.000000, -291.000000)" fill="#FFFFFF"
                                            fill-rule="nonzero">
                                            <g transform="translate(1716.000000, 291.000000)">
                                                <g transform="translate(1.000000, 0.000000)">
                                                    <path class="color-background opacity-6"
                                                        d="M45,0 L26,0 C25.447,0 25,0.447 25,1 L25,20 C25,20.379 25.214,20.725 25.553,20.895 C25.694,20.965 25.848,21 26,21 C26.212,21 26.424,20.933 26.6,20.8 L34.333,15 L45,15 C45.553,15 46,14.553 46,14 L46,1 C46,0.447 45.553,0 45,0 Z">
                                                    </path>
                                                    <path class="color-background"
                                                        d="M22.883,32.86 C20.761,32.012 17.324,31 13,31 C8.676,31 5.239,32.012 3.116,32.86 C1.224,33.619 0,35.438 0,37.494 L0,41 C0,41.553 0.447,42 1,42 L25,42 C25.553,42 26,41.553 26,41 L26,37.494 C26,35.438 24.776,33.619 22.883,32.86 Z">
                                                    </path>
                                                    <path class="color-background"
                                                        d="M13,28 C17.432,28 21,22.529 21,18 C21,13.589 17.411,10 13,10 C8.589,10 5,13.589 5,18 C5,22.529 8.568,28 13,28 Z">
                                                    </path>
                                                </g>
                                            </g>
                                        </g>
                                    </g>
                                </svg>
                            </div>
                            <span class="nav-link-text ms-1"><span class="nav-link-text ms-1"><?php echo JFStudio\Router::getName(JFStudio\Router::TradingView); ?></span></span>
                        </a>
                    </li>
                <?php } ?>

                <li class="nav-item mt-4">
                    <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Herramientas</h6>
                </li>

                <li class="nav-item">
                    <a class="nav-link  <?php if($route == JFStudio\Router::Calculator){?>active<?php } ?>"
                        href="../../apps/tools/calculator">
                        <i class="bi bi-calculator"></i>
                        <span class="nav-link-text ms-1"><?php echo JFStudio\Router::getName(JFStudio\Router::Calculator); ?></span>
                    </a>
                </li>

                <?php if($UserLogin->_loaded) { ?>
                    <li class="nav-item mt-4">
                        <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Ajustes de cuenta</h6>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link  <?php if(in_array($route,[JFStudio\Router::Profile])){?>active<?php } ?>"
                            href="../../apps/backoffice/profile">
                            <i class="bi bi-person-circle"></i>
                            <span class="nav-link-text ms-1"><?php echo JFStudio\Router::getName(JFStudio\Router::Profile); ?></span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="?logout=true">
                            <i class="bi bi-door-open-fill"></i>
                            <span class="nav-link-text ms-1">Cerrar sesión</span>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </div>
        <div class="sidenav-footer mx-3 ">

        </div>
    </aside>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
                
        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl <?php if($floating_nav === true) { ?>bg-transparent position-absolute floating-nav w-100 z-index-2<?php } ?>">
            <div class="container-fluid py-1">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 ps-2 me-sm-6 me-5">
                        <li class="breadcrumb-item text-sm"><a class=" opacity-5" href="javascript:;">Páginas</a>
                        </li>
                        <li class="breadcrumb-item text-sm  active" aria-current="page">
                            <?php echo JFStudio\Router::getName($route);?>
                        </li>
                    </ol>
                    <h6 class=" font-weight-bolder ms-2"><?php echo JFStudio\Router::getName($route);?></h6>
                </nav>
                <?php if($UserLogin->_loaded) { ?>
                    <div class="collapse navbar-collapse me-md-0 me-sm-4 mt-sm-0 mt-2" id="navbar">
                        <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                            
                        </div>
                        <ul class="navbar-nav justify-content-end">
                            <li class="nav-item d-flex pe-3 align-items-center">
                                <a href="javascript:;" class="nav-link  font-weight-bold px-0">

                                    <span class="d-sm-inline d-none"></span>
                                    <img src="<?php echo $UserLogin->_data['user_account']['image'];?>" class="avatar avatar-sm me-3" alt="<?php echo $UserLogin->_data['user_data']['names'];?>">
                                    <a class="fw-semibold" href="../../apps/backoffice"><u><?php echo $UserLogin->_data['user_data']['names'];?></u></a>
                                </a>
                            </li>
                            <li class="nav-item d-xl-none ps-3 pe-0 d-flex align-items-center">
                                <a href="javascript:;" class="nav-link  p-0">
                                </a>
                                <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                                    <div class="sidenav-toggler-inner">
                                        <i class="sidenav-toggler-line"></i>
                                        <i class="sidenav-toggler-line"></i>
                                        <i class="sidenav-toggler-line"></i>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item dropdown px-2 d-flex align-items-center">
                                <a href="../../apps/backoffice/notifications" class="nav-link p-0" id="dropdownMenuButton"
                                    >
                                    <i class="fa fa-bell cursor-pointer" aria-hidden="true"></i>
                                </a>    
                                <!-- <a href="../../apps/backoffice/notifications" class="nav-link p-0" id="dropdownMenuButton"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa fa-bell cursor-pointer" aria-hidden="true"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end px-2 py-3 ms-n4"
                                    aria-labelledby="dropdownMenuButton">
                                    <li class="mb-2">
                                        <a class="dropdown-item border-radius-md" href="javascript:;">
                                            <div class="d-flex py-1">
                                                <div class="my-auto">
                                                    <img src="../assets/img/team-2.jpg" class="avatar avatar-sm me-3">
                                                </div>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="text-sm font-weight-normal mb-1">
                                                        <span class="font-weight-bold">New message</span> from Laur
                                                    </h6>
                                                    <p class="text-xs text-secondary mb-0">
                                                        <i class="fa fa-clock me-1" aria-hidden="true"></i>
                                                        13 minutes ago
                                                    </p>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="mb-2">
                                        <a class="dropdown-item border-radius-md" href="javascript:;">
                                            <div class="d-flex py-1">
                                                <div class="my-auto">
                                                    <img src="../assets/img/small-logos/logo-spotify.svg"
                                                        class="avatar avatar-sm bg-gradient-dark me-3">
                                                </div>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="text-sm font-weight-normal mb-1">
                                                        <span class="font-weight-bold">New album</span> by Travis Scott
                                                    </h6>
                                                    <p class="text-xs text-secondary mb-0">
                                                        <i class="fa fa-clock me-1" aria-hidden="true"></i>
                                                        1 day
                                                    </p>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item border-radius-md" href="javascript:;">
                                            <div class="d-flex py-1">
                                                <div class="avatar avatar-sm bg-gradient-secondary me-3 my-auto">
                                                    <svg width="12px" height="12px" viewBox="0 0 43 36" version="1.1"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        xmlns:xlink="http://www.w3.org/1999/xlink">
                                                        <title>credit-card</title>
                                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                            <g transform="translate(-2169.000000, -745.000000)"
                                                                fill="#FFFFFF" fill-rule="nonzero">
                                                                <g transform="translate(1716.000000, 291.000000)">
                                                                    <g transform="translate(453.000000, 454.000000)">
                                                                        <path class="color-background"
                                                                            d="M43,10.7482083 L43,3.58333333 C43,1.60354167 41.3964583,0 39.4166667,0 L3.58333333,0 C1.60354167,0 0,1.60354167 0,3.58333333 L0,10.7482083 L43,10.7482083 Z"
                                                                            opacity="0.593633743"></path>
                                                                        <path class="color-background"
                                                                            d="M0,16.125 L0,32.25 C0,34.2297917 1.60354167,35.8333333 3.58333333,35.8333333 L39.4166667,35.8333333 C41.3964583,35.8333333 43,34.2297917 43,32.25 L43,16.125 L0,16.125 Z M19.7083333,26.875 L7.16666667,26.875 L7.16666667,23.2916667 L19.7083333,23.2916667 L19.7083333,26.875 Z M35.8333333,26.875 L28.6666667,26.875 L28.6666667,23.2916667 L35.8333333,23.2916667 L35.8333333,26.875 Z">
                                                                        </path>
                                                                    </g>
                                                                </g>
                                                            </g>
                                                        </g>
                                                    </svg>
                                                </div>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="text-sm font-weight-normal mb-1">
                                                        Payment successfully completed
                                                    </h6>
                                                    <p class="text-xs text-secondary mb-0">
                                                        <i class="fa fa-clock me-1" aria-hidden="true"></i>
                                                        2 days
                                                    </p>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                </ul> -->
                            </li>
                        </ul>
                    </div>
                <?php } ?>
            </div>
        </nav>

        %content%

        <footer class="container footer pt-5 fixesd-bottom">
            <div class="container-fluid">
                <div class="row align-items-center justify-content-lg-between">
                    <div class="col-lg-6 mb-lg-0 mb-4">
                        <div class="copyright text-center text-sm text-muted text-lg-start">
                            © <script>
                            document.write(new Date().getFullYear())
                            </script>,
                            made with <i class="fa fa-heart"></i> by
                            <a href="https://grancapital.fund/" class="font-weight-bold" target="_blank">Libertad en Automático</a>
                            for a better web.
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <ul class="nav nav-footer justify-content-center justify-content-lg-end">
                            <li class="nav-item">
                                <a href="" class="nav-link text-muted" target="_blank">Libertad en Automático</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </footer>
    </main>

    <script src="../../src/js/core/bootstrap.bundle.min.js" type="text/javascript"></script>
    <!--   Core JS Files   -->
    <script src="../../src/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="../../src/js/plugins/smooth-scrollbar.min.js"></script>
    <script src="../../src/js/plugins/chartjs.min.js"></script>
    <script src="../../src/js/42d5adcbca.js" type="text/javascript"></script>
    <script src="../../src/js/jquery-3.1.1.js" type="text/javascript"></script>
    <script src="../../src/js/alertCtrl.js?t=1" type="text/javascript"></script>
    <script src="../../src/js/general.js?t=2" type="text/javascript"></script>

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
    <script async defer src="../../src/js/buttons.js"></script>
    <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="../../src/js/soft-ui-dashboard.min.js?v=1.0.6"></script>
    
    <script src="../../src/js/vue.js"></script>
    
    %js_scripts%

</body>

</html>