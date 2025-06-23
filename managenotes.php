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
            <a href="adminprofile.html">
            <img src="circle-user.png" alt="userprofile" width="40" height="40" class="userprofile">
            </a>
        </h1>

        <div class="dashboard">
        <div class="sidebar">
        <ul>
            <li><a href="uploadedreports.html">
            <img src="document.png" alt="uploadedreports" class="uploadedreports">
            Uploaded Reports
            </a></li>
            <li><a href="managenotes.html">
            <img src="bell.png" alt="managenotes" class="managenotes">
            Manage Notes
            </a></li>
            <li><a href="noticeboard.html">
            <img src="notice.png" alt="noticeboard" class="noticeboard">
            Notice Board
            </a></li>
        </ul>
        </div>  
        
        <div class="notice-content">
            <h2>Community Notes</h2>
<?php
$conn = new mysqli("localhost", "root", "", "your_database_name");

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM notes WHERE id=$id");
    header("Location: managenotes.php");
}

$result = $conn->query("SELECT * FROM notes ORDER BY created_at DESC");
?>

  <?php while($row = $result->fetch_assoc()): ?>
    <div class="notice-card">
      <strong><?= htmlspecialchars($row['title']) ?></strong>
      <p><?= htmlspecialchars($row['content']) ?></p>
      <p class="notice-date"><?= time_elapsed_string($row['created_at']) ?></p>
      
      <div class="card-buttons">
        <a href="viewnote.php?id=<?= $row['id'] ?>" class="view-btn">View Details</a>
        <a href="?delete=<?= $row['id'] ?>" class="delete-btn" onclick="return confirm('Delete this note?')">Delete</a>
      </div>
    </div>
  <?php endwhile; ?>
</div>

</body>
</html>

<?php
function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }
    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}
?>
