<?php

include_once dirname(__DIR__, 1) . '/repo/TagRepository.php';
include_once dirname(__DIR__, 1) . '/utils/validator-utils.php';

class TagController
{
    const TAG_URI_PATH = "tags";

    private $requestMethod;
    private $tagId;
    private $tagRepository;

    public function __construct($requestMethod, $tagId)
    {
        $this->requestMethod = $requestMethod;
        $this->tagId = $tagId;
        $this->tagRepository = new TagRepository();
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                if ($this->tagId == null) {
                    $this->tagRepository->getAll();
                } else {
                    $this->tagRepository->getById($this->tagId);
                }
                break;
            case 'POST':
                $tag = $this->createTagFromRequest();
                if ($this->validateTag($tag)) {
                    $this->tagRepository->createTag($tag);
                }
                break;
            case 'PUT':
                // TODO: add put operation
                break;
            case 'PATCH':
                // TODO: add patch operations
                break;
            case 'DELETE':
                $this->tagRepository->deleteTag($this->tagId);
                break;
            default:
                $this->invalidMethod();
                break;
        }
    }

    private function createTagFromRequest()
    {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        $tag = new Tag();
        $tag->fromAssocArray($input);
        return $tag;
    }

    private function validateTag(Tag $tag)
    {
        $tagName = $tag->getName();
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
