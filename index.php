<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$sql = "SELECT uploads.id AS upload_id, uploads.filename, uploads.caption, users.username, 
        (SELECT COUNT(*) FROM likes WHERE likes.upload_id = uploads.id) AS like_count 
        FROM uploads 
        JOIN users ON uploads.user_id = users.id 
        ORDER BY uploads.created_at DESC";
$uploads = $conn->query($sql);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['comment'], $_POST['upload_id'])) {
        $upload_id = $_POST['upload_id'];
        $user_id = $_SESSION['user_id'];
        $comment = $_POST['comment'];

        $stmt = $conn->prepare("INSERT INTO comments (upload_id, user_id, comment) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $upload_id, $user_id, $comment);
        $stmt->execute();
        $stmt->close();
    } elseif (isset($_POST['like'], $_POST['upload_id'])) {
        $upload_id = $_POST['upload_id'];
        $user_id = $_SESSION['user_id'];

        $check_like = $conn->query("SELECT * FROM likes WHERE upload_id = $upload_id AND user_id = $user_id");
        if ($check_like->num_rows == 0) {
            $conn->query("INSERT INTO likes (upload_id, user_id) VALUES ($upload_id, $user_id)");
        } else {
            $conn->query("DELETE FROM likes WHERE upload_id = $upload_id AND user_id = $user_id");
        }
    }
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda - Upuimg</title>
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
            justify-content: flex-end;
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

        .upload-item .details {
            margin-bottom: 10px;
        }

        .details strong {
            font-size: 16px;
            color: #1abc9c;
        }

        .details p {
            font-size: 14px;
            color: #b3b3b3;
        }

        .actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .actions .like {
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .actions .like i {
            margin-right: 5px;
            color: #e74c3c;
        }

        .actions .like span {
            font-size: 14px;
        }

        .comments-section {
            display: none;
            margin-top: 10px;
            background: #2b2f33;
            padding: 15px;
            border-radius: 8px;
        }

        .comments-section h3 {
            margin-bottom: 10px;
            color: #ffffff;
        }

        .comments-section ul {
            list-style-type: none;
            padding: 0;
        }

        .comments-section li {
            margin-bottom: 10px;
            color: #d3d3d3;
        }

        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #4c5054;
            border-radius: 8px;
            margin-bottom: 10px;
            background: #4c5054;
            color: #ffffff;
            font-family: 'Poppins', sans-serif;
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
    </style>
    <script>
        function toggleComments(uploadId) {
            const section = document.getElementById(`comments-${uploadId}`);
            section.style.display = section.style.display === 'block' ? 'none' : 'block';
        }
    </script>
</head>
<body>
<div class="container">
    <h1>Selamat Datang di Upuimg (Upload yoUr Img)</h1>
    <p>Halo, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
    <nav>
        <a href="upload.php">Upload</a>
        <a href="profile.php">Profil</a>
        <a href="logout.php">Logout</a>
    </nav>
    <div class="uploads">
        <?php while ($upload = $uploads->fetch_assoc()): ?>
            <div class="upload-item">
                <?php 
                $file_extension = pathinfo($upload['filename'], PATHINFO_EXTENSION);
                if (in_array($file_extension, ['mp4', 'avi', 'mov'])): ?>
                    <video controls>
                        <source src="uploads/<?php echo htmlspecialchars($upload['filename']); ?>" type="video/<?php echo $file_extension; ?>">
                        Browser Anda tidak mendukung video.
                    </video>
                <?php else: ?>
                    <img src="uploads/<?php echo htmlspecialchars($upload['filename']); ?>" alt="Image">
                <?php endif; ?>
                <div class="details">
                    <p><strong>@<?php echo htmlspecialchars($upload['username']); ?></strong></p>
                    <p><?php echo htmlspecialchars($upload['caption']); ?></p>
                </div>
                <div class="actions">
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="upload_id" value="<?php echo $upload['upload_id']; ?>">
                        <button type="submit" name="like" class="like">
                            <i class="fas fa-heart"></i>
                            <span><?php echo $upload['like_count']; ?></span>
                        </button>
                    </form>
                    <button onclick="toggleComments(<?php echo $upload['upload_id']; ?>)">Komentar</button>
                </div>
                <div id="comments-<?php echo $upload['upload_id']; ?>" class="comments-section">
                    <h3>Komentar</h3>
                    <ul>
                        <?php
                        $comments = $conn->query("SELECT comments.comment, users.username FROM comments JOIN users ON comments.user_id = users.id WHERE comments.upload_id = {$upload['upload_id']} ORDER BY comments.created_at ASC");
                        ?>
                        <?php while ($comment = $comments->fetch_assoc()): ?>
                            <li><strong><?php echo htmlspecialchars($comment['username']); ?>:</strong> <?php echo htmlspecialchars($comment['comment']); ?></li>
                        <?php endwhile; ?>
                    </ul>
                    <form method="POST">
                        <input type="hidden" name="upload_id" value="<?php echo $upload['upload_id']; ?>">
                        <textarea name="comment" placeholder="Tambahkan komentar..."></textarea>
                        <button type="submit">Kirim</button>
                    </form>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>
</body>
</html>
