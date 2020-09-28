<?php
class xException extends Exception
{
    protected $message = 'Unknown exception';     // Exception message
    private   $string;                            // Unknown
    protected $code    = 0;                       // User-defined exception code
    protected $file;                              // Source filename of exception
    protected $line;                              // Source line of exception
    private   $trace;                             // Unknown

    public function __construct($message = null, $code = 0)
    {
        if (!$message) {
            throw new $this('Unknown '. get_class($this));
        }
        parent::__construct($message, $code);
    }
   
    public function __toString()
    {
        echo      "<br><strong>MESSAGE: </strong>'{$this->message}"
                . "<br><strong>FILE: </strong>{$this->file}"
                . "<br><strong>LINE: </strong>{$this->line}"
                . "<br><strong>TRACE: </strong>{$this->getTraceAsString()}";
        return '';
    }
}
?>