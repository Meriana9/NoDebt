<?php
namespace App\Models;

require('includes/auth.php');
include('includes/alerts.php');

if (!isset($_GET['eid']) || intval($_GET['eid']) == 0) {
    header('location:index.php');
    exit();
}

require('models/User.php');
require('models/Expense.php');

$expense_id = intval($_GET['eid']);
$current_user_id = User::getID($_SESSION['email']);
$expense = Expense::get($expense_id);

if (isset($_POST['add'])) {
    require('models/Scan.php');

    if (!isset($_FILES["scan"])) {
        $ALERTS[] = [
            'type' => 'error',
            'msg' => '<i class="fa fa-times"></i> invalid scan file'
        ];
        return;
    }

    $file_name = date("Ymddhs"). '_' . $_FILES['scan']['name'];
    $file_tmp = $_FILES['scan']['tmp_name'];
    move_uploaded_file($file_tmp, "./uploads/" . $file_name);

    Scan::add($file_name, $expense_id);
    $ALERTS[] = [
        'type' => 'success',
        'msg' => '<i class="fa fa-check"></i> scan added successfully'
    ];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include('./includes/header.php'); ?>
    <title>Payments System | Add Scan</title>
</head>

<body>
    <?php include('./includes/navbar.php'); ?>

    <main>
        <section id="add-scan">
            <h1><i class="fa fa-file"></i> Add scan to expense</h1>
            <div class="container">
                <div>
                    <table>
                        <thead>
                            <th>Description</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>User</th>
                            <th>Tag</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?php echo $expense['description']; ?></td>
                                <td><?php echo $expense['amount']; ?> €</td>
                                <td><?php echo $expense['spend_date']; ?></td>
                                <td><?php echo $expense['name']; ?></td>
                                <td><?php echo $expense['tag']; ?></td>
                            </tr>
                        </tbody>
                    </table>

                    <form action="add-scan.php?eid=<?php echo $_GET['eid']; ?>" method="post" enctype="multipart/form-data">
                        <input type="file" name="scan">
                        <div class="group">
                            <button type="submit" name="add"><i class="fa fa-upload"></i> Upload Scan</button>
                        </div>

                        <?php
                            print_alerts();
                        ?>

                    </form>
                </div>
            </div>
        </section>
    </main>

    <?php include('./includes/footer.php'); ?>

</body>

</html>