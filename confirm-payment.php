<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('./includes/header.php'); ?>
    <title>Payments System | Confirm Payments</title>
</head>
<body>
    <?php include('./includes/navbar.php'); ?>
    
    <main>
        <section id="confirm-payment">
            <h1><i class="fa fa-dollar-sign"></i> Payments</h1>
            <div class="container">
                <div class="payments">
                    <table>
                        <thead>
                            <th>Description</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>User</th>
                            <th>Tags</th>
                            <th></th>
                            <th></th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Description of the first expense</td>
                                <td>150 €</td>
                                <td>15-11-2021</td>
                                <td>Username one</td>
                                <td>Trip</td>
                                <td>
                                    <button type="submit" class="btn-reject"><i class="fa fa-trash"></i> Delete</button>
                                </td>
                                <td>
                                    <button type="submit" class="btn-accept"><i class="fa fa-check"></i> Confirm</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </main>
    
    <?php include('./includes/footer.php'); ?>

</body>
</html>