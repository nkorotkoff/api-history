<?php


namespace app\dto;


use Monolog\Logger;

abstract class BaseDto implements Validatable
{

    public ?string $error = null;

    public function __construct(array $data)
    {
        foreach ($data as $property => $value) {
            if (property_exists($this, $property)) {
                $this->$property = $value;
            }
        }

        $this->validate();
    }

    abstract public function validate(): void;

    public function hasError(): bool
    {
        if ($this->error) {
            app()->config('container')->get('logger')->log(Logger::INFO, 'Validation error:' . $this->error);
        }
        return $this->error !== null;
    }
}