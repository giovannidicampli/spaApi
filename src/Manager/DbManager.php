<?php
class DBController
{
    const host = 'localhost';
    const database = 'spaDB';
    const username = 'root';
    const password = '';

    public static function getConnection(){
        $con = mysqli_connect(self::host, self::username, self::password, self::database);
        return $con;
    }

}