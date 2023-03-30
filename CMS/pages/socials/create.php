<?php
require "../../utils/functions.php";
require "../../db/connection.php";

if($_SESSION["id_role"]!=1){
    header("Location: ../contact_requests/read.php");
}

$pdo = pdo_connect_mysql();

$msg = '';
$name = $icon = $url="";
$name_err= $icon_err= $url_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
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

    if(empty($name_err) && empty($icon_err) && empty($url_err)){

        $sql = "INSERT INTO socials (name,icon,url, id_me) VALUES (:name,:icon,:url, 1)";

        if($stmt = $pdo->prepare($sql)){
            $stmt->bindParam(":name", $param_name);
            $stmt->bindParam(":icon", $param_icon);
            $stmt->bindParam(":url", $param_url);

            $param_name = $name;
            $param_icon = $icon;
            $param_url = $url;

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
                    <h1 class="section-title">Create Social</h1>
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
                    
                    <label for="icon" class="main-text">Icon</label>
                    <input name="icon" id="icon" value="<?php echo $icon; ?>"
                        class="form-control form-control-lg <?php echo (!empty($icon_err)) ? 'is-invalid' : ''; ?>">
                    <span class="invalid-feedback"><?php echo $icon_err; ?></span>
                
                    <label for="url" class="main-text">Url</label>
                    <input name="url" id="url" value="<?php echo $url; ?>"
                        class="form-control form-control-lg <?php echo (!empty($url_err)) ? 'is-invalid' : ''; ?>">
                    <span class="invalid-feedback"><?php echo $url_err; ?></span>
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