<?php
    try{
        if($_SERVER["REQUEST_METHOD"] === "POST") {
            $jsonData = file_get_contents("php://input");
            $hashmap = json_decode($jsonData, true);
            $textBody = $hashmap["textBody"];
            $ret = file_put_contents('IO/diagram.txt', $textBody);
            if(!$ret) throw new Exception('File could not be created due to error.');

            $outputFormat = $hashmap["outputFormat"];
            if($outputFormat != 'txt') {
                $command = "java -jar jarscript/plantuml.jar IO/diagram.txt -t{$outputFormat}";
            }else{
                $command = "java -jar jarscript/plantuml.jar IO/diagram.txt -{$outputFormat}";
            }

            exec($command);

            switch($hashmap["operationName"]){
                case 'render':
                    render("IO/diagram.{$outputFormat}");
                    break;
               case 'download':
                    download("IO/diagram.{$outputFormat}");
                    break;
            }
        }else{
            throw new Exception('Unsupported request from the user.');
        }
    }catch(Exception $e){
        echo "ERROR: {$e->getMessage()}";
    }

    function render($path){
        #3 FIX ME add PNG encoding support
        readfile($path);
        #flush();
        #ob_clean();
        #unlink($path);
    }

    function download($path){
        #2 FIX ME add PNG download support
        if (file_exists($path)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($path).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($path));
            readfile($path);
            #flush();
            #ob_clean();
            #unlink($path);
        }
    }
?>
