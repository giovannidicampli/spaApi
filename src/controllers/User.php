<?php

class User
{

    public static function get_utente_by_id($id)
    {
        $con = DBController::getConnection();

        $query = "SELECT id, nome, cognome, username, email FROM utente WHERE id = ?";

        $stmt = $con->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->store_result();

        if ( $stmt->num_rows() > 0 ) {
            $stmt->bind_result($id, $nome, $cognome, $username, $email);

            $utente = array();

            while ($stmt->fetch()) {
                $temp = array();
                $temp['id'] = $id;
                $temp['nome'] = $nome;
                $temp['cognome'] = $cognome;
                $temp['email'] = $email;
                $temp['username'] = $username;
                array_push($utente, $temp);

                return $utente;
            }
        } else {
            return false;
        }
    }

    public static function get_utente_by_username($username)
    {
        $con = DBController::getConnection();

        $query = "SELECT id, nome, cognome, username, email FROM utente WHERE username = ?";

        $stmt = $con->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();



        if ( $stmt->num_rows() > 0 ) {
            $stmt->bind_result($id, $nome, $cognome, $username, $email);

            $utente = array();

            while ($stmt->fetch()) {
                $temp = array();
                $temp['id'] = $id;
                $temp['nome'] = $nome;
                $temp['cognome'] = $cognome;
                $temp['email'] = $email;
                $temp['username'] = $username;
                array_push($utente, $temp);

                return $utente;
            }
        } else {
            return false;
        }
    }

    public static function get_utente_by_email($email)
    {
        $con = DBController::getConnection();

        $query = "SELECT id, nome, cognome, username, email FROM utente WHERE email = ?";

        $stmt = $con->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ( $stmt->num_rows() > 0 ) {
            $stmt->bind_result($id, $nome, $cognome, $username, $email);

            $utente = array();

            while ($stmt->fetch()) {
                $temp = array();
                $temp['id'] = $id;
                $temp['nome'] = $nome;
                $temp['cognome'] = $cognome;
                $temp['email'] = $email;
                $temp['username'] = $username;
                array_push($utente, $temp);

                return $utente;
            }
        } else {
            return false;
        }
    }
}