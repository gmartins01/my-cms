<?php
require "../../utils/functions.php";
require "../../db/connection.php";

if($_SESSION["id_role"]!=1){
    header("Location: ../contact_requests/read.php");
}

$pdo = pdo_connect_mysql();
$success_msg = $msg = '';
$name = $image =  $id_skill_type ='';
$name_err = $image_err = $id_skill_type_err= '';

$stmt = $pdo->prepare('SELECT * FROM skills WHERE id = ?');
$stmt->execute([$_GET['id']]);
$skill = $stmt->fetch(PDO::FETCH_ASSOC);

if (isset($_GET['id'])) {
    if (!empty($_POST)) {

        if(empty(trim($_POST["name"]))){
            $name_err = "Please fill the name.";
        }
        else{
            $name = trim($_POST["name"]);
        }
        
        if(isset($_POST["id_skill_type"]) && $_POST["id_skill_type"]=='Select a skill type'){
            $id_skill_type_err = "Please select a skill type.";
        }    
        else{
            $id_skill_type = trim($_POST["id_skill_type"]);
        }
        
        $status = trim($_POST["status"]);

        $target_dir = "../../uploaded_files/";
        $image_name = basename($_FILES["image"]["name"]);
        
        $imageFileType = strtolower(pathinfo($image_name,PATHINFO_EXTENSION));

        if ($_FILES["image"]["size"] > 2500000 && !empty($_FILES['image']['name'])) {
            $image_err = "Sorry, the max file size is 2.5 MB.";
        } elseif($imageFileType != "jpg" && $imageFileType != "png" 
            && $imageFileType != "jpeg" && !empty($_FILES['image']['name'])) {
            $image_err = 'Sorry, only JPG, JPEG and PNG files are allowed.';
        }

        $i = 1;
        while (file_exists($target_dir . $image_name)) {
            $image_name = basename($_FILES["image"]["name"], "." . $imageFileType) . "($i)." . $imageFileType;
            $i++;
        } 
        
        $target_file = $target_dir . $image_name;

        if(empty($name_err) && empty($image_err) && empty($id_skill_type_err)){
            if (empty($_FILES['image']['name'])){
                $target_file = $skill['image'];
            }else if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                unlink($skill['image']);
            }
            $stmt = $pdo->prepare('UPDATE skills SET name = ?,image = ?,id_skill_type = ?,status = ?,id_me = ? WHERE id = ?');
            $stmt->execute([$name,$target_file,$id_skill_type,$status,1,$_GET['id']]);
            $success_msg = 'Skill updated successfully!';
            
        }else{
            $msg = 'Failed to update skill!';
        }
    }
    
    $stmt = $pdo->prepare('SELECT * FROM skills WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    $skill = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$skill) {
        exit('Skill with that ID doesn\'t exist!');
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
                    <h1 class="section-title">Update Skill</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="section">
        <form action="update.php?id=<?php echo $skill['id']?>" method="POST" enctype="multipart/form-data">
            <div class="container">
            
                <div class="form-group">

                    <label for="name" class="main-text">Name</label>
                    <input name="name" id="name" value="<?php echo $skill['name']; ?>"
                        class="form-control form-control-lg <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>">
                    <span class="invalid-feedback"><?php echo $name_err; ?></span>
                    
                    <label for="image" class="main-text">Skill Logo</label>
                    <input class="form-control <?php echo (!empty($image_err)) ? 'is-invalid' : ''; ?>" type="file" 
                        value="<?php echo $skill['image']; ?>" name="image" onchange="preview()" />
                    <span class="invalid-feedback"><?php echo $image_err; ?></span>

                    <div class="row text-center">
                        <div class="col-md-12">
                            <img name="image" id="image" src="<?php echo $skill['image']?>" class="img-responsive">
                        </div>
                    </div>

                    <label for="id_skill_type" class="main-text">Skill Type</label>
                    <select name="id_skill_type" id="id_skill_type" class="form-select form-select-lg
                        <?php echo (!empty($id_skill_type_err)) ? 'is-invalid' : ''; ?>">
                        <option value="1" <?php if($skill['id_skill_type']==1) echo 'selected="selected"'; ?> >Programming Languages & Tools</option>
                        <option value="2" <?php if($skill['id_skill_type']==2) echo 'selected="selected"'; ?> >Frameworks</option>
                    </select>
                    <span class="invalid-feedback"><?php echo $id_skill_type_err; ?></span>

                    <label for="status" class="main-text">Status</label>
                    <select name="status" id="status" class="form-select form-select-lg">
                        <option value="1" <?php if($skill['status']==1) echo 'selected="selected"'; ?> >Enabled</option>
                        <option value="2" <?php if($skill['status']==2) echo 'selected="selected"'; ?> >Disabled</option>   
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
