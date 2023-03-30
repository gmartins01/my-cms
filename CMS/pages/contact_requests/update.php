<?php
    require "../../db/connection.php";
    require "../../utils/functions.php";

    $pdo = pdo_connect_mysql();
    if (isset($_GET['id'])) {
        $stmt = $pdo->prepare('UPDATE contact_requests SET is_read = IF(is_read=1, 0, 1) WHERE id = ?');
        $stmt->execute([$_GET['id']]);

        header('Location: read.php');
        exit;
    }else{
        header('Location: read.php');
        exit;
    }

?>
