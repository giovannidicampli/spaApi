<?php

use api\routes\Route;
use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;


class OffertaRoutes extends Route
{

    public static function register_routes(App $app)
    {
        $app->get('/offerte', self::class . ':get_offerte');
        $app->get('/offerta/{nome}', self::class . ':get_offerta_by_nome');
        $app->post('/offerta/inserisci', self::class . ':inserisci_offerta');
        $app->put('/offerta/{id}/edit', self::class . ':edit_offerta_by_id');
        $app->delete('/offerta/{id}/delete', self::class . ':delete_offerta_by_id');
    }


    public function get_offerte(Request $request, Response $response)
    {
        $result = false;

        $con = DBController::getConnection();

        if ( $con ) {
            $query = "SELECT nome, dataInizio, dataFine, descrizione, prezzo FROM offerta";

            $stmt = $con->prepare($query);
            $stmt->execute();
            $stmt->store_result();

            if ( $stmt->num_rows() ) {
                $stmt->bind_result($nome, $dataInizio, $dataFine, $descrizione, $prezzo);

                $offerte = array();

                while ($stmt->fetch()) {
                    $temp = array();
                    $temp['nome'] = $nome;
                    $temp['dataInizio'] = $dataInizio;
                    $temp['dataFine'] = $dataFine;
                    $temp['descrizione'] = $descrizione;
                    $temp['prezzo'] = $prezzo;
                    
                    //Formattano rispettivamente la data inizio e la data fine così gg/mm/aaaa
                    $temp1 = explode(" ", $temp['dataInizio']);
                    $temp2 = explode("-", $temp1[0]);
                    $temp['dataInizio'] = $temp2[2] . "/" . $temp2[1] . "/" . $temp2[0];

                    $temp1 = explode(" ", $temp['dataFine']);
                    $temp2 = explode("-", $temp1[0]);
                    $temp['dataFine'] = $temp2[2] . "/" . $temp2[1] . "/" . $temp2[0];

                    array_push($offerte, $temp);
                }

                $result = true;
                $this->message = "ci sono offerte";
                $response = self::get_response($response, $result, 'offerte', $offerte);


            } else {
                $this->message = "non ci sono offerte";
                $response = self::get_response($response, $result, 'offerte', false);
            }

        } else {
            $this->message = "database non connesso";
            $response = self::get_response($response, $result, 'offerte', false);
        }

        return $response;
    }


    public function get_offerta_by_nome(Request $request, Response $response)
    {
        $result = false;

        $con = DBController::getConnection();

        if ( $con ) {

            $nome = $request->getAttribute('nome');

            $query = "SELECT nome, dataInizio, dataFine, descrizione, prezzo FROM offerta WHERE nome = ?";

            $stmt = $con->prepare($query);
            $stmt->bind_param("s", $nome);
            $stmt->execute();
            $stmt->store_result();

            if ( $stmt->num_rows() ) {
                $stmt->bind_result($nome, $dataInizio, $dataFine, $descrizione, $prezzo);
                $stmt->fetch();


                $offerta['nome'] = $nome;
                $offerta['dataInizio'] = $dataInizio;
                $offerta['dataFine'] = $dataFine;
                $offerta['descrizione'] = $descrizione;
                $offerta['prezzo'] = $prezzo;

                //Formattano rispettivamente la data inizio e la data fine così gg/mm/aaaa
                $temp1 = explode(" ", $offerta['dataInizio']);
                $temp2 = explode("-", $temp1[0]);
                $offerta['dataInizio'] = $temp2[2] . "/" . $temp2[1] . "/" . $temp2[0];

                $temp1 = explode(" ", $offerta['dataFine']);
                $temp2 = explode("-", $temp1[0]);
                $offerta['dataFine'] = $temp2[2] . "/" . $temp2[1] . "/" . $temp2[0];

                $result = true;
                $this->message = "l'offerta c'è";
                $response = self::get_response($response, $result, 'offerta', $offerta);


            } else {
                $this->message = "l'offerta non c'è";
                $response = self::get_response($response, $result, 'offerta', false);
            }

        } else {
            $this->message = "database non connesso";
            $response = self::get_response($response, $result, 'offerta', false);
        }

        return $response;
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
                $response = self::get_response($response, $result, 'inserimento', true);


            } else {
                $this->message = "parametri mancanti";
                $response = self::get_response($response, $result, 'inserimento', false);
            }

        } else {
            $this->message = "database non connesso";
            $response = self::get_response($response, $result, 'inserimento', false);
        }

        return $response;
    }


    public function edit_offerta_by_id(Request $request, Response $response)
    {
        $result = false;

        $con = DBController::getConnection();

        if ( $con ) {

            $id = $request->getAttribute('id');

            $newNome = $request->getHeader('nome');
            $newDataInizio = $request->getHeader('dataInizio');
            $newDataFine = $request->getHeader('dataFine');
            $newDescrizione = $request->getHeader('descrizione');
            $newPrezzo = $request->getHeader('prezzo');

            $query = "UPDATE offerta SET nome = '$newNome[0]', dataInizio = '$newDataInizio[0]', dataFine = '$newDataFine[0]'
             descrizione = '$newDescrizione[0]', prezzo = '$newPrezzo[0]' WHERE id = '$id'";

            print_r($request->getHeader('nome'));

            $stmt = $con->prepare($query);
            $stmt->execute();
            $stmt->store_result();

            if ( $stmt ) {

                $result = true;
                $this->message = "offerta modificata";
                $response = self::get_response($response, $result, 'edit', true);


            } else {
                $this->message = "parametri mancanti";
                $response = self::get_response($response, $result, 'edit', false);
            }

        } else {
            $this->message = "database non connesso";
            $response = self::get_response($response, $result, 'edit', false);
        }

        return $response;
    }


    public function delete_offerta_by_id(Request $request, Response $response)
    {
        $result = false;

        $con = DBController::getConnection();

        if ( $con ) {

            $id = $request->getAttribute('id');

            $query = "DELETE FROM offerta WHERE id = ?";

            $stmt = $con->prepare($query);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->store_result();

            if ( $stmt ) {

                $result = true;
                $this->message = "offerta eliminata";
                $response = self::get_response($response, $result, 'delete', true);


            } else {
                $this->message = "parametri mancanti";
                $response = self::get_response($response, $result, 'delete', false);
            }

        } else {
            $this->message = "database non connesso";
            $response = self::get_response($response, $result, 'delete', false);
        }

        return $response;
    }
}