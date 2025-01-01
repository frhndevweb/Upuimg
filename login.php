<?php
// Mengaktifkan error reporting untuk pengembangan
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Memulai session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Menghubungkan ke database
include 'db.php';

// Menyiapkan variabel error untuk menampilkan pesan
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Mendapatkan data dari form dan menghilangkan spasi yang tidak perlu
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Memeriksa keberadaan user di database
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username); // Binding parameter untuk username
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Jika user ditemukan, bind hasil query
        $stmt->bind_result($user_id, $hashed_password);
        $stmt->fetch();

        // Memverifikasi password yang dimasukkan dengan yang ada di database
        if (password_verify($password, $hashed_password)) {
            // Menyimpan data session jika login berhasil
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;
            // Mengalihkan ke halaman utama setelah login
            header("Location: index.php");
            exit;
        } else {
            // Jika password salah
            $error = "Password salah.";
        }
    } else {
        // Jika username tidak ditemukan
        $error = "Username tidak ditemukan.";
    }
    $stmt->close(); // Menutup prepared statement
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body, h1, p {
            margin: 0;
            padding: 0;
        }
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to bottom, #3a6073, #16222a);
            color: #fff;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            background: rgba(255, 255, 255, 0.1);
            padding: 20px 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            max-width: 400px;
            width: 100%;
            text-align: center;
        }

        .container h1 {
            font-size: 2rem;
            margin-bottom: 20px;
            color: #ffffff;
        }

        .error {
            background-color: #ff4d4d;
            color: #ffffff;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            font-size: 0.9rem;
        }

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

        input::placeholder {
            color: #aaa;
        }

        input:focus {
            outline: none;
            border: 1px solid #3a6073;
        }

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

        button:hover {
            background: #16a085;
        }

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
        <h1>Login</h1>
        
        <?php if (!empty($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>

        <form method="POST">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
            <button type="submit">Login</button>
        </form>
        
        <p>Belum punya akun? <a href="register.php">Daftar di sini</a>.</p>
    </div>
</body>
</html>
