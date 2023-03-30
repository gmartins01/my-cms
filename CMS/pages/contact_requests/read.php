<?php
require "../../db/connection.php";
require "../../utils/functions.php";

$pdo = pdo_connect_mysql();
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$records_per_page = 20;

$stmt = $pdo->prepare('SELECT * FROM contact_requests ORDER BY id LIMIT :current_page, :record_per_page');
$stmt->bindValue(':current_page', ($page-1)*$records_per_page, PDO::PARAM_INT);
$stmt->bindValue(':record_per_page', $records_per_page, PDO::PARAM_INT);
$stmt->execute();
$contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);

$num_contacts = $pdo->query('SELECT COUNT(*) FROM contact_requests')->fetchColumn();
?>

<?=template_header('Read')?>

    <div class="section content">
        <h1 class="section-title">Contact Requests</h1>
        
        <table class="table table-hover">
            <thead>
            <tr>
                <th scope="col">Status</th>
                <th scope="col">From</th>
                <th scope="col">Subject</th>
                <th scope="col">Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($contacts as $c): ?>
                <tr>
                    <td><?php if ($c['is_read'] == 1) echo "Read"; 
                        else echo "Unread";?></td>
                    <td><?php echo $c['email']?></td>
                    <td><?php echo $c['subject']?></td>
                    <td class="actions">
                        <a href="reply.php?id=<?php echo $c['id']?>" class="edit"><i class="fa-solid fa-envelope"></i></a>
                        <a href="update.php?id=<?php echo $c['id']?>" class="edit"><i class="fa-solid fa-eye"></i></a>
                        <a href="delete.php?id=<?php echo $c['id']?>" class="trash"><i class="fa-sharp fa-solid fa-trash"></i></a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="read.php?page=<?php echo $page-1?>"><i class="fas fa-angle-double-left fa-sm"></i></a>
            <?php endif; ?>
            <?php if ($page*$records_per_page < $num_contacts): ?>
                <a href="read.php?page=<?php echo $page+1?>"><i class="fas fa-angle-double-right fa-sm"></i></a>
            <?php endif; ?>
        </div>
    </div>

<?=template_footer()?>