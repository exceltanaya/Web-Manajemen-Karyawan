<?php
require_once "include/header.php";
require_once "../connection.php";

$id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;

$sql = "SELECT * FROM employee WHERE id = $id";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    $rows = mysqli_fetch_assoc($result);
    $name = $rows["name"];
    $email = $rows["email"];
    $dob = $rows["dob"];
    $gender = $rows["gender"];
    $salary = $rows["salary"];
}

$nameErr = $emailErr = $passErr = $salaryErr = "";
$pass = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $gender = $_REQUEST["gender"] ?? "";
    $dob = $_REQUEST["dob"] ?? "";
    $name = $_REQUEST["name"] ?? "";
    $salary = $_REQUEST["salary"] ?? "";
    $email = $_REQUEST["email"] ?? "";
    $pass = $_REQUEST["pass"] ?? "";

    if (empty($name)) {
        $nameErr = "<p style='color:red'> * Nama Diperlukan</p>";
    }
    if (empty($salary)) {
        $salaryErr = "<p style='color:red'> * Gaji Diperlukan</p>";
    }
    if (empty($email)) {
        $emailErr = "<p style='color:red'> * Email Diperlukan</p>";
    }
    if (empty($pass)) {
        $passErr = "<p style='color:red'> * Kata Sandi Diperlukan</p>";
    }

    if (!empty($name) && !empty($email) && !empty($pass) && !empty($salary)) {
        $sql_select_query = "SELECT email FROM employee WHERE email = '$email' AND id != $id";
        $r = mysqli_query($conn, $sql_select_query);

        if (mysqli_num_rows($r) > 0) {
            $emailErr = "<p style='color:red'> * Email Sudah Terdaftar</p>";
        } else {
            $sql = "UPDATE employee SET name = '$name', email = '$email', password ='$pass', dob='$dob', gender='$gender', salary='$salary' WHERE id = $id";
            $result = mysqli_query($conn, $sql);
            if ($result) {
                echo "<script>
                        $(document).ready(function(){
                            $('#showModal').modal('show');
                            $('#modalHead').hide();
                            $('#linkBtn').attr('href', 'manage-employee.php');
                            $('#linkBtn').text('Lihat Karyawan');
                            $('#addMsg').text('Profile Sukses Diubah!');
                            $('#closeBtn').text('Ubah Lagi?');
                        })
                     </script>";
            }
        }
    }
}
?>

<div>
    <div class="login-form-bg h-100">
        <div class="container h-100">
            <div class="row justify-content-center h-100">
                <div class="col-xl-6">
                    <div class="form-input-content">
                        <div class="card login-form mb-0">
                            <div class="card-body pt-4 shadow">
                                <h4 class="text-center">Ubah Profil</h4>
                                <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?id=' . $id; ?>">
                                    <div class="form-group">
                                        <label>Nama Lengkap :</label>
                                        <input type="text" class="form-control" value="<?php echo $name; ?>" name="name">
                                        <?php echo $nameErr; ?>
                                    </div>

                                    <div class="form-group">
                                        <label>Email :</label>
                                        <input type="email" class="form-control" value="<?php echo $email; ?>" name="email">
                                        <?php echo $emailErr; ?>
                                    </div>

                                    <div class="form-group">
                                        <label>Password: </label>
                                        <input type="password" class="form-control" value="<?php echo $pass; ?>" name="pass">
                                        <?php echo $passErr; ?>
                                    </div>

                                    <div class="form-group">
                                        <label>Gaji Pokok :</label>
                                        <input type="number" class="form-control" value="<?php echo $salary; ?>" name="salary">
                                        <?php echo $salaryErr; ?>
                                    </div>

                                    <div class="form-group">
                                        <label>Tanggal Lahir :</label>
                                        <input type="date" class="form-control" value="<?php echo $dob; ?>" name="dob">
                                    </div>

                                    <div class="form-group form-check form-check-inline">
                                        <label class="form-check-label">Jenis kelamin :</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" <?php if ($gender == "Pria") echo "checked"; ?> value="Pria">
                                        <label class="form-check-label">Pria</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" <?php if ($gender == "Pria") echo "checked"; ?> value="Wanita">
                                        <label class="form-check-label">Wanita</label>
                                    </div>

                                    <br>

                                    <button type="submit" class="btn btn-primary btn-block">Perbarui</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once "include/footer.php";
?>