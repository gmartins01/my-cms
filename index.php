<?php
require "./CMS/db/connection.php";

$pdo = pdo_connect_mysql();

// Me
$stmt = $pdo->prepare('SELECT * FROM me WHERE id = 1');
$stmt->execute();
$me = $stmt->fetch(PDO::FETCH_ASSOC);

// Socials
$stmt = $pdo->prepare('SELECT * FROM socials WHERE id_me = 1 and status = 1');
$stmt->execute();
$socials = $stmt->fetchAll(PDO::FETCH_ASSOC);

// About Me
$stmt = $pdo->prepare('SELECT * FROM about WHERE id_me = 1 and status = 1');
$stmt->execute();
$about = $stmt->fetchAll(PDO::FETCH_ASSOC);
$numRowsAboutMe = $stmt->rowCount();

// Education
$stmt = $pdo->prepare('SELECT * FROM education WHERE id_me = 1 and status = 1');
$stmt->execute();
$education = $stmt->fetchAll(PDO::FETCH_ASSOC);
$numRowsEducation = $stmt->rowCount();

// Languages
$stmt = $pdo->prepare('SELECT * FROM languages WHERE id_me = 1 and status = 1');
$stmt->execute();
$languages = $stmt->fetchAll(PDO::FETCH_ASSOC);
$numRowsLanguages = $stmt->rowCount();

// Skills
$stmt = $pdo->prepare('SELECT * FROM skills WHERE id_me = 1 and id_skill_type = 1 and status = 1');
$stmt->execute();
$skills1 = $stmt->fetchAll(PDO::FETCH_ASSOC);
$numRowsSkills1 = $stmt->rowCount();
$stmt = $pdo->prepare('SELECT * FROM skills WHERE id_me = 1 and id_skill_type = 2 and status = 1');
$stmt->execute();
$skills2 = $stmt->fetchAll(PDO::FETCH_ASSOC);
$numRowsSkills2 = $stmt->rowCount();


$email = $subject = $message = "";
$email_err = $subject_err = $message_err = "";
$success_msg = $msg = '';
if($_SERVER["REQUEST_METHOD"] == "POST"){

    if(empty(trim($_POST["email"]))){
        $email_err = "Please fill the email.";
    }
    else{
        $email = trim($_POST["email"]);
    }

    if(empty(trim($_POST["subject"]))){
        $subject_err = "Please fill the subject.";
    }  
    else{
        $subject = trim($_POST["subject"]);
    }

    if(empty(trim($_POST["message"]))){
        $message_err = "Please fill the message.";
    }
    else{
        $message = trim($_POST["message"]);
    }


    if(empty($email_err) && empty($subject_err) && empty($message_err)){
        $sql = "INSERT INTO contact_requests (email,subject,message, id_me) VALUES (:email,:subject,:message, 1)";
        if($stmt = $pdo->prepare($sql)){
            $stmt->bindParam(":email", $param_email);
            $stmt->bindParam(":subject", $param_subject);
            $stmt->bindParam(":message", $param_message);

            $param_email = $email;
            $param_subject = $subject;
            $param_message = $message;

            if($stmt->execute()){
                //header("location: index.php");
                $email = $subject = $message = "";

                $success_msg = 'Message sent successfully!';
            }
            else{
                $msg = "Error sending the message. Try again please.";
            }

            unset($stmt);
        }
    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio</title>
    <!--Bootstrap-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
    <!--External Css-->
    <link rel="stylesheet" href="styles.css">
    <!--Font Awesome-->
    <script src="https://kit.fontawesome.com/44e2662d8b.js" crossorigin="anonymous"></script>
    
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar sticky-top navbar-expand-md">
        <a class="navbar-brand navtitle" href="#">Portfolio</a>
        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbar">
            <span class="navtitle" role="button" ><i class="fa fa-bars" aria-hidden="true"></i></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbar">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <?php if($numRowsAboutMe > 0) { ?>
            <li class="nav-item active">
                <a class="nav-link navtext" href="#about">About Me</a>
            </li>
            <?php } ?>
            <?php if($numRowsEducation > 0) { ?>
            <li class="nav-item active">
                <a class="nav-link navtext" href="#education">Education</a>
            </li>
            <?php } ?>
            <?php if($numRowsLanguages > 0) { ?>
            <li class="nav-item active">
                <a class="nav-link navtext" href="#languages">Languages</a>
            </li>
            <?php } ?>
            <?php if($numRowsSkills1 > 0 || $numRowsSkills2 > 0) { ?>
            <li class="nav-item active">
              <a class="nav-link navtext" href="#skills">Skills</a>
            </li>
            <?php } ?>
            <li class="nav-item active">
              <a class="nav-link navtext" target="_blank" href="./CMS/auth/login.php">CMS</a>
            </li>
          </ul>
          <ul class="navbar-nav me-2 mb-2 mb-lg-0">
            <li class="nav-item">
                <button id="theme-toggle" class="theme-toggle-button fas"></button>
            </li>
          </ul>
        </div>
    </nav>
    <div id="wrapper">

        <!-- Me -->
        <div id="header">
            <div class="section" id="me">
                <div class="container text-center">
                    <div class="row">
                        <div class="col-md-12">
                                <img src="./CMS/pages/profile/<?php echo $me["image"] ?>" class="img-responsive" alt="Photo of <?php echo $me["name"] ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                                <h1 class="section-title">Hi, I'm <span class="main-text"><?php echo $me["name"]?></span></h1>
                                <p class="lead">I'm a <?php echo $me["profession"] ?></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="icon-container">
                            <?php foreach ($socials as $s): ?>
                                <a href="<?php echo $s["url"] ?>" target="_blank" class="<?php echo $s["icon"] ?> icon"></a>
                            <?php endforeach; ?>
                            </div>   
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="main">

            <!--About me-->
            <?php if($numRowsAboutMe > 0) { ?>
            <div class="section" id="about">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 section-title">
                            <h2>About Me</h2>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                        <?php foreach ($about as $a): ?>
                            <p class="section-text"><?php echo $a["description"]?></p>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>

            <!--Education-->
            <?php if($numRowsEducation > 0) { ?>
            <div class="section" id="education">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="section-title">
                                <h2>Education</h2>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                        <?php foreach ($education as $edu): ?>
                            <div class="education-block text-center">
                                <div class="education-image">                  
                                    <img src="./CMS/pages/education/<?php echo $edu["image"] ?>" class="img-responsive"  alt="<?php echo $edu["institution"] ?> logo">
                                </div>
                                <div class="education-title">
                                    <h3><?php echo $edu["institution"] ?></h3>
                                    <p><?php echo $edu["year_start"] ?> - <?php echo $edu["year_end"] ?></p>
                                </div>
                                <div class="education-text">
                                    <p><?php echo $edu["course"] ?>
                                    </p>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>

                    </div>
                    
                </div>
            </div>
            <?php } ?>

            <!--Languages-->
            <?php if($numRowsLanguages > 0) { ?>
            <div id="languages" class="section">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="section-title">
                                <h2>Languages</h2>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 section-text">
                            <?php foreach ($languages as $l): ?>
                                <p><?php echo $l["description"] ?></p>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>

            <!--Skills-->
            <?php if($numRowsSkills1 > 0 || $numRowsSkills2 > 0) { ?>
            <div class="section" id="skills">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="section-title">
                                <h2 class="section-title">Skills</h2>
                            </div>
                        </div>
                    </div>
                    <?php if($numRowsSkills1 > 0) { ?>
                    <div class="row">
                        <div class="col-md-12 section-subtitle">
                            <h3>Programming Languages & Tools</h3>
                        </div>
                    </div>
                    
                    <div class="row">
                    
                        <div class="col-md-12">
                        <?php foreach ($skills1 as $s1): ?>
                            <img src="./CMS/pages/skills/<?php echo $s1['image'] ?>" class="icon" alt="<?php echo $s1['name']?> logo">
                        <?php endforeach; ?>
                        </div>   
                    </div>
                    <?php } ?>
                    
                    <?php if($numRowsSkills2 > 0) { ?>
                    <div class="row">
                        <div class="col-md-12 section-subtitle">
                            <h3>Frameworks</h3>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="co-md-12">
                        <?php foreach ($skills2 as $s2): ?>
                            <img src="./CMS/pages/skills/<?php echo $s2['image'] ?>" class="icon" alt="<?php echo $s2['name']?> logo">
                        <?php endforeach; ?>
                        </div>
                    </div>
                    <?php } ?>
                    
                </div>
            </div>
            <?php } ?>
            
            <!--Contact-->
            <div class="section" id="contact">
                <div class="container">
                    <form method="POST" id="form">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="section-title">
                                    <h2>Get in Touch</h2>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label for="email" class="form-label d-flex justify-content-start">Email</label>
                                <input type="email" name="email" id="email" value="<?php echo $email; ?>"
                                class="form-control form-control-lg <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>">
                                <span class="invalid-feedback d-flex justify-content-start"><?php echo $email_err; ?></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label for="subject" class="form-label d-flex justify-content-start">Subject</label>
                                <input type="text" name="subject" id="subject" value="<?php echo $subject; ?>"
                                class="form-control form-control-lg <?php echo (!empty($subject_err)) ? 'is-invalid' : ''; ?>" >
                                <span class="invalid-feedback d-flex justify-content-start"><?php echo $subject_err; ?></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label for="message" class="form-label d-flex justify-content-start">Message</label>
                                <textarea name="message" id="message" cols="30" rows="10" class="form-control form-control-lg <?php echo (!empty($message_err)) ? 'is-invalid' : ''; ?>"><?php echo $message?></textarea>
                                <span class="invalid-feedback d-flex justify-content-start"><?php echo $message_err; ?></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <input type="submit" value="Submit" class="btn btn-primary button">
                            </div>
                        </div>
                    </form>

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

                </div>
            </div>
        </div>

        <div id="footer">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="footer-text">
                            <p>&copy; <?php echo date("Y")?> - <?php echo $me["name"]?></p>         
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="main.js"></script>
</body>
</html>