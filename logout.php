<?php
if(isset($_POST['logout'])) {
    session_save_path("tmp");
    session_start();
    session_destroy();
    $_SESSION = array();
    header('Location: /');
}
?>