<?php
require_once 'app/helpers.php';
session_start();

redirect_auth(false, './signin.php');

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
        $query = "INSERT INTO posts VALUES (NULL, $uid, '$title', '$article',NOW())";

        $result = mysqli_query($link, $query);

        if ($result && mysqli_affected_rows($link) === 1) {
            header('location: ./blog.php');
            exit();
        }
    }
}

$page_title = 'Add Post';
require_once './templates/header.php';

?>
<section class="container">
    <h1 class="mt-4 display-4">Add New Post</h1>
    <div class="col-lg-6">
        <form method="post">
            <div class="mb-3 mt-5 w-75 form-floating">
                <input type="text" value="<?= posted_value('title') ?>" name="title" class="form-control" id="title" placeholder="Title..">
                <label for="title">Choose a title</label>
                <?= field_errors('title') ?>
            </div>

            <div class="mb-3 form-floating">
                <textarea class="form-control" placeholder="Input text here.." id="article" style="height: 200px" name="article"><?= posted_value('article') ?></textarea>
                <label for="article">Input your text here..</label>
                <?= field_errors('article') ?>
            </div>
            <div class="d-flex my-3">
                <button type="submit" name="submit" value="submit" class="btn btn-outline-info">Publish</button>
                <a href="./blog.php" class="btn btn-outline-danger ms-3">Cancel</a>
            </div>
        </form>
    </div>
</section>
<?php
include_once './templates/footer.php';

// to do
// better validation