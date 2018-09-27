<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] != true) {
    echo json_encode(array('success' => false, 'message' => 'Permission denied'));
    die;
}
if ($_SERVER['REQUEST_METHOD'] != 'POST' || !isset($_POST['person_id'])) {
    echo json_encode(array('success' => false, 'message' => 'Invalid request method'));
    die;
}
include '../includes/db.php';
try {
    $sql = "SELECT vpr.id, p.firstname, p.lastname, v.title, v.text 
	FROM person as p 
    LEFT JOIN story_person_relationship as vpr
    	ON vpr.person_id=p.id
    LEFT JOIN story as v
    	ON v.id=vpr.story_id
    WHERE p.id=?";
    $stmt = $handler->prepare($sql);
    $stmt->execute([$_POST['person_id']]);
    $result = array();
    foreach ($stmt->fetchAll() as $loop => $item) {
        $result[$loop]['id'] = $item['id'];
        $result[$loop]['First Name'] = $item['First Name'];
        $result[$loop]['last name'] = $item['last name'];
        $result[$loop]['title'] = $item['title'];
        $result[$loop]['text'] = $item['text'];
    }
    echo json_encode(array('success' => true, 'content' => $result));
} catch (PDOException $e) {
    echo json_encode(array('success' => false, 'message' => $e->getMessage()));
}

