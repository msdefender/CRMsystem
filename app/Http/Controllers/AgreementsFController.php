<?php

namespace App\Http\Controllers;

use App\AgreementsF;
use App\Agreements;
use Illuminate\Http\Request;

class AgreementsFController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data =  AgreementsF::latest()->paginate(10);
        //dd($data);
        return view('agreementsF.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       // $product = AgreementsF::findOrFail;
        $data =  Agreements::latest()->get();
        return view('agreementsF.create',compact('data'));
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
      //     dd($input);
        AgreementsF::create($input);
     
        return redirect()->route('agreementsF.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Agreements  $agreements
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       //dd(3);
       AgreementsF::findOrFail($id)->delete();
       return back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Agreements  $agreements
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
     
        $product = AgreementsF::findOrFail($id);
        $agreements  = Agreements::get();
        return view('agreementsF.edit',compact('product','agreements'));
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
        $product = AgreementsF::findOrFail($id);

       
        $product->update([
            'agreement_id' => $request->agreement_id,
            'file_name'  => $request->file_name,
            'file_value'  => $request->file_value,
       
            
        ]);

        
        return redirect()->route('agreementsF.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Agreements  $agreements
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //dd(1);
     //   AgreementsF::findOrFail($id)->delete();

     

      

       // return back();
    }
}
