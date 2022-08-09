<?php

namespace App\Http\Controllers;

use App\Credit;
use App\Orders;
use App\Pay;
use Illuminate\Http\Request;

class CreditController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datas = Credit::latest()->paginate(10);

        return view('credit.index', compact('datas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = Orders::where(['display' => 0,'credit' => 0])->get();
       // dd($data);
        return view('credit.create',compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      
        $month = $request->month;
        $debt = Orders::findOrFail($request->order_id)->first()->total; 
        $tDebt =($debt*(1+($request->percentage)/100))-$request->first_pay;
        $d =str_replace('-','',$request->start_date);
        $day =$d%100;
        $m =(($d-$day)%10000)/100;
        $y = (($d-$m*100-$day))/10000;
       
        Credit::create([
            'month' =>$month,
            'percentage' =>$request->percentage,
            'debt' =>$debt-$request->first_pay,
            'first_pay' => $request->first_pay,
            'order_id' => $request->order_id,
            'start_date' => $request->start_date,
            ]);
      
        for($i = 1;$i <= $month;$i++)
        {
        
            $new_date = date('Y-m-d', mktime(0, 0, 0, $m + $i, $day, $y));
            Pay::create([
                'order_id' => $request->order_id,
                'pay_date' => $new_date,
                'sum' => round($tDebt/$month),
                'debt' => round($tDebt/$month),
        
            ]);

        }
        Orders::where('id',$request->order_id)->update([
            'credit' => 1
        ]);
        return redirect()->back();

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Credit  $credit
     * @return \Illuminate\Http\Response
     */
    public function show($order_id)
    {
      //dd($order_id);
        $datas = Pay::where('order_id',$order_id)->get();
      //  dd($datas);
        return view('credit.pay_table',compact('datas'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Credit  $credit
     * @return \Illuminate\Http\Response
     */
    public function edit(Credit $credit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Credit  $credit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Credit $credit)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Credit  $credit
     * @return \Illuminate\Http\Response
     */
    public function destroy(Credit $credit)
    {
        //
    }

    public function test1()
    {
        $data = Orders::get();
     //   dd($data);//$creditor = Pay::select('sum','debt')->where(['order_id' => $request->order_id,'is_pay' => 'NO'])->get();
        return view('credit.test',compact('data'));
        
    }

    public function test(Request $request)
    {
        $pul = $request->pul;
        $creditor = Pay::where(['order_id' => $request->order_id,'is_pay' => 'NO'])->get();
        $count = count($creditor);
        $i = 0;   
        while($pul != 0)
        {
            
            if($creditor[$count-1]->debt == 0){
                break;
            }
            if($pul >= $creditor[$i]->debt){

                $pul = $pul - $creditor[$i]->debt ;
                $creditor[$i]->update([
                    'debt' => 0,
                    'is_pay' => 'YES'
                ]);
                
            }else{
               
                $creditor[$i]->update([
                    'debt' => $creditor[$i]->debt- $pul,
                    
                ]);

                $pul = 0;
            }

            $i++;
        }
       
        return redirect()->back();
    }
}
