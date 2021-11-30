<?php
require_once 'app/helpers.php';
session_start();
$page_title = 'Home';
require_once './templates/header.php';

?>

<body>
    <section class="container text-center p-4">
        <?php if (is_logged_in()) : ?>

            <h1 class="display-2">Welcome to iDog, forum for dog lovers!</h1>
            <p>Tell us just how much you love your dogs by sharing stories and pictures about them with other dog lovers worldwide</p>
            <a href="blog.php" role="button" class="mt-4 btn btn-outline-info btn-lg">Check out the iDogBlog!</a>
    </section>
    <br>
    <section class="container p-3">
        <div class="row">
            <div class="col">
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Sapiente tenetur esse rerum, delectus in veniam beatae porro accusamus inventore dolorem tempora dignissimos aut, eum aperiam. Ullam quidem fugiat tempora accusantium possimus. Sapiente autem incidunt fuga sed at rerum amet, nisi non, exercitationem labore unde qui consequatur facere dignissimos veniam modi.
            </div>
            <div class="col">
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Expedita, excepturi. Iste nesciunt eligendi, vel reprehenderit dolore voluptas molestiae temporibus ab.
            </div>
            <div class="col m-auto">
                <br>
                <img src=".\images\dog.jpg" alt="happy dog" style="width: 40rem;">
            </div>
        </div>

    <?php else : ?>
        <h1 class="display-2">Welcome to iDog, forum for dog lovers!</h1>
        <p>Tell us just how much you love your dogs by sharing stories and pictures about them with other dog lovers worldwide</p>
        <a href="signup.php" role="button" class="mt-4 btn btn-outline-info btn-lg w-50">Sign Up for FREE!</a><br>
        <a href="signin.php" role="button" class="mt-4 btn btn-outline-primary btn-lg w-50"> Members Login </a>
    </section>
    <br>
    <section class="container p-3">
        <div class="row">
            <div class="col">
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Sapiente tenetur esse rerum, delectus in veniam beatae porro accusamus inventore dolorem tempora dignissimos aut, eum aperiam. Ullam quidem fugiat tempora accusantium possimus. Sapiente autem incidunt fuga sed at rerum amet, nisi non, exercitationem labore unde qui consequatur facere dignissimos veniam modi.
            </div>
            <div class="col">
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Expedita, excepturi. Iste nesciunt eligendi, vel reprehenderit dolore voluptas molestiae temporibus ab.
            </div>
            <div class="col m-auto">
                <br>
                <img src=".\images\dog.jpg" alt="happy dog" style="width: inherit;">
            </div>
        </div>
    <?php endif; ?>
    </section>

    <?php

    include_once './templates/footer.php';

    ?>