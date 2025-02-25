<?php

declare(strict_types=1);

namespace App\Core\Exception;

class ValidationException extends \Exception
{
    private array $errors;

    public function __construct(array $errors, string $message = "Validation failed")
    {
        parent::__construct($message);
        $this->errors = $errors;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
