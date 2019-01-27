<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class TestController extends Controller
{
    public function index()
    {
        return $newCollection = collect([1, 2, 3, 4, 5]);
    }
}
