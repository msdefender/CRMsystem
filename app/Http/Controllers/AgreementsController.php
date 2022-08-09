<?php

namespace App\Http\Controllers;

use App\Agreements;
use App\File;
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
     //   dd($request);
      
        $request->validate([
        'file' => 'required|file|mimes:doc,docx,csv,xlsx,xls,txt,pdf'
        ]);

        $fileName = time().'.'.$request->file->extension();  
        $request->file->move(public_path(), $fileName);
        
        Agreements::create([
            'title' => $request->title,
            'name' => $fileName,
            'description' => $request->description
        ]);
         
        return redirect()->route('agreements.index');
     }

    /**
     * Display the specified resource.
     *
     * @param  \App\Agreements  $agreements
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = Agreements::findOrFail($id);
        $user->delete();
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Agreements  $agreements
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       
            
        $product =  Agreements::findOrFail($id)->get()[0];
        
       
        return view('agreements.edit',compact('product'));
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
        //dd($request);
        $request->validate([
            'file' => 'required|file|mimes:doc,docx,csv,xlsx,xls,txt,pdf'
            ]);
    
            $fileName = time().'.'.$request->file->extension();  
            $request->file->move(public_path(), $fileName);
             $product = Agreements::findOrFail($id);

       
        $product->update([
            'title'  => $request->title,
            'name'  => $fileName,
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
