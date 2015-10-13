<!doctype html>
<html lang="en">
 <head>
  <meta charset="UTF-8">
  <title>
   Payment Page
  </title>
 </head>
 <body>
 <h1>Payment Page</h1>
  {{ Form::open(array('url' => url('payment'), 'id'=>'payment', 'method' => 'post')) }}
    {{ Form::label('lbl_name','Name',array('id'=>'','class'=>'')) }}
    {{ Form::text('text_name','',array('id'=>'','class'=>'')) }}<br/>
	  {{ Form::label('lbl_payment_gateway','Payment Gateway',array('id'=>'','class'=>'')) }}
	  {{ Form::select('select_payment_gateway',$data,'enabled') }}<br/>
	  {{ Form::label('lbl_value','Value',array('id'=>'','class'=>'')) }}
	  {{ Form::text('text_value','',array('id'=>'text_value','class'=>'')) }}
    {{ Form::select('select_value_currency',array('eur'=>'EUR','usd'=>'USD','try'=>'TRY'),'enabled') }}
  {{ Form::close() }}
  <input class="" name="pay" id="pay" type="button" value="Pay"/>
 </body>
 <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
 <script type="text/javascript">
    $(document).ready(function() {
        $("#pay").click(function(e) {
        e.preventDefault();
        $.ajax({
            type: 'post',
            cache: false,
            url:'/payment',                       
            data: $('#payment').serialize(),
            beforeSend: function() {

            },
            success: function(data) {
                if(data["success"] == "0")
                  alert(data["error_message"]);
                else
                  alert(data["success_message"]);
            },
            error:function (xhr, ajaxOptions, thrownError){
                alert("error:"+xhr.status);
            },
            dataType: "json"
        });
      });
   });
 </script>
</html>