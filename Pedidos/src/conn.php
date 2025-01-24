<?php

    function connect()
    {
        try {
            $dwes = new PDO(DB_DSN, DB_USER, DB_PASSWD);
            $dwes->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $ex) {
            echo $ex->getMessage();
            return false;
        }
        return $dwes;
    }
    
