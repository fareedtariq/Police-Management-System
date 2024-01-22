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
        $sql = "SELECT * FROM `criminal`";
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
    function addCriminalRecord($Name, $Address, $CNIC, $I_id)
    {
        global $conn;
        $Name = sanitize($Name);
        $Address = sanitize($Address);
        $CNIC = sanitize($CNIC);
        $I_id = sanitize($I_id);

        $sql = "INSERT INTO `criminal` (Name, Address, CNIC, I_id) VALUES ('$Name', '$Address', '$CNIC', '$I_id')";
        if ($conn->query($sql) === true) {
            return true;
        } else {
            return false;
        }
    }

    // Function to update a criminal record
    function updateCriminalRecord($Criminal_id, $Name, $Address, $CNIC, $I_id)
    {
        global $conn;
        $Criminal_id = sanitize($Criminal_id);
        $Name = sanitize($Name);
        $Address = sanitize($Address);
        $CNIC = sanitize($CNIC);
        $I_id = sanitize($I_id);

        $sql = "UPDATE `criminal` SET Name='$Name', Address='$Address', CNIC='$CNIC', I_id='$I_id' WHERE Criminal_id=$Criminal_id";
        if ($conn->query($sql) === true) {
            return true;
        } else {
            return false;
        }
    }

    // Function to delete a criminal record
    function deleteCriminalRecord($Criminal_id)
    {
        global $conn;
        $Criminal_id = sanitize($Criminal_id);

        $sql = "DELETE FROM `criminal` WHERE Criminal_id=$Criminal_id";
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
        $Address = $_POST['Address'];
        $CNIC = $_POST['CNIC'];
        $I_id = $_POST['I_id'];

        if (addCriminalRecord($Name, $Address, $CNIC, $I_id)) {
            echo "Criminal record added successfully.";
        } else {
            echo "Failed to add criminal record.";
        }
    }

    // Update criminal record
    if (isset($_POST['update_record'])) {
        $Criminal_id = $_POST['Criminal_id'];
        $Name = $_POST['Name'];
        $Address = $_POST['Address'];
        $CNIC = $_POST['CNIC'];
        $I_id = $_POST['I_id'];

        if (updateCriminalRecord($Criminal_id, $Name, $Address, $CNIC, $I_id)) {
            echo "Criminal record updated successfully.";
        } else {
            echo "Failed to update criminal record.";
        }
    }

    // Delete criminal record
    if (isset($_POST['delete_record'])) {
        $Criminal_id = $_POST['Criminal_id'];

        if (deleteCriminalRecord($Criminal_id)) {
            echo "Criminal record deleted successfully.";
        } else {
            echo "Failed to delete criminal record.";
        }
    }

    // Read all criminal records
    $criminalRecords = fetchAllCriminalRecords();
    ?>

    <!-- Add Criminal Record Form -->
    <h2>Add Criminal Record</h2>
    <form method="POST">
        <label>Name:</label>
        <input type="text" name="Name" required><br>

        <label>Address:</label>
        <input type="text" name="Address" required><br>

        <label>CNIC:</label>
        <input type="text" name="CNIC" required><br>

        <label>Investigator ID:</label>
        <input type="text" name="I_id" required><br>

        <input type="submit" name="add_record" value="Add Record">
    </form>

    <hr>

    <!-- Edit Criminal Record Form -->
    <h2>Edit Criminal Record</h2>
    <table>
        <tr>
            <th>Criminal ID</th>
            <th>Name</th>
            <th>Address</th>
            <th>CNIC</th>
            <th>Investigator ID</th>
            <th>Action</th>
        </tr>
        <?php foreach ($criminalRecords as $record) : ?>
            <tr>
                <td><?php echo $record['Criminal_id']; ?></td>
                <td><?php echo $record['Name']; ?></td>
                <td><?php echo $record['Address']; ?></td>
                <td><?php echo $record['CNIC']; ?></td>
                <td><?php echo $record['I_id']; ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="Criminal_id" value="<?php echo $record['Criminal_id']; ?>">
                        <input type="text" name="Name" value="<?php echo $record['Name']; ?>">
                        <input type="text" name="Address" value="<?php echo $record['Address']; ?>">
                        <input type="text" name="CNIC" value="<?php echo $record['CNIC']; ?>">
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
