<?php
require "../../utils/functions.php";
require "../../db/connection.php";

if($_SESSION["id_role"]!=1){
    header("Location: ../contact_requests/read.php");
}

$pdo = pdo_connect_mysql();
$msg = '';
if (isset($_GET['id'])) {
    $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$user) {
        exit('User doesn\'t exist with that ID!');
    }
    if (isset($_GET['confirm'])) {
        if ($_GET['confirm'] == 'yes') {
            $stmt = $pdo->prepare('DELETE FROM users WHERE id = ?');
            $stmt->execute([$_GET['id']]);
            $msg = 'You have deleted the user!';
        } else {
            header('Location: read.php');
            exit;
        }
    }
} else {
    exit('No ID specified!');
}
?>

<?=template_header('Delete')?>

    <div class="delete text-center">
        <h2 class="section-title">Delete user #<?=$user['id']?></h2>
        <?php if ($msg): ?>
            <p><?=$msg?></p>
            <div class="row">
                <div class="col-md-12">
                    <a href="read.php" class="custom-button button-cancel" role="button">Return</a>
                </div>
            </div>
        <?php else: ?>
            <p>Are you sure you want to delete user #<?=$user['id']?>?</p>
            <div class="yesno">
                <a href="delete.php?id=<?=$user['id']?>&confirm=yes">Yes</a>
                <a href="delete.php?id=<?=$user['id']?>&confirm=no">No</a>
            </div>
        <?php endif; ?>
    </div>

<?=template_footer()?>