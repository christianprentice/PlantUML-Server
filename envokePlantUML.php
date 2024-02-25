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

        if ($operationName == 'render') {
            $command = "java -jar jarscript/plantuml.jar IO/diagram.txt -tsvg";
        } elseif ($outputFormat == 'txt') {
            $command = "java -jar jarscript/plantuml.jar IO/diagram.txt -txt";
        } else {
            $command = "java -jar jarscript/plantuml.jar IO/diagram.txt -t{$outputFormat}";
        }

        exec($command);

        switch ($operationName) {
            case 'render':
                render("IO/diagram.svg");
                break;
            case 'download':
                download("IO/diagram.{$outputFormat}");
                break;
        }
    } else {
        throw new Exception('Unsupported request from the user.');
    }
}

function render($path)
{
    readfile($path);
}

function download($path)
{
    if (file_exists($path)) {
        $data = file_get_contents($path);
        $mimeType = mime_content_type($path);
        $base64Data = base64_encode($data);
        $dataURL = "data:$mimeType;base64,$base64Data";
        echo json_encode(['imageSrc' => $dataURL]);
    }
}
