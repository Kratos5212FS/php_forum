<?php
require_once 'app/helpers.php';
session_start();

$link = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB);
$query = "SELECT u.name, u.profile_image, p.*
FROM users u JOIN posts p ON p.user_id = u.id
ORDER BY p.created_at DESC";

$result = mysqli_query($link, $query);

$page_title = 'Blog';
require_once './templates/header.php';
?>
<section class="container">
    <?php if (is_logged_in()) : ?>
        <h1 class="mt-4 display-4">iDogBlog</h1>
        <p class="mb-4">Don't forget to never share your password with anyone and certainely do not fully trust random strangers online, for your own safety <i class="bi bi-heart"></i> Have Fun!</p>

        <a href="add-post.php" class="btn btn-outline-info"><i class="bi bi-plus-circle"></i> Add New Post</a>

        <?php if ($result && mysqli_num_rows($result) > 0) : ?>

            <h3 class="mt-4">Most recent posts:</h3>

            <?php while ($post = mysqli_fetch_assoc($result)) : ?>
                <div class="my-3">

                    <div class="card">
                        <?php if (is_logged_in() && $post['user_id'] === $_SESSION['user_id']) : ?>
                            <div class="card-header d-flex justify-content-between align-items-center bg-info text-light">
                            <?php else : ?>
                                <div class="card-header d-flex justify-content-between align-items-center bg-primary text-light">
                                <?php endif; ?>
                                <div>
                                    <img src="./images/profiles/<?= $post['profile_image'] ?>" alt="ProfileImage" style="height: 60px; width: 60px; border-radius: 180%;">
                                    <!-- ------ -->
                                    <a href="user-profile.php?uid=<?= $post['user_id'] ?>" class="btn btn-outline-light">

                                        <span><b><?= htmlentities(ucwords($post['name'])) ?></b></span></a>
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


                                    <!-- -- -->
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
                                    <!-- -- -->


                                </div>

                            </div>
                    </div>

                <?php endwhile; ?>

            <?php else : ?>

                <h3 class="mt-4">No posts yet ... </h3>
                <p>Be the first one to post!</p>


            <?php endif; ?>
        <?php else : ?>
            <h1 class="mt-4 display-4">iDogBlog</h1>
            <p class="mb-4">You can watch other people's posts but you cannot reply or posts anything until you log in or sign up!</p>

            <a href="signup.php" class="btn btn-outline-info w-50"><i class="bi bi-arrow-right"></i> Join the iDogBlog today!</a><br>
            <a href="signin.php" class="btn btn-outline-primary mt-1 w-50"><i class="bi bi-arrow-left"></i> Login if you're already a member!</a>

            <?php if ($result && mysqli_num_rows($result) > 0) : ?>

                <h3 class="mt-4">Most recent posts:</h3>

                <?php while ($post = mysqli_fetch_assoc($result)) : ?>
                    <div class="my-3">

                        <div class="card">
                            <div class="card-header d-flex justify-content-between bg-primary text-light">

                                <div>
                                    <img src="./images/profiles/<?= $post['profile_image'] ?>" alt="ProfileImage" style="height: 60px; width: 60px; border-radius: 180%;"> <span><?= htmlentities(ucwords($post['name'])) ?></span>
                                </div>
                                <span><?= ago($post['created_at']) ?></span>

                            </div>
                            <div class="card-body">
                                <h3 class="card-title"><?= $post['title'] ?></h3>
                                <p class="card-text"><?= $post['article'] ?></p>

                                <?php
                                $reply_query = "SELECT * FROM replies ORDER BY replied_at DESC";
                                $reply_result = mysqli_query($link, $reply_query);

                                while ($reply = mysqli_fetch_assoc($reply_result)) : ?>
                                    <?php if ($reply['post_id'] === $post['id']) : ?>
                                        <div class="card-header d-flex justify-content-between align-items-center text-dark mt-3">
                                            <div>
                                                <span><b><?= htmlentities(ucwords($reply['name'])) ?></b></span>
                                            </div>
                                            <span><?= ago($reply['replied_at']) ?></span>
                                        </div>
                                        <div class="card-body">
                                            <p class="card-text"><?= nl2br(htmlentities($reply['content'])) ?></p>
                                        </div>
                                        <hr>
                                    <?php endif; ?>
                                <?php endwhile; ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>

            <?php else : ?>

                <h3 class="mt-4 text-sm">No posts yet ... </h3>
                <p>Login/SignUp to start posting!</p>

            <?php endif; ?>

        <?php endif; ?>

</section>

<?php
include_once './templates/footer.php';
