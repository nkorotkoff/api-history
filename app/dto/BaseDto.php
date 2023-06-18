<?php


namespace app\dto;


abstract class BaseDto implements Validatable
{
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
}