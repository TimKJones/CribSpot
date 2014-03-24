class A2Cribs.Rentpay
	@init: () ->
		@report_credit = yes
		@braintree = Braintree.create('MIIBCgKCAQEAvxM/Oy1nPH0H/N/kya9jT84pJ78pR5UglboAxJH3yktxWjNNFQ85uNsjd5fKd+XFgEGYyfEqwUuHej1MafyO0Wu2W4HJoau3OmYC3EQRMr5yZR9mR1/3pRmi4JBi/wd3NBXdUZ5ZIjSO2bkaYJSTVcguGKtocKVseBYlHsHtS6tseWOi813cqHz15877F/iOZzdcS84JVGwPdVIdzBqQCpAoiUnPjzNAGeFBk+Rm9y6CtzWGGn6Z7zOxzUf5VJPycoz94Yr7EDIjTy8vF42IHsyOhreWGX/+p9nQGxiey+sslhTlKue7jRkC8IHwAHaXLYH37lrRoriN6emASj7shQIDAQAB');
		@braintree.onSubmitEncryptForm 'braintree-payment-form', @EncryptFormCallback

		@div.find(".back").click (event) =>
			@div.find(".rentpay-step").hide()
			@div.find("." + $(event.currentTarget).data('back')).show()

		@div.find(".next-step").click (event) =>
			if $(event.currentTarget).hasClass "finish-rentpay"
				$("#rentpay-signup").modal("hide")
				@div.find(".rentpay-step").hide()
				@div.find(".part-one").show()
				return
			@div.find(".rentpay-step").hide()
			@div.find("." + $(event.currentTarget).data('next-step')).show()

		@div.find(".pay-option").click (event) =>
			unless $(event.currentTarget).hasClass("inactive")
				@div.find(".pay-option").removeClass("active")
				$(event.currentTarget).addClass("active")
				if $(event.currentTarget).hasClass("show-card")
					@div.find(".white-cover").hide()
					$("#braintree-payment-form").find("[name=venmo]").val("no")
				else
					@div.find(".white-cover").show()
					$("#braintree-payment-form").find("[name=venmo]").val("yes")

		@div.find(".finish-rentpay").click =>
			@div.find(".form-field").each (index, value) ->
				$("#braintree-payment-form").find("[name=#{$(value).data("field-name")}]").val($(value).val())

			$("#braintree-payment-form").submit()

		@div.find(".report-rent").click (event) =>
			$(event.currentTarget).find("img").toggleClass "hide"
			@report_credit = !@report_credit


		@div.find(".add_roommate").click =>
			div = """
				<div class="housemate">
					<input class="gotham-bold input75 email" type="text" placeholder="Housemate's Email">
					<input class="gotham-bold input25 rent" type="text" placeholder="Rent">
				</div>
			"""
			@div.find(".housemates").append div


	@EncryptFormCallback : (event) ->
		housemates = []
		$(".housemate").each (index, value) ->
			email = $(value).find(".email").val()
			rent = $(value).find(".rent").val()
			if rent?.length and email?.length
				housemates.push
					email: email
					rent: rent

		data = $("#braintree-payment-form").serializeObject()
		data.housemates = housemates
		data.report_credit = @report_credit

		$.post '/Rentpays/CreateTransaction', data, () =>
			console.log 'posted'

		return false


	$(document).ready =>
		if (@div = $("#rentpay-signup")).length
			@init()
		password_modal = $("#password-protect")
		if password_modal.length
			password_modal.find(".password_protected").submit ->
				if password_modal.find("input").val() is "GOBLUE"
					password_modal.modal("hide")
				return false
			password_modal.modal
				backdrop: 'static'
				keyboard: false

`$.fn.serializeObject = function()
{
    var o = {};
    var a = this.serializeArray();
		$.each(a, function() {
			if (o[this.name] !== undefined) {
				if (!o[this.name].push) {
					o[this.name] = [o[this.name]];
				}
				o[this.name].push(this.value || '');
			} else {
				o[this.name] = this.value || '';
			}
		});
		return o;
};`
