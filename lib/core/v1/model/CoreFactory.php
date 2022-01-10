<?php

class CoreFactory {

    public static function beginTransaction() {

        global $db_conn;
        $query = "START TRANSACTION;";
        @mysqli_query($db_conn, $query);
    }

    public static function commitTransaction() {

        global $db_conn;
        $query = "COMMIT;";
        @mysqli_query($db_conn, $query);
    }

    public static function rollbackTransaction() {

        global $db_conn;
        $query = "ROLLBACK;";
        @mysqli_query($db_conn, $query);
    }

}
