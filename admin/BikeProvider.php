<?php

spl_autoload_register(function($class_name){
   include_once $class_name . ".php";
});

class BikeProvider extends DataProvider
{
    /**
     * Get info all bikes
    */
    public function getAllBike() : iterable
    {
        $arrBike = [];

        $conn = $this->connect();
        $cmd = "SELECT * FROM bike";
        $res = mysqli_query($conn, $cmd);
        while($row = mysqli_fetch_array($res)){
            $bike = new Bike();
            $bike->bikeId = $row['bikeId'];
            $bike->bikeName = $row['bikeName'];
            $bike->bikeBrand = $row['bikeBrand'];
            $bike->bikeType = $row['bikeType'];
            $bike->bikeImage = $row['bikeImage'];
            $bike->bikePrice = $row['bikePrice'];
            $bike->bikeDiscountPrice = $row['bikeDiscountPrice'];
            $bike->bikeShortDesc = $row['bikeShortDesc'];
            $bike->bikeGift = $row['bikeGift'];
            $bike->bikeHighlight = $row['bikeHighlight'];
            $bike->bikeSpecs = $row['bikeSpecs'];
            $bike->bikeGallery = $row['bikeGallery'];
            $bike->dateCreated = $row['dateCreated'];
            $bike->dateModified = $row['dateModified'];

            $arrBike[] = $bike;

        }

        $conn->close();
        return $arrBike;
    }

    /**
     * Get info of all new 10 bikes
    */
    public function getNewBikes($num = 'all', $sort = 'default') : iterable
    {
        $arrBike = [];

        $conn = $this->connect();
        //Select all bikes from 5 days ago until now
        $cmd = "SELECT * FROM bike WHERE dateModified > (DATE_SUB(CURRENT_DATE, INTERVAL 5 DAY)) ";
        if($num != 'all'){
            $cmd .= " LIMIT 8 OFFSET " . (int) $num;
        }
        if($sort != 'default'){
            $arr_sort = explode("_", $sort);
            $dir = $arr_sort[1] == "up" ? 'ASC' : 'DESC';
            $cmd .= " ORDER BY bikePrice " . $dir;
        }
        //print_r($cmd);
        $res = mysqli_query($conn, $cmd);
        while($row = mysqli_fetch_array($res)){
            $bike = new Bike();
            $bike->bikeId = $row['bikeId'];
            $bike->bikeName = $row['bikeName'];
            $bike->bikeBrand = $row['bikeBrand'];
            $bike->bikeType = $row['bikeType'];
            $bike->bikeImage = $row['bikeImage'];
            $bike->bikePrice = $row['bikePrice'];
            $bike->bikeDiscountPrice = $row['bikeDiscountPrice'];
            $bike->bikeShortDesc = $row['bikeShortDesc'];
            $bike->bikeGift = $row['bikeGift'];
            $bike->bikeHighlight = $row['bikeHighlight'];
            $bike->bikeSpecs = $row['bikeSpecs'];
            $bike->bikeGallery = $row['bikeGallery'];
            $bike->dateCreated = $row['dateCreated'];
            $bike->dateModified = $row['dateModified'];

            $arrBike[] = $bike;

        }

        $conn->close();
        return $arrBike;
    }

    /**
     * Get info of all new 10 bikes
    */
    public function getPromotedBikes($num = 'all', $sort = 'default') : iterable
    {
        $arrBike = [];

        $conn = $this->connect();
        //Select all bikes from 10 days ago until now
        $cmd = "SELECT * FROM bike WHERE bikeDiscountPrice > 0 ";
        if($num != 'all'){
            $cmd .= " LIMIT 8 OFFSET " . (int) $num;
        }
        if($sort != 'default'){
            $arr_sort = explode("_", $sort);
            $dir = $arr_sort[1] == "up" ? 'ASC' : 'DESC';
            $cmd .= " ORDER BY bikePrice " . $dir;
        }

        $res = mysqli_query($conn, $cmd);
        while($row = mysqli_fetch_array($res)){
            $bike = new Bike();
            $bike->bikeId = $row['bikeId'];
            $bike->bikeName = $row['bikeName'];
            $bike->bikeBrand = $row['bikeBrand'];
            $bike->bikeType = $row['bikeType'];
            $bike->bikeImage = $row['bikeImage'];
            $bike->bikePrice = $row['bikePrice'];
            $bike->bikeDiscountPrice = $row['bikeDiscountPrice'];
            $bike->bikeShortDesc = $row['bikeShortDesc'];
            $bike->bikeGift = $row['bikeGift'];
            $bike->bikeHighlight = $row['bikeHighlight'];
            $bike->bikeSpecs = $row['bikeSpecs'];
            $bike->bikeGallery = $row['bikeGallery'];
            $bike->dateCreated = $row['dateCreated'];
            $bike->dateModified = $row['dateModified'];

            $arrBike[] = $bike;

        }

        $conn->close();
        return $arrBike;
    }

    /**
     * Get info of all new 10 bikes
     * @param $keyword: keyword to filter
     * @param $column_name: column to search keyword
    */
    public function getBikesByColumn($keyword, $column_name) : iterable
    {
        $arrBikes = [];
        $conn = $this->connect();

        $cmd = "SELECT * FROM bike WHERE " . $column_name . " LIKE '%". $keyword ."%'";

        $res = mysqli_query($conn, $cmd);
        while($row = mysqli_fetch_array($res)){
            $bike = new Bike();
            $bike->bikeId = $row['bikeId'];
            $bike->bikeName = $row['bikeName'];
            $bike->bikeBrand = $row['bikeBrand'];
            $bike->bikeType = $row['bikeType'];
            $bike->bikeImage = $row['bikeImage'];
            $bike->bikePrice = $row['bikePrice'];
            $bike->bikeDiscountPrice = $row['bikeDiscountPrice'];
            $bike->bikeShortDesc = $row['bikeShortDesc'];
            $bike->bikeGift = $row['bikeGift'];
            $bike->bikeHighlight = $row['bikeHighlight'];
            $bike->bikeSpecs = $row['bikeSpecs'];
            $bike->bikeGallery = $row['bikeGallery'];
            $bike->dateCreated = $row['dateCreated'];
            $bike->dateModified = $row['dateModified'];

            $arrBikes[] = $bike;

        }

        $conn->close();
        return $arrBikes;
    }

    /**
     * Get bikes by query string
     * @param $query: keyword to filter
     * @param $sort: sort option
     */
    public function getBikesByQuery($query, $sort='default', $action='default', $num='all') : iterable
    {
        $arrBikes = [];
        $conn = $this->connect();
        $arr = explode("&", $query);
        $arrType = [];
        $arr_type_query = [];
        $arrBrand = [];
        $arr_brand_query = [];
        $arr_query = [];

        $cmd = "SELECT * FROM bike WHERE 1=1 AND ";
        if(count($arr) > 1){//== 2
            $arrType = explode("=", $arr[0]);
            $arr_type_query = explode("|", $arrType[1]);
            $arrBrand = explode("=", $arr[1]);
            $arr_brand_query = explode("|", $arrBrand[1]);
            if($arrType[0] == "q") $arrType[0] = ['bikeHighlight', 'bikeName', 'bikeShortDesc'];

            if(count($arr_type_query) > 0){
                foreach($arr_type_query as $item){
                    if(count($arrBrand) > 0){
                        foreach($arr_brand_query as $q_brand){
                            if(is_array($arrType[0])){
                                foreach($arrType[0] as $arr_col){
                                    $cmd .= $arr_col . " LIKE '%". $item ."%' AND " . $arrBrand[0] . " LIKE '%". $q_brand ."%' OR ";
                                }
                                $cmd = substr($cmd, 0, strlen($cmd) - 4);
                            }
                            else{
                                $cmd .= $arrType[0] . " LIKE '%". $item ."%' AND " . $arrBrand[0] . " LIKE '%". $q_brand ."%'";
                            }
                            if($action == 'new'){
                                $cmd .= " AND dateModified > (DATE_SUB(CURRENT_DATE, INTERVAL 5 DAY))";
                            }
                            if($action == 'promote'){
                                $cmd .= " AND bikeDiscountPrice > 0";
                            }
                            $cmd .= " OR ";
                        }
                    }
                    else{
                        if(is_array($arrType[0])){
                            foreach($arrType[0] as $arr_col){
                                $cmd .= $arr_col . " LIKE '%". $item ."%' OR ";
                            }
                            $cmd = substr($cmd, 0, strlen($cmd) - 4);
                        }
                        else{
                            $cmd .= $arrType[0] . " LIKE '%". $item ."%'";
                        }
                        if($action == 'new'){
                            $cmd .= " AND dateModified > (DATE_SUB(CURRENT_DATE, INTERVAL 5 DAY))";
                        }
                        if($action == 'promote'){
                            $cmd .= " AND bikeDiscountPrice > 0";
                        }
                        $cmd .= " OR ";
                    }
                }
                $cmd = substr($cmd, 0, strlen($cmd) - 4);
            }
        }
        if(count($arr) == 1){
            $arr_query = explode("=", $arr[0]);
            $arr_col = $arr_query[0];
            if ($arr_col == "q") $arr_col = ['bikeHighlight', 'bikeName', 'bikeShortDesc'];
            $arr_query_string = explode("|", $arr_query[1]);

            foreach($arr_query_string as $item){
                if(is_array($arr_col)){
                    if(count($arr_col) > 1){
                        foreach($arr_col as $col){
                            $cmd .= $col . " LIKE '%". $item ."%' OR ";
                        }
                        $cmd = substr($cmd, 0, strlen($cmd) - 4);
                    }
                }
                else{
                    $cmd .= $arr_col . " LIKE '%". $item ."%'";
                }
                if($action == 'new'){
                    $cmd .= " AND dateModified > (DATE_SUB(CURRENT_DATE, INTERVAL 5 DAY))";
                }
                if($action == 'promote'){
                    $cmd .= " AND bikeDiscountPrice > 0";
                }
                $cmd .= " OR ";
            }
            $cmd = substr($cmd, 0, strlen($cmd) - 4);
        }
        /*if($action != 'default'){
            if($action == 'new'){
                $cmd .= " AND dateModified > (DATE_SUB(CURRENT_DATE, INTERVAL 5 DAY))";
            }
            if($action == 'promote'){
                $cmd .= " AND bikeDiscountPrice > 0";
            }
        }*/
        if($num != 'all'){
            $cmd .= " LIMIT 8 OFFSET " . (int) $num;
        }
        if($sort != 'default'){
            $arr_sort = explode("_", $sort);
            $dir = $arr_sort[1] == "up" ? 'ASC' : 'DESC';
            $cmd .= " ORDER BY bikePrice " . $dir;
        }

        //var_dump($cmd);
        $res = mysqli_query($conn, $cmd);
        while($row = mysqli_fetch_array($res)){
            $bike = new Bike();
            $bike->bikeId = $row['bikeId'];
            $bike->bikeName = $row['bikeName'];
            $bike->bikeBrand = $row['bikeBrand'];
            $bike->bikeType = $row['bikeType'];
            $bike->bikeImage = $row['bikeImage'];
            $bike->bikePrice = $row['bikePrice'];
            $bike->bikeDiscountPrice = $row['bikeDiscountPrice'];
            $bike->bikeShortDesc = $row['bikeShortDesc'];
            $bike->bikeGift = $row['bikeGift'];
            $bike->bikeHighlight = $row['bikeHighlight'];
            $bike->bikeSpecs = $row['bikeSpecs'];
            $bike->bikeGallery = $row['bikeGallery'];
            $bike->dateCreated = $row['dateCreated'];
            $bike->dateModified = $row['dateModified'];

            $arrBikes[] = $bike;

        }

        $conn->close();
        return $arrBikes;
    }

    /**
     * Get a bike specified by its ID
     * @param $bikeId: The ID of the bike
     * @return $bike: The Bike object
    */
    public function getBikeById($bikeId)
    {
        $conn = $this->connect();
        $cmd = "SELECT * FROM bike WHERE bikeId = $bikeId";
        $res = mysqli_query($conn, $cmd);
        $bike = new Bike();
        while($row = mysqli_fetch_array($res)){
            $bike->bikeId = $row['bikeId'];
            $bike->bikeName = $row['bikeName'];
            $bike->bikeBrand = $row['bikeBrand'];
            $bike->bikeType = $row['bikeType'];
            $bike->bikeImage = $row['bikeImage'];
            $bike->bikePrice = $row['bikePrice'];
            $bike->bikeDiscountPrice = $row['bikeDiscountPrice'];
            $bike->bikeShortDesc = $row['bikeShortDesc'];
            $bike->bikeGift = $row['bikeGift'];
            $bike->bikeHighlight = $row['bikeHighlight'];
            $bike->bikeSpecs = $row['bikeSpecs'];
            $bike->bikeGallery = $row['bikeGallery'];
            $bike->dateCreated = $row['dateCreated'];
            $bike->dateModified = $row['dateModified'];
        }
        return $bike;
    }

    /**
     * Add new bike
     * @param $bike: the Bike object
     * @throws $message: input param is not Bike object
     * @return true: procedure is successful
    */
    public function addBike($bike)
    {
        if(get_class($bike) !== "Bike"){
            throw new Exception("The input param is not a Bike object");
        }
        else{
            $conn = $this->connect();
            $cmd = "INSERT INTO bike (bikeName, bikeBrand, bikeType, bikeImage, bikePrice, bikeDiscountPrice, bikeShortDesc, bikeGift, bikeHighlight, bikeSpecs, bikeGallery, dateCreated, dateModified) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
            $stm = $conn->prepare($cmd);
            $stm->bind_param("ssssddsssss", $bike->bikeName, $bike->bikeBrand, $bike->bikeType, $bike->bikeImage, $bike->bikePrice, $bike->bikeDiscountPrice, $bike->bikeShortDesc, $bike->bikeGift, $bike->bikeHighlight, $bike->bikeSpecs, $bike->bikeGallery);
            $stm->execute();

            $stm->close();
            $conn->close();
            return true;
        }
    }

    /**
     * Update info of a bike
     * @param $bike: the Bike object
     * @throws $message: input param is not Bike object
     * @return true: procedure is successful
    */
    public function updateBike($bike)
    {
        if(get_class($bike) !== "Bike"){
            throw new Exception("The input param is not a Bike object");
        }
        else{
            $conn = $this->connect();
            $cmd = "UPDATE bike SET bikeName=?, bikeBrand=?, bikeType=?, bikeImage=?, bikePrice=?, bikeDiscountPrice=?, bikeShortDesc=?, bikeGift=?, bikeHighlight=?, bikeSpecs=?, bikeGallery=?, dateModified=NOW() WHERE bikeId='". $bike->bikeId ."'";
            $stm = $conn->prepare($cmd);
            $stm->bind_param("ssssddsssss", $bike->bikeName, $bike->bikeBrand, $bike->bikeType, $bike->bikeImage, $bike->bikePrice, $bike->bikeDiscountPrice, $bike->bikeShortDesc, $bike->bikeGift, $bike->bikeHighlight, $bike->bikeSpecs, $bike->bikeGallery);
            $stm->execute();

            $stm->close();
            $conn->close();
            return true;
        }
    }

    /**
     * Delete a bike by its ID
     * @param $bikeId: ID of bike to delete
    */
    public function deleteBike($bikeId)
    {
        $conn = $this->connect();
        $cmd = "DELETE FROM bike WHERE bikeId = " . $bikeId;
        mysqli_query($conn, $cmd);

        $conn->close();
    }
}