<?php

spl_autoload_register(function($class_name){
    include $class_name . ".php";
});


class BrandProvider extends DataProvider
{
    /**
     * Get all brands in database
    */
    public function getAllBrand()
    {
        $arrBrand = [];
        $conn = $this->connect();
        $cmd = "SELECT * FROM brand";
        $res = mysqli_query($conn, $cmd);
        while($row = mysqli_fetch_array($res)){
            $brand = new Brand();
            $brand->brandId = $row['brandId'];
            $brand->brandName = $row['brandName'];
            $brand->brandDesc = $row['brandDesc'];
            $brand->brandLogo = $row['brandLogo'];
            $brand->dateCreated = $row['dateCreated'];
            $brand->dateModified = $row['dateModified'];

            $arrBrand[] = $brand;
        }

        $conn->close();
        return $arrBrand;
    }

    /**
     * Get a brand by its Id in database
     * @param $brandId : id of the brand
     * @return Brand: Brand object
     */
    public function getBrandById($brandId)
    {
        $conn = $this->connect();
        $cmd = "SELECT * FROM brand WHERE brandId = '". $brandId ."'";
        $res = mysqli_query($conn, $cmd);
        $brand = new Brand();
        while($row = mysqli_fetch_array($res)){
            $brand->brandId = $row['brandId'];
            $brand->brandName = $row['brandName'];
            $brand->brandDesc = $row['brandDesc'];
            $brand->brandLogo = $row['brandLogo'];
            $brand->dateCreated = $row['dateCreated'];
            $brand->dateModified = $row['dateModified'];
        }
        $conn->close();
        return $brand;
    }

    /**
     * Add a brand to database
     * @param $brand: Brand object
     * @throws $message: input param is not Brand object
    */
    public function addBrand($brand)
    {
        if(!get_class($brand) == "Brand"){
            throw new Exception("Input param is not a Brand object");
            return;
        }
        $conn = $this->connect();
        $cmd = "INSERT INTO brand (brandName, brandLogo, brandDesc, dateCreated, dateModified) VALUES (?, ?, ?, NOW(), NOW())";
        $stm = $conn->prepare($cmd);
        $stm->bind_param('sss', $brand->brandName, $brand->brandLogo, $brand->brandDesc);
        $stm->execute();

        $stm->close();
        $conn->close();
    }

    /**
     * Update a brand in database
     * @param $brand: Brand object
     * @throws $mess: Input param not a Brand object
    */
    public function updateBrand($brand)
    {
        if(!get_class($brand) == "Brand"){
            throw new Exception("Input param is not a Brand object");
            return;
        }
        $conn = $this->connect();
        $cmd = "UPDATE brand SET brandName=?, brandLogo=?, brandDesc=?, dateModified=NOW() WHERE brandId='". $brand->brandId ."'";
        $stm = $conn->prepare($cmd);
        $stm->bind_param('sss', $brand->brandName, $brand->brandLogo, $brand->brandDesc);
        $stm->execute();

        $stm->close();
        $conn->close();
    }

    /**
     * Delete a brand in database
     * @param $brandId: Brand's Id object
    */
    public function deleteBrand($brandId)
    {
        $conn = $this->connect();
        $cmd = "DELETE FROM brand WHERE brandId = '" . $brandId . "'";
        mysqli_query($conn, $cmd);

        $conn->close();
    }
}