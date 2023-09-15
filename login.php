<?php
    namespace App\Models;
    
    session_start();

    if (isset($_SESSION['email'])){
        header("location:index.php");
        exit();
    }

    include('includes/alerts.php');

    // check for login request
    if (isset($_POST["login"])){
        $email = $_POST["email"];
        $password = $_POST["password"];

        // include the user model
        require('models/User.php');

        $user = new User();
        $login_result = $user->login($email, $password);
        if ($login_result != false){
            //set the sessions variables
            $_SESSION['email'] = $email;
            header("location:index.php");
            exit();
        }
        else{
            $ALERTS[] = [
                'type' => 'error',
                'msg' => '<i class="fa fa-times"></i> Wrong email or password'
            ];
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('./includes/header.php'); ?>
    <title>Payments System | Login</title>
</head>
<body>
    <main>
        <section id="login">
            <div class="logo">
                <img src="./img/logo.png" height="200px">
            </div>
            <h1>Login to your account</h1>
            <form action="login.php" method="post">
                <input type="email" name="email" placeholder="E-mail" required>
                <input type="password" name="password" placeholder="Password" required>
                <div class="group">
                    <button type="submit" name="login"><i class="fa fa-lock"></i> Login now</button>
                    <div>
                        <a href="register.php">Create your account</a> | <a href="reset-password.php">Reset your password</a>
                    </div>
                </div>

                <?php
                    print_alerts();
                ?>

            </form>
        </section>
    </main>
    
    <?php include('./includes/footer.php'); ?>

</body>
</html>