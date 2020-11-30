<?php
require_once('require.php');

try {
    $category_id = null;
    if (isset($_GET['category_id']) && Functions::checkInteger($_GET['category_id'])) {
        $category_id = intval($_GET['category_id']);
    }

    $item = new Item;
    $item->published = 1;

    $total = $item->getItemTotal($category_id);

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

    $itemList = $item->getItemList($category_id, $offset, $count);

    $itemListCount = count($itemList);

    if ($itemListCount == 0) {
        Message::addWarning(ITEM_NOT_FOUND_WARNING, true);
    }

    $category = new Category($category_id);
    $cat = $category->getCategory();
} catch (Exception $ex) {
    Message::addError($ex->getMessage(), true);
}

$breadcrumbs = [ITEMS_MENU => $_SERVER['PHP_SELF']];

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
        <h1 class="h3 mb-3"><?php echo ITEMS_TITLE ?></h1>
        <p>
            <?php echo (!Functions::checkNull($cat) ? sprintf('%s : %s', $cat->name, $cat->description) : ALL_CATEGORIES) ?>
        </p>
        <?php
        foreach ($itemList as $i => $item) {
            ?>
            <div class="row">
                <div class="col-md-2">
                    <img src="assets/images/items/<?php echo $item->filename ?>" alt="<?php echo $item->title ?>" width="150" height="150">
                </div>
                <div class="col-md-7">
                    <h2 class="h5">
                        <a href="item.php?id=<?php echo $item->id ?>"><?php echo $item->title ?></a>
                    </h2>
                    <?php if (!Functions::checkNull($item->categories) && count($item->categories) > 0) { ?>
                        <p>
                            <span class="fa fa-folder-open"></span>
                            <?php
                            $categories = [];
                            foreach ($item->categories as $category) {
                                $categories[] = $category->name;
                            }
                            echo implode(' | ', $categories);
                            ?>
                        </p>
                    <?php } ?>
                    <?php if (!Functions::checkNull($item->create_date)) { ?>
                        <p>
                            <span class="fa fa-calendar"></span>
                            <?php
                            $item_create_date = new DateTime($item->create_date);
                            echo $item_create_date->format('d/m/Y H:i');
                            ?>
                        </p>
                    <?php } ?>
                    <?php if (!Functions::checkNull($item->creator)) { ?>
                        <p>
                            <span class="fa fa-user"></span> <?php echo sprintf('%s %s', $item->creator->first_name, $item->creator->last_name) ?>
                        </p>
                    <?php } ?>
                    <p>
                        <?php echo $item->description ?>
                    </p>
                </div>
                <div class="col-md-3">
                    <p>
                        <?php echo sprintf(COMMENT_LINK, count($item->comments)) ?>
                    </p>
                    <a class="btn btn-secondary" href="item.php?id=<?php echo $item->id ?>" role="button"><span class="fa fa-eye"></span> <?php echo ITEM_LINK ?></a>
                </div>
            </div>
            <hr>
            <?php
        }
        ?>
        <div class="row">
            <div class="col-md-9">
                <?php echo Pagination::paginate('items.php', '?' . ((!Functions::checkNull($category_id)) ? 'category_id=' . $category_id . '&' : '') . 'page=', $pageNum, $current, $count); ?>

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
