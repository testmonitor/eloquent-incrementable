<?php

namespace TestMonitor\Incrementable\Test;

use TestMonitor\Incrementable\Test\Models\Record;
use TestMonitor\Incrementable\Traits\Incrementable;
use TestMonitor\Incrementable\Exceptions\MissingIncrementableDefinition;

class WithoutIncrementableDefinitionTest extends TestCase
{
    /**
     * @var \TestMonitor\Incrementable\Test\Models\Record
     */
    protected $record;

    public function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase();

        $this->record = new class() extends Record
        {
            use Incrementable;
        };
    }

    /** @test */
    public function it_will_throw_an_exception_when_defition_is_missing()
    {
        $this->expectException(MissingIncrementableDefinition::class);

        $record = new $this->record();
        $record->save();
    }
}
