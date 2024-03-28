<?php

use PHPUnit\Framework\TestCase;

require 'TaskAPI.php';

class TaskAPITest extends TestCase {
    private PDO $pdo;
    private TaskAPI $api;

    protected function setUp(): void {
        $this->pdo = new PDO('mysql:host=localhost;dbname=nome_do_banco', 'usuario', 'senha');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->api = new TaskAPI($this->pdo);
    }

    public function testGetAllTasks(): void {
        $tasks = $this->api->getAllTasks();
        $this->assertIsArray($tasks);
    }

    public function testCreateTask(): void {
        $this->assertTrue($this->api->createTask('Nova Tarefa', '2024-03-28', '2024-03-28', 'Em andamento'));
    }

    public function testUpdateTask(): void {
        $this->assertTrue($this->api->updateTask(1, 'Tarefa Atualizada', '2024-03-28', '2024-03-28', 'Concluída'));
    }
}
