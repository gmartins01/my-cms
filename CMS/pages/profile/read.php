<?php
require "../../utils/functions.php";
require "../../db/connection.php";

if($_SESSION["id_role"]!=1){
    header("Location: ../contact_requests/read.php");
}

// Connect to MySQL database
$pdo = pdo_connect_mysql();

$stmt = $pdo->prepare('SELECT * FROM me WHERE id = 1');
$stmt->execute();
$me = $stmt->fetch(PDO::FETCH_ASSOC);

?>

<?=template_header('Read')?>

    <div class="section">
        <div class="container text-center">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="section-title">My profile</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="section" id="me">
        <div class="container text-center">
            <div class="row">
                <div class="col-md-12">
                    <img src="<?php echo $me["image"] ?>" class="img-responsive" alt="Photo of <?php echo $me["name"] ?>">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <h1 class="section-subtitle">Name: <?php echo $me["name"] ?></h1>
                    
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <h1 class="section-subtitle">Profession: <?php echo $me["profession"]?></h1>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                <a href="update.php?id=<?php echo $me['id']?>" class="custom-button button-update" role="button">Update</a>
                </div>
            </div>
        </div>
    </div>

<?=template_footer()?>