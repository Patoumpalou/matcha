<?php

include('../model/inc.php');
update_conns($conn, $_SESSION['id'], date("Y-m-d H:i:s"), '0');
session_destroy();

?>