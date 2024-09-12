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

    public function createPhotoMedium($fileName, $filePath, $fileType, $fileSize, $uploadDate,  $resolution, $userId)
    {
        $stmt = $this->conn->prepare("INSERT INTO Fotos (Titel, Dateipfad, Typ, Dateigröße, Hochlade_datum, Auflösung, Benutzer_ID) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssi", $fileName, $filePath, $fileType, $fileSize, $uploadDate, $resolution, $userId);
        $stmt->execute();
        $stmt->close();
    }

    public function createVideoMedium($fileName, $filePath, $fileType, $fileSize, $uploadDate,  $resolution, $duration, $userId)
    {
        $stmt = $this->conn->prepare("INSERT INTO Videos (Titel, Dateipfad, Typ, Dateigröße, Hochlade_datum, Auflösung, Dauer, Benutzer_ID) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssi", $fileName, $filePath, $fileType, $fileSize, $uploadDate, $resolution, $duration, $userId);
        $stmt->execute();
        $stmt->close();
    }

    public function createAudioBookMedium($fileName, $filePath, $fileType, $fileSize, $uploadDate,  $speaker, $duration, $userId)
    {
        $stmt = $this->conn->prepare("INSERT INTO Hörbücher (Titel, Dateipfad, Typ, Dateigröße, Hochlade_datum, Sprecher, Dauer, Benutzer_ID) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssi", $fileName, $filePath, $fileType, $fileSize, $uploadDate, $speaker, $duration, $userId);
        $stmt->execute();
        $stmt->close();
    }

    public function createEbookMedium($fileName, $filePath, $fileType, $fileSize, $uploadDate,  $author, $pages, $userId)
    {
        $stmt = $this->conn->prepare("INSERT INTO Ebooks (Titel, Dateipfad, Typ, Dateigröße, Hochlade_datum, Autor, Seitenzahl, Benutzer_ID) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssii", $fileName, $filePath, $fileType, $fileSize, $uploadDate, $author, $pages, $userId);
        $stmt->execute();
        $stmt->close();
    }

    public function deleteMedium(int $id, string $type) {
        
    }
    
    public function updateMedium(int $id, $fileType, $title, $resolution ='', $duration='', $speaker='', $author='', $pages='') {
        switch($fileType){
            case'photo';
                $stmt = $this->conn->prepare("UPDATE Fotos SET Titel = ?,  Auflösung = ? WHERE ID = ?");
                $stmt->bind_param("ssi", $title, $resolution, $id);
                $stmt->execute();
                $stmt->close();
            case'video';
                $stmt = $this-> conn->prepare("UPDATE Fotos SET Titel = ?, Auflösung = ?, Dauer = ? WHERE ID = ?");
                $stmt->bind_param("sssi", $title, $resolution, $duration, $id);
                $stmt->execute();
                $stmt->close();
            case'audiobook';
                $stmt = $this-> conn->prepare("UPDATE Fotos SET Titel = ?, Sprecher = ?, Dauer = ? WHERE ID = ?");
                $stmt->bind_param("sssi", $title, $speaker, $duration, $id);
                $stmt->execute();
                $stmt->close();
            case'ebook';
                $stmt = $this-> conn->prepare("UPDATE Fotos SET Titel = ?, Autor = ?, Seitenzahl = ? WHERE ID = ?");
                $stmt->bind_param("ssii", $title, $author, $pages, $id);
                $stmt->execute();
                $stmt->close();
        }        
    }

    public function readAllMedia($currentUserId)
    {
    $mediaTypes = ['Fotos', 'Videos', 'Hörbücher', 'Ebooks'];
    $results = [];

    foreach ($mediaTypes as $type) {
        $stmt = $this->conn->prepare("SELECT * FROM $type WHERE Benutzer_ID = ?");
        $stmt->bind_param("s", $currentUserId);
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
