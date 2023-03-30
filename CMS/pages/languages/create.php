<?php
require "../../utils/functions.php";
require "../../db/connection.php";

if($_SESSION["id_role"]!=1){
    header("Location: ../contact_requests/read.php");
}

$pdo = pdo_connect_mysql();

$msg = '';
$description = "";
$description_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(empty(trim($_POST["description"]))){
        $description_err = "Please fill description.";
    }
    else{
        $description = trim($_POST["description"]);
    }

    if(empty($description_err)){

        $sql = "INSERT INTO languages (description, id_me) VALUES (:description, 1)";

        if($stmt = $pdo->prepare($sql)){
            $stmt->bindParam(":description", $param_description);

            $param_description = $description;

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

    unset($pdo);
}
?>

<?=template_header('Create')?>

<div class="section">
        <div class="container text-center">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="section-title">Create language text</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="section">
        <form action="create.php" method="POST" enctype="multipart/form-data">
            <div class="container">
                <div class="form-group">
                    <label for="description" class="main-text">Description</label>
                    <textarea name="description" id="description" cols="30" rows="10"
                        class="form-control form-control-lg <?php echo (!empty($description_err)) ? 'is-invalid' : ''; ?>"></textarea>
                    <span class="invalid-feedback"><?php echo $description_err; ?></span>
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

        <?php if ($msg): ?>
            <div class="container">
                <p class="text-center alert"><?php echo $msg ?></p>
            </div>
        <?php endif; ?>
<?=template_footer()?>