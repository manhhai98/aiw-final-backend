<?php

interface BaseModel {
    public function encodeToJson();
    public function decodeFromJson($jsonData);
    public function toAssocArray();
    public function fromAssocArray($arrayData);
    public function toString();
}

?>