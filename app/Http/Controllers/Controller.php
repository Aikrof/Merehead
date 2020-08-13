<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use App\Core\Rest\RestController;

class Controller extends RestController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
