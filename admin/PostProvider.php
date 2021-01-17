<?php

spl_autoload_register(function($class_name){
    include $class_name . ".php";
});

class PostProvider extends DataProvider
{
    /**
     * Get all posts in database
     */
    public function getAllPost()
    {
        $arrPost = [];
        $conn = $this->connect();
        $cmd = "SELECT * FROM post";
        $res = mysqli_query($conn, $cmd);
        while($row = mysqli_fetch_array($res)){
            $post = new Post();
            $post->postId = $row['postId'];
            $post->postTitle = $row['postTitle'];
            $post->postTag = $row['postTag'];
            $post->postContent = $row['postContent'];
            $post->postImage = $row['postImage'];
            $post->dateCreated = $row['dateCreated'];
            $post->dateModified = $row['dateModified'];

            $arrPost[] = $post;
        }

        $conn->close();
        return $arrPost;
    }

    /**
     * Get a post by its Id in database
     * @param $postId : id of the post
     * @return Post: Post object
     */
    public function getPostById($postId)
    {
        $conn = $this->connect();
        $cmd = "SELECT * FROM post WHERE postId = '". $postId ."'";
        $res = mysqli_query($conn, $cmd);
        $post = new Post();
        while($row = mysqli_fetch_array($res)){
            $post->postId = $row['postId'];
            $post->postTitle = $row['postTitle'];
            $post->postTag = $row['postTag'];
            $post->postContent = $row['postContent'];
            $post->postImage = $row['postImage'];
            $post->dateCreated = $row['dateCreated'];
            $post->dateModified = $row['dateModified'];
        }
        $conn->close();
        return $post;
    }

    /**
     * Get a post by its tag in database
     * @param $postTag : tag of the post
     * @return Post: Post object
     */
    public function getPostByTag($postTag)
    {
        $conn = $this->connect();
        $arr_post = [];
        $postTag = str_replace(",", "", $postTag);
        $arrTag = explode(" ", $postTag);
        $cmd = "SELECT * FROM post WHERE 1=1 AND ";
        foreach ($arrTag as $id => $tag){
            $cmd .= "postTag LIKE '%". $tag ."%' OR ";
        }
        $cmd = substr($cmd, 0, strlen($cmd) - 4);

        //print_r($cmd);
        $res = mysqli_query($conn, $cmd);
        while($row = mysqli_fetch_array($res)){
            $post = new Post();
            $post->postId = $row['postId'];
            $post->postTitle = $row['postTitle'];
            $post->postTag = $row['postTag'];
            $post->postContent = $row['postContent'];
            $post->postImage = $row['postImage'];
            $post->dateCreated = $row['dateCreated'];
            $post->dateModified = $row['dateModified'];
            $arr_post[] = $post;
        }
        $conn->close();
        return $arr_post;
    }

    /**
     * Add a post to database
     * @param $post: Post object
     * @throws $message: input param is not Post object
     */
    public function addPost($post)
    {
        if(!get_class($post) == "Post"){
            throw new Exception("Input param is not a Post object");
            return;
        }
        $conn = $this->connect();
        $cmd = "INSERT INTO post (postTitle, postImage, postContent, postTag, dateCreated, dateModified) VALUES (?, ?, ?, ?, NOW(), NOW())";
        $stm = $conn->prepare($cmd);
        $stm->bind_param('ssss', $post->postTitle, $post->postImage, $post->postContent, $post->postTag);
        $stm->execute();

        $stm->close();
        $conn->close();
    }

    /**
     * Update a post in database
     * @param $post: Post object
     * @throws $mess: Input param not a Post object
     */
    public function updatePost($post)
    {
        if(!get_class($post) == "Post"){
            throw new Exception("Input param is not a Post object");
            return;
        }
        $conn = $this->connect();
        $cmd = "UPDATE post SET postTitle=?, postImage=?, postContent=?, postTag=?, dateModified=NOW() WHERE postId='". $post->postId ."'";
        //print_r($cmd);
        $stm = $conn->prepare($cmd);
        $stm->bind_param('ssss', $post->postTitle, $post->postImage, $post->postContent, $post->postTag);
        $stm->execute();
        $stm->close();
        $conn->close();
    }

    /**
     * Delete a post in database
     * @param $postId: Post's Id object
     */
    public function deletePost($postId)
    {
        $conn = $this->connect();
        $cmd = "DELETE FROM post WHERE postId = '" . $postId . "'";
        mysqli_query($conn, $cmd);

        $conn->close();
    }
}