<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'coaching_db';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$full_name      = trim($_POST['full_name'] ?? '');
$phone          = trim($_POST['phone'] ?? '');
$class_selected = trim($_POST['class_selected'] ?? '');

if ($full_name && $phone && $class_selected) {
    $stmt = $conn->prepare("INSERT INTO enrollments (full_name, phone, class_selected) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $full_name, $phone, $class_selected);

    if ($stmt->execute()) {
        $msg = "
        <div class='msg success'>
            <span class='close-btn' onclick=\"window.location.href='anj.html'\">&times;</span>
            <h2>Enrollment Successful!</h2>
            <p><strong>$full_name</strong>, you have been successfully enrolled in <strong>$class_selected</strong> STD.</p>
            <p class='thanks'>Thank you for joining us!<br>For further registeration we will contact you soon.</p>
        </div>";
    } else {
        $msg = "
        <div class='msg error'>
            <span class='close-btn' onclick=\"window.location.href='anj.html'\">&times;</span>
            ❌ Insert failed: " . $stmt->error . "
        </div>";
    }
    $stmt->close();
} else {
    $msg = "
    <div class='msg warning'>
        <span class='close-btn' onclick=\"window.location.href='anj.html'\">&times;</span>
        ⚠️ Some fields are empty. Please check form.
    </div>";
}

$conn->close();

echo "
<style>
body {
  background:#FFFFE0;
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
  margin: 0;
}

.msg {
  position: relative;
  padding: 30px 40px;
  margin: 20px;
  border-radius: 12px;
  font-family: 'Segoe UI', sans-serif;
  font-size: 1rem;
  font-weight: 500;
  max-width: 500px;
  text-align: center;
  box-shadow: 0 8px 20px rgba(0,0,0,0.15);
  animation: bounceIn 0.8s ease-out;
}

.success { background: linear-gradient(135deg,#E6E6FA,#800080); color: #2c0025ff; }
.error   { background: linear-gradient(135deg, #dc3545, #ff6b81); color: #fff; }
.warning { background: linear-gradient(135deg, #ffc107, #ffe066); color: #333; }

.msg h2 {
  margin: 0 0 10px;
  font-size: 1.5rem;
  font-weight: bold;
  animation: fadeInDown 1s ease;
}

.msg p {
  margin: 8px 0;
  font-size: 1rem;
  animation: fadeIn 1.2s ease;
}

.msg .thanks {
  margin-top: 15px;
  font-size: 1.2rem;
  font-weight: bold;
  color: #fff8dc;
  animation: pulse 2s infinite;
}

/* Close button */
.close-btn {
  position: absolute;
  right: 15px;
  top: 12px;
  font-size: 22px;
  font-weight: bold;
  cursor: pointer;
  color: #fff;
  transition: 0.3s;
}
.warning .close-btn { color: #333; }
.close-btn:hover { transform: scale(1.2); }

/* Animations */
@keyframes bounceIn {
  0% { transform: scale(0.5); opacity: 0; }
  60% { transform: scale(1.1); opacity: 1; }
  100% { transform: scale(1); }
}
@keyframes fadeInDown {
  from { opacity: 0; transform: translateY(-20px); }
  to { opacity: 1; transform: translateY(0); }
}
@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}
@keyframes pulse {
  0% { transform: scale(1); }
  50% { transform: scale(1.05); }
  100% { transform: scale(1); }
}
</style>
$msg
";
?>
