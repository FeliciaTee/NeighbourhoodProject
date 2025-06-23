<?php
session_start();
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: adminlogin.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "neighborhoodproject");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Delete report
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM reports WHERE report_id = $id");
    header("Location: uploadedreports.php");
    exit();
}

// Update report
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_report'])) {
    $id = intval($_POST['report_id']);
    $title = $conn->real_escape_string($_POST['title']);
    $category = $conn->real_escape_string($_POST['category']);
    $location = $conn->real_escape_string($_POST['location']);
    $description = $conn->real_escape_string($_POST['description']);

    $conn->query("UPDATE reports SET title='$title', category='$category', location='$location', description='$description' WHERE report_id=$id");
    header("Location: uploadedreports.php");
    exit();
}

// Search reports
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$sql = "SELECT * FROM reports";
if (!empty($search)) {
    $sql .= " WHERE title LIKE '%$search%' OR category LIKE '%$search%'";
}
$sql .= " ORDER BY created_at DESC";
$result = $conn->query($sql);
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
      <li><a href="index.html">Home</a></li>
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
        <li><a href="uploadedreports.php"><img src="document.png" alt="uploadedreports"> Uploaded Reports</a></li>
        <li><a href="managenotes.php"><img src="bell.png" alt="managenotes"> Manage Notes</a></li>
        <li><a href="noticeboard.php"><img src="notice.png" alt="noticeboard"> Notice Board</a></li>
      </ul>
    </div>

    <div class="content">
      <h2>Resident Reports</h2>

      <form method="GET" style="margin-bottom: 20px;">
        <input type="text" name="search" placeholder="Search by title or category..." value="<?= htmlspecialchars($search) ?>">
        <button type="submit">Search</button>
      </form>

      <?php while ($row = $result->fetch_assoc()): ?>
        <form method="POST" class="note-card">
          <input type="hidden" name="report_id" value="<?= $row['report_id'] ?>">
          <div class="note-left">
            <label><strong>Title:</strong></label>
            <input type="text" name="title" value="<?= htmlspecialchars($row['title']) ?>">

            <label><strong>Category:</strong></label>
            <input type="text" name="category" value="<?= htmlspecialchars($row['category']) ?>">

            <label><strong>Location:</strong></label>
            <input type="text" name="location" value="<?= htmlspecialchars($row['location']) ?>">

            <label><strong>Description:</strong></label>
            <textarea name="description"><?= htmlspecialchars($row['description']) ?></textarea>

            <div class="note-time"><?= time_elapsed_string($row['created_at']) ?></div>

            <div class="note-buttons">
              <button type="submit" name="update_report" class="btn-flag">Save</button>
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
        </form>
      <?php endwhile; ?>
    </div>
  </div>
</body>
<footer>
    © 2025 The Neighborhood Bandar Seri Ehsan. All rights reserved.
</footer>
</html>

<?php
function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $weeks = floor($diff->d / 7);
    $diff->d -= $weeks * 7;

    $string = [
        'y' => 'year', 'm' => 'month', 'w' => $weeks,
        'd' => 'day', 'h' => 'hour', 'i' => 'min', 's' => 'sec'
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
