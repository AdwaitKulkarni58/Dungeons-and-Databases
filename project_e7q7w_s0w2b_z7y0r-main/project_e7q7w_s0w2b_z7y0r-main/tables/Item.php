<?php
include_once('connection.php');
global $db_conn;

$lastID = 1;
$sqlItem = "SELECT MAX(item_id) FROM item";
connectToDB();
$stmtItem = oci_parse($db_conn, $sqlItem);
$result = oci_execute($stmtItem);
if ($result && oci_fetch($stmtItem)) {
    $lastID = oci_result($stmtItem, 1);
}

oci_free_statement($stmtItem);
disconnectFromDB();

if (isset($_POST['action']) && $_POST['action'] == 'Add Item') {
    // Get user input from the form
    $item_name = $_POST["item_name"];
    $item_type = $_POST["item_type"];
    $item_weight = $_POST["item_weight"];
    $item_cost = $_POST["item_cost"];

    // Insert data into the Item table
    $sqlItem = "INSERT INTO item (item_id, item_name, item_type, item_weight, item_cost) VALUES ($lastID + 1, :item_name, :item_type, :item_weight, :item_cost)";
    connectToDB();
    $stmtItem = oci_parse($db_conn, $sqlItem);

    oci_bind_by_name($stmtItem, ":item_name", $item_name);
    oci_bind_by_name($stmtItem, ":item_type", $item_type);
    oci_bind_by_name($stmtItem, ":item_weight", $item_weight);
    oci_bind_by_name($stmtItem, ":item_cost", $item_cost);

    $resultItem = oci_execute($stmtItem);
    oci_free_statement($stmtItem);
    disconnectFromDB();

    // Check if the query was successful
    if ($resultItem) {
        echo "<meta http-equiv='refresh' content='0'>";
    } else {
        echo "Error adding item: " . oci_error($db_conn);
    }
} else if (isset($_POST['daction']) && $_POST['daction'] == 'Delete Item') {
    $id = $_POST["id"];

    $sqlItem = "DELETE FROM item WHERE item_id=:id";
    connectToDB();
    $stmtItem = oci_parse($db_conn, $sqlItem);

    oci_bind_by_name($stmtItem, ":id", $id);

    $result = oci_execute($stmtItem);
    oci_free_statement($stmtItem);
    disconnectFromDB();

    if ($result) {
        echo "<meta http-equiv='refresh' content='0'>";
    } else {
        echo "Error deleting item: " . oci_error($db_conn);
    }
} else {
    connectToDB();
    $stmtItems = oci_parse($db_conn, "SELECT * FROM item");
    $itemsResult = oci_execute($stmtItems);

    $nitems = oci_fetch_all($stmtItems, $results);

    oci_free_statement($stmtItems);
    disconnectFromDB();
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Item table</title>
</head>
<body>
<h1>Design your item</h1>

<form action="" method="post">
    <!-- Add item name -->
    <label for="item_name">Item Name:</label>
    <input type="text" name="item_name">
    <br><br>

    <!-- Add item type -->
    <label for="item_type">Item Type:</label>
    <select type="text" name="item_type">
        <option value="">--- Select a type ---</option>
        <option value="WEAPON">Weapon</option>
        <option value="ETC">ETC</option>
    </select>
    <br><br>

    <!-- Add item weight -->
    <label for="item_weight">Item Weight:</label>
    <input type="number" name="item_weight">
    <br><br>

    <!-- Add item cost -->
    <label for="item_cost">Item Cost:</label>
    <input type="number" name="item_cost">
    <br><br>

    <input type="submit" name="action" value="Add Item">
</form>

<h1>List of Items</h1>

<table class="table table-bordered text-center" name="Item Table" style="display: inline-block">
    <tr>
        <td>
            <table border='1' style="font-weight:bold">
                <tr>
                    <td>Item ID</td>
                </tr>
                <tr>
                    <td>Item Name</td>
                </tr>
                <tr>
                    <td>Item Type</td>
                </tr>
                <tr>
                    <td>Item Weight</td>
                </tr>
                <tr>
                    <td>Item Cost</td>
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

<h1>Delete Item</h1>
<form action="" method="post">
    <!-- Select item ID -->
    <label for="id">Item ID to delete:</label>
    <input type="number" name="id">
    <br><br>

    <input type="submit" name="daction" value="Delete Item">
</form>

</body>
</html>
