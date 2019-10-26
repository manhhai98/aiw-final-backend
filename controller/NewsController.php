<?php

include_once dirname(__DIR__, 1) . '/repo/NewsRepository.php';
include_once dirname(__DIR__, 1) . '/utils/validator-utils.php';

class NewsController
{
    const NEWS_URI_PATH = "news";

    private $requestMethod;
    private $newsId;
    private $newsRepository;

    public function __construct($requestMethod, $newsId)
    {
        $this->requestMethod = $requestMethod;
        $this->newsId = $newsId;
        $this->newsRepository = new NewsRepository();
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                if ($this->newsId == null) {
                    $this->newsRepository->getAll();
                } else {
                    $this->newsRepository->getById($this->newsId);
                }
                break;
            case 'POST':
                $news = $this->createNewsFromRequest();
                if ($this->validateNews($news)) {
                    $this->newsRepository->createNews($news);
                }
                break;
            case 'PUT':
                // TODO: add put operation
                break;
            case 'PATCH':
                // TODO: add patch operations
                break;
            case 'DELETE':
                $this->newsRepository->deleteTag($this->newsId);
                break;
            default:
                $this->invalidMethod();
                break;
        }
    }

    private function createNewsFromRequest()
    {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        $news = new News();
        $news->fromAssocArray($input);
        return $news;
    }

    private function validateNews(News $news)
    {
        $newsName = $news->getName();
        $hasError = false;
        $validateErrorMessage = "";
        if (empty($tagName)) { 
            $hasError = true;
            $validateErrorMessage = "Tag name must not be empty";
        }
        if (strlen($tagName) > 255) { 
            $hasError = true;
            $validateErrorMessage = "Tag name must not exceed 255 characters";
        }
        // Uncommented due to error
        // if (ValidatorUtils::hasSpecialChars($tagName)) {
        //     $hasError = true;
        //     $validateErrorMessage = "Tag name must not contains special characters";
        // }

        if ($hasError) {
            $errResponse = new ResponseWrapper(null, new ResponseMeta(
                "400",
                $validateErrorMessage
            ));
            echo $errResponse->encodeToJson();
            return false;
        } else {
            return true;
        }
    }

    private function invalidMethod()
    {
        $errResponse = new ResponseWrapper(null, new ResponseMeta(
            "405",
            "Method not allowed"
        ));
        echo $errResponse->encodeToJson();
    }
}
