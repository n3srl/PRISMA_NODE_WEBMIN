<div class ="col-md-12 col-sm-12 col-xs-12">
    <footer style="bottom: 0px; display: block; clear: both; position: fixed;width:100%;margin-right: 0px; padding: 8px 15px; font-size: 11px; float: right; left:0 !important">

        <div class="pull-right">
            Copyright &copy; <?php echo date("Y") ?> &ndash; <?php echo (date("Y") + 1) ?>
            N3 S.r.l. All rights reserved 
        </div>
        <div class="clearfix"></div>
    </footer>
    <!-- /footer content -->
</div>
</div>

<!-- jQuery -->
<script src="/ext_lib_fe/jquery/dist/jquery.min.js<?= _VERSION_ ?>"></script>
<!-- Bootstrap -->
<script src="/ext_lib_fe/bootstrap/dist/js/bootstrap.min.js<?= _VERSION_ ?>"></script>
<!-- FastClick -->
<script src="/ext_lib_fe/fastclick/lib/fastclick.js<?= _VERSION_ ?>"></script>
<!-- NProgress -->
<script src="/ext_lib_fe/nprogress/nprogress.js<?= _VERSION_ ?>"></script>
<!-- iCheck -->
<script src="/ext_lib_fe/iCheck/icheck.min.js<?= _VERSION_ ?>"></script>
<!-- Datatables -->
<script src="/ext_lib_fe/datatables.net/js/jquery.dataTables.min.js<?= _VERSION_ ?>"></script>
<script src="/ext_lib_fe/datatables.net-bs/js/dataTables.bootstrap.min.js<?= _VERSION_ ?>"></script>
<script src="/ext_lib_fe/datatables.net-buttons/js/dataTables.buttons.min.js<?= _VERSION_ ?>"></script>
<script src="/ext_lib_fe/datatables.net-buttons-bs/js/buttons.bootstrap.min.js<?= _VERSION_ ?>"></script>
<script src="/ext_lib_fe/datatables.net-buttons/js/buttons.flash.min.js<?= _VERSION_ ?>"></script>
<script src="/ext_lib_fe/datatables.net-buttons/js/buttons.html5.min.js<?= _VERSION_ ?>"></script>
<script src="/ext_lib_fe/datatables.net-buttons/js/buttons.print.min.js<?= _VERSION_ ?>"></script>
<script src="/ext_lib_fe/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js<?= _VERSION_ ?>"></script>
<script src="/ext_lib_fe/datatables.net-keytable/js/dataTables.keyTable.min.js<?= _VERSION_ ?>"></script>
<script src="/ext_lib_fe/datatables.net-responsive/js/dataTables.responsive.min.js<?= _VERSION_ ?>"></script>
<script src="/ext_lib_fe/datatables.net-responsive-bs/js/responsive.bootstrap.js<?= _VERSION_ ?>"></script>
<script src="/ext_lib_fe/datatables.net-scroller/js/dataTables.scroller.min.js<?= _VERSION_ ?>"></script>
<script src="/ext_lib_fe/datatables.net-rowgroup/js/dataTables.rowGroup.min.js<?= _VERSION_ ?>"></script>
<!--script src="/ext_lib_fe/jszip/dist/jszip.min.js<?= _VERSION_ ?>"></script>
<script src="/ext_lib_fe/pdfmake/build/pdfmake.min.js<?= _VERSION_ ?>"></script>
<script src="/ext_lib_fe/pdfmake/build/vfs_fonts.js<?= _VERSION_ ?>"></script-->
<!-- bootstrap-daterangepicker -->
<script src="/ext_lib_fe/moment/moment.min.js<?= _VERSION_ ?>"></script>
<script src="/ext_lib_fe/datepicker/daterangepicker.js<?= _VERSION_ ?>"></script>
<!-- Bootstrap jQuery-TE -->
<script src="/ext_lib_fe/jQuery-TE_v.1.4.0/jquery-te-1.4.0.min.js<?= _VERSION_ ?>"  type="text/javascript"></script>
<!-- jQuery autocomplete -->
<script src="/ext_lib_fe/devbridge-autocomplete/dist/jquery.autocomplete.min.js<?= _VERSION_ ?>"></script>
<!-- validator -->
<script src="/ext_lib_fe/validator/validator.min.js<?= _VERSION_ ?>"></script>
<!-- Dropzone.js -->
<script src="/ext_lib_fe/dropzone/dist/min/dropzone.min.js<?= _VERSION_ ?>"></script>
<!-- Skycons -->
<script src="/ext_lib_fe/skycons/skycons.js<?= _VERSION_ ?>"></script>
<!-- bootstrap-progressbar -->
<script src="/ext_lib_fe/bootstrap-progressbar/bootstrap-progressbar.min.js<?= _VERSION_ ?>"></script>
<!-- ECharts -->
<script src="/ext_lib_fe/echarts/dist/echarts.min.js<?= _VERSION_ ?>"></script>

<!-- morris.js -->
<script src="/ext_lib_fe/raphael/raphael.min.js<?= _VERSION_ ?>"></script>

<!-- Select2 -->
<script src="/ext_lib_fe/select2/dist/js/i18n/it.js<?= _VERSION_ ?>"></script>
<script src="/ext_lib_fe/select2/dist/js/select2.full.min.js<?= _VERSION_ ?>"></script>

<script src="/ext_lib_fe/BlockUI/jquery.blockUI.js<?= _VERSION_ ?>"></script>
<script src="/ext_lib_fe/jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js<?= _VERSION_ ?>"></script>
<script src="/ext_lib_fe/jquery.cookie/js.cookie.js<?= _VERSION_ ?>"></script>

<!-- PNotify -->
<script src="/ext_lib_fe/pnotify/dist/pnotify.js<?= _VERSION_ ?>"></script>
<script src="/ext_lib_fe/pnotify/dist/pnotify.buttons.js<?= _VERSION_ ?>"></script>
<script src="/ext_lib_fe/pnotify/dist/pnotify.confirm.js<?= _VERSION_ ?>"></script>
<script src="/ext_lib_fe/pnotify/dist/pnotify.callbacks.js<?= _VERSION_ ?>"></script>
<script src="/ext_lib_fe/pnotify/dist/pnotify.mobile.js<?= _VERSION_ ?>"></script>
<script src="/ext_lib_fe/pnotify/dist/pnotify.animate.js<?= _VERSION_ ?>"></script>
<script src="/ext_lib_fe/pnotify/dist/pnotify.nonblock.js<?= _VERSION_ ?>"></script>

<!-- Jquery Confirm -->
<script src="/ext_lib_fe/JqueryConfirm/jquery-confirm.min.js<?= _VERSION_ ?>"></script>

<!-- Jquery md5 -->
<script src="/ext_lib_fe/jquery-md5/jquery.md5.js<?= _VERSION_ ?>"></script>

<!-- download.js -->
<script src="/ext_lib_fe/download/download.js<?= _VERSION_ ?>"></script>

<!-- Custom Theme Scripts -->
<script src="/js/custom.js<?= _VERSION_ ?>"></script>
<script src="/js/standard.js<?= _VERSION_ ?>"></script>
<script src="/js/loading.js<?= _VERSION_ ?>"></script>
<script src="/js/footer.js<?= _VERSION_ ?>"></script>




<script>

    $(document).ready(function () {
        //setContentHeight();
    });

</script>

<?php
if (isset($error)) {
    $print = "";
    foreach ($error as $value) {
        $print .= '<p>' . mysqli_escape_string($db_conn, $value) . '</p>';
    }
    ?>
    <script>$(document).ready(function () {
            new PNotify({
                title: 'Error',
                text: '<?= $print ?>',
                type: 'error',
                hide: false,
                styling: 'bootstrap3'
            });


        });</script>


    <?php
}
?>
<script src='<?php echo $_SERVER['PATH_WEBROOT'] ?>/js/form.js<?= _VERSION_ ?>'></script>
<?php
includeJsFooterFromDir("/js/crud");
includeJsFooterFromDir("/js/baseModel");
includeJsFooterFromDir("/js/model");
includeJsFooterFromDir("/js/factory");
includeJsFooterFromDir("/js/logic");
?>
