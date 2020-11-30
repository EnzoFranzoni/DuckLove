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
        if ($action == 'category_insert') {
            
            $categoryUpdate = new Category(null, $_POST['name'], $_POST['description'], $_POST['published'], now);

            $categoryUpdate->insertCategory();

            $cleanForm = true;

            Message::addSuccess(CATEGORY_INSERT_FORM_SUCCESS, false);
        }

        if ($action == 'category_update') {

            $categoryUpdate = new Category(null, $_POST['name'], $_POST['description'], $_POST['published'], now);

            $categoryUpdate->updateCategory();

            Message::addSuccess(CATEGORY_UPDATE_FORM_SUCCESS, false);
        }

        if ($action == 'category_delete') {
            
            $categoryDelete = new Category($id);

            $categoryDelete->deleteCategory();

            Message::addSuccess(CATEGORY_DELETE_FORM_SUCCESS, false);
        }
        
        if ($action == 'category_load' && Functions::checkInteger($id)) {
        $categoryGet = new Category($id);
        $categoryGet->getCategory();
    }
    }

    

    $category = new Category;
    $categoryList = $category->getCategoryList();

    //$selectUserAccessList = Select::getUserAccessList();
    $selectStateList = Select::getStateList();
} catch (CheckException $ex) {
    Message::addWarning($ex->getMessage(), false);
} catch (Exception $ex) {
    Message::addError($ex->getMessage(), true);
}

if (isset($categoryGet)) {
    $action = 'category_update';

    $name = $categoryGet->name;
    $description = $categoryGet->description;
    $published = $categoryGet->published;
} else {
    $action = 'category_insert';

    $name = isset($_POST['name']) && !$cleanForm ? $_POST['name'] : '';
    $description = isset($_POST['description']) && !$cleanForm ? $_POST['description'] : '';
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
        <h1 class = "h3 mb-3"><?php echo CATEGORIES_TITLE ?></h1>
        <form class="form-inline" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th class="d-none">
                                <?php echo CATEGORY_LIST_ID_TAB; ?>
                            </th>
                            <th>
                                <?php echo CATEGORY_LIST_NAME_TAB; ?>
                            </th>
                            <th>
                                <?php echo CATEGORY_LIST_DESCRIPTION_TAB; ?>
                            </th>
                            <th>
                                <?php echo CATEGORY_LIST_PUBLISHED_TAB; ?>
                            </th>
                            <th>
                                <?php echo CATEGORY_LIST_CREATE_DATE_TAB; ?>
                            </th>
                            <th>
                                <?php echo CATEGORY_LIST_ACTION_TAB; ?>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categoryList as $category) { ?>
                            <tr>
                                <td class="d-none">
                                    <?php echo $category->id ?>
                                </td>
                                <td>
                                    <?php echo $category->name ?>
                                </td>
                                <td>
                                    <?php echo $category->description ?>
                                </td>
                                <td>
                                    <?php echo $selectStateList[$category->published] ?>
                                </td>
                                <td>
                                    <?php
                                    $category_create_date = new DateTime($category->create_date);
                                    echo $category_create_date->format('d/m/Y H:i');
                                    ?>
                                </td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="actions">
                                        <a href="<?php echo $_SERVER['PHP_SELF'] ?>action=category_load&?id=<?php echo $category->id ?>" class="btn btn-secondary">
                                            <i class="fa fa-pencil" aria-hidden="true"></i>
                                        </a>
                                        <a href="<?php echo $_SERVER['PHP_SELF'] ?>?action=category_delete&id=<?php echo $category->id ?>" class="btn btn-secondary">
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
                                <label class="sr-only" for="name"><?php echo CATEGORY_NAME_LABEL ?></label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="<?php echo CATEGORY_NAME_PLACEHOLDER ?>" value="<?php echo $name ?>">
                            </td>
                            <td>
                                <label class="sr-only" for="description"><?php echo CATEGORY_DESCRIPTION_LABEL ?></label>
                                <input type="text" class="form-control" id="description" name="description" placeholder="<?php echo CATEGORY_DESCRIPTION_PLACEHOLDER ?>" value="<?php echo $description ?>">
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
                                <button type="submit" class="btn btn-primary"><?php echo CATEGORY_SUBMIT_LABEL ?></button>
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
