<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App;

$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
});

// Get di tutte le offerte
$app->get('/api/offerte', function (Request $request, Response $response) {
    $sql = "SELECT * FROM offerta";

    try {
        // Get DB Object
        $db = new dbManager();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $offerte = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($offerte);
    } catch (PDOException $e) {
        echo '{"error": {"contenuto": ' . $e->getMessage() . '}';
    }
});

// Get singola offerta
$app->get('/api/offerta/{id}', function (Request $request, Response $response) {
    $id = $request->getAttribute('id');

    $sql = "SELECT * FROM offerta WHERE id = $id";

    try {
        // Get DB Object
        $db = new dbManager();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $offerta = $stmt->fetch(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($offerta);
    } catch (PDOException $e) {
        echo '{"error": {"contenuto": ' . $e->getMessage() . '}';
    }
});

// Add Offerta
$app->post('/api/offerta/add', function (Request $request, Response $response) {
    $nome = $request->getParam('nome');
    $dataInizio = $request->getParam('dataInizio');
    $dataFine = $request->getParam('dataFine');
    $descrizione = $request->getParam('descrizione');
    $prezzo = $request->getParam('prezzo');
    //$immagine = $request->getParam('immagine');

    $sql = "INSERT INTO offerta (nome,dataInizio,dataFine,descrizione,prezzo) VALUES
    (:nome,:dataInizio,:dataFine,:descrizione,:prezzo)";

    try {
        // Get DB Object
        $db = new dbManager();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':dataInizio', $dataInizio);
        $stmt->bindParam(':dataFine', $dataFine);
        $stmt->bindParam(':descrizione', $descrizione);
        $stmt->bindParam(':prezzo', $prezzo);

        $stmt->execute();

        echo '{"notice": {"contenuto": "Offerta aggiunta"}';

    } catch (PDOException $e) {
        echo '{"error": {"contenuto": ' . $e->getMessage() . '}';
    }
});

// Update offerta
$app->put('/api/offerta/update/{id}', function (Request $request, Response $response) {
    $id = $request->getAttribute('id');
    $nome = $request->getParam('nome');
    $dataInizio = $request->getParam('dataInizio');
    $dataFine = $request->getParam('dataFine');
    $descrizione = $request->getParam('descrizione');
    $prezzo = $request->getParam('prezzo');
    //$immagine = $request->getParam('immagine');

    $sql = "UPDATE offerta SET
				nome = :nome,
				dataInizio = :dataInizio,
                dataFine = :dataFine,
                descrizione = :descrizione,
                prezzo = :prezzo
			WHERE id = $id";

    try {
        // Get DB Object
        $db = new dbManager();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':dataInizio', $dataInizio);
        $stmt->bindParam(':dataFine', $dataFine);
        $stmt->bindParam(':descrizione', $descrizione);
        $stmt->bindParam(':prezzo', $prezzo);

        $stmt->execute();

        echo '{"notice": {"contenuto": "Offerta aggiornata"}';

    } catch (PDOException $e) {
        echo '{"error": {"contenuto": ' . $e->getMessage() . '}';
    }
});

// Delete offerta
$app->delete('/api/offerta/delete/{id}', function (Request $request, Response $response) {
    $id = $request->getAttribute('id');

    $sql = "DELETE FROM offerta WHERE id = $id";

    try {
        // Get DB Object
        $db = new dbManager();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);
        $stmt->execute();
        $db = null;
        echo '{"notice": {"contenuto": "Offerta eliminata"}';
    } catch (PDOException $e) {
        echo '{"error": {"contenuto": ' . $e->getMessage() . '}';
    }
});