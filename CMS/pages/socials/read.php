<?php
require "../../db/connection.php";
require "../../utils/functions.php";

if($_SESSION["id_role"]!=1){
    header("Location: ../contact_requests/read.php");
}

$pdo = pdo_connect_mysql();
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$records_per_page = 20;

$stmt = $pdo->prepare('SELECT * FROM socials ORDER BY id LIMIT :current_page, :record_per_page');
$stmt->bindValue(':current_page', ($page-1)*$records_per_page, PDO::PARAM_INT);
$stmt->bindValue(':record_per_page', $records_per_page, PDO::PARAM_INT);
$stmt->execute();
$socials = $stmt->fetchAll(PDO::FETCH_ASSOC);

$num_socials = $pdo->query('SELECT COUNT(*) FROM socials')->fetchColumn();
?>

<?=template_header('Read')?>

    <div class="section content">
        <h1 class="section-title">Socials</h1>
        <a href="create.php" class="custom-button button-create">Create Social</a>
        <table class="table table-hover">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Status</th>
                <th scope="col">Name</th>
                <th scope="col">Icon</th>
                <th scope="col">Url</th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($socials as $s): ?>
                <tr class="table-content">
                    <th><?php echo $s['id']?></th>
                    <td><?php if ($s['status'] == 1) echo "Enabled"; 
                        else echo "Disabled";?></td>
                    <td><?php echo $s['name']?></td>
                    <td><i class="<?php echo $s['icon']?>"></i></td>
                    <td><?php echo $s['url']?></td>
                    <td class="actions">
                        <a href="update.php?id=<?php echo $s['id']?>" class="edit"><i class="fa-solid fa-pen-to-square"></i></a>
                        <a href="delete.php?id=<?php echo $s['id']?>" class="trash"><i class="fa-sharp fa-solid fa-trash"></i></a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="read.php?page=<?php echo $page-1?>"><i class="fas fa-angle-double-left fa-sm"></i></a>
            <?php endif; ?>
            <?php if ($page*$records_per_page < $num_socials): ?>
                <a href="read.php?page=<?php echo $page+1?>"><i class="fas fa-angle-double-right fa-sm"></i></a>
            <?php endif; ?>
        </div>
    </div>

<?=template_footer()?>