@extends('layouts.app')


@section('content')
<style type="text/css">
	img{
		padding-left: 20px;
	}
</style>
<div class="row">

</div>

@php

    echo $r = 'Product name: '.$product[0]->name.'</br>'.
        'Product description: '.$product[0]->description.'</br>'.
        'Product quantity: '.$product[0]->name.'</br>';

@endphp



<div class="container text-center" style="border: 1px solid #a1a1a1;padding: 15px;width: 70%;">
    <img src="data:image/png;base64,{!!DNS2D::getBarcodePNG( $r , 'QRCODE' )!!}" alt="barcode" />
</div>


@endsection