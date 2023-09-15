<?php

namespace App\Models;

require('includes/auth.php');
include('includes/alerts.php');

if (!isset($_GET['gid']) || intval($_GET['gid']) == 0) {
    header('location:index.php');
    exit();
}

require('models/Group.php');
require('models/SubGroup.php');


$group_id = intval($_GET['gid']);
$group = Group::get($group_id);
$sub_groups = SubGroup::getSubGroups($_GET['gid']);

if(isset($_POST['delete'])){
    SubGroup::delete($_POST['sgid']);
    $ALERTS[] = [
        'type' => 'success',
        'msg' => '<i class="fa fa-check"></i> Sub group deleted successfully, <a href="index.php">Home</a>'
    ];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include('./includes/header.php'); ?>
    <title>Payments System | Sub Groups</title>
</head>

<body>
    <?php include('./includes/navbar.php'); ?>

    <main>
        <section id="group">
            <h1><i class="fa fa-users"></i> <?php echo $group['name']; ?></h1>
            <br>
            <hr>
            <br>
            <div class="container">
                <div>
                    <h4>Sub Groups</h4>
                    <table>
                        <thead>
                            <th>Name</th>
                            <th>Admin</th>
                            <th>Members</th>
                            <th></th>
                        </thead>
                        <tbody>
                            <?php
                            if (count($sub_groups) > 0) {
                                foreach ($sub_groups as $sg) {
                            ?>
                                    <tr>
                                        <td><?php echo $sg['sgname']; ?></td>
                                        <td><?php echo $sg['uname']; ?> <?php echo $sg['ufname']; ?></td>
                                        <td>
                                            <?php 
                                                $users = SubGroup::getMembers($sg['sgid']);
                                                if (count($users) > 0) {
                                                    foreach ($users as $u) {
                                                        echo $u['name'] . ' ' .$u['firstname'] . ',';
                                                    }
                                                }
                                            ?>
                                        </td>
                                        <td>
                                            <form action="sub-groups.php?gid=<?php echo $_GET['gid']; ?>" method="POST" style="margin-top: 0;display:inline;">
                                                <input type="hidden" name="sgid" value="<?php echo $sg['sgid']; ?>">
                                                <button class="btn btn-sm btn-reject" name="delete" type="submit"><i class="fa fa-trash"></i> Delete</button>
                                            </form>
                                            <a class="btn btn-sm" href="add-user-to-sub-group.php?sgid=<?php echo $sg['sgid']; ?>"><i class="fa fa-plus"></i> Add User</a>
                                        </td>
                                    </tr>
                                <?php
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="3">No Sub Groups Found!</td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <br>
            <hr>
            <br>
            <a class="btn btn-sm" href="create-sub-group.php?gid=<?php echo $group['id']; ?>">Create Sub Group</a>
            <br>
            <br>
            <?php
            print_alerts();
            ?>
        </section>
    </main>

    <?php include('./includes/footer.php'); ?>

</body>

</html>