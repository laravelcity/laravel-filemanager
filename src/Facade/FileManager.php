<?php
namespace Laravelcity\FileManager\Facade;

use Illuminate\Support\Facades\Facade;

class FileManager extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'FileManagerClass';
    }
}