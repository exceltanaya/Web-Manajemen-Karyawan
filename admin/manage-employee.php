<?php 
require_once "include/header.php";
require_once "../connection.php";

// Logika pencarian
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$where = '';
if (!empty($search)) {
    $where = "WHERE name LIKE '%$search%' OR email LIKE '%$search%' OR id LIKE '%$search%'";
}

$sql = "SELECT * FROM employee $where";
$result = mysqli_query($conn, $sql);

$i = 1;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Karyawan - CV. IMMANUEL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: #007bff;
            color: white;
            border-radius: 10px 10px 0 0;
        }
        .table {
            border-radius: 10px;
            overflow: hidden;
        }
        .table thead th {
            background-color: #007bff;
            color: white;
        }
        .btn-action {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
        .btn-action i {
            margin-right: 0.25rem;
        }
        .table td, .table th {
            vertical-align: middle;
        }
    </style>
</head>
<body>

<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Karyawan CV. IMMANUEL</h4>
        </div>
        <div class="card-body">
            <!-- Add search form -->
            <form action="" method="GET" class="mb-3">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Cari karyawan..." name="search" value="<?php echo htmlspecialchars($search); ?>">
                    <button class="btn btn-primary" type="submit">Cari</button>
                    <?php if (!empty($search)): ?>
                        <a href="manage-employees.php" class="btn btn-secondary">Reset</a>
                    <?php endif; ?>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>    No.</th>
                            <th>ID Karyawan</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Jenis Kelamin</th>
                            <th>Tanggal Lahir</th>
                            <th>Usia di Tahun Ini</th>
                            <th>Gaji</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if (mysqli_num_rows($result) > 0) {
                            while ($rows = mysqli_fetch_assoc($result)) {
                                $name = $rows["name"];
                                $email = $rows["email"];
                                $dob = $rows["dob"];
                                $gender = $rows["gender"];
                                $id = $rows["id"];
                                $salary = $rows["salary"];

                                $gender = $gender ?: "Not Defined";
                                
                                if ($dob == "") {
                                    $dob = "Not Defined";
                                    $age = "Not Defined";
                                } else {
                                    $dob = date('d-m-Y', strtotime($dob));
                                    $dateOfBirth = new DateTime($rows["dob"]);
                                    $today = new DateTime();
                                    $age = $today->diff($dateOfBirth)->y;
                                }

                                $salary = $salary ? number_format($salary, 0, ',', '.') : "";
                        ?>
                        <tr>
                            <td><?php echo $i++; ?></td>
                            <td><?php echo $id; ?></td>
                            <td><?php echo $name; ?></td>
                            <td><?php echo $email; ?></td>
                            <td><?php echo $gender; ?></td>
                            <td><?php echo $dob; ?></td>
                            <td><?php echo $age; ?></td>
                            <td>Rp <?php echo $salary; ?></td>
                            <td>
                                <a href='edit-employee.php?id=<?php echo $id; ?>' class='btn btn-primary btn-action me-2'>
                                    <i class='fas fa-edit'></i>Edit
                                </a>
                                <a href='javascript:void(0);' onclick='showDeleteConfirmation("<?php echo $id; ?>", "<?php echo $name; ?>")' class='btn btn-danger btn-action'>
                                    <i class='fas fa-trash'></i>Hapus
                                </a>
                            </td>
                        </tr>
                        <?php 
                            }
                        } else {
                            echo "<tr><td colspan='9' class='text-center'>Tidak ada karyawan yang ditemukan!</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<!-- Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Penghapusan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Apakah Anda yakin ingin menghapus karyawan <span id="employeeName"></span>?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-danger" id="confirmDelete">Ya, Hapus</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
let deleteModal;

function showDeleteConfirmation(id, name) {
    $('#employeeName').text(name);
    $('#confirmDelete').data('id', id);
    deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}

$(document).ready(function() {
    $('#confirmDelete').click(function() {
        var id = $(this).data('id');
        $.ajax({
            url: 'delete-employee.php',
            type: 'POST',
            data: {id: id},
            success: function(response) {
                if(response == 'success') {
                    deleteModal.hide();
                    location.reload();
                } else {
                    alert('Terjadi kesalahan saat menghapus karyawan.');
                }
            }
        });
    });

    // Event listener untuk tombol Batal
    $('.btn-secondary[data-bs-dismiss="modal"]').click(function() {
        deleteModal.hide();
    });

    // Event listener untuk tombol close (x)
    $('.btn-close').click(function() {
        deleteModal.hide();
    });

    // Event listener untuk klik di luar modal
    $('#deleteModal').on('click', function(e) {
        if (e.target === this) {
            deleteModal.hide();
        }
    });
});
</script>
</body>
</html>

<?php 
require_once "include/footer.php";
?>