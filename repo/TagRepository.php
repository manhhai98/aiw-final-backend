<?php

include_once './config/db-config.php';
include_once './models/helper/Consts.php';
include_once './models/helper/ResponseWrapper.php';
require_once './models/Tag.php';
require_once './repo/BaseRepository.php';

class TagRepository implements BaseRepository
{
    const TAG_TBL_NAME = "tagsss";

    public function getAll()
    {
        global $conn;
        $response = new ResponseWrapper();
        try {
            $stmt = $conn->prepare("SELECT * FROM " . TagRepository::TAG_TBL_NAME);
            $stmt->execute();
            $data = $stmt->fetchAll();
            $response->setData($data);
            $response->setMeta(ResponseMeta::getSuccessGetMeta());
        } catch (Exception $e) {
            $response->setMeta(new ResponseMeta(
                "400",
                $e->getMessage()
            ));
        }
        echo $response->encodeToJson();
    }

    public function getById($id)
    {
        global $conn;
        $response = new ResponseWrapper();
        try {
            $stmt = $conn->prepare("SELECT * FROM " . TagRepository::TAG_TBL_NAME . " WHERE id=" . $id);
            $stmt->execute();
            $data = $stmt->fetchAll();
            $response->setData($data);
            $response->setMeta(ResponseMeta::getSuccessGetMeta());
        } catch (Exception $e) {
            $response = getGenericError($e);
        }
        echo $response->encodeToJson();
    }

    public function createTag(Tag $tag)
    {
        global $conn;
        $response = new ResponseWrapper();
        try {
            $stmt = $conn->prepare('INSERT INTO ' . TagRepository::TAG_TBL_NAME . ' (name) VALUES (:tagName)');
            $result = $stmt->execute([
                'tagName' => $tag->getName()
            ]);
            if ($result) {
                $response->setData(
                    array(
                        "id" => $conn->lastInsertId(),
                        "name" => $tag->getName()
                    )
                );
                $response->setMeta(ResponseMeta::getSuccessCreateMeta());
            } else {
                $response->setMeta(new ResponseMeta(
                    "422",
                    $stmt->errorInfo()
                ));
            }
        } catch (Exception $e) {
            $response = getGenericError($e);
        }
        echo $response->encodeToJson();
    }

    public function deleteTag($tagId)
    {
        global $conn;
        $response = new ResponseWrapper();
        try {
            // sql to delete a record
            $stmt = $conn->prepare("DELETE FROM " . TagRepository::TAG_TBL_NAME . " WHERE id={$tagId}");
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $response->setMeta(new ResponseMeta("200", "Delete successfully"));
            } else {
                // TODO: check error handling, currently does not return error
                $response->setMeta(new ResponseMeta(
                    "422",
                    $stmt->errorInfo()
                ));
            }
        } catch (Exception $e) {
            $response = getGenericError($e);
        }
        echo $response->encodeToJson();
    }

    public function getGenericError(Exception $e)
    {
        $errResposne = new ResponseWrapper();
        $errResposne->setMeta(new ResponseMeta(
            "400",
            $e->getMessage()
        ));
        return $errResposne;
    }
}
