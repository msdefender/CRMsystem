@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html>
<head>
   
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" />
    
</head>

<body>
<form action="{{ route('file') }}" method="POST" class="w-full max-w-lg px-6 py-12" enctype="multipart/form-data">
 @csrf

<div class="mt-8 bg-white rounded border-b-4 border-gray-300">
<div class="row"> 
  <div class="col-lg-5">
  
    @foreach($data as $d)
    <label  for="">{{ $d->input_name }}</label>
  
    
    <br>
    <input class="form-control" type="text" value="" name="{{$d->input_name}}" required/>
    <br>
    @endforeach
    <input type="hidden" name="id" value="{{$data1->id}}">
    <input type="hidden" name="customer_id" value="{{$customer_id}}">
    <input type="hidden" name="order_id" value="{{$order_id}}">
    <input class="form-control" type="hidden" value="{{$data1->name}}" name="filename" />

  </div>
</div>      
 <button type="submit" class="btn btn-success">Save</button>                 
</div>

</form>


</body>
</html>
@endsection