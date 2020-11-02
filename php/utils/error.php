<?php
class xException extends Exception
{
    protected $code    = 0;                       // User-defined exception code
    protected $file;                              // Source filename of exception
    protected $line;                              // Source line of exception
    private   $trace;                             // Unknown

    public function __construct($message)
    {
        if (!$message) {
            throw new $this('Unknown '. get_class($this));
        }
        parent::__construct($message);
    }
   
    public function __toString()
    {
        $this->parent = current(current(parent::getTrace())['args']);
        // xlog($this->parent);
        $error =  "<style>
                    body {
                        display:flex;
                        height:100vh;
                        align-items:center;
                        justify-content: center;
                        background-color: white;
                        font-family: Courier New;
                        color: black;
                    }
                    table {
                        padding-top: 10px;
                        padding-left: 10px;
                        padding-right: 20px;
                        padding-bottom: 10px;
                        margin-top: 10px;
                        margin-bottom: 10px;
                        margin-right: 10px;
                        margin-left: 10px;
                        width:100%;
                        text-align: left;
                    }
                    td {
                        padding: 5px;
                    }
                    div {
                        width: 800px;
                        background-color: red;
                        text-align: left;
                        box-shadow: inset 0 0 0 10px black;
                    }
                  
                    th.c {
                        width:20%;
                    }</style>"
                . "<div><table style=\"width:100%\">"
                . "<tr><th class='c'>MESSAGE: </th><td>{$this->parent->getMessage()}</td></tr>"
                . "<tr><th class='c'>FILE: </th><td>{$this->parent->getFile()}</td></tr>"
                . "<tr><th class='c'>LINE: </th><td>{$this->parent->getLine()}</td></tr>"
                . "<tr><th class='c'>TRACE: </th><td>{$this->getTraceAsString()}</td></tr>"
                . "</table></div>";
                echo $error;
        return $error;
    }
}

function exception_handler($exception) {
    throw new xException($exception->getMessage());
}
?>