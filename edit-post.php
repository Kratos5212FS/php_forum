<?php
require_once 'app/helpers.php';
session_start();

redirect_auth(false, './signin.php');

if (validate_csrf() && isset($_GET['pid']) && is_numeric($_GET['pid'])) {
    $pid = filter_input(INPUT_GET, 'pid', FILTER_SANITIZE_NUMBER_INT);

    if ($pid) {
        $uid = $_SESSION['user_id'];
        $link = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB);

        $query = "SELECT * FROM posts WHERE id=$pid AND user_id=$uid";
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

    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $title = trim($title);
    $title = mysqli_real_escape_string($link, $title);

    $article = filter_input(INPUT_POST, 'article', FILTER_SANITIZE_STRING);
    $article = mysqli_real_escape_string($link, $article);

    $is_form_valid = true;

    if (!$title || mb_strlen($title) < 2) {
        $is_form_valid = false;
        $errors['title'] = '* Title is required with minimum 2 characters!';
    }

    if (!$article || mb_strlen($article) < 10) {
        $is_form_valid = false;
        $errors['article'] = '* Article can not be shorter than 10 characters!';
    }

    if ($is_form_valid) {
        $uid = $_SESSION['user_id'];

        $query = "UPDATE posts SET title='$title', article='$article' WHERE id=$pid";

        $result = mysqli_query($link, $query);

        if ($result && mysqli_affected_rows($link) === 1) {
            header('location: ./blog.php');
            exit();
        }
        header('location: ./blog.php');
        exit();
    }
}

$page_title = 'Edit Post';
require_once './templates/header.php';

?>
<section class="container">
    <h1 class="mt-4 display-4">Edit Post</h1>
    <div class="col-lg-6">
        <form method="post">
            <div style="width: 65%;" class="mb-3 mt-5 form-floating">
                <input type="text" value="<?= posted_value('title') ? posted_value('title') : htmlentities($post['title']) ?>" name="title" class="form-control" id="title" placeholder="Title..">
                <label for="title">Edit your title</label>
                <?= field_errors('title') ?>
            </div>

            <div class="mb-3 form-floating">
                <textarea class="form-control" placeholder="Input text here.." id="article" style="height: 200px" name="article"><?= posted_value('article') ? posted_value('article') : htmlentities($post['article']) ?></textarea>
                <label for="article">Edit your text here..</label>
                <?= field_errors('article') ?>
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
