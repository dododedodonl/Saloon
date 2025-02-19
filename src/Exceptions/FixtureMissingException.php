<?php

namespace Sammyjo20\Saloon\Exceptions;

class FixtureMissingException extends SaloonException
{
    /**
     * Constructor
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        parent::__construct(sprintf('The fixture "%s" could not be found in storage.', $name));
    }
}
