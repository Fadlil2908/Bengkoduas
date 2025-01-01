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

// Proses penyimpanan mata kuliah ke tabel jwl_mhs
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['simpan_matkul'])) {
    $matkul_id = $_POST['matkul'];

    // Ambil data mata kuliah berdasarkan ID
    $queryMatkul = $conn->prepare("SELECT * FROM jwl_matakuliah WHERE id = ?");
    $queryMatkul->bind_param("i", $matkul_id);
    $queryMatkul->execute();
    $resultMatkul = $queryMatkul->get_result();
    $matkul = $resultMatkul->fetch_assoc();

    if ($matkul) {
        // Simpan ke tabel jwl_mhs
        $stmt = $conn->prepare("INSERT INTO jwl_mhs (mhs_id, matakuliah, sks, kelp, ruangan) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("isiss", $mhs_id, $matkul['matakuliah'], $matkul['sks'], $matkul['kelp'], $matkul['ruangan']);
        $stmt->execute();
        $stmt->close();

        // Update tabel inputmhs dengan mata kuliah baru
        $newMatkul = empty($mahasiswa['matakuliah']) ? $matkul['matakuliah'] : $mahasiswa['matakuliah'] . ', ' . $matkul['matakuliah'];
        $updateInputMhs = $conn->prepare("UPDATE inputmhs SET matakuliah = ? WHERE id = ?");
        $updateInputMhs->bind_param("si", $newMatkul, $mhs_id);
        $updateInputMhs->execute();
    }
}

// Proses hapus mata kuliah dari tabel jwl_mhs
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];

    // Ambil data mata kuliah sebelum dihapus
    $queryMatkulToDelete = $conn->prepare("SELECT * FROM jwl_mhs WHERE id = ?");
    $queryMatkulToDelete->bind_param("i", $id);
    $queryMatkulToDelete->execute();
    $resultMatkulToDelete = $queryMatkulToDelete->get_result();
    $matkulToDelete = $resultMatkulToDelete->fetch_assoc();

    if ($matkulToDelete) {
        // Hapus mata kuliah dari tabel jwl_mhs
        $stmt = $conn->prepare("DELETE FROM jwl_mhs WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();

        // Perbarui kolom matakuliah di tabel inputmhs
        $updatedMatkul = str_replace($matkulToDelete['matakuliah'], '', $mahasiswa['matakuliah']);
        $updatedMatkul = preg_replace('/,\s*,/', ',', $updatedMatkul); // Hilangkan koma berlebih
        $updatedMatkul = trim($updatedMatkul, ', '); // Hilangkan koma di awal/akhir
        $updateInputMhs = $conn->prepare("UPDATE inputmhs SET matakuliah = ? WHERE id = ?");
        $updateInputMhs->bind_param("si", $updatedMatkul, $mhs_id);
        $updateInputMhs->execute();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Mata Kuliah Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        td {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 200px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Sistem Input Mata Kuliah Mahasiswa</h2>
        <div class="alert alert-info">
            <strong>Mahasiswa:</strong> <?= $mahasiswa['namaMhs'] ?> | 
            <strong>NIM:</strong> <?= $mahasiswa['nim'] ?> | 
            <strong>IPK:</strong> <?= $mahasiswa['ipk'] ?>
            <a href="dashboard.php" class="btn btn-warning btn-sm float-end">Kembali ke data mahasiswa</a>
        </div>

        <!-- Form Input Mata Kuliah -->
        <form method="POST" action="">
            <div class="row mb-3">
                <div class="col-md-9">
                    <select class="form-select" name="matkul" required>
                        <option value="" disabled selected>Pilih Mata Kuliah</option>
                        <?php
                        // Ambil data mata kuliah dari tabel jwl_matakuliah
                        $query = "SELECT * FROM jwl_matakuliah";
                        $result = $conn->query($query);

                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='{$row['id']}'>{$row['matakuliah']} (SKS: {$row['sks']}, Kelp: {$row['kelp']}, Ruang: {$row['ruangan']})</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100" name="simpan_matkul">Simpan</button>
                </div>
            </div>
        </form>

        <!-- Tabel Daftar Mata Kuliah -->
        <table class="table table-bordered text-center">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Matakuliah</th>
                    <th>SKS</th>
                    <th>Kelompok</th>
                    <th>Ruangan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Ambil data KRS mahasiswa dari tabel jwl_mhs
                $queryKRS = $conn->prepare("SELECT * FROM jwl_mhs WHERE mhs_id = ?");
                $queryKRS->bind_param("i", $mhs_id);
                $queryKRS->execute();
                $resultKRS = $queryKRS->get_result();

                if ($resultKRS->num_rows > 0) {
                    $no = 1;
                    while ($row = $resultKRS->fetch_assoc()) {
                        echo "<tr>
                            <td>{$no}</td>
                            <td title='{$row['matakuliah']}'>{$row['matakuliah']}</td>
                            <td>{$row['sks']}</td>
                            <td>{$row['kelp']}</td>
                            <td>{$row['ruangan']}</td>
                            <td>
                                <a href='?id={$mhs_id}&hapus={$row['id']}' class='btn btn-danger btn-sm'>Hapus</a>
                            </td>
                        </tr>";
                        $no++;
                    }
                } else {
                    echo "<tr><td colspan='6'>Tidak ada data mata kuliah.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
