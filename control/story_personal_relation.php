<?php
include "is_logged_in.php";

include '../includes/db.php';

try {
    $stories = getstories();
    $people = getpeople();
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['story']) && isset($_POST['person'])) {
            $form_type = 'insert';
            $sql = "INSERT INTO story_person_relationship (story_id, person_id) VALUES (?, ?)";
            $stmt = $handler->prepare($sql);
            $stmt->execute(array($_POST['story'], $_POST['person']));
            $count = $stmt->rowCount();
        } elseif (isset($_POST['vpr_id'])) {
            $form_type = 'delete';
            $sql = "DELETE FROM story_person_relationship WHERE id=?";
            $stmt = $handler->prepare($sql);
            $stmt->execute(array($_POST['vpr_id']));
            $count = $stmt->rowCount();
        }
    }
} catch (PDOException $e) {

}

function getstoryen() {
    global $handler;
    $sql = "SELECT * FROM story";
    $stmt = $handler->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
}

function getPersonen() {
    global $handler;
    $sql = "SELECT * FROM person";
    $stmt = $handler->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>story person relationship</title>
        <script src="../js/jquery.js"></script>
        <script>
            $(document).ready(function () {
                $("#person_select").change(function () {
                    var person_id = $("#person_select option:selected").val();
                    $.post( "../ajax_pages/person_stories.php", { person_id: person_id }, function( data ) {
                        console.log(data);
                        if (data.success == true) {
                            $("#stories_from_title").html("stories from a specific person: <b>" + data.content[0].First Name+ " " + data.content[0].last name + "</b>");
                            var $result = $("#vpr_result");
                            $result.html("");
                            for(var i = 0; i < data.content.length; i++) {
                                if (data.content[i].id != null) {
                                    $result.append("<tr>" +
                                        "<td>" + data.content[i].title + "</td>" +
                                        "<td>" + data.content[i].text + "</td>" +
                                        "<td><form action='' method='post'>" +
                                        "<input type='hidden' name='vpr_id' value='" + data.content[i].id + "'>" +
                                        "<input type='submit' class='btn btn-danger btn-sm' value='remove'>" +
                                        "</form></td>" +
                                        "</tr>");
                                }
                            }
                        }
                    }, 'json');
                });
            });
        </script>
    </head>
    <body>
        <?php include "navbar.php"; ?>
        <div class="container">
            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-lg-8">
                    <div class="row">
                        <div class="col-md-12">
                            <?php
                            if (isset($form_type)) {
                                switch ($form_type) {
                                    case 'insert':
                                        if (isset($count) && $count >= 1) {
                                            ?>
                                            <div class="alert alert-success">
                                                <p>Successfully updated the database!</p>
                                            </div>
                                            <?php
                                        } else {
                                            ?>
                                            <div class="alert alert-danger">
                                                <p>Could not put the story person relationship in the database!</p>
                                            </div>
                                            <?php
                                        }
                                        break;

                                    case 'delete':
                                        if (isset($count) && $count >= 1) {
                                            ?>
                                            <div class="alert alert-success">
                                                <p>Successfully removed the relationship between story and person</p>
                                            </div>
                                            <?php
                                        } else {
                                            ?>
                                            <div class="alert alert-danger">
                                                <p>Could not remove the relationship between story and person</p>
                                            </div>
                                            <?php
                                        }
                                        break;
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <form action="" method="post">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>story title:</label>
                                    <select id="story_select" class="form-control" name="story">
                                        <option disabled selected hidden>story</option>
                                        <?php foreach ($stories as $story) { ?>
                                            <option value="<?= $story['id'] ?>"><?= $story['title'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>

                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>person:</label>
                                    <select id="person_select" class="form-control" name="person">
                                        <option disabled selected hidden>person</option>
                                        <?php foreach ($people as $person) { ?>
                                            <option value="<?= $person['id'] ?>"><?= $person['First Name']. ' ' .$person['last name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <input type="submit" class="btn btn-primary" value="add">
                            </div>
                        </form>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h4 id="stories_from_title">stories from a specific person:</h4>
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Text</th>
                                        <th>remove</th>
                                    </tr>
                                </thead>
                                <tbody id="vpr_result">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-2"></div>
            </div>
        </div>
    </body>
</html>
