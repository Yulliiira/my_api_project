<?php
// Подключение к БД и модели Fact
include_once('../core/Database.php');
include_once('../core/Fact.php');

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$database = new Database();
$db = $database->connect();

$fact = new Fact($db);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $number = isset($_GET['number']) ? intval($_GET['number']) : null;

    if ($number !== null) {
        $fact_text = $fact->getFact($number);
        if ($fact_text) {
            echo json_encode([
                'number' => $number,
                'fact' => $fact_text
            ]);
        } else {
            echo json_encode(['message' => 'Fact not found']);
        }
    } else {
        echo json_encode(['message' => 'Number not provided']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = json_decode(file_get_contents('php://input'));

    if (isset($data->number, $data->fact)) {
        if ($fact->postFact($data->number, $data->fact)) {
            echo json_encode(['message' => 'Fact added']);
        } else {
            echo json_encode(['message' => 'Fact not added']);
        }
    } else {
        echo json_encode(['message' => 'Incomplete data. Number and fact are required']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'PATCH') {

    $data = json_decode(file_get_contents('php://input'));

    if (
        isset($data->number) && !empty($data->number) &&
        isset($data->fact) && !empty($data->fact)
    ) {
        if ($fact->updateFact($data->number, $data->fact)) {
            echo json_encode(['message' => 'Fact updated']);
        } else {
            echo json_encode(['message' => 'Fact not updated']);
        }
    } else {
        echo json_encode(['message' => 'Incomplete data. Number and fact are required']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {

    $data = json_decode(file_get_contents('php://input'));
    $id = isset($data->id) ? intval($data->id) : null;

    if ($id !== null) {

        if ($fact->deleteFact($id)) {
            http_response_code(200);
            echo json_encode(['message' => 'Fact deleted']);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Fact not deleted']);
        }
    } else {
        http_response_code(400);
        echo json_encode(['message' => 'ID not provided']);
    }
} else {
    http_response_code(405);
    echo json_encode(['message' => 'Invalid request method']);
}

