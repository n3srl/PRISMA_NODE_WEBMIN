<?php

class FieldException extends Exception {

    public $errors;
    public $message = "Error Field";

    public function __construct($errors) {
        error_log(json_encode($errors));
        $this->message = implode("<br />", $errors);

        $this->errors = $errors;
        parent::__construct();
    }

}

class PersonException extends Exception {

    public $errors;
    public $message = "Error Field";

    public function __construct($errors) {
        error_log(json_encode($errors));
        $this->message = implode("<br />", $errors);

        $this->errors = $errors;
        parent::__construct();
    }

}

class CSRFException extends Exception {

    public $errors;
    public $message = "Error Field";

    public function __construct($errors) {
        error_log(json_encode($errors));
        $this->message = implode("<br />", $errors);

        $this->errors = $errors;
        parent::__construct();
    }

}

class ApiException extends Exception {

    public static $FieldException = "FIELD";
    public static $CSRFException = "CSRF";
    public static $PersonException = "PERSON";
    public static $Generic = "GENERAL";
    public $errors;
    public $message = "Error Field";

    public function __construct($type, $errors = null) {

        switch ($type) {
            case self::$FieldException:
                $this->message = implode("<br />", $errors);
                $this->errors = $errors;
                break;
            case self::$CSRFException:
                $this->message = "Error CSRF";
                break;
            case self::$PersonException:
                $this->message = "Error Person";
                break;
            case self::$Generic:
                $this->message = "Generic Error";
                if ($errors != null) {
                    if (is_array($errors))
                        $this->message = implode("<br />", $errors);
                    else
                        $this->message = $errors;
                }
                break;
        }



        parent::__construct();
    }

}
