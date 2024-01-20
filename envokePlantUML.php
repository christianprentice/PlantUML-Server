<?php
    if($_SERVER["REQUEST_METHOD"] === "POST") {
        $jsonData = file_get_contents("php://input");
    }
