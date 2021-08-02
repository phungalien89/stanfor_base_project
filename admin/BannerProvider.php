<?php


class BannerProvider extends DataProvider
{
    /**
     * Get banner object
    */
    public function getBanner()
    {
        $conn = $this->connect();
        $cmd = "SELECT * FROM banner";
        $res = mysqli_query($conn, $cmd);
        $arrBanner = [];
        while($row = mysqli_fetch_array($res)){
            $banner = new Banner();
            $banner->bannerId = $row['bannerId'];
            $banner->bannerContent = $row['bannerContent'];
            $arrBanner[] = $banner;
        }
        $conn->close();
        return $arrBanner;
    }

    /**
     * Update banner
     * @param: $banner - Banner object input
    */
    public function updateBanner($banner)
    {
        if(get_class($banner) != "Banner"){
            throw new Exception("Input object is not a Banner object");
            return;
        }
        $conn = $this->connect();
        $cmd = "UPDATE banner SET bannerContent=?, dateModified=NOW()";
        $stm = $conn->prepare($cmd);
        $stm->bind_param("s", $banner->bannerContent);
        $stm->execute();

        $stm->close();
        $conn->close();
    }

    /**
     * Add banner once and never more
    */
    public function addBanner($banner)
    {
        if(get_class($banner) != "Banner"){
            throw new Exception("Input object is not a Banner object");
            return;
        }
        $conn = $this->connect();
        $cmd = "INSERT INTO banner (bannerContent, dateCreated, dateModified) VALUES (?, NOW(), NOW())";
        $stm = $conn->prepare($cmd);
        $stm->bind_param("s", $banner->bannerContent);
        $stm->execute();

        $stm->close();
        $conn->close();
    }

    /**
     * Delete banner
    */
    public function deleteBanner()
    {
        $conn = $this->connect();
        $cmd = "DELETE FROM banner";//Delete all banner
        $res = mysqli_query($conn, $cmd);
        $conn->close();
    }

}