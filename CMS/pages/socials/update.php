<?php
require "../../utils/functions.php";
require "../../db/connection.php";

if($_SESSION["id_role"]!=1){
    header("Location: ../contact_requests/read.php");
}

$pdo = pdo_connect_mysql();
$success_msg = $msg = '';
$name = $icon = $url= $status = "";
$name_err= $icon_err= $url_err = "";

if (isset($_GET['id'])) {
    if (!empty($_POST)) {

        if(empty(trim($_POST["name"]))){
        $name_err = "Please fill the name.";
        }
        else{
            $name = trim($_POST["name"]);
        }

        if(empty(trim($_POST["icon"]))){
            $icon_err = "Please fill the icon.";
        }
        else{
            $icon = trim($_POST["icon"]);
        }

        if(empty(trim($_POST["url"]))){
            $url_err = "Please fill the url.";
        }
        else{
            $url = trim($_POST["url"]);
        }

        $status = trim($_POST["status"]);

    if(empty($name_err) && empty($icon_err) && empty($url_err)){
            $stmt = $pdo->prepare('UPDATE socials SET name = ?, icon = ?, url = ?,status=?,id_me = ? WHERE id = ?');
            $stmt->execute([$name,$icon,$url, $status,1,$_GET['id']]);
            $success_msg = 'Updated Successfully!';
        }else{
            $msg = 'Failed to update!';
        }
    }
    $stmt = $pdo->prepare('SELECT * FROM socials WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    $social = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$social) {
        exit('Social with that ID doesn\'t exist!');
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
                    <h1 class="section-title">Update Social</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="section">
        <form action="update.php?id=<?php echo $social['id']?>" method="POST" enctype="multipart/form-data">
            <div class="container">
                <div class="form-group">
                    <label for="name" class="main-text">Name</label>
                    <input name="name" id="name" value="<?php echo $social['name']?>"
                        class="form-control form-control-lg <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>">
                    <span class="invalid-feedback"><?php echo $name_err; ?></span>
                    
                    <label for="icon" class="main-text">Icon</label>
                    <input name="icon" id="icon" value="<?php echo $social['icon']?>"
                        class="form-control form-control-lg <?php echo (!empty($icon_err)) ? 'is-invalid' : ''; ?>">
                    <span class="invalid-feedback"><?php echo $icon_err; ?></span>
                
                    <label for="url" class="main-text">Url</label>
                    <input name="url" id="url" value="<?php echo $social['url']?>"
                        class="form-control form-control-lg <?php echo (!empty($url_err)) ? 'is-invalid' : ''; ?>">
                    <span class="invalid-feedback"><?php echo $url_err; ?></span>
                
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-select form-select-lg">
                        <option value="1" <?php if($social['status']==1) echo 'selected="selected"'; ?> >Enabled</option>
                        <option value="2" <?php if($social['status']==2) echo 'selected="selected"'; ?> >Disabled</option>
                            
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