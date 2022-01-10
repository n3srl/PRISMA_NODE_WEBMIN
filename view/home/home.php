<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3></h3>
            </div>
        </div>
        <div class="clearfix"></div>
        <!-- Calcolo Iva -->
        <div class="row">
            <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="tile-stats">
                    <div class="icon"><i class="fa fa-calendar-check-o"></i>
                    </div>
                    <div class="count" id="visiteSettimana">0</div>
                    <h3>Visite in settimana</h3>
                    <p><a class="settimanaDesc" href="/appuntamento/list"  style="color: black"></a></p>
                </div>
            </div>
            <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="tile-stats">
                    <div class="icon"><i class="fa fa-calendar-check-o"></i>
                    </div>
                    <div class="count" id="visiteDaEffettuare">0</div>
                    <h3>Visite da effettuare</h3>
                    <p><a href="/appuntamento/list"  style="color: black" id="bottone_effettuare">Visualiza elenco.</a></p>
                </div>
            </div>
            <!--div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="tile-stats">
                    <div class="icon"><i class="fa fa-edit"></i>
                    </div>
                    <div class="count" id="visiteScadute">0</div>
                    <h3>Visite scadute</h3>
                    <p><a href="/appuntamento/list"  style="color: black" id="bottone_scadute">Visualiza elenco.</a></p>
                </div>
            </div-->
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 col-md-offset-3 col-lg-offset-3">
                <div class="col-xs-12"><button type='button' class='btn pull-right btn-add-1 col-xs-12' onclick="window.location.href = '/cliente/edit'" >+ <i class="fa fa-user" style="margin-right: 10px "></i> Aggiungi cliente</button></div>
                <div class="col-xs-12"><button type='button' class='btn pull-right btn-add-2 col-xs-12' onclick="window.location.href = '/appuntamento/edit'" >+ <i class="fa fa-calendar-o"  style="margin-right: 10px "></i> Aggiungi visita</button></div>
                <div class="col-xs-12"><button type='button' class='btn pull-right btn-add-3 col-xs-12' onclick="window.location.href = '/prodotto/edit'" >+ <i class="fa fa-cube"  style="margin-right: 10px "></i> Aggiungi prodotto</button></div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_content">
                        <div class="row">
                            <!-- div class="col-lg-3 col-md-6">
                                <div>
                                    <div class="x_title">
                                        <h2>Ultimi clienti inseriti</h2>
                                        <div class="clearfix"></div>
                                    </div>
                                    <ul class="list-unstyled top_profiles scroll-view" style="height: 50%" id="listClientiInseriti">

                                    </ul>
                                </div>

                            </div-->
                            <div class="col-lg-3 col-md-6">
                                <div>
                                    <div class="x_title">
                                        <h2>Prossime visite</h2>
                                        <div class="clearfix"></div>
                                    </div>
                                    <ul class="list-unstyled top_profiles scroll-view" id="listProssimiAppuntamenti">
                                    </ul>
                                </div>

                            </div>
                            <div class="col-lg-9 col-md-12 col-sm-12">
                                <div>
                                    <div class="x_title">
                                        <h2>Geolocalizzazione visite</h2>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div id="map" style="height: 328px"></div>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="row" style="height: 20px;"></div>
    <?php
    include "./view/template/foot.php";
    ?>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA7Wg-9vZ4pc0KTymAAh4L2x93HLRtWMZ4&callback=initHome" async defer></script>