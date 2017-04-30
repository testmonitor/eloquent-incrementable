<?php

namespace ByTestGear\Incrementable\Test;

use ByTestGear\Incrementable\Test\Models\Record;
use ByTestGear\Incrementable\Traits\Incrementable;
use ByTestGear\Incrementable\Exceptions\MissingIncrementableDefinition;

class WithoutIncrementableDefinitionTest extends TestCase
{
    /**
     * @var \ByTestGear\Incrementable\Test\Models\Record
     */
    protected $record;

    public function setUp()
    {
        parent::setUp();

        $this->setUpDatabase();

        $this->record = new class() extends Record {
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
