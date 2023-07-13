<?php
require_once "./dao/BaseDao.class.php";

class StudentsDao extends BaseDao {
  public function __construct() {
    parent::__construct('students');
}

public function getAllStudents() {
  $stmt = $this->conn->prepare("SELECT * FROM students");
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function get_by_idStudent($id){
  $stmt = $this->conn->prepare("SELECT * FROM students WHERE id = :id");
  $stmt->execute(['id' => $id]);
  return reset($result);
}

  public function addStudent($FirstName, $LastName, $Grade, $Description) {
    $stmt = $this->conn->prepare("INSERT INTO students (FirstName, LastName, Grade, Description) VALUES (:FirstName, :LastName , :Grade, :Description)");
    $stmt->execute([':FirstName' => $FirstName, ':LastName' => $LastName, ':Grade' => $Grade, ':Description' => $Description]);
  }

  public function deleteStudent($id) {
    $stmt = $this->conn->prepare("DELETE FROM students WHERE id=:id"); 
    $stmt->bindParam(':id', $id);
    $stmt->execute();
  }

  
  public function updateStudent($id, $data) {
    $FirstName = $data['FirstName'];
    $LastName = $data['LastName'];
    $Grade = intval($data['Grade']);

    $stmt = $this->conn->prepare("UPDATE students SET FirstName = ?, Grade = ?, LastName = ? WHERE id = ?");
    $stmt->execute([$FirstName, $Grade, $LastName, $id]);

    return $stmt->rowCount() > 0;
}

 }

?>
