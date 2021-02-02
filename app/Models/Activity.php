<?php

namespace App\Models;

use jeremykenedy\LaravelLogger\App\Models\Activity as Model;

class Activity extends Model
{
    protected $table      = 'laravel_logger_activity';
    protected $primaryKey = 'id';
    public $timestamps    = false;

    protected $softDelete = false;
}
