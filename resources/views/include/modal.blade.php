<div id="delete_confirm_modal" class="modal fade" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
		  	<div class="modal-header alert-danger">
		    	<button type="button" class="close" data-dismiss="modal">&times;</button>
		    	<h4 class="modal-title">Delete Confirmation</h4>
		  	</div>
		  	<div class="modal-body">
				<form id="delete_confirm_form">
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group">
							    <label class="text-danger">Are you sure, you want to delete this ?</label>
							</div>
						</div>
						<input type="hidden" name="slack" value="" id="delete_confirm_slack">
						<input type="hidden" name="token" value="{{ $token }}">
					</div>
				</form>
				<div id="delete_confirm_message"></div>
		  	</div>
		  	<div class="modal-footer">
		    	<button  class="btn btn-default" data-dismiss="modal">Close</button>
		    	<button  class="btn btn-danger" id="delete_confirm_btn">Delete</button>
		  	</div>
		</div>
	</div>
</div>