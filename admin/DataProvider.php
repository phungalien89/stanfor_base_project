<?php


class DataProvider
{
    public function connect()
    {
        $hostname = "localhost";
        $username = "root";
        $password = "";
        $dbname = "bike_store";

        $conn = new mysqli($hostname, $username, $password, $dbname, 3306);
        $conn->set_charset("utf8");

        return $conn;
    }
}