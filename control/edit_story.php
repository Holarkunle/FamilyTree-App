<?php
include "is_logged_in.php";

include '../includes/db.php';

try {

    if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['story_id'])) {
        $story = getstory($_GET['story_id']);
    } elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['story_id'])) {
        if (isset($_POST['story_title']) && isset($_POST['story_text'])) {
            $sql = "UPDATE story SET title=?, text=? WHERE id=?";
            $stmt = $handler->prepare($sql);
            $stmt->execute([
                $_POST['story_title'],
                $_POST['story_text'],
                $_POST['story_id']
            ]);
            $story = getstory($_POST['story_id']);
        } else {
            $story = array();
        }
    } else {
        $story = array();
    }
} catch (PDOException $e) {
    echo 'Something went wrong...';
    die;
}

function getstory($story_id) {
    global $handler;
    $sql = "SELECT * FROM story WHERE id=?";
    $stmt = $handler->prepare($sql);
    $stmt->execute([$story_id]);
    return $stmt->fetchAll();
}

if (isset($_POST['remove'])) {
    $sql3 = "DELETE FROM story WHERE id = ?";
    $pd = $handler->prepare($sql3);
    $pd->execute(array($_GET['story_id']));
    header('Location: stories.php');
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>story To adjust</title>
        <script src="../js/bootstrap.min.js"></script>
        <script src="../js/jquery.js"></script>
        <script src="../js/tinymce_dev/tinymce/js/tinymce/tinymce.min.js"></script>
        <script>
            tinymce.init({
                selector: "#story_text",
                height: 150,
                plugins: [
                    "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
                    "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                    "save table contextmenu directionality emoticons template paste textcolor"
                ],
                //content_css: "../css/forum.css",
                toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons",
                style_formats: [
                    {title: 'Bold text', inline: 'b'},
                    {title: 'Red text', inline: 'span', styles: {color: '#ff0000'} },
                    {title: 'Red header', block: 'h1', styles: {color: '#ff0000'} },
                    {title: 'Example 1', inline: 'span', classes: 'example1'},
                    {title: 'Example 2', inline: 'span', classes: 'example2'},
                    {title: 'Table styles'},
                    {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
                ]
            });
        </script>
    </head>
    <body>
        <?php include "navbar.php"; ?>
        <div class="container">
            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    <?php
                    if (count($story) == 0) { ?>
                        <div class="alert alert-danger">
                            <p>story not found. Go back to<a href="login.php">control panel</a>.</p>
                        </div>
                        <?php
                    } else {
                        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                            ?>
                            <div class="alert alert-success">
                                <p>Successfully adapted this story!</p>
                            </div>
                            <?php
                        }
                        ?>
                        <form action="" method="post">
                            <input type="hidden" name="story_id" value="<?= $story[0]['id'] ?>">
                            <div class="form-group">
                                <label for="storytitle">story title</label>
                                <input type="text" class="form-control" id="storytitle" name="story_title" placeholder="title" value="<?= $story[0]['title']; ?>">
                            </div>
                            <textarea id="story_text" title="story_text" name="story_text">
                                <?= $story[0]['text']; ?>
                            </textarea>
                            <input type="submit" class="btn btn-default" value="story save">
                            <button class="btn btn-danger" type="submit" name="remove">remove</button>
                        </form>
                        <?php
                    }
                    ?>
                </div>
                <div class="col-md-2"></div>
            </div>
        </div>
    </body>
</html>
