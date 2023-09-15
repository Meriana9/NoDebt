<?php
    namespace App\Models;

    require('includes/auth.php');
    include('includes/alerts.php');

    if (!isset($_GET['gid']) || intval($_GET['gid']) == 0) {
        header('location:index.php');
        exit();
    }

    if (isset($_POST['create'])){
        require('models/SubGroup.php');
        require('models/User.php');

        $name = trim($_POST['name']);
        $group_id = trim($_GET['gid']);
        $user_id = User::getID($_SESSION['email']);

        if ($name != ''){
            SubGroup::create($user_id, $group_id, $name);
            $ALERTS[] = [
                'type' => 'success',
                'msg' => '<i class="fa fa-check"></i> Sub group created successfully'
            ];
        }else{
            $ALERTS[] = [
                'type' => 'error',
                'msg' => '<i class="fa fa-times"></i> Sub group name can not be empty'
            ];
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('./includes/header.php'); ?>
    <title>Payments System | Creat Sub Group</title>
</head>
<body>
    <?php include('./includes/navbar.php'); ?>
    
    <main>
        <section>
            <h1><i class="fa fa-users"></i> Create Sub Group</h1>
            <form action="create-sub-group.php?gid=<?php echo $_GET['gid']; ?>" method="post">
                <input type="text" name="name" placeholder="Sub group name">
                <div class="group">
                    <button type="submit" name="create"><i class="fa fa-plus"></i> Create Sub Group</button>
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