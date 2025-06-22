<?php
$mysqli = new mysqli("localhost", "root", "", "your_database_name");
if ($mysqli->connect_errno) {
    die("Database connection failed.");
}

$notification_id = $mysqli->real_escape_string($_GET['id']);

$query = "SELECT r.name, c.comment_text, c.created_at
          FROM comments c
          JOIN residents r ON c.resident_id = r.resident_id
          WHERE c.notification_id = '$notification_id'
          ORDER BY c.created_at DESC";

$result = $mysqli->query($query);
while ($row = $result->fetch_assoc()) {
    echo "<p><strong>" . htmlspecialchars($row['name']) . ":</strong> "
       . htmlspecialchars($row['comment_text'])
       . " <br><small><em style='color:gray;'>"
       . $row['created_at'] . "</em></small></p><hr>";
}
?>
