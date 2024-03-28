<?php

class Task {
    private int $id;
    private string $name;
    private string $startDate;
    private string $endDate;
    private string $status;

    public function __construct(string $name, string $startDate, string $endDate, string $status, int $id = 0) {
        $this->id = $id;
        $this->name = $name;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->status = $status;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getStartDate(): string {
        return $this->startDate;
    }

    public function getEndDate(): string {
        return $this->endDate;
    }

    public function getStatus(): string {
        return $this->status;
    }
}
