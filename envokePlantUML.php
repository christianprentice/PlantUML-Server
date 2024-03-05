<?php

try {
    handlePOST();
} catch (Exception $e) {
    echo "ERROR: {$e->getMessage()}";
}

function handlePOST()
{

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $jsonData = file_get_contents("php://input");
        $hashmap = json_decode($jsonData, true);

        $textBody = $hashmap["textBody"];
        $ret = file_put_contents('IO/diagram.txt', $textBody);
        if (!$ret) throw new Exception('File could not be created due to error.');

        $outputFormat = $hashmap["outputFormat"];
        $operationName = $hashmap["operationName"];

        if ($operationName = 'render') $outputFormat = 'svg';
        executeJar($outputFormat, $operationName);

        handleOperation("IO/diagram.{$outputFormat}", $operationName);

    } else {
        throw new Exception('Unsupported request from the user.');
    }
}

function executeJar($operationName, $outputFormat){
    if ($operationName == 'render') {
        exec("java -jar jarscript/plantuml.jar IO/diagram.txt -tsvg");
    } elseif ($outputFormat == 'txt') {
        exec("java -jar jarscript/plantuml.jar IO/diagram.txt -txt");
    } else {
        exec("java -jar jarscript/plantuml.jar IO/diagram.txt -t{$outputFormat}");
    }
}

function handleOperation($path, $operation){
    if (file_exists($path)) {

        $data = file_get_contents($path);
        $mimeType = mime_content_type($path);
        $base64Data = base64_encode($data);
        $dataURL = "data:$mimeType;base64,$base64Data";

        switch ($operation) {
            case 'render':
                echo json_encode(['renderContent' => $dataURL]);
                break;
            case 'download':
                echo json_encode(['imageSrc' => $dataURL]);
                break;
        }
    } else {
        throw new Exception('file $path not found');
    }
}

