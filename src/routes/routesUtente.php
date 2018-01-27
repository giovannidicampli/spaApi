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

// Get tutti gli utenti
$app->get('/api/utenti', function(Request $request, Response $response){
    $sql = "SELECT * FROM utente";

    try{
        // Get DB Object
        $db = new dbManager();
        // Connessione
        $db = $db->connect();

        $stmt = $db->query($sql);
        $customers = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($customers);
    } catch(PDOException $e){
        echo '{"errore": {"contenuto": '.$e->getMessage().'}';
    }
});

//Get singolo utente
$app->get('/api/utente/{id}', function (Request $request, Response $response) {
    $id = $request->getAttribute('id');

    $sql = "SELECT * FROM utente WHERE id = $id";

    try {
        // Get DB Object
        $db = new dbManager();
        // Connessione
        $db = $db->connect();

        $stmt = $db->query($sql);
        $utente = $stmt->fetch(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($utente);
    } catch (PDOException $e) {
        echo '{"error": {"contenuto": ' . $e->getMessage() . '}';
    }
});

// Add Utente
$app->post('/api/utente/add', function (Request $request, Response $response) {
    $nome = $request->getParam('nome');
    $cognome = $request->getParam('cognome');
    $email = $request->getParam('email');
    $username = $request->getParam('username');
    $password = $request->getParam('password');
    $is_admin = $request->getParam('is_admin');
    $newsletter = $request->getParam('newsletter');

    $sql = "INSERT INTO utente (nome,cognome,email,username,password,is_admin,newsletter) VALUES
    (:nome,:cognome,:email,:username,:password,:is_admin,:newsletter)";

    try {
        // Get DB Object
        $db = new dbManager();
        // Connessione
        $db = $db->connect();

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':cognome', $cognome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':is_admin', $is_admin);
        $stmt->bindParam(':newsletter', $newsletter);

        $stmt->execute();

        echo '{"notice": {"contenuto": "Utente aggiunto"}';

    } catch (PDOException $e) {
        echo '{"error": {"contenuto": ' . $e->getMessage() . '}';
    }
});

// Update utente
$app->put('/api/utente/update/{id}', function (Request $request, Response $response) {
    $id = $request->getAttribute('id');
    $nome = $request->getParam('nome');
    $cognome = $request->getParam('cognome');
    $email = $request->getParam('email');
    $username = $request->getParam('username');
    $password = $request->getParam('password');
    $is_admin = $request->getParam('is_admin');
    $newsletter = $request->getParam('newsletter');

    $sql = "UPDATE utente SET
				nome = :nome,
				cognome = :cognome,
                email = :email,
                username = :username,
                password = :password,
                is_admin = :is_admin,
                newsletter = :newsletter
			WHERE id = $id";

    try {
        // Get DB Object
        $db = new dbManager();
        // Connessione
        $db = $db->connect();

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':cognome', $cognome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':is_admin', $is_admin);
        $stmt->bindParam(':newsletter', $newsletter);

        $stmt->execute();

        echo '{"notice": {"contenuto": "Utente aggiornato"}';

    } catch (PDOException $e) {
        echo '{"error": {"contenuto": ' . $e->getMessage() . '}';
    }
});

// Delete utente
$app->delete('/api/utente/delete/{id}', function (Request $request, Response $response) {
    $id = $request->getAttribute('id');

    $sql = "DELETE FROM utente WHERE id = $id";

    try {
        // Get DB Object
        $db = new dbManager();
        // Connessione
        $db = $db->connect();

        $stmt = $db->prepare($sql);
        $stmt->execute();
        $db = null;
        echo '{"notice": {"contenuto": "Utente eliminato"}';
    } catch (PDOException $e) {
        echo '{"error": {"contenuto": ' . $e->getMessage() . '}';
    }
});