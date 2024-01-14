<?php
session_start();
include('database/dbconnect.php');
include('header.php');

$usernameErr = $passwordErr = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST['username'])) {
        $usernameErr = "Username Is Required";
    } else {
        $username = $_POST["username"];
    }

    if (empty($_POST['password'])) {
        $passwordErr = "Password Is Required";
    } else {
        $password = $_POST["password"];
    }

    if (empty($passwordErr) && empty($usernameErr)) {
        $sql = "SELECT r.role_name FROM roles r, users u WHERE u.role_id = r.id AND u.username = '$username' AND u.password = '$password'";

        // Execute the query
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $row["role_name"];
            setcookie('username', $username);

            if ($_SESSION['role'] == 'Administrator') {
                header("Location: views/admin/indexadmin.php");
                exit();
            } else if ($_SESSION['role'] == 'User') {
                header("Location: views/user/indexuser.php");
                exit();
            }
        } else {
            echo "<span style='color: red'>Incorrect username and/or password. Please login again.</span><br>";
        }
    }
}

if (empty($_SESSION['username'])) {
    ?>
    <br>
    <div class="login-form">
        <div class="row g-0">
            <div class="col-lg-6">
                <div class="bg-dark p-5">
                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                        <div class="row g-3">
                            <div class="col-6">
                                <input type="text" name="username" placeholder="Username" style="height: 55px;">
                                <span style="color: red"><?php echo $usernameErr; ?></span>
                            </div>

                            <div class="col-6">
                                <input type="password" name="password" placeholder="Password" style="height: 55px;">
                                <span style="color: red"><?php echo $passwordErr; ?></span>
                            </div>
                            <br>
                            <div class="col-12">
                                <input type="submit" name="login" value="Login" class="btn btn-primary">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php
}

include('footer.php');
?>
