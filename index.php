<?php
require_once('require.php');

try {
    $item = new Item;
    $item->published = 1;

    $itemList = $item->getItemList(null, 0, 3);

    $itemListCount = count($itemList);

    if ($itemListCount == 0) {
        Message::addWarning(ITEM_NOT_FOUND_WARNING, false);
    }
} catch (Exception $ex) {
    Message::addError($ex->getMessage(), true);
}

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
        <h1 class="h3 mb-3"><?php echo PROJECT_NAME ?></h1>
        <p>
            <?php echo WELCOME_MESSAGE ?>
        </p>
        <div class="row">
            <?php
            foreach ($itemList as $i => $item) {
                ?>
                <div class="col-md-4">
                    <h2><?php echo $item->title ?></h2>
                    <p>
                        <?php echo $item->description ?>
                    </p>
                    <p>
                        <a class="btn btn-secondary" href="item.php?id=<?php echo $item->id ?>" role="button">Voir les détails &raquo;</a>
                    </p>
                </div>
            <?php } ?>
        </div>
    <?php } ?>

    <hr>
</div>

<?php
require_once('includes/footer.php');
