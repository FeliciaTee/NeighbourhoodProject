<?php
$conn = new mysqli("localhost", "root", "", "workshop project");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM reports WHERE report_id=$id");
    header("Location: uploadedreports.php");
    exit();
}

$result = $conn->query("SELECT * FROM reports ORDER BY date_created DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Uploaded Reports</title>
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
          <h2>Resident Reports</h2>
  <?php while ($row = $result->fetch_assoc()): ?>
    <div class="note-card">
      <div class="note-left">
        <div class="note-title"><?= htmlspecialchars($row['title']) ?> (<?= htmlspecialchars($row['category']) ?>)</div>
        <div class="note-content">
          <strong>Location:</strong> <?= htmlspecialchars($row['location']) ?><br>
          <strong>User ID:</strong> <?= htmlspecialchars($row['user_id']) ?><br>
          <?= nl2br(htmlspecialchars($row['description'])) ?>
        </div>
        <div class="note-time"><?= time_elapsed_string($row['created_at']) ?></div>
        <div class="note-buttons">
          <a href="viewreport.php?id=<?= $row['report_id'] ?>" class="btn-view">View</a>
          <a href="updatereport.php?id=<?= $row['report_id'] ?>" class="btn-flag">Update</a>
          <a href="?delete=<?= $row['report_id'] ?>" class="btn-delete" onclick="return confirm('Delete this report?')">Delete</a>
        </div>
      </div>
      <div class="note-right">
        <?php if (!empty($row['image'])): ?>
          <img src="<?= htmlspecialchars($row['image']) ?>" alt="Report Image">
        <?php else: ?>
          <img src="report-placeholder.jpg" alt="No Image">
        <?php endif; ?>
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