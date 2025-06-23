<?php
include 'connect.php';

$result = $conn->query("SELECT * FROM reports ORDER BY created_at DESC");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Reports</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include("header.html"); ?>
<?php include("adminSidebar.php"); ?>

<div class="content">
    <h2>Resident Reports</h2>

    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="report-box">
            <div class="report-details">
                <h3><?= htmlspecialchars($row['title']) ?></h3>
                <p><a href="report.php?report_id=<?= $row['report_id'] ?>"><?= htmlspecialchars($row['title']) ?></a></p>
                <p><strong>Report ID:</strong> <?= $row['report_id'] ?><br>
                   <strong>Resident ID:</strong> <?= $row['user_id'] ?></p>
                <p><strong>Description:</strong><br>
                   <?= nl2br(htmlspecialchars($row['description'])) ?>
                </p>
                <p>
                    <strong>Category:</strong> <?= htmlspecialchars($row['category']) ?><br>
                    <strong>Location:</strong> <?= htmlspecialchars($row['location']) ?><br>
                    <strong>Status:</strong> <?= htmlspecialchars($row['status']) ?><br>
                    <strong>Date Created:</strong> <?= $row['created_at'] ?><br>
                    <strong>Date Updated:</strong> <?= $row['updated_at'] ?>
                </p>
                
                <a href="report.php?report_id=<?= $row['report_id'] ?>">View / Edit Report</a>
            </div>
            <div class="report-image">
                <?php if ($row['image']): ?>
                    <img src="<?= $row['image'] ?>" alt="Photo" width="100">
                <?php else: ?>
                    <p>No Image</p>
                <?php endif; ?>
            </div>
        </div>
    <?php endwhile; ?>
</div>

</body>
</html>
