<?php
include_once('connection.php');
global $db_conn;

if (isset($_POST['action']) && $_POST['action'] == 'Add Feature') {
    // Get user input from the form
    $feat_name = $_POST["feat"];
    $feature_name = $_POST["feature_name"];
    $feature_description = $_POST["feature_description"];
    $level_required = $_POST["level_required"];

    // Insert data into the Feature table
    $sqlFeature = "INSERT INTO feature (feature_name, feature_description, level_required, feat) VALUES (:feature_name, :feature_description, :level_required, :feat)";
    connectToDB();
    $stmtFeature = oci_parse($db_conn, $sqlFeature);

    oci_bind_by_name($stmtFeature, ":feature_name", $feature_name);
    oci_bind_by_name($stmtFeature, ":feature_description", $feature_description);
    oci_bind_by_name($stmtFeature, ":level_required", $level_required);
    oci_bind_by_name($stmtFeature, ":feat", $feat_name);

    $resultFeature = oci_execute($stmtFeature);
    oci_free_statement($stmtFeature);
    disconnectFromDB();

    // Check if the query was successful
    if ($resultFeature) {
        echo "<meta http-equiv='refresh' content='0'>";
    } else {
        echo "Error adding feature: " . oci_error($db_conn);
    }
} else if (isset($_POST['daction']) && $_POST['daction'] == 'Delete Feature') {
    $name = $_POST["name"];

    $sqlFeature = "DELETE FROM feature WHERE feature_name=:name";
    connectToDB();
    $stmtFeature = oci_parse($db_conn, $sqlFeature);

    oci_bind_by_name($stmtFeature, ":name", $name);

    $result = oci_execute($stmtFeature);
    oci_free_statement($stmtFeature);
    disconnectFromDB();

    if ($result) {
        echo "<meta http-equiv='refresh' content='0'>";
    } else {
        echo "Error deleting feat: " . oci_error($db_conn);
    }
} else {
    connectToDB();
    $stmtFeatures = oci_parse($db_conn, "SELECT feature_name, level_required, feat FROM feature");
    $featuresResult = oci_execute($stmtFeatures);

    $nfeatures = oci_fetch_all($stmtFeatures, $results);

    oci_free_statement($stmtFeatures);

    $stmtFeats = oci_parse($db_conn, "SELECT feat_name FROM feat");
    $featsResult = oci_execute($stmtFeats);
    $nfeats = oci_fetch_all($stmtFeats, $feats);

    oci_free_statement($stmtFeats);

    disconnectFromDB();
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Feature Table</title>
</head>
<body>
<h1>Design your feature</h1>

<form method="post">
    <!-- Add feature name -->
    <label for="feature_name">Feature Name:</label>
    <input type="text" name="feature_name">
    <br><br>

    <!-- Add feature description -->
    <label for="feature_description">Feature Description:</label>
    <input type="text" name="feature_description" size="100">
    <br><br>

    <!-- Add level required -->
    <label for="level_required">Level Required:</label>
    <input type="number" name="level_required">
    <br><br>

    <!-- Add feat -->
    <label for="feat">Feat:</label>
    <select type="text" name="feat">
        <option value="">--- Select an added feat ---</option>
        <?php
        foreach ($feats['FEAT_NAME'] as $key => $name) {
            echo '<option value="' . $name . '">' . $name . '</option>';
        }
        ?>
    </select>
    <br><br>

    <input type="submit" name="action" value="Add Feature">
</form>

<h1>List of Features</h1>

<table class="table table-bordered text-center" name="Feature Table" style="display: inline-block">
    <tr>
        <td>
            <table border='1' style="font-weight:bold">
                <tr>
                    <td>Feature Name</td>
                </tr>
                <tr>
                    <td>Level Required</td>
                </tr>
                <tr>
                    <td>Associated Feat</td>
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

<h1>Delete Feature</h1>
<form action="" method="post">
    <!-- Select item ID -->
    <label for="id">Feature Name to delete:</label>
    <input type="text" name="name">
    <br><br>

    <input type="submit" name="daction" value="Delete Feature">
</form>

</body>
</html>
