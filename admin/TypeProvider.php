<?php

spl_autoload_register(function($class_name){
    include $class_name . ".php";
});


class TypeProvider extends DataProvider
{
    /**
     * Get all brands in database
    */
    public function getAllType()
    {
        $arrType = [];
        $conn = $this->connect();
        $cmd = "SELECT * FROM type";
        $res = mysqli_query($conn, $cmd);
        while($row = mysqli_fetch_array($res)){
            $type = new Type();
            $type->typeId = $row['typeId'];
            $type->typeName = $row['typeName'];
            $type->typeImage = $row['typeImage'];
            $type->dateCreated = $row['dateCreated'];
            $type->dateModified = $row['dateModified'];

            $arrType[] = $type;
        }

        $conn->close();
        return $arrType;
    }

    /**
     * Get a type specified by its ID
     * @param $typeId: The ID of the type
     * @return $type: The Type object
     */
    public function getTypeById($typeId)
    {
        $conn = $this->connect();
        $cmd = "SELECT * FROM type WHERE typeId = $typeId";
        $res = mysqli_query($conn, $cmd);
        $type = new Type();
        while($row = mysqli_fetch_array($res)){
            $type->typeId = $row['typeId'];
            $type->typeName = $row['typeName'];
            $type->typeImage = $row['typeImage'];
        }
        return $type;
    }

    /**
     * Add a type to database
     * @param $type: Type object
     * @throws $message: input param is not Brand object
    */
    public function addType($type)
    {
        if(!get_class($type) == "Type"){
            throw new Exception("Input param is not a Type object");
            return;
        }
        $conn = $this->connect();
        $cmd = "INSERT INTO type (typeName, typeImage, dateCreated, dateModified) VALUES (?, ?, NOW(), NOW())";
        $stm = $conn->prepare($cmd);
        $stm->bind_param('ss', $type->typeName, $type->typeImage);
        $stm->execute();

        $stm->close();
        $conn->close();
    }

    /**
     * Update a type in database
     * @param $type: Type object
     * @throws $mess: Input param not a Brand object
    */
    public function updateType($type)
    {
        if(!get_class($type) == "Type"){
            throw new Exception("Input param is not a Type object");
            return;
        }
        $conn = $this->connect();
        $cmd = "UPDATE type SET typeName=?, typeImage=?, dateModified=NOW() WHERE typeId='" . $type->typeId . "'";
        $stm = $conn->prepare($cmd);
        $stm->bind_param('ss', $type->typeName, $type->typeImage);
        $stm->execute();

        $stm->close();
        $conn->close();
    }

    /**
     * Delete a type in database
     * @param $typeId: Type's Id object
    */
    public function deleteType($typeId)
    {
        $conn = $this->connect();
        $cmd = "DELETE FROM type WHERE typeId = '" . $typeId . "'";
        mysqli_query($conn, $cmd);

        $conn->close();
    }
}