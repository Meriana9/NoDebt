<?php

$ALERTS = [];

function print_alerts(){
    global $ALERTS;

    if (count($ALERTS) > 0){
        foreach($ALERTS as $alert){
            if ($alert['type'] == "error")
                echo "<div class='error-alert'>" . $alert['msg'] . "</div>";
            else
                echo "<div class='success-alert'>" . $alert['msg'] . "</div>";
        }
    }
}

?>