<?php
/**
 * @author Cookie
 * @file DataReader.php
 * @date 09/29/2013 02:00:00
 * @version 0.1
 * @desc The following class contains methods related to reading the txt tables from the RO /data/ folder.
 */

namespace Crewkie\PhpRo;

class DataReader
{
    public function __construct($dataSource) {
        $this->fileName     = $dataSource;
        $this->dataSource   = fopen($dataSource, 'r');
    }

    public function size($setFileSize = false) {
        if ($setFileSize) {
            $this->len = filesize($this->fileName);
        }
        return filesize($this->fileName);
    }

    public function read($len) {
        if (isset($this->len)) {
            $len = $this->len;
            $this->len = 0;
        }

        return fread($this->dataSource, $len);
    }

    public function tell() {
        return ftell($this->dataSource);
    }

    public function seek($offset, $whence = SEEK_SET) {
        fseek($this->dataSource, $offset, $whence);

        return $this;
    }

    public function __destruct() {
        if (isset($this->dataSource))
            fclose($this->dataSource);
    }

}