<?php
require "../../utils/functions.php";
require "../../db/connection.php";

if($_SESSION["id_role"]!=1){
    header("Location: ../contact_requests/read.php");
}

$pdo = pdo_connect_mysql();
$success_msg = $msg = '';
$name = $username = $role = "";
$name_err = $username_err = $role_err = "";

$stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
$stmt->execute([$_GET['id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (isset($_GET['id'])) {
    
    if (!empty($_POST)) {
        
        
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
                    if($stmt->rowCount() == 1 && $param_username != $user['username']){
                        $username_err = "Username already exists!";
                    }
                    else{
                        $username = trim($_POST["username"]);
                    }
                }
                else{
                    echo "Ups! Try again please.";
                }
    
                unset($stmt);
            }
        }

        if(isset($_POST["id_role"]) && $_POST["id_role"]==''){
            $role_err = "Please select a role.";
        }    
        else{
            $role = trim($_POST["id_role"]);
        }
        
        if(empty($name_err) && empty($username_err) && empty($role_err)){
            $stmt = $pdo->prepare('UPDATE users SET name = ?, username = ?, id_role = ?, id_me = ? WHERE id = ?');
            $stmt->execute([$name, $username, $role, 1,$_GET['id']]);
            $success_msg = 'Updated Successfully!';
        }else{
            $msg = 'Failed to update!';
        }
        
    }
    $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        exit('User doesn\'t exist with that ID!');
    }
} else {
    exit('No ID specified!');
}
?>

<?=template_header('Read')?>

<div class="section">
        <div class="container text-center">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="section-title">Update User</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="section">
        <form action="update.php?id=<?php echo $user['id']?>" method="POST" enctype="multipart/form-data">
            <div class="container">
                <div class="form-group">
                    <label for="name" class="main-text">Name</label>
                    <input name="name" id="name" value="<?php echo $user['name']; ?>"
                        class="form-control form-control-lg <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>">
                    <span class="invalid-feedback"><?php echo $name_err; ?></span>
                    
                    <label for="username" class="main-text">Username</label>
                    <input name="username" id="username" value="<?php echo $user['username']; ?>"
                        class="form-control form-control-lg <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>">
                    <span class="invalid-feedback"><?php echo $username_err; ?></span>
                                
                    <label for="id_role">Role</label>
                        <select name="id_role" id="id_role" class="form-select form-select-lg
                            <?php echo (!empty($role_err)) ? 'is-invalid' : ''; ?>">
                            <option value="1" <?php if($user['id_role']==1) echo 'selected="selected"'; ?> >Admin</option>
                            <option value="2" <?php if($user['id_role']==2) echo 'selected="selected"'; ?> >Manager</option>
                            <option value="3" <?php if($user['id_role']==3) echo 'selected="selected"'; ?> >Unauthorized</option>
                    </select>
                    <span class="invalid-feedback"><?php echo $role_err; ?></span>
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