<?php
class ControllerDownload
{
    public $file;

    function __construct($filename)
    {
        $this->file = $filename;
    }

    function getLastDl()
    {
        return $this->file;
    }
}