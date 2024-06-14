<?php
include_once('connection.php');
global $db_conn;

if (isset($_POST['action']) && $_POST['action'] == 'Add Background') {
    // Get user input from the form
    $bgnd_name = $_POST["bgnd_name"];
    $feature = $_POST["feature"];
    $background_description = $_POST["background_description"];

    // Insert data into the Background table
    $sqlBackground = "INSERT INTO background (bgnd_name, background_description, feature) VALUES (:bgnd_name, :background_description, :feature)";
    connectToDB();
    $stmtBackground = oci_parse($db_conn, $sqlBackground);

    oci_bind_by_name($stmtBackground, ":bgnd_name", $bgnd_name);
    oci_bind_by_name($stmtBackground, ":background_description", $background_description);
    oci_bind_by_name($stmtBackground, ":feature", $feature);

    $resultBackground = oci_execute($stmtBackground);
    oci_free_statement($stmtBackground);
    disconnectFromDB();

    // Check if the query was successful
    if ($resultBackground) {
        echo "<meta http-equiv='refresh' content='0'>";
    } else {
        echo "Error adding background: " . oci_error($db_conn);
    }
} else if (isset($_POST['daction']) && $_POST['daction'] == 'Delete Background') {
    $name = $_POST["name"];

    $sqlBackground = "DELETE FROM background WHERE bgnd_name=:name";
    connectToDB();
    connectToDB();
    $stmtBackground = oci_parse($db_conn, $sqlBackground);

    oci_bind_by_name($stmtBackground, ":name", $name);

    $result = oci_execute($stmtBackground);
    oci_free_statement($stmtBackground);
    disconnectFromDB();

    if ($result) {
        echo "<meta http-equiv='refresh' content='0'>";
    } else {
        echo "Error deleting background: " . oci_error($db_conn);
    }
} else {
    connectToDB();
    $stmtBackgrounds = oci_parse($db_conn, "SELECT bgnd_name, feature FROM background");
    $backgroundsResult = oci_execute($stmtBackgrounds);

    $nbackgrounds = oci_fetch_all($stmtBackgrounds, $results);

    oci_free_statement($stmtBackgrounds);

    $stmtFeatures = oci_parse($db_conn, "SELECT feature_name FROM feature");
    $featuresResult = oci_execute($stmtFeatures);
    $nFeatures = oci_fetch_all($stmtFeatures, $features);

    oci_free_statement($stmtFeatures);

    disconnectFromDB();
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Background Table</title>
</head>
<body>
<h1>Design your background</h1>
<form action="" method="POST">
    <!-- Add bgnd name -->
    <label for="bgnd_name">Bgnd Name:</label>
    <input type="text" name="bgnd_name">
    <br><br>

    <!-- Add background description -->
    <label for="background_description">Background Description:</label>
    <input type="text" name="background_description" size="100">
    <br><br>

    <!-- Add feature -->
    <label for="feature">Feature:</label>
    <select type="text" name="feature">
        <option value="">--- Select an added feature ---</option>
        <?php
        foreach ($features['FEATURE_NAME'] as $key => $name) {
            echo '<option value="' . $name . '">' . $name . '</option>';
        }
        ?>
    </select>
    <br><br>

    <input type="submit" name="action" value="Add Background">
</form>

<h1>List of Backgrounds</h1>

<table class="table table-bordered text-center" name="Background Table" style="display: inline-block">
    <tr>
        <td>
            <table border='1' style="font-weight:bold">
                <tr>
                    <td>Background Name</td>
                </tr>
                <tr>
                    <td>Associated Feature</td>
                </tr>
            </table>
        </td>
        <td>
            <table border='1'>
                <?php
                foreach ($results as $col) {
                    echo "<tr>\n";
                    foreach ($col as $item) {
                        echo "<td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "") . "</td>\n";
                    }
                    echo "</tr>\n";
                }
                ?>
        </td>
</table>
</table>

<h1>Delete Background</h1>
<form action="" method="post">
    <label for="id">Background Name to delete:</label>
    <input type="text" name="name">
    <br><br>

    <input type="submit" name="daction" value="Delete Background">
</form>

</body>
</html>
