<?php
require_once('require.php');

if (isset($_GET['error']) && $_GET['error'] == '1') {
    Message::addWarning(LOGIN_ERROR, false);
}

$cleanForm = false;

if (isset($_POST['action']) && $_POST['action'] == 'login_submit') {
    try {
        $encryptPassword = Functions::encryptDataMd5($_POST['password']);

        $user = new User(null, $_POST['username'], $encryptPassword);

        $user->checkAccount();

        $_SESSION['user'] = serialize($user);

        $cleanForm = true;

        header('Location: index.php');
    } catch (CheckException $ex) {
        Message::addWarning($ex->getMessage(), false);
    } catch (Exception $ex) {
        Message::addError($ex->getMessage(), true);
    }
}

$username = isset($_POST['username']) && !$cleanForm ? $_POST['username'] : '';

$breadcrumbs = [LOGIN_MENU => $_SERVER['PHP_SELF']];

require_once('includes/header.php');
?>

<div class="container">

    <?php
    Message::echoSuccesses();
    Message::echoWarnings();
    Message::echoErrors();
    Message::echoInfos();

    if (!Message::isBlocking()) {
        ?>
        <h1 class="h3 mb-3 text-center"><?php echo LOGIN_TITLE ?></h1>
        <form class="form-signin mb-3" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
            <label for="username" class="sr-only"><?php echo USERNAME_LABEL ?></label>
            <input type="text" class="form-control" id="username" name="username" placeholder="<?php echo USERNAME_PLACEHOLDER ?>" value="<?php echo $username ?>" maxlength="50" required autofocus>
            <label for="password" class="sr-only"><?php echo PASSWORD_LABEL ?></label>
            <input type="password" class="form-control" id="password" name="password" placeholder="<?php echo PASSWORD_PLACEHOLDER ?>" maxlength="50" required>

            <hr class="mb-4">
            <input type="hidden" name="action" value="login_submit">
            <button class="btn btn-lg btn-primary btn-block" type="submit"><?php echo LOGIN_SUBMIT_LABEL ?></button>
        </form>
        <ul class="list-inline">
            <li class="list-inline-item">
                <a href="remind.php"><?php echo RESET_LINK ?></a>
            </li>
        </ul>
    <?php } ?>

    <hr>
</div>

<?php
require_once('includes/footer.php');
