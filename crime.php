<!DOCTYPE html>
<html>

<head>
    <title>Police Management</title>
</head>

<body>
    <h1>Police Management</h1>

    <?php
    // Include the database connection file
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'pms';

    // Create a connection
    $conn = new mysqli($host, $username, $password, $database);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Function to sanitize user input
    function sanitize($data)
    {
        global $conn;
        return mysqli_real_escape_string($conn, $data);
    }

    // Function to fetch all criminal records
    function fetchAllCriminalRecords()
    {
        global $conn;
        $sql = "SELECT * FROM `crime`";
        $result = $conn->query($sql);
        $criminalRecords = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $criminalRecords[] = $row;
            }
        }
        return $criminalRecords;
    }

    // Function to add a new criminal record
    function addCriminalRecord($Name, $Crime_type, $Act_Law, $Criminal_id, $I_id)
    {
        global $conn;
        $Name = sanitize($Name);
        $Crime_type = sanitize($Crime_type);
        $Act_Law = sanitize($Act_Law);
        $Criminal_id = sanitize($Criminal_id);
        $I_id = sanitize($I_id);

        $sql = "INSERT INTO `crime` (Name, Crime_type, Act_Law, Criminal_id, I_id) VALUES ('$Name', '$Crime_type', '$Act_Law', '$Criminal_id', '$I_id')";
        if ($conn->query($sql) === true) {
            return true;
        } else {
            return false;
        }
    }

    // Function to update a criminal record
    function updateCriminalRecord($Crime_id, $Name, $Crime_type, $Act_Law, $Criminal_id, $I_id)
    {
        global $conn;
        $Crime_id = sanitize($Crime_id);
        $Name = sanitize($Name);
        $Crime_type = sanitize($Crime_type);
        $Act_Law = sanitize($Act_Law);
        $Criminal_id = sanitize($Criminal_id);
        $I_id = sanitize($I_id);

        $sql = "UPDATE `crime` SET Name='$Name', Crime_type='$Crime_type', Act_Law='$Act_Law', Criminal_id='$Criminal_id', I_id='$I_id' WHERE Crime_id=$Crime_id";
        if ($conn->query($sql) === true) {
            return true;
        } else {
            return false;
        }
    }

    // Function to delete a criminal record
    function deleteCriminalRecord($Crime_id)
    {
        global $conn;
        $Crime_id = sanitize($Crime_id);

        $sql = "DELETE FROM `crime` WHERE Crime_id=$Crime_id";
        if ($conn->query($sql) === true) {
            return true;
        } else {
            return false;
        }
    }

    // Handle form submissions

    // Add new criminal record
    if (isset($_POST['add_record'])) {
        $Name = $_POST['Name'];
        $Crime_type = $_POST['Crime_type'];
        $Act_Law = $_POST['Act_Law'];
        $Criminal_id = $_POST['Criminal_id'];
        $I_id = $_POST['I_id'];

        if (addCriminalRecord($Name, $Crime_type, $Act_Law, $Criminal_id, $I_id)) {
            echo "Criminal record added successfully.";
        } else {
            echo "Failed to add criminal record.";
        }
    }

    // Update criminal record
    if (isset($_POST['update_record'])) {
        $Crime_id = $_POST['Crime_id'];
        $Name = $_POST['Name'];
        $Crime_type = $_POST['Crime_type'];
        $Act_Law = $_POST['Act_Law'];
        $Criminal_id = $_POST['Criminal_id'];
        $I_id = $_POST['I_id'];

        if (updateCriminalRecord($Crime_id, $Name, $Crime_type, $Act_Law, $Criminal_id, $I_id)) {
            echo "Criminal record updated successfully.";
        } else {
            echo "Failed to update criminal record.";
        }
    }

    // Delete criminal record
    if (isset($_POST['delete_record'])) {
        $Crime_id = $_POST['Crime_id'];

        if (deleteCriminalRecord($Crime_id)) {
            echo "Criminal record deleted successfully.";
        } else {
            echo "Failed to delete criminal record.";
        }
    }

    // Read all criminal records
    $criminalRecords = fetchAllCriminalRecords();
    ?>

    <!-- Add Criminal Record Form -->
    <h2>Add Crime Record</h2>
    <form method="POST">
        <label>Name:</label>
        <input type="text" name="Name" required><br>

        <label>Crime Type:</label>
        <input type="text" name="Crime_type" required><br>

        <label>Act/Law:</label>
        <input type="text" name="Act_Law" required><br>

        <label>Criminal ID:</label>
        <input type="text" name="Criminal_id" required><br>

        <label>Investigator ID:</label>
        <input type="text" name="I_id" required><br>

        <input type="submit" name="add_record" value="Add Record">
    </form>

    <hr>

    <!-- Edit Criminal Record Form -->
    <h2>Edit Criminal Record</h2>
    <table>
        <tr>
            <th>Crime ID</th>
            <th>Name</th>
            <th>Crime Type</th>
            <th>Act/Law</th>
            <th>Criminal ID</th>
            <th>Investigator ID</th>
            <th>Action</th>
        </tr>
        <?php foreach ($criminalRecords as $record) : ?>
            <tr>
                <td><?php echo $record['Crime_id']; ?></td>
                <td><?php echo $record['Name']; ?></td>
                <td><?php echo $record['Crime_type']; ?></td>
                <td><?php echo $record['Act_Law']; ?></td>
                <td><?php echo $record['Criminal_id']; ?></td>
                <td><?php echo $record['I_id']; ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="Crime_id" value="<?php echo $record['Crime_id']; ?>">
                        <input type="text" name="Name" value="<?php echo $record['Name']; ?>">
                        <input type="text" name="Crime_type" value="<?php echo $record['Crime_type']; ?>">
                        <input type="text" name="Act_Law" value="<?php echo $record['Act_Law']; ?>">
                        <input type="text" name="Criminal_id" value="<?php echo $record['Criminal_id']; ?>">
                        <input type="text" name="I_id" value="<?php echo $record['I_id']; ?>">
                        <input type="submit" name="update_record" value="Update">
                        <input type="submit" name="delete_record" value="Delete">
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <?php
    $conn->close();
    ?>

</body>

</html>
