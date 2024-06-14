<?php
include_once('connection.php');
global $db_conn;

if (isset($_POST['action']) && $_POST['action'] == 'Add Class') {
    // Get user input from the form
    $class_name = $_POST["class_name"];
    $hit_die = $_POST["hit_die"];
    $class_description = $_POST["class_description"];
    $feature = $_POST["feature"];

    // Insert data into the Class table
    $sqlClass = "INSERT INTO mainclass (class_name, hit_die, class_description, feature) VALUES (:class_name, :hit_die, :class_description, :feature)";
    connectToDB();
    $stmtClass = oci_parse($db_conn, $sqlClass);

    oci_bind_by_name($stmtClass, ":class_name", $class_name);
    oci_bind_by_name($stmtClass, ":hit_die", $hit_die);
    oci_bind_by_name($stmtClass, ":class_description", $class_description);
    oci_bind_by_name($stmtClass, ":feature", $feature);

    $resultClass = oci_execute($stmtClass);
    oci_free_statement($stmtClass);
    disconnectFromDB();

    // Check if the query was successful
    if ($resultClass) {
        echo "<meta http-equiv='refresh' content='0'>";
    } else {
        echo "Error adding class: " . oci_error($db_conn);
    }
} else if (isset($_POST['daction']) && $_POST['daction'] == 'Delete Class') {
    $name = $_POST["name"];

    $sqlClass = "DELETE FROM mainclass WHERE class_name=:name";
    connectToDB();
    $stmtClass = oci_parse($db_conn, $sqlClass);

    oci_bind_by_name($stmtClass, ":name", $name);

    $result = oci_execute($stmtClass);
    oci_free_statement($stmtClass);
    disconnectFromDB();

    if ($result) {
        echo "<meta http-equiv='refresh' content='0'>";
    } else {
        echo "Error deleting feat: " . oci_error($db_conn);
    }
} else {
    connectToDB();
    $stmtClasses = oci_parse($db_conn, "SELECT class_name, hit_die, feature FROM mainclass");
    $classesResult = oci_execute($stmtClasses);

    $nclasses = oci_fetch_all($stmtClasses, $results);

    oci_free_statement($stmtClasses);

    $stmtFeatures = oci_parse($db_conn, "SELECT feature_name FROM feature");
    $featuresResult = oci_execute($stmtFeatures);
    $nfeatures = oci_fetch_all($stmtFeatures, $features);

    oci_free_statement($stmtFeatures);

    disconnectFromDB();
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Class Table</title>
</head>
<body>
<h1>Design your class</h1>

<form method="POST">
    <!-- Add class name -->
    <label for="class_name">Class Name:</label>
    <input type="text" name="class_name">
    <br><br>

    <!-- Add hit_die -->
    <label for="hit_die">Hit Die:</label>
    <input type="text" name="hit_die">
    <br><br>

    <!-- Add class description -->
    <label for="class_description">Class Description:</label>
    <input type="text" name="class_description" size="100">
    <br><br>

    <!-- Add feature -->
    <label for="feature">Feat:</label>
    <select type="text" name="feature">
        <option value="">--- Select an added feature ---</option>
        <?php
        foreach ($features['FEATURE_NAME'] as $key => $name) {
            echo '<option value="' . $name . '">' . $name . '</option>';
        }
        ?>
    </select>
    <br><br>

    <input type="submit" name="action" value="Add Class">
</form>

<h1>List of Classes</h1>

<table class="table table-bordered text-center" name="Class Table" style="display: inline-block">
    <tr>
        <td>
            <table border='1' style="font-weight:bold">
                <tr>
                    <td>Class Name</td>
                </tr>
                <tr>
                    <td>Hit Die</td>
                </tr>
                <tr>
                    <td>Feature Name</td>
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

<h1>Delete Class</h1>
<form action="" method="post">
    <!-- Select item ID -->
    <label for="id">Class Name to delete:</label>
    <input type="text" name="name">
    <br><br>

    <input type="submit" name="daction" value="Delete Class">
</form>

</body>
</html>
