<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Community Notes</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
    body {
      font-family: 'Lucida Console', monospace;
      margin: 0;
      background-color: #f5f9f2;
    }

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

    .hero-left p {
      margin-top: 0;
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
      padding: 40px;
    }

    .hero-right h2 {
      margin-top: 0;
    }

    .announcements {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
      gap: 20px;
    }

    .announcement-card {
      border: 1px solid #ccc;
      border-radius: 10px;
      padding: 20px;
      background-color: #fff;
    }

    .announcement-card strong {
      font-size: 16px;
    }

    .announcement-card p {
      margin: 10px 0;
      font-size: 13px;
    }

    .announcement-card small {
      color: #888;
      font-size: 12px;
    }

    footer {
      text-align: center;
      padding: 10px;
      margin-top: 50px;
      font-size: 12px;
    }

    form {
      margin: 30px 0;
    }

    form input, form textarea {
      width: 100%;
      max-width: 500px;
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-family: "Lucida Console";
    }

    form button {
      background-color: #a5d66f;
      border: none;
      padding: 10px 20px;
      color: black;
      font-weight: bold;
      cursor: pointer;
      border-radius: 5px;
      font-family: "Lucida Console";
    }

    @media (max-width: 768px) {
      .hero-container {
        flex-direction: column;
      }

      .hero-left {
        width: 100%;
        height: auto;
      }

      .hero-right {
        padding: 20px;
      }
    }
  </style>
</head>
<body>
  <header>
    <img src="banner.png" alt="Logo" height="100">
    <nav><a href="logout.php">Log Out</a></nav>
  </header>

  <div class="hero-container">
    <div class="hero-left">
      <ul>
        <li><a href="resident.php">Resident Profile</a></li>
        <li><a href="report.php"> Lodge Report</a></li>
        <li><a href="notescommunity.php">Community Notes</a></li>
        <li><a href="faq.html"> Help & Support</a></li>
        <li><a href="notification.php">Announcement</a></li>
      </ul>
    </div>

    <div class="hero-right">
      <h2>Community Notes</h2>
      <form id="uploadnote" method="post">
        <input type="text" name="title" placeholder="Title" required><br>
        <textarea name="content" placeholder="Write your note..." rows="4" required></textarea><br>
        <button type="submit">Submit Note</button>
      </form>

      <div id="notes-container"></div>
    </div>
  </div>

  <footer>© 2025 The Neighborhood Bandar Seri Ehsan. All rights reserved.</footer>

  <script>
    document.getElementById("uploadnote").addEventListener("submit", function(e) {
      e.preventDefault();
      const formData = new FormData(this);
      fetch("uploadnote.php", { method: "POST", body: formData })
        .then(res => res.text())
        .then(() => {
          this.reset();
          loadNotes();
        });
    });

    function loadNotes() {
      fetch("getnote.php")
        .then(res => res.text())
        .then(data => {
          document.getElementById("notes-container").innerHTML = data;
        });
    }

    loadNotes();
  </script>
</body>
</html>