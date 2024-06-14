<?php
include_once('connection.php');
global $db_conn;

if (isset($_POST['action']) && $_POST['action'] == 'Add Character') {
    // Get user input from the form
    $char_name = $_POST["char_name"];
    $char_strength = $_POST["char_strength"];
    $char_dexterity = $_POST["char_dexterity"];
    $char_constitution = $_POST["char_constitution"];
    $char_intelligence = $_POST["char_intelligence"];
    $char_wisdom = $_POST["char_wisdom"];
    $char_charisma = $_POST["char_charisma"];
    $race_name = $_POST["race_name"];
    $bgnd_name = $_POST["bgnd_name"];

    // Insert data into the Character table
    $sqlCharacter = "INSERT INTO character (char_name, str, dex, con, int_stat, wis, chr, race_name, bgnd_name) 
                             VALUES (:char_name, :char_strength, :char_dexterity, :char_constitution, :char_intelligence, :char_wisdom, :char_charisma, :race_name, :bgnd_name)";
    connectToDB();
    $stmtCharacter = oci_parse($db_conn, $sqlCharacter);

    oci_bind_by_name($stmtCharacter, ":char_name", $char_name);
    oci_bind_by_name($stmtCharacter, ":char_strength", $char_strength);
    oci_bind_by_name($stmtCharacter, ":char_dexterity", $char_dexterity);
    oci_bind_by_name($stmtCharacter, ":char_constitution", $char_constitution);
    oci_bind_by_name($stmtCharacter, ":char_intelligence", $char_intelligence);
    oci_bind_by_name($stmtCharacter, ":char_wisdom", $char_wisdom);
    oci_bind_by_name($stmtCharacter, ":char_charisma", $char_charisma);
    oci_bind_by_name($stmtCharacter, ":race_name", $race_name);
    oci_bind_by_name($stmtCharacter, ":bgnd_name", $bgnd_name);

    $resultCharacter = oci_execute($stmtCharacter);
    oci_free_statement($stmtCharacter);
    disconnectFromDB();

    // Check if the query was successful
    if ($resultCharacter) {
        echo "<meta http-equiv='refresh' content='0'>";
    } else {
        echo "Error adding character: " . oci_error($db_conn);
    }
}  else if (isset($_POST['daction']) && $_POST['daction'] == 'Delete Character') {
    $name = $_POST["name"];

    $sqlChar = "DELETE FROM character WHERE char_name=:name";
    connectToDB();
    $stmtChar = oci_parse($db_conn, $sqlChar);

    oci_bind_by_name($stmtChar, ":name", $name);

    $result = oci_execute($stmtChar);
    oci_free_statement($stmtChar);
    disconnectFromDB();

    if ($result) {
        echo "<meta http-equiv='refresh' content='0'>";
    } else {
        echo "Error deleting character: " . oci_error($db_conn);
    }
} else {
    connectToDB();
    $stmtChars = oci_parse($db_conn, "SELECT char_name, str, dex, con, int_stat, wis, chr, race_name, bgnd_name FROM character");
    $charsResult = oci_execute($stmtChars);

    $nchars = oci_fetch_all($stmtChars, $results);

    oci_free_statement($stmtChars);

    $stmtRaces = oci_parse($db_conn, "SELECT race_name FROM race");
    $racesResult = oci_execute($stmtRaces);
    $nRaces = oci_fetch_all($stmtRaces, $races);

    oci_free_statement($stmtRaces);

    $stmtBackgrounds = oci_parse($db_conn, "SELECT bgnd_name FROM background");
    $backgroundsResult = oci_execute($stmtBackgrounds);
    $nFeatures = oci_fetch_all($stmtBackgrounds, $backgrounds);

    oci_free_statement($stmtBackgrounds);

    disconnectFromDB();
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Character Table</title>
</head>
<body>
<h1>Design your character</h1>

<form action="" method="POST">
    <!-- Add character name -->
    <label for="char_name">Name:</label>
    <input type="text" name="char_name">
    <br><br>

    <!-- Add character strength -->
    <label for="char_strength">Strength:</label>
    <input type="number" name="char_strength">
    <br><br>

    <!-- Add character dexterity -->
    <label for="char_dexterity">Dexterity:</label>
    <input type="number" name="char_dexterity">
    <br><br>

    <!-- Add character constitution -->
    <label for="char_constitution">Constitution:</label>
    <input type="number" name="char_constitution">
    <br><br>

    <!-- Add character intelligence -->
    <label for="char_intelligence">Intelligence:</label>
    <input type="number" name="char_intelligence">
    <br><br>

    <!-- Add character wisdom -->
    <label for="char_wisdom">Wisdom:</label>
    <input type="number" name="char_wisdom">
    <br><br>

    <!-- Add character charisma -->
    <label for="char_charisma">Charisma:</label>
    <input type="number" name="char_charisma">
    <br><br>

    <!-- Add race name  -->
    <label for="race">Race:</label>
    <select type="text" name="race_name">
    <option value="">--- Select an added race ---</option>
    <?php
    foreach ($races['RACE_NAME'] as $key => $name) {
        echo '<option value="' . $name . '">' . $name . '</option>';
    }
    ?>
    </select>
    <br><br>

    <!-- Add background name  -->
    <label for="bgnd_name">Background:</label>
    <select type="text" name="bgnd_name">
    <option value="">--- Select an added background ---</option>
    <?php
    foreach ($backgrounds['BGND_NAME'] as $key => $name) {
        echo '<option value="' . $name . '">' . $name . '</option>';
    }
    ?>
    </select>
    <br><br>

    <input type="submit" name="action" value="Add Character">
</form>

<h1>List of Characters</h1>

<table class="table table-bordered text-center" name="Character Table" style="display: inline-block">
    <tr>
        <td>
            <table border='1' style="font-weight:bold">
                <tr>
                    <td>Name</td>
                </tr>
                <tr>
                    <td>Strength</td>
                </tr>
                <tr>
                    <td>Dexterity</td>
                </tr>
                <tr>
                    <td>Constitution</td>
                </tr>
                <tr>
                    <td>Intelligence</td>
                </tr>
                <tr>
                    <td>Wisdom</td>
                </tr>
                <tr>
                    <td>Charisma</td>
                </tr>
                <tr>
                    <td>Race</td>
                </tr>
                <tr>
                    <td>Background</td>
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

<h1>Delete Character</h1>
<form action="" method="post">
    <label for="id">Character Name to delete:</label>
    <input type="text" name="name">
    <br><br>

    <input type="submit" name="daction" value="Delete Character">
</form>

</body>
</html>