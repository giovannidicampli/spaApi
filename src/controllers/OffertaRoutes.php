<?php

use api\routes\Route;
use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;


class OffertaRoutes extends Route
{

    public static function register_routes(App $app)
    {
        $app->post('/offerta/inserisci', self::class . ':inserisci_offerta');
        $app->get('/offerte', self::class . ':get_offerte');
        $app->get('/offerta/{id}', self::class . ':get_offerta_by_id');
        $app->delete('/offerta/{id}/delete', self::class . ':delete_offerta_by_id');
        $app->put('/offerta/{id}/edit', self::class . ':edit_offerta_by_id');
    }

    public function inserisci_offerta(Request $request, Response $response)
    {
        $result = false;

        $con = DBController::getConnection();

        if ( $con ) {
                $query = "INSERT INTO offerta (nome, dataInizio, dataFine, descrizione, prezzo) VALUES (?, ?, ?, ?, ?)";

                $stmt = $con->prepare($query);
                $stmt->bind_param("ssssi", $nome, $dataInizio, $dataFine, $descrizione, $prezzo);
                $stmt->execute();
                $stmt->store_result();

                if ( $stmt ) {
                    $result = true;
                    $this->message = "inserimento effettuato";
                    $response = self::get_response($response, $result, 'registrazione', true);


            } else {
                $this->message = "parametri mancanti";
                $response = self::get_response($response, $result, 'registrazione', false);
            }

        } else {
            $this->message = "database non connesso";
            $response = self::get_response($response, $result, 'registrazione', false);
        }

        return $response;
    }

//    public function accesso_utente(Request $request, Response $response)
//    {
//        $result = false;
//
//        $con = DBController::getConnection();
//
//        if ($con) {
//            $requestData = $request->getParsedBody();
//
//            $username = $requestData['username'];
//            $password = $requestData['password'];
//
//            if ($username && $password) {
//
//                $query = "SELECT id FROM utente WHERE username = ? AND password = ?";
//
//                $stmt = $con->prepare($query);
//                $stmt->bind_param("ss", $username, md5($password));
//                $stmt->execute();
//                $stmt->store_result();
//
//                if ($stmt->num_rows) {
//                    $result = true;
//                    $this->message = "accesso effettuato";
//                    $response = self::get_response($response, $result, 'accesso', true);
//                } else {
//                    $this->message = "username o password non validi";
//                    $response = self::get_response($response, $result, 'accesso', true);
//                }
//            } else {
//                $this->message = "parametri mancanti";
//                $response = self::get_response($response, $result, 'accesso', false);
//            }
//        } else {
//            $this->message = "database non connesso";
//            $response = self::get_response($response, $result, 'accesso', false);
//        }
//
//        return $response;
//    }
//
//    public function get_utente_by_username(Request $request, Response $response)
//    {
//        $result = false;
//
//        $con = DBController::getConnection();
//
//        if ($con) {
//
//            $username = $request->getAttribute('username');
//
//            $utente = User::get_utente_by_username($username);
//
//            if ($utente) {
//                $result = true;
//                $this->message = "utente esistente";
//                $response = self::get_response($response, $result, 'utente', $utente);
//            } else {
//                $this->message = "utente non esistente";
//                $response = self::get_response($response, $result, 'utente', false);
//            }
//        } else {
//            $this->message = "database non connesso";
//            $response = self::get_response($response, $result, 'utente', false);
//        }
//
//        return $response;
//    }
//
//    public function get_utente_by_id(Request $request, Response $response)
//    {
//        $result = false;
//
//        $con = DBController::getConnection();
//
//        if ($con) {
//
//            $id = $request->getAttribute('id');
//
//            $utente = User::get_utente_by_id($id);
//
//            if ($utente) {
//                $result = true;
//                $this->message = "utente esistente";
//                $response = self::get_response($response, $result, 'utente', $utente);
//            } else {
//                $this->message = "utente non esistente";
//                $response = self::get_response($response, $result, 'utente', false);
//            }
//        } else {
//            $this->message = "database non connesso";
//            $response = self::get_response($response, $result, 'utente', false);
//        }
//
//        return $response;
//    }
//
//
//    public function delete_utente_by_username(Request $request, Response $response)
//    {
//        $result = false;
//
//        $con = DBController::getConnection();
//
//        if ($con) {
//
//            $username = $request->getAttribute('username');
//
//            $utente = User::get_utente_by_username($username);
//
//            if ($utente) {
//                $query = "DELETE FROM utente WHERE username = ?";
//
//                $stmt = $con->prepare($query);
//                $stmt->bind_param("s", $username);
//                $stmt->execute();
//                $stmt->store_result();
//
//                if ($stmt) {
//                    $result = true;
//                    $this->message = "utente cancellato";
//                    $response = self::get_response($response, $result, 'delete', true);
//                } else {
//                    $this->message = "utente non cancellato";
//                    $response = self::get_response($response, $result, 'delete', true);
//                }
//            } else {
//                $this->message = "utente non esistente";
//                $response = self::get_response($response, $result, 'delete', false);
//            }
//        } else {
//            $this->message = "database non connesso";
//            $response = self::get_response($response, $result, 'delete', false);
//        }
//
//        return $response;
//    }
//
//    public function edit_utente_by_username(Request $request, Response $response)
//    {
//        $result = false;
//
//        $con = DBController::getConnection();
//
//        if ($con) {
//
//            $username = $request->getAttribute('username');
//
//            $utente = User::get_utente_by_username($username);
//
//            if ($utente) {
//                $query = "UPDATE utente SET";
//
//                $newUsername = $request->getHeader('username');
//                $newEmail = $request->getHeader('email');
//                $newPassword = $request->getHeader('password');
//
//                if ($newUsername) {
//                    if (User::get_utente_by_username($newUsername[0])) {
//                        $this->message = "username già esistente";
//                        $response = self::get_response($response, $result, 'edit', false);
//                        return $response;
//                    } else {
//                        $query .= " username = '" . $newUsername[0] . "',";
//                    }
//                }
//                if ($newEmail) {
//                    if (User::get_utente_by_email($newEmail[0])) {
//                        $this->message = "email già esistente";
//                        $response = self::get_response($response, $result, 'edit', false);
//                        return $response;
//                    } else {
//                        $query .= " email = '" . $newEmail[0] . "',";
//                    }
//                }
//                if ($newPassword) {
//                    $query .= " password = '" . md5($newPassword[0]) . "',";
//                }
//
//                $query = rtrim($query, ',');
//                $query .= " WHERE username = '" . $username . "'";
//
//                print_r($query);
//
//
//                $stmt = $con->prepare($query);
//                $stmt->execute();
//                $stmt->store_result();
//
//                if ($stmt) {
//                    $result = true;
//                    $this->message = "utente modificato";
//                    $response = self::get_response($response, $result, 'edit', true);
//                } else {
//                    $this->message = "utente non modificato";
//                    $response = self::get_response($response, $result, 'edit', false);
//                }
//            } else {
//                $this->message = "utente non esistente";
//                $response = self::get_response($response, $result, 'edit', false);
//            }
//        } else {
//            $this->message = "database non connesso";
//            $response = self::get_response($response, $result, 'edit', false);
//        }
//
//        return $response;
//    }
//
}