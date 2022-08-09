@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html>
<head>
   
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" />
    
</head>
<style>
body {font-family: Arial, Helvetica, sans-serif;}
* {box-sizing: border-box;}

/* Set a style for all buttons */
button {
  background-color: #04AA6D;
  color: white;
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  cursor: pointer;
  width: 100%;
  opacity: 0.9;
}

button:hover {
  opacity:1;
}

/* Float cancel and delete buttons and add an equal width */
.cancelbtn, .deletebtn {
  float: left;
  width: 50%;
}

/* Add a color to the cancel button */
.cancelbtn {
  background-color: #ccc;
  color: black;
}

/* Add a color to the delete button */
.deletebtn {
  background-color: #f44336;
}

/* Add padding and center-align text to the container */
.container {
  padding: 16px;
  text-align: center;
}

/* The Modal (background) */
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  left: 0;
  top: 0;
  margin-left: 130px; 
  margin-top: 70px;
  width: 100%; /* Full width */
  height: 70%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
 
  padding-top: 50px;
}

/* Modal Content/Box */
.modal-content {
  background-color: #fefefe;
  margin: 5% auto 15% auto; /* 5% from the top, 15% from the bottom and centered */
  border: 1px solid #888;
  width: 600px; /* Could be more or less, depending on screen size */
}

/* Style the horizontal ruler */
hr {
  border: 1px solid #f1f1f1;
  margin-bottom: 25px;
}
 
/* The Modal Close Button (x) */
.close {
  position: absolute;
  right: 35px;
  top: 15px;
  font-size: 30px;
  font-weight:30px ;
  color: #f1f1f1;
}

.close:hover,
.close:focus {
  color: #f44336;
  cursor: pointer;
}

/* Clear floats */
.clearfix::after {
  content: "";
  clear: both;
  display: table;
}

/* Change styles for cancel button and delete button on extra small screens */
@media screen and (max-width: 300px) {
  .cancelbtn, .deletebtn {
     width: 100%;
  }
}
</style>
<body>
     
<div class="container">
    <div style="display: flex;" class="col-lg-5">
    <h3>Products</h1> @if($display->file_name != NULL) <a href="{{ route('download',$order_id) }}"  style="width:150px;display:flex ;margin-left:20px"  class="btn btn-success">Agreement <svg style="margin-left:5px ;" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-download" viewBox="0 0 16 16">
  <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
  <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"/>
</svg></a>

@if($display->credit == 0)
<a href="{{ route('credit.create') }}"  style="width:150px;display:flex ;margin-left:20px"  class="btn btn-success">Create Credit <svg xmlns="http://www.w3.org/2000/svg" style="margin-left:5px ;"  width="20" height="20" fill="currentColor" class="bi bi-calendar-plus" viewBox="0 0 16 16">
  <path d="M8 7a.5.5 0 0 1 .5.5V9H10a.5.5 0 0 1 0 1H8.5v1.5a.5.5 0 0 1-1 0V10H6a.5.5 0 0 1 0-1h1.5V7.5A.5.5 0 0 1 8 7z"/>
  <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
</svg></a>
@endif
@endif
    </div>
    @if($display->display == 1)
    <form action="{{ route('create',$order_id) }}" method="post" >  
    @csrf
   <input type="hidden" id="product_id" name="product_id">
    
    <div class="row"> 

        <div class="col-lg-5">
          <label for="name">Name</label>
          <input class=" form-control" id="search" type="text" name="name" required>
         
        </div>
          <div class="col-lg-1 col-auto">
            <label for="quantity">Quantity</label>
            <input  class=" form-control" type="number" value="1" name="quantity">
          </div>
          
      <div class="col-md-1">
      <button style="margin-top:23px ;" type="submit" class="btn btn-success">ADD</button>
      </div>
      <div class="col-md-1">
      <a  style="margin-top:23px ;" onclick="document.getElementById('id01').style.display='block'" class="btn btn-success">FINISH</a>
      </div>
      </div>
     
      </form>
      @endif
  </div>

  <body>


<div id="id01" class="modal">
  <span onclick="document.getElementById('id01').style.display='none'" class="close" title="Close Modal">×</span>
  <form class="modal-content" action="{{ route('agree',$order_id ) }}" method="get"  >
    @csrf
    <div class="container">
      <h1>Are you sure you want to create agreement?</h1>
      <input type="hidden" id="sum" name="total">
      <div class="clearfix">
       
        <button type="button" onclick="document.getElementById('id01').style.display='none'" class="cancelbtn">Cancel</button>
        <button type="submit"  class="deletebtn">Create</button>
      </div>

    </div>
  </form>
</div>

<script>
// Get the modal
var modal = document.getElementById('id01');

// When the user clicks anywhere outside of the modal, close it

window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}
</script>

</body>

<div class="mt-6 bg-white rounded border-b-6 border-gray-300">
            <div class="flex flex-wrap items-center uppercase text-sm font-semibold bg-gray-300 text-gray-600 rounded-tl rounded-tr">
                <div class="w-1/12 px-3 py-3">Name</div>
                <div class="w-1/12 px-3 py-3">Price</div>
                <div class="w-1/12 px-3 py-3">Unit</div>
                <div class="w-1/12 px-3 py-3">Quantity</div>
                <div class="w-2/12 px-3 py-3">Image</div>
                <div class="w-1/12 px-3 py-3">Total</div>
                @if($display->display == 1)
                <div class="w-2/12 px-4 py-3 text-right">Action</div>
                @endif
            </div>
            <?php $sum = 0; ?>
            @foreach($data as $d)
             <?php 
               $sum =$sum + ( $d->price * $d->quantity);
               
              ?>
             
                <div class="flex flex-wrap items-center text-gray-700 border-t-2 border-l-4 border-r-4 border-gray-300">
                    <div class="w-1/12 px-3 py-3 flex flex-wrap">{{$d->name}}</div>
                    <div class="w-1/12 px-3 py-3 flex flex-wrap">{{$d->price}}  </div>
                    <div class="w-1/12 px-3 py-3 flex flex-wrap">{{$d->unit->name}} </div>
                    <div class="w-1/12 px-3 py-3 flex flex-wrap">{{$d->quantity}}  </div>
                    <div class="w-2/12 px-3 py-3 flex flex-wrap"><img src="/image/{{ $d->image }}" name="image">  </div>
                    <div class="w-2/12 px-3 py-3 flex flex-wrap" >{{$d->price * $d->quantity }}  </div>
                    @if($display->display == 1)
                    <div class="w-3/12 px-3 py-3 flex flex-wrap">
                    
                        <a style="margin-left: 5px;" href="{{ route('destroy',['id'=>$d->id,'order_id'=>$order_id]) }}">   
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                        <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                        </svg>
                        </a>
                    </div>
                    @endif
                </div>
                @endforeach    
                <div class="flex flex-wrap items-center uppercase text-sm font-semibold bg-gray-300 text-gray-600 rounded-tl rounded-tr">
           
                <div class="w-2/12 px-3 py-3" style="display: flex;"> TOTAL: <p id="total" style="margin-left: 510px;">{{$sum}}</p></div>
               
                 </div>      
        </div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script> 
<script>
  var modal = document.getElementById('id01');
  let total = document.getElementById('total').innerHTML;
  document.getElementById('sum').value =total;
//console.log(total);
// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}
</script>
<script type="text/javascript">
    var path = "{{ route('autocomplete') }}";
    $( "#search" ).autocomplete({
    
        source: function( request, response ) {
          $.ajax({
            url: path,
            type: 'GET',
            dataType: "json",
            data: {
               search: request.term
            },
            success: function( data ) {
               response( data );
            }
          });
        },
        select: function (event, ui) {
           $('#search').val(ui.item.label); 
          document.getElementById('product_id').value = ui.item.id;
          //  console.log(ui.item.id);
          //  console.log(ui.item); 
           return false;
        }
      });
 
</script>

</body>
</html>
@endsection