<?php
$conn = new mysqli("localhost", "root", "", "your_dbname");

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

$result = $conn->query("SELECT * FROM reports ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin: Uploaded Reports</title>
  <style>
    body { font-family: Arial; background: #f9f9f9; padding: 20px; }
    form { background: #fff; padding: 20px; border-radius: 10px; margin-bottom: 20px; box-shadow: 0 0 10px #ccc; }
    textarea, input[type=text] { width: 100%; padding: 8px; margin: 5px 0 10px; }
    button, a { margin-right: 10px; }
    img { max-width: 300px; margin-top: 10px; }
  </style>
</head>
<body>

<h2>All Submitted Reports</h2>

<?php while ($row = $result->fetch_assoc()): ?>
  <form method="post">
    <input type="hidden" name="id" value="<?= $row['id'] ?>">
    <p><strong>Report Title:</strong></p>
    <input type="text" name="title" value="<?= htmlspecialchars($row['title']) ?>">

    <p><strong>Description:</strong></p>
    <textarea name="description"><?= htmlspecialchars($row['description']) ?></textarea>

    <p><strong>Category:</strong> <?= $row['category'] ?></p>
    <p><strong>Location:</strong> <?= $row['location'] ?></p>
    <p><strong>User ID:</strong> <?= $row['user_id'] ?></p>
    <p><strong>Image:</strong><br>
      <?php if (!empty($row['image'])): ?>
        <img src="<?= $row['image'] ?>" alt="Report Image">
      <?php else: ?>
        No image provided.
      <?php endif; ?>
    </p>
    <p><strong>Created At:</strong> <?= $row['created_at'] ?></p>

    <button type="submit" name="update">Update</button>
    <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Delete this report?')">Delete</a>
  </form>
<?php endwhile; ?>

</body>
</html>

