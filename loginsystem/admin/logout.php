<?php
// Memulai sesi PHP
session_start(); // Fungsi ini digunakan untuk memulai sesi. Harus dipanggil sebelum ada output lain ke browser.

// Menghancurkan sesi
session_destroy(); // Menghapus semua data yang terkait dengan sesi saat ini. 
// Setelah sesi dihancurkan, semua variabel sesi tidak lagi tersedia.
?>
<!-- Bagian JavaScript untuk mengarahkan pengguna ke halaman "index.php" -->
<script language="javascript">
    // Mengarahkan pengguna ke halaman "index.php" menggunakan JavaScript
    document.location = "index.php"; // Mengubah lokasi dokumen saat ini ke "index.php".
    // Alternatif yang lebih modern: window.location.href = "index.php";
</script>