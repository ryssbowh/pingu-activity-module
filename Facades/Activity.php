<?php
namespace Pingu\Activity\Facades;

use Illuminate\Support\Facades\Facade;

class Activity extends Facade
{

    protected static function getFacadeAccessor()
    {

        return 'activity';

    }

}