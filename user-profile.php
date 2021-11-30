<?php
require_once 'app/helpers.php';
session_start();

redirect_auth(false, './signin.php');

if (isset($_GET['uid']) && is_numeric($_GET['uid'])) {
    $uid = filter_input(INPUT_GET, 'uid', FILTER_SANITIZE_NUMBER_INT);

    if ($uid) {
        $link = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB);
        $query = "SELECT * FROM users WHERE id=$uid";

        $result = mysqli_query($link, $query);

        if ($result && mysqli_num_rows($result) === 1) {
            $user = mysqli_fetch_assoc($result);
        } else {
            header('location: ./blog.php');
            exit();
        }
    }
}

$last_post_query = "SELECT * FROM posts WHERE user_id=$uid ORDER BY id DESC";
$last_post_result = mysqli_query($link, $last_post_query);

// if ($last_post_result && mysqli_num_rows($last_post_result) > 0) {
//     $post = mysqli_fetch_assoc($last_post_result);
// }

$page_title = htmlentities(ucwords($user['name'])) . '\'s Profile';
require_once './templates/header.php';
?>

<section class="container p-4 col-md-8">
    <img src="images/profiles/<?= $user['profile_image'] ?>" class="float-end rounded" alt="<?= $user['name'] ?>" width="40%">
    <b>
        <h1 class="mt-4 mb-4 display-4 text-black"><?= htmlentities(ucwords($user['name'])) ?></h1>
    </b>
    <div class="d-flex space-between">
        <div class="me-4">
            <p><?= htmlentities(ucwords($user['name'])) ?>'s email address: </p>
            <b>
                <span><?= $user['email'] ?></span>
            </b>
        </div>
        <div class="ms-4">
            <p>Member since: </p>
            <b>
                <span><?= $user['joined_at'] ?></span>
            </b>
        </div>
    </div>
</section>
<?php if ($last_post_result && mysqli_num_rows($last_post_result) > 0) : ?>
    <div class="container col-md-8 mt-4"><br>
        <hr>
        <?php if ($last_post_result && mysqli_num_rows($last_post_result) < 2) : ?>

            <h2 class="mt-4 mb-4 display-6"><?= htmlentities(ucwords($user['name'])) ?>'s latest Post:</h2>
        <?php else : ?>
            <h2 class="mt-4 mb-4 display-6"><?= htmlentities(ucwords($user['name'])) ?>'s latest Posts:</h2>
        <?php endif; ?>


        <?php while ($post = mysqli_fetch_assoc($last_post_result)) : ?>

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <img src="./images/profiles/<?= $user['profile_image'] ?>" alt="ProfileImage" style="height: 60px; width: 60px; border-radius: 180%;" class="mb-4">
                        <span><b><?= htmlentities(ucwords($user['name'])) ?></b></span>
                    </div>

                    <span><?= ago($post['created_at']) ?></span>

                </div>
                <div class="card-body">
                    <h3 class="card-title"><?= htmlentities(ucfirst($post['title'])) ?></h3>
                    <p class="card-text"><?= nl2br(htmlentities($post['article'])) ?></p>

                    <?php if (is_logged_in() && $post['user_id'] === $_SESSION['user_id']) : ?>
                        <div class="d-flex justify-content-end">
                            <div class="dropdown show">
                                <a class="dropdown" role="button" id="dropdownMenuButton1" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots text-black" style="font-size: 1.35rem;"></i>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                    <a href="edit-post.php?pid=<?= $post['id'] ?>&token=<?= $_SESSION['token'] ?>" class="dropdown-item mb-2"><i class="bi bi-pencil"></i> Edit</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="btn dropdown-item mt-2" id="delete-post" onclick="confirmDelete()"><i class="bi bi-trash"></i> Delete</a>
                                    <!-- הכפתור מחיקה לא עובד כמו שצריך, ניסיתי לתקן ולא הצלחתי.. אז אני מגיש מה עשינו בכיתה. בוא מוחק את השורה הכי ישנה שקיימת אצל היוזר -->
                                </div>
                            </div>
                        </div>
                        <script>
                            function confirmDelete() {

                                var r = confirm("Are you sure you want to delete this post?");
                                if (r === true) {
                                    window.location = 'delete-post.php?pid=<?= $post['id'] ?>&token=<?= $_SESSION['token'] ?>';
                                }
                            }
                        </script>
                    <?php endif; ?>


                    <?php if (is_logged_in()) : ?>
                        <a href="reply.php?pid=<?= $post['id'] ?>&token=<?= $_SESSION['token'] ?>" class="btn btn-sm btn-success"><i class="bi bi-reply"></i> Reply</a>

                        <?php
                        $reply_query = "SELECT * FROM replies ORDER BY replied_at DESC";
                        $reply_result = mysqli_query($link, $reply_query);

                        if ($reply_result && mysqli_num_rows($reply_result) > 0) : ?>
                            <?php while ($reply = mysqli_fetch_assoc($reply_result)) : ?>
                                <?php if ($reply['post_id'] === $post['id']) : ?>
                                    <div class="card-header d-flex justify-content-between align-items-center text-dark mt-3">
                                        <div>
                                            <span><b><?= htmlentities(ucwords($reply['name'])) ?></b></span>
                                        </div>
                                        <span><?= ago($reply['replied_at']) ?></span>
                                    </div>
                                    <div class="card-body">
                                        <p class="card-text"><?= nl2br(htmlentities($reply['content'])) ?></p>
                                        <?php if (is_logged_in() && $reply['user_id'] === $_SESSION['user_id']) : ?>
                                            <div class="d-flex justify-content-end">
                                                <div class="dropdown show">
                                                    <a class="dropdown" role="button" id="dropdownMenuButton1" data-bs-toggle="dropdown">
                                                        <i class="bi bi-three-dots text-black" style="font-size: 1.35rem;"></i>
                                                    </a>
                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                        <a href="edit-reply.php?rid=<?= $reply['id'] ?>&token=<?= $_SESSION['token'] ?>" class="dropdown-item mb-2"><i class="bi bi-pencil"></i> Edit</a>
                                                        <div class="dropdown-divider"></div>
                                                        <a class="btn dropdown-item mt-2" id="delete-reply" onclick="confirmDeleteReply()"><i class="bi bi-trash"></i> Delete</a>
                                                        <!-- הכפתור מחיקה לא עובד כמו שצריך, ניסיתי לתקן ולא הצלחתי.. אז אני מגיש מה עשינו בכיתה. בוא מוחק את השורה הכי ישנה שקיימת אצל היוזר -->
                                                    </div>
                                                </div>
                                            </div>
                                            <script>
                                                function confirmDeleteReply() {

                                                    var r = confirm("Are you sure you want to delete this post?");
                                                    if (r === true) {
                                                        window.location = 'delete-reply.php?rid=<?= $reply['id'] ?>&token=<?= $_SESSION['token'] ?>';
                                                    }
                                                }
                                            </script>
                                        <?php endif; ?>
                                    </div>
                                    <hr>
                                <?php endif; ?>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div><br>

        <?php endwhile; ?>

    <?php else : ?>
        <h3>This user hasn't posted anything yet.</h3>
    <?php endif; ?>
    </div>

    <?php

    include './templates/footer.php';
