<?php

namespace App\Controller;

use App\Core\Controller;
use App\Model\KeywordModel;
use App\Repository\KeywordRepository;
use Exception;

class KeywordController extends Controller
{
    private $keywordRepository;
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
        }
        $this->keywordRepository = new KeywordRepository();

        $rawData = file_get_contents('php://input');
        $this->data = json_decode($rawData, true);
        error_log(print_r($this->data, true));
    }

    public function createKeyword()
    {
        $keywordName = $this->data['keywordName'] ?? '';
        $keyword = new KeywordModel($keywordName);
        $this->keywordRepository->createKeyword($keyword, $this->currentUserId);
    }

    public function updateKeyword()
    {
        $keywordId = $this->data['keywordId'] ?? 0;
        $keywordName = $this->data['keywordName'] ?? '';
        $this->keywordRepository->updateKeywordName($keywordId, $keywordName);
    }

    public function createAssociation()
    {
        $keywordId = $this->data['keywordId'] ?? 0;
        $mediumId = $this->data['mediumId'] ?? 0;
        $this->keywordRepository->assignKeywordToMedia($keywordId, $mediumId);
    }

    public function deleteAssociation()
    {
        $keywordId = $this->data['keywordId'] ?? 0;
        $mediumId = $this->data['mediumId'] ?? 0;
        $this->keywordRepository->removeKeywordFromMedia($keywordId, $mediumId);
    }

    public function getAllKeywordsAndAssociations()
    {
        $mediumId = $this->data['mediumId'] ?? 0;
        $mediaKeywords = $this->keywordRepository->getkeywordsforSentMedia($mediumId);
        echo json_encode(['status' => 'success', 'data' => $mediaKeywords]);
    }

    public function getKeywordsForSentMedia()
    {
        $keywordsAndAssociations = $this->keywordRepository->readAllKeywordsWithAssociations($this->currentUserId);
        echo json_encode(['status' => 'success', 'data' => $keywordsAndAssociations]);
    }

    public function deleteKeyword()
    {
        $keywordId = $this->data['keywordId'] ?? 0;
        $this->keywordRepository->deleteKeyword($keywordId);
    }

    public function readKeywordPerUser()
    {
        $userId = $this->data['userId'] ?? 0;
        $keywordsPerUser = $this->keywordRepository->readKeywordAmountPerUser($userId);
        echo json_encode(['status' => 'success', 'data' => $keywordsPerUser]);
    }

    public function deleteAllKeywordsAndAssociations($userId)
    {
        $data = $this->keywordRepository->readAllKeywordsWithAssociations($userId);
        $keywords = $data['keywords'];
        $associations = $data['associations'];

        foreach ($associations as $association) {
            $this->keywordRepository->removeKeywordFromMedia($association['Schlagwort_ID'], $association['Medium_ID']);
        }
        
        foreach ($keywords as $keyword) {
            $this->keywordRepository->deleteKeyword($keyword['Schlagwort_ID']);
        }
    }
}
