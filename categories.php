<?php
require_once('require.php');

try {
    $category = new Category;
    $category->published = 1;

    $total = $category->getCategoryTotal();

    $count = DEFAULT_ROWS_PER_PAGE;

    $pageNum = ceil($total / $count);

    $current = 1;
    $page = $current;
    if (isset($_GET['page']) && Functions::checkInteger($_GET['page'])) {
        $page = intval($_GET['page']);
        if ($page >= 1 && $page <= $pageNum) {
            $current = $page;
        } else if ($page < 1) {
            $current = 1;
        } else {
            $current = $pageNum;
        }
    }
    $offset = ($current * $count - $count);

    $categoryList = $category->getItemCategoryList(null, $offset, $count);

    $categoryListCount = count($categoryList);

    if ($categoryListCount == 0) {
        Message::addWarning(CATEGORY_NOT_FOUND_WARNING, true);
    }
} catch (Exception $ex) {
    Message::addError($ex->getMessage(), true);
}

$breadcrumbs = [CATEGORIES_MENU => $_SERVER['PHP_SELF']];

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
        <h1 class="h3 mb-3"><?php echo CATEGORIES_TITLE ?></h1>
        <?php
        foreach ($categoryList as $i => $category) {
            ?>
            <div class="row">
                <div class="col-md-9">
                    <h2 class="h5">
                        <a href="items.php?category_id=<?php echo $category->id ?>"><?php echo $category->name ?></a>
                    </h2>
                    <p>
                        <?php echo $category->description ?>
                    </p>
                    <?php if (!Functions::checkNull($category->create_date)) { ?>
                        <p>
                            <span class="fa fa-calendar"></span>
                            <?php
                            $category_create_date = new DateTime($category->create_date);
                            echo $category_create_date->format('d/m/Y H:i');
                            ?>
                        </p>
                    <?php } ?>
                </div>
                <div class="col-md-3">
                    <a class="btn btn-secondary" href="items.php?category_id=<?php echo $category->id ?>" role="button"><span class="fa fa-eye"></span> <?php echo CATEGORY_LINK ?></a>
                </div>
            </div>
            <hr>
            <?php
        }
        ?>
        <div class="row">
            <div class="col-md-9">
                <?php echo Pagination::paginate('items.php', '?page=', $pageNum, $current, $count); ?>

            </div>
            <div class="col-md-3">
                <?php echo Pagination::count($pageNum, $current, $total); ?>
            </div>
        </div>

    <?php } ?>

    <hr>
</div>

<?php
require_once('includes/footer.php');
