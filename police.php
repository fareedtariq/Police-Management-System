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

    // Function to fetch all police records
    function fetchAllPoliceRecords()
    {
        global $conn;
        $sql = "SELECT * FROM `police`";
        $result = $conn->query($sql);
        $policeRecords = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $policeRecords[] = $row;
            }
        }
        return $policeRecords;
    }

    // Function to add a new police record
    function addPoliceRecord($Address, $City, $Station)
    {
        global $conn;
        $Address = sanitize($Address);
        $City = sanitize($City);
        $Station = sanitize($Station);

        $sql = "INSERT INTO `police` (Address, City, Station) VALUES ('$Address', '$City', '$Station')";
        if ($conn->query($sql) === true) {
            return true;
        } else {
            return false;
        }
    }

    // Function to update a police record
    function updatePoliceRecord($P_id, $Address, $City, $Station)
    {
        global $conn;
        $P_id = sanitize($P_id);
        $Address = sanitize($Address);
        $City = sanitize($City);
        $Station = sanitize($Station);

        $sql = "UPDATE `police` SET Address='$Address', City='$City', Station='$Station' WHERE P_id=$P_id";
        if ($conn->query($sql) === true) {
            return true;
        } else {
            return false;
        }
    }

    // Function to delete a police record
    function deletePoliceRecord($P_id)
    {
        global $conn;
        $P_id = sanitize($P_id);

        $sql = "DELETE FROM `police` WHERE P_id=$P_id";
        if ($conn->query($sql) === true) {
            return true;
        } else {
            return false;
        }
    }

    // Handle form submissions

    // Add new police record
    if (isset($_POST['add_record'])) {
        $Address = $_POST['Address'];
        $City = $_POST['City'];
        $Station = $_POST['Station'];

        if (addPoliceRecord($Address, $City, $Station)) {
            echo "Police record added successfully.";
        } else {
            echo "Failed to add police record.";
        }
    }

    // Update police record
    if (isset($_POST['update_record'])) {
        $P_id = $_POST['P_id'];
        $Address = $_POST['Address'];
        $City = $_POST['City'];
        $Station = $_POST['Station'];

        if (updatePoliceRecord($P_id, $Address, $City, $Station)) {
            echo "Police record updated successfully.";
        } else {
            echo "Failed to update police record.";
        }
    }

    // Delete police record
    if (isset($_POST['delete_record'])) {
        $P_id = $_POST['P_id'];

        if (deletePoliceRecord($P_id)) {
            echo "Police record deleted successfully.";
        } else {
            echo "Failed to delete police record.";
        }
    }

    // Read all police records
    $policeRecords = fetchAllPoliceRecords();
    ?>

    <!-- Add Police Record Form -->
    <h2>Add Police Record</h2>
    <form method="POST">
        <label>Address:</label>
        <input type="text" name="Address" required><br>

        <label>City:</label>
        <input type="text" name="City" required><br>

        <label>Station:</label>
        <input type="text" name="Station" required><br>

        <input type="submit" name="add_record" value="Add Record">
    </form>

    <hr>

    <!-- Edit Police Record Form -->
    <h2>Edit Police Record</h2>
    <table>
        <tr>
            <th>Police ID</th>
            <th>Address</th>
            <th>City</th>
            <th>Station</th>
            <th>Action</th>
        </tr>
        <?php foreach ($policeRecords as $record) : ?>
            <tr>
                <td><?php echo $record['P_id']; ?></td>
                <td><?php echo $record['Address']; ?></td>
                <td><?php echo $record['City']; ?></td>
                <td><?php echo $record['Station']; ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="P_id" value="<?php echo $record['P_id']; ?>">
                        <input type="text" name="Address" value="<?php echo $record['Address']; ?>">
                        <input type="text" name="City" value="<?php echo $record['City']; ?>">
                        <input type="text" name="Station" value="<?php echo $record['Station']; ?>">
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
