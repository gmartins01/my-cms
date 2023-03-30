<?php
require "../../utils/functions.php";
require "../../db/connection.php";

if($_SESSION["id_role"]!=1){
    header("Location: ../contact_requests/read.php");
}

$pdo = pdo_connect_mysql();

$success_msg = $msg = '';
$name = $username = $password = $confirm_password = $role = "";
$name_err = $username_err = $password_err = $confirm_password_err= $role_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(empty(trim($_POST["name"]))){
        $name_err = "Please fill name.";
    }
    else{
        $name = trim($_POST["name"]);
    }
    if(empty(trim($_POST["username"]))){
        $username_err = "Please fill username.";
    }
    elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
        $username_err = "Wrong username pattern! Must be a-z, A-Z, 0-9 and _";
    }
    else{
        $sql = "SELECT id FROM users WHERE username = :username";

        if($stmt = $pdo->prepare($sql)){
            $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
            $param_username = trim($_POST["username"]);

            if($stmt->execute()){
                if($stmt->rowCount() == 1){
                    $username_err = "Username already exists!";
                }
                else{
                    $username = trim($_POST["username"]);
                }
            }
            else{
                $msg = "Ups! Try again please.";
            }

            unset($stmt);
        }
    }

    if(empty(trim($_POST["password"]))){
        $password_err = "Please fill password.";
    }
    elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password need to be at least 6 characters.";
    }
    else{
        $password = trim($_POST["password"]);
    }

    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please fill confirm password.";
    }
    else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Passwords mismatch!";
        }
    }

    if(isset($_POST["id_role"]) && $_POST["id_role"]=='Select role'){
        $role_err = "Please select a role.";
    }    
    else{
        $role = trim($_POST["id_role"]);
    }

    if(empty($name_err) && empty($username_err) && 
        empty($password_err) && empty($confirm_password_err) 
        && empty($role_err)){

        $sql = "INSERT INTO users (name, username, password, id_me, id_role) VALUES (:name, :username, :password, 1,:id_role)";

        if($stmt = $pdo->prepare($sql)){
            $stmt->bindParam(":name", $param_name);
            $stmt->bindParam(":username", $param_username);
            $stmt->bindParam(":password", $param_password);
            $stmt->bindParam(":id_role", $param_id_role);

            $param_name = $name;
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT);
            $param_id_role = $role;

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
                    <h1 class="section-title">Create User</h1>
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
                    
                    <label for="username" class="main-text">Username</label>
                    <input name="username" id="username" value="<?php echo $username; ?>"
                        class="form-control form-control-lg <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>">
                    <span class="invalid-feedback"><?php echo $username_err; ?></span>
                
                    <label for="password" class="main-text">Password</label>
                    <input type="password" name="password" id="password" value="<?php echo $password; ?>"
                        class="form-control form-control-lg <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                    <span class="invalid-feedback"><?php echo $password_err; ?></span>

                    <label for="confirm_password" class="main-text">Confirm Password</label>
                    <input type="password" name="confirm_password" id="confirm_password" value="<?php echo $confirm_password; ?>"
                        class="form-control form-control-lg <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>">
                    <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
                
                    <label for="id_role">Role</label>
                    <select name="id_role" id="id_role" class="form-select form-select-lg
                        <?php echo (!empty($role_err)) ? 'is-invalid' : ''; ?>">
                        <option selected>Select role</option>
                        <option value="1">Admin</option>
                        <option value="2">Manager</option>
                    </select>
                    <span class="invalid-feedback"><?php echo $role_err; ?></span>
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