<?php
require "../../utils/functions.php";
require "../../db/connection.php";

$pdo = pdo_connect_mysql();
$msg = '';
if (isset($_GET['id'])) {
    $stmt = $pdo->prepare('SELECT * FROM contact_requests WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    $contact = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$contact) {
        exit('Contact request with that ID doesn\'t exist !');
    }
    if (isset($_GET['confirm'])) {
        if ($_GET['confirm'] == 'yes') {
            $stmt = $pdo->prepare('DELETE FROM contact_requests WHERE id = ?');
            $stmt->execute([$_GET['id']]);
            $msg = 'You have deleted the contact request !';
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
        <h2 class="section-title">Delete contact request ?</h2>
        <?php if ($msg): ?>
            <p><?=$msg?></p>
            <div class="row">
                <div class="col-md-12">
                    <a href="read.php" class="custom-button button-cancel" role="button">Return</a>
                </div>
            </div>
        <?php else: ?>
            <p>Are you sure you want to delete the contact request?</p>
            <div class="yesno">
                <a href="delete.php?id=<?php echo $contact['id']?>&confirm=yes">Yes</a>
                <a href="delete.php?id=<?php echo $contact['id']?>&confirm=no">No</a>
            </div>
        <?php endif; ?>
    </div>

<?=template_footer()?>