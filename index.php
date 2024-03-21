<?php
    
    require_once 'config.php';
    
    require_once 'includes/classes/RequestHandler.php';
    require_once 'includes/classes/Responder.php';
    require_once 'includes/classes/JsonParser.php';
    require_once 'includes/classes/ErrorHandlerJson.php';
    require_once 'includes/classes/DataHandler.php';
    require_once 'includes/classes/MySQLDataGetter.php';
    require_once 'includes/classes/JsonEncoder.php';
    
    $responder = new Responder();
    $errorHandler = new ErrorHandlerJson($responder);
    
    try {
        $dataGetter = new MySQLDataGetter(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    } catch (Exception $ex){
        $errorHandler->throwError(500, 'Database connection error');
        exit();
    }

    $parser = new JsonParser();
    $encoder = new JsonEncoder();
    $dataHandler = new DataHandler($dataGetter, $encoder, $errorHandler);
    $handler = new RequestHandler(file_get_contents('php://input'), $_SERVER["CONTENT_TYPE"], $parser, $errorHandler, $dataHandler, $responder);
    
    $handler->handleRequest();
  
?>