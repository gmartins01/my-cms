<?php
require "../../db/connection.php";
require "../../utils/functions.php";

if($_SESSION["id_role"]!=1){
    header("Location: ../contact_requests/read.php");
}

$pdo = pdo_connect_mysql();
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$records_per_page = 20;

$stmt = $pdo->prepare('SELECT * FROM languages ORDER BY id LIMIT :current_page, :record_per_page');
$stmt->bindValue(':current_page', ($page-1)*$records_per_page, PDO::PARAM_INT);
$stmt->bindValue(':record_per_page', $records_per_page, PDO::PARAM_INT);
$stmt->execute();
$languages = $stmt->fetchAll(PDO::FETCH_ASSOC);

$num_languages = $pdo->query('SELECT COUNT(*) FROM languages')->fetchColumn();
?>

<?=template_header('Read')?>

    <div class="section content">
        <h1 class="section-title">Languages</h1>
        <a href="create.php" class="custom-button button-create">Create Language Text</a>
        <table class="table table-hover">
            <thead>
            <tr>
                <td>#</td>
                <td>Status</td>
                <td>Description</td>
                <td></td>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($languages as $lang): ?>
                <tr>
                    <td><?php echo $lang['id']?></td>
                    <td><?php if ($lang['status'] == 1) echo "Enabled"; 
                        else echo "Disabled";?></td>
                    <td><?php echo $lang['description']?></td>
                    <td class="actions">
                        <a href="update.php?id=<?php echo $lang['id']?>" class="edit"><i class="fa-solid fa-pen-to-square"></i></a>
                        <a href="delete.php?id=<?php echo $lang['id']?>" class="trash"><i class="fa-sharp fa-solid fa-trash"></i></a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="read.php?page=<?php echo $page-1?>"><i class="fas fa-angle-double-left fa-sm"></i></a>
            <?php endif; ?>
            <?php if ($page*$records_per_page < $num_languages): ?>
                <a href="read.php?page=<?php echo $page+1?>"><i class="fas fa-angle-double-right fa-sm"></i></a>
            <?php endif; ?>
        </div>
    </div>

<?=template_footer()?>