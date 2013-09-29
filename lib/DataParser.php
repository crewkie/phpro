<?php
/**
 * @author Cookie
 * @file DataParser.php
 * @date 09/29/2013 02:00:00
 * @version 0.1
 * @desc This file contains a class with methods related to parsing data.
 */
namespace Crewkie\PhpRo;

class DataParser {
    private static $dataParser;

    public function __construct($data) {
        $this->data = $data;
    }

    public function getInstance($data) {
        if (!self::$dataParser) {
            self::$dataParser = new DataParser($data);
        }
        return self::$dataParser;
    }

    /**
     * Returns an array of data based on data.
     * @param array $delimeters
     * @param array $keys
     * @throws \Exception
     * @return array
     */
    public function toArray($delimiters, $keys) {
        if (!isset($this->data))
            throw new \Exception('$this->data is not set. Class was not constructed properly.');

        $cur_del_count = 1;
        $cur_del_idx = 0;
        $cur_arr_idx = 0;
        $cur_data = "";
        $dataArr = array();
        $len = strlen($this->data);
        for ($i = 0; $i < $len; $i++) {
            $text = substr($this->data, $i, 1);
            $cur_del_arr = $delimiters[$cur_del_idx];
            if ($text == $cur_del_arr[0]) { // Matches delimiter
                if ($cur_del_count == $cur_del_arr[1]) { // Matches delimiter count
                    $dataArr[$cur_arr_idx][$keys[$cur_del_idx]] = $cur_data;

                    // Increment the delimiter idx
                    $cur_del_idx++;
                    if (sizeof($delimiters) == $cur_del_idx) {
                        $cur_del_idx = 0;
                        $cur_arr_idx++;
                    }

                    // Unset data
                    $cur_data = "";
                    $cur_del_count = 1;
                } else
                    $cur_del_count++;

            } else { // Doesn't match delimiter
                $cur_data .= $text;
            }
        }

        return $dataArr;
    }
}