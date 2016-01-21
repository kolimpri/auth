<?php

namespace Kolimpri\Auth\Http\Controllers;

use Kolimpri\Auth\InteractsWithkAuthHooks;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use InteractsWithkAuthHooks;
}
