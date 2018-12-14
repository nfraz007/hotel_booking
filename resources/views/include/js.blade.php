<!-- jQuery -->
<script src="{{ asset('plugins/jquery/dist/jquery.min.js') }}"></script>
<!-- Jquery UI -->
<script src="{{ asset('plugins/jquery-ui/jquery-ui.js') }}"></script>
<!-- Bootstrap -->
<script src="{{ asset('plugins/popper/js/popper.js') }}"></script>
<script src="{{ asset('plugins/bootstrap4/js/bootstrap.min.js') }}"></script>
<!-- bootstrap-daterangepicker -->
<script src="{{ asset('plugins/moment/min/moment.min.js') }}"></script>
<script src="{{ asset('plugins/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
<!-- js validator  -->
<script src="{{ asset('plugins/jquery-validate/jquery.validate.js') }}"></script>

<!-- app Scripts -->
<script src="{{ asset('js/app.js') }}"></script>

@if(isset($js) && is_array($js) && count($js))
	@foreach($js as $my_js)
		<script src="{{ asset('js/'.$my_js) }}"></script>
	@endforeach
@endif