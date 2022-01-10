<?php

class DateLogic {

    public static function toUser($date = "") {
        //Formato accettato 2019-06-01 16:00:00
        if (strlen($date) < 19 && !empty($date)) {
            $date .= " 08:00:00";
        }
        if (empty($date)) {
            return date('Y-m-d\TH:i:sP');
        }
        $date = DateTime::createFromFormat("Y-m-d H:i:s", $date);

        $Person = CoreLogic::GetPersonLogged();
        if (!empty($Person->timezone)) {
            $date->setTimezone(new DateTimeZone($Person->timezone));
        }

        //Formato convertito 2019-06-01T12:00:00-04:00
        return $date->format("Y-m-d\TH:i:sP");
    }

    public static function fromUser($date = "") {
        //Formato 2019-06-01T12:00:00-04:00
        //-> convertito in -> 2019-06-01 16:00:00
        if (empty($date)) {
            return date('Y-m-d H:i:s');
        }
        if (is_int($date)) {
            return date('Y-m-d H:i:s', $date);
        }

        return date('Y-m-d H:i:s', strtotime($date));
    }

    public static function selectTimezone() {
        $OptionsArray = timezone_identifiers_list();
        $select = '<select name="SelectContacts">';
        while (list ($key, $row) = each($OptionsArray)) {
            $select .= '<option value="' . $row . '"';
            $select .= ($row == $selected ? ' selected' : '');
            $select .= '>' . $row . '</option>';
        }  // endwhile;
        $select .= '</select>';
        return $select;
    }

    public static function x_week_range($date) {
        $ts = strtotime($date);
        $start = (date('w', $ts) == 0) ? $ts : strtotime('Monday this week', $ts);
        return array(date('Y-m-d', $start),
            date('Y-m-d', strtotime('next sunday', $start)));
    }

    public static function dateYmdTOdmY($date) {
        return implode("/", array_reverse(explode("-", $date)));
    }

    public static function datedmYTOYmd($date) {
        return implode("-", array_reverse(explode("/", $date)));
    }
    public static function getMonthString($intMonth){
        //Converte il mese da numero a stringa
        $month = "";
        switch($intMonth){
           case 1:
               $month = "Gennaio";
               break;
           case 2:
               $month = "Febbraio";
               break;
           case 3:
               $month = "Marzo";
               break;
           case 4:
               $month = "Aprile";
               break;
           case 5:
               $month = "Maggio";
               break;
           case 6:
               $month = "Giugno";
               break;
           case 7:
               $month = "Luglio";
               break;
           case 8:
               $month = "Agosto";
               break;
           case 9:
               $month = "Settembre";
               break;
           case 10:
               $month = "Ottobre";
               break;
           case 11:
               $month = "Novembre";
               break;
           case 12:
               $month = "Dicembre";
               break;
        }
        return $month;
    }
}
