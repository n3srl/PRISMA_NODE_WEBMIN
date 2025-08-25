<html>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= _('Grafico delle Rilevazioni') ?></title>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>



    <div class="x_title">
        <div class="clearfix">
            <div class="col-md-6 ">
                <h2><?= _('Grafico delle rilevazioni') ?></h2>
            </div>
        </div>
    </div>

    <div class="x_content">
        <label for="datePicker"><?= _('Seleziona la data:') ?></label>
        <input type="date" id="datePicker" name="datePicker" class="form-control" style="max-width: 200px; margin-bottom: 20px;">
        <canvas id="graficoDetection" style="max-width: 100%; height: 400px;"></canvas>
    </div>



<script src='<?php echo $_SERVER['PATH_WEBROOT'] ?>/js/detectionGraph.js'></script> 


</html>
