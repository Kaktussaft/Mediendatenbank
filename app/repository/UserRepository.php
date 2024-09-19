<?php

namespace App\Repository;

use App\Model\UserModel;
use App\Database\DbConnection;



class UserRepository
{
    private $conn;
    public function __construct()
    {
        $this->conn = DbConnection::getInstance()->getConnection();
    }

    public function createUser($user)
    {
        $username = $user->getUsername();
        $email = $user->getEmail();
        $surname = $user->getSurname();
        $name = $user->getName();
        $isAdmin = $user->getIsAdmin();
        $registrationDate = $user->getRegistrationDate()->format('Y-m-d');

        $stmt = $this->conn->prepare("INSERT INTO Benutzer (Benutzername, EMail, Nachname, Vorname, Rolle, Registrierungsdatum) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $username, $email, $surname, $name, $isAdmin, $registrationDate);
        $stmt->execute();
        $stmt->close();
    }
    
    public function readUserByUsername($username)
    {
        $stmt = $this->conn->prepare("SELECT Benutzername FROM Benutzer WHERE Benutzername = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getUserByUsername($username)
    {
        $stmt = $this->conn->prepare("SELECT * FROM Benutzer WHERE Benutzername = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();

        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        return $user;
    }

    public function updateUser($username, $email, $surname, $name, $isAdmin, $previousUsername)
    {
        $stmt = $this->conn->prepare("UPDATE Benutzer SET Benutzername = ?, EMail = ?, Nachname = ?, Vorname = ?, Rolle = ? WHERE Benutzername = ?");
        $stmt->bind_param("ssssss", $username, $email, $surname, $name, $isAdmin, $previousUsername);
        $stmt->execute();
        $stmt->close();  
    }

    public function readAllUsers()
    {
        $stmt = $this->conn->prepare("SELECT * FROM Benutzer");
        $stmt->execute();
        $result = $stmt->get_result();
        $users = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $users;
    }
    
    public function deleteUser($userId)
    {
        $stmt = $this->conn->prepare("DELETE FROM Benutzer WHERE Benutzer_ID = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->close();
    }
}
