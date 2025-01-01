<?php
// Koneksi ke database
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'bengkoduas';

$conn = new mysqli($host, $user, $password, $database);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Periksa apakah ID tersedia di URL
if (!isset($_GET['id'])) {
    die("ID mahasiswa tidak ditemukan. <a href='dashboard.php'>Kembali ke dashboard</a>");
}

$mhs_id = $_GET['id'];

// Ambil data mahasiswa berdasarkan ID
$queryMahasiswa = $conn->prepare("SELECT * FROM inputmhs WHERE id = ?");
$queryMahasiswa->bind_param("i", $mhs_id);
$queryMahasiswa->execute();
$resultMahasiswa = $queryMahasiswa->get_result();
$mahasiswa = $resultMahasiswa->fetch_assoc();

if (!$mahasiswa) {
    die("Data mahasiswa tidak ditemukan. <a href='dashboard.php'>Kembali ke dashboard</a>");
}

// Ambil data KRS mahasiswa dari tabel jwl_mhs
$queryKRS = $conn->prepare("SELECT * FROM jwl_mhs WHERE mhs_id = ?");
$queryKRS->bind_param("i", $mhs_id);
$queryKRS->execute();
$resultKRS = $queryKRS->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Rencana Studi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            .btn-warning, /* Sembunyikan tombol "Kembali ke data mahasiswa" */
            .alert .float-end,
            .alert a,
            #printPDF {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between">
            <!-- Tanggal saat dicetak -->
            <span><?= date('d/m/Y, H:i') ?></span>
            <button id="printPDF" class="btn btn-primary" onclick="window.print()">Cetak PDF</button>
        </div>

        <h2 class="text-center mb-4">Kartu Rencana Studi</h2>
        <p class="text-center">Lihat jadwal mata kuliah yang telah diinputkan disini!</p>
        <div class="alert alert-info">
            <strong>Mahasiswa:</strong> <?= $mahasiswa['namaMhs'] ?> | 
            <strong>NIM:</strong> <?= $mahasiswa['nim'] ?> | 
            <strong>IPK:</strong> <?= $mahasiswa['ipk'] ?>
            <a href="dashboard.php" class="btn btn-warning btn-sm float-end">Kembali ke data mahasiswa</a>
        </div>

        <!-- Tabel KRS -->
        <table class="table table-bordered text-center">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Mata Kuliah</th>
                    <th>SKS</th>
                    <th>Kelompok</th>
                    <th>Ruangan</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total_sks = 0;
                if ($resultKRS->num_rows > 0) {
                    $no = 1;
                    while ($row = $resultKRS->fetch_assoc()) {
                        echo "<tr>
                            <td>{$no}</td>
                            <td>{$row['matakuliah']}</td>
                            <td>{$row['sks']}</td>
                            <td>{$row['kelp']}</td>
                            <td>{$row['ruangan']}</td>
                        </tr>";
                        $total_sks += $row['sks'];
                        $no++;
                    }
                } else {
                    echo "<tr><td colspan='5'>Tidak ada data mata kuliah.</td></tr>";
                }
                ?>
                <!-- Baris Total SKS -->
                <tr>
                    <th colspan="2">Total SKS</th>
                    <th><?= $total_sks ?></th>
                    <td colspan="2"></td>
                </tr>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
