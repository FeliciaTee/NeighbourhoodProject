<?php
include 'connect.php';

$sql = "SELECT title, content, created_at FROM notes WHERE is_flagged = 0 ORDER BY created_at DESC";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    echo "<div class='announcement-card'>";
    echo "<strong>" . htmlspecialchars($row['title']) . "</strong><br>";
    echo nl2br(htmlspecialchars($row['content'])) . "<br>";
    echo "<small>Posted on: " . $row['created_at'] . "</small>";
    echo "</div>";
}
?>