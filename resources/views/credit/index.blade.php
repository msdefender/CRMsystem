@extends('layouts.app')

@section('content')

<div class="roles-permissions">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-gray-700 uppercase font-bold">Credits</h2>
            </div>
            <div class="flex flex-wrap items-center">
               
                <a href="{{ route('credit.create') }}" class="bg-gray-200 text-gray-700 text-sm uppercase py-2 px-4 flex items-center rounded">
                    <svg class="w-3 h-3 fill-current" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="plus" class="svg-inline--fa fa-plus fa-w-14" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M416 208H272V64c0-17.67-14.33-32-32-32h-32c-17.67 0-32 14.33-32 32v144H32c-17.67 0-32 14.33-32 32v32c0 17.67 14.33 32 32 32h144v144c0 17.67 14.33 32 32 32h32c17.67 0 32-14.33 32-32V304h144c17.67 0 32-14.33 32-32v-32c0-17.67-14.33-32-32-32z"></path></svg>
                    <span class="ml-2 text-xs font-semibold">Create Credit</span>
                </a>
            </div>
        </div>
        <div class="mt-8 bg-white rounded border-b-4 border-gray-300">
            <div class="flex flex-wrap items-center uppercase text-sm font-semibold bg-gray-300 text-gray-600 rounded-tl rounded-tr">
                <div class="w-2/12 px-4 py-3">Customer</div>
                <div class="w-2/12 px-4 py-3 ">Months</div>
                <div class="w-2/12 px-4 py-3 ">Percentage</div>
                <div class="w-2/12 px-4 py-3">First_pay</div>
                <div class="w-2/12 px-4 py-3">Action</div>
            </div>
            @foreach ($datas as $data)
                <div class="flex flex-wrap items-center text-gray-700 border-t-2 border-l-4 border-r-4 border-gray-300">
                    <div class="w-2/12 px-4 py-3 flex flex-wrap">U000{{ $data->order_id }}</div>
                    <div class="w-2/12 px-4 py-3 flex flex-wrap"> {{ $data->month }} </div>
                    <div class="w-2/12 px-4 py-3 flex flex-wrap"> {{ $data->percentage }}% </div>
                    <div class="w-2/12 px-4 py-3 flex flex-wrap"> {{ $data->first_pay }} </div>
                    <div class="w-2/12 px-4 py-3 flex flex-wrap">
                       
                        <a style="margin-left: 5px;" href="{{ route('credit.show',$data->order_id) }}">   
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                        <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                        <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                        </svg>
                        </a>
                    </div>
                   
                </div>
            @endforeach
            
        </div>
    </div>

@endsection