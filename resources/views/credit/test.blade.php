@extends('layouts.app')

@section('content')
    <div class="roles">

        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-gray-700 uppercase font-bold">Test</h2>
            </div>
            
        </div>

        <div class="table w-full mt-8 bg-white rounded">
            <form action="{{ route('test') }}" method="post" class="w-full max-w-lg px-6 py-12" enctype="multipart/form-data">
                @csrf
                <div class="md:flex md:items-center mb-6">
                    <div class="md:w-1/3">
                        <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4" for="inline-full-name">
                           Customer
                        </label>

                    </div>
                    <div class="md:w-2/3">
                       <select class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500" name="order_id" >
                            <option>--Change Customer--</option>
                            @foreach($data as $d)
                            <option value="{{$d->id}}">U000{{$d->id}}-{{$d->customer->name}}</option>
                            @endforeach
                         </select>

                        @error('name')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>

                   
                </div>
                <div class="md:flex md:items-center mb-6">
                    <div class="md:w-1/3">
                        <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4" for="inline-full-name">
                        PUL
                        </label>
                    
                    </div>  
                        <div class="md:w-2/3">
                          <input name="pul" class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500" id="inline-full-name" type="number">

                        </div>
                </div>
               
                <div class="md:flex md:items-center">
                    <div class="md:w-1/3"></div>
                    <div class="md:w-2/3">
                        <button class="shadow bg-blue-500 hover:bg-blue-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded" type="submit">
                            Create 
                        </button>
                    </div>
                </div>
            </form>        
        </div>
        
    </div>
@endsection