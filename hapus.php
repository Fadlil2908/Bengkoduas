<?php
// Koneksi ke database
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'bengkoduas'; // Sesuaikan dengan nama database Anda

$conn = new mysqli($host, $user, $password, $database);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Periksa apakah parameter id ada
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Query untuk menghapus data berdasarkan id
    $stmt = $conn->prepare("DELETE FROM inputmhs WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Jika berhasil, arahkan kembali ke halaman utama
        echo "<script>
                alert('Data berhasil dihapus.');
                window.location.href = 'dashboard.php';
              </script>";
    } else {
        // Jika gagal, tampilkan pesan kesalahan
        echo "<script>
                alert('Gagal menghapus data.');
                window.location.href = 'dashboard.php';
              </script>";
    }

    $stmt->close();
} else {
    // Jika parameter id tidak ada, kembali ke halaman utama
    echo "<script>
            alert('ID tidak ditemukan.');
            window.location.href = 'dashboard.php';
          </script>";
}

$conn->close();
?>
