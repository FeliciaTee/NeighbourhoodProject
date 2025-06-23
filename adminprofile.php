<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
     <link rel="stylesheet" href="adminstyle.css">
</head>

<body>
    <header>
        <div class="main">
            <ul>
                <img src="banner.png" alt="banner" class="banner">
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
    </header>

    <div class="dashboard">
        <div class="sidebar">
        <ul>
            <li><a href="uploadedreports.php">
            <img src="document.png" alt="uploadedreports" class="uploadedreports">
            Uploaded Reports
            </a></li>
            <li><a href="managenotes.php">
            <img src="bell.png" alt="managenotes" class="managenotes">
            Manage Notes
            </a></li>
            <li><a href="noticeboard.php">
            <img src="notice.png" alt="noticeboard" class="noticeboard">
            Notice Board
            </a></li>
        </ul>
        </div>
        
        <div class="content-area">
        <div class="profile-card">
            <div class="profile-header">
                <img src="woman-head.png" alt="Profile Picture">
                <div class="profile-info">
                    <h2 id="display-name">Your Name</h2>
                    <p id="display-role">Your Role</p>
                </div>
            </div>

            <form class="profile-form" id="profileForm">
                <div class="form-row">
                    <div>
                        <label>Name</label>
                        <input type="text" id="nameInput" placeholder="Enter your name">
                    </div>
                    <div>
                        <label>Role</label>
                        <input type="text" id="roleInput" placeholder="Enter your role">
                    </div>
                </div>
                <div class="form-row">
                    <div>
                        <label>Email</label>
                        <input type="email" id="emailInput" placeholder="Enter your email">
                    </div>
                    <div>
                        <label>Phone Number</label>
                        <input type="text" id="phoneInput" placeholder="Enter your phone number">
                    </div>
                </div>
                <div class="form-row full">
                    <div>
                        <label>Address</label>
                        <textarea id="addressInput" placeholder="Enter your address"></textarea>
                    </div>
                </div>
                <div class="form-row right">
                    <button type="submit">Save Change</button>
                </div>
            </form>
        </div>

        <div class="contact-box">
            <h3>Contact Information</h3>
            <p><img src="email.png" class="icon"> <span id="contact-email">-</span></p>
            <p><img src="phone-call.png" class="icon"> <span id="contact-phone">-</span></p>
            <p><img src="location.png" class="icon"> <span id="contact-address">-</span></p>
        </div>
        </div>
        </div>

        <script>
            window.onload = function () {
            const name = localStorage.getItem("name");
            const role = localStorage.getItem("role");
            const email = localStorage.getItem("email");
            const phone = localStorage.getItem("phone");
            const address = localStorage.getItem("address");

    if (name) {
      document.getElementById("display-name").innerText = name;
      document.getElementById("nameInput").value = name;
    }
    if (role) {
      document.getElementById("display-role").innerText = role;
      document.getElementById("roleInput").value = role;
    }
    if (email) {
      document.getElementById("contact-email").innerText = email;
      document.getElementById("emailInput").value = email;
    }
    if (phone) {
      document.getElementById("contact-phone").innerText = phone;
      document.getElementById("phoneInput").value = phone;
    }
    if (address) {
      document.getElementById("contact-address").innerText = address;
      document.getElementById("addressInput").value = address;
    }
  };

  document.getElementById("profileForm").addEventListener("submit", function (e) {
    e.preventDefault();

    const name = document.getElementById("nameInput").value;
    const role = document.getElementById("roleInput").value;
    const email = document.getElementById("emailInput").value;
    const phone = document.getElementById("phoneInput").value;
    const address = document.getElementById("addressInput").value;

    document.getElementById("display-name").innerText = name;
    document.getElementById("display-role").innerText = role;
    document.getElementById("contact-email").innerText = email;
    document.getElementById("contact-phone").innerText = phone;
    document.getElementById("contact-address").innerText = address;

    localStorage.setItem("name", name);
    localStorage.setItem("role", role);
    localStorage.setItem("email", email);
    localStorage.setItem("phone", phone);
    localStorage.setItem("address", address);
  });

</script>
<footer>
    © 2025 The Neighborhood Bandar Seri Ehsan. All rights reserved.
</footer>
</body>
</html>