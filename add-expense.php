<?php
    namespace App\Models;

    require('includes/auth.php');
    include('includes/alerts.php');

    if (!isset($_GET['gid']) || intval($_GET['gid']) == 0){
        header('location:index.php');
        exit();
    }

    require('models/Group.php');
    require('models/User.php');
    
    $group_id = intval($_GET['gid']);
    $group = Group::get($group_id);
    $users = Group::getUsers($group_id);
    $current_user_id = User::getID($_SESSION['email']);

    if (isset($_POST['add'])){
        require('models/Expense.php');

        $expense = [
            'spend_date' => $_POST['date'],
            'amount' => $_POST['amount'],
            'description' => $_POST['description'],
            'user_id' => $_POST['user_id'],
            'tag' => $_POST['tag'],
            'group_id' => $group_id,
        ];

        if ($expense['spend_date'] == "" || doubleval($expense['amount']) == 0 || intval($expense['user_id']) == 0){
            $ALERTS[] = [
                'type' => 'error',
                'msg' => '<i class="fa fa-times"></i> invalid or missing data'
            ];
        }else{
            Expense::add($expense);
            Group::updateDivide($group_id);
            $ALERTS[] = [
                'type' => 'success',
                'msg' => '<i class="fa fa-check"></i> expense added successfully'
            ];
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('./includes/header.php'); ?>
    <title>Payments System | Add expense</title>
</head>
<body>
    <?php include('./includes/navbar.php'); ?>
    
    <main>
        <section id="add-expense">
            <h1><i class="fa fa-dollar-sign"></i> Add expense to group [<?php echo $group['name']; ?>]</h1>
            <form action="add-expense.php?gid=<?php echo $_GET['gid'] ?>" method="post">
                <input type="date" name="date" placeholder="Expense date">
                <input type="number" name="amount" placeholder="Expense amount">
                <textarea name="description" cols="30" rows="10" placeholder="Description"></textarea>
                <select name="user_id">
                    <?php
                        if (count($users) > 0){
                            foreach ($users as $u){?>
                                <option value="<?php echo $u['id']; ?>" <?php echo ($u['id'] == $current_user_id) ? 'selected' : ''; ?>><?php echo $u['name']; ?></option>
                            <?php }
                        }else{
                            ?>
                                <option value="0">No users found</option> 
                            <?php
                        }
                    ?>
                </select>
                <input type="text" name="tag" placeholder="Tag">
                <div class="group">
                    <button type="submit" name="add"><i class="fa fa-plus"></i> Add Expense</button>
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