# SIPD Tools Get Data

Salah satu aplikasi yang dikembangan oleh daerah untuk membackup data SIPD Kemendagri

### Syarat Penggunaan
- Gunakan PHP >= 7.2 agar enkripsi ssl nya lancar
- Database SQL Server untuk menampung data SIPD

### Cara Penggunaan
- Copy atau rename file ```config.php.example``` ke ```config.php```
- Setting konfigurasi nama daerah, id daerah, username SIPD dll
- Buka console windows atau terminal linux dan masuk ke direktori kerja
- ```php sipd.php``` untuk login SIPD Merah dan mendapatkan SESSION login
- ```php penat.php``` untuk login SIPD Penatausahaan
- ```php rak.php``` untuk menarik data RAK (Rencana Anggaran KAS)
