<?php
include "is_logged_in.php";
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        include '../includes/db.php';

        if (isset($_POST['story_title']) && isset($_POST['story_text']) && !empty($_POST['story_title']) && !empty($_POST['story_text'])) {
            $sql = 'INSERT INTO story (title, text) VALUES (?, ?)';
            $stmt = $handler->prepare($sql);

            if ($stmt->execute(array($_POST['story_title'], $_POST['story_text']))) {
                $success_message = true;
            } else {
                $error_messages = array('something went wrong when adding the story');
            }
        } else {
            $error_messages = array();
            if (empty($_POST['story_title'])) {
                $error_messages[] = 'A story title is mandatory';
            }
            if (empty($_POST['story_text'])) {
                $error_messages[] = 'A story text is mandatory';
            }
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>FamilyTree login</title>
        <link href="../css/bootstrap/bootstrap.min.css" rel="stylesheet">
<!--        <link href="../css/style.css" rel="stylesheet">-->
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
        <style>
            .story-form {
                margin-top: 50px;
            }
        </style>
    </head>
    <body>
        <?php include "navbar.php"; ?>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="text-center">add a story</h1>
                    <p class="text-center">or go back to it<a href="login.php">control panel</a>.</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-8 story-form">
                    <form action="" method="post">
                        <?php if (isset($error_messages)) { ?>
                            <div class="alert alert-danger">
                            <?php
                                foreach ($error_messages as $message) {
                                echo '<p>'.$message.'</p>';
                            }
                            ?>
                            </div>
                        <?php } ?>
                        <?php if (isset($success_message) && $success_message === true) {?>
                            <div class="alert alert-success">
                            The post has been successfully entered into the database !
                            </div>
                        <?php
                        }
                        unset($_POST['story_text']);
                        unset($_POST['story_title']);
                        ?>
                        <div class="form-group">
                            <label for="storyTitle">Story title</label>
                            <?php if (isset($_POST['story_title'])) { ?>
                                <input type="text" class="form-control" id="storytitle" name="story_title" placeholder="Title" value="<?= $_POST['story_title'] ?>">
                            <?php } else { ?>
                                <input type="text" class="form-control" id="storytitle" name="story_title" placeholder="Title">
                            <?php }?>
                        </div>
                        <textarea id="story_text" title="story_text" name="story_text">
                            <?php if (isset($_POST['story_text'])) {
                                echo $_POST['story_text'];
                            }?>
                        </textarea>
                        <input type="submit" class="btn btn-default" value="story save">
                    </form>
                </div>
                <div class="col-md-2"></div>
            </div>
        </div>
    </body>
</html>
