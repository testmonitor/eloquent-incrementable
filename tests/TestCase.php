<?php

namespace ByTestGear\Incrementable\Test;

use Illuminate\Database\Schema\Blueprint;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    protected function setUpDatabase($withSoftDeletes = false)
    {
        $builder = $this->app['db']->connection()->getSchemaBuilder();

        $builder->create('records', function (Blueprint $table) use ($withSoftDeletes) {
            $table->increments('id');
            $table->integer('code');
            $table->integer('project')->nullable();
            $table->integer('group')->nullable();

            if ($withSoftDeletes) {
                $table->softDeletes();
            }
        });
    }

    protected function setUpDatabaseWithSoftDeletes()
    {
        $this->setUpDatabase(true);
    }
}
