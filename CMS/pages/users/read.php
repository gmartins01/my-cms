<?php
require "../../db/connection.php";
require "../../utils/functions.php";

if($_SESSION["id_role"]!=1){
    header("Location: ../contact_requests/read.php");
}

$pdo = pdo_connect_mysql();
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$records_per_page = 20;

$stmt = $pdo->prepare('SELECT * FROM users ORDER BY id LIMIT :current_page, :record_per_page');
$stmt->bindValue(':current_page', ($page-1)*$records_per_page, PDO::PARAM_INT);
$stmt->bindValue(':record_per_page', $records_per_page, PDO::PARAM_INT);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

$num_users = $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
?>

<?=template_header('Read')?>


<div class="section content">
        <h1 class="section-title">Users</h1>
        <a href="create.php" class="custom-button button-create">Create User</a>
        <table class="table table-hover">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Username</th>
                <th scope="col">Role</th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($users as $u): ?>
                <tr>
                    <th><?php echo $u['id']?></th>
                    <td><?php echo $u['name']?></td>
                    <td><?php echo $u['username']?></td>
                    <td><?php if ($u['id_role'] == 1) echo "Admin"; 
                        elseif ($u['id_role'] == 2) echo "Manager";
                        else echo "Unauthorized "?></td>
                    <td class="actions">
                        <a href="update.php?id=<?php echo $u['id']?>" class="edit"><i class="fa-solid fa-pen-to-square"></i></a>
                        <a href="delete.php?id=<?php echo $u['id']?>" class="trash"><i class="fa-sharp fa-solid fa-trash"></i></a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="read.php?page=<?php echo $page-1?>"><i class="fas fa-angle-double-left fa-sm"></i></a>
            <?php endif; ?>
            <?php if ($page*$records_per_page < $num_users): ?>
                <a href="read.php?page=<?php echo $page+1?>"><i class="fas fa-angle-double-right fa-sm"></i></a>
            <?php endif; ?>
        </div>
    </div>



<?=template_footer()?>