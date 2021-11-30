<?php
require_once 'app/helpers.php';
session_start();

redirect_auth(false, './signin.php');

if (validate_csrf() && isset($_GET['pid']) && is_numeric($_GET['pid'])) {
    $pid = filter_input(INPUT_GET, 'pid', FILTER_SANITIZE_NUMBER_INT);

    if ($pid) {
        $uid = $_SESSION['user_id'];
        $link = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB);

        $query = "SELECT * FROM posts WHERE id=$pid";
        $result = mysqli_query($link, $query);

        if ($result && mysqli_num_rows($result) === 1) {
            $post = mysqli_fetch_assoc($result);
        }
    }
}
if (!isset($post)) {
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
        $name = $_SESSION['user_name'];

        $query = "INSERT INTO replies VALUES (NULL, $uid, '$reply', '$name', $pid, NOW())";

        $result = mysqli_query($link, $query);

        if ($result && mysqli_affected_rows($link) === 1) {
            header('location: ./blog.php');
            exit();

        }
        $errors['submit'] = '* An error has occured, please try again later!';
    }
}

$page_title = 'reply Post';
require_once './templates/header.php';

?>
<section class="container">
    <h1 class="mt-4 display-4">Reply to post</h1>
    <div class="col-lg-6">
        <form method="post">
            <div class="card-body">
                <h3 class="card-title"><?= htmlentities(ucfirst($post['title'])) ?></h3>
                <p class="card-text"><?= nl2br(htmlentities($post['article'])) ?></p>
            </div>

            <div class="mb-3 form-floating">
                <textarea class="form-control" placeholder="Input text here.." id="reply" style="height: 200px" name="reply"><?= posted_value('reply') ? posted_value('reply') : '' ?></textarea>
                <label for="reply">Enter your reply</label>
                <?= field_errors('reply') ?>
            </div>
            <div class="d-flex my-3">
                <button type="submit" name="submit" value="submit" class="btn btn-outline-success">Reply</button>
                <a href="./blog.php" class="btn btn-outline-danger ms-3">Cancel</a>
                <?= field_errors('submit') ?>
            </div>
        </form>
    </div>
</section>
<?php
include_once './templates/footer.php';
