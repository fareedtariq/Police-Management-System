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

    // Function to fetch all investigator records
    function fetchAllInvestigatorRecords()
    {
        global $conn;
        $sql = "SELECT * FROM `investigator`";
        $result = $conn->query($sql);
        $investigatorRecords = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $investigatorRecords[] = $row;
            }
        }
        return $investigatorRecords;
    }

    // Function to add a new investigator record
    function addInvestigatorRecord($Name, $Service, $Performance, $City)
    {
        global $conn;
        $Name = sanitize($Name);
        $Service = sanitize($Service);
        $Performance = sanitize($Performance);
        $City = sanitize($City);

        $sql = "INSERT INTO `investigator` (Name, Service, Performance, City) VALUES ('$Name', '$Service', '$Performance', '$City')";
        if ($conn->query($sql) === true) {
            return true;
        } else {
            return false;
        }
    }

    // Function to update an investigator record
    function updateInvestigatorRecord($I_id, $Name, $Service, $Performance, $City)
    {
        global $conn;
        $I_id = sanitize($I_id);
        $Name = sanitize($Name);
        $Service = sanitize($Service);
        $Performance = sanitize($Performance);
        $City = sanitize($City);

        $sql = "UPDATE `investigator` SET Name='$Name', Service='$Service', Performance='$Performance', City='$City' WHERE I_id=$I_id";
        if ($conn->query($sql) === true) {
            return true;
        } else {
            return false;
        }
    }

    // Function to delete an investigator record
    function deleteInvestigatorRecord($I_id)
    {
        global $conn;
        $I_id = sanitize($I_id);

        $sql = "DELETE FROM `investigator` WHERE I_id=$I_id";
        if ($conn->query($sql) === true) {
            return true;
        } else {
            return false;
        }
    }

    // Handle form submissions

    // Add new investigator record
    if (isset($_POST['add_record'])) {
        $Name = $_POST['Name'];
        $Service = $_POST['Service'];
        $Performance = $_POST['Performance'];
        $City = $_POST['City'];

        if (addInvestigatorRecord($Name, $Service, $Performance, $City)) {
            echo "Investigator record added successfully.";
        } else {
            echo "Failed to add investigator record.";
        }
    }

    // Update investigator record
    if (isset($_POST['update_record'])) {
        $I_id = $_POST['I_id'];
        $Name = $_POST['Name'];
        $Service = $_POST['Service'];
        $Performance = $_POST['Performance'];
        $City = $_POST['City'];

        if (updateInvestigatorRecord($I_id, $Name, $Service, $Performance, $City)) {
            echo "Investigator record updated successfully.";
        } else {
            echo "Failed to update investigator record.";
        }
    }

    // Delete investigator record
    if (isset($_POST['delete_record'])) {
        $I_id = $_POST['I_id'];

        if (deleteInvestigatorRecord($I_id)) {
            echo "Investigator record deleted successfully.";
        } else {
            echo "Failed to delete investigator record.";
        }
    }

    // Read all investigator records
    $investigatorRecords = fetchAllInvestigatorRecords();
    ?>

    <!-- Add Investigator Record Form -->
    <h2>Add Investigator Record</h2>
    <form method="POST">
        <label>Name:</label>
        <input type="text" name="Name" required><br>

        <label>Service:</label>
        <input type="text" name="Service" required><br>

        <label>Performance:</label>
        <input type="text" name="Performance" required><br>

        <label>City:</label>
        <input type="text" name="City" required><br>

        <input type="submit" name="add_record" value="Add Record">
    </form>

    <hr>

    <!-- Edit Investigator Record Form -->
    <h2>Edit Investigator Record</h2>
    <table>
        <tr>
            <th>Investigator ID</th>
            <th>Name</th>
            <th>Service</th>
            <th>Performance</th>
            <th>City</th>
            <th>Action</th>
        </tr>
        <?php foreach ($investigatorRecords as $record) : ?>
            <tr>
                <td><?php echo $record['I_id']; ?></td>
                <td><?php echo $record['Name']; ?></td>
                <td><?php echo $record['Service']; ?></td>
                <td><?php echo $record['Performance']; ?></td>
                <td><?php echo $record['City']; ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="I_id" value="<?php echo $record['I_id']; ?>">
                        <input type="text" name="Name" value="<?php echo $record['Name']; ?>">
                        <input type="text" name="Service" value="<?php echo $record['Service']; ?>">
                        <input type="text" name="Performance" value="<?php echo $record['Performance']; ?>">
                        <input type="text" name="City" value="<?php echo $record['City']; ?>">
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
