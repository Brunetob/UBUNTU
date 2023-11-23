<?php
// dbHandler.php
class DBHandler {
    private $dbconn;

    public function __construct() {
        require_once('conf.php');
        $this->dbconn = $dbconn;
    }

    public function getDBConnection() {
        return $this->dbconn;
    }
}
?>