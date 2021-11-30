<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
    <link rel="icon" href="./images/dog.svg" sizes="any" type="image/svg+xml">

    <title><?= $page_title ?> - iDogs</title>
</head>

<body class="d-flex flex-column min-vh-100">
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <div class="container">
                <a class="navbar-brand" href="./">iDogs</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-between" id="navbarNav">

                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link<?= active_nav_item('About') ?>" href="about.php">About</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link<?= active_nav_item('Blog') ?>" href="blog.php">Blog</a>
                        </li>
                    </ul>

                    <?php

                    if (!empty($_SESSION['user_name'])) : ?>

                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link" href="profile.php"><?= 'Welcome ' . htmlentities(ucwords($_SESSION['user_name'])) . '!' ?></a>
                            </li>
                            <li class="nav-item">
                                <a href="logout.php" class="nav-link text-light">Logout</a>
                            </li>
                        </ul>

                    <?php else : ?>

                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a href="signin.php" class="nav-link<?= active_nav_item('Sign In') ?>">Sign In</a>
                            </li>
                            <li class="nav-item">
                                <a href="signup.php" class="nav-link">Sign Up</a>
                            </li>
                        </ul>

                    <?php endif; ?>

                </div>
            </div>
        </nav>
    </header>


    <main class="container p-4 flex-fill" id="body">