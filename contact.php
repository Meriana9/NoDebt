<?php

namespace PHPMailer\PHPMailer;

require('includes/auth.php');
include('includes/alerts.php');

if (isset($_POST["contact"])) {
    $email = $_POST["email"];
    $title = $_POST["title"];
    $message = $_POST["message"];

    $is_data_valid = true;

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $is_data_valid = false;
        $ALERTS[] = [
            'type' => 'error',
            'msg' => '<i class="fa fa-times"></i> Email is not valid'
        ];
    }

    if ($title == "" || $message == "") {
        $is_data_valid = false;
        $ALERTS[] = [
            'type' => 'error',
            'msg' => '<i class="fa fa-times"></i> Title or message can not be empty'
        ];
    }

    if ($is_data_valid) {

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
                    'msg' => '<i class="fa fa-times"></i> Could not send the message'
                ];
            } else {
                $ALERTS[] = [
                    'type' => 'success',
                    'msg' => '<i class="fa fa-check"></i> Message sent successfully'
                ];
            }
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include('./includes/header.php'); ?>
    <title>Payments System | Contact Us</title>
</head>

<body>
    <?php include('./includes/navbar.php'); ?>

    <main>
        <section id="contact">
            <h1>Contact Us</h1>
            <form action="contact.php" method="post">
                <input type="email" name="email" placeholder="E-mail" required value="<?php echo ($_SESSION['email'] != '') ? $_SESSION['email'] : ''; ?>">
                <input type="text" name="title" placeholder="Title" required>
                <textarea name="message" cols="30" rows="10" placeholder="Your message..." required></textarea>
                <div class="group">
                    <button type="submit" name="contact"><i class="fa fa-envelope"></i> Send Message</button>
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