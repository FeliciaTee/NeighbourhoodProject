<?php
include 'connect.php';

$sql = "SELECT title, content, created_at FROM notes ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        echo "<div class='announcement-card'>";
        echo "<strong>" . htmlspecialchars($row['title']) . "</strong>";
        echo "<p>" . nl2br(htmlspecialchars($row['content'])) . "</p>";
        echo "<small>" . date("F j, Y g:i A", strtotime($row['created_at'])) . "</small>";
        echo "</div>";
    }
} else {
    echo "<p>No notes found.</p>";
}
?>

