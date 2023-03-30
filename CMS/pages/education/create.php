<?php
require "../../utils/functions.php";
require "../../db/connection.php";

if($_SESSION["id_role"]!=1){
    header("Location: ../contact_requests/read.php");
}

$pdo = pdo_connect_mysql();

$success_msg = $msg = '';
$image = $institution = $course = $year_start = $year_end = "";
$image_err = $institution_err= $course_err= $year_start_err = $year_end_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
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

    if(empty($institution_err) && empty($course_err) && empty($year_start_err)
    && empty($year_end_err) && empty($image_err)){
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)){
            $sql = "INSERT INTO education (institution,course,image,year_start,year_end, id_me) VALUES (:institution,:course,:image,:year_start,:year_end, 1)";

            if($stmt = $pdo->prepare($sql)){
                $stmt->bindParam(":institution", $param_institution);
                $stmt->bindParam(":course", $param_course);
                $stmt->bindParam(":image", $param_image);
                $stmt->bindParam(":year_start", $param_year_start);
                $stmt->bindParam(":year_end", $param_year_end);

                $param_institution = $institution;
                $param_course = $course;
                $param_image = $target_file;
                $param_year_start = $year_start;
                $param_year_end = $year_end;

                if($stmt->execute()){
                    header("location: read.php");
                    $msg = 'Created Successfully!';
                }
                else{
                    echo "Ups! Try again please.";
                }

                unset($stmt);
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
                    <h1 class="section-title">Create Education</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="section">
        <form action="create.php" method="POST" enctype="multipart/form-data">
            <div class="container">
            
                <div class="form-group">

                    <label for="institution" class="main-text">Institution</label>
                    <input name="institution" id="institution" value="<?php echo $institution; ?>"
                        class="form-control form-control-lg <?php echo (!empty($institution_err)) ? 'is-invalid' : ''; ?>">
                    <span class="invalid-feedback"><?php echo $institution_err; ?></span>
                    
                    <label for="image" class="main-text">Institution Logo</label>
                    <input class="form-control <?php echo (!empty($image_err)) ? 'is-invalid' : ''; ?>" type="file" 
                        value="<?php echo $target_file; ?>" name="image" onchange="preview()" />
                    <span class="invalid-feedback"><?php echo $image_err; ?></span>

                    <div class="row text-center">
                        <div class="col-md-12">
                            <img name="image" id="image" src="" class="img-responsive">
                        </div>
                    </div>

                    <label for="course" class="main-text">Course</label>
                    <input name="course" id="course" value="<?php echo $course; ?>"
                        class="form-control form-control-lg <?php echo (!empty($course_err)) ? 'is-invalid' : ''; ?>">
                    <span class="invalid-feedback"><?php echo $course_err; ?></span>
                
                    <label for="year_start" class="main-text">Year Start</label>
                    <input type="number" name="year_start" id="year_start" value="<?php echo $year_start; ?>" min="2000" max="2099" step="1"
                        class="form-control form-control-lg <?php echo (!empty($year_start_err)) ? 'is-invalid' : ''; ?>">
                    <span class="invalid-feedback"><?php echo $year_start_err; ?></span>

                    <label for="year_end" class="main-text">Year End</label>
                    <input type="number" name="year_end" id="year_end" value="<?php echo $year_end; ?>" min="2000" max="2099" step="1"
                        class="form-control form-control-lg <?php echo (!empty($year_end_err)) ? 'is-invalid' : ''; ?>">
                    <span class="invalid-feedback"><?php echo $year_end_err; ?></span>
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
