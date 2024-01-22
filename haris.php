<?php
// Include the database connection file
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'cricket';

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

// Function to fetch all matches
function fetchAllMatches()
{
    global $conn;
    $sql = "SELECT * FROM `match`";
    $result = $conn->query($sql);
    $matches = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $matches[] = $row;
        }
    }
    return $matches;
}

// Function to add a new match
function addMatch($matchDate, $matchTime)
{
    global $conn;
    $matchDate = sanitize($matchDate);
    $matchTime = sanitize($matchTime);

    $sql = "INSERT INTO `match` (Match_date, Match_time) VALUES ('$matchDate', '$matchTime')";
    if ($conn->query($sql) === true) {
        return true;
    } else {
        return false;
    }
}

// Function to update a match
function updateMatch($matchId, $matchDate, $matchTime)
{
    global $conn;
    $matchId = sanitize($matchId);
    $matchDate = sanitize($matchDate);
    $matchTime = sanitize($matchTime);

    $sql = "UPDATE `match` SET Match_date='$matchDate', Match_time='$matchTime' WHERE Match_id=$matchId";
    if ($conn->query($sql) === true) {
        return true;
    } else {
        return false;
    }
}

// Function to delete a match
function deleteMatch($matchId)
{
    global $conn;
    $matchId = sanitize($matchId);

    $sql = "DELETE FROM `match` WHERE Match_id=$matchId";
    if ($conn->query($sql) === true) {
        return true;
    } else {
        return false;
    }
}

// Handle form submissions

// Add new match
if (isset($_POST['add_match'])) {
    $matchDate = $_POST['match_date'];
    $matchTime = $_POST['match_time'];

    if (addMatch($matchDate, $matchTime)) {
        echo "Match added successfully.";
    } else {
        echo "Failed to add match.";
    }
}

// Update match
if (isset($_POST['update_match'])) {
    $matchId = $_POST['match_id'];
    $matchDate = $_POST['match_date'];
    $matchTime = $_POST['match_time'];

    if (updateMatch($matchId, $matchDate, $matchTime)) {
        echo "Match updated successfully.";
    } else {
        echo "Failed to update match.";
    }
}

// Delete match
if (isset($_POST['delete_match'])) {
    $matchId = $_POST['match_id'];

    if (deleteMatch($matchId)) {
        echo "Match deleted successfully.";
    } else {
        echo "Failed to delete match.";
    }
}

// Read all matches
$matches = fetchAllMatches();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Match Management</title>
</head>

<body>
    <h1>Match Management</h1>

    <!-- Add Match Form -->
    <h2>Add Match</h2>
    <form method="POST">
        <label>Match Date:</label>
        <input type="date" name="match_date" required><br>

        <label>Match Time:</label>
        <input type="time" name="match_time" required><br>

        <input type="submit" name="add_match" value="Add Match">
    </form>

    <hr>

    <!-- Edit Match Form -->
    <h2>Edit Match</h2>
    <table>
        <tr>
            <th>Match ID</th>
            <th>Match Date</th>
            <th>Match Time</th>
            <th>Action</th>
        </tr>
        <?php foreach ($matches as $match) : ?>
            <tr>
                <td><?php echo $match['Match_id']; ?></td>
                <td><?php echo $match['Match_date']; ?></td>
                <td><?php echo $match['Match_time']; ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="match_id" value="<?php echo $match['Match_id']; ?>">
                        <input type="date" name="match_date" value="<?php echo $match['Match_date']; ?>">
                        <input type="time" name="match_time" value="<?php echo $match['Match_time']; ?>">
                        <input type="submit" name="update_match" value="Update">
                        <input type="submit" name="delete_match" value="Delete">
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

</body>

</html>

<?php
$conn->close();
?>