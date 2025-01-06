<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice</title>

<style>
 
</style>
<script type = 'text/javascript'>  
    window.onload = function(){ window.print(); }
</script>
</head>
<body style="font-size: 12px; margin-bottom: 2em;">
            <div style="text-align: center;">
                {{-- <img src="{{ url('storage/Taibah-FC-LOGO-bulat-aja-w-Stroke.png') }}" alt="ZaharaPOS" width="100" /> --}}
                <h4>Kitchen Order {{ $order->id }}</h4>
                <h4 style="margin-top: -12px;">{{ $order->code_tr }}</h4>
            </div>
    <div class="">
        <div style="text-align: center; margin-bottom: 12px;"><b>order items</b></div>
        @foreach ($orderitems as $orderitem)
        <div class="items" style="margin-bottom: 12px;">
            <div style="text-align: left;"><b>#{{$loop->iteration}} {{ $orderitem->product->name }} {{ $orderitem->product->variant }} ({{ $orderitem->quantity }})</b></div>

            @if($orderitem->contain != '')
            @php 
              $contains = Str::of($orderitem->contain)->explode(',')
            @endphp
            @foreach ($contains as $contain)
            @php
                $qtyContain = Str::between($contain, '(', ')');
                if (is_int($qtyContain)) {
                    $qtyCo = $qtyContain*$orderitem->quantity;
                } else {
                    $qtyCo = '__';
                }
                
            @endphp
            <div style="margin-top: 6px; justify-content: space-between; display: flex;"><span style="margin-left: 1em;">{{ $contain }}</span><span>x{{ $orderitem->quantity }} = {{$qtyCo}}</span></div>
            @endforeach
            @endif
        
        </div>
        @endforeach
    </div>
 <hr size="3" color="black">
</body>
</html>