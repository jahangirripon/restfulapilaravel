<?php

namespace App\Http\Controllers\Buyer;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Buyer;
use App\User;

class BuyerController extends ApiController
{
    public function index()
    {
        $buyers = Buyer::has('transactions')->get();

        return $this->showAll($buyers);

        //return response()->json(['data' => $buyers], 200);
    }

    public function show(Buyer $buyer)
    {
        // $buyer = Buyer::has('transactions')->findOrFail($id);

        return $this->showOne($buyer);

        //return response()->json(['data' => $buyer], 200);

    }

}
