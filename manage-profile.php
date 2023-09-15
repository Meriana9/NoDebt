<?php
    namespace App\Models;
    
    require('includes/auth.php');
    require('models/User.php');

    include('includes/alerts.php');

    $user = new User();

    $auth_email = $SESSION_EMAIL;
    $user_data = $user->getUser($auth_email);

    if (isset($_GET["delete"])){
        // check if there is an open group participation
        
        $user->inActiveAccount($auth_email);
        $user->deleteAccount($auth_email);

        //send confirmation email
        mail($user_data["email"], "Delete Account Confirmation", "Your Account Will Be Deleted As Soon As Possible");

        header("location:logout.php");
        exit();
    }

    if (isset($_POST["edit"])){
        $name = $_POST["name"];
        $firstname = $_POST["firstname"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $password_confirmation = $_POST["repeat_password"];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $name == "" || $firstname == ""){
            $ALERTS[] = [
                'type' => 'error',
                'msg' => '<i class="fa fa-times"></i> Invalid data'
            ];
        }else{
            $user->updateProfileData($name, $firstname, $auth_email, $email);
            $ALERTS[] = [
                'type' => 'success',
                'msg' => '<i class="fa fa-check"></i> Profile data updated successfully'
            ];
        }

        if ($password != $password_confirmation || $password == "" || $password_confirmation == ""){
            $ALERTS[] = [
                'type' => 'error',
                'msg' => '<i class="fa fa-times"></i> Password does not match!'
            ];
        }else{
            $user->updatePassword($auth_email, $password);
            $ALERTS[] = [
                'type' => 'success',
                'msg' => '<i class="fa fa-check"></i> Password updated successfully'
            ];
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('./includes/header.php'); ?>
    <title>Payments System | Manage profile</title>
</head>
<body>
    <?php include('./includes/navbar.php'); ?>
    
    <main>
        <section id="manage-profile">
            <h1><i class="fa fa-user-cog"></i> Manage your profile</h1>
            <form action="manage-profile.php" method="post">
                <div class="group">
                    <input type="text" name="name" placeholder="First name" value="<?php echo $user_data['name']; ?>">
                    <input type="text" name="firstname" placeholder="Last name" value="<?php echo $user_data['firstname']; ?>">
                </div>
                <input type="email" name="email" placeholder="E-mail" value="<?php echo $user_data['email']; ?>">
                <div class="group">
                    <input type="password" name="password" placeholder="Password">
                    <input type="password" name="repeat_password" placeholder="Repeat password">
                </div>
                <div class="group">
                    <button type="submit" name="edit"><i class="fa fa-save"></i> Save Changes</button>
                    <div>
                        <a href="manage-profile.php?delete" onclick="return confirm('Are you sure?')"><i class="fa fa-trash"></i> Delete your account</a>
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