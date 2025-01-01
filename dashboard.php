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

// Proses input data mahasiswa baru
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['input_mahasiswa'])) {
    $namaMhs = $_POST['nama_mahasiswa'];
    $nim = $_POST['nim'];
    $ipk = $_POST['ipk'];
    $sks = $ipk < 3 ? 20 : 24;

    // Periksa apakah NIM sudah ada
    $cekNIM = $conn->prepare("SELECT * FROM inputmhs WHERE nim = ?");
    $cekNIM->bind_param("s", $nim);
    $cekNIM->execute();
    $result = $cekNIM->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('NIM sudah ada, gunakan NIM yang berbeda.');</script>";
    } else {
        // Simpan data ke database
        $stmt = $conn->prepare("INSERT INTO inputmhs (namaMhs, nim, ipk, sks, matakuliah) VALUES (?, ?, ?, ?, ?)");
        $defaultMatkul = '-';
        $stmt->bind_param("ssdss", $namaMhs, $nim, $ipk, $sks, $defaultMatkul);
        $stmt->execute();
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard KRS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-control {
            height: calc(2.5rem + 2px);
        }
        .btn-primary {
            width: 100%;
            height: calc(2.5rem + 2px);
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Sistem Input Kartu Rencana Studi (KRS)</h2>
        <p class="text-center">Input data Mahasiswa disini!</p>

        <!-- Form Input Mahasiswa -->
        <form class="mb-5" method="POST" action="">
            <div class="row g-3 align-items-center">
                <div class="col-md-4">
                    <input type="text" class="form-control" name="nama_mahasiswa" placeholder="Nama Mahasiswa" required>
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control" name="nim" placeholder="NIM" required>
                </div>
                <div class="col-md-4">
                    <input type="number" step="0.01" class="form-control" name="ipk" placeholder="IPK" required>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary" name="input_mahasiswa">Input Mahasiswa</button>
                </div>
            </div>
        </form>

        <!-- Tabel Data -->
        <table class="table table-bordered text-center">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Nama Mahasiswa</th>
                    <th>NIM</th>
                    <th>IPK</th>
                    <th>SKS Maksimal</th>
                    <th>Matakuliah</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Ambil data dari database
                $query = "SELECT * FROM inputmhs";
                $result = $conn->query($query);

                if ($result->num_rows > 0) {
                    $no = 1;
                    while ($row = $result->fetch_assoc()) {
                        // Hapus karakter
                        $matkul = ltrim(ltrim($row['matakuliah'], '-'), ',');

                        // Jika nama mata kuliah terlalu panjang
                        $displayMatkul = strlen($matkul) > 30 ? substr($matkul, 0, 30) . '...' : $matkul;

                        echo "<tr>
                            <td>{$no}</td>
                            <td>{$row['namaMhs']}</td>
                            <td>{$row['nim']}</td>
                            <td>{$row['ipk']}</td>
                            <td>{$row['sks']}</td>
                            <td title='{$matkul}'>{$displayMatkul}</td>
                            <td>
                                <a href='jwlmatkul.php?id={$row['id']}' class='btn btn-warning btn-sm'>Edit</a>
                                <a href='hapus.php?id={$row['id']}' class='btn btn-danger btn-sm'>Hapus</a>
                                <a href='lihat.php?id={$row['id']}' class='btn btn-info btn-sm'>Lihat</a>
                            </td>
                        </tr>";
                        $no++;
                    }
                } else {
                    echo "<tr><td colspan='7'>Tidak ada data.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>