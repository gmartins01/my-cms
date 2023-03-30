<?php
require "../../utils/functions.php";
require "../../db/connection.php";

if($_SESSION["id_role"]!=1){
    header("Location: ../contact_requests/read.php");
}

$pdo = pdo_connect_mysql();
$success_msg = $msg = '';
$description = "";
$description_err = "";

if (isset($_GET['id'])) {
    if (!empty($_POST)) {
        
        $description = $_POST['description'];

        if(empty(trim($_POST["description"]))){
            $description_err = "Please fill the description.";
        }
        else{
            $description = trim($_POST["description"]);
        }

        $status = trim($_POST["status"]);

        if(empty($description_err)){

            $stmt = $pdo->prepare('UPDATE languages SET description = ?, status=?,id_me = ? WHERE id = ?');
            $stmt->execute([$description, $status,1,$_GET['id']]);
            $success_msg  = 'Updated Successfully!';
        }else{
            $msg = 'Failed to update!';
        }
    }
    $stmt = $pdo->prepare('SELECT * FROM languages WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    $languages = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$languages) {
        exit('Languages text with that ID doesn\'t exist!');
    }
} else {
    exit('No ID specified!');
}
?>

<?=template_header('Update')?>

    <div class="section">
        <div class="container text-center">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="section-title">Update Language Text</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="section">
        <form action="update.php?id=<?php echo $languages['id']?>" method="POST" enctype="multipart/form-data">
            <div class="container">
                <div class="form-group">
                    <label for="description" class="main-text">Description</label>
                    <textarea name="description" id="description" cols="30" rows="10" 
                        class="form-control form-control-lg <?php echo (!empty($description_err)) ? 'is-invalid' : ''; ?>"><?php echo $languages['description']?></textarea>
                    <span class="invalid-feedback"><?php echo $description_err; ?></span>
                
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-select form-select-lg">
                        <option value="1" <?php if($languages['status']==1) echo 'selected="selected"'; ?> >Enabled</option>
                        <option value="2" <?php if($languages['status']==2) echo 'selected="selected"'; ?> >Disabled</option>   
                    </select>
                </div>

                <div class="row text-center">
                    <div class="col-md-6">
                        <a href="read.php" class="custom-button button-cancel" role="button">Return</a>
                    </div>
                    <div class="col-md-6">
                        <input type="submit" value="Update" class="custom-button button-update">
                    </div>
                </div>
            </div>
        </form>
    </div>

    <?php if ($success_msg): ?>
            <div class="container">
                <p class="text-center alert-success"><?php echo $success_msg ?></p>
            </div>
    <?php endif; ?>

    <?php if ($msg): ?>
            <div class="container">
                <p class="text-center alert-fail"><?php echo $msg ?></p>
            </div>
    <?php endif; ?>

<?=template_footer()?>