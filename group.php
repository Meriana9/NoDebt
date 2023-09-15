<?php

namespace App\Models;

require('includes/auth.php');
include('includes/alerts.php');

if (!isset($_GET['gid']) || intval($_GET['gid']) == 0) {
    header('location:index.php');
    exit();
}

require('models/Group.php');
require('models/User.php');
require('models/Expense.php');
require('models/Payment.php');

$group_id = intval($_GET['gid']);
$group = Group::get($group_id);
$users = Group::getUsers($group_id);
$current_user_id = User::getID($_SESSION['email']);
$expenses = Expense::getGroupExpensesWithoutFilters($group_id);
$payments = Payment::getGroupPayments($group_id);

if (isset($_POST['delete'])) {
    Group::delete($_POST['gid']);
    $ALERTS[] = [
        'type' => 'success',
        'msg' => '<i class="fa fa-check"></i> group deleted successfully, <a href="index.php">Home</a>'
    ];
}

if (isset($_POST['confirm'])) {
    Payment::confirm($_POST['pid']);
    $ALERTS[] = [
        'type' => 'success',
        'msg' => '<i class="fa fa-check"></i> payment confirmed successfully'
    ];
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include('./includes/header.php'); ?>
    <title>Payments System | Group Details</title>
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
                    <h4><?php echo $group['currency']; ?> Expenses</h4>
                    <table>
                        <thead>
                            <th>Description</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>User</th>
                            <th>Tags</th>
                        </thead>
                        <tbody>
                            <?php
                            if (count($expenses) > 0) {
                                foreach ($expenses as $e) {
                            ?>
                                    <tr>
                                        <td><?php echo $e['description']; ?></td>
                                        <td><b><?php echo $e['amount']; ?> <?php echo $group['currency']; ?></b></td>
                                        <td><?php echo $e['spend_date']; ?></td>
                                        <td><?php echo $e['name']; ?></td>
                                        <td><?php echo $e['tag']; ?></td>
                                    </tr>
                                <?php
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="5">No Expenses Found!</td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="participants">
                    <h2><i class="fa fa-users"></i> Participants</h2>
                    <ul>
                        <?php
                        if (count($users) > 0) {
                            foreach ($users as $u) {
                                $total_payments = Payment::getUserTotalPayments($u['id'], $group_id);
                                $diff = $total_payments - $group['divide'];
                        ?>
                                <li><?php echo $u['name']; ?> <b><?php echo ($diff != '') ? $diff : 0 ?> <?php echo $group['currency']; ?></b></li>
                        <?php
                            }
                        }
                        ?>
                    </ul>
                </div>
            </div>
            <br><br><br>
            <div>
                <h4><?php echo $group['currency']; ?> Payments</h4>
                <table>
                    <thead>
                        <th>User</th>
                        <th>Amount</th>
                        <th>Pay Date</th>
                        <th>Status</th>
                        <th></th>
                    </thead>
                    <tbody>
                        <?php
                        if (count($payments) > 0) {
                            foreach ($payments as $p) {
                        ?>
                                <tr>
                                    <td><?php echo $p['name']; ?></td>
                                    <td><b><?php echo $p['amount']; ?> <?php echo $group['currency']; ?></b></td>
                                    <td><?php echo $p['pay_date']; ?></td>
                                    <td><?php echo $p['is_confirmed'] == 1 ? 'Confirmed' : 'Not Confirmed'; ?></td>
                                    <td>
                                        <?php
                                            if($p['is_confirmed'] != 1){
                                                ?>
                                                    <form action="group.php?gid=<?php echo $group['id']; ?>" class="form-btn" method="post">
                                                        <input type="hidden" name="pid" value="<?php echo $p['id']; ?>">
                                                        <button type="submit" name="confirm" class="btn btn-sm">Confirm Payment</button>
                                                    </form>
                                                <?php
                                            }
                                        ?>
                                    </td>
                                </tr>
                            <?php
                            }
                        } else {
                            ?>
                            <tr>
                                <td colspan="5">No Payments Found!</td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <br>
            <hr>
            <br>
            <a class="btn btn-sm" href="edit-group.php?id=<?php echo $group['id']; ?>">Edit Group</a>
            <form action="group.php?gid=<?php echo $group['id']; ?>" class="form-btn" method="post">
                <input type="hidden" name="gid" value="<?php echo $group['id']; ?>">
                <button type="submit" name="delete" class="btn btn-sm">Delete Group</button>
            </form>
            <a class="btn btn-sm" href="add-expense.php?gid=<?php echo $group['id']; ?>">Add Expense</a>
            <a class="btn btn-sm" href="pay-group.php?gid=<?php echo $group['id']; ?>">Add Payment</a>
            <a class="btn btn-sm" href="sub-groups.php?gid=<?php echo $group['id']; ?>">Sub Groups</a>
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