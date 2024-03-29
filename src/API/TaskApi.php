<?php

namespace API;

use PDO;
use Model\Task;

class TaskAPI
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getAllTasks(): array
    {
        $query = "SELECT * FROM tasks";
        $statement = $this->db->query($query);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTaskById(int $taskId): ?array
    {
        $query = "SELECT * FROM tasks WHERE id=?";
        $statement = $this->db->prepare($query);
        $statement->execute([$taskId]);
        $task = $statement->fetch(PDO::FETCH_ASSOC);
        return $task ?: null;
    }

    public function createTask(Task $task): bool
    {
        $query = "INSERT INTO tasks (name, startDate, endDate, status) VALUES (?, ?, ?, ?)";
        $statement = $this->db->prepare($query);
        return $statement->execute([$task->getName(), $task->getStartDate(), $task->getEndDate(), $task->getStatus()]);
    }

    public function updateTask(Task $task): bool
    {
        $query = "UPDATE tasks SET name=?, startDate=?, endDate=?, status=? WHERE id=?";
        $statement = $this->db->prepare($query);
        return $statement->execute([$task->getName(), $task->getStartDate(), $task->getEndDate(), $task->getStatus(), $task->getId()]);
    }

    public function deleteTask(int $taskId): bool
    {
        $query = "DELETE FROM tasks WHERE id=?";
        $statement = $this->db->prepare($query);
        return $statement->execute([$taskId]);
    }
}
