<?php
require_once "include/header.php";
?>

<?php
require_once "include/header.php";
?>


<?php

$nameErr = $emailErr = $passErr =  "";
$name = $email = $dob = $gender = $pass = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty($_REQUEST["gender"])) {
        $gender = "";
    } else {
        $gender = $_REQUEST["gender"];
    }


    if (empty($_REQUEST["dob"])) {
        $dob = "";
    } else {
        $dob = $_REQUEST["dob"];
    }

    if (empty($_REQUEST["name"])) {
        $nameErr = "<p style='color:red'> * Nama Diperlukan</p>";
    } else {
        $name = $_REQUEST["name"];
    }

    if (empty($_REQUEST["email"])) {
        $emailErr = "<p style='color:red'> * Email Diperlukan</p> ";
    } else {
        $email = $_REQUEST["email"];
    }

    if (empty($_REQUEST["pass"])) {
        $passErr = "<p style='color:red'> * Password Diperlukan</p> ";
    } else {
        $pass = $_REQUEST["pass"];
    }


    if (!empty($name) && !empty($email) && !empty($pass)) {

        // database connection
        require_once "../connection.php";

        $sql_select_query = "SELECT email FROM admin WHERE email = '$email' ";
        $r = mysqli_query($conn, $sql_select_query);

        if (mysqli_num_rows($r) > 0) {
            $emailErr = "<p style='color:red'> * Email Sudah Terdaftar</p>";
        } else {

            $sql = "INSERT INTO admin( name , email , password , dob, gender ) VALUES( '$name' , '$email' , '$pass' , '$dob' , '$gender' )  ";
            $result = mysqli_query($conn, $sql);
            if ($result) {
                $name = $email = $dob = $gender = $pass = "";
                echo "<script>
                        $(document).ready( function(){
                            $('#showModal').modal('show');
                            $('#modalHead').hide();
                            $('#linkBtn').attr('href', 'manage-admin.php');
                            $('#linkBtn').text('Lihat Admin');
                            $('#addMsg').text('Admin Sukses Ditambahkan!');
                            $('#closeBtn').text('Tambah Lagi?');
                        })
                     </script>
                     ";
            }
        }
    }
}

?>



<div>
    <div class="login-form-bg h-100">
        <div class="container mt-5 h-100">
            <div class="row justify-content-center h-100">
                <div class="col-xl-6">
                    <div class="form-input-content">
                        <div class="card login-form mb-0">
                            <div class="card-body pt-5 shadow">
                                <h4 class="text-center">Tambahkan Admin Baru</h4>
                                <form method="POST" action=" <?php htmlspecialchars($_SERVER['PHP_SELF']) ?>">

                                    <div class="form-group">
                                        <label>Nama Lengkap:</label>
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
                                        <label>Tanggal Lahir :</label>
                                        <input type="date" class="form-control" value="<?php echo $dob; ?>" name="dob">

                                    </div>

                                    <div class="form-group form-check form-check-inline">
                                        <label class="form-check-label">Jenis Kelamin :</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" <?php if ($gender == "Pria") {
                                                                                                        echo "checked";
                                                                                                    } ?> value="Pria" selected>
                                        <label class="form-check-label">Pria</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" <?php if ($gender == "Wanita") {
                                                                                                        echo "checked";
                                                                                                    } ?> value="Wanita">
                                        <label class="form-check-label">Wanita</label>
                                    </div>

                                    <br>

                                    <button type="submit" class="btn btn-primary btn-block">Tambah</button>
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


<?php
require_once "include/footer.php";
?>