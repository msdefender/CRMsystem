<?php

namespace App\Http\Controllers;

use App\Customers;
use Illuminate\Http\Request;

class CustomersController extends Controller
{
   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $products =  Customers::latest()->paginate(10);
        return view('customers.index',compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('customers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $input = $request->all();
        if ($image = $request->file('image')) {
        $destinationPath = 'image/';
        $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
        $image->move($destinationPath, $profileImage);
        $input['image'] = "$profileImage";
        }
        Customers::create($input);
     
        return redirect()->route('customers.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Products  $products
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = Customers::findOrFail($id);
        $user->delete();
       
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Products  $products
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Customers::findOrFail($id);
        return view('customers.edit',compact('product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Products  $products
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $product = Customers::findOrFail($id);

        if ($image = $request->file('image')) {
            $destinationPath = 'image/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $input['image'] = "$profileImage";
            }
        $product->update([
            'title'  => $request->title,
            'lastname'  => $request->lastname,
            'name'  => $request->firstname,
            'email' => $request->email,
            'phone' => $request->phone,
            
        ]);

        
        return redirect()->route('customers.index');
    }
}
