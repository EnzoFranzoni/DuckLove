<?php
require_once('require.php');

if (Functions::checkNull($user_session)) {
    header('Location: login.php?error=1');
}

try {
    $user = new User($user_session->id);

    $checkAccount = $user->getUser();

    if (Functions::checkNull($checkAccount)) {
        throw new Exception(USER_CHECK_ACCOUNT_USERNAME_PASSWORD_ERROR);
    }

    $email = $_POST['email'] ?? $user->email;

    if (isset($_POST['action']) && $_POST['action'] == 'account_submit') {

        if (!Functions::checkStringOrNull($_POST['password'])) {
            if (Functions::checkStringOrNull($_POST['password_confirm'])) {
                throw new CheckException(USER_CHECK_FIELD_PASSWORD_CONFIRM_ERROR);
            } else if ($_POST['password'] != $_POST['password_confirm']) {
                throw new CheckException(USER_CHECK_FIELD_PASSWORD_NOT_MATCH_ERROR);
            }

            $user->password = Functions::encryptDataMd5($_POST['password']);
        }

        $user->email = $_POST['email'];

        $user->updateUser();

        $_SESSION['user'] = serialize($user);

        Message::addSuccess(ACCOUNT_FORM_SUCCESS, false);
    }
} catch (CheckException $ex) {
    Message::addWarning($ex->getMessage(), false);
} catch (Exception $ex) {
    Message::addError($ex->getMessage(), true);
}

$breadcrumbs = [ACCOUNT_MENU => $_SERVER['PHP_SELF']];

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
        <h1 class="h3 mb-3"><?php echo ACCOUNT_TITLE ?></h1>
        <form class="needs-validation" novalidate action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
            <div class="mb-3">
                <label for="email"><?php echo EMAIL_LABEL ?></label>
                <input type="email" class="form-control" id="email" name="email" placeholder="" value="<?php echo $email ?>" maxlength="100" required>
                <div class="invalid-feedback">
                    <?php echo USER_CHECK_FIELD_EMAIL_ERROR ?>
                </div>
            </div>

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
            <input type="hidden" name="action" value="account_submit">
            <button class="btn btn-primary btn-lg btn-block" type="submit"><?php echo ACCOUNT_SUBMIT_LABEL ?></button>
        </form>
    <?php } ?>

    <hr>
</div>

<script src="assets/js/form-validation.js"></script>

<?php
require_once('includes/footer.php');
