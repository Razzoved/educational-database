<?php

declare(strict_types=1);

namespace App\Exceptions;

use CodeIgniter\HTTP\Response;
use Exception;

class BadPostException extends Exception
{
    public function __construct(string $message, int $code = Response::HTTP_BAD_REQUEST)
    {
        parent::__construct($message, $code);
    }
}
