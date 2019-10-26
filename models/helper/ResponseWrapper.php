<?php

include_once dirname(__DIR__, 1) . '/helper/ResponseMeta.php';

class ResponseWrapper implements BaseModel{
    public static $API_DATA = "data";
    public static $API_META = "meta";

    private $data;
    private $meta;

    public function __construct($data = null, ResponseMeta $meta = null)
    {
        $this->data = $data;
        if ($meta) $this->meta = $meta->toAssocArray();
    }

    public function setData($data) {
        $this->data = $data;
    }

    public function getData() {
        return $this->data;
    }

    public function setMeta(ResponseMeta $meta) {
        $this->meta = $meta->toAssocArray();
    }

    public function getMeta() {
        return $this->meta;
    }

    public function toAssocArray() {
        return array(
            ResponseWrapper::$API_DATA => $this->data,
            ResponseWrapper::$API_META => $this->meta
        );
    }

    public function fromAssocArray($arrayData)
    {
        return new ResponseWrapper(
            $arrayData[ResponseWrapper::$API_DATA],
            $arrayData[ResponseWrapper::$API_META]
        );
    }

    public function encodeToJson()
    {
        return json_encode($this->toAssocArray());
    }

    public function decodeFromJson($jsonData)
    { 
        $assocArray = json_decode($jsonData);
        $object = new ResponseWrapper(
            $assocArray[ResponseWrapper::$API_DATA],
            $assocArray[ResponseWrapper::$API_META]
        );
        return $object;
    }

    public function toString()
    { 
        echo "Response Wrapper :: ";
    }
}

?>