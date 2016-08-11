@extends('layout.main')

@section('content')

<table>
	<thead>
		<tr>
			<th>Description</th>
			<th>Number</th>
			<th>Unit price</th>
			<th>Amount</th>
		</tr>
	</thead>
	<tbody>
		@foreach($items as $item)
		<tr>
			<td>{{ $item->description() }}</td>
			<td>{{ $item->quantity() }} {{ $item->unitdescription(true) }}</td>
			<td>&euro; {{ number_format($item->price(),2,',','.') }} / {{ $item->unit() }} {{ $item->unitdescription() }}</td>
			<td>&euro; {{ number_format($item->totalamount(),2,',','.') }}</td>
		</tr>
		@endforeach
		
	</tbody>
	<tbody class="total">
		<tr>
			<td>Total</td>
			<td></td>
			<td></td>
			<td>&euro; {{ number_format($cart->totalamount(),2,',','.') }}</td>
		</tr>
	</tbody>
</table>


@stop