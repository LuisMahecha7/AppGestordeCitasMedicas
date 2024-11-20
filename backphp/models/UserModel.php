<?php
include('conexion.php');

class UserModel {

    private $pdo;

    public function __construct() {
        $this->pdo = new Conexion();
    }

    public function getUserById($id) {
        try {
            $sql = $this->pdo->prepare("SELECT * FROM users WHERE id = :id");
            $sql->bindValue(':id', $id);
            $sql->execute();
            $sql->setFetchMode(PDO::FETCH_ASSOC);
            return $sql->fetchAll();
        } catch (PDOException $e) {
            return ['error' => 'Error de base de datos: ' . $e->getMessage()];
        }
    }

    public function getAllUsers() {
        try {
            $sql = $this->pdo->prepare("SELECT * FROM users");
            $sql->execute();
            $sql->setFetchMode(PDO::FETCH_ASSOC);
            return $sql->fetchAll();
        } catch (PDOException $e) {
            return ['error' => 'Error de base de datos: ' . $e->getMessage()];
        }
    }

    public function createUser($username, $password) {
        try {
            $sqlCheck = "SELECT id FROM users WHERE username = :username";
            $stmtCheck = $this->pdo->prepare($sqlCheck);
            $stmtCheck->bindValue(':username', $username);
            $stmtCheck->execute();

            $resultCheck = $stmtCheck->fetch(PDO::FETCH_ASSOC);

            if ($resultCheck) {
                return ['mensaje' => 'El correo ya está en uso por otro registro.'];
            }

            $sql = "INSERT INTO users (username, password) VALUES (:username, :password)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':username', $username);
            $stmt->bindValue(':password', $password);
            $stmt->execute();

            return ['mensaje' => 'Registro agregado satisfactoriamente!'];
        } catch (PDOException $e) {
            return ['error' => 'Error en el servidor: ' . $e->getMessage()];
        }
    }

    public function updateUser($id, $username, $password) {
        try {
            $sqlCheck = "SELECT id FROM users WHERE username = :username";
            $stmtCheck = $this->pdo->prepare($sqlCheck);
            $stmtCheck->bindValue(':username', $username);
            $stmtCheck->execute();

            $resultCheck = $stmtCheck->fetch(PDO::FETCH_ASSOC);

            if ($resultCheck && $resultCheck['id'] != $id) {
                return ['mensaje' => 'El correo ya está en uso por otro registro.'];
            }

            $sql = 'UPDATE users SET username = :username, password = :password WHERE id = :id';
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':username', $username);
            $stmt->bindValue(':password', $password);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            return ['mensaje' => 'Registro actualizado satisfactoriamente.'];
        } catch (PDOException $e) {
            return ['error' => 'Error en el servidor: ' . $e->getMessage()];
        }
    }

    public function deleteUser($id) {
        try {
            $sql = 'DELETE FROM users WHERE id = :id';
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return ['mensaje' => 'Registro eliminado satisfactoriamente.'];
            } else {
                return ['mensaje' => 'No se encontró el registro para eliminar.'];
            }
        } catch (PDOException $e) {
            return ['error' => 'Error en el servidor: ' . $e->getMessage()];
        }
    }
}
