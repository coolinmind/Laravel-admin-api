<?php

namespace Pl\LaravelAdminApi\Exceptions\Common;

use Illuminate\Http\Request;
use Exception;

class ExcelException extends Exception
{
    public function render(Request $request)
    {
        $whoops = new \Whoops\Run();
        $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler());
        return response($whoops->handleException($this));
    }
}
