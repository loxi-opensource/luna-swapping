<?php

namespace app\common\types\user_draft;

class BaseItem implements Arrayable
{
    public function __construct(array $data)
    {
        $reflection = new \ReflectionClass($this);
        foreach ($reflection->getProperties() as $property) {
            $property->setAccessible(true);
            $propertyName = $property->getName();
            $property->setValue($this, $data[$propertyName] ?? null);
        }
    }

    public function toArray(): array
    {
        $result = [];
        $reflection = new \ReflectionClass($this);
        foreach ($reflection->getProperties() as $property) {
            $property->setAccessible(true);
            $propertyName = $property->getName();
            $result[$propertyName] = $property->getValue($this);
        }
        return $result;
    }
}
