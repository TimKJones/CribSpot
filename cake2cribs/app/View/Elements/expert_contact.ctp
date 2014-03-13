<div id="expert-contact" class="modal hide fade text-center">
	<img src="/img/founders/indiana.png" alt="" />
	<p class="mission f45 red">Let's find you a place!</p>
	<p class="gotham-bold f18">Enter your info below and Alex, one of our local renter experts, will work with you directly to find and sign a rental</p>
	<div class="expert-divider"></div>
	<form id="contact_alex">
		<input name="email" class="email gotham-bold" type="email" placeholder="Email Address">
		<div class="and_spacer mission gray f24">- and -</div>
		<input name="phone" class="phone gotham-bold" type="text" placeholder="Phone Number">
		<button type="submit">GO</button>
	</form>

</div>

<script type="text/javascript" charset="utf-8">
	$("#contact_alex").submit(function(){
		console.log($(this).serialize());
		$.ajax({
			type: 'POST',
			url: '/',
			data: $(this).serialize()
		})
		$("#expert-contact").modal("hide");
		$("#expert-signup").modal("show");
		return false;
	});
</script>
