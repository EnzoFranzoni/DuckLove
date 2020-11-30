<?php
require_once('require.php');

try {
    $checkReset = null;

    $username = $_GET['username'] ?? '';
    $access_key = $_GET['access_key'] ?? '';

    $user = new User(null, $username, null, null, null, null, null, null, null, $access_key);

    $checkReset = $user->checkReset();

    if (isset($_POST['action']) && $_POST['action'] == 'reset_submit') {

        if (Functions::checkStringOrNull($_POST['password'])) {
            throw new CheckException(USER_CHECK_FIELD_PASSWORD_ERROR);
        } else if (Functions::checkStringOrNull($_POST['password_confirm'])) {
            throw new CheckException(USER_CHECK_FIELD_PASSWORD_CONFIRM_ERROR);
        } else if ($_POST['password'] != $_POST['password_confirm']) {
            throw new CheckException(USER_CHECK_FIELD_PASSWORD_NOT_MATCH_ERROR);
        }

        $user->password = Functions::encryptDataMd5($_POST['password']);

        $user->updateUser();

        $_SESSION['user'] = serialize($user);

        Message::addSuccess(RESET_FORM_SUCCESS, false);
    }
} catch (CheckException $ex) {
    Message::addWarning($ex->getMessage(), false);
} catch (Exception $ex) {
    Message::addError($ex->getMessage(), true);
}

$breadcrumbs = [RESET_MENU => $_SERVER['PHP_SELF']];

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
        <h1 class="h3 mb-3"><?php echo RESET_TITLE ?></h1>
        <?php if (!Functions::checkNull($checkReset)) { ?>
            <form class="needs-validation" novalidate action="<?php echo $_SERVER['PHP_SELF'] ?>?username=<?php echo $username ?>&access_key=<?php echo $access_key ?>" method="post">
                <div class="mb-3">
                    <label for="password"><?php echo PASSWORD_LABEL ?></label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="" value="" maxlength="50">
                    <div class="invalid-feedback">
                        <?php echo USER_CHECK_FIELD_PASSWORD_ERROR ?>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="password_confirm"><?php echo PASSWORD_CONFIRM_LABEL ?></label>
                    <input type="password" class="form-control" id="password_confirm" name="password_confirm" placeholder="" value="" maxlength="50">
                    <div class="invalid-feedback">
                        <?php echo USER_CHECK_FIELD_PASSWORD_CONFIRM_ERROR ?>
                    </div>
                </div>

                <hr class="mb-4">
                <input type="hidden" name="action" value="reset_submit">
                <button class="btn btn-primary btn-lg btn-block" type="submit"><?php echo RESET_SUBMIT_LABEL ?></button>
            </form>
            <?php
        }
    }
    ?>

    <hr>
</div>

<script src="assets/js/form-validation.js"></script>

<?php
require_once('includes/footer.php');
