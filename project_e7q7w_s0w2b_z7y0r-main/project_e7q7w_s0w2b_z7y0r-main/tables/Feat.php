<?php
include_once('connection.php');
global $db_conn;

if (isset($_POST['action']) && $_POST['action'] == 'Add Feat') {
    // Get user input from the form
    $feat_name = $_POST["feat_name"];
    $feat_description = $_POST["feat_description"];

    // Insert data into the Feat table
    $sqlFeat = "INSERT INTO feat (feat_name, feat_description) VALUES (:feat_name, :feat_description)";
    connectToDB();
    $stmtFeat = oci_parse($db_conn, $sqlFeat);

    oci_bind_by_name($stmtFeat, ":feat_name", $feat_name);
    oci_bind_by_name($stmtFeat, ":feat_description", $feat_description);

    $resultFeat = oci_execute($stmtFeat);
    oci_free_statement($stmtFeat);
    disconnectFromDB();

    // Check if the queries were successful
    if ($resultFeat) {
        echo "<meta http-equiv='refresh' content='0'>";
    } else {
        echo "Error adding feat: " . oci_error($db_conn);
    }
} else if (isset($_POST['daction']) && $_POST['daction'] == 'Delete Feat') {
    $name = $_POST["name"];

    $sqlFeat = "DELETE FROM feat WHERE feat_name=:name";
    connectToDB();
    $stmtFeat = oci_parse($db_conn, $sqlFeat);

    oci_bind_by_name($stmtFeat, ":name", $name);

    $result = oci_execute($stmtFeat);
    oci_free_statement($stmtFeat);
    disconnectFromDB();

    if ($result) {
        echo "<meta http-equiv='refresh' content='0'>";
    } else {
        echo "Error deleting feat: " . oci_error($db_conn);
    }
} else {
    connectToDB();
    $stmtFeats = oci_parse($db_conn, "SELECT feat_name FROM feat");
    $featsResult = oci_execute($stmtFeats);

    $nfeats = oci_fetch_all($stmtFeats, $results);

    oci_free_statement($stmtFeats);
    disconnectFromDB();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Feat Table</title>
</head>
<body>
<h1>Design your feat</h1>

<form action="" method="post">
    <!-- Add feat name -->
    <label for="feat_name">Feat Name:</label>
    <input type="text" name="feat_name">
    <br><br>

    <!-- Add feat description -->
    <label for="feat_description">Feat Description:</label>
    <input type="text" name="feat_description" size="100">
    <br><br>

    <input type="submit" name="action" value="Add Feat">
</form>

<h1>List of Feats</h1>

<table class="table table-bordered text-center" name="Feat Table" style="display: inline-block">
    <tr>
        <td>
            <table border='1' style="font-weight:bold">
                <tr>
                    <td>Feat Name</td>
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

<h1>Delete Feat</h1>
<form action="" method="post">
    <!-- Select item ID -->
    <label for="id">Feat Name to delete:</label>
    <input type="text" name="name">
    <br><br>

    <input type="submit" name="daction" value="Delete Feat">
</form>

</body>
</html>
