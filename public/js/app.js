App = (function($){
	self = this;

	spin = '<i class="fa fa-spinner fa-spin"></i> &nbsp;&nbsp; ';
	options = {
		login : 'Log In',
		login_spin : spin + 'Logging In',
		save : 'Save',
		save_spin : spin + 'Saving',
		delete : 'Delete',
		delete_spin : spin + 'Deleting',
	};
	config = {
		max_row : 100
	};

	login = function(){
		$(document).on("click", "#login_btn", function(e){
			self.login_ajax();
		});

		$(document).find("#login_form").on("keypress", "#username, #password", function(e){
			var keycode = (e.keyCode ? e.keyCode : e.which);
            if(keycode == '13'){
            	e.preventDefault();
                self.login_ajax();   
            }
		});
	}

	login_ajax = function(){
		if($("#login_form").valid()){
			$("#login_btn").attr('disabled', true);
			$("#login_btn").html(self.options.login_spin);
			$.ajax({
			    url: self.api_url("login"),
			    type:'post',
			    dataType: "json",
			    data: $("#login_form").serialize(),
			    success: function(response) {
			        // console.log(response);
			        self.message("#login_message", response);
			        self.redirect(response);
			        $("#login_btn").attr('disabled', false);
			        $("#login_btn").html(self.options.login);
			    },
			    error: function(xhr) {
			        console.log(xhr);
			        $("#login_btn").attr('disabled', false);
			        $("#login_btn").html(self.options.login);
			    }
			});
		}
	}

	base_url = function(url = ""){
		return $("#base_url").val() + "/" +url;
	}

	api_url = function(url = ""){
		return $("#api_url").val() + "/" +url;
	}

	token = function(){
		return $("#token").val();
	}

	message = function(selector = "", response = "", error = false){
		$(selector).removeClass();
		$(selector).addClass("alert");
		if(response.status){
			if(error == false) $(selector).addClass("alert-success").text(response.message);
		}else{
			$(selector).addClass("alert-danger").text(response.message);
		}
	}

	redirect = function(response = ""){
        if(response.status){
	        window.setTimeout(function(){
	        	if(response.redirect) window.location.href = response.redirect;
	        	else location.reload();
            }, 500);
	    }
	}

	checkbox_get = function(selector = ""){
		data = [];
		$(selector+":checked").each(function(){
		    data.push($(this).val());
		});
		return data;
	}

	checkbox_put = function(selector = "", data = []){
		this.checkbox_reset(selector);

		$(selector).each(function(){
			if(data.includes(parseInt($(this).val()))){
				$(this).prop('checked', true);
			}
		});
	}

	checkbox_reset = function(selector = ""){
		$(selector).each(function(){
			$(this).prop('checked', false);
		});
	}

	return {
		init: function(){
			login();
		},
		options: options,
		config: config,
		base_url: base_url,
		api_url: api_url,
		token: token,
		message: message,
		redirect: redirect,
		checkbox_get: checkbox_get,
		checkbox_put: checkbox_put,
		checkbox_reset: checkbox_reset
	};
})($);

$(document).ready(function(){
	App.init();
});