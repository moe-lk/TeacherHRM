<?php

function logError($error, $errlvl,$tp="") {    
    error_log($error);
    if($tp=='SH'){
    $errorHtml = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>EMIS</title>
</head>
<body>
<form action="index.php" enctype="multipart/form-data" name="errorForm" id="errorForm" method="post">
<p>
The request cannot be processed at this moment due to network interference.</br>
Please click below button to try again.</br></br>
<input type="submit" name="errorButton" id="errorButton" value="Try Again"/>
</p>
</form>
</body>
</html>';
    echo $errorHtml;
    }
}

function errorHandler($error_level, $error_message, $error_file, $error_line, $error_context) {
    $error = "level: " . $error_level . " | msg:" . $error_message . " | file:" . $error_file . " | line:" . $error_line;
    switch ($error_level) {
        case E_ERROR:
        case E_CORE_ERROR:
        case E_COMPILE_ERROR:
        case E_PARSE:
            logError($error, "fatal");
            break;
        case E_USER_ERROR:
        case E_RECOVERABLE_ERROR:
            logError($error, "error");
            break;
        case E_WARNING:
        case E_CORE_WARNING:
        case E_COMPILE_WARNING:
        case E_USER_WARNING:
            logError($error, "warn");
            break;
        case E_NOTICE:
        case E_USER_NOTICE:
            logError($error, "info");
            break;
        case E_STRICT:
            logError($error, "debug");
            break;
        default:
            logError($error, "warn");
    }
}

function shutdownHandler() { //will be called when php script ends.
    $lasterror = error_get_last();
    switch ($lasterror['type']) {
        case E_ERROR:
        case E_CORE_ERROR:
        case E_COMPILE_ERROR:
        case E_USER_ERROR:
        case E_RECOVERABLE_ERROR:
        case E_CORE_WARNING:
        case E_COMPILE_WARNING:
        case E_PARSE:
            $error = "[SHUTDOWN] level:" . $lasterror['type'] . " | msg:" . $lasterror['message'] . " | file:" . $lasterror['file'] . " | line:" . $lasterror['line'];
            logError($error, "fatal",'SH');
    }
}



?>