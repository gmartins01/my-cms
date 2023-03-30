<?php
require "../../utils/functions.php";
require "../../db/connection.php";

if($_SESSION["id_role"]!=1){
    header("Location: ../contact_requests/read.php");
}

$pdo = pdo_connect_mysql();
$msg = '';
if (isset($_GET['id'])) {
    $stmt = $pdo->prepare('SELECT * FROM languages WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    $languages = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$languages) {
        exit('Language with that ID doesn\'t exist !');
    }
    if (isset($_GET['confirm'])) {
        if ($_GET['confirm'] == 'yes') {
            $stmt = $pdo->prepare('DELETE FROM languages WHERE id = ?');
            $stmt->execute([$_GET['id']]);
            $msg = 'You have deleted the language text!';
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
        <h2 class="section-title">Delete language text #<?=$languages['id']?></h2>
        <?php if ($msg): ?>
            <p><?=$msg?></p>
            <div class="row">
                <div class="col-md-12">
                    <a href="read.php" class="custom-button button-cancel" role="button">Return</a>
                </div>
            </div>
        <?php else: ?>
            <p>Are you sure you want to delete language text #<?=$languages['id']?>?</p>
            <div class="yesno">
                <a href="delete.php?id=<?=$languages['id']?>&confirm=yes">Yes</a>
                <a href="delete.php?id=<?=$languages['id']?>&confirm=no">No</a>
            </div>
        <?php endif; ?>
    </div>

<?=template_footer()?>