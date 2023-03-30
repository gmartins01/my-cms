<?php
require "../../utils/functions.php";
require "../../db/connection.php";

if($_SESSION["id_role"]!=1){
    header("Location: ../contact_requests/read.php");
}

$pdo = pdo_connect_mysql();
$msg = '';
$success_msg = '';
$name = $profession = "";
$name_err = $profession_err = $image_err = "";

$stmt = $pdo->prepare('SELECT * FROM me WHERE id = ?');
$stmt->execute([$_GET['id']]);
$me = $stmt->fetch(PDO::FETCH_ASSOC);

if (isset($_GET['id'])) {
    
    if (!empty($_POST)) {
        
        if(empty(trim($_POST["name"]))){
            $name_err = "Please fill the name.";
        }
        else{
            $name = trim($_POST["name"]);
        }

        if(empty(trim($_POST["profession"]))){
            $profession_err = "Please fill the profession.";
        }
        else{
            $profession = trim($_POST["profession"]);
        }
        
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

        
        if(empty($name_err) && empty($profession_err) && empty($image_err)){
            
            if (empty($_FILES['image']['name'])){
                $target_file = $me['image'];
            }else if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                unlink($me['image']);
            }
            $stmt = $pdo->prepare('UPDATE me SET name = ?, profession = ?, image = ? WHERE id = ?');
            $stmt->execute([$name, $profession, $target_file, $_GET['id']]);
            $me['image'] = $target_file;
            $success_msg = 'Profile updated successfully!';
        }
        else{
            $msg = 'Failed to update profile!';
        }
        
    }
    $stmt = $pdo->prepare('SELECT * FROM me WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    $me = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$me) {
        exit('Profile with that ID doesn\'t exist!');
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
                    <h1 class="section-title">Update profile</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="section" id="me">
        <form action="update.php?id=<?php echo $me['id']?>" method="POST" enctype="multipart/form-data">
            <div class="container">
                <div class="row text-center">
                    <div class="col-md-12">
                        <img id="image" src="<?php echo $me['image']?>" class="img-responsive" alt="Photo of <?php echo $me["name"] ?>">
                    </div>
                </div>
                
                    <div class="form-group">
                    <label for="name" class="main-text">Name</label>
                    <input type="text" name="name" id="name" value="<?php echo $me['name']?>"
                        class="form-control form-control-lg <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>">
                    <span class="invalid-feedback"><?php echo $name_err; ?></span>
        
                    <label for="profession" class="main-text">Profession</label>
                    <input type="text" name="profession" id="profession" value="<?php echo $me['profession']?>"
                        class="form-control form-control-lg <?php echo (!empty($profession_err)) ? 'is-invalid' : ''; ?>"/>
                    <span class="invalid-feedback"><?php echo $profession_err; ?></span>

                    <label for="image" class="main-text">Image</label>
                    <input class="form-control" type="file" name="image" id="image" onchange="preview()" />
                    <span class="invalid-feedback"><?php echo $image_err; ?></span>

                    <div class="row text-center">
                        <div class="col-md-6">
                            <a href="read.php" class="custom-button button-cancel" role="button">Return</a>
                        </div>
                        <div class="col-md-6">
                            <input type="submit" id="update" value="Update" class="custom-button button-update">
                        </div>
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
