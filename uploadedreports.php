<?php
$conn = new mysqli("localhost", "root", "", "your_database_name");

// Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM reports WHERE id=$id");
    header("Location: uploadedreports.php");
}

// Update
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];

    $conn->query("UPDATE reports SET title='$title', description='$description' WHERE id=$id");
    header("Location: uploadedreports.php");
}

// Fetch all reports
$result = $conn->query("SELECT * FROM reports");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin: Uploaded Reports</title>
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
            <h2>Resident Reports</h2>

<?php while($row = $result->fetch_assoc()): ?>
  <form method="post" style="border:1px solid #ccc; padding:10px; margin-bottom:10px;">
    <input type="hidden" name="id" value="<?= $row['id'] ?>">
    <p><strong>Title:</strong><br><input type="text" name="title" value="<?= htmlspecialchars($row['title']) ?>"></p>
    <p><strong>Description:</strong><br><textarea name="description"><?= htmlspecialchars($row['description']) ?></textarea></p>
    <p><strong>Updated:</strong> <?= $row['created_at'] ?></p>
    <button type="submit" name="update">Update</button>
    <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Delete this report?')">Delete</a>
  </form>
<?php endwhile; ?>

</body>
</html>
