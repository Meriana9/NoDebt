<?php
namespace App\Models;

require('includes/auth.php');
include('includes/alerts.php');

if (!isset($_GET['gid']) || intval($_GET['gid']) == 0) {
    header('location:index.php');
    exit();
}

require('models/Expense.php');
require('models/Group.php');

$expense_id = intval($_GET['gid']);
$group = Group::get(intval($_GET['gid']));
$group_name = $group['name'];
$group_currency = $group['currency'];

//get search filters
$filters = [
    'description' => (isset($_GET['description'])) ? $_GET['description'] : '',
    'from' => (isset($_GET['from'])) ? doubleval($_GET['from']) : 0,
    'to' => (isset($_GET['to'])) ? doubleval($_GET['to']) : 0,
    'start_date' => (isset($_GET['start_date'])) ? $_GET['start_date'] : '',
    'end_date' => (isset($_GET['end_date'])) ? $_GET['end_date'] : '',
];

$expenses = Expense::getGroupExpenses($expense_id, $filters);

if(isset($_POST['delete'])){
    Expense::delete($_POST['eid']);
    Group::updateDivide($_GET['gid']);
    $ALERTS[] = [
        'type' => 'success',
        'msg' => '<i class="fa fa-check"></i> Expense deleted successfully'
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('./includes/header.php'); ?>
    <title>Payments System | Group Expenses</title>
</head>
<body>
    <?php include('./includes/navbar.php'); ?>
    
    <main>
        <section id="expenses">
            <h1><i class="fa fa-dollar-sign"></i> Expenses for group [<?php echo $group_name; ?>]</h1>
            <br>
            <div class="container">
                <div>
                    <table>
                        <thead>
                            <th>Description</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>User</th>
                            <th>Group</th>
                            <th>Tag</th>
                            <th></th>
                            <th></th>
                        </thead>
                        <tbody>
                            <?php
                                if (count($expenses) > 0){
                                    foreach ($expenses as $e){ ?>
                                        <tr>
                                            <td><?php echo $e['description']; ?></td>
                                            <td><?php echo $e['amount']; ?> <? echo $group_currency; ?></td>
                                            <td><?php echo $e['spend_date']; ?></td>
                                            <td><?php echo $e['name']; ?></td>
                                            <td><?php echo $e['group_name']; ?></td>
                                            <td><?php echo $e['tag']; ?></td>
                                            <td>
                                                <a class="btn btn-accept" href="add-scan.php?eid=<?php echo $e['id']; ?>"><i class="fa fa-plus"></i> Add Scan</a>
                                            </td>
                                            <td>
                                                <form action="expenses.php?gid=<?php echo $_GET['gid']; ?>" method="POST" style="margin-top: 0;">
                                                    <input type="hidden" name="eid" value="<?php echo $e['id']; ?>">
                                                    <button class="btn btn-reject" name="delete" type="submit"><i class="fa fa-trash"></i> Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php }
                                }else{
                                    ?>
                                        <tr>
                                            <td colspan="9">No Expenses Found!</td>
                                        </tr>
                                    <?php
                                }
                            ?>
                            <?php
                            print_alerts();
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="search">
                    <h2><i class="fa fa-search"></i> Search</h2>
                    <form action="expenses.php" method="get">
                        <input type="hidden" name="gid" value="<?php echo $_GET['gid']; ?>">
                        <input type="text" name="description" placeholder="Description" value="<?php echo (isset($_GET['description'])) ? $_GET['description'] : ''; ?>">
                        <div class="group">
                            <input type="number" name="from" placeholder="from" value="<?php echo (isset($_GET['from'])) ? $_GET['from'] : ''; ?>">
                            <input type="number" name="to" placeholder="to" value="<?php echo (isset($_GET['to'])) ? $_GET['to'] : ''; ?>">
                        </div>
                        <input type="date" name="start_date" placeholder="Start date" value="<?php echo (isset($_GET['start_date'])) ? $_GET['start_date'] : ''; ?>">
                        <input type="date" name="end_date" placeholder="End date" value="<?php echo (isset($_GET['end_date'])) ? $_GET['end_date'] : ''; ?>">
                        <div class="group">
                            <button type="submit"><i class="fa fa-search"></i> Search</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </main>
    
    <?php include('./includes/footer.php'); ?>

</body>
</html>