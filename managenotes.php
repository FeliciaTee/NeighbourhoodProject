<?php
session_start();
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: adminlogin.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "workshop project");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Delete note
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM notes WHERE note_id = $id");
    header("Location: managenotes.php");
    exit();
}

// Flag/Unflag note
if (isset($_GET['flag'])) {
    $id = intval($_GET['flag']);
    $conn->query("UPDATE notes SET is_flagged = 1 WHERE note_id = $id");
    header("Location: managenotes.php");
    exit();
}

if (isset($_GET['unflag'])) {
    $id = intval($_GET['unflag']);
    $conn->query("UPDATE notes SET is_flagged = 0 WHERE note_id = $id");
    header("Location: managenotes.php");
    exit();
}

// Get all notes
$result = $conn->query("SELECT * FROM notes ORDER BY date_created DESC");
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
      <li><a href="logout.php">Log Out</a></li>
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
        <li><a href="noticeboard.php">
          <img src="notice.png" alt="noticeboard" class="noticeboard">
          Notice Board
        </a></li>
      </ul>
    </div>

    <div class="content">
      <h2>Manage Notes</h2>
      <a href="addnote.php" class="btn-add">+ Add Note</a>

      <?php while ($row = $result->fetch_assoc()): ?>
        <div class="note-card <?= $row['is_flagged'] ? 'flagged' : '' ?>">
          <div class="note-title">
            <?= htmlspecialchars($row['title']) ?>
            <?php if ($row['is_flagged']): ?>
              <span class="flag-indicator">🔴 Flagged</span>
            <?php endif; ?>
          </div>
          <div class="note-content"><?= nl2br(htmlspecialchars($row['content'])) ?></div>
          <div class="note-time"><?= time_elapsed_string($row['date_created']) ?></div>
          <div class="note-buttons">
            <a href="editnote.php?id=<?= $row['note_id'] ?>" class="btn-edit">Edit</a>
            <a href="?delete=<?= $row['note_id'] ?>" class="btn-delete" onclick="return confirm('Delete this note?')">Delete</a>
            <?php if ($row['is_flagged']): ?>
              <a href="?unflag=<?= $row['note_id'] ?>" class="btn-flag">Unflag</a>
            <?php else: ?>
              <a href="?flag=<?= $row['note_id'] ?>" class="btn-flag">Flag</a>
            <?php endif; ?>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  </div>
</body>
</html>

<?php
function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $weeks = floor($diff->d / 7);
    $diff->d -= $weeks * 7;

    $string = [
        'y' => 'year',
        'm' => 'month',
        'w' => $weeks,
        'd' => 'day',
        'h' => 'hour',
        'i' => 'min',
        's' => 'sec',
    ];

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