<?php

require_once("ApplicationLogger.php");
class ControllerCreator 
{
    public $page;
    public $log_file;
    public $log_func;
    public $msg;

    function __wakeup()
    {
        if(empty($this->msg) || empty($this->log_func) || empty($this->log_file))
        {
            $this->msg = "__wakeup() called on ControllerCreator";
            $this->log_func = "info";
            $this->log_file = "/var/security/security.log";
        }
        $this->logger = new ApplicationLogger($this->msg,$this->log_file, $this->log_func);
    }

    function __construct($page)
    {
        $this->page = $page;
        include($this->page);
    }
}