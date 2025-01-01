<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $caption = $_POST['caption'];
    $file_name = $_FILES['file']['name'];
    $file_tmp = $_FILES['file']['tmp_name'];
    $file_type = mime_content_type($file_tmp);

    // Validasi file
    $allowed_types = ['image/jpeg', 'image/png', 'video/mp4', 'video/avi', 'video/mov'];
    if (!in_array($file_type, $allowed_types)) {
        $error = "Format file tidak didukung. Gunakan JPG, PNG, atau format video (MP4, AVI, MOV).";
    } else {
        $destination = 'uploads/' . $file_name;
        if (move_uploaded_file($file_tmp, $destination)) {
            $stmt = $conn->prepare("INSERT INTO uploads (user_id, filename, caption) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $user_id, $file_name, $caption);
            $stmt->execute();
            $stmt->close();
            $success = "File berhasil diunggah.";
        } else {
            $error = "Gagal mengunggah file.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload - Upuimg</title>
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
            max-width: 600px;
            margin: auto;
            background: #2b2f33;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .back-button {
            text-decoration: none;
            color: #1abc9c;
            font-weight: bold;
            transition: color 0.3s;
        }

        .back-button:hover {
            color: #3498db;
        }

        h1 {
            margin-bottom: 20px;
            font-size: 24px;
            text-align: center;
        }

        .success {
            color: #1abc9c;
            margin-bottom: 10px;
        }

        .error {
            color: #e74c3c;
            margin-bottom: 10px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="file"], textarea {
            margin-bottom: 15px;
            padding: 10px;
            border: none;
            border-radius: 8px;
            background: #4c5054;
            color: #ffffff;
        }

        button {
            background: #1abc9c;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }

        button:hover {
            background: #16a085;
        }

        .loading {
            display: none;
            margin-top: 10px;
            text-align: center;
        }

        .loading span {
            display: inline-block;
            width: 10px;
            height: 10px;
            margin: 0 5px;
            background-color: #3498db;
            border-radius: 50%;
            animation: bounce 1.2s infinite;
        }

        .loading span:nth-child(2) {
            animation-delay: 0.2s;
        }

        .loading span:nth-child(3) {
            animation-delay: 0.4s;
        }

        @keyframes bounce {
            0%, 80%, 100% {
                transform: scale(0);
            }
            40% {
                transform: scale(1);
            }
        }
    </style>
    <script>
        function showLoading() {
            document.querySelector('.loading').style.display = 'block';
        }
    </script>
</head>
<body>
<div class="header">
    <a href="javascript:history.back()" class="back-button">← Kembali</a>
</div>
<div class="container">
    <h1>Upload File</h1>
    <p>Disarankan menggunakan gambar 1:1</p>
    <p>Upload gambar/video</P>
    <p>&nbsp;</p>
    <?php if (!empty($success)): ?>
        <p class="success"><?php echo $success; ?></p>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>
    <form method="POST" enctype="multipart/form-data" onsubmit="showLoading()">
        <label for="file">File:</label>
        <input type="file" name="file" id="file" required>
        <label for="caption">Caption:</label>
        <textarea name="caption" id="caption" required></textarea>
        <button type="submit">Upload</button>
    </form>
    <div class="loading">
        <span></span><span></span><span></span>
        <p>Mengunggah file, harap tunggu...</p>
    </div>
</div>
</body>
</html>
