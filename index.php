<?php

use API\TaskAPI;
use App\Router;
use Model\Task;

require_once ('autoload.php');

$db = new PDO("mysql:host=localhost;dbname=myTasks", "root", "");

$taskAPI = new TaskApi($db);

$router = new Router();
$router->get('/api/tasks', function () use ($taskAPI) {
    if(isset($_GET['id'])){
        return handleGetTaskById($taskAPI, $_GET['id']);
    }
    handleGetAllTasks($taskAPI);
});

$router->post('/api/tasks/create', function () use ($taskAPI) {
    $requestData = json_decode(file_get_contents('php://input'), true);
    handleCreateTask($taskAPI, $requestData);
});
$router->put('/api/tasks/update', function () use ($taskAPI) {
    $requestData = json_decode(file_get_contents('php://input'), true);
    handleUpdateTask($taskAPI, $requestData);
});
$router->delete('/api/tasks/delete', function () use ($taskAPI) {
    $requestData = json_decode(file_get_contents('php://input'), true);
    handleDeleteTask($taskAPI, $requestData);
});

$router->run();

function handleGetAllTasks(TaskAPI $taskAPI)
{
    $tasks = $taskAPI->getAllTasks();
    header('Content-Type: application/json');
    echo json_encode($tasks);
}

function handleGetTaskById(TaskAPI $taskAPI, $taskId)
{
    $task = $taskAPI->getTaskById($taskId);
    if ($task) {
        header('Content-Type: application/json');
        echo json_encode($task);
    } else {
        http_response_code(404);
        echo json_encode(["error" => "Tarefa não encontrada"]);
    }
}

function handleCreateTask(TaskAPI $taskAPI, array $requestData)
{
    $name = filter_var($requestData['name'], FILTER_SANITIZE_STRING);
    $startDate = filter_var($requestData['startDate'], FILTER_SANITIZE_STRING);
    $endDate = filter_var($requestData['endDate'], FILTER_SANITIZE_STRING);
    $status = filter_var($requestData['status'], FILTER_SANITIZE_STRING);

    if (empty($name) || empty($startDate) || empty($endDate) || empty($status)) {
        http_response_code(400);
        echo json_encode(["error" => "Todos os campos são obrigatórios"]);
        return;
    }

    $task = new Task($name, $startDate, $endDate, $status);

    $success = $taskAPI->createTask($task);

    if ($success) {
        http_response_code(201);
        echo json_encode(["message" => "Tarefa criada com sucesso"]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Erro ao criar tarefa"]);
    }
}

function handleUpdateTask(TaskAPI $taskAPI, $requestData)
{
    $id = (int) filter_var($requestData['id'], FILTER_SANITIZE_STRING);
    $name = filter_var($requestData['name'], FILTER_SANITIZE_STRING);
    $startDate = filter_var($requestData['startDate'], FILTER_SANITIZE_STRING);
    $endDate = filter_var($requestData['endDate'], FILTER_SANITIZE_STRING);
    $status = filter_var($requestData['status'], FILTER_SANITIZE_STRING);

    if (empty($name) || empty($startDate) || empty($endDate) || empty($status) || empty($id)) {
        http_response_code(400);
        echo json_encode(["error" => "Todos os campos são obrigatórios"]);
        return;
    }
    $task = new Task($name, $startDate, $endDate, $status, $id);
    $success = $taskAPI->updateTask($task);

    if ($success) {
        echo "Tarefa atualizada com sucesso";
    } else {
        echo "Erro ao atualizar tarefa";
    }
}

function handleDeleteTask(TaskAPI $taskAPI, array $requestData)
{
    $id = filter_var($requestData['id'], FILTER_SANITIZE_STRING);
    $success = $taskAPI->deleteTask($id);
    if ($success) {
        echo "Tarefa excluída com sucesso";
    } else {
        echo "Erro ao deletar tarefa";
    }
}
