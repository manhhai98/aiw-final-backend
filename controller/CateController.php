<?php

include_once dirname(__DIR__, 1) . '/repo/CateRepository.php';
include_once dirname(__DIR__, 1) . '/utils/validator-utils.php';

class CateController
{
    const CATE_URI_PATH = "categories";

    private $requestMethod;
    private $cateId;
    private $cateRepository;

    public function __construct($requestMethod, $cateId)
    {
        $this->requestMethod = $requestMethod;
        $this->cateId = $cateId;
        $this->cateRepository = new CateRepository();
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                if ($this->cateId == null) {
                    $this->cateRepository->getAll();
                } else {
                    $this->cateRepository->getById($this->cateId);
                }
                break;
            case 'POST':
                $cate = $this->createCateFromRequest();
                if ($this->validateCate($cate)) {
                    $this->cateRepository->createCate($cate);
                }
                break;
            case 'PUT':
                // TODO: add put operation
                break;
            case 'PATCH':
                // TODO: add patch operations
                break;
            case 'DELETE':
                $this->cateRepository->deleteCate($this->cateId);
                break;
            default:
                $this->invalidMethod();
                break;
        }
    }

    private function createCateFromRequest()
    {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        $cate = new Cate();
        $cate->fromAssocArray($input);
        return $cate;
    }

    private function validateCate(Cate $cate)
    {
        $cateName = $cate->getName();
        $hasError = false;
        $validateErrorMessage = "";
        if (empty($cateName)) { 
            $hasError = true;
            $validateErrorMessage = "Category name must not be empty";
        }
        if (strlen($cateName) > 255) { 
            $hasError = true;
            $validateErrorMessage = "Category name must not exceed 255 characters";
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
