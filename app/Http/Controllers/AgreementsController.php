<?php

namespace App\Http\Controllers;

use App\Agreements;
use Illuminate\Http\Request;

class AgreementsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data =  Agreements::latest()->paginate(10);
        return view('agreements.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        return view('agreements.create');
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
      // dd($input)->file_url;
        Agreements::create($input);
     
        return redirect()->route('agreements.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Agreements  $agreements
     * @return \Illuminate\Http\Response
     */
    public function show(Agreements $agreements)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Agreements  $agreements
     * @return \Illuminate\Http\Response
     */
    public function edit(Agreements $agreements)
    {
        $product =  Agreements::latest()->paginate(10);
        return view('agreements.index',compact('product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Agreements  $agreements
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $product = Agreements::findOrFail($id);

       
        $product->update([
            'title'  => $request->title,
            'file_url'  => $request->file_url,
            'description'  => $request->description,
           
            
        ]);

        
        return redirect()->route('agreements.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Agreements  $agreements
     * @return \Illuminate\Http\Response
     */
    public function destroy(Agreements $agreements)
    {
        //
    }
}
