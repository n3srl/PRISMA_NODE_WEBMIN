<?php /* @var $User User */ ?> 
<!-- Bootstrap -->
<link href="<?php echo $_SERVER['PATH_WEBROOT'] ?>/ext_lib_fe/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Font Awesome -->
<link href="<?php echo $_SERVER['PATH_WEBROOT'] ?>/ext_lib_fe/font-awesome/css/font-awesome.min.css" rel="stylesheet">
<link href="<?php echo $_SERVER['PATH_WEBROOT'] ?>/css/custom.css" rel="stylesheet">    

<title><?= CoreLogic::GetStationCode() ?></title>
<?php include __DIR__.'/../etc/errorPopup.html';?>
<body style="background:#F7F7F7;">
    <!-- Selettore di Lingua -->
    <div class="language_selector_home" style="
        position: absolute;
        top: 20;
        right: 20;
    ">
        <?php PrismaMultilanguage::getViewOptions(); ?>
    </div>
    <div class="">
        <a class="hiddenanchor" id="toregister"></a>
        <a class="hiddenanchor" id="tologin"></a>

        <div id="wrapper">
            <div id="login" class="form">
                <section class="login_content">
                    <img src="/img/logo.png" alt="..." width="140px">
                    <form id="LoginForm">
                        <h1>Login</h1>
                        <div>
                            <input type="text" class="form-control" name='username' id='username' placeholder="Username" required="" />
                        </div>
                        <div>
                            <input type="password" class="form-control" name='password' id='password' placeholder="Password" required="" />
                        </div>
                        <div>
                            <button type="submit" class="btn btn-success">Log in</button>
                        </div>
                        <div class="clearfix"></div>
                    </form>
                </section>

                
            </div>
        </div>
    </div>
    
    <!-- jQuery -->
    <script src="<?php echo $_SERVER['PATH_WEBROOT'] ?>/ext_lib_fe/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="<?php echo $_SERVER['PATH_WEBROOT'] ?>/ext_lib_fe/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="<?php echo $_SERVER['PATH_WEBROOT'] ?>/ext_lib_fe/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="<?php echo $_SERVER['PATH_WEBROOT'] ?>/ext_lib_fe/nprogress/nprogress.js"></script>
    <!-- Datatables -->
    <script src="<?php echo $_SERVER['PATH_WEBROOT'] ?>/ext_lib_fe/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="<?php echo $_SERVER['PATH_WEBROOT'] ?>/ext_lib_fe/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <script src="<?php echo $_SERVER['PATH_WEBROOT'] ?>/ext_lib_fe/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="<?php echo $_SERVER['PATH_WEBROOT'] ?>/ext_lib_fe/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
    <script src="<?php echo $_SERVER['PATH_WEBROOT'] ?>/ext_lib_fe/datatables.net-buttons/js/buttons.flash.min.js"></script>
    <script src="<?php echo $_SERVER['PATH_WEBROOT'] ?>/ext_lib_fe/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="<?php echo $_SERVER['PATH_WEBROOT'] ?>/ext_lib_fe/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="<?php echo $_SERVER['PATH_WEBROOT'] ?>/ext_lib_fe/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
    <script src="<?php echo $_SERVER['PATH_WEBROOT'] ?>/ext_lib_fe/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
    <script src="<?php echo $_SERVER['PATH_WEBROOT'] ?>/ext_lib_fe/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="<?php echo $_SERVER['PATH_WEBROOT'] ?>/ext_lib_fe/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
    <script src="<?php echo $_SERVER['PATH_WEBROOT'] ?>/ext_lib_fe/datatables.net-scroller/js/dataTables.scroller.min.js"></script>
    <script src="<?php echo $_SERVER['PATH_WEBROOT'] ?>/ext_lib_fe/select2/dist/js/select2.min.js"></script>
    <script src="<?php echo $_SERVER['PATH_WEBROOT'] ?>/js/etc/errorPopup.js"></script>
    <script src="<?php echo $_SERVER['PATH_WEBROOT'] ?>/js/login.js<?= _VERSION_ ?>"></script>
    

</body>
</html>
