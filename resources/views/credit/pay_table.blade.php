@extends('layouts.app')

@section('content')

<div class="roles-permissions">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-gray-700 uppercase font-bold">Pay Table</h2>
            </div>
            <div class="flex flex-wrap items-center">
                <a href="{{ route('credit.index') }}" class="bg-gray-200 text-gray-700 text-sm uppercase py-2 px-4 flex items-center rounded">
                    <svg class="w-3 h-3 fill-current" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="long-arrow-alt-left" class="svg-inline--fa fa-long-arrow-alt-left fa-w-14" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M134.059 296H436c6.627 0 12-5.373 12-12v-56c0-6.627-5.373-12-12-12H134.059v-46.059c0-21.382-25.851-32.09-40.971-16.971L7.029 239.029c-9.373 9.373-9.373 24.569 0 33.941l86.059 86.059c15.119 15.119 40.971 4.411 40.971-16.971V296z"></path></svg>
                    <span class="ml-2 text-xs font-semibold">Back</span>
                </a>
            </div>
        </div>
        <div class="mt-8 bg-white rounded border-b-4 border-gray-300">
            <div class="flex flex-wrap items-center uppercase text-sm font-semibold bg-gray-300 text-gray-600 rounded-tl rounded-tr">
                <div class="w-2/12 px-4 py-3">Customer</div>
                <div class="w-2/12 px-4 py-3 ">Pay Date</div>
                <div class="w-2/12 px-4 py-3 ">Sum</div>
                <div class="w-2/12 px-4 py-3">Is pay</div>
                <div class="w-2/9 px-4 py-3 text-right">Debt</div>
            </div>
            <?php $sum = 0; $debt = 0; ?>
            @foreach($datas as $d)
             <?php 
               $sum =$sum + ( $d->sum);
               $debt = $debt +($d->debt);
              ?>
            @endforeach
            @foreach ($datas as $data)
                <div class="flex flex-wrap items-center text-gray-700 border-t-2 border-l-4 border-r-4 border-gray-300">
                    <div class="w-2/12 px-4 py-3 flex flex-wrap">U000{{ $data->order_id }}</div>
                    <div class="w-2/12 px-4 py-3 flex flex-wrap"> {{ $data->pay_date }} </div>
                    <div class="w-2/12 px-4 py-3 flex flex-wrap"> {{ $data->sum }} </div>
                    <div class="w-2/12 px-4 py-3 flex flex-wrap"> {{ $data->is_pay }} </div>
                    <div class="w-2/12 px-4 py-3 flex flex-wrap"> {{ $data->debt }} </div>
                   
                   
                </div>
            @endforeach
            <div class="flex flex-wrap items-center uppercase text-sm font-semibold bg-gray-300 text-gray-600 rounded-tl rounded-tr">
           
           <div class="w-2/12 px-3 py-3" style="display: flex;"> TOTAL: <p id="total" style="margin-left: 320px;">{{$sum}}</p><p style="margin-left: 340px;">{{$debt}}  </p></div>
          
            </div>  
        </div>
    </div>

@endsection