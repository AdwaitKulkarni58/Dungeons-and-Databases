<!--
Reference of code: Sample project from the php section of the course: https://www.students.cs.ubc.ca/~cs-304/resources/php-oracle-resources/php-setup.html#running-a-sample-project
-->

<?php
$success = True;
$db_conn = NULL;
$show_debug_alert_messages = True;
define('_SERVER', "dbhost.students.cs.ubc.ca:1522/stu");
define('_USERNAME', "ora_aurus");
define('_PASSWORD', "a10099935");

function debugAlertMessage($message)
{
    global $show_debug_alert_messages;

    if ($show_debug_alert_messages) {
        echo "<script type='text/javascript'>alert('" . $message . "');</script>";
    }
}

function connectToDB($username = _USERNAME, $password = _PASSWORD, $server = _SERVER)
{
    global $db_conn;

    //echo "Connecting to " . $server . " With user [" . $username . "], password [" . $password . "]<br/>";

    $db_conn = oci_connect($username, $password, $server);

    if ($db_conn) {
        //debugAlertMessage("Database is Connected");
        return true;
    } else {
        //debugAlertMessage("Cannot connect to Database");
        $e = OCI_Error();
        echo htmlentities($e['message']);
        return false;
    }
}


function disconnectFromDB()
{
    global $db_conn;

    //debugAlertMessage("Disconnect from Database");
    oci_close($db_conn);
}

?>
