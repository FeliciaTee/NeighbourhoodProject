<?php
// Sambungan ke database
$conn = new mysqli("localhost", "root", "", "neighborhoodproject");

// Semak sambungan
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ambil data dari borang
$name = $_POST['name'];
$username = $_POST['username'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$address = $_POST['address'];
$password = $_POST['password'];

// Hash password untuk keselamatan
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// 1. Semak jika username sudah wujud
$checkUsername = $conn->prepare("SELECT * FROM residents WHERE username = ?");
$checkUsername->bind_param("s", $username);
$checkUsername->execute();
$resultUsername = $checkUsername->get_result();

if ($resultUsername->num_rows > 0) {
    echo "Username telah didaftarkan, sila pilih username lain.";
    exit();
}

// 2. Semak jika email sudah wujud
$checkEmail = $conn->prepare("SELECT * FROM residents WHERE email = ?");
$checkEmail->bind_param("s", $email);
$checkEmail->execute();
$resultEmail = $checkEmail->get_result();

if ($resultEmail->num_rows > 0) {
    echo "Email telah didaftarkan.";
    exit();
}

// 3. Semak jika phone sudah wujud
$checkPhone = $conn->prepare("SELECT * FROM residents WHERE phone = ?");
$checkPhone->bind_param("s", $phone);
$checkPhone->execute();
$resultPhone = $checkPhone->get_result();

if ($resultPhone->num_rows > 0) {
    echo "Nombor telefon telah didaftarkan.";
    exit();
}

// 4. Masukkan data baru
$stmt = $conn->prepare("INSERT INTO residents (name, username, email, password, phone, address) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssss", $name, $username, $email, $hashed_password, $phone, $address);

if ($stmt->execute()) {
    echo "Pendaftaran berjaya!";
} else {
    echo "Ralat: " . $stmt->error;
}

// Tutup sambungan
$stmt->close();
$conn->close();
?>

