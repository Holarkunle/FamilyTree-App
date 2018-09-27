<?php
    include "includes/db.php";

    $sql = "SELECT story.id, story.title, story.text FROM story WHERE NOT EXISTS (SELECT * FROM story_person_relationship WHERE story.id = story_person_relationship.story_id) LIMIT 2";
    $stmt = $handler->query($sql);
    $QueryData = $stmt->fetchAll();

	if (isset($_GET['search'])) {
        $searchvalue = $_GET['search'];

        $query3 = $handler->query("SELECT * FROM story WHERE title = '{$searchvalue}'");
        $QueryData3 = $query3->fetchAll();

        foreach($QueryData3 as $story) {
            header("Location:stories.php?story={$story['id']}");
        }
    }
    ?>
<!DOCTYPE html>
<html>
    <head>
        <meta name="keywords" content="FamilyTree, Odesanya, Wasiu, Odesanya Wasiu, familytree, family">
		<meta name="description" content="Odesanya Wasiu family tree">
		<meta name="author" content="Atanda">
		<meta charset="utf-8">
        <link type="text/css" rel="stylesheet" href="css/style.css"/>
        <link rel="stylesheet" href="css/materialize.css">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <script type="text/javascript" src="js/jquery.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.7/js/materialize.min.js"></script>
        <title>Odesanya Wasiu stories</title>
    </head>
    <body>
        <div class="NavBar text-center">
            <p style="text-align: center !important;"class="Top"><a href="index.php">Odesanya Wasiu</a></p>
        </div>
        <img id="background" src="images/IMG1.jpg" />
        <div class="row">
        <div class="input-field col l5 s12 m12 search2">
            <form method="get">
                <div class="row">
                    <div class="input-field col s12">
                        <i class="material-icons prefix">search</i>
                        <input type="text" autocomplete="off" name="search" id="autocomplete-input" class="autocomplete">
                        <label for="autocomplete-input">Search stories</label>

                    </div>
                </div>
				<button class="btn" name="search">Search</button>
            </form>
            <?php
                $query2 = $handler->query("SELECT * FROM story");
                $searchdata = $query2->fetchAll();
                ?>
            <script>
                $(document).ready(function(){
                	$('.autocomplete').autocomplete({
                	  data: {
                		'<?php foreach ($searchdata as $story): ?>' : null,
                		'<?php echo $story['title']; ?> ': null,
                		'<?php endforeach; ?>' : null
                		},
                	});
                });
            </script>
        </div>
        <?php if($QueryData) { ?>
        <div class="col s12 m12 l5">
            <?php foreach($QueryData as $key => $story) { ?>
            <div class="verse 2">
                <div class="card purple darken-4">
                    <div class="card-content white-text">
                        <span class="card-title"><?= $story['title']; ?></span> <br />
                        <?= substr($story['text'],0,300); ?>
                    </div>
                    <div class="card-action">
                        <a class="btn" href="stories.php?story=<?= $story['id']?>">read more</a>
                     </div>
                </div>
            </div>
            <?php } } else { ?>
            <?php } ?>

			<?php
			if (isset($_GET['story'])) {
				$storyid = $_GET['story'];
				$stmt2 = $handler->prepare("SELECT * FROM story WHERE id = ?");
				$stmt2->execute(array($storyid));
				$QueryData2 = $stmt2->fetchAll();?>
			<script>
			$(document).ready(function(){
				$('#story').openModal();
			});
			</script>
			<?php foreach($QueryData2 as $search2):  ?>
            <div id="search" class="modal">
                <div class="modal-content">
                    <h4><?= $search2['title'] ?></h4>
                    <p><?= $search2['text'] ?></p>
                </div>
                <div class="modal-footer">
                    <a href="#" class=" modal-action modal-close waves-effect waves-green btn-flat">Close</a>
                </div>
            </div>
		<?php endforeach; } ?>
        </div>
    </body>
</html>
