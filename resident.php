<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Resident Dashboard</title>
    <link rel="stylesheet" type="text/css" href="residentstyle.css">
    <style>

        .banner {
            display: flex;
            align-items: center;
        }

        .banner img {
            height: 50px;
            margin-right: 10px;
        }

        header span {
            font-size: 20px;
            font-weight: bold;
        }

        nav a {
            margin: 0 10px;
            color: black;
            text-decoration: none;
            font-weight: bold;
        }

        nav a:hover {
            text-decoration: underline;
        }

    
       
.sidebar {
    width: 180px;
    background-color: #d5ecb3;
    position: fixed;
    top: 70px;
    bottom: 0;
    left: 0;
    padding: 20px;
    overflow-y: auto;
}

        .sidebar ul {
            list-style-type: none;
            padding: 0;
        }

        .sidebar ul li {
            margin: 15px 0;
        }

        .sidebar ul li a {
            text-decoration: none;
            color: #333;
        }

        .main {
            flex: 1;
            margin-left: 230px;
            padding: 20px;
            padding-top: 100px;
        }

        .card {
            background-color: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            max-width: 600px;
        }

        .card h2 {
            color: #33691e;
            margin-bottom: 20px;
        }

        .card p {
            font-size: 14px;
            margin: 10px 0;
            color: #444;
        }

        footer {
            text-align: center;
            background-color: #a4d373;
            padding: 20px;
            margin-left: 180px;
        }
    </style>
</head>
<body>

<header>
    <div class="banner">
        <img src="banner.png" alt="banner">
        <span>The Neighborhood: One-Stop Community Center</span>
    </div>
    <nav>
        
        <a href="logout.php">Log Out</a>
    </nav>
</header>

<div class="sidebar">
    <ul>
        <li><a href="resident.php">Resident Profile</a></li>
        <li><a href="report.html">Lodge Report</a></li>
        <li><a href="notescommunity.php">Community Notes</a></li>
        <li><a href="faq.html">Help & Support</a></li>
        <li><a href="notification.php"><strong>Notifications</strong></a></li>
    </ul>
</div>

<div class="main">
      <?php
    if (isset($_SESSION['update_success'])) {
        echo '<div style="background-color: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 15px; border: 1px solid #c3e6cb;">
            Profile updated successfully!
              </div>';
        unset($_SESSION['update_success']);
    }
    ?>

    <!-- Last login aligned right -->
    <div style="text-align: right; font-size: 14px; color: #555; margin-bottom: 10px;">
        <?php
        date_default_timezone_set('Asia/Kuala_Lumpur');
        $lastLogin = $_SESSION['last_login'];
        echo "Last login: " . date('d M Y, h:i A', strtotime($lastLogin));
        ?>
    </div>

    <div class="card" style="text-align: center;">
        <img src="uploads/<?php echo $_SESSION['profile_pic'] ?? 'default.jpeg'; ?>" 
             alt="Profile Picture" 
             style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; margin-bottom: 15px;">

        <h2>Welcome, <?php echo $_SESSION['name']; ?>!</h2>
        <p><strong>Username:</strong> <?php echo $_SESSION['username']; ?></p>
        <p><strong>Email:</strong> <?php echo $_SESSION['email']; ?></p>
         <p><strong>Phone:</strong> <?php echo $_SESSION['phone']; ?></p>
        <p><strong>Address:</strong> <?php echo $_SESSION['address']; ?></p>
        <p><strong>Resident ID:</strong> <?php echo $_SESSION['formatted_id']; ?></p>
    </div>
    
<div style="margin-top: 20px;">
    <button onclick="document.getElementById('editModal').style.display='block'" 
        style="padding: 10px 20px; background-color: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer;">
        Edit Profile
    </button>
</div>
</div>



<footer>
    &copy; 2025 The Neighborhood Bandar Seri Ehsan. All rights reserved.
</footer>

<!-- Modal -->
<div id="editModal" class="modal" style="display:none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.5);">
  <div style="background-color: #fff; margin: 10% auto; padding: 30px; border-radius: 10px; width: 50%; position: relative;">
    <span onclick="document.getElementById('editModal').style.display='none'" 
          style="position: absolute; top: 10px; right: 20px; font-size: 24px; font-weight: bold; cursor: pointer;">&times;</span>
    
    <h3>Edit Profile</h3>
    <form action="updateprofile.php" method="POST" enctype="multipart/form-data">
        <label>Full Name:</label>
        <input type="text" name="name" value="<?php echo $_SESSION['name']; ?>" required style="width: 100%; margin-bottom: 10px;"><br>

        <label>Username:</label>
        <input type="text" name="username" value="<?php echo $_SESSION['username']; ?>" required style="width: 100%; margin-bottom: 10px;"><br>

    <label>Phone Number:</label>
    <input type="text" name="phone" value="<?php echo $_SESSION['phone']; ?>" required style="width: 100%; margin-bottom: 10px;"><br>

    <label>Email:</label>
    <input type="email" name="email" value="<?php echo $_SESSION['email']; ?>" required style="width: 100%; margin-bottom: 10px;"><br>

        <label>Address:</label>
        <input type="text" name="address" value="<?php echo $_SESSION['address']; ?>" required style="width: 100%; margin-bottom: 10px;"><br>

        <label>Change Profile Picture:</label>
        <input type="file" name="profile_pic" accept="image/*" style="margin-bottom: 15px;"><br>

        <button type="submit" 
            style="padding: 10px 20px; background-color: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer;">
            Save Changes
        </button>
    </form>
  </div>
</div>

<script>
window.onclick = function(event) {
  const modal = document.getElementById('editModal');
  if (event.target === modal) {
    modal.style.display = "none";
  }
}
</script>

</body>
</html>


</body>
</html>
