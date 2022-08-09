<?php

namespace App\Http\Controllers;

use App\Baskets;
use App\Products;
use App\Orders;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Null_;

class BasketsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
     //  dd($id);
        return view('orders.see');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request,$order_id)
    {
       
        Baskets::create([
        'product_id' =>   $request->product_id,
        'order_id' =>   $order_id,
        'quantity' =>   $request->quantity
        ]);
        return redirect()->route('see',['order_id' => $order_id]);
       
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Baskets  $baskets
     * @return \Illuminate\Http\Response
     */
    public function show(Baskets $baskets)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Baskets  $baskets
     * @return \Illuminate\Http\Response
     */
    public function edit(Baskets $baskets)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Baskets  $baskets
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Baskets $baskets)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Baskets  $baskets
     * @return \Illuminate\Http\Response
     */
    public function destroy($id,$order_id)
    {
            
        Baskets::findOrFail($id)->delete();
        return redirect()->route('see',['order_id' => $order_id]);
    }
}
