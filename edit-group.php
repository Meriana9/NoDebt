<?php
    namespace App\Models;
    
    require('includes/auth.php');
    include('includes/alerts.php');
    require('models/Group.php');

    if (!isset($_GET['id']) || intval($_GET['id']) == 0){
        header('location:index.php');
        exit();
    }

    $group = Group::get(intval($_GET['id']));

    if (isset($_POST['edit'])){
        require('models/User.php');

        $name = trim($_POST['name']);
        $currency = trim($_POST['currency']);

        if ($name != '' && $currency != ''){
            Group::edit($name, $currency, intval($_GET['id']));
            $ALERTS[] = [
                'type' => 'success',
                'msg' => '<i class="fa fa-check"></i> group updated successfully'
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
    <title>Payments System | Edit group</title>
</head>
<body>
    <?php include('./includes/navbar.php'); ?>
    
    <main>
        <section id="create-group">
            <h1><i class="fa fa-users"></i> Edit group</h1>
            <form action="edit-group.php?id=<?php echo $_GET['id']; ?>" method="post">
                <input type="text" name="name" placeholder="Group name" value="<?php echo $group['name']; ?>">
                <select name="currency">
                    <option value="$" <?php ($group['currency'] == '$') ? 'selected' : '' ?>>$</option>
                    <option value="&euro;" <?php ($group['currency'] == '&euro;') ? 'selected' : '' ?>>€</option>
                </select>
                <div class="group">
                    <button type="submit" name="edit"><i class="fa fa-save"></i> Edit group</button>
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