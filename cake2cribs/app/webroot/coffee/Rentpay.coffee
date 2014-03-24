class A2Cribs.Rentpay
	@init: () ->
		@report_credit = yes
		@is_venmo = "no"
		@braintree = Braintree.create("MIIBCgKCAQEAoae5cN5m4spsJAXDUy7MxIH8hH3PcCO/M4PhXEZI51y5LAk6aT4zsNMzdA0G/+nJyhDnPitc3L3PCzNn+JJjeuKNwP5Il59JAmojqw5y6REzDIpFjCWHZId2qocQTbB56SGpfNd/OJIcBL+xv7ndJhM8uqX5byEpTuWXWOf+Sj83GszqfpQQtNDEWrW1a79ayl+Eg7PtGA/BHKEftlxtKJ1GVkOOdek8P4B2jHqnvfchMN2dMTetZiOWeIkcquCGn55k4cRgDj0i4v2CIQ7BFI+XTmqoaW6zcZHPkZKEWg0tWBhBXTB8JvttF9hZPqJkXR+eaHwJ2OCi6l44GTQHdwIDAQAB")
		@braintree.onSubmitEncryptForm 'braintree-payment-form', @EncryptFormCallback

		@div.find(".back").click (event) =>
			@div.find(".rentpay-step").hide()
			@div.find("." + $(event.currentTarget).data('back')).show()

		@div.find(".next-step").click (event) =>
			if @validate_inputs(event.currentTarget)
				if $(event.currentTarget).hasClass "finish-rentpay"
					$("#rentpay-signup").modal("hide")
					@div.find(".rentpay-step").hide()
					@div.find(".part-one").show()
					return
				@div.find(".rentpay-step").hide()
				@div.find("." + $(event.currentTarget).data('next-step')).show()
			else
				A2Cribs.UIManager.Error "Please complete all fields!"

		@div.find(".pay-option").click (event) =>
			unless $(event.currentTarget).hasClass("inactive")
				@div.find(".pay-option").removeClass("active")
				$(event.currentTarget).addClass("active")
				if $(event.currentTarget).hasClass("show-card")
					@div.find(".white-cover").hide()
					$("#braintree-payment-form").find("[name=venmo]").val("no")
					@is_venmo = "no"
				else
					@div.find(".white-cover").show()
					$("#braintree-payment-form").find("[name=venmo]").val("yes")
					@is_venmo = "yes"

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


	@validate_inputs: (target) =>
		retVal = true
		if $(target).closest(".rentpay-step").hasClass("part-three")
			return true
		if $(target).closest(".rentpay-step").hasClass("part-two") and @is_venmo is "yes"
			return true
		$(target).closest(".rentpay-step").find("input").each (index, value) ->
			unless $(value).val().length
				retVal = false

		return retVal
			
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
		data.build_credit = @report_credit

		$.post '/Rentpays/CreateTransaction', data, () =>
			A2Cribs.UIManager.CloseLogs()
			A2Cribs.UIManager.Success "Thanks for signing up! Your payment has been recorded!"
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
				else
					A2Cribs.UIManager.CloseLogs()
					A2Cribs.UIManager.Error "Password was incorrect"
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
