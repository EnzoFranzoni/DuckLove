<?php
require_once('require.php');

try {
    $item_id = null;
    if (isset($_GET['id']) && Functions::checkInteger($_GET['id'])) {
        $item_id = intval($_GET['id']);
    }

    if (isset($_POST['action']) && $_POST['action'] == 'comment_submit') {
        try {
            $comment = new Comment(null, $_POST['message'], 1, $item_id, $user_session->id, now);

            $comment->insertComment();

            Message::addSuccess(COMMENT_FORM_SUCCESS, false);
        } catch (CheckException $ex) {
            Message::addWarning($ex->getMessage(), false);
        }
    }

    $checkItem = null;

    $item = new Item($item_id);
    $item->published = 1;

    $checkItem = $item->getItem();

    if (Functions::checkNull($checkItem)) {
        throw new CheckException(ITEM_NOT_FOUND_WARNING);
    }
} catch (CheckException $ex) {
    Message::addWarning($ex->getMessage(), false);
} catch (Exception $ex) {
    Message::addError($ex->getMessage(), true);
}

$breadcrumbs = [ITEMS_MENU => 'items.php', ITEM_MENU => $_SERVER['PHP_SELF'] . '?id=' . $item_id];

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
        <h1 class="h3 mb-3"><?php echo ITEM_TITLE ?></h1>
        <?php if (!Functions::checkNull($checkItem)) { ?>
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
                        <?php echo $item->details ?>
                    </p>
                </div>
                <div class="col-md-3">
                    <p>
                        <?php echo sprintf(COMMENT_LINK, count($item->comments)) ?>
                    </p>
                </div>
            </div>
            <hr>
            <div class="text-center">
                <?php
                if (count($item->comments) > 0) {
                    foreach ($item->comments as $comment) {
                        $comment_create_date = new DateTime($comment->create_date);
                        ?>
                        <blockquote class="blockquote">
                            <p class="mb-0">
                                <?php echo $comment->message ?>
                            </p>
                            <footer class="blockquote-footer">
                                <?php echo sprintf(COMMENT_BLOCKQUOTE, $comment->creator->first_name, $comment->creator->last_name, $comment_create_date->format('d/m/Y H:i')) ?>
                            </footer>
                        </blockquote>
                        <?php
                    }
                } else {
                    echo ITEM_COMMENT_NOT_FOUND_MESSAGE;
                }
                ?>
            </div>
            <hr>

            <?php if (!Functions::checkNull($user_session)) { ?>
                <h3 class="h5 mb-3">
                    <?php echo COMMENT_TITLE ?>
                </h3>
                <form class="needs-validation" novalidate action="<?php echo $_SERVER['PHP_SELF'] ?>?id=<?php echo $item_id ?>" method="post">
                    <div class="mb-3">
                        <label for="message"><?php echo MESSAGE_LABEL ?></label>
                        <textarea class="form-control" id="message" name="message" rows="3" required></textarea>
                        <div class="invalid-feedback">
                            <?php echo COMMENT_CHECK_FIELD_MESSAGE_ERROR ?>
                        </div>
                    </div>

                    <hr class="mb-4">
                    <input type="hidden" name="action" value="comment_submit">
                    <button class="btn btn-primary btn-lg btn-block" type="submit"><?php echo COMMENT_SUBMIT_LABEL ?></button>
                </form>
                <?php
            } else {
                echo ITEM_COMMENT_NOT_LOGIN_MESSAGE;
            }
        }
    }
    ?>

    <hr>
</div>

<script src="assets/js/form-validation.js"></script>

<?php
require_once('includes/footer.php');
