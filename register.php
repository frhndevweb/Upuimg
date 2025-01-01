<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $password);

    if ($stmt->execute()) {
        header("Location: login.php");
        exit;
    } else {
        $error = "Gagal mendaftarkan akun. Username mungkin sudah digunakan.";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Upuimg</title>
    <style>
        body, h1, p {
            margin: 0;
            padding: 0;
        }

        /* Styling body */
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to bottom, #3a6073, #16222a);
            color: #fff;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Container untuk form login */
        .container {
            background: rgba(255, 255, 255, 0.1);
            padding: 20px 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            max-width: 400px;
            width: 100%;
            text-align: center;
        }

        /* Header form */
        .container h1 {
            font-size: 2rem;
            margin-bottom: 20px;
            color: #ffffff;
        }

        /* Error message styling */
        .error {
            background-color: #ff4d4d;
            color: #ffffff;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            font-size: 0.9rem;
        }

        /* Styling label dan input */
        label {
            display: block;
            margin-bottom: 8px;
            text-align: left;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            color: #333;
        }

        /* Placeholder styling */
        input::placeholder {
            color: #aaa;
        }

        /* Fokus pada input */
        input:focus {
            outline: none;
            border: 1px solid #3a6073;
        }

        /* Tombol login */
        button {
            background: #1abc9c;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s;
            width: 100%;
        }

        /* Hover pada tombol */
        button:hover {
            background: #16a085;
        }

        /* Link register */
        .container p {
            margin-top: 15px;
            font-size: 0.9rem;
        }

        .container a {
            color: #1abc9c;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s;
        }

        .container a:hover {
            color: #16a085;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Daftar Akun</h1>
        <?php if (!empty($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="POST">
            <label>Username:</label>
            <input type="text" name="username" required>
            <label>Password:</label>
            <input type="password" name="password" required>
            <button type="submit">Daftar</button>
        </form>
        <p>Sudah punya akun? <a href="login.php">Login di sini</a>.</p>
    </div>
</body>
</html>
