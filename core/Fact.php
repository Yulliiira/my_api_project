<?php
class Fact
{
    public $conn;
    public $table = 'facts';

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getFact($number)
    {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE number = ? LIMIT 1';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $number, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['fact'] : null;
    }

    public function postFact($number, $fact)
    {
        $query = 'INSERT INTO ' . $this->table . ' (number, fact) VALUES (:number, :fact)';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':number', $number, PDO::PARAM_INT);
        $stmt->bindParam(':fact', $fact, PDO::PARAM_STR);

        if ($stmt->execute()) {
            http_response_code(201);
            return true;
        } else {
            printf("Error: %s.\n", $stmt->error);
            http_response_code(404);
            return false;
        }
    }

    public function updateFact($number,$fact)
    {
        $query = 'UPDATE ' . $this->table . ' SET fact = :fact WHERE number = :number';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':number', $number, PDO::PARAM_INT);
        $stmt->bindParam(':fact', $fact, PDO::PARAM_STR);

        if ($stmt->execute()) {
            http_response_code(200);
            return true;
        } else {
            printf("Error: %s.\n", $stmt->error);
            http_response_code(500);
            return false;
        }

    }

    public function deleteFact($id){
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
