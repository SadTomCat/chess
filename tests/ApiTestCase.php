<?php

namespace Tests;

class ApiTestCase extends TestCase
{
    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->withHeaders(['Accept' => 'application/json']);
    }
}
