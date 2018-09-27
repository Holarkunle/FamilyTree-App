

<?php
    if ($_SERVER['REQUEST_METHOD'] != 'GET') {
        die('You can only view this page with a GET request.');
    }

    include 'includes/db.php';
    include 'includes/familytree.php';

    if (array_key_exists('person', $_GET)) {
        $person_id = $_GET['person'];
        $familytree = new familytree($handler);
        if ($familytree->personExists($person_id)) {
            $person = $familytree->getPerson($person_id);
            $person_stories = $familytree->getStories($person_id);

            $father = $familytree->getPerson($person['father_id']);
            if ($father !== false) {
                $father_stories = $familytree->getStories($father['id']);
            } else {
                $father_stories = array();
            }

            $mother = $familytree->getPerson($person['mother_id']);
            if ($mother !== false) {
                $mother_stories = $familytree->getStories($mother['id']);
            } else {
                $mother_stories = array();
            }
        } else {
            $error_message = 'person not found!';
        }

        $person_sql = $handler->query("SELECT * FROM person WHERE partner_id = '{$person_id}' LIMIT 1");
        $person_partner = $person_sql->fetchAll();
    }

    if (isset($_GET['search'])) {
        $searchvalue = $_GET['search'];
        $arr = explode(' ',trim($searchvalue));
        $FirstWord = $arr[0];
        $LastWord = $arr[count($arr)-1];

        $query3 = $handler->query("SELECT * FROM person WHERE FirstName LIKE '{$FirstWord}%' AND lastname LIKE '%{$LastWord}%'");
        $QueryData = $query3->fetchAll();

        foreach($QueryData as $person) {
            header("Location: familytree.php?person={$person['id']}");
        }
    }

    ?>
<!DOCTYPE html>
<html>
    <head>
        <meta name="keywords" content="FamilyTree,Odesanya, Wasiu,Odesanya Wasiu, familytree, family">
		<meta name="description" content="Odesanya Wasiu familytree">
		<meta name="author" content="Olakunle">
		<meta charset="utf-8">
        <link type="text/css" rel="stylesheet" href="css/style.css"/>
        <title>familytree</title>
        <link rel="stylesheet" href="css/materialize.css">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <script type="text/javascript" src="js/jquery.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.7/js/materialize.min.js"></script>
    </head>
    <body>
        <div class="NavBar">
            <p class="Top"><a href="index.php">Home page</a></p>
            <p class=""><a href="control/index.php">control panel</a></p>
            
        </div>
        <img id="background" src="images/IMG1.jpg" />
        <div class="row">
            <div class="familytree">
                <div class="input-field col l5 s12 m12 Search">
                    <div id="error_box">
                        <?php if (isset($error_message)) { ?>
                        <?= $error_message ?>
                        <?php } ?>
                    </div>
                    <form method="get">
                        <div class="row">
                            <div class="input-field col s12">
                                <i class="material-icons prefix">search</i>
                                <input type="text" autocomplete="off" name="search" id="autocomplete-input" class="autocomplete">
                                <label for="autocomplete-input">Search</label>
                            </div>
                        </div>
                        <button class="btn" name="search">Search</button>
                    </form>
                </div>
                <?php
                    $query2 = $handler->query("SELECT * FROM person");
                    $searchdata = $query2->fetchAll();

                    ?>
                <script>
                    $(document).ready(function(){
                        $('.autocomplete').autocomplete({
                          data: {
                            '<?php foreach ($searchdata as $naam): ?>' : null,
                            '<?php echo $naam['firstname'] . ' ' . $naam['lastname']; ?> ': null,
                            '<?php endforeach; ?>' : null
                            },
                        });

                    });
                </script>
                <div class="col s12 m12 l3">
                    <div class="vak1">
                        <?php if (isset($person)) { ?>
                        <div class="card purple darken-4">
                            <div class="card-content white-text">
                                <span class="card-title"><?= $person['firstname']. ' '. $person['lastname'] ?></span>
                                <p><?= $person['dateofbirth']; ?> <br />
                                    Partner: <?php foreach($person_partner as $partner) { ?> <a href="familytree.php?person=<?=$partner['id']; ?>"> <?= $partner['firstname']. " " . $partner['lastname']; ?> </a><?php } ?>
                                </p>
                            </div>
                            <div class="card-action">
                                <?php foreach ($person_stories as $story) { ?>
                                <a class="btn" href="#" onclick="$('#story_<?= $story['id'] ?>').openModal()">story</a>
                                <?php } ?>
                            </div>
                        </div>
                        <?php } else { ?>
                        <div class="card purple darken-4">
                            
                                
                               
                            </div>
                            <div class="card-action">
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="col s6">
                    <div class="field3">
                        <?php if (isset($person) && isset($father) && $father !== false) { ?>
                        <div class="card purple darken-4">
                            <div class="card-content white-text">
                                <span class="card-title">
                                father: <a href="familytree.php?person=<?= $father['id'] ?>">
                                <?= $father['firstname']. ' ' .$father['lastname']?>
                                </a>
                                </span>

                                <?php
                                    $father_sql = $handler->query("SELECT * FROM person WHERE partner_id = '{$father['id']}' LIMIT 1");
                                    $father_partner = $father_sql->fetchAll();
                                 ?>
                                <p><?= $father['dateofbirth'] ?> <br />
                                    Partner: <?php foreach($father_partner as $partner) { ?> <a href="familytree.php?person=<?=$partner['id']; ?>"> <?= $partner['firstname']. " " . $partner['lastname']; ?> </a><?php } ?>

                                </p>
                            </div>
                            <div class="card-action">
                                <?php foreach ($father_stories as $story) { ?>
                                <a class="btn" href="#" onclick="$('#story_<?= $story['id'] ?>').openModal()">story</a>
                                <?php } ?>
                            </div>
                        </div>
                        <?php } else { ?>
                        <div class="card purple darken-4">
                            
                            <div class="card-action">
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                    <div class="col s6">
                        <?php if (isset($person) && isset($mother) && $mother !== false) { ?>
                        <div class="card purple darken-4">
                            <div class="card-content white-text">
                                <span class="card-title">
                                mother: <a href="familytree.php?person=<?= $mother['id'] ?>">
                                <?= $mother['firstname']. ' ' .$mother['lastname']?>
                                </a>
                                </span>
                                <?php
                                    $mother_sql = $handler->query("SELECT * FROM person WHERE partner_id = '{$mother['id']}' LIMIT 1");
                                    $mother_partner = $mother_sql->fetchAll();
                                ?>
                                <p><?= $mother['dateofbirth']?><br />
                                    Partner: <?php foreach($mother_partner as $partner) { ?> <a href="familytree.php?person=<?=$partner['id']; ?>"> <?= $partner['firstname']. " " . $partner['lastname']; ?> </a><?php } ?>
                                </p>

                            </div>
                            <div class="card-action">
                                <?php foreach ($mother_stories as $story) { ?>
                                <a class="btn" href="#" onclick="$('#story_<?= $story['id'] ?>').openModal()">story</a>
                                <?php } ?>
                            </div>
                        </div>
                        <?php } else { ?>
                        
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>

        <?php
            if (isset($person)) {
                foreach ($person_stories as $story) { ?>
        <div id="story_<?= $story['id'] ?>" class="modal">
            <div class="modal-content">
                <h4><?= $story['title'] ?></h4>
                <p><?= $story['text'] ?></p>
            </div>
            <div class="modal-footer">
                <a href="#" class=" modal-action modal-close waves-effect waves-green btn-flat">Close</a>
            </div>
        </div>
        <?php
            }
            foreach ($mother_stories as $story) { ?>
        <div id="story_<?= $story['id'] ?>" class="modal">
            <div class="modal-content">
                <h4><?= $story['title'] ?></h4>

                <p><?= $story['text'] ?></p>
            </div>
            <div class="modal-footer">
                <a href="#" class=" modal-action modal-close waves-effect waves-green btn-flat">Close</a>
            </div>
        </div>
        <?php
            }
            foreach ($father_stories as $story) { ?>
        <div id="story_<?= $story['id'] ?>" class="modal">
            <div class="modal-content">
                <h4><?= $story['title'] ?></h4>
                <p><?= $story['text'] ?></p>
            </div>
            <div class="modal-footer">
                <a href="#" class=" modal-action modal-close waves-effect waves-green btn-flat">Close</a>
            </div>
        </div>
        <?php
            }
            } ?>
    </body>
</html>
