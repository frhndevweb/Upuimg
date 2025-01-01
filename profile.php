<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil data user
$user = $conn->query("SELECT * FROM users WHERE id = $user_id")->fetch_assoc();

// Ambil unggahan user
$uploads = $conn->query("SELECT * FROM uploads WHERE user_id = $user_id ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - Upuimg</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            color: #ffffff;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: auto;
            background: #2b2f33;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        nav a {
            color: #1abc9c;
            text-decoration: none;
            font-weight: 600;
            margin-left: 20px;
            transition: color 0.3s;
        }

        nav a:hover {
            color: #3498db;
        }

        .nav-links {
            display: flex;
        }

        .profile-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .profile-header h1 {
            font-size: 1.8rem;
            margin-bottom: 5px;
            color: #ffffff;
        }

        .profile-header p {
            font-size: 1rem;
            color: #b3b3b3;
        }

        .uploads {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .upload-item {
            background: #3c4146;
            border-radius: 12px;
            padding: 15px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .upload-item:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.4);
        }

        .upload-item img, .upload-item video {
            width: 100%;
            height: auto;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .upload-item p {
            color: #b3b3b3;
        }
    </style>
</head>
<body>
<div class="container">
    <nav>
        <a href="index.php">Beranda</a>
        <div class="nav-links">
            <a href="rules.html">Rules</a>
            <a href="https://hannaaffiii.netlify.app" target="_blank">Info Admin</a>
            <a href="https://fhstore.netlify.app" target="_blank">Webstore</a>
            <a href="logout.php">Logout</a>
        </div>
    </nav>
    <div class="profile-header">
        <h1><?php echo htmlspecialchars($user['username']); ?></h1>
    </div>
    <div class="uploads">
        <?php while ($upload = $uploads->fetch_assoc()): ?>
            <div class="upload-item">
                <?php 
                    // Cek tipe file dan tampilkan sesuai dengan tipe file
                    $file_path = 'uploads/' . htmlspecialchars($upload['filename']);
                    $file_type = mime_content_type($file_path);
                    if (strpos($file_type, 'image') !== false): 
                ?>
                    <img src="<?php echo $file_path; ?>" alt="Image">
                <?php elseif (strpos($file_type, 'video') !== false): ?>
                    <video controls>
                        <source src="<?php echo $file_path; ?>" type="<?php echo $file_type; ?>">
                        Your browser does not support the video tag.
                    </video>
                <?php endif; ?>
                <p><?php echo htmlspecialchars($upload['caption']); ?></p>
            </div>
        <?php endwhile; ?>
    </div>
</div>
</body>
</html>
