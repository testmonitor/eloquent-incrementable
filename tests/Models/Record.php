<?php

namespace TestMonitor\Incrementable\Tests\Models;

use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    protected $table = 'records';

    public $timestamps = false;
}
