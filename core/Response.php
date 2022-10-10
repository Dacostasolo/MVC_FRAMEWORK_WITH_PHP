<?php

namespace app\core;

class Response
{
    public function setResponse(int $responseCode)
    {
        http_response_code($responseCode);
    }
}
