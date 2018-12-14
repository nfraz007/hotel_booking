<div class="row">
	@if(count($hotel))
		@foreach($hotel as $key => $value)
		<div class="col-sm-3">
			<div class="card">
			  	<img class="card-img-top" src="https://picsum.photos/200/120/?random" alt="Card image">
			  	<div class="card-body">
			    	<a href="{{ route('detail', ['slack' => $value->slack]) }}"><b class="card-title">{{ $value->name }}</b></a>
			    	<p class="card-text">
			    		{{ $value->city }}, {{ $value->country }} 
			    		<span class="badge badge-{{ (in_array($value->review_rating, [0,1,2]) ? 'danger' : ((in_array($value->review_rating, [3,4,5,6])) ? 'warning' : 'success' )) }} pull-right">{{ $value->review_rating }} / 10 <i class="fa fa-star"></i></span>
			    	</p>
			  	</div>
			</div>
		</div>
		@endforeach
	@else
	<div class="col-sm-12 text-center">
		<h1 class="display-1"><i class="fa fa-frown-o"></i></h1>
		<p>No Data Found</p>
	</div>
	@endif
</div>