<?php
/**
 * MySQLI Class
 * SBUIADMIN Class
 *
 * @link https://dev.informatux.com/
 *
 * @package SBUIADMIN
 * @file UTF-8
 * ©INFORMATUX.COM
 */

// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
// Blocking direct access to plugin      -=
// -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
defined('SBUIADMIN_PATH') or die('Are you crazy!');


class sql extends Smarty {
    var $host       = _AM_DB_HOST;
    var $user       = _AM_DB_USER;
    var $pass       = _AM_DB_PWD;
    var $base       = _AM_DB_NAME;
    var $port       = _AM_DB_PORT;
    var $socket     = _AM_DB_SOCKET;
    var $connect_id = 0;
    var $error;
    var $debug      = NULL;
    var $result_id  = false;
    var $query      = "";

    function connect() {
        if ($this->socket === false) {
            $this->connect_id = mysqli_connect($this->host, $this->user, $this->pass, $this->base);
        } else {
            $this->connect_id = mysqli_connect($this->host, $this->user, $this->pass, $this->base, $this->port, $this->socket);            
        }
        if (mysqli_connect_errno()) {
            //$this->dump(mysqli_connect_error() . ' (' . mysqli_errno($this->connect_id) . ')', 'Connect MYSQLI DB (Error)');
            return false;
        } else {
            //$this->dump(mysqli_get_host_info($this->connect_id), 'Connect SQL DB (success) : ');
            return $this->connect_id;
        }
    }
    
    function query($query) {
        // Check if DB connect ID
        if (!$this->connect_id) $this->connect();
        // Check Request SQL
        $this->result_id = mysqli_query($this->connect_id, $query);
        if ($this->result_id) {
            $this->query = trim($query);
            $this->error = '';
            //$this->dump(trim($query), 'Query SQL (success)');
            return $this->result_id;
        } else {
            //$this->dump(mysqli_error(), 'Query SQL (error)');
            $this->error = mysqli_error($this->connect_id);
            return false;
        }
    }
    function lastinsertid() {
        return mysqli_insert_id($this->connect_id);
    }
    function numrows() {
        if (isset($this->result_id)) {
            if (preg_match('`^select`i', $this->query)) return mysqli_num_rows($this->result_id);
            if (preg_match('`^(insert|update|delete)`i', $this->query)) return mysqli_affected_rows($this->result_id);
        } else {
            return count($this->records);
        }
    }
    function object($query) {
        return mysqli_fetch_object($query);
    }
    function getArray($query, $mode = 'ASSOC') {
        switch ($mode) {
            case 'NUM':
                return mysqli_fetch_array($query, MYSQLI_NUM);
            break;
            case 'BOTH':
                return mysqli_fetch_array($query, MYSQLI_BOTH);
            break;
            case 'ASSOC':
                return mysqli_fetch_array($query, MYSQLI_ASSOC);
            break;
            default:
                return mysqli_fetch_assoc($query);
        }
    }
    function assoc($query) {
        if (!$query) return false;
        return mysqli_fetch_assoc($query);
    }
    function toarray($result) {
        if (!$result) return false;
        $res_array = array();
        for ($count = 0; $row = mysqli_fetch_array($result); $count++) {
            $res_array[$count] = $row;
        }
        return $res_array;
    }
    function error() {
        if (!$this->connect_id) $this->connect();
        return mysqli_error($this->connect_id);
    }
    function close() {
        if (!$this->connect_id) $this->connect();
        return mysqli_close($this->connect_id);
    }
    function free() {
        //if (!$this->connect_id) $this->connect();
        //return mysqli_free_result($this->connect_id);
    }
    function escape_string($escapestr) {
        return mysqli_real_escape_string($this->connect_id, $escapestr);
    }
    function optimize($tbl_name) {
        if (!$this->connect_id) $this->connect();
        $query = "OPTIMIZE TABLE $tbl_name";
        if ($this->result_id = mysqli_query($this->connect_id, $query)) {
            $this->query = trim($query);
            $this->error = '';
            return $this->result_id;
        } else {
            $this->error = mysqli_error($this->connect_id);
            return false;
        }
    }
    function truncate($tbl_name) {
        //if (!$this->connect_id) $this->connect();
        $query = "TRUNCATE TABLE $tbl_name";
        if ($this->result_id = mysqli_query($this->connect_id, $query)) {
            $this->query = trim($query);
            $this->error = '';
            return $this->result_id;
        } else {
            $this->error = mysqli_error($this->connect_id);
            return false;
        }
    }
    function blob($file) {
        $blob = file_get_contents($file);
        $blob = addslashes($blob);
        $blob = addcslashes($blob, "");
        return $blob;
    }
    function showUpdateResult($result, $msg_success = '', $msg_error = '') {
        $result_error = ($msg_error != '') ? $msg_error : _AM_SYS_MSG_ERROR_NOUPDATE;
        $result_success = ($msg_success != '') ? '1:' . $msg_success : '1';
        if ($result) {
            echo $result_success;
        } else {
            if (_AM_DEBUG_MODE == '4') $error = $this->error();
            echo $result_error . '<br />' . $error;
        }
    }
    function redirect_error($code) {
        $error_msg = addslashes($this->error());
        header("Location:redirect.php?errorid=$code&msg=$error_msg");
        exit;
    }
}

?>