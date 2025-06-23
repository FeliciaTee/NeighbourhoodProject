<?php
$conn = new mysqli("localhost", "root", "", "workshop project");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['delete'])) {
  $id = $_GET['delete'];
  $conn->query("DELETE FROM notes WHERE note_id=$id");
  header("Location: managenotes.php");
  exit();
}

$result = $conn->query("SELECT * FROM notes ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Notes</title>
  <link rel="stylesheet" href="adminstyle.css">
</head>

<body>
        <div class="main">
            <ul>
                <img src="banner.png" alt="banner" width="200" height="100" class="banner">
                <li><a href="#">Home</a></li>
                <li><a href="about.html">About Us</a></li>
                <li><a href="logout.html">Log Out</a></li>
            </ul>
        </div>

        <h1>
            <a href="adminprofile.php">
            <img src="circle-user.png" alt="userprofile" width="40" height="40" class="userprofile">
            </a>
        </h1>

        <div class="dashboard">
        <div class="sidebar">
        <ul>
            <li><a href="uploadedreports.php">
            <img src="document.png" alt="uploadedreports" class="uploadedreports">
            Uploaded Reports
            </a></li>
            <li><a href="managenotes.php">
            <img src="bell.png" alt="managenotes" class="managenotes">
            Manage Notes
            </a></li>
            <li><a href="noticeboard.html">
            <img src="notice.png" alt="noticeboard" class="noticeboard">
            Notice Board
            </a></li>
        </ul>
        </div>
          <h2>Community Notes</h2>
   <?php while ($row = $result->fetch_assoc()): ?>
    <div class="note-card">
      <div class="note-left">
        <div class="note-title"><?= htmlspecialchars($row['title']) ?></div>
        <div class="note-content"><?= htmlspecialchars($row['content']) ?></div>
        <div class="note-time"><?= time_elapsed_string($row['created_at']) ?></div>
        <div class="note-buttons">
          <a href="viewnote.php?id=<?= $row['note_id'] ?>" class="btn-view">View Details</a>
          <a href="flagnote.php?id=<?= $row['note_id'] ?>" class="btn-flag">Flag</a>
          <a href="?delete=<?= $row['note_id'] ?>" class="btn-delete" onclick="return confirm('Delete this note?')">Delete</a>
        </div>
      </div>
      <div class="note-right">
        <img src="placeholder.jpg" alt="Note Image">
      </div>
    </div>
  <?php endwhile; ?>

</body>
</html>

<?php

function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $weeks = floor($diff->d / 7);
    $diff->d -= $weeks * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => $weeks,
        'd' => 'day',
        'h' => 'hour',
        'i' => 'min',
        's' => 'sec',
    );

    foreach ($string as $k => &$v) {
        if ($k === 'w') {
            if ($v > 0) {
                $v = $v . ' week' . ($v > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        } elseif ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}
?>