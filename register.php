<?php
namespace App\Models;

session_start();

include('includes/alerts.php');

// check for register request
if (isset($_POST["register"])){
    $email = $_POST["email"];
    $password = $_POST["password"];
    $password_repeat = $_POST["repeat_password"];

    
    // include the user model
    require('models/User.php');

    $user = new User();
    
    if ($user->emailAlreadyExists($email)){
        if (!$user->isActive($email)){
            mail($email, "Account Activation", "Activate your account by clicking the link: ");
            $user->activateAccount($email);
            $ALERTS[] = [
                'type' => 'success',
                'msg' => 'We have sent an activation link to your email'
            ];
        }else{
            $ALERTS[] = [
                'type' => 'error',
                'msg' => '<i class="fa fa-times"></i> This email is already exists'
            ];
        }
    }else{
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $ALERTS[] = [
                'type' => 'error',
                'msg' => '<i class="fa fa-times"></i> invalid email'
            ];
        }else{
            if ($password != $password_repeat){
                $ALERTS[] = [
                    'type' => 'error',
                    'msg' => '<i class="fa fa-times"></i> Password does not match'
                ];
            }else{
                $user_data = [
                    "name" => $_POST["name"],
                    "firstname" => $_POST["firstname"],
                    "email" => $email,
                    "password" => $password,
                ];
            
                $user->register($user_data);
                $_SESSION["email"] = $email;
            
                header("location:index.php");
                exit();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('./includes/header.php'); ?>
    <title>Payments System | Register</title>
</head>
<body>
    <main>
        <section id="register">
            <div class="logo">
                <img src="./img/logo.png" height="200px">
            </div>
            <h1>Register your account</h1>
            <form action="register.php" method="post">
                <div class="group">
                    <input type="text" name="name" placeholder="Name">
                    <input type="text" name="firstname" placeholder="First name">
                </div>
                <input type="email" name="email" placeholder="E-mail" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="password" name="repeat_password" placeholder="Repeat password" required>
                <div class="group">
                    <button type="submit" name="register"><i class="fa fa-user-plus"></i> Register now</button>
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