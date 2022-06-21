<?php

namespace App\Http\Controllers;

use App\Units;
use Illuminate\Http\Request;

class UnitsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       
        $data = Units::latest()->paginate(10);

        return view('units.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('units.create');
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
        Units::create($input);
     
        return redirect()->route('units.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Units  $units
     * @return \Illuminate\Http\Response
     */
    public function show(Units $products,$id)
    {
        $user = Units::findOrFail($id);
        $user->delete();
       
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Units  $units
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Units::findOrFail($id);
        return view('units.edit',compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Units  $units
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $data = Units::findOrFail($id);
        $data->update([
            'name'  => $request->name,
            
        ]);
        return redirect()->route('units.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Units  $units
     * @return \Illuminate\Http\Response
     */
    public function destroy(Units $units)
    {
        //
    }
}
