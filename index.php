<?php
namespace App\Models;

require('includes/auth.php');
require('includes/alerts.php');
require('models/Expense.php');
require('models/Payment.php');
require('models/Group.php');
require('models/User.php');
require('models/Invitation.php');

$user_id = User::getID($_SESSION['email']);
$groups = Group::getUserGroups($user_id);
$invitations = Invitation::getUserInvitations($_SESSION['email']);

if (isset($_POST['reject'])){
    Invitation::reject($_POST['invitation_id']);
    $ALERTS[] = [
        'type' => 'error',
        'msg' => '<i class="fa fa-times"></i> Invitation rejected'
    ];
}

if (isset($_POST['accept'])){
    Invitation::accept($_POST['invitation_id']);
    $ALERTS[] = [
        'type' => 'success',
        'msg' => '<i class="fa fa-times"></i> Invitation accepted successfully'
    ];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('./includes/header.php'); ?>
    <title>Payments System | Groups List</title>
</head>
<body>
    <?php include('./includes/navbar.php'); ?>
    
    <main>
        <section id="groups-list">
            <h1><i class="fa fa-users"></i> Groups List</h1>
            <div class="container">
                <div>
                    <?php
                        if (count($groups) > 0){
                            foreach ($groups as $g){?>
                                <div class="group-data">
                                    <h3><a href="group.php?gid=<?php echo $g['id']; ?>"><?php echo $g['name']; ?></a></h3>
                                    <div class="data">
                                        <table>
                                            <thead>
                                                <th>Description</th>
                                                <th>Amount</th>
                                                <th>Date</th>
                                                <th>User</th>
                                                <th>Tag</th>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $latest_expenses = Expense::getGroupLatestExpenses($g['id']);
                                                    if (count($latest_expenses) > 0){
                                                        foreach ($latest_expenses as $le){
                                                            ?>
                                                                <tr>
                                                                    <td><?php echo $le['description']; ?></td>
                                                                    <td><b><?php echo $le['amount']; ?> <?php echo $g['currency']; ?></b></td>
                                                                    <td><?php echo $le['spend_date']; ?></td>
                                                                    <td><?php echo $le['name']; ?></td>
                                                                    <td><?php echo $le['tag']; ?></td>
                                                                </tr>
                                                            <?php 
                                                        }
                                                    }else{
                                                        ?>
                                                            <tr>
                                                                <td colspan="5">No Expenses Found!</td>
                                                            </tr>
                                                        <?php
                                                    }
                                                ?>
                                            </tbody>
                                        </table>
                                        <div>
                                            <hr>
                                            <br>
                                            <a class="btn btn-sm" href="expenses.php?gid=<?php echo $g['id']; ?>">Group Expenses</a>
                                            <a class="btn btn-sm" href="add-expense.php?gid=<?php echo $g['id']; ?>">Add Expense</a>
                                            <br>
                                        </div>
                                        <br>
                                        <h4><i class="fa fa-users"></i> Participants</h4>
                                        <table class="participants-table">
                                            <tbody>
                                                <?php
                                                    $group_users = Group::getUsers($g['id']);
                                                    if (count($group_users) > 0){
                                                        foreach ($group_users as $gu){
                                                            $total_expenses = Expense::getUserTotalExpenses($gu['id'], $g['id']);
                                                            $total_payments = Payment::getUserTotalPayments($gu['id'], $g['id']);
                                                            $diff = $total_payments - $g['divide'];
                                                            ?>
                                                                <tr>
                                                                    <td><?php echo $gu['name']; ?></td>
                                                                    <td>Total Expenses: <b><?php echo ($total_expenses != '') ? $total_expenses : 0 ?> <?php echo $g['currency']; ?></b></td>
                                                                    <td>Difference: <b><?php echo $diff ?> <?php echo $g['currency']; ?></b></td>
                                                                </tr>
                                                            <?php
                                                        }
                                                    }else{
                                                        ?>
                                                            <tr>
                                                                <td colspan="3">No Users Found!</td>
                                                            </tr>
                                                        <?php
                                                    }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            <?php 
                            }
                        }else{
                            echo "<p>No Groups Found</p>";
                        }
                    ?>
                </div>
                <div class="invitations">
                    <h2><i class="fa fa-user-plus"></i> Invitations List</h2>
                    <ul>
                        <?php
                        
                            if (count($invitations) > 0){
                                foreach ($invitations as $i){
                                    ?>
                                        <li>
                                            you are invited to join <b><?php echo $i['name']; ?></b>
                                            <form action="index.php" method="post">
                                                <input type="hidden" name="invitation_id" value="<?php echo $i['i_id']; ?>">
                                                <button type="submit" class="btn-reject" name="reject"><i class="fa fa-times"></i> Reject</button>
                                            </form>
                                            <form action="index.php" method="post">
                                                <input type="hidden" name="invitation_id" value="<?php echo $i['i_id']; ?>">
                                                <button type="submit" class="btn-accept" name="accept"><i class="fa fa-check"></i> Accept</button>
                                            </form>
                                        </li>
                                    <?php
                                }
                            }else{
                                ?>
                                    <li>No invitations yet!</li>
                                <?php
                            }
                    
                        ?>
                    </ul>
                    <div>
                        <?php
                            print_alerts();
                        ?>
                    </div>
                </div>
            </div>
        </section>
    </main>
    
    <?php include('./includes/footer.php'); ?>

</body>
</html>