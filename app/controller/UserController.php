<?php

namespace App\Controller;

use App\Core\Controller;
use App\Model\UserModel;
use App\Controller\MediumController;
use App\Controller\KeywordController;
use App\Repository\UserRepository;
use DateTime;


class UserController extends Controller
{
    private $userRepository;
    private $mediumController;
    private $keywordController;
    private $currentUser;

    public function __construct()
    {
        session_start();
        $this->userRepository = new UserRepository();
        $this->mediumController = new MediumController();
        $this->keywordController = new KeywordController();
    }

    public function login($username = '')
    {
        $userExists = $this->userRepository->readUserByUsername($username);

        if (!$userExists) {
            header('Location: http://localhost/Mediendatenbank/public/');
            echo 'User does not exist';
        } else {
            $this->currentUser = $this->userRepository->getUserByUsername($username);
            $_SESSION['currentUser'] = $this->currentUser;
            $isAdmin = $this->currentUser['Rolle'] === 'admin' ? 'true' : 'false';
            $this->view('User', ['isAdmin' => $isAdmin]);
        }
    }

    public function register(string $name, string $surname, string $username, string $email)
    {
        $userExists = $this->userRepository->readUserByUsername($username);

        if (!$userExists) {
            $currentDateTime = new DateTime();
            list($name, $surname, $username, $email) = $this->sanitizeUserInput($name, $surname, $username, $email);
            $user = new UserModel($name, $surname, $username, $currentDateTime, "false", $email);
            $this->userRepository->createUser($user);
            header('Location: http://localhost/Mediendatenbank/public/');
        } else {
            header('Location: http://localhost/Mediendatenbank/public/');
            exit();
        }
    }
   
    public function toggleAdminView()
    {
        if (!isset($_SESSION['currentUser'])) {
            header('Location: http://localhost/Mediendatenbank/public/');
            exit();
        }
        else{
        $this->view('Admin');
        }
    }
    public function toggleUserView()
    {
        if (!isset($_SESSION['currentUser'])) {
            header('Location: http://localhost/Mediendatenbank/public/');
            exit();
        }
        $currentUser = $_SESSION['currentUser'];
        $isAdmin = $currentUser['Rolle'] === 'admin' ? 'true' : 'false';
        $this->view('User', ['isAdmin' => $isAdmin]);
    }

    public function logout()
    {
        $_SESSION = [];
        session_destroy();
        header('Location: http://localhost/Mediendatenbank/public/');
        exit();
    }

    public function updateUser()
    {
        $rawData = file_get_contents('php://input');
        $data = json_decode($rawData, true);

        if (json_last_error() === JSON_ERROR_NONE) {
            $newUsername = isset($data['username']) ? $data['username'] : '';
            $newEmail = isset($data['email']) ? $data['email'] : '';
            $newSurname = isset($data['lastname']) ? $data['lastname'] : '';
            $newName = isset($data['firstname']) ? $data['firstname'] : '';

            list($username, $email, $surname, $name) = $this->sanitizeUserInput($newUsername, $newEmail, $newSurname, $newName);

            $currentUser = $_SESSION['currentUser'];
            $userExists = $this->userRepository->readUserByUsername($username);
            $isAdmin = $currentUser['Rolle'] === 'admin' ? 'admin' : 'false';

            if ($userExists && $username != $currentUser['Benutzername'] ) {
                echo json_encode(['statusMessage' => 'duplicate', 'message' => 'Nutzername bereits vergeben']);
               
            } else {
                $this->userRepository->updateUser($username, $email, $surname, $name, $isAdmin, $currentUser['Benutzername']);
                echo json_encode(['statusMessage' => 'success', 'message' => 'Nutzer erfolgreich aktualisiert']);
            }
            
        } else {
            echo json_encode(['statusMessage' => 'error', 'message' => 'Invalid JSON data']);
        }
    }

    public function updateUserAdmin()
    {
        $rawData = file_get_contents('php://input');
        $data = json_decode($rawData, true);
        error_log(print_r($data, true));

        if (json_last_error() === JSON_ERROR_NONE) {
            $newUsername = isset($data['username']) ? $data['username'] : '';
            $oldUsername = isset($data['oldUsername']) ? $data['oldUsername'] : ''; 
            $newEmail = isset($data['email']) ? $data['email'] : '';
            $newSurname = isset($data['lastname']) ? $data['lastname'] : '';
            $newName = isset($data['firstname']) ? $data['firstname'] : '';
            $isAdmin = isset($data['isAdmin']) ? $data['isAdmin'] : '';

            list($username, $email, $surname, $name) = $this->sanitizeUserInput($newUsername, $newEmail, $newSurname, $newName);

            $userExists = $this->userRepository->readUserByUsername($username);

            if ($userExists) {
                echo json_encode(['statusMessage' => 'duplicate', 'message' => 'Nutzername bereits vergeben']);
               
            } else {
                $this->userRepository->updateUser($username, $email, $surname, $name, $isAdmin, $oldUsername);
                echo json_encode(['statusMessage' => 'success', 'message' => 'Nutzer erfolgreich aktualisiert']);
            }
            
        } else {
            echo json_encode(['statusMessage' => 'error', 'message' => 'Invalid JSON data']);
        }
    }
    public function getAllUsers()
    {
        $users = $this->userRepository->readAllUsers();
        echo json_encode($users);
    }
    
    public function deleteUser()
    {
        $rawData = file_get_contents('php://input');
        $data = json_decode($rawData, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            $username = isset($data['username']) ? $data['username'] : '';
            $user = $this->userRepository->getUserByUsername($username);
            $userId = $user['Benutzer_ID'];
            $this->keywordController->deleteAllKeywordsAndAssociations($userId);
            $this->mediumController->deleteAllMediaForUser($userId);
            $this->userRepository->deleteUser($userId);
            echo json_encode(['statusMessage' => 'success', 'message' => 'Nutzer erfolgreich gelöscht']);
        } else {
            echo json_encode(['statusMessage' => 'error', 'message' => 'Invalid JSON data']);
        }
    }
}
