<?php


$exceptionClass = array(
    
    
    "dispositivoMonitoraggio",
    "dispositivoMonitoraggioHistory",
    "monitoraggioDerattizzazione",
    "monitoraggioInsettiStriscianti",
    "monitoraggioInsettiVolanti",
    "monitoraggioMurino",
    "monitoraggioTignolaCereali",
    "monitoraggioTopi",
    "sedeOperativa",
    "sedeOperativaCliente",
    "applicazioneReview",
    "contrattoDaFatturare",
    "contrattiInScadenza",
    "aliquotaIva",
    "fatturaFornitori"
    
    
);

foreach ($exceptionClass as $ex){
    if(strtolower($ex) == $class){
        $class = $ex;
        break; 
    }
}