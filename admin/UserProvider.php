<?php

spl_autoload_register(function($class_name){
   include_once $class_name . ".php";
});

class UserProvider extends DataProvider
{
    /**
     * Get info all users
    */
    public function getAllUser() : iterable
    {
        $arrUser = [];

        $conn = $this->connect();
        $cmd = "SELECT userId, userEmail, userPassword, userImage, userDisplayName, dateCreated, dateModified FROM user";
        $res = mysqli_query($conn, $cmd);
        while($row = mysqli_fetch_array($res)){
            $user = new User();
            $user->userId = $row['userId'];
            $user->userEmail = $row['userEmail'];
            $user->userPassword = $row['userPassword'];
            $user->userImage = $row['userImage'];
            $user->userDisplayName = $row['userDisplayName'];
            $user->dateCreated = $row['dateCreated'];
            $user->dateModified = $row['dateModified'];

            $arrUser[] = $user;
        }

        $conn->close();
        return $arrUser;
    }

    /**
     * Get a user specified by its ID
     * @param $userId: The ID of the user
     * @return $user: The User object
    */
    public function getUserById($userId)
    {
        $conn = $this->connect();
        $cmd = "SELECT userId, userEmail, userPassword, userImage, userDisplayName FROM user WHERE userId = $userId";
        $res = mysqli_query($conn, $cmd);
        $user = new User();
        while($row = mysqli_fetch_array($res)){
            $user->userId = $row['userId'];
            $user->userEmail = $row['userEmail'];
            $user->userPassword = $row['userPassword'];
            $user->userImage = $row['userImage'];
            $user->userDisplayName = $row['userDisplayName'];
        }
        return $user;
    }

    /**
     * Register new user
     * @param $user: the User object
     * @throws $message: input param is not User object
     * @return true: procedure is successful
    */
    public function addUser($user)
    {
        if(get_class($user) !== "User"){
            throw new Exception("The input param is not a User object");
        }
        else{
            $conn = $this->connect();
            $cmd = "INSERT INTO user (userEmail, userPassword, userImage, userDisplayName, dateCreated, dateModified) VALUES (?, ?, ?, ?, NOW(), NOW())";
            $stm = $conn->prepare($cmd);
            $stm->bind_param("ssss", $user->userEmail, $user->userPassword, $user->userImage, $user->userDisplayName);
            $stm->execute();

            $stm->close();
            $conn->close();
            return true;
        }
    }

    /**
     * Update info of a user
     * @param $user: the User object
     * @throws $message: input param is not User object
     * @return true: procedure is successful
    */
    public function updateUser($user)
    {
        if(get_class($user) !== "User"){
            throw new Exception("The input param is not a User object");
        }
        else{
            $conn = $this->connect();
            $cmd = "UPDATE user SET userEmail=?, userPassword=?, userImage=?, userDisplayName=?, dateModified=NOW() WHERE userId=". $user->userId;
            $stm = $conn->prepare($cmd);
            $stm->bind_param("ssss", $user->userEmail, $user->userPassword, $user->userImage, $user->userDisplayName);
            $stm->execute();

            $stm->close();
            $conn->close();
            return true;
        }
    }

    /**
     * Delete a user by its ID
     * @param $userId: ID of user to delete
    */
    public function deleteUser($userId)
    {
        $conn = $this->connect();
        $cmd = "DELETE FROM user WHERE userId = " . $userId;
        mysqli_query($conn, $cmd);

        $conn->close();
    }
    
    /**
     * Get user in database by Email
     * @param $email: email of user to find
     * @return $user: a User object if user is available
    */
    public function getUserByEmail($email)
    {
        $user = new User();
        $conn = $this->connect();
        $cmd = "SELECT userId, userEmail, userPassword, userImage, userDisplayName FROM user WHERE userEmail = '$email'";
        $res = mysqli_query($conn, $cmd);
        $row = mysqli_fetch_array($res);
        $num_user = mysqli_num_rows($res);
        if($num_user == 0){
            return false;
        }
        $user->userId = $row['userId'];
        $user->userEmail = $row['userEmail'];
        $user->userPassword = $row['userPassword'];
        $user->userImage = $row['userImage'];
        $user->userDisplayName = $row['userDisplayName'];
        return $user;
    }
}