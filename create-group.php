<?php
    namespace App\Models;

    require('includes/auth.php');
    include('includes/alerts.php');

    if (isset($_POST['create'])){
        require('models/Group.php');
        require('models/User.php');

        $name = trim($_POST['name']);
        $currency = trim($_POST['currency']);
        $user_id = User::getID($_SESSION['email']);

        if ($name != ''){
            Group::create($name, $currency, $user_id);
            $ALERTS[] = [
                'type' => 'success',
                'msg' => '<i class="fa fa-check"></i> group created successfully'
            ];
        }else{
            $ALERTS[] = [
                'type' => 'error',
                'msg' => '<i class="fa fa-times"></i> group name can not be empty'
            ];
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('./includes/header.php'); ?>
    <title>Payments System | Create group</title>
</head>
<body>
    <?php include('./includes/navbar.php'); ?>
    
    <main>
        <section>
            <h1><i class="fa fa-users"></i> Create group</h1>
            <form action="create-group.php" method="post">
                <input type="text" name="name" placeholder="Group name">
                <select name="currency">
                    <option value="$">$</option>
                    <option value="&euro;">€</option>
                </select>
                <div class="group">
                    <button type="submit" name="create"><i class="fa fa-plus"></i> Create group</button>
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