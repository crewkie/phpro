<?php
/**
 * @author Cookie
 * @file ItemTable.php
 * @date 09/29/2013 02:00:00
 * @version 0.1
 * @desc The following class contains methods related to reading item-related description and name tables from the
 * RO client-side files located in the /data folder (or in the GRF).
 */

namespace Crewkie\PhpRo;

use Crewkie\PhpRo\DataReader;
use Crewkie\PhpRo\DataParser;

define('RO_DATA_COLOR_LEN', 6);
define('RO_DATA_COLOR_BLACK', "000000");

class ItemTable
{

    public function __construct(DataReader $dataReader) {
        $this->dataReader   = $dataReader;
    }

    /**
     * Parses the Item Description table into a multi-dimensional array consisting of item ID and name.
     * @return array
     */
    public function parseData() {
        if (isset($this->data))
            return $this->data;

        $items = array();
        $data  = $this
            ->dataReader
            ->size(true)
            ->read();

        $DataParser = new DataParser($data);
        $array = $DataParser->toArray(array(array("#", 1), array("#", 1)), array("id", "desc"));

        return $array;

    }

    /**
     * Parses RO specific formatting into HTML and adds line breaks.
     * @param mixed $data
     * @return string
     */
    public function parseHtml($data) {
        $in_color = false;
        $len = strlen($data);
        //$data = str_replace("^" . RO_DATA_COLOR_BLACK, "</span>", $data);
        for ($i = 0; $i < $len * 2; $i++) {
            $text = substr($data, $i, 1);

            if ($text == "^" || $text == "^") {

                $iOffset = $i + 1;
                $color = substr($data, $iOffset, RO_DATA_COLOR_LEN);
                $colorHtml = '#' . substr($data, $iOffset, RO_DATA_COLOR_LEN);
              //  $data = substr_replace($data, "<span style=\"color: $colorHtml\">", $i, 1 + RO_DATA_COLOR_LEN);
                if ($in_color) {
                    if ($color != RO_DATA_COLOR_BLACK) {
                        $data = substr_replace($data, "</span><span style=\"color: $colorHtml\">", $i, 1 + RO_DATA_COLOR_LEN);
                    } else
                        $data = substr_replace($data, "</span>", $i, 1 + RO_DATA_COLOR_LEN);
                    $in_color = false;
                } else if (!$in_color) {
                    $in_color = true;
                    $data = substr_replace($data, "<span style=\"color: $colorHtml\">", $i, 1 + RO_DATA_COLOR_LEN);
                }

            }
        }

        // Add line breaks.
        $data = nl2br($data);

        return $data;

    }
}