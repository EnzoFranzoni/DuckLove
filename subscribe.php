<?php
require_once('require.php');

$cleanForm = false;

if (isset($_POST['action']) && $_POST['action'] == 'subscribe_submit') {
    try {
        $pdo = Db::getInstance();

        $pdo->beginTransaction();

        $user = new User(null, null, null, $_POST['last_name'], $_POST['first_name'], $_POST['email'], 3, 1, now);

        $user->username = Functions::generateUsername($_POST['last_name'], $_POST['first_name']);
        $decryptedPassword = Functions::generateUserPassword(8);
        $user->password = Functions::encryptDataMd5($decryptedPassword);

        $checkUsername = $user->getUser();

        if (!Functions::checkNull($checkUsername)) {
            throw new CheckException(SUBSCRIBE_CHECK_USERNAME_ERROR);
        }

        $user->insertUser();

        Functions::sendMail($user->email, SUBSCRIBE_MAIL_SUBJECT, sprintf(SUBSCRIBE_MAIL_BODY, PROJECT_NAME, $user->username, $decryptedPassword));

        $cleanForm = true;

        $pdo->commit();

        Message::addSuccess(SUBSCRIBE_FORM_SUCCESS, false);
    } catch (CheckException $ex) {
        Message::addWarning($ex->getMessage(), false);
    } catch (Exception $ex) {
        if (isset($pdo) && $pdo->inTransaction()) {
            $pdo->rollBack();
        }

        Message::addError($ex->getMessage(), true);
    }
}

$last_name = isset($_POST['last_name']) && !$cleanForm ? $_POST['last_name'] : '';
$first_name = isset($_POST['first_name']) && !$cleanForm ? $_POST['first_name'] : '';
$email = isset($_POST['email']) && !$cleanForm ? $_POST['email'] : '';

$breadcrumbs = [SUBSCRIBE_MENU => $_SERVER['PHP_SELF']];

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
        <h1 class="h3 mb-3"><?php echo SUBSCRIBE_TITLE ?></h1>
        <form class="needs-validation" novalidate action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="last_name"><?php echo LAST_NAME_LABEL ?></label>
                    <input type="text" class="form-control" id="last_name" name="last_name" placeholder="" value="<?php echo $last_name ?>" maxlength="50" required>
                    <div class="invalid-feedback">
                        <?php echo USER_CHECK_FIELD_LASTNAME_ERROR ?>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="first_name"><?php echo FIRST_NAME_LABEL ?></label>
                    <input type="text" class="form-control" id="first_name" name="first_name" placeholder="" value="<?php echo $first_name ?>" maxlength="50" required>
                    <div class="invalid-feedback">
                        <?php echo USER_CHECK_FIELD_FIRSTNAME_ERROR ?>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="email"><?php echo EMAIL_LABEL ?></label>
                <input type="email" class="form-control" id="email" name="email" placeholder="" value="<?php echo $email ?>" maxlength="100" required>
                <div class="invalid-feedback">
                    <?php echo USER_CHECK_FIELD_EMAIL_ERROR ?>
                </div>
            </div>

            <hr class="mb-4">
            <input type="hidden" name="action" value="subscribe_submit">
            <button class="btn btn-primary btn-lg btn-block" type="submit"><?php echo SUBSCRIBE_SUBMIT_LABEL ?></button>
        </form>
    <?php } ?>

    <hr>
</div>

<script src="assets/js/form-validation.js"></script>

<?php
require_once('includes/footer.php');
