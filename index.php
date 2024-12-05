<?php
include('config.php');


$search = '';
$results = [];

if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $stmt = $pdo->prepare("SELECT * FROM tb_sma_smk WHERE nama_sekolah LIKE :search OR kecamatan LIKE :search");
    $stmt->execute(['search' => "%$search%"]);
    $results = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data SMA dan SMK Se-Kota Batam</title>
    <link rel="stylesheet" href="style.css"> <!-- Link ke file CSS -->
</head>

<body>
    <h1>Data SMA dan SMK Se-Kota Batam</h1>
   

    <?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_sekolah";


// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Mendapatkan kata kunci pencarian jika ada
$search = "";
if (isset($_POST['search'])) {
    $search = $_POST['search'];
}

// Query untuk mengambil data berdasarkan pencarian dengan prepared statement
$sql = "SELECT * FROM tb_sma_smk WHERE nama_sekolah LIKE ? OR kecamatan LIKE ?";
$stmt = $conn->prepare($sql);

// Mengikat parameter untuk prepared statement
$searchTerm = "%" . $search . "%";
$stmt->bind_param("ss", $searchTerm, $searchTerm);

// Menjalankan query
$stmt->execute();

// Mendapatkan hasil
$result = $stmt->get_result();
?>

<!-- Form Pencarian -->
<form method="post" action="">
    <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Cari Sekolah...">
    <button type="submit">Cari</button>
</form>

<?php
// Menampilkan hasil pencarian
if ($result->num_rows > 0) {
    echo "<table border='3'><tr>
    <th>NAMA SEKOLAH</th>
    <th>AKREDITASI</th>
    <th>STATUS SEKOLAH</th>
    <th>ALAMAT</th>
    <th>KECAMATAN</th>
    </tr>";
    while ($row = $result->fetch_assoc()) {
        echo "</td><td>".$row["nama_sekolah"]."</td><td>".$row["akreditasi"]."</td><td>".$row["status_sekolah"]."</td><td>".$row["alamat"]."</td><td>".$row["kecamatan"]."</td></tr>";
    }
    echo "</table>";
} else {
    echo "Tidak ada hasil yang ditemukan.";
}

$stmt->close();
$conn->close();
?>
