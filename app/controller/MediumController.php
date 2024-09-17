<?php

namespace App\Controller;

use App\Core\Controller;
use App\Repository\MediumRepository;
use Exception;
use DateTime;

class MediumController extends Controller
{

    private $mediumRepository;
    private $currentUserId;
    private $data;

    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['currentUser']['Benutzer_ID'])) {
            $this->currentUserId = $_SESSION['currentUser']['Benutzer_ID'];
        } else {
            throw new Exception("User is not logged in.");
        }

        $this->mediumRepository = new MediumRepository();

        $rawData = file_get_contents('php://input');
        $this->data = json_decode($rawData, true);
        error_log(print_r($this->data, true));
    }

    public function uploadFile()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
                foreach ($_FILES['file']['name'] as $key => $name) {

                    $file = [
                        'name' => $_FILES['file']['name'][$key],
                        'type' => $_FILES['file']['type'][$key],
                        'tmp_name' => $_FILES['file']['tmp_name'][$key],
                        'error' => $_FILES['file']['error'][$key],
                        'size' => $_FILES['file']['size'][$key],
                    ];

                    $fileType = $this->determineMediaType($file['name']);
                    $fileName = $file['name'];
                    $fileSize = $file['size'];

                    $uploadDir = $this->getUploadDirectory($fileType);
                    $uploadFile = $uploadDir . basename($file['name']);
                    $uploadDate =  (new DateTime())->format('Y-m-d');


                    if ($file['error'] !== 0) {
                        throw new Exception('Error while uploading file: ' . $file['error']);
                    }

                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }

                    $this->currentUserId = $_SESSION['currentUser']['Benutzer_ID'];
                    $mediaID = $this->generateUUID();

                    if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
                        $filePath = '/Mediendatenbank/public/uploads/' . $fileType . '/' . basename($file['name']);
                        switch ($fileType) {
                            case 'photo':
                                list($width, $height) = getimagesize($uploadFile);
                                $fileResolution = $width . 'x' . $height;
                                $this->mediumRepository->createPhotoMedium($mediaID, $fileName, $filePath, $fileType, $fileSize, $uploadDate, $fileResolution, $this->currentUserId);
                                break;
                            case 'video':
                                $this->mediumRepository->createVideoMedium($mediaID, $fileName, $filePath, $fileType, $fileSize, $uploadDate, '', '', $this->currentUserId);
                                break;
                            case 'audiobook':
                                $this->mediumRepository->createAudioBookMedium($mediaID, $fileName, $filePath, $fileType, $fileSize, $uploadDate, '', '', $this->currentUserId);
                                break;
                            case 'ebook':
                                $this->mediumRepository->createEbookMedium($mediaID, $fileName, $filePath, $fileType, $fileSize, $uploadDate, '', '', $this->currentUserId);
                                break;
                        }


                        echo json_encode(['status' => 'success', 'message' => 'File erfolgreich hochgeladen.']);
                    } else {
                        throw new Exception('Failed to move uploaded file.');
                    }
                }
            } else {
                throw new Exception('Invalid request.');
            }
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function getAllMediums()
    {
        $searchParameter = $this->data['searchParameter'] ?? '';
        try {
            $media = $this->mediumRepository->readAllMedia($this->currentUserId, $this->data['direction'], $this->data['sortingParameter'], $searchParameter);
            echo json_encode(['status' => 'success', 'data' => $media]); //returns: Photos, Videos, Audiobooks, Ebooks in that order for current user
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function updateMedium()
    {
        $id = $this->data['ID'];
        $title = $this->data['Titel'];
        $this->mediumRepository->updateMedium($id, $title);
    }

    public function getMediaAmountPerUser()
    {
        $userId = $this->data['userId'] ?? '';
        $result = $this->mediumRepository->readMediaAmountPerUser($userId);
        echo json_encode(['status' => 'success', 'data' => $result]);
    }

    public function deleteAllMediaForUser($userId)
    {
        $allMediaIdsForUser = $this->mediumRepository->getAllMediaIdsPerUser($userId);
        
        foreach($allMediaIdsForUser as $mediaId) {
            $this->mediumRepository->deleteMedium($mediaId);
        }
    }

    private function determineMediaType($fileName)
    {
        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        switch ($extension) {
            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'gif':
                return 'photo';
            case 'mp4':
            case 'avi':
            case 'mov':
                return 'video';
            case 'mp3':
            case 'wav':
            case 'aac':
                return 'audiobook';
            case 'pdf':
            case 'epub':
            case 'mobi':
                return 'ebook';
            default:
                throw new Exception('Unsupported file type.');
        }
    }

    private function getUploadDirectory($fileType)
    {
        switch ($fileType) {
            case 'photo':
                return __DIR__ . '/../../public/uploads/photo/';
            case 'video':
                return __DIR__ . '/../../public/uploads/video/';
            case 'audiobook':
                return __DIR__ . '/../../public/uploads/audioBook/';
            case 'ebook':
                return __DIR__ . '/../../public/uploads/ebook/';
            default:
                throw new Exception('Invalid media type.');
        }
    }

    function generateUUID()
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }
}
