<?php

namespace Guara;

class Field
{
    public function __construct(
        string $name,
        mixed $value = '',
        array $errors = []
    ) {
        $this->name = $name;
        $this->value = $value;
        $this->errors = $errors;
    }

    public function isEmpty(callable $error = null): Field
    {
        if (strlen($this->value) === 0) {
            $this->errors[] = $this->error('The field is empty.', $error);
        }
        return $this;
    }

    public function min(int|float $minimum, callable $error = null): Field
    {
        if (is_string($this->value)) {
            if (strlen($this->value) <= $minimum) {
                $this->errors[] = $this->error("The string must be greater or equal to $minimum characters.", $error);
            }
        } else {
            if ($this->value <= $minimum) {
                $this->errors[] = $this->error("The number must be greater or equal to $minimum.", $error);
            }
        }
        return $this;
    }

    public function max(int|float $maximum, callable $error = null): Field
    {
        if (is_string($this->value)) {
            if (strlen($this->value) >= $maximum) {
                $this->errors[] = $this->error("The string must be less or equal to $maximum characters.", $error);
            }
        } else {
            if ($this->value >= $maximum) {
                $this->errors[] = $this->error("The number must be less or equal to $maximum.", $error);
            }
        }
        return $this;
    }

    public function equalsTo(Field $field, callable $error = null): Field
    {
        if ($this->value !== $field->value) {
            $this->errors[] = $this->error("The value of $this->name must be equals to $field->name.", $error);
        }
        return $this;
    }
    public function conditon(callable $conditon): Field
    {
        if ($conditon($this)) {
            $this->errors[] = $this->error($conditon($this));
        }
        return $this;
    }

    public function lastError(): Error
    {
        return $this->errors[count($this->errors) - 1];
    }

    public function hasErrors(): bool
    {
        return count($this->errors) > 0;
    }
    private function error(string $message, callable $callback = null): Error
    {
        return $callback === null ? new Error($this->name, $message) : new Error($this->name, $callback($this->name, $this->value));
    }
}
