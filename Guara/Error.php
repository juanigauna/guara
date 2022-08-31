<?php
namespace Guara;

class Error {
    public function __construct(
        string $name,
        string $message
    ) {
        $this->name = $name;
        $this->message = $message;
    }

    public function getError(): string
    {
        return "$this->name: $this->message";
    }
    public function name(): string
    {
        return $this->name;
    }
    public function message(): string
    {
        return $this->message;
    }
}