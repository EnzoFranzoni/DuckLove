<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <base href="<?php echo live_site ?>">

        <title><?php echo PROJECT_NAME ?></title>

        <link href="vendor/twbs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="vendor/components/font-awesome/css/font-awesome.min.css" rel="stylesheet">
        <link href="assets/css/custom.css" rel="stylesheet">

        <link rel="icon" href="assets/images/favicon.ico">
    </head>
    <body>

        <nav class="navbar navbar-expand-md navbar-dark<?php echo (DEBUG ? '' : ' fixed-top') ?> bg-dark">
            <a class="navbar-brand" href="#"><?php echo PROJECT_NAME ?></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsMain" aria-controls="navbarsMain" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarsMain">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="categories.php"><?php echo CATEGORIES_MENU ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="items.php"><?php echo ITEMS_MENU ?></a>
                    </li>
                    <?php if (!Functions::checkNull($user_session) && $user_session->user_access_id == 1) { ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="dropdownAdmin" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo ADMIN_MENU ?></a>
                            <div class="dropdown-menu" aria-labelledby="dropdownAdmin">
                                <a class="dropdown-item" href="admin/category.php"><?php echo CATEGORIES_MENU ?></a>
                                <a class="dropdown-item" href="admin/item.php"><?php echo ITEMS_MENU ?></a>
                                <a class="dropdown-item" href="admin/user.php"><?php echo USER_MENU ?></a>
                            </div>
                        </li>
                    <?php } ?>
                    <li class="nav-item">
                        <a class="nav-link" href="subscribe.php"><?php echo SUBSCRIBE_MENU ?></a>
                    </li>
                    <?php if (Functions::checkNull($user_session)) { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php"><?php echo LOGIN_MENU ?></a>
                        </li>
                    <?php } else { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="account.php"><?php echo ACCOUNT_MENU ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php"><?php echo LOGOUT_MENU ?></a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </nav>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="#"><?php echo PROJECT_NAME ?></a>
                </li>
                <?php
                foreach ($breadcrumbs as $bcLabel => $bcLink) {
                    ?>
                    <li class="breadcrumb-item active" aria-current="page">
                        <?php if (!Functions::checkNull($bcLink)) { ?>
                            <a href="<?php echo $bcLink ?>"><?php echo $bcLabel ?></a>
                            <?php
                        } else {
                            echo $bcLabel;
                        }
                        ?>
                    </li>
                <?php } ?>
            </ol>
        </nav>

        <main role="main">

