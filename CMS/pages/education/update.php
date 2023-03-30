<?php
require "../../utils/functions.php";
require "../../db/connection.php";

if($_SESSION["id_role"]!=1){
    header("Location: ../contact_requests/read.php");
}

$pdo = pdo_connect_mysql();
$success_msg = $msg = '';
$image = $institution = $course = $year_start = $year_end = $status = "";
$image_err = $institution_err= $course_err= $year_start_err = $year_end_err = "";

$stmt = $pdo->prepare('SELECT * FROM education WHERE id = ?');
$stmt->execute([$_GET['id']]);
$education = $stmt->fetch(PDO::FETCH_ASSOC);

if (isset($_GET['id'])) {
    if (!empty($_POST)) {

        if(empty(trim($_POST["institution"]))){
            $institution_err = "Please fill the institution.";
        }
        else{
            $institution = trim($_POST["institution"]);
        }
    
        if(empty(trim($_POST["course"]))){
            $course_err = "Please fill the course.";
        }
        else{
            $course = trim($_POST["course"]);
        }
    
        if(empty(trim($_POST["year_start"]))){
            $year_start_err = "Please fill the start year.";
        }
        else{
            $year_start = trim($_POST["year_start"]);
        }
    
        if(empty(trim($_POST["year_end"]))){
            $year_end_err = "Please fill the end year.";
        }
        else{
            $year_end = trim($_POST["year_end"]);
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

        
        if(empty($institution_err) && empty($course_err) && empty($year_start_err)
            && empty($year_end_err) && empty($image_err)){

            if (empty($_FILES['image']['name'])){
                $target_file = $education['image'];
            }if(move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)){
                unlink($education['image']);
            }
                $stmt = $pdo->prepare('UPDATE education SET institution = ?, course = ?, image = ?,year_start = ?,year_end=?,status=?,id_me = ? WHERE id = ?');
                $stmt->execute([$institution,$course,$target_file,$year_start,$year_end,$status,1,$_GET['id']]);
                $success_msg = 'Education updated successfully!';
            
        }else{
            $msg = 'Failed to update education!';
        }
    }
    $stmt = $pdo->prepare('SELECT * FROM education WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    $education = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$education) {
        exit('Educaiton with that ID doesn\'t exist!');
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
                    <h1 class="section-title">Update Education</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="section" id="education">
        <form action="update.php?id=<?php echo $education['id']?>" method="POST" 
             enctype="multipart/form-data">
            <div class="container">
                
                <div class="form-group">
                    
                    <label for="institution" class="main-text">Institution</label>
                    <input name="institution" id="institution" value="<?php echo $education['institution']; ?>"
                        class="form-control form-control-lg <?php echo (!empty($institution_err)) ? 'is-invalid' : ''; ?>">
                    <span class="invalid-feedback"><?php echo $institution_err; ?></span>
                        
                    <label for="image" class="main-text">Institution Logo</label>
                    <input class="form-control <?php echo (!empty($image_err)) ? 'is-invalid' : ''; ?>" type="file" 
                        value="<?php echo $target_file; ?>" name="image" onchange="preview()" />
                    <span class="invalid-feedback"><?php echo $image_err; ?></span>

                    <div class="row text-center">
                        <div class="col-md-12">
                            <img id="image" name="image" class="img-responsive" src="<?php echo $education['image']?>" alt="<?php echo $education['institution']?> logo">
                        </div>
                    </div>
                    
                    <label for="course" class="main-text">Course</label>
                    <input name="course" id="course" value="<?php echo $education['course']; ?>"
                        class="form-control form-control-lg <?php echo (!empty($course_err)) ? 'is-invalid' : ''; ?>">
                    <span class="invalid-feedback"><?php echo $course_err; ?></span>
                    
                    <label for="year_start" class="main-text">Year Start</label>
                    <input type="number" name="year_start" id="year_start" value="<?php echo $education['year_start']; ?>" min="2000" max="2099" step="1"
                        class="form-control form-control-lg <?php echo (!empty($year_start_err)) ? 'is-invalid' : ''; ?>">
                    <span class="invalid-feedback"><?php echo $year_start_err; ?></span>

                    <label for="year_end" class="main-text">Year End</label>
                    <input type="number" name="year_end" id="year_end" value="<?php echo $education['year_end']; ?>" min="2000" max="2099" step="1"
                        class="form-control form-control-lg <?php echo (!empty($year_end_err)) ? 'is-invalid' : ''; ?>">
                    <span class="invalid-feedback"><?php echo $year_end_err; ?></span>

                    <label for="status">Status</label>
                        <select name="status" id="status" class="form-select form-select-lg">
                            <option value="1" <?php if($education['status']==1) echo 'selected="selected"'; ?> >Enabled</option>
                            <option value="2" <?php if($education['status']==2) echo 'selected="selected"'; ?> >Disabled</option>    
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