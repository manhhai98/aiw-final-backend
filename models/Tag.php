<?php

include_once dirname(__DIR__, 1) . '/models/helper/BaseModel.php';

class Tag implements BaseModel
{
    public static $API_TAG_ID = "id";
    public static $API_TAG_NAME = "name";

    private $id;
    private $name;

    public function __construct($name = "")
    {
        $this->name = $name;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function toAssocArray() {
        return array(
            Tag::$API_TAG_NAME => $this->name
        );
    }

    public function fromAssocArray($dataArray) {
        $this->name = $dataArray[Tag::$API_TAG_NAME];
        return $this;
    }

    public function encodeToJson()
    {
        return json_encode($this->toAssocArray());
    }

    public function decodeFromJson($jsonData)
    { 
        $assocArray = json_decode($jsonData);
        $object = new Tag($assocArray[Tag::$API_TAG_NAME]);
        return $object;
    }

    public function toString()
    { 
        echo "Object: [Tag: id= {$this->getId()}, name= {$this->getName()}]";
    }
}
