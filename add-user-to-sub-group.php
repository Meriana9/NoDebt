<?php

namespace App\Models;

if (!isset($_GET['sgid']) || intval($_GET['sgid']) == 0) {
    header('location:index.php');
    exit();
}

require('includes/auth.php');
include('includes/alerts.php');
require('models/User.php');
require('models/SubGroup.php');

$user_id = User::getID($_SESSION['email']);
$sg_name = SubGroup::get($_GET['sgid'])['name'];
$users = SubGroup::getUsersToAdd($_GET['sgid']);

if(isset($_POST['add'])){
    SubGroup::addUser($_GET['sgid'], $_POST['user_id']);
    $ALERTS[] = [
        'type' => 'success',
        'msg' => '<i class="fa fa-check"></i> User added to sub group successfully, <a href="index.php">Home</a>'
    ];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include('./includes/header.php'); ?>
    <title>Payments System | Add member to sub group</title>
</head>

<body>
    <?php include('./includes/navbar.php'); ?>

    <main>
        <section id="invite-user">
            <h1><i class="fa fa-user-plus"></i> Add user to sub group [<?php echo $sg_name; ?>]</h1>
            <form action="add-user-to-sub-group.php?sgid=<?php echo $_GET['sgid']; ?>" method="post">
                <select name="user_id">
                    <?php
                    if (count($users) > 0) {
                        foreach ($users as $u) ?>
                        <option value="<?php echo $u['id']; ?>"><?php echo $u['name']; ?></option>
                    <?php
                    } else {
                    ?>
                        <option value="0">No Users Found</option>
                    <?php
                    }
                    ?>
                </select>
                <div class="group">
                    <button type="submit" name="add"><i class="fa fa-plus"></i> Add User</button>
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