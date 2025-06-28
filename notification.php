<?php 
session_start();

// Redirect if not logged in
if (!isset($_SESSION['resident_id'])) {
    header("Location: login.php");
    exit();
}

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "neighborhoodproject";

// Connect to DB
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("DB Connection failed: " . $conn->connect_error);
}

// Get all comments with usernames
$comment_sql = "
    SELECT c.notification_id, c.comment_text, c.created_at, r.username 
    FROM comments c
    JOIN residents r ON c.resident_id = r.resident_id
    ORDER BY c.created_at DESC
";

$comment_result = $conn->query($comment_sql);

// Check for query errors
if ($comment_result === false) {
    die("Error fetching comments: " . $conn->error);
}

// Group comments by notification_id
$comments_by_notification = [];
if ($comment_result->num_rows > 0) {
    while ($row = $comment_result->fetch_assoc()) {
        $comments_by_notification[$row['notification_id']][] = $row;
    }
}

// Generate CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <style>
        /* Reset and Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f5f5;
            color: #333;
            line-height: 1.6;
        }
        
        /* Header Styles */
        header {
            background-color: #a4d373;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 100;
        }
        
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
            color: #333;
        }
        
        nav a {
            margin: 0 10px;
            color: #333;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s;
        }
        
        nav a:hover {
            color: #2c5e1a;
        }
        
        /* Sidebar Styles */
        .sidebar {
            width: 200px;
            background-color: #d5ecb3;
            position: fixed;
            top: 80px;
            bottom: 0;
            left: 0;
            padding: 20px;
            overflow-y: auto;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }
        
        .sidebar p {
            font-weight: bold;
            margin-bottom: 20px;
            font-size: 18px;
            color: #2c5e1a;
            border-bottom: 1px solid #a4d373;
            padding-bottom: 10px;
        }
        
        .sidebar ul {
            list-style-type: none;
        }
        
        .sidebar ul li {
            margin: 15px 0;
            padding: 8px 0;
            border-bottom: 1px solid #c0df9d;
        }
        
        .sidebar ul li a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
            display: block;
            transition: all 0.3s;
        }
        
        .sidebar ul li a:hover {
            color: #2c5e1a;
            transform: translateX(5px);
        }
        
        .sidebar ul li a strong {
            color: #2c5e1a;
        }
        
        /* Main Content Styles */
        .main {
            margin-left: 240px;
            padding: 30px;
            padding-top: 100px;
            min-height: calc(100vh - 60px);
        }
        
        h2 {
            color: #2c5e1a;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #d5ecb3;
        }
        
        /* Cards Layout */
        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }
        
        .card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }
        
        .card img {
            width: 100%;
            height: 160px;
            object-fit: cover;
            border-bottom: 1px solid #eee;
        }
        
        .card-content {
            padding: 20px;
        }
        
        .card-content h4 {
            margin: 0 0 10px 0;
            font-size: 18px;
            color: #2c5e1a;
        }
        
        .card-content p {
            font-size: 14px;
            color: #666;
            margin-bottom: 15px;
        }
        
        .card-content button {
            margin-top: 5px;
            background-color: #3dd96b;
            border: none;
            color: white;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        
        .card-content button:hover {
            background-color: #2c5e1a;
        }
        
        /* Comments Section */
        .comments-section {
            margin-top: 15px;
            padding: 15px;
            border-top: 1px solid #eee;
            background-color: #f9f9f9;
        }
        
        .comments-section h5 {
            color: #2c5e1a;
            margin-bottom: 10px;
            font-size: 16px;
        }
        
        .comment {
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px dashed #ddd;
        }
        
        .comment:last-child {
            border-bottom: none;
        }
        
        .comment small {
            color: #999;
            font-size: 12px;
        }
        
        /* Comment Form */
        .comment-form {
            margin-top: 15px;
        }
        
        .comment-form textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            resize: vertical;
            min-height: 60px;
            margin-bottom: 10px;
        }
        
        .comment-form button[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .comment-form button[type="submit"]:hover {
            background-color: #3e8e41;
        }
        
        /* Ads Scroller */
        .scroll-container {
            background-color: #69bc55;
            overflow-x: auto;
            white-space: nowrap;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 30px;
        }
        
        .scroll-container img {
            height: 180px;
            margin-right: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
            transition: transform 0.3s;
        }
        
        .scroll-container img:hover {
            transform: scale(1.03);
        }
        
        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.5);
        }
        
        .modal-content {
            background-color: #fff;
            margin: 10% auto;
            padding: 25px;
            border-radius: 10px;
            width: 60%;
            max-width: 600px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            position: relative;
        }
        
        .close {
            color: #aaa;
            position: absolute;
            top: 15px;
            right: 25px;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            transition: color 0.3s;
        }
        
        .close:hover {
            color: #333;
        }
        
        .modal h2 {
            color: #2c5e1a;
            margin-bottom: 15px;
        }
        
        .modal p {
            line-height: 1.6;
            color: #555;
        }
        
        /* Footer Styles */
        footer {
            text-align: center;
            background-color: #a4d373;
            padding: 15px;
            margin-left: 200px;
            color: #333;
            font-size: 14px;
        }
        
        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                position: static;
                top: auto;
                margin-bottom: 20px;
            }
            
            .main {
                margin-left: 0;
                padding: 20px;
                padding-top: 80px;
            }
            
            footer {
                margin-left: 0;
            }
            
            .modal-content {
                width: 90%;
            }
        }

        .new-badge {
    background-color: #4CAF50;
    color: white;
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 12px;
    margin-left: 8px;
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
        <li><a href="report.php">Lodge Report</a></li>
        <li><a href="notescommunity.php">Community Notes</a></li>
        <li><a href="faq.html">Help & Support</a></li>
        <li><a href="notification.php"><strong>Announcement</strong></a></li>
    </ul>
</div>

<div class="main">
    <h2>Notifications</h2>

    <div class="card" style="max-width: 600px; margin-bottom: 30px;">
    <div class="card-content">
        <h3 style="color:#2c5e1a;">Post a New Notification</h3>
        <form action="upload_notification.php" method="POST" enctype="multipart/form-data">
            <input type="text" name="title" placeholder="Title" required style="width:100%; padding:8px; margin:10px 0;"><br>
            <textarea name="description" placeholder="Description" rows="3" required style="width:100%; padding:8px; margin-bottom:10px;"></textarea><br>
            <input type="file" name="image" accept="image/*" required style="margin-bottom:10px;"><br>
            <button type="submit" style="background-color:#3dd96b; color:white; border:none; padding:8px 16px; border-radius:5px; cursor:pointer;">Upload Notification</button>
        </form>
    </div>
</div>


    <div class="cards">
         <!-- Display user-posted notifications -->
  <?php
$userNotiSql = "SELECT un.*, r.username FROM user_notifications un 
                JOIN residents r ON un.resident_id = r.resident_id 
                ORDER BY un.created_at DESC";
$userNotiResult = $conn->query($userNotiSql);

// Get last login time
$lastLogin = $_SESSION['last_login'];

if ($userNotiResult->num_rows > 0) {
    while ($row = $userNotiResult->fetch_assoc()) {
        $notiId = 'user' . $row['notification_id']; // Unique ID like "user3"

        echo '<div class="card">';
        echo '<img src="' . htmlspecialchars($row['image_path']) . '" alt="User Notification">';
        echo '<div class="card-content">';
        echo '<h4>' . htmlspecialchars($row['title']);

        // Show NEW badge if created_at > last_login
        if (strtotime($row['created_at']) > strtotime($lastLogin)) {
            echo ' <span class="new-badge">NEW</span>';
        }

        echo '</h4>';
        $preview = substr($row['description'], 0, 50) . '...';
        echo '<p>' . htmlspecialchars($preview) . '</p>';
        echo '<small style="color:gray;">Posted by ' . htmlspecialchars($row['username']) . ' on ' . date('d M Y, h:i A', strtotime($row['created_at'])) . '</small>';
        echo '<br>';
echo '<div style="margin-top: 10px; display: flex; gap: 10px;">';

// See Details button
echo '<button class="openModalBtn" data-modal="modal-' . $notiId . '" style="background-color:rgb(21, 143, 41); color: white; border: none; padding: 6px 12px; border-radius: 5px; cursor: pointer;">See Details</button>';

// Delete button only if user is the owner
if ($_SESSION['resident_id'] == $row['resident_id']) {
    echo '<form action="delete_notification.php" method="POST" onsubmit="return confirm(\'Are you sure you want to delete this notification?\');">';
    echo '<input type="hidden" name="notification_id" value="' . $row['notification_id'] . '">';
    echo '<input type="hidden" name="image_path" value="' . htmlspecialchars($row['image_path']) . '">';
    echo '<button type="submit" style="background-color: #e74c3c; color: white; padding: 6px 12px; border: none; border-radius: 5px; cursor: pointer;">Delete Notification</button>';
    echo '</form>';
}

echo '</div>'; // end button container

        echo '</div>';

        // Comments section
        echo '<div class="comments-section">';
        echo '<h5>Comments:</h5>';
        if (isset($comments_by_notification[$notiId])) {
            foreach ($comments_by_notification[$notiId] as $comment) {
                echo '<div class="comment">';
                echo '<p><strong>' . htmlspecialchars($comment['username']) . ':</strong> ' .
                    htmlspecialchars($comment['comment_text']) . '</p>';
                echo '<small>' . date('d M Y, h:i A', strtotime($comment['created_at'])) . '</small>';
                echo '</div>';
            }
        } else {
            echo '<p style="color:#999;">No comments yet.</p>';
        }

        // Comment form
        echo '<form class="comment-form" action="submitcomment.php" method="POST">';
        echo '<input type="hidden" name="notification_id" value="' . $notiId . '">';
        echo '<input type="hidden" name="csrf_token" value="' . $csrf_token . '">';
        echo '<textarea name="comment_text" placeholder="Write your comment..." required></textarea>';
        echo '<button type="submit">Post Comment</button>';
        echo '</form>';
        echo '</div>'; // end comments-section

        echo '</div>'; // end card

        // Modal
        echo '<div id="modal-' . $notiId . '" class="modal">';
        echo '<div class="modal-content">';
        echo '<span class="close">&times;</span>';
        echo '<h2>' . htmlspecialchars($row['title']) . '</h2>';
        echo '<p>' . nl2br(htmlspecialchars($row['description'])) . '</p>';
        echo '</div></div>';
    }
}
?>



        <!-- Cleanup Card -->
        <div class="card">
            <img src="cleanup.png" alt="cleanup">
            <div class="card-content">
                <h4>BSE COMMUNITY CLEANUP 2025</h4>
                <p>Organized by Community Association of BSE</p>
                <button class="openModalBtn" data-modal="modal-cleanup">See Details</button>
            </div>
            <div class="comments-section">
                <h5>Comments:</h5>
                <?php
                    $notifID = 'cleanup';
                    if (isset($comments_by_notification[$notifID])) {
                        foreach ($comments_by_notification[$notifID] as $comment) {
                            echo '<div class="comment">';
                            echo '<p><strong>' . htmlspecialchars($comment['username']) . ':</strong> ' . 
                                 htmlspecialchars($comment['comment_text']) . '</p>';
                            echo '<small>' . date('d M Y, h:i A', strtotime($comment['created_at'])) . '</small>';
                            echo '</div>';
                        }
                    } else {
                        echo '<p style="color:#999;">No comments yet.</p>';
                    }
                ?>
                <form class="comment-form" action="submitcomment.php" method="POST">
                    <input type="hidden" name="notification_id" value="cleanup">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <textarea name="comment_text" placeholder="Write your comment..." required></textarea>
                    <button type="submit">Post Comment</button>
                </form>
            </div>
        </div>

        <!-- Tax Card -->
        <div class="card">
            <img src="tax.jpg" alt="tax">
            <div class="card-content">
                <h4>Property Tax Payment</h4>
                <p>Majlis Perbandaran Kuala Langat</p>
                <button class="openModalBtn" data-modal="modal-tax">See Details</button>
            </div>
            <div class="comments-section">
                <h5>Comments:</h5>
                <?php
                    $notifID = 'tax';
                    if (isset($comments_by_notification[$notifID])) {
                        foreach ($comments_by_notification[$notifID] as $comment) {
                            echo '<div class="comment">';
                            echo '<p><strong>' . htmlspecialchars($comment['username']) . ':</strong> ' . 
                                 htmlspecialchars($comment['comment_text']) . '</p>';
                            echo '<small>' . date('d M Y, h:i A', strtotime($comment['created_at'])) . '</small>';
                            echo '</div>';
                        }
                    } else {
                        echo '<p style="color:#999;">No comments yet.</p>';
                    }
                ?>
                <form class="comment-form" action="submitcomment.php" method="POST">
                    <input type="hidden" name="notification_id" value="tax">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <textarea name="comment_text" placeholder="Write your comment..." required></textarea>
                    <button type="submit">Post Comment</button>
                </form>
            </div>
        </div>

        <!-- Dewan Card -->
        <div class="card">
            <img src="communitycenter.jpg" alt="dewan">
            <div class="card-content">
                <h4>NOW OPEN: DEWAN BSE</h4>
                <p>A new multipurpose hall for events, meetings, and community activities.</p>
                <button class="openModalBtn" data-modal="modal-dewan">See Details</button>
            </div>
            <div class="comments-section">
                <h5>Comments:</h5>
                <?php
                    $notifID = 'dewan';
                    if (isset($comments_by_notification[$notifID])) {
                        foreach ($comments_by_notification[$notifID] as $comment) {
                            echo '<div class="comment">';
                            echo '<p><strong>' . htmlspecialchars($comment['username']) . ':</strong> ' . 
                                 htmlspecialchars($comment['comment_text']) . '</p>';
                            echo '<small>' . date('d M Y, h:i A', strtotime($comment['created_at'])) . '</small>';
                            echo '</div>';
                        }
                    } else {
                        echo '<p style="color:#999;">No comments yet.</p>';
                    }
                ?>
                <form class="comment-form" action="submitcomment.php" method="POST">
                    <input type="hidden" name="notification_id" value="dewan">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <textarea name="comment_text" placeholder="Write your comment..." required></textarea>
                    <button type="submit">Post Comment</button>
                </form>
            </div>
        </div>
    </div>

    <h2>Community Ads</h2>
    <div class="scroll-container">
        <img src="iklan1.png" alt="Clothing Sale Poster">
        <img src="rent.png" alt="Room for Rent Ad">
        <img src="iklan2.png" alt="Food Delivery Promo">
        <img src="iklan3.png" alt="Cafe Grand Opening">
        <img src="iklan4.png" alt="Volunteers Needed">
        <img src="iklan5.png" alt="Kitten Adopting">
    </div>
</div>

<!-- Modals -->
<div id="modal-cleanup" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>BSE COMMUNITY CLEAN UP 2025</h2>
        <p>25 June 2025<br><br>
        Join us for the BSE Community Cleanup: bring your own cleaning tools, wear proper attire, and enjoy a free breakfast as we work together to keep our neighborhood clean!</p>
    </div>
</div>

<div id="modal-tax" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Property Tax Payment</h2>
        <p>Friendly reminder to settle your property tax with MPKL before the due date to avoid late penalties.</p>
    </div>
</div>

<div id="modal-dewan" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Soft Launch: Dewan BSE</h2>
        <p>You're invited to the Soft Launch for Dewan BSE on 5th July 2025, come celebrate this new community space with us!</p>
    </div>
</div>

<footer>
    © 2025 The Neighborhood Bandar Seri Ehsan. All rights reserved.
</footer>

<script>
    // Modal functionality
    document.addEventListener('DOMContentLoaded', function() {
        const buttons = document.querySelectorAll('.openModalBtn');
        const modals = document.querySelectorAll('.modal');
        const closes = document.querySelectorAll('.close');
        
        // Open modal
        buttons.forEach(button => {
            button.addEventListener('click', function() {
                const modalId = this.getAttribute('data-modal');
                document.getElementById(modalId).style.display = 'block';
            });
        });
        
        // Close modal
        closes.forEach(closeBtn => {
            closeBtn.addEventListener('click', function() {
                this.closest('.modal').style.display = 'none';
            });
        });
        
        // Close when clicking outside
        window.addEventListener('click', function(event) {
            modals.forEach(modal => {
                if (event.target === modal) {
                    modal.style.display = 'none';
                }
            });
        });
        
        // Close with ESC key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                modals.forEach(modal => {
                    modal.style.display = 'none';
                });
            }
        });
    });
</script>

</body>
</html>