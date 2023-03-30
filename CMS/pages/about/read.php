<?php
require "../../db/connection.php";
require "../../utils/functions.php";

if($_SESSION["id_role"]!=1){
    header("Location: ../contact_requests/read.php");
}

$pdo = pdo_connect_mysql();
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$records_per_page = 20;

$stmt = $pdo->prepare('SELECT * FROM about ORDER BY id LIMIT :current_page, :record_per_page');
$stmt->bindValue(':current_page', ($page-1)*$records_per_page, PDO::PARAM_INT);
$stmt->bindValue(':record_per_page', $records_per_page, PDO::PARAM_INT);
$stmt->execute();
$abouts = $stmt->fetchAll(PDO::FETCH_ASSOC);

$num_abouts = $pdo->query('SELECT COUNT(*) FROM about')->fetchColumn();
?>

<?=template_header('Read')?>

    <div class="section content">
        <h1 class="section-title">About</h1>
        <a href="create.php" class="custom-button button-create">Create About</a>
        <table class="table table-hover">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Status</th>
                <th scope="col">Description</th>
                <th scope="col">Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($abouts as $a): ?>
                <tr>
                    <th cope="row"><?php echo $a['id']?></th>
                    <td><?php if ($a['status'] == 1) echo "Enabled"; 
                        else echo "Disabled";?></td>
                    <td><?php echo $a['description']?></td>
                    
                    <td class="actions">
                        <a href="update.php?id=<?php echo $a['id']?>" class="edit"><i class="fa-solid fa-pen-to-square"></i></a>
                        <a href="delete.php?id=<?php echo $a['id']?>" class="trash"><i class="fa-sharp fa-solid fa-trash"></i></a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="read.php?page=<?php echo $page-1?>"><i class="fas fa-angle-double-left fa-sm"></i></a>
            <?php endif; ?>
            <?php if ($page*$records_per_page < $num_abouts): ?>
                <a href="read.php?page=<?php echo $page+1?>"><i class="fas fa-angle-double-right fa-sm"></i></a>
            <?php endif; ?>
        </div>
    </div>

<?=template_footer()?>