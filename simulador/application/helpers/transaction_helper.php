<?php
function __exception_error_handler($errno, $errstr, $errfile, $errline ) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
}

class TryFinallyErrorHandler {
    public static $count = 0;
    
    static function beginTry () {
        TryFinallyErrorHandler::$count+=1;
        set_error_handler('__exception_error_handler');
    }
    static function finallyTry () {
        TryFinallyErrorHandler::$count-=1;
        if(TryFinallyErrorHandler::$count<=0) {
            set_error_handler('_exception_handler');    
            TryFinallyErrorHandler::$count = 0;
        }
    }
}
