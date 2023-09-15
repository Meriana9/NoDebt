<?php
    namespace App\Models;

    include('includes/alerts.php');

    // check for login request
    if (isset($_POST["reset"])){
        $email = $_POST["email"];

        // include the user model
        require('models/User.php');

        $user = new User();
        if ($user->emailAlreadyExists($email)){
            $ALERTS[] = [
                'type' => 'error',
                'msg' => '<i class="fa fa-times"></i> The email is not valid'
            ];
        }
        else{
            $new_password = uniqid();

            //reset the password
            $user->resetPassword($email, $new_password);

            //send the new password to user email
            mail($email, "Password Reset", "Your new password: " . $new_password);

            $ALERTS[] = [
                'type' => 'success',
                'msg' => '<i class="fa fa-check"></i> Password reset successfully, please check your email'
            ];
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('./includes/header.php'); ?>
    <title>Payments System | Reset Password</title>
</head>
<body>
    <?php include('./includes/navbar.php'); ?>
    
    <main>
        <section id="login">
            <h1>Reset your Account Password</h1>
            <form action="reset-password.php" method="post">
                <input type="email" name="email" placeholder="E-mail" required>
                <div class="group">
                    <button type="submit" name="reset"><i class="fa fa-lock"></i> Reset Password</button>
                    <div>
                        <a href="login.php">Login to your account</a>
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