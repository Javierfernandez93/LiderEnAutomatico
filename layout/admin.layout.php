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
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link rel="stylesheet" href="../../src/css/nucleo-icons.css" />
    <link rel="stylesheet" href="../../src/css/nucleo-svg.css" />
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="../../src/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../src/css/general.css?t=1" />
    <link rel="stylesheet" href="../../src/css/nucleo-svg.css" />
    <!-- CSS Files -->
    
    <link id="pagestyle" href="../../src/css/soft-ui-dashboard.css?v=1.0.6" rel="stylesheet" />
    %css_scripts%
</head>
<body class="g-sidenav-show  bg-gray-100">
    <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 "
        id="sidenav-main">
        <div class="sidenav-header">
            <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
            <a class="navbar-brand m-0" href=" https://demos.grandcapital.found/soft-ui-dashboard/pages/dashboard.html "
                target="_blank">
                <img src="../../src/img/logo.png?t=4.1.2" class="navbar-brand-" alt="main_logo">
                
            </a>
        </div>
        <hr class="horizontal dark mt-0">
        <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
            <ul class="navbar-nav">

                <?php if($UserSupport->hasPermission('list_dash')) { ?>
                    <li class="nav-item">
                        <a class="nav-link <?php if(in_array($route,[JFStudio\Router::AdminDash,JFStudio\Router::AdminStats])){?>active<?php } ?>"
                            href="../../apps/admin">
                            <i class="bi bi-cup"></i>
                            <span class="nav-link-text ms-1"><?php echo JFStudio\Router::getName(JFStudio\Router::AdminDash);?></span>
                        </a>
                    </li>
                <?php } ?>
               
                <?php if($UserSupport->hasPermission('list_users')) { ?>
                    <li class="nav-item">
                        <a class="nav-link <?php if(in_array($route,[JFStudio\Router::AdminUsers,JFStudio\Router::AdminDeposits,JFStudio\Router::AdminUserEdit,JFStudio\Router::AdminUserAdd,JFStudio\Router::AdmiActivation])){?>active<?php } ?>"
                            href="../../apps/admin-users">
                            <i class="bi bi-people-fill"></i>
                            <span class="nav-link-text ms-1"><?php echo JFStudio\Router::getName(JFStudio\Router::AdminUsers);?></span>
                        </a>
                    </li>
                <?php } ?>
                <?php if($UserSupport->hasPermission('list_administrators')) { ?>
                    <li class="nav-item">
                        <a class="nav-link <?php if(in_array($route,[JFStudio\Router::AdminAdministrators,JFStudio\Router::AdminAdministratorsAdd,JFStudio\Router::AdminAdministratorsEdit])){?>active<?php } ?>" href="../../apps/admin-administrators">
                            <i class="bi bi-people-fill"></i>
                            <span class="nav-link-text ms-1"><?php echo JFStudio\Router::getName(JFStudio\Router::AdminAdministrators);?></span>
                        </a>
                    </li>
                <?php } ?>
                <li class="nav-item d-none">
                    <a class="nav-link <?php if($route == JFStudio\Router::AdminActivations){?>active<?php } ?>" href="../../apps/admin-activations">
                        <i class="bi bi-credit-card-2-back-fill"></i>
                        <span class="nav-link-text ms-1"><?php echo JFStudio\Router::getName(JFStudio\Router::AdminActivations);?></span>
                    </a>
                </li>

                <?php if($UserSupport->hasPermission('list_transactions')) { ?>
                    <li class="nav-item">
                        <a class="nav-link <?php if($route == JFStudio\Router::AdminTransactions){?>active<?php } ?>" href="../../apps/admin-transactions">
                            <i class="bi bi-credit-card-2-back-fill"></i>
                            <span class="nav-link-text ms-1"><?php echo JFStudio\Router::getName(JFStudio\Router::AdminTransactions);?></span>
                        </a>
                    </li>
                <?php } ?>
                
                <?php if($UserSupport->hasPermission('list_transactions')) { ?>
                    <li class="nav-item">
                        <a class="nav-link <?php if($route == JFStudio\Router::AdminTransactionsList){?>active<?php } ?>" href="../../apps/admin-transactions/list">
                            <i class="bi bi-currency-exchange"></i>
                            <span class="nav-link-text ms-1"><?php echo JFStudio\Router::getName(JFStudio\Router::AdminTransactionsList);?></span>
                        </a>
                    </li>
                <?php } ?>

                <?php if($UserSupport->hasPermission('list_brokers')) { ?>
                    <li class="nav-item">
                        <a class="nav-link <?php if(in_array($route,[JFStudio\Router::AdminBrokers,JFStudio\Router::AdminBrokersEdit,JFStudio\Router::AdminBrokersAdd,JFStudio\Router::AdminBrokersCapitals])){?>active<?php } ?>" href="../../apps/admin-brokers">
                            <i class="bi bi-currency-bitcoin"></i>
                            <span class="nav-link-text ms-1"><?php echo JFStudio\Router::getName(JFStudio\Router::AdminBrokers);?></span>
                        </a>
                    </li>
                <?php } ?>


                <li class="nav-item mt-3">
                    <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Herramientas</h6>
                </li>

                <?php if($UserSupport->hasPermission('list_tools')) { ?>
                    <li class="nav-item">
                        <a class="nav-link <?php if(in_array($route,[JFStudio\Router::AdminToolsAdd,JFStudio\Router::AdminToolsEdit,JFStudio\Router::AdminTools])){?>active<?php } ?>" href="../../apps/admin-tools">
                            <i class="bi bi-tools"></i>
                            <span class="nav-link-text ms-1"><?php echo JFStudio\Router::getName(JFStudio\Router::AdminTools);?></span>
                        </a>
                    </li>
                <?php } ?>
                
                <?php if($UserSupport->hasPermission('list_notices')) { ?>
                    <li class="nav-item">
                        <a class="nav-link <?php if(in_array($route,[JFStudio\Router::AdminNoticesAdd,JFStudio\Router::AdminNoticesEdit,JFStudio\Router::AdminNotices])){?>active<?php } ?>" href="../../apps/admin-news">
                            <i class="bi bi-newspaper"></i>
                            <span class="nav-link-text ms-1"><?php echo JFStudio\Router::getName(JFStudio\Router::AdminNotices);?></span>
                        </a>
                    </li>
                <?php } ?>
                
                <?php if($UserSupport->hasPermission('list_payment_methods')) { ?>
                    <li class="nav-item">
                        <a class="nav-link <?php if(in_array($route,[JFStudio\Router::AdminPaymentMethods])){?>active<?php } ?>" href="../../apps/admin-payment-methods">
                            <i class="bi bi-credit-card"></i>
                            <span class="nav-link-text ms-1"><?php echo JFStudio\Router::getName(JFStudio\Router::AdminPaymentMethods);?></span>
                        </a>
                    </li>
                <?php } ?>

                <li class="nav-item mt-3">
                    <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Sesión</h6>
                </li>
                
                        

                <li class="nav-item">
                    <a class="nav-link" href="?adminLogout=true">
                        <i class="bi bi-forward-fill"></i>
                        <span class="nav-link-text ms-1">Cerrar sesión</span>
                    </a>
                </li>
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
                <div class="collapse navbar-collapse me-md-0 me-sm-4 mt-sm-0 mt-2" id="navbar">
                    <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                        
                    </div>
                    <ul class="navbar-nav justify-content-end">
                        <li class="nav-item d-flex pe-3 align-items-center">
                            <a href="javascript:;" class="nav-link  font-weight-bold px-0">

                                <span class="d-sm-inline d-none"></span>
                                <!-- <img src="<?php echo $UserLogin->_data['user_account']['image'];?>" class="avatar avatar-sm me-3" alt="<?php echo $UserLogin->_data['user_data']['names'];?>">
                                <a href="../../apps/backoffice"><?php echo $UserLogin->_data['user_data']['names'];?></a> -->
                            </a>
                        </li>
                        <li class="nav-item d-xl-none ps-3 pe-0 d-flex align-items-center">
                            <a href="javascript:;" class="nav-link  p-0">
                            </a><a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                                <div class="sidenav-toggler-inner">
                                    <i class="sidenav-toggler-line"></i>
                                    <i class="sidenav-toggler-line"></i>
                                    <i class="sidenav-toggler-line"></i>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        %content%

        <footer class="container footer pt-3 fixed-bottom">
            <div class="container-fluid">
                <div class="row align-items-center justify-content-center">
                    <div class="col-auto text-center">
                        <div class="copyright text-center text-sm text-muted text-lg-start">
                            © <script>
                            document.write(new Date().getFullYear())
                            </script>,
                            made with <i class="fa fa-heart"></i> by
                            <a href="https://libertadenautomatico.com/" class="font-weight-bold" target="_blank">Grand
                                Capital - admin site</a>
                            for a better web.
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </main>

    <script src="../../src/js/core/bootstrap.bundle.min.js" type="text/javascript"></script>
    <!--   Core JS Files   -->
    <script src="../../src/js/plugins/perfect-scrollbar.min.js" type="text/javascript"></script>
    <script src="../../src/js/plugins/smooth-scrollbar.min.js" type="text/javascript"></script>
    <script src="../../src/js/plugins/chartjs.min.js" type="text/javascript"></script>
    <script src="../../src/js/42d5adcbca.js" type="text/javascript"></script>
    <script src="../../src/js/jquery-3.1.1.js" type="text/javascript"></script>
    <script src="../../src/js/general.js?t=3" type="text/javascript"></script>
    <script src="../../src/js/alertCtrl.js?t=1" type="text/javascript"></script>

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