<?php
require_once 'app/helpers.php';
session_start();

redirect_auth(false, './signin.php');

if (validate_csrf() && isset($_GET['rid']) && is_numeric($_GET['rid'])) {
    $rid = filter_input(INPUT_GET, 'rid', FILTER_SANITIZE_NUMBER_INT);

    if ($rid) {
        $uid = $_SESSION['user_id'];
        $link = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB);

        $query = "SELECT * FROM replies WHERE id=$rid AND user_id=$uid";
        $result = mysqli_query($link, $query);

        if ($result && mysqli_num_rows($result) === 1) {
            $reply = mysqli_fetch_assoc($result);
        }
    }
}
if (!isset($reply)) {
    header('location: ./blog.php');
    exit();
}

if (isset($_POST['submit'])) {
    $link = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB);

    $reply = filter_input(INPUT_POST, 'reply', FILTER_SANITIZE_STRING);
    $reply = mysqli_real_escape_string($link, $reply);

    $is_form_valid = true;

    if (!$reply || mb_strlen($reply) < 2) {
        $is_form_valid = false;
        $errors['reply'] = '* Reply can not be shorter than 2 characters!';
    }

    if ($is_form_valid) {
        $uid = $_SESSION['user_id'];

        $query = "UPDATE replies SET content='$reply' WHERE id=$rid";

        $result = mysqli_query($link, $query);

        if ($result && mysqli_affected_rows($link) === 1) {
            header('location: ./blog.php');
            exit();
        }
        header('location: ./blog.php');
        exit();
    }
}

$page_title = 'Edit Reply';
require_once './templates/header.php';

?>
<section class="container">
    <h1 class="mt-4 display-4">Edit Reply</h1>
    <div class="col-lg-6">
        <form method="post">

            <div class="mb-3 form-floating">
                <textarea class="form-control" placeholder="Input text here.." id="reply" style="height: 200px" name="reply"><?= posted_value('content') ? posted_value('content') : htmlentities($reply['content']) ?></textarea>
                <label for="reply">Edit your reply</label>
                <?= field_errors('reply') ?>
            </div>
            <div class="d-flex my-3">
                <button type="submit" name="submit" value="submit" class="btn btn-outline-info">Save Changes</button>
                <a href="./blog.php" class="btn btn-outline-danger ms-3">Cancel</a>
                <?= field_errors('submit') ?>
            </div>
        </form>
    </div>
</section>
<?php
include_once './templates/footer.php';
