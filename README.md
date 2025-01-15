# Upuimg

![Upuimg Logo](https://github.com/frhndevweb/Upuimg/blob/master/upuimg.png)

**Upuimg** adalah sebuah platform sosial sederhana yang memungkinkan pengguna untuk mendaftar, login, mengunggah gambar, memberi like, dan mengomentari unggahan. Dibuat dengan PHP dan MySQL, proyek ini adalah implementasi dasar dari jejaring sosial minimalis buatan saya sendiri.

---

## Fitur Utama
- **Autentikasi Pengguna**: Registrasi, login, dan logout.
- **Profil Pengguna**: Setel gambar profil.
- **Unggah Gambar**: Unggah gambar dengan caption.
- **Interaksi Sosial**:
  - Beri like pada unggahan.
  - Tinggalkan komentar pada unggahan.
- **Responsif dan Mudah Digunakan**: Antarmuka berbasis HTML dan CSS.

---

## Struktur Proyek
```
Upuimg/
├── db.php          # Koneksi ke database.
├── index.php       # Halaman utama.
├── login.php       # Halaman login.
├── register.php    # Halaman registrasi.
├── upload.php      # Halaman unggah gambar.
├── profile.php     # Halaman profil pengguna.
├── logout.php      # Logout pengguna.
├── style.css       # Gaya CSS.
├── uploads/        # Folder penyimpanan gambar.
```

---

## Demo Langsung
Akses demo langsung melalui tautan berikut: [Upuimg Live Demo](https://upuimg.kesug.com)

---

## Database
### Nama Database
`social_media`

### Struktur Tabel
1. **users**
   - Kolom: `id`, `username`, `password`, `created_at`, `profile_picture`.

2. **uploads**
   - Kolom: `id`, `user_id`, `filename`, `caption`, `created_at`.

3. **likes**
   - Kolom: `id`, `upload_id`, `user_id`, `created_at`.

4. **comments**
   - Kolom: `id`, `upload_id`, `user_id`, `comment`, `created_at`.

---

## Cara Instalasi
1. Clone repositori ini:
   ```bash
   git clone https://github.com/frhndevweb/Upuimg.git
   ```

2. Import file `db.sql` ke database MySQL Anda:
   - Buat database baru dengan nama `social_media` di phpMyAdmin.
   - Salin query SQL berikut untuk membuat struktur tabel:
     ```sql
     CREATE DATABASE IF NOT EXISTS social_media;
     USE social_media;

     CREATE TABLE users (
         id INT AUTO_INCREMENT PRIMARY KEY,
         username VARCHAR(50) NOT NULL,
         password VARCHAR(255) NOT NULL,
         created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
         profile_picture VARCHAR(255) DEFAULT NULL
     ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

     CREATE TABLE uploads (
         id INT AUTO_INCREMENT PRIMARY KEY,
         user_id INT NOT NULL,
         filename VARCHAR(255) NOT NULL,
         caption TEXT DEFAULT NULL,
         created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
         FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
     ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

     CREATE TABLE likes (
         id INT AUTO_INCREMENT PRIMARY KEY,
         upload_id INT NOT NULL,
         user_id INT NOT NULL,
         created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
         FOREIGN KEY (upload_id) REFERENCES uploads(id) ON DELETE CASCADE ON UPDATE CASCADE,
         FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
     ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

     CREATE TABLE comments (
         id INT AUTO_INCREMENT PRIMARY KEY,
         upload_id INT NOT NULL,
         user_id INT NOT NULL,
         comment TEXT NOT NULL,
         created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
         FOREIGN KEY (upload_id) REFERENCES uploads(id) ON DELETE CASCADE ON UPDATE CASCADE,
         FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
     ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
     ```

3. Sesuaikan file `db.php` dengan kredensial database Anda:
   ```php
   <?php
   $host = 'localhost';
   $db   = 'social_media';
   $user = 'root';
   $pass = '';
   $charset = 'utf8mb4';

   $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
   $options = [
       PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
       PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
       PDO::ATTR_EMULATE_PREPARES   => false,
   ];

   try {
       $pdo = new PDO($dsn, $user, $pass, $options);
   } catch (\PDOException $e) {
       throw new \PDOException($e->getMessage(), (int)$e->getCode());
   }
   ?>
   ```

4. Akses proyek di browser:
   ```
   http://localhost/Upuimg
   ```

---

## Dukungan
Untuk pertanyaan atau laporan masalah, silakan buka [issue di GitHub](https://github.com/frhndevweb/Upuimg/issues).

---

**Selamat menggunakan Upuimg!**

