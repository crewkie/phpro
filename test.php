<?php
/**
 * This is just an example script I created really fast. Ignore the messy-ness.
 */
namespace Crewkie\PhpRo;

// Set the appropriate directory.
chdir(__DIR__);

require 'lib/DataReader.php';
require 'lib/DataParser.php';
require 'lib/ItemTable.php';

$submitState = (!empty($_POST) ? true : false);

if ($submitState) {
    $item_id = (int)$_POST['item_id'];
    $item_table = new ItemTable(new DataReader("idnum2descnametable.txt"));
    $items = $item_table->parseData();

    /*echo $items[0]["id"] . '<br>';
    echo $item_table->parseHtml($items[1]["desc"]);*/

    $found_desc = false;
    $desc = "";
    $desc_html = "";
    $id = 0;
    foreach ($items AS $item) {
        if ($item['id'] == $item_id) {
            $found_desc = true;
            $id = $item['id'];
            $desc = $item['desc'];
            $desc_html = $item_table->parseHtml($desc);
        }
    }

    if (!$found_desc) {
        $errorHtml = "<p style=\"color: red\">Item ID #{$item_id} was NOT found. Try again.";
    } else {
        echo<<<EOT
<h2>Item Description Parser</h2>
<p>The following description was found:</p>
EOT;
        echo "Item ID:" . $id . "<br>";
        echo "Description without formatting:" . "<br>";
        echo nl2br($desc) . "<br>";
        echo "Description with HTML formatting:" . "<br>";
        echo $desc_html . "<br>";
        echo "<a href=\"test.php\">Click here to search again...</a>";
        exit;
    }
}

    //echo var_dump($items);
    echo<<<EOT
<h2>Item Description Parser</h2>
EOT;
if (isset($errorHtml))
    echo $errorHtml;

    echo<<<EOD
<p>Input an item ID to find the description. This reads from <a href="idnum2descnametable.txt">this file.</a>
<form action="" method="post">
<table>
    <tbody>
        <tr>
            <th><label for="item_id">Item ID:</label></th>
            <td><input type="text" name="item_id" id="item_id" /></td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="submit" value="Search" name="search" />
            </td>
        </tr>
    </tbody>
</table>
</form>
EOD;

unset($item_table); // clean up the data and close the file descriptor.

?>