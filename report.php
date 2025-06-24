<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
include 'connect.php';

if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}

$username = $_SESSION['username'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $category = trim($_POST['category']);
    $location = trim($_POST['location']);
    $description = trim($_POST['description']);
    $imagePath = '';
    $status = 'new';
    $date_created = date('Y-m-d H:i:s');

    // Get resident_id
    $stmt = $conn->prepare("SELECT resident_id FROM residents WHERE username = ?");
    if (!$stmt) {
        die(" Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($resident_id);
    $stmt->fetch();
    $stmt->close();

    if (!$resident_id) {
        echo "<script>alert('Resident not found.'); window.location.href='report.php';</script>";
        exit();
    }

    // Upload image
    if (!empty($_FILES['image']['name'])) {
        $imageName = basename($_FILES['image']['name']);
        $targetDir = "uploads/";
        $targetFile = $targetDir . uniqid() . "_" . $imageName;

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $imagePath = $targetFile;
        } else {
            echo "<script>alert('Image upload failed.'); window.location.href='report.php';</script>";
            exit();
        }
    }
$query = "INSERT INTO reports (resident_id, title, category, location, description, report_image, status, date_created)
          VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($query);

if (!$stmt) {
    die(" Prepare failed: " . $conn->error . "<br> Query: " . $query);
}

$stmt->bind_param("isssssss", $resident_id, $title, $category, $location, $description, $imagePath, $status, $date_created);


    if ($stmt->execute()) {
        echo "<script>alert('Report submitted successfully!'); window.location.href='report.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>REPORT</title>
   <link rel="stylesheet" href="residentstyle.css">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <style>
   
    header {
      background-color: #a5d66f;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 10px 30px;
    }
    .left-header {
      display: flex;
      align-items: center;
    }
    .left-header img {
      height: 150px;
      margin-right: 30px;
    }
    nav a {
      margin: 0 12px;
      color: black;
      text-decoration: none;
      font-weight: bold;
    }
    nav a:hover {
      text-decoration: underline;
    }
    .hero-container {
      display: flex;
      align-items: flex-start;
    }
    .hero-left {
      width: 200px;
      background-color: #d5ecb3;
      height: 100vh;
      padding: 20px;
    }
    .hero-left ul {
      list-style-type: none;
      padding: 0;
    }
    .hero-left ul li {
      margin: 15px 0;
    }
    .hero-left ul li a {
      text-decoration: none;
      color: #333;
    }
    .hero-left ul li a i {
      margin-right: 8px;
    }
    .hero-right {
      flex: 1;
      padding: 30px;
    }
    .report-form {
      background-color: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0px 0px 10px #ccc;
      max-width: 700px;
    }
    .report-form h2 {
      font-size: 22px;
      font-weight: bold;
      margin-bottom: 10px;
    }
    .report-form input,
    .report-form select,
    .report-form textarea {
      width: 100%;
      margin-top: 10px;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-family: "Lucida Console";
    }
    .report-form textarea {
      height: 100px;
      resize: vertical;
    }
    .report-form .form-group {
      margin-bottom: 15px;
    }
    .form-wrapper {
      display: flex;
      justify-content: center;
      align-items: flex-start;
      min-height: 100vh;
    }
    .submit-btn {
      margin-top: 15px;
      padding: 10px 20px;
      background-color: #d3f4d1;
      border: none;
      cursor: pointer;
      border-radius: 5px;
      float: right;
      font-family: "Lucida Console";
    }
    footer {
      text-align: center;
      padding: 10px;
      margin-top: 50px;
      font-size: 12px;
      font-family: "Lucida Console";
    }
  </style>
</head>
<body>

  <header>
    <div class="left-header">
      <img src="banner.png" alt="Logo">
    </div>
    <nav>
     
      <a href="logout.php">Log Out</a>
    </nav>
  </header>

  <div class="hero-container">
    <div class="hero-left">
      <ul>
        <li><a href="resident.php">Resident Profile</a></li>
        <li><a href="report.php">Lodge Report</a></li>
        <li><a href="notescommunity.php">Community Notes</a></li>
        <li><a href="faq.html">Help & Support</a></li>
        <li><a href="notification.php"><strong> Notifications</strong></a></li>
      </ul>
    </div>

    <div class="hero-right">
      <div class="form-wrapper">
        <div class="report-form">
          <h2>Lodge a New Report</h2>
          <p>Report to Improve Our Environment and Community</p>

          <form action="report.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
              <label>Report Title</label>
              <input type="text" name="title" required>
            </div>

            <div class="form-group">
              <label>Username : <?php echo htmlspecialchars($username); ?></label>
              <input type="hidden" name="username" value="<?php echo htmlspecialchars($username); ?>">
            </div>

            <div class="form-group">
              <label>Category</label>
              <select name="category" required>
                <option value="">Select a category</option>
                <option value="road damage">Road Damage</option>
                <option value="garbage issue">Garbage Issue</option>
                <option value="streetlight issues">Streetlight Issues</option>
                <option value="water supplies">Water Supplies</option>
                <option value="others">Others</option>
              </select>
            </div>

            <div class="form-group">
              <label>Location</label>
              <input type="text" name="location" required>
            </div>

            <div class="form-group">
              <label>Description</label>
              <textarea name="description" placeholder="Provide detailed information about the issue..."></textarea>
            </div>

            <div class="form-group">
              <label>Upload Image (Optional)</label>
              <input type="file" name="image">
            </div>

            <button type="submit" class="submit-btn">Submit Report</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <footer>
    © 2025 The Neighborhood Bandar Seri Ehsan. All rights reserved.
  </footer>

</body>
</html>