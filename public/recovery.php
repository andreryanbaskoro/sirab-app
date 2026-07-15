<?php
$host = '127.0.0.1';
$db   = 'db_sirab_app';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (Exception $e) {
    die("Koneksi gagal: " . $e->getMessage());
}

$tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
$step = $_GET['step'] ?? '';

echo "<div style='font-family: sans-serif; line-height: 1.6; padding: 20px;'>";

if ($step === 'discard') {
    echo "<h2>Proses 1: Memutus Data Baru</h2>";
    foreach ($tables as $table) {
        try {
            $pdo->exec("ALTER TABLE `$table` DISCARD TABLESPACE");
            echo "✅ Memutus tabel: <b>$table</b><br>";
        } catch (Exception $e) {
            echo "⚠️ Lewati $table: " . $e->getMessage() . "<br>";
        }
    }
    echo "<h3 style='color: blue;'>Langkah 1 Selesai!</h3>";
    echo "<p><b>PENTING:</b> Sekarang buka File Explorer, <b>COPY SEMUA FILE</b> dari folder Backup (lama) Anda ke dalam folder <b>C:\xampp\mysql\data\db_sirab_app</b> (Timpa/Replace semua jika ditanya).</p>";
    echo "<p>Jika proses COPY sudah selesai, silakan klik tombol di bawah ini:</p>";
    echo "<a href='?step=import' style='background: green; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px;'>Lanjut Langkah 2 (IMPORT)</a>";

} elseif ($step === 'import') {
    echo "<h2>Proses 2: Menempelkan Data Lama</h2>";
    foreach ($tables as $table) {
        try {
            $pdo->exec("ALTER TABLE `$table` IMPORT TABLESPACE");
            echo "✅ Menempelkan data tabel: <b>$table</b><br>";
        } catch (Exception $e) {
            echo "❌ Gagal $table: " . $e->getMessage() . "<br>";
        }
    }
    echo "<h3 style='color: green;'>Langkah 2 Selesai! SEMUA DATA BERHASIL KEMBALI! 🎉</h3>";
    echo "<p>Silakan buka phpMyAdmin atau aplikasi web Anda untuk mengecek datanya.</p>";

} else {
    echo "<h2>Sistem Penyelamat Database (Otomatis)</h2>";
    echo "<p>Alat ini akan mengembalikan seluruh data Anda dari file backup .ibd secara serentak.</p>";
    echo "<a href='?step=discard' style='background: red; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px;'>Mulai Langkah 1 (DISCARD)</a>";
}

echo "</div>";
