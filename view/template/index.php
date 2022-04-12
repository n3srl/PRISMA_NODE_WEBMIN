<?php ?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>
            Orma

        </title>
        <script type="text/javascript" src="<?php echo $_SERVER['PATH_WEBROOT'] ?>/js/actions.js<?= _VERSION_ ?>"></script>


        <!-- Bootstrap -->
        <link href="<?php echo $_SERVER['PATH_WEBROOT'] ?>/ext_lib_fe/bootstrap/dist/css/bootstrap.min.css<?= _VERSION_ ?>" rel="stylesheet" type="text/css"/>
        <!-- Font Awesome -->
        <link href="<?php echo $_SERVER['PATH_WEBROOT'] ?>/ext_lib_fe/font-awesome/css/font-awesome.min.css<?= _VERSION_ ?>" rel="stylesheet">
        <!-- iCheck -->
        <link href="<?php echo $_SERVER['PATH_WEBROOT'] ?>/ext_lib_fe/iCheck/skins/flat/green.css<?= _VERSION_ ?>" rel="stylesheet">
        <!-- Datatables -->
        <link href="<?php echo $_SERVER['PATH_WEBROOT'] ?>/ext_lib_fe/datatables.net-bs/css/dataTables.bootstrap.min.css<?= _VERSION_ ?>" rel="stylesheet">
        <link href="<?php echo $_SERVER['PATH_WEBROOT'] ?>/ext_lib_fe/datatables.net-buttons-bs/css/buttons.bootstrap.min.css<?= _VERSION_ ?>" rel="stylesheet">
        <link href="<?php echo $_SERVER['PATH_WEBROOT'] ?>/ext_lib_fe/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css<?= _VERSION_ ?>" rel="stylesheet">
        <link href="<?php echo $_SERVER['PATH_WEBROOT'] ?>/ext_lib_fe/datatables.net-responsive-bs/css/responsive.bootstrap.min.css<?= _VERSION_ ?>" rel="stylesheet">
        <link href="<?php echo $_SERVER['PATH_WEBROOT'] ?>/ext_lib_fe/datatables.net-scroller-bs/css/scroller.bootstrap.min.css<?= _VERSION_ ?>" rel="stylesheet">
        <link href="<?php echo $_SERVER['PATH_WEBROOT'] ?>/ext_lib_fe/datatables.net-rowgroup/css/rowGroup.dataTables.min.css<?= _VERSION_ ?>" rel="stylesheet">

        <!-- Dropzone.js -->
        <link href="<?php echo $_SERVER['PATH_WEBROOT'] ?>/ext_lib_fe/dropzone/dist/min/dropzone.min.css<?= _VERSION_ ?>" rel="stylesheet">

        <!-- bootstrap-progressbar -->
        <link href="<?php echo $_SERVER['PATH_WEBROOT'] ?>/ext_lib_fe/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css<?= _VERSION_ ?>" rel="stylesheet">

        <!-- Custom Theme Style -->   
        <link href="<?php echo $_SERVER['PATH_WEBROOT'] ?>/css/custom.css<?= _VERSION_ ?>" rel="stylesheet">    
        <link href="<?php echo $_SERVER['PATH_WEBROOT'] ?>/css/loading.css<?= _VERSION_ ?>" rel="stylesheet">    

        <link href="<?php echo $_SERVER['PATH_WEBROOT'] ?>/ext_lib_fe/jQuery-TE_v.1.4.0/jquery-te-1.4.0.css<?= _VERSION_ ?>" type="text/css"  rel="stylesheet"/>
        <!-- Select2 -->
        <link href="<?php echo $_SERVER['PATH_WEBROOT'] ?>/ext_lib_fe/select2/dist/css/select2.min.css<?= _VERSION_ ?>" rel="stylesheet">

        <script src="<?php echo $_SERVER['PATH_WEBROOT'] ?>/ext_lib_fe/livevalidation-1.3/livevalidation_standalone.compressed.js<?= _VERSION_ ?>" type="text/javascript" ></script> 

        <!-- PNotify -->
        <link href="<?php echo $_SERVER['PATH_WEBROOT'] ?>/ext_lib_fe/pnotify/dist/pnotify.css<?= _VERSION_ ?>" rel="stylesheet">
        <link href="<?php echo $_SERVER['PATH_WEBROOT'] ?>/ext_lib_fe/pnotify/dist/pnotify.buttons.css<?= _VERSION_ ?>" rel="stylesheet">
        <link href="<?php echo $_SERVER['PATH_WEBROOT'] ?>/ext_lib_fe/pnotify/dist/pnotify.material.css<?= _VERSION_ ?>" rel="stylesheet">
        <link href="<?php echo $_SERVER['PATH_WEBROOT'] ?>/ext_lib_fe/pnotify/dist/pnotify.mobile.css<?= _VERSION_ ?>" rel="stylesheet">
        <link href="<?php echo $_SERVER['PATH_WEBROOT'] ?>/ext_lib_fe/pnotify/dist/pnotify.nonblock.css<?= _VERSION_ ?>" rel="stylesheet">

        <!-- Jquery Confirm -->
        <link href="<?php echo $_SERVER['PATH_WEBROOT'] ?>/ext_lib_fe/JqueryConfirm/jquery-confirm.min.css<?= _VERSION_ ?>" rel="stylesheet">

        <script> var imgfilepath = "<?= _IMGFILEURL_ ?>";</script>
        <script> var serverNameConfig = "<?= _SEVERNAMEC_ ?>";</script>

        <link rel="preload" href="/img/loading.gif" as="image">

    </head>


    <?php
    $classBody = "nav-md";
    $ua = strtolower($_SERVER['HTTP_USER_AGENT']);
    if (stripos($ua, 'android') !== false) { // && stripos($ua,'mobile') !== false) {
        $classBody = "nav-sm";
    }
    ?>


    <body class="<?= $classBody ?>">
        <div class="container body">

            <div class="main_container">
                <div id="stailavorandocome" class="row" style="height: 40px;background-color: orange; display: none">
                    <div class="col-md-11" style="margin-left: 10px;color: black">
                        <h4>Stai lavorando come Cliente 1</h4>
                    </div>
                </div>
                <?php
                if (CoreLogic::GetPersonLogged() != null) {
                    include "./view/template/menu.php";
                    /*
                    if(CoreLogic::VerifyPermission() === "admin"){
                        include "./view/template/menu.php";
                    } else {
                        include "./view/template/menuAgent.php";
                    }
                     */
                }
                ?>

                <?php
                $class = lcfirst($class);

                if (CoreLogic::GetPersonLogged() != null) {
                    if (CoreLogic::GetPersonLogged() != null) {
                        @include "./view/$class/$operazione.php";
                     
                    }
                } else {
                    @include "./view/user/login.php";
                }
                ?>
            </div>


    </body>
</html>