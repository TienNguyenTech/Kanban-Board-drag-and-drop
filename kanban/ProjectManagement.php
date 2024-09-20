<?php
class ProjectManagement {
    private $pdo;

    public function __construct() {
        // Connect to the database using PDO
        $dsn = 'mysql:host=localhost;dbname=fit2101_draganddrop;charset=utf8';
        $username = 'fit2101_project';
        $password = 'fit2101';

        try {
            $this->pdo = new PDO($dsn, $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }

    // Get all task statuses
    public function getAllStatus() {
        $stmt = $this->pdo->query('SELECT * FROM tbl_status ORDER BY id');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get tasks by project name and status ID
    public function getProjectTaskByStatus($statusId, $projectName) {
        $stmt = $this->pdo->prepare('
            SELECT * FROM tbl_task
            WHERE status_id = :status_id
            AND project_name = :project_name
            ORDER BY created_at
        ');
        $stmt->execute([
            ':status_id' => $statusId,
            ':project_name' => $projectName
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update the task status (column move)
    public function editTaskStatus($status_id, $task_id) {
        $stmt = $this->pdo->prepare('UPDATE tbl_task SET status_id = :status_id WHERE id = :task_id');
        $stmt->execute([
            ':status_id' => $status_id,
            ':task_id' => $task_id
        ]);
        return $stmt->rowCount();
    }
}
?>
