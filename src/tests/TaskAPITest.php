<?php

use PHPUnit\Framework\TestCase;
use API\TaskApi;
use Model\Task;

require_once 'src/API/TaskApi.php';
require_once 'src/MODEL/Task.php';

class TaskAPITest extends TestCase
{
    private static $pdo;
    private static $dbName = 'myTasks_test';
    private $taskAPI;

    public static function setUpBeforeClass(): void
    {
        self::$pdo = new PDO("mysql:host=localhost", "root", "");
        self::$pdo->exec("CREATE DATABASE IF NOT EXISTS " . self::$dbName);
    }

    protected function setUp(): void
    {
        $this->pdo = new PDO("mysql:host=localhost;dbname=" . self::$dbName, "root", "");
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS tasks (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255),
            startDate DATE,
            endDate DATE,
            status VARCHAR(50)
        )");
        $this->taskAPI = new TaskApi($this->pdo);
    }

    public function testCreateTask()
    {
        $task = new Task("Test Task", "2024-03-30", "2024-03-31", "Pronto");
        $result = $this->taskAPI->createTask($task);
        $this->assertTrue($result);
    }

    public function testGetTaskById()
    {
        $task = new Task("Test Task", "2024-03-30", "2024-03-31", "Pronto");
        $this->taskAPI->createTask($task);
        $taskId = $this->pdo->lastInsertId();
        $result = $this->taskAPI->getTaskById($taskId);
        $this->assertEquals("Test Task", $result['name']);
    }

    protected function tearDown(): void
    {
        $this->pdo->exec("DROP TABLE IF EXISTS tasks");
    }

    public static function tearDownAfterClass(): void
    {
        self::$pdo->exec("DROP DATABASE IF EXISTS " . self::$dbName);
    }
}
