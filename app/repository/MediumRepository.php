<?php

namespace App\Repository;

use App\Database\DbConnection;

class MediumRepository
{
    private $conn;

    public function __construct()
    {
        $this->conn = DbConnection::getInstance()->getConnection();
    }

    public function createPhotoMedium($id, $fileName, $filePath, $fileType, $fileSize, $uploadDate,  $resolution, $userId)
    {
        $stmt = $this->conn->prepare("INSERT INTO Fotos (Foto_ID, Titel, Dateipfad, Typ, Dateigröße, Hochlade_datum, Auflösung, Benutzer_ID) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssi", $id, $fileName, $filePath, $fileType, $fileSize, $uploadDate, $resolution, $userId);
        $stmt->execute();
        $stmt->close();
    }

    public function createVideoMedium($id, $fileName, $filePath, $fileType, $fileSize, $uploadDate,  $resolution, $duration, $userId)
    {
        $stmt = $this->conn->prepare("INSERT INTO Videos (Video_ID, Titel, Dateipfad, Typ, Dateigröße, Hochlade_datum, Auflösung, Dauer, Benutzer_ID) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssi", $id, $fileName, $filePath, $fileType, $fileSize, $uploadDate, $resolution, $duration, $userId);
        $stmt->execute();
        $stmt->close();
    }

    public function createAudioBookMedium($id, $fileName, $filePath, $fileType, $fileSize, $uploadDate,  $speaker, $duration, $userId)
    {
        $stmt = $this->conn->prepare("INSERT INTO Hörbücher (Hörbuch_ID, Titel, Dateipfad, Typ, Dateigröße, Hochlade_datum, Sprecher, Dauer, Benutzer_ID) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssi", $id, $fileName, $filePath, $fileType, $fileSize, $uploadDate, $speaker, $duration, $userId);
        $stmt->execute();
        $stmt->close();
    }

    public function createEbookMedium($id, $fileName, $filePath, $fileType, $fileSize, $uploadDate,  $author, $pages, $userId)
    {
        $stmt = $this->conn->prepare("INSERT INTO Ebooks (ebook_ID, Titel, Dateipfad, Typ, Dateigröße, Hochlade_datum, Autor, Seitenzahl, Benutzer_ID) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssii", $id, $fileName, $filePath, $fileType, $fileSize, $uploadDate, $author, $pages, $userId);
        $stmt->execute();
        $stmt->close();
    }

    public function updateMedium( $id, $title)
    {
        $tableType = $this->getMediaTypeById($id);
        $idQuery = $tableType . "_ID";
        $stmt = $this->conn->prepare("UPDATE $tableType SET Titel = ? WHERE $idQuery  = ?");
        $stmt->bind_param("si", $title, $id);
        $stmt->execute();
    }

    public function readAllMedia($currentUserId, $direction, $sortingParamter, $searchParameter) //sort asc/desc/size/date
    {
        $mediaTypes = ['Fotos', 'Videos', 'Hörbücher', 'Ebooks'];
        $results = [];

        foreach ($mediaTypes as $type) {

            $query = "SELECT * FROM $type WHERE Benutzer_ID = ?";

            if ($searchParameter) {
                $query .= " AND Titel = '%?%'";
            }

            $query .= " ORDER BY $sortingParamter $direction";
            $stmt = $this->conn->prepare($query);

            if ($searchParameter) {
                $stmt->bind_param("ss", $currentUserId, $searchParameter);
            } else {
                $stmt->bind_param("s", $currentUserId);
            }

            $stmt->execute();
            $result = $stmt->get_result();
            $mediaData = [];
            while ($row = $result->fetch_assoc()) {
                $mediaData[] = $row;
            }
            $results[$type] = $mediaData;
            $stmt->close();
        }

        return $results;
    }

    public function readMediaAmountPerUser($userId)
    {
        $mediaTypes = ['Fotos', 'Videos', 'Hörbücher', 'Ebooks'];
        $results = [];

        foreach ($mediaTypes as $type) {
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM $type WHERE Benutzer_ID = ?");
            $stmt->bind_param("s", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            $mediaData = [];
            while ($row = $result->fetch_assoc()) {
                $mediaData[] = $row;
            }
            $results[$type] = $mediaData;
            $stmt->close();
        }

        return $results;
    }
    public function getMediaTypeById($id)
    {
        $tables = ['Fotos', 'Videos', 'Hörbücher', 'Ebooks'];
        foreach ($tables as $table) {
            $query = "SELECT filetype FROM $table WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("s", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $stmt->close();
                return $row['filetype'];
            }
            $stmt->close();
        }
        return null;
    }

    public function deleteMedium($id)
    {
        $tablename = $this->getMediaTypeById($id);
        $idQuery = $tablename . "_ID";
        $stmt = $this->conn->prepare("DELETE FROM $tablename WHERE $idQuery = ?");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $stmt->close();
    }

    public function getAllMediaIdsPerUser($userId)
    {
        $mediaTypes = ['Fotos', 'Videos', 'Hörbücher', 'Ebooks'];
        $results = [];

        foreach ($mediaTypes as $type) {
            $stmt = $this->conn->prepare("SELECT ID FROM $type WHERE Benutzer_ID = ?");
            $stmt->bind_param("s", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            $mediaData = [];
            while ($row = $result->fetch_assoc()) {
                $mediaData[] = $row;
            }
            $results[$type] = $mediaData;
            $stmt->close();
        }

        return $results;
    }
}
