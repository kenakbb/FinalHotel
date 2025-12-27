<?php
include '../includes/config.php';
session_start();

$error = "";

if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = hash('sha256', $_POST['password']);

    $stmt = $conn->prepare("SELECT id FROM admin WHERE email = ? AND password = ?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $_SESSION['admin'] = true;
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid admin credentials";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <style>
        /* Inline CSS for login page */
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: Arial, sans-serif; }
        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #141e30, #243b55);
            color: #fff;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #f4b41a;
            font-size: 28px;
        }
        form {
            background: rgba(0, 0, 0, 0.75);
            padding: 30px;
            border-radius: 10px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 0 15px rgba(0,0,0,0.5);
        }
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: none;
            font-size: 16px;
        }
        input[type="email"]:focus,
        input[type="password"]:focus {
            outline: none;
            box-shadow: 0 0 5px #f4b41a;
        }
        button[type="submit"] {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 5px;
            background-color: #f4b41a;
            color: #000;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        button[type="submit"]:hover {
            background-color: #ffcc33;
        }
        p {
            text-align: center;
            margin-bottom: 15px;
            font-size: 14px;
        }
    </style>
</head>
<body>

<h2>Admin Login</h2>

<?php if ($error) echo "<p style='color:red;'>$error</p>"; ?>

<form method="post">
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit" name="login">Login</button>
</form>

</body>
</html>
