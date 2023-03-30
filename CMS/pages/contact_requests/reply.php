<?php
require "../../utils/functions.php";
require "../../db/connection.php";

$pdo = pdo_connect_mysql();
$success_msg = $msg = '';
$reply = $reply_subject ="";
$reply_err =$reply_subject_err="";

if (isset($_GET['id'])) {
    
    // Mark as read
    $stmt = $pdo->prepare('UPDATE contact_requests SET is_read = 1 WHERE id = ?');
    $stmt->execute([$_GET['id']]);

    $stmt = $pdo->prepare('SELECT * FROM contact_requests WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    $contact = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$contact) {
        exit('Contact request with that ID doesn\'t exist!');
    }
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        if(empty(trim($_POST["reply"]))){
            $reply_err = "Please fill the reply message.";
        }
        else{
            $reply = trim($_POST["reply"]);
        }
        
        if(empty(trim($_POST["reply_subject"]))){
            $reply_subject_err = "Please fill the subject message.";
        }
        else{
            $reply_subject = trim($_POST["reply_subject"]);
        }
        
        if(empty($reply_err) && empty($reply_subject_err)){
            $to = $contact['email'];

            if (mail($to, $reply_subject, $reply)) {
                $success_msg = 'Your message has been sent.';
            } else {
                $msg =  'There was a error sending the email.';
            }
        }

    }
}else {
    exit('No ID specified!');
}

?>
<?=template_header('Reply')?>

    <div class="section">
        <div class="container text-center">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="section-title">Message</h2>
                </div>
            </div>
        </div>
        </div>
        <div class="container">
            <div class="form-group">

                <label for="email" class="main-text">From</label>
                <input type="email" name="email" Disabled id="email" 
                class="form-control form-control-lg" value="<?php echo $contact['email']?>">

                <label for="subject" class="main-text">Subject</label>
                <input type="text" name="subject" Disabled id="subject" 
                class="form-control form-control-lg" value="<?php echo $contact['subject']?>">

                <label for="message" class="main-text">Message</label>
                    <textarea name="message" Disabled id="message" cols="30" rows="10" 
                        class="form-control form-control-lg "><?php echo $contact['message']?></textarea>
                        
            </div>
        </div>
        <div class="section">

            <div class="container text-center">
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="section-title">Reply</h2>
                    </div>
                </div>
            </div>

        
            <form method="POST">
                <div class="container">
                    <div class="form-group">
                        <label for="reply_subject" class="main-text">Subject</label>
                        <input type="text" name="reply_subject"  id="reply_subject" 
                            class="form-control form-control-lg <?php echo (!empty($reply_subject_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $reply_subject ?>">
                        <span class="invalid-feedback"><?php echo $reply_subject_err; ?></span>
                        
                        <label for="reply" class="main-text">Reply message</label>
                            <textarea name="reply" id="reply" cols="30" rows="10" 
                            class="form-control form-control-lg <?php echo (!empty($reply_err)) ? 'is-invalid' : ''; ?>"><?php echo $reply?></textarea>
                        <span class="invalid-feedback"><?php echo $reply_err; ?></span>
                    </div>
                
                    <div class="row text-center">
                        <div class="col-md-6">
                            <a href="read.php" class="custom-button button-cancel" role="button">Return</a>
                        </div>
                        <div class="col-md-6">
                            <input type="submit" value="Reply" class="custom-button button-update">
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