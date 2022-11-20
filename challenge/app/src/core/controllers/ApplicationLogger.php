<?php

class ApplicationLogger
{
    public $log_file;
    public $log_func;
    public $log_args;
    
    function __construct($msg, $log_file, $log_func)
    {
        if(!empty($log_func)) $this->log_func = $log_func;
        if(!empty($log_file)) $this->log_file = $log_file;
        if(!file_exists($this->log_file)) file_put_contents($this->log_file,"[+] File created\n",FILE_APPEND);
        if(!empty($msg)) $this->log_args = $msg;
        if(method_exists($this,$log_func)) call_user_func(array($this, $log_func),$this->log_args);
        else call_user_func($this->log_func, $this->log_args);
    }

    function info($msg)
    {
        file_put_contents($this->log_file,"[INFO] ".$msg."\n",FILE_APPEND);
    }

    function error($msg)
    {
        file_put_contents($this->log_file,"[ERROR] ".$msg."\n",FILE_APPEND);
    }
}