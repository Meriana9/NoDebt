<?php

namespace App\Models;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require('includes/auth.php');
include('includes/alerts.php');
require('models/User.php');
require('models/Group.php');

$user_id = User::getID($_SESSION['email']);
$groups = Group::getUserGroups($user_id);
$user = new User();

if (isset($_POST['send'])) {
    $email = $_POST['email'];
    if ($_POST['group_id'] != '' && $_POST['group_id'] != '0') {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $title = 'Invitation';
            $message = 'Your are invited to join a group';

            if ($user->emailAlreadyExists($email)) {
                require('models/Invitation.php');
                Invitation::create($_POST['group_id'], $email);

                require('vendor/PHPMailer/src/PHPMailer.php');
                require('vendor/PHPMailer/src/Exception.php');

                //Create an instance; passing `true` enables exceptions
                $mail = new PHPMailer(true);

                try {
                    $mail->CharSet = 'UTF-8';
                    $mail->setFrom($email);
                    $mail->addAddress('m.alchami@student.helmo.be');
                    $mail->addAddress($email);
                    $mail->addReplyTo($email);
                    $mail->Subject = 'Invitation';
                    $mail->Body = $message;
                    if (!$mail->send()) {
                        $ALERTS[] = [
                            'type' => 'error',
                            'msg' => '<i class="fa fa-times"></i> Could not send the invitation'
                        ];
                    } else {
                        $ALERTS[] = [
                            'type' => 'success',
                            'msg' => '<i class="fa fa-check"></i> Invitation sent successfully'
                        ];
                    }
                } catch (Exception $e) {
                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }
            } else {
                $ALERTS[] = [
                    'type' => 'error',
                    'msg' => '<i class="fa fa-times"></i> This email is not valid'
                ];
            }
        } else {
            $ALERTS[] = [
                'type' => 'error',
                'msg' => '<i class="fa fa-times"></i> No group selected'
            ];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include('./includes/header.php'); ?>
    <title>Payments System | Invite user to group</title>
</head>

<body>
    <?php include('./includes/navbar.php'); ?>

    <main>
        <section id="invite-user">
            <h1><i class="fa fa-user-plus"></i> Invite user to group</h1>
            <form action="invite-user.php" method="post">
                <select name="group_id">
                    <?php
                    if (count($groups) > 0) {
                        foreach ($groups as $g) ?>
                        <option value="<?php echo $g['id']; ?>"><?php echo $g['name']; ?></option>
                    <?php
                    } else {
                    ?>
                        <option value="0">No Groups Found</option>
                    <?php
                    }
                    ?>
                </select>
                <input type="email" name="email" placeholder="E-mail to invite">
                <div class="group">
                    <button type="submit" name="send"><i class="fa fa-envelope"></i> Send Invitation</button>
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