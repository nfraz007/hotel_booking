<div class="row">
	<div class="col-sm-12">
		<img class="img-responsive" src="https://picsum.photos/1000/200/?random" width="100%">
	</div>
</div>
<br>
<div class="row">
	<div class="col-sm-12">
		<h2>{{ $data->name }}</h2>
		<h4>Rating : {{ $data->review_rating }} / 10</h4>
		<p>Address : {{ $data->address }}, {{ $data->city }}, {{ $data->country }} - {{ $data->postal_code }}</p>
	</div>
</div>
<div class="row">
	<div class="col-sm-12">
		<div class="card bg-light">
		    <div class="card-body">
		    	<div class="row">
		    		<div class="col-sm-1 col-2">
						<img src="https://picsum.photos/200/200/?random" class="rounded-circle" width="100%">
		    		</div>
		    		<div class="col-sm-10 col-10">
		      			<p class="card-text">{{ $data->review_username }}</p>
		      			<b>{{ $data->review_title }}</b>
		      			<p class="text-dark">{{ $data->review_text }}</p>
		    		</div>
		    	</div>
		    </div>
		  </div>
	</div>
</div>