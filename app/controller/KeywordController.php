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
        $this->keywordRepository = new KeywordRepository();
    }

    public function createKeyword(string $keywordName)
    {
        $keyword = new KeywordModel($keywordName);
        $this->keywordRepository->createKeyword($keyword, $this->currentUserId);
    }

    public function updateKeyword(int $keywordId, string $keywordName)
    {
        $this->keywordRepository->updateKeywordName($keywordId, $keywordName);
    }

    public function createAssociation(int $keywordId, int $mediumId)
    {
        $this->keywordRepository->assignKeywordToMedia($keywordId, $mediumId);
    }

    public function deleteAssociation(int $keywordId, int $mediumId)
    {
        $this->keywordRepository->removeKeywordFromMedia($keywordId, $mediumId);
    }

    public function getAllKeywordsAndAssociations()
    {
        $keywordsAndAssociations = $this->keywordRepository->readAllKeywordsWithAssociations($this->currentUserId);
        echo json_encode(['status' => 'success', 'data' => $keywordsAndAssociations]);
    }
    public function deleteKeyword(int $keywordId)
    {
        $this->keywordRepository->deleteKeyword($keywordId);
    }
}
