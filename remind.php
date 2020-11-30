<?php
require_once('require.php');

$cleanForm = false;

try {
    if (isset($_POST['action']) && $_POST['action'] == 'remind_submit') {
        $user = new User(null, null, null, null, null, $_POST['email']);

        $checkAccount = $user->getUser();

        if (Functions::checkNull($checkAccount)) {
            throw new CheckException(USER_CHECK_ACCOUNT_USERNAME_PASSWORD_ERROR);
        }

        $user->access_key = Functions::generateUserPassword(32);

        $user->updateUser();

        $resetFormUrl = sprintf('%sreset.php?username=%s&access_key=%s', live_site, $user->username, $user->access_key);

        Functions::sendMail($user->email, REMIND_MAIL_SUBJECT, sprintf(REMIND_MAIL_BODY, PROJECT_NAME, $resetFormUrl));

        $cleanForm = true;

        Message::addSuccess(REMIND_FORM_SUCCESS, false);
    }
} catch (CheckException $ex) {
    Message::addWarning($ex->getMessage(), false);
} catch (Exception $ex) {
    Message::addError($ex->getMessage(), true);
}

$email = isset($_POST['email']) && !$cleanForm ? $_POST['email'] : '';

$breadcrumbs = [REMIND_MENU => $_SERVER['PHP_SELF']];

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
        <h1 class="h3 mb-3"><?php echo REMIND_TITLE ?></h1>
        <form class="needs-validation" novalidate action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
            <div class="mb-3">
                <label for="email"><?php echo EMAIL_LABEL ?></label>
                <input type="email" class="form-control" id="email" name="email" placeholder="" value="<?php echo $email ?>" maxlength="100" required>
                <div class="invalid-feedback">
                    <?php echo USER_CHECK_FIELD_EMAIL_ERROR ?>
                </div>
            </div>

            <hr class="mb-4">
            <input type="hidden" name="action" value="remind_submit">
            <button class="btn btn-primary btn-lg btn-block" type="submit"><?php echo REMIND_SUBMIT_LABEL ?></button>
        </form>
    <?php } ?>

    <hr>
</div>

<script src="assets/js/form-validation.js"></script>

<?php
require_once('includes/footer.php');
