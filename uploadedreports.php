<?php
include 'connect.php';

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Handle Delete
if (isset($_GET['delete'])) {
    $report_id = intval($_GET['delete']);
    $conn->query("DELETE FROM reports WHERE report_id = $report_id");
    header("Location: uploadedreports.php");
    exit();
}

// Handle Update
if (isset($_POST['update'])) {
    $report_id = intval($_POST['report_id']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    $conn->query("UPDATE reports SET title = '$title', description = '$description' WHERE report_id = $report_id");
    header("Location: uploadedreports.php");
    exit();
}

// Fetch reports
$result = $conn->query("SELECT * FROM reports ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Uploaded Reports - Admin Panel</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 20px; }
        h2 { margin-bottom: 20px; }
        form {
            background: #fff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 0 8px rgba(0,0,0,0.1);
        }
        input[type="text"], textarea {
            width: 100%;
            padding: 8px;
            margin: 5px 0 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button, a {
            display: inline-block;
            padding: 8px 12px;
            margin-top: 10px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        a.delete-btn {
            background-color: #e74c3c;
        }
        img {
            max-width: 300px;
            display: block;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<h2>All Submitted Reports</h2>

<?php while ($row = $result->fetch_assoc()): ?>
    <form method="post">
        <input type="hidden" name="report_id" value="<?= htmlspecialchars($row['report_id']) ?>">

        <p><strong>Title:</strong></p>
        <input type="text" name="title" value="<?= htmlspecialchars($row['title']) ?>">

        <p><strong>Description:</strong></p>
        <textarea name="description"><?= htmlspecialchars($row['description']) ?></textarea>

        <p><strong>Category:</strong> <?= htmlspecialchars($row['category']) ?></p>
        <p><strong>Location:</strong> <?= htmlspecialchars($row['location']) ?></p>
        <p><strong>User ID:</strong> <?= htmlspecialchars($row['user_id']) ?></p>

        <p><strong>Image:</strong><br>
            <?php if (!empty($row['image'])): ?>
                <img src="<?= htmlspecialchars($row['image']) ?>" alt="Report Image">
            <?php else: ?>
                No image provided.
            <?php endif; ?>
        </p>

        <p><strong>Submitted At:</strong> <?= htmlspecialchars($row['created_at']) ?></p>

        <button type="submit" name="update">Update</button>
        <a href="?delete=<?= htmlspecialchars($row['report_id']) ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this report?');">Delete</a>

    </form>
<?php endwhile; ?>

</body>
</html>

