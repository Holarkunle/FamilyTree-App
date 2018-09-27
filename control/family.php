<?php
    include "../includes/db.php";
    include "is_logged_in.php";

    $sql = "SELECT * FROM person";
    $pd = $handler->prepare($sql);
    $pd->execute();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>family trail</title>
    </head>
    <body>
        <?php include "navbar.php"; ?>
        <div class="container">
            <div class="row">
                <div class="col-md-10">
                    <table class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <td>firstname</td>
                            <td>lastname</td>
                            <td>Year of birth - year of death</td>
                            <td>edit</td>
                        </tr>
                        </thead>
                        <tbody>
                            <?php foreach($pd->fetchAll() as $person): ?>
                                <tr>
                                    <td><?= $person['firstname']; ?></td>
                                    <td><?= $person['lastname']; ?></td>
                                    <td><?= $person['dateofbirth']; ?></td>
                                    <td>
                                        <a href="edit_family.php?family_id=<?= $person['id']?>" class="btn btn-default"></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-2">
                    <a href="add_family.php" class="btn btn-primary" type="submit">Add a family member</a>
                </div>
            </div>
        </div>
    </body>
</html>
