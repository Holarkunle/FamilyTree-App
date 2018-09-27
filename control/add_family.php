<?php
    include "../includes/db.php";
    include "is_logged_in.php";
    if (isset($_POST['add'])) {
        $firstname = $_POST["firstname"];
        $lastname = $_POST["lastname"];
        $dateofbirth = $_POST["dateofbirth"];
        if (empty($firstname) || empty($lastname)) {
?>
    <h4 id="error">please enter all required fields </h4>
<?php
        } else {
            $sql = $handler->prepare('INSERT INTO person (firstname, lastname, dateofbirth, father_id, mother_id, partner_id) VALUES (?, ?, ?, ?, ?, ?)');
            $sql->execute(array($firstname, $lastname, $dateofbirth, $_POST['father'], $_POST['mother'], $_POST['partner']));
            header("Location: family.php");
        }
    }

    $sql2 = "SELECT * FROM person";
    $fetch = $handler->prepare($sql2);
    $fetch->execute();
    $fetch2 = $fetch->fetchAll();

 ?>
<!DOCTYPE html>
<html>
    <head>
        <title>add family member </title>
    </head>
    <body>
    <?php include "navbar.php"; ?>
        <div class="add_form">
            <form method="post">
                <div id="add_form" class="form-group">
                    <label>Firstname:</label>
                    <input type="text" class="form-control" id="firstname" name="firstname">
                    <label>Lastname:</label>
                    <input type="text" class="form-control" id="firstname" name="lastname">
                    <label>Year of birth - mortality year:</label>
                    <input type="text" class="form-control" id="firstname" name="dateofbirth">
                    <label>Father:</label>
                    <select id="father" class="form-control" name="father">
                        <option disabled selected hidden></option>
                        <?php foreach ($fetch2 as $person2) { ?>
                            <option value="<?= $person2['id'] ?>"><?= $person2['firstname'] . " " . $person2['lastname']; ?></option>
                        <?php } ?>
                    </select>
                    <label>Mother:</label>
                    <select id="mother" class="form-control" name="mother">
                        <option disabled selected hidden></option>
                        <?php foreach ($fetch2 as $person2) { ?>
                            <option value="<?= $person2['id'] ?>"><?= $person2['firstname'] . " " . $person2['lastname']; ?></option>
                        <?php } ?>
                    </select>

                    <label>Partner:</label>
                    <select id="partner" class="form-control" name="partner">
                        <option disabled selected hidden></option>
                        <?php foreach ($fetch2 as $person2) { ?>
                            <option value="<?= $person2['id'] ?>"><?= $person2['firstname'] . " " . $person2['lastname']; ?></option>
                        <?php } ?>
                    </select>
                    <br />
                    <button id="add" class="btn btn-default" type="submit" name="add">add</button>
                </div>
            </form>
        </div>
        <style>
            .add_form {
                width: 500px;
                margin: auto;
            }
            #add {
                text-align: center;
            }
            #error {
                text-align: center;
            }
        </style>
    </body>
</html>
