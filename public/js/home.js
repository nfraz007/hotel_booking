Home = (function($){
	self = this;

	action = function(){
		$(".filter_sort").click(function(){
			sort_by = $(this).data("sort_by");
			sort_type = $(this).data("sort_type");

			$("#sort_by").val(sort_by);
			$("#sort_type").val(sort_type);

			self.hotel_ajax()
		})
	}

	hotel = function(){
		$(document).on("keyup", "#filter_search", function(e){
			self.hotel_ajax();
		});

		hotel_ajax();
	}

	hotel_filter = function(){
		filter = {
			search: $("#filter_search").val(),
			sort_by: $("#sort_by").val(),
			sort_type: $("#sort_type").val(),
			page: $("#filter_page").val()
		}
		return filter;
	}

	hotel_ajax = function(){
		$("#hotel_list").html('<h1 class="display-4 text-center">'+App.spin+'</h1>');
		$.ajax({
		    url: self.api_url("hotel_list"),
		    type:'post',
		    dataType: "json",
		    data: self.hotel_filter(),
		    success: function(response) {
		        console.log(response);
		        if(response.status){
		        	$("#hotel_list").html(response.template);
		        }
		    },
		    error: function(xhr) {
		        console.log(xhr);
		    }
		});
	}

	return {
		init: function(){
			action();
			hotel();
		}
	}
})($);

$(document).ready(function(){
	Home.init();
});