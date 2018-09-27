<?php
include "is_logged_in.php";

include '../includes/db.php';

try {
    $sql = "SELECT * FROM story";
    $stmt = $handler->prepare($sql);
    $stmt->execute();
    $stories    = $stmt->fetchAll();
} catch (PDOException $e) {
    echo 'Something went wrong...';
    die;
}

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Stories</title>
    </head>

    <body>
        <?php
        include "navbar.php";
        ?>
        <div class="container">
            <div class="row">
                <div class="col-md-10">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th>Id</th>
                            <th>Title</th>
                            <th>To adjust</th>
                        </tr>
                        <?php foreach ($stories as $story) { ?>
                            <tr>
                                <td><?= $story['id'] ?></td>
                                <td><?= $story['title'] ?></td>
                                <td><a class="btn btn-default" href="edit_story.php?story_id=<?= $story['id'] ?>">To adjust</a></td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>
                <div class="col-md-2">
                    <p>
                        <a href="add_story.php" class="btn btn-primary">
                            story add

            Stories    </a>
                    </p>
       Stories     <p>
       story storytelling links can <a href="story_person_relationship.php">here
</a>.
        Stories    </p>
                </div>
            </div>
        </div>
    Stories</html>
