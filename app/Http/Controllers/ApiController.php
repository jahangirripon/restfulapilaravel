<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use App\Transformers\UserTransformer;

class ApiController extends Controller
{
    use ApiResponser;

    public function __construct()
    {
        //parent::__construct();
        //$this->middleware('transform.input:'.UserTransformer::class)->only(['store', 'update']);
    }
}
