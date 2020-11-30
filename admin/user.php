<?php
require_once('../require.php');

if (Functions::checkNull($user_session)) {
    header('Location: login.php?error=1');
}

$id = $_POST['id'] ?? $_GET['id'] ?? null;
$action = $_POST['action'] ?? $_GET['action'] ?? null;
$cleanForm = false;

try {
    if (!Functions::checkNull($action)) {
        if ($action == 'user_insert') {
            try {
                $cryptedPassword = Functions::encryptDataMd5($_POST['password']);

                $userUpdate = new User(null, $_POST['username'], $cryptedPassword, $_POST['last_name'], $_POST['first_name'], $_POST['email'], $_POST['user_access_id'], $_POST['published'], now);

                $userUpdate->insertUser();

                $cleanForm = true;

                Message::addSuccess(USER_INSERT_FORM_SUCCESS, false);
            } catch (CheckException $ex) {
                Message::addWarning($ex->getMessage(), false);
            }
        }

        if ($action == 'user_update') {
            try {
                $cryptedPassword = Functions::encryptDataMd5($_POST['password']);

                $userUpdate = new User($id, $_POST['username'], $cryptedPassword, $_POST['last_name'], $_POST['first_name'], $_POST['email'], $_POST['user_access_id'], $_POST['published'], now);

                $userUpdate->updateUser();

                Message::addSuccess(USER_UPDATE_FORM_SUCCESS, false);
            } catch (CheckException $ex) {
                Message::addWarning($ex->getMessage(), false);
            }
        }

        if ($action == 'user_delete') {
            try {
                if ($id == $user_session->id) {
                    throw new CheckException(USER_AUTO_DELETE_WARNING);
                }

                $userDelete = new User($id);

                $userDelete->deleteUser();

                Message::addSuccess(USER_DELETE_FORM_SUCCESS, false);
            } catch (CheckException $ex) {
                Message::addWarning($ex->getMessage(), false);
            }
        }

        if ($action == 'user_load' && Functions::checkInteger($id)) {
            $userGet = new User($id);
            $userGet->getUser();
        }
    }

    $user = new User;
    $userList = $user->getUserList();

    $selectUserAccessList = Select::getUserAccessList();
    $selectStateList = Select::getStateList();
} catch (CheckException $ex) {
    Message::addWarning($ex->getMessage(), false);
} catch (Exception $ex) {
    Message::addError($ex->getMessage(), true);
}

if (isset($userGet)) {
    $action = 'user_update';

    $username = $userGet->username;
    $password = $userGet->password;
    $last_name = $userGet->last_name;
    $first_name = $userGet->first_name;
    $email = $userGet->email;
    $user_access_id = $userGet->user_access_id;
    $published = $userGet->published;
} else {
    $action = 'user_insert';

    $username = isset($_POST['username']) && !$cleanForm ? $_POST['username'] : '';
    $password = isset($_POST['password']) && !$cleanForm ? $_POST['password'] : '';
    $last_name = isset($_POST['last_name']) && !$cleanForm ? $_POST['last_name'] : '';
    $first_name = isset($_POST['first_name']) && !$cleanForm ? $_POST['first_name'] : '';
    $email = isset($_POST['email']) && !$cleanForm ? $_POST['email'] : '';
    $user_access_id = isset($_POST['user_access_id']) && !$cleanForm ? $_POST['user_access_id'] : '';
    $published = isset($_POST['published']) && !$cleanForm ? $_POST['published'] : '';
}

$breadcrumbs = ['Administration' => 'admin.php', 'Utilisateurs' => 'admin/user.php'];

require_once('../includes/header.php');
?>

<div class="container-fluid">

    <?php
    Message::echoSuccesses();
    Message::echoWarnings();
    Message::echoErrors();
    Message::echoInfos();

    if (!Message::isBlocking()) {
        ?>
        <h1 class = "h3 mb-3"><?php echo USER_LIST_TITLE ?></h1>
        <form class="form-inline" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th class="d-none">
                                <?php echo USER_LIST_ID_TAB; ?>
                            </th>
                            <th>
                                <?php echo USER_LIST_USERNAME_TAB; ?>
                            </th>
                            <th>
                                <?php echo USER_LIST_PASSWORD_TAB; ?>
                            </th>
                            <th>
                                <?php echo USER_LIST_LAST_NAME_TAB; ?>
                            </th>
                            <th>
                                <?php echo USER_LIST_FIRST_NAME_TAB; ?>
                            </th>
                            <th>
                                <?php echo USER_LIST_EMAIL_TAB; ?>
                            </th>
                            <th>
                                <?php echo USER_LIST_USER_ACCESS_TAB; ?>
                            </th>
                            <th>
                                <?php echo USER_LIST_PUBLISHED_TAB; ?>
                            </th>
                            <th>
                                <?php echo USER_LIST_CREATE_DATE_TAB; ?>
                            </th>
                            <th>
                                <?php echo USER_LIST_ACTION_TAB; ?>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($userList as $user) { ?>
                            <tr class="<?php echo ($user->id == $user_session->id ? 'table-active' : '') ?>">
                                <td class="d-none">
                                    <?php echo $user->id ?>
                                </td>
                                <td>
                                    <?php echo $user->username ?>
                                </td>
                                <td>
                                    ***
                                </td>
                                <td>
                                    <?php echo $user->last_name ?>
                                </td>
                                <td>
                                    <?php echo $user->first_name ?>
                                </td>
                                <td>
                                    <?php echo $user->email ?>
                                </td>
                                <td>
                                    <?php
                                    $userAccess = new UserAccess($user->user_access_id);
                                    echo $userAccess->getUserAccess()->name;
                                    ?>
                                </td>
                                <td>
                                    <?php echo $selectStateList[$user->published] ?>
                                </td>
                                <td>
                                    <?php
                                    $user_create_date = new DateTime($user->create_date);
                                    echo $user_create_date->format('d/m/Y H:i');
                                    ?>
                                </td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="actions">
                                        <a href="<?php echo $_SERVER['PHP_SELF'] ?>?action=user_load&id=<?php echo $user->id ?>" class="btn btn-secondary">
                                            <i class="fa fa-pencil" aria-hidden="true"></i>
                                        </a>
                                        <a href="<?php echo $_SERVER['PHP_SELF'] ?>?action=user_delete&id=<?php echo $user->id ?>" class="btn btn-secondary <?php echo ($user->id == $user_session->id ? 'disabled' : '') ?>">
                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td class="d-none">
                                <label class="sr-only" for="id"><?php echo ID_LABEL ?></label>
                                <input type="hidden" class="form-control" id="id" name="id" placeholder="<?php echo ID_PLACEHOLDER ?>" value="<?php echo $id ?>">
                            </td>
                            <td>
                                <label class="sr-only" for="username"><?php echo USERNAME_LABEL ?></label>
                                <input type="text" class="form-control" id="username" name="username" placeholder="<?php echo USERNAME_PLACEHOLDER ?>" value="<?php echo $username ?>">
                            </td>
                            <td>
                                <label class="sr-only" for="password"><?php echo PASSWORD_LABEL ?></label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="<?php echo PASSWORD_PLACEHOLDER ?>" value="<?php echo $password ?>">
                            </td>
                            <td>
                                <label class="sr-only" for="last_name"><?php echo LAST_NAME_LABEL ?></label>
                                <input type="text" class="form-control" id="last_name" name="last_name" placeholder="<?php echo LAST_NAME_PLACEHOLDER ?>" value="<?php echo $last_name ?>">
                            </td>
                            <td>
                                <label class="sr-only" for="first_name"><?php echo FIRST_NAME_LABEL ?></label>
                                <input type="text" class="form-control" id="first_name" name="first_name" placeholder="<?php echo FIRST_NAME_PLACEHOLDER ?>" value="<?php echo $first_name ?>">
                            </td>
                            <td>
                                <label class="sr-only" for="email"><?php echo EMAIL_LABEL ?></label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="<?php echo EMAIL_PLACEHOLDER ?>" value="<?php echo $email ?>">
                            </td>
                            <td>
                                <label class="sr-only" for="user_access_id"><?php echo USER_ACCESS_PLACEHOLDER ?></label>
                                <select class="form-control" id="user_access_id" name="user_access_id">
                                    <option value=""><?php echo SELECT_OPTION_NONE; ?></option>
                                    <?php foreach ($selectUserAccessList as $selectUserAccess) : ?>
                                        <option value="<?php echo $selectUserAccess->value; ?>"<?php echo $user_access_id == $selectUserAccess->value ? ' selected="selected"' : ''; ?>>
                                            <?php echo $selectUserAccess->text; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td>
                                <label class="sr-only" for="published"><?php echo PUBLISHED_LABEL ?></label>
                                <select class="form-control" id="published" name="published">
                                    <option value=""><?php echo SELECT_OPTION_NONE; ?></option>
                                    <?php foreach ($selectStateList as $selectStateValue => $selectStateText) : ?>
                                        <option value="<?php echo $selectStateValue; ?>"<?php echo (!Functions::checkStringOrNull($published) && $published == $selectStateValue ? ' selected="selected"' : ''); ?>>
                                            <?php echo $selectStateText; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td>

                            </td>
                            <td>
                                <input type="hidden" name="action" value="<?php echo $action ?>">
                                <button type="submit" class="btn btn-primary"><?php echo USER_SUBMIT_LABEL ?></button>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </form>
    <?php } ?>

    <hr>
</div>

<?php
require_once('../includes/footer.php');
