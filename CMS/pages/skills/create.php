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

if($_SERVER["REQUEST_METHOD"] == "POST"){

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

    $target_dir = "../../uploaded_files/";
    $image_name = basename($_FILES["image"]["name"]);

    if (empty($image_name)){
        $image_err = "Please select a image";
    }elseif ($_FILES["image"]["size"] > 2500000) {
        $image_err = "Sorry, the max file size is 2.5 MB.";
    } else{
        $imageFileType = strtolower(pathinfo($image_name,PATHINFO_EXTENSION));

        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            $image_err = 'Sorry, only JPG, JPEG and PNG files are allowed.';
        }

        $i = 1;
        while (file_exists($target_dir . $image_name)) {
            $image_name = basename($_FILES["image"]["name"], "." . $imageFileType) . "($i)." . $imageFileType;
            $i++;
        } 
        
        $target_file = $target_dir . $image_name;
    }

    if(empty($name_err) && empty($image_err) && empty($id_skill_type_err)){
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)){

            $sql = "INSERT INTO skills (name, image,id_skill_type ,id_me) VALUES (:name,:image,:id_skill_type, 1)";

            if($stmt = $pdo->prepare($sql)){
                $stmt->bindParam(":name", $param_name);
                $stmt->bindParam(":image", $param_image);
                $stmt->bindParam(":id_skill_type", $param_id_skill_type);

                $param_name = $name;
                $param_image = $target_file;
                $param_id_skill_type = $id_skill_type;

                if($stmt->execute()){
                    header("location: read.php");
                    $success_msg = 'Created Successfully!';
                }
                else{
                    $msg = "Ups! Try again please.";
                }

                unset($stmt);
            }else{
                $msg = "Failed to create.";
            }
        }
    }

    

    unset($pdo);
}
?>

<?=template_header('Create')?>

    <div class="section">
        <div class="container text-center">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="section-title">Create Skill</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="section">
        <form action="create.php" method="POST" enctype="multipart/form-data">
            <div class="container">
            
                <div class="form-group">

                    <label for="name" class="main-text">Name</label>
                    <input name="name" id="name" value="<?php echo $name; ?>"
                        class="form-control form-control-lg <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>">
                    <span class="invalid-feedback"><?php echo $name_err; ?></span>
                    
                    <label for="image" class="main-text">Skill Logo</label>
                    <input class="form-control <?php echo (!empty($image_err)) ? 'is-invalid' : ''; ?>" type="file" 
                        value="<?php echo $target_file; ?>" name="image" onchange="preview()" />
                    <span class="invalid-feedback"><?php echo $image_err; ?></span>

                    <div class="row text-center">
                        <div class="col-md-12">
                            <img name="image" id="image" src="" class="img-responsive">
                        </div>
                    </div>

                    <label for="id_skill_type">Skill Type</label>
                    <select name="id_skill_type" id="id_skill_type" class="form-select form-select-lg
                        <?php echo (!empty($id_skill_type_err)) ? 'is-invalid' : ''; ?>">
                        <option selected>Select a skill type</option>
                        <option value="1">Programming Languages & Tools</option>
                        <option value="2">Frameworks</option>
                    </select>
                    <span class="invalid-feedback"><?php echo $id_skill_type_err; ?></span>
                </div>

                <div class="row text-center">
                    <div class="col-md-6">
                        <a href="read.php" class="custom-button button-cancel" role="button">Return</a>
                    </div>
                    <div class="col-md-6">
                        <input type="submit" value="Create" class="custom-button button-update">
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
