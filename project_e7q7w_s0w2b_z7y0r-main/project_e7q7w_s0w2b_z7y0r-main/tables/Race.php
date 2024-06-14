<?php
include_once('connection.php');
global $db_conn;

if (isset($_POST['action']) && $_POST['action'] == 'Add Race') {
    // Get user input from the form
    $race_name = $_POST["race_name"];
    $speed = $_POST["speed"];
    $race_description = $_POST["race_description"];
    $feature = $_POST["feature"];

    $sqlRace = "INSERT INTO race (race_name, speed, race_description, feature) VALUES (:race_name, :speed, :race_description, :feature)";
    connectToDB();
    $stmtRace = oci_parse($db_conn, $sqlRace);

    oci_bind_by_name($stmtRace, ":race_name", $race_name);
    oci_bind_by_name($stmtRace, ":speed", $speed);
    oci_bind_by_name($stmtRace, ":race_description", $race_description);
    oci_bind_by_name($stmtRace, ":feature", $feature);

    $resultRace = oci_execute($stmtRace);
    oci_free_statement($stmtRace);
    disconnectFromDB();

    // Check if the query was successful
    if ($resultRace) {
        echo "<meta http-equiv='refresh' content='0'>";
    } else {
        echo "Error adding race: " . oci_error($db_conn);
    }
} else if (isset($_POST['daction']) && $_POST['daction'] == 'Delete Race') {
    $name = $_POST["name"];

    $sqlRace = "DELETE FROM race WHERE race_name=:name";
    connectToDB();
    $stmtRace = oci_parse($db_conn, $sqlRace);

    oci_bind_by_name($stmtRace, ":name", $name);

    $result = oci_execute($stmtRace);
    oci_free_statement($stmtRace);
    disconnectFromDB();

    if ($result) {
        echo "<meta http-equiv='refresh' content='0'>";
    } else {
        echo "Error deleting race: " . oci_error($db_conn);
    }
} else {
    connectToDB();
    $stmtRaces = oci_parse($db_conn, "SELECT race_name, speed, feature FROM race");
    $racesResult = oci_execute($stmtRaces);

    $nraces = oci_fetch_all($stmtRaces, $results);

    oci_free_statement($stmtRaces);

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
    <title>Race Table</title>
</head>
<body>
<h1>Design your race</h1>

<form method="post">
    <!-- Add race name -->
    <label for="race_name">Race Name:</label>
    <input type="text" name="race_name">
    <br><br>

    <!-- Add speed -->
    <label for="speed">Speed:</label>
    <input type="number" name="speed">
    <br><br>

    <!-- Add race description -->
    <label for="race_description">Race Description:</label>
    <input type="text" name="race_description" size="100">
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

    <input type="submit" name="action" value="Add Race">
</form>

<h1>List of Races</h1>

<table class="table table-bordered text-center" name="Race Table" style="display: inline-block">
    <tr>
        <td>
            <table border='1' style="font-weight:bold">
                <tr>
                    <td>Race Name</td>
                </tr>
                <tr>
                    <td>Speed</td>
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

<h1>Delete Race</h1>
<form method="post">
    <label for="id">Race Name to delete:</label>
    <input type="text" name="name">
    <br><br>

    <input type="submit" name="daction" value="Delete Race">
</form>

</body>
</html>
