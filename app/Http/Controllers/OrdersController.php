<?php

namespace App\Http\Controllers;

use App\Agreements;
use App\AgreementsF;
use App\Customers;
use App\Orders;
use App\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products =  Orders::latest()->paginate(10);

        return view('orders.index',compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       $data = Agreements::latest()->paginate(10);
       $customers = Customers::latest()->get();
      
        return view('orders.create',compact('data','customers'));
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
    
        Orders::create($input);
     
        return redirect()->route('orders.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Products  $products
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = Orders::findOrFail($id);
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
      
        $data = Agreements::latest()->paginate(10);
        $customers = Customers::latest()->get();
        $product = Orders::findOrFail($id);
        $custo = Customers::where('id',$product->customer_id)->first();
        return view('orders.edit',compact('product','data','customers','custo'));
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
        
        $product = Orders::where('id',$id)->first();  
        $product->update([
            'status'  => $request->status,
            'customer_id'  => $request->customer_id,
            'has_agreements'  => $request->has_agreements,
            'payments' => $request->payments,
        ]);

        
        return redirect()->route('orders.index');
    }

    public function see($order_id)
    {
       
        $data = Products::join('baskets', 'products.id', '=', 'baskets.product_id')
        ->where('baskets.order_id',$order_id)
        ->get();
        $display = Orders::where('id',$order_id)->first();
       // $credit = Orders::where('id',$order_id)->first();
        return view('orders.see',compact('order_id','data','display'));
       // return view('orders.see',compact('id','data','customer_id','display'));
    }

    public function autocomplete(Request $request)
    {

        $data = DB::table('products')->select("name as value", "id")
        ->where('name', 'LIKE', '%'. $request->get('search'). '%')
        ->get();      
        return response()->json($data);
    }
 
    public function agree(Request $request,$order_id)
    {
     
     // dd($request->total);
        $data  =Agreements::get();
        Orders::where('id',$order_id)->update([
         'total' => $request->total
        ]);
        $customer_id = Orders::where('id',$order_id)->first()->customer_id;
        return view('orders.agree',compact('data','customer_id','order_id'));
    }

    

    public function agg($id,$customer_id,$order_id)
    {
        $data = AgreementsF::where('agreement_id',$id)->get();
        $data1 = Agreements::where('id',$id)->first();
        return view('orders.post_agree',compact('data','data1','customer_id','order_id'));
    }

    public function file(Request $request)
    {
      //  dd($request);
        $fileName = $request->filename;
        $file = public_path($fileName);
        $fields = AgreementsF::where('agreement_id',$request->id)->get();
        $phpword = new \PhpOffice\PhpWord\TemplateProcessor($file);
        $phpword->cloneBlock('firstblock', 1, true, true);
        foreach($fields as $field)
       { 
     
         $phpword->setValue($field->field_value.'#1',$request[$field->input_name]);
       
       }
    
        $phpword->saveAs(time().$fileName);

        $last =  Orders::where('customer_id',$request->customer_id)->update([
            'agreement_id' => $request->id,
            'status' => 'accepted',
            'display' => false,
            'file_name' =>time().$fileName
        
           ]);
       
           return redirect()->route('see',['order_id' => $request->order_id]);
    	//return response()->download(public_path(time().$fileName));
        
        
    }

    public function download($order_id)
    {
        $fileName =Orders::where('id',$order_id)->first()->file_name;
        return response()->download(public_path($fileName));
    }

    public function change(Request $request)
    {

        $data = DB::table('agreement_fields')
        ->where('agreement_id',$request->get('chan'))
        ->get();  
         
        return response()->json($data);
    }
}
