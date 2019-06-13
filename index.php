<?php
$method = $_SERVER['REQUEST_METHOD']; //define the request method
$data = getData($method); //data from the request

//q - parameters from URL (index.php?q=)
    if (isset($_GET['q'])) {
        $url = $_GET['q'];
    } else {
        $url = '';
    }

$url = rtrim($url, '/'); // remove '/' from the end of the line
$urls = explode('/', $url);
$urlData = array_slice($urls, 1);

route($method, $urlData, $data);

function getData($method)
{
    //GET, POST
    if ($method === 'GET') {
        return $_GET;
    }
    if ($method === 'POST') {
        return $_POST;
    }

    //PUT, DELETE
    $data = [];

    /*
     * php://input является потоком только для чтения,
     * который позволяет вам читать необработанные данные
     * из тела запроса
     * */
    $exp = explode('&', file_get_contents('php://input')); //Split a string by string.
    foreach ($exp as $value) {
        $item = explode('=', $value);
        if (count($item) == 2) {
            $data[urldecode($item[0])] = urldecode($item[1]);
        }
    }
    return $data;
}

function route($method, $urlData, $data)
{

    if ($method === 'GET' && count($urlData) === 1) {
        $id = $urlData[0]; //id товара

        // get from the database.

        // answer
        echo json_encode(array(
            'method' => 'GET',
            'id' => $id,
            'good' => 'laptop',
            'price' => 30000,
            'color' => 'black',
            'sale' => 2500
        ));

        return;
    }

    if ($method === 'POST' && empty($urlData)) {
        //Add product to the database

        echo json_encode(array(
            'method' => 'POST',
            'id' => rand(1, 1000),
            'formData' => $data
        ));

        return;
    }

    if ($method === 'PUT' && count($urlData) === 1) {
        $id = $urlData[0];

        // update all fields of goods in the database

        echo json_encode(array(
            'method' => 'PUT',
            'id' => $id,
            'formData' => $data
        ));

        return;
    }

    if ($method === 'DELETE' && count($urlData) === 1) {
        $id = $urlData[0];

        // Remove from the database

        echo json_encode(array(
            'method' => 'DELETE',
            'id' => $id
        ));

        return;
    }

    //Error
    header('HTTP/1.0 400 Bad Request');
    echo json_encode(array(
        'error' => 'Bad Request'
    ));
}