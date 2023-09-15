<?php
    namespace App\Models;

    require('includes/auth.php');
    include('includes/alerts.php');

    if (!isset($_GET['gid']) || intval($_GET['gid']) == 0) {
        header('location:index.php');
        exit();
    }

    if (isset($_POST['create'])){
        require('models/Payment.php');
        require('models/User.php');

        $amount = trim($_POST['amount']);
        $group_id = trim($_GET['gid']);
        $user_id = User::getID($_SESSION['email']);

        if ($amount != 0){
            Payment::create($amount, $group_id, $user_id);
            $ALERTS[] = [
                'type' => 'success',
                'msg' => '<i class="fa fa-check"></i> payment added successfully'
            ];
        }else{
            $ALERTS[] = [
                'type' => 'error',
                'msg' => '<i class="fa fa-times"></i> payment amount can not be zero'
            ];
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('./includes/header.php'); ?>
    <title>Payments System | Pay group</title>
</head>
<body>
    <?php include('./includes/navbar.php'); ?>
    
    <main>
        <section>
            <h1><i class="fa fa-users"></i> Pay group</h1>
            <form action="pay-group.php?gid=<?php echo $_GET['gid']; ?>" method="post">
                <input type="text" name="amount" placeholder="Payment amount">
                <div class="group">
                    <button type="submit" name="create"><i class="fa fa-plus"></i> Add Payment</button>
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