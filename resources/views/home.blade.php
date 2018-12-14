<div class="row">
	<div class="col-sm-6 col-9">
		<div class="form-group">
		  	<input type="text" class="form-control" id="filter_search" placeholder="Search keyword">
		  	<input type="hidden" id="sort_by" value="">
		  	<input type="hidden" id="sort_type" value="">
		  	<input type="hidden" id="filter_page" value="1">
		</div>
	</div>
	<div class="col-sm-6 col-3">
		<div class="dropdown pull-right">
		  	<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
		    	Sort By
		  	</button>
		  	<div class="dropdown-menu dropdown-menu-right">
		    	<a class="dropdown-item filter_sort" data-sort_by="name" data-sort_type="asc" href="#">Name (asc)</a>
		    	<a class="dropdown-item filter_sort" data-sort_by="name" data-sort_type="desc" href="#">Name (desc)</a>
		    	<a class="dropdown-item filter_sort" data-sort_by="review_rating" data-sort_type="asc" href="#">Rating (asc)</a>
		    	<a class="dropdown-item filter_sort" data-sort_by="review_rating" data-sort_type="desc" href="#">Rating (desc)</a>
		  	</div>
		</div>
	</div>
</div>
<div id="hotel_list"></div>