<?php

namespace App\Repository;

use App\database\DbConnection;
use App\Model\KeywordModel;

class KeywordRepository
{

    private $conn;
    public function __construct()
    {
        $this->conn = DbConnection::getInstance()->getConnection();
    }

    public function createKeyword($keyword, $currentUserId)
    {
        $keywordName = $keyword->getName();
        $stmt = $this->conn->prepare("INSERT INTO Schlagworte (Schlagwort_Name, Benutzer_ID) VALUES (?, ?)");
        $stmt->bind_param("ss", $keywordName, $currentUserId);
        $stmt->execute();
        $stmt->close();
    }

    public function updateKeywordName($keywordId, $keywordName)
    {
        $stmt = $this->conn->prepare("UPDATE Schlagworte SET Schlagwort_Name = ? WHERE Schlagwort_ID = ?");
        $stmt->bind_param("si", $keywordName, $keywordId);
        $stmt->execute();
        $stmt->close();
    }

    public function deleteKeyword(int $keywordId)
    {
        $stmt = $this->conn->prepare("DELETE FROM SchlagwortMedien WHERE Schlagwort_ID = ?");
        $stmt->bind_param("i", $keywordId);
        $stmt->execute();
        $stmt->close();

        $stmt = $this->conn->prepare("DELETE FROM Schlagworte WHERE Schlagwort_ID = ?");
        $stmt->bind_param("i", $keywordId);
        $stmt->execute();
        $stmt->close();
    }


    public function readAllKeywordsWithAssociations($currentUserId)
    {
        $keywords = [];

        $stmt = $this->conn->prepare("SELECT * FROM Schlagworte WHERE Benutzer_ID = ?");
        $stmt->bind_param("s", $currentUserId);
        $stmt->execute();
        $resultKeywords = $stmt->get_result();

        while ($keyword = $resultKeywords->fetch_assoc()) {
            $keywords[] = $keyword;
        }
        $stmt->close();

        $associations = [];
        $stmt = $this->conn->prepare("SELECT * FROM SchlagwortMedien WHERE Schlagwort_ID = ?");

        while ($keyword = $resultKeywords->fetch_assoc()) {
            $keywordId = $keyword['Schlagwort_ID'];
            $stmt->bind_param("i", $keywordId);
            $stmt->execute();
            $resultAssociations = $stmt->get_result();

            while ($association = $resultAssociations->fetch_assoc()) {
                $associations[] = $association;
            }
        }
        $stmt->close();
        return [$keywords, $associations];
    }

    public function assignKeywordToMedia($keywordId, $mediaId)
    {

        $stmt = $this->conn->prepare("INSERT INTO SchlagwortMedien (Schlagwort_ID, Medium_ID) VALUES (?, ?)");
        $stmt->bind_param("ss", $keywordId, $mediaId);
        $stmt->execute();
        $stmt->close();
    }

    public function removeKeywordFromMedia($keywordId, $mediaId)
    {
        $stmt = $this->conn->prepare("DELETE FROM SchlagwortMedien WHERE Schlagwort_ID = ? AND Medium_ID = ?");
        $stmt->bind_param("ss", $keywordId, $mediaId);
        $stmt->execute();
        $stmt->close();
    }

    public function readKeywordAmountPerUser($userId)
    {
        $stmt = $this->conn->prepare("SELECT COUNT(Schlagwort_ID) FROM Schlagworte WHERE Benutzer_ID = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $amount[] = $row;
        }
        $stmt->close();
        return $amount;
    }

    public function getkeywordsforSentMedia($mediaIds)
    {
        $associations = [];
        foreach ($mediaIds as $mediaId) {
            $stmt = $this->conn->prepare("SELECT * FROM SchlagwortMedien WHERE Medium_ID = ?");
            $stmt->bind_param("s", $mediaId);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $associations[] = $row;
            }
            $stmt->close();
        }
    }
}
