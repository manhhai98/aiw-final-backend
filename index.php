<?php

include_once './common/common-header.php';
include_once './repo/TagRepository.php';
include_once './controller/TagController.php';
include_once './repo/CateRepository.php';
include_once './controller/CateController.php';


function __main__()
{
    // get uri and request method
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uri = explode('/', $uri); // turn url -> array['1' => 'v1', '2' => 'path',...]
    $requestMethod = $_SERVER['REQUEST_METHOD']; // get request method (GET,PUT,PATH,POST or DELETE)
    /**
     * Main function to get data depending on uri
     */
    handlePath($uri, $requestMethod);
}

function handlePath($uri, $requestMethod)
{
    $controller = null;
    $mainPath = $uri[2];
    if (!isPathValid($mainPath)) {
        $response =  new ResponseWrapper(null, new ResponseMeta("404", "Invalid path, path not found"));
        echo $response->encodeToJson();
    }
    switch ($mainPath) {
        case TagController::TAG_URI_PATH:
            $controller = new TagController($requestMethod, $uri[3] ?? null);
            break;
        case CateController::CATE_URI_PATH:
            $controller = new CateController($requestMethod, $uri[3] ?? null);
            break;
        default:
            // TODO: handle broken case
            break;
    }
    if ($controller) {
        $controller->processRequest();
    }
}

function isPathValid($path) {
    $validPaths = [TagController::TAG_URI_PATH] = [CateController::CATE_URI_PATH];
    if (in_array($path, $validPaths)) return true;
    else return false;
}


/**
 * initialize main function
 */
__main__();

include_once './config/db-close.php';
