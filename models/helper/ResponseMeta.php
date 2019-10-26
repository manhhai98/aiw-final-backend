<?php

include_once dirname(__DIR__, 1) . '/helper/BaseModel.php';

class ResponseMeta implements BaseModel {
    public static $API_HTTP_CODE = "httpCode";
    public static $API_MESSAGE = "message";

    private $httpCode;
    private $message;

    public function __construct($httpCode = null, $message = null)
    {
        $this->httpCode = $httpCode;
        $this->message = $message;
    }

    public function setHttpCode($httpCode) {
        $this->httpCode = $httpCode;
    }

    public function getHttpCode() {
        return $this->httpCode;
    }

    public function setMessage($message) {
        $this->message = $message;
    }

    public function getMessage() {
        return $this->message;
    }

    public static function getSuccessGetMeta() {
        return new ResponseMeta(
            Consts::$RESPONSE_GET_OK_CODE, 
            Consts::$RESPONSE_OK_MESSAGE
        );
    }

    public static function getSuccessCreateMeta() {
        return new ResponseMeta(
            Consts::$RESPONSE_CREATE_OK_CODE, 
            Consts::$RESPONSE_CREATE_OK_MESSAGE
        );
    }

    public function toAssocArray() {
        return array(
            ResponseMeta::$API_HTTP_CODE => $this->httpCode,
            ResponseMeta::$API_MESSAGE => $this->message
        );
    }

    public function fromAssocArray($arrayData)
    {
        return new ResponseMeta(
            $arrayData[ResponseMeta::$API_HTTP_CODE],
            $arrayData[ResponseMeta::$API_MESSAGE]
        );
    }

    public function encodeToJson()
    {
        return json_encode($this->toAssocArray());
    }

    public function decodeFromJson($jsonData)
    { 
        $assocArray = json_decode($jsonData);
        $object = new ResponseMeta(
            $assocArray[ResponseMeta::$API_HTTP_CODE],
            $assocArray[ResponseMeta::$API_MESSAGE]
        );
        return $object;
    }

    public function toString()
    { 
        echo "ResponseMeta :: ";
    }
}

?>