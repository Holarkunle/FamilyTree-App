<?php
ob_start();
include "../includes/db.php";
include "is_logged_in.php";

$sql = "SELECT * FROM person WHERE id = ?";
$prepare = $handler->prepare($sql);
$prepare->execute(array($_GET['family_id']));

$sql4 = "SELECT * FROM person";
$people = $handler->prepare($sql4);
$people->execute();
$fetch = $people->fetchAll();


?>

<!DOCTYPE html>
<html>
    <head>
        <title>Edit family member</title>
    </head>
    <body>
        <?php include "navbar.php"; ?>
        <div class="container">
            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    <form method="post">
                        <div id="add_form" class="form-group">
                            <?php foreach ($prepare->fetchAll() as $family):
                            $sql5 = "SELECT * FROM person WHERE id = ?";
                            $fetch2 = $handler->prepare($sql5);
                            $fetch2->execute(array($family['father_id']));
                            $father = $fetch2->fetchAll();

                            $fetch2->execute(array($family['mother_id']));
                            $mother = $fetch2->fetchAll();

                            $fetch2->execute(array($family['partner_id']));
                            $partner = $fetch2->fetchAll();

                                ?>
                                <div class="form-group">
                                    <label>First name:</label>
                                    <input type="text" class="form-control" id="firstname" value="<?= $family['first name']; ?>" name="firstname" placeholder="Firstname">
                                </div>
                                <div class="form-group">
                                    <label>Last name:</label>
                                    <input type="text" class="form-control" id="lastname" value="<?= $family['lastname']; ?>" name="lastname" placeholder="Lastname">
                                </div>
                                <div class="form-group">
                                    <label>Date of birth:</label>
                                    <input type="text" class="form-control" id="birth" value="<?= $family['dateofbirth']; ?>" name="dateofbirth" placeholder="Dateofbirth(dd-mm-yyyy) (not required)">
                                </div>
                                <div class="form-group">
                                    <label>Mother:</label>
                                    <select id="mother" class="form-control" name="mother">
                                        <?php foreach ($mother as $family3) { ?>
                                            <option value="<?= $family3['id'] ?>" selected><?= $family3['first name'] . " " . $family3['lastname']; ?></option>
                                        <?php } ?>
                                        <?php foreach ($fetch as $person) { ?>
                                            <option value="<?= $person['id'] ?>"><?= $person['first name'] . " " . $person['lastname']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Father</label>
                                    <select id="father" class="form-control" name="father">
                                        <?php foreach ($father as $family3) { ?>
                                            <option value="<?= $family3['id'] ?>" selected><?= $family3['first name'] . " " . $family3['lastname']; ?></option>
                                        <?php } ?>
                                        <?php foreach ($fetch as $person2) { ?>
                                            <option value="<?= $person2['id'] ?>"><?= $person2['firstname'] . " " . $person2['lastname']; ?></option>
                                        <?php } ?>
                                    </select>

                                </div>
                                <div class="form-group">
                                    <label>Partner</label>
                                    <select id="partner" class="form-control" name="partner">
                                        <?php foreach ($partner as $family3) { ?>
                                            <option value="<?= $family3['id'] ?>" selected><?= $family3['firstname'] . " " . $family3['lastname']; ?></option>
                                        <?php } ?>
                                        <?php foreach ($fetch as $person2) { ?>
                                            <option value="<?= $person2['id'] ?>"><?= $person2['firstname'] . " " . $person2['lastname']; ?></option>
                                        <?php } ?>
                                    </select>

                                </div>
                            <?php endforeach; ?>
                            <button class="btn btn-default" type="submit" name="save">Amendments save</button>
                            <button class="btn btn-danger" type="submit" name="remove">remove</button>
                        </div>
                    </form>
                    <?php


                    if (isset($_POST['save'])) {
                        $firstname = $_POST['firstname'];
                        $lastname = $_POST['lastname'];
                        $birth = $_POST['dateofbirth'];

                        $sql2 = "UPDATE person SET firstname = :firstname, lastname = :lastname, dateofbirth = :dateofbirth, father_id = :father_id, mother_id = :mother_id, partner_id = :partner_id WHERE id = :person_id";
                        $pd = $handler->prepare($sql2);
                        $pd->execute(array(':firstname' => $firstname,
                                           ':lastname' => $lastname,
                                           ':dateofbirth' => $birth,
                                           ':father_id' => $_POST['father'],
                                           ':mother_id' => $_POST['mother'],
                                           ':partner_id' => $_POST['partner'],
                                           ':person_id' => $_GET['family_id']
                                       ));
                        header("Location: family.php");

                    }

                    if (isset($_POST['remove'])) {
                        $sql3 = "DELETE FROM person WHERE id = ?";
                        $pd = $handler->prepare($sql3);
                        $pd->execute(array($_GET['family_id']));
                        header('Location: family.php');
                    }
                    ?>
                </div>
                <div class="col-md-3"></div>
            </div>
        </div>
    </body>
</html>
