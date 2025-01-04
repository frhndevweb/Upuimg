# Upuimg

**Upuimg** adalah sebuah platform sosial sederhana yang memungkinkan pengguna untuk mendaftar, login, mengunggah gambar, memberi like, dan mengomentari unggahan. Dibuat dengan PHP dan MySQL, proyek ini adalah implementasi dasar dari jejaring sosial minimalis buatan saya sendiri.

## Fitur
- **Autentikasi Pengguna**: Registrasi, login, dan logout.
- **Profil Pengguna**: Setel gambar profil.
- **Unggah Gambar**: Unggah gambar dengan caption.
- **Interaksi Sosial**:
  - Beri like pada unggahan.
  - Tinggalkan komentar pada unggahan.
- **Responsif dan Mudah Digunakan**: Antarmuka berbasis HTML dan CSS.

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

## Database
**Nama Database**: `social_media`

### Struktur Tabel
1. **users**
   - Informasi pengguna.
   - Kolom: `id`, `username`, `password`, `created_at`, `profile_picture`.

2. **uploads**
   - Penyimpanan unggahan gambar.
   - Kolom: `id`, `user_id`, `filename`, `caption`, `created_at`.

3. **likes**
   - Penyimpanan data like pada unggahan.
   - Kolom: `id`, `upload_id`, `user_id`, `created_at`.

4. **comments**
   - Penyimpanan komentar pada unggahan.
   - Kolom: `id`, `upload_id`, `user_id`, `comment`, `created_at`.

### Query SQL
```sql
CREATE DATABASE IF NOT EXISTS social_media;
USE social_media;

-- Tabel pengguna
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    profile_picture VARCHAR(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Tabel unggahan
CREATE TABLE uploads (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    filename VARCHAR(255) NOT NULL,
    caption TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Tabel likes
CREATE TABLE likes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    upload_id INT NOT NULL,
    user_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (upload_id) REFERENCES uploads(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Tabel komentar
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

## Instalasi
1. Clone repositori ini:
   ```bash
   git clone https://github.com/frhndevweb/Upuimg.git
   ```
2. Import file `db.sql` ke database MySQL kamu.
3. Pastikan file `db.php` telah dikonfigurasi dengan kredensial database kamu.

## Cara Menjalankan dengan XAMPP

1. **Instal XAMPP**  
   - Unduh dan instal [XAMPP](https://www.apachefriends.org/index.html) di komputer kamu.  
   - Jalankan XAMPP, kemudian aktifkan modul **Apache** dan **MySQL**.  

2. **Pindahkan Proyek ke Folder XAMPP**  
   - Salin seluruh folder proyek `Upuimg` ke folder `htdocs` di direktori instalasi XAMPP. Contoh:  
     ```
     C:\xampp\htdocs\Upuimg
     ```

3. **Import Database**  
   - Buka **phpMyAdmin** melalui browser dengan mengakses `http://localhost/phpmyadmin`.  
   - Buat database baru dengan nama `social_media`.  
   - Import file `db.sql` (yang berisi struktur tabel dan data) ke database tersebut.  

4. **Konfigurasi File `db.php`**  
   - Pastikan file `db.php` memiliki konfigurasi yang sesuai dengan server MySQL lokal XAMPP. Contoh:  
     ```php
     <?php
     $host = 'localhost';
     $db   = 'social_media';
     $user = 'root'; // Default username MySQL di XAMPP
     $pass = '';     // Kosongkan jika tidak ada password
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

5. **Akses Proyek di Browser**  
   - Buka browser dan akses URL berikut:  
     ```
     http://localhost/Upuimg
     ```
   - Proyek kini dapat digunakan! Pastikan semua fitur seperti login, registrasi, dan unggah gambar berjalan dengan baik.

6. **Testing dan Debugging**  
   - Jika ada masalah, cek:  
     - Kredensial di `db.php`.  
     - Struktur database dan data di phpMyAdmin.  
     - Struktur direktori proyek di folder `htdocs`.  


**Selamat Mencoba😁**
