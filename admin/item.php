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
        if ($action == 'item_insert') {

            $itemUpdate = new Item(null, $_POST['name'], $_POST['description'], $_POST['published'], now);

            $itemUpdate->insertItem();

            $cleanForm = true;

            Message::addSuccess(ITEM_INSERT_FORM_SUCCESS, false);
        }

        if ($action == 'item_update') {

            $itemUpdate = new Category(null, $_POST['name'], $_POST['description'], $_POST['published'], now);

            $itemUpdate->updateItem();

            Message::addSuccess(ITEM_UPDATE_FORM_SUCCESS, false);
        }

        if ($action == 'item_delete') {

            $itemDelete = new Item($id);

            $itemDelete->deleteCategory();

            Message::addSuccess(ITEM_DELETE_FORM_SUCCESS, false);
        }

        if ($action == 'item_load' && Functions::checkInteger($id)) {
            $itemGet = new Item($id);
            $itemGet->getItem();
        }
    }



    $item = new Item;
    $itemList = $item->getItemList();

    //$selectUserAccessList = Select::getUserAccessList();
    $selectStateList = Select::getStateList();
   
} catch (CheckException $ex) {
    Message::addWarning($ex->getMessage(), false);
} catch (Exception $ex) {
    Message::addError($ex->getMessage(), true);
}

if (isset($categoryGet)) {
    $action = 'item_update';

    $title = $itemGet->title;
    $description = $itemyGet->description;
    $details = $itemGet->details;
    $published = $itemGet->published;
    $create_user_id = $itemGet->create_user_id;
    
    
} else {
    $action = 'item_insert';

    $name = isset($_POST['title']) && !$cleanForm ? $_POST['title'] : '';
    $description = isset($_POST['description']) && !$cleanForm ? $_POST['description'] : '';
    $details = isset($_POST['details']) && !$cleanForm ? $_POST['details'] : '';
    $published = isset($_POST['published']) && !$cleanForm ? $_POST['published'] : '';
    $create_user_id = isset($_POST['create_user_id']) && !$cleanForm ? $_POST['create_user_id'] : '';
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
        <h1 class = "h3 mb-3"><?php echo ITEM_TITLE ?></h1>
        <form class="form-inline" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th class="d-none">
                                <?php echo ITEM_LIST_ID_TAB; ?>
                            </th>
                            <th>
                                <?php echo ITEM_LIST_TITLE_TAB; ?>
                            </th>
                            <th>
                                <?php echo ITEM_LIST_DESCRIPTION_TAB; ?>
                            </th>
                            <th>
                                <?php echo ITEM_LIST_DETAILS_TAB; ?>
                            </th>
                            <th>
                                <?php echo ITEM_LIST_PUBLISHED_TAB; ?>
                            </th>
                            <th>
                                <?php echo ITEM_LIST_CREATE_DATE_TAB; ?>
                            </th>
                            <th>
                                <?php echo ITEM_LIST_CREATE_USER_ID_TAB; ?>
                            </th>
                            <th>
                                <?php echo ITEM_LIST_ACTION_TAB; ?>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($itemList as $item) { ?>
                            <tr>
                                <td class="d-none">
                                    <?php echo $item->id ?>
                                </td>
                                <td>
                                    <?php echo $item->title ?>
                                </td>
                                <td>
                                    <?php echo $item->description ?>
                                </td>
                                <td>
                                    <?php echo $item->details ?>
                                </td>
                                <td>
                                    <?php echo $selectStateList[$item->published] ?>
                                </td>
                                <td>
                                    <?php
                                    $item_create_date = new DateTime($item->create_date);
                                    echo $item_create_date->format('d/m/Y H:i');
                                    ?>
                                </td>
                                <td>
                                    <?php echo $item->creator->last_name ?>
                                </td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="actions">
                                        <a href="<?php echo $_SERVER['PHP_SELF'] ?>action=item_load&?id=<?php echo $item->id ?>" class="btn btn-secondary">
                                            <i class="fa fa-pencil" aria-hidden="true"></i>
                                        </a>
                                        <a href="<?php echo $_SERVER['PHP_SELF'] ?>?action=item_delete&id=<?php echo $item->id ?>" class="btn btn-secondary">
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
                                <label class="sr-only" for="title"><?php echo ITEM_TITLE_LABEL ?></label>
                                <input type="text" class="form-control" id="title" name="title" placeholder="<?php echo ITEM_TITLE_PLACEHOLDER ?>" value="<?php echo $name ?>">
                            </td>
                            <td>
                                <label class="sr-only" for="description"><?php echo ITEM_DESCRIPTION_LABEL ?></label>
                                <input type="text" class="form-control" id="description" name="description" placeholder="<?php echo ITEM_DESCRIPTION_PLACEHOLDER ?>" value="<?php echo $description ?>">
                            </td>
                            <td>
                                <label class="sr-only" for="details"><?php echo ITEM_DETAILS_LABEL ?></label>
                                <input type="text" class="form-control" id="details" name="details" placeholder="<?php echo ITEM_DETAILS_PLACEHOLDER ?>" value="<?php echo $details ?>">
                            </td>
                            <td>
                                <label class="sr-only" for="published"><?php echo PUBLISHED_LABEL ?></label>
                                <select class="form-control" id="published" name="published">
                                    <option value=""><?php echo SELECT_OPTION_NONE; ?></option>
                                    <?php foreach ($selectStateList as $selectStateValue => $selectStateText) : ?>
                                        <option value="<?php echo $selectStateValue; ?>"<?php echo $published == $selectStateValue ? ' selected="selected"' : ''; ?>>
                                            <?php echo $selectStateText; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td>

                            </td>
                            <td>
                                <input type="hidden" name="action" value="<?php echo $action ?>">
                                <button type="submit" class="btn btn-primary"><?php echo ITEM_SUBMIT_LABEL ?></button>
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
