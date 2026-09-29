<?php

namespace TestMonitor\Incrementable\Exceptions;

use Exception;

final class MissingIncrementableDefinition extends Exception
{
    /**
     * Reports a missing incrementable field definition.
     *
     * @param string $model
     * @return static
     */
    public static function create($model)
    {
        return new self("The Incrementable field definition is missing in {$model}.");
    }
}
