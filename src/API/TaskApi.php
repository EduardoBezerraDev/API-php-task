<?php

namespace App\API;

use PDO;
use App\Model\Task;

class TaskAPI {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function getAllTasks(): array {
        $statement = $this->db->query("SELECT * FROM tasks");
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createTask(Task $task): bool {
        $query = "INSERT INTO tasks (name, startDate, endDate, status) VALUES (?, ?, ?, ?)";
        $statement = $this->db->prepare($query);
        return $statement->execute([$task->getName(), $task->getStartDate(), $task->getEndDate(), $task->getStatus()]);
    }

    public function updateTask(Task $task): bool {
        $query = "UPDATE tasks SET name=?, startDate=?, endDate=?, status=? WHERE id=?";
        $statement = $this->db->prepare($query);
        return $statement->execute([$task->getName(), $task->getStartDate(), $task->getEndDate(), $task->getStatus(), $task->getId()]);
    }
}
