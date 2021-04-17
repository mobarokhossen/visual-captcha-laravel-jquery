
<link href="{{ asset('captcha/visualcaptcha.css') }}" rel="stylesheet"/>

<div class="form-group text-center" id="frm-captcha">
	<div id="status-message"></div>
	<div class="sample-captcha"></div>
</div>


<script type="text/javascript">
	let base_url = "{{ url('/') }}";
</script>
<script type='text/javascript' src="{{ asset('js/jquery-1.9.1.min.js') }}"></script>
<script src="{{ asset('captcha/visualcaptcha.jquery.js') }}"></script>
<script src="{{ asset('captcha/main.js') }}"></script>