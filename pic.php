<?php
//ini_set('display_errors',1);
//error_reporting(E_ALL);
$username = "root";
$password = "";
$host = "localhost";
$database = "failpics";
$key = md5("20111031");

mysql_connect($host, $username, $password) or die("Can not connect to database: ".mysql_error());
mysql_select_db($database) or die("Can not select the database: ".mysql_error());

if (isset($_GET['q']) && isset($_GET['md5'])) {

    $q = $_GET['q'];
    $hash = $_GET['md5'];

    $fp = fopen('/tmp/hash.txt','wb');
    fwrite($fp,$q.$key);
    fclose($fp);

    # Create a hash of the query, combining it with the key
    $qhash = md5($q.$key);
    if ($qhash != $hash) {
        header('Content-Type: text/html');
        //echo "Bad content - hash mismatch (should be $qhash).";
        echo "Bad content.";
        exit;
    }

        //$q = "select image FROM failpics LIMIT 1";
    $result = mysql_query($q);
    if (!$result) {
        $message  = 'Invalid query: ' . mysql_error() . "<br>";
        $message .= 'Whole query: ' . $q;
        die($message);
    }
    $row = mysql_fetch_assoc($result);
    $content = $row['image'];

    //header('Content-type: image/jpg');
    header('Content-Type: image/jpeg');
    echo $content;
} else {
    header('Content-Type: text/html');
    echo "Bad content.";
    exit;
}
?>
