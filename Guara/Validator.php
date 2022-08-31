<?php
namespace Guara;

class Validator {
    public function __construct(
        private array $data = [],
        private array $collection = [],
        private array $errors = [],
    ) {
        $this->data = $data;
        $this->collection = $collection;
    }

    public function getField(string $name): Field
    {
        return $this->collection[$name];
    }

    public function field(string $name): Field
    {
        $field = new Field($name, $this->data[$name]);
        $this->collection[$name] = $field;
        return $field;
    }

    public function validate(): void
    {
        foreach ($this->collection as $field) {
            if ($field->hasErrors()) {
                $this->errors[] = $field->lastError();
            }
        }
    }

    public function data() : array
    {
        return $this->data;
    }
    
    public function collection() : array
    {
        return $this->collection;
    }

    public function errors() : array
    {
        return $this->errors;
    }
}