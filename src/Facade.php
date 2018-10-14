<?php
namespace Nlksoft\SmsGateway;

use Illuminate\Support\Facades\Facade as BaseFacace;

class Facade extends BaseFacace
{
    protected static function getFacadeAccessor()
    {
        return 'nlksms';
    }
}