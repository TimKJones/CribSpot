(function() {

  A2Cribs.Rentpay = (function() {
    var _this = this;

    function Rentpay() {}

    Rentpay.init = function() {
      var _this = this;
      this.report_credit = true;
      this.is_venmo = "no";
      this.braintree = Braintree.create('MIIBCgKCAQEAvxM/Oy1nPH0H/N/kya9jT84pJ78pR5UglboAxJH3yktxWjNNFQ85uNsjd5fKd+XFgEGYyfEqwUuHej1MafyO0Wu2W4HJoau3OmYC3EQRMr5yZR9mR1/3pRmi4JBi/wd3NBXdUZ5ZIjSO2bkaYJSTVcguGKtocKVseBYlHsHtS6tseWOi813cqHz15877F/iOZzdcS84JVGwPdVIdzBqQCpAoiUnPjzNAGeFBk+Rm9y6CtzWGGn6Z7zOxzUf5VJPycoz94Yr7EDIjTy8vF42IHsyOhreWGX/+p9nQGxiey+sslhTlKue7jRkC8IHwAHaXLYH37lrRoriN6emASj7shQIDAQAB');
      this.braintree.onSubmitEncryptForm('braintree-payment-form', this.EncryptFormCallback);
      this.div.find(".back").click(function(event) {
        _this.div.find(".rentpay-step").hide();
        return _this.div.find("." + $(event.currentTarget).data('back')).show();
      });
      this.div.find(".next-step").click(function(event) {
        if (_this.validate_inputs(event.currentTarget)) {
          if ($(event.currentTarget).hasClass("finish-rentpay")) {
            $("#rentpay-signup").modal("hide");
            _this.div.find(".rentpay-step").hide();
            _this.div.find(".part-one").show();
            return;
          }
          _this.div.find(".rentpay-step").hide();
          return _this.div.find("." + $(event.currentTarget).data('next-step')).show();
        } else {
          return A2Cribs.UIManager.Error("Please complete all fields!");
        }
      });
      this.div.find(".pay-option").click(function(event) {
        if (!$(event.currentTarget).hasClass("inactive")) {
          _this.div.find(".pay-option").removeClass("active");
          $(event.currentTarget).addClass("active");
          if ($(event.currentTarget).hasClass("show-card")) {
            _this.div.find(".white-cover").hide();
            $("#braintree-payment-form").find("[name=venmo]").val("no");
            return _this.is_venmo = "no";
          } else {
            _this.div.find(".white-cover").show();
            $("#braintree-payment-form").find("[name=venmo]").val("yes");
            return _this.is_venmo = "yes";
          }
        }
      });
      this.div.find(".finish-rentpay").click(function() {
        _this.div.find(".form-field").each(function(index, value) {
          return $("#braintree-payment-form").find("[name=" + ($(value).data("field-name")) + "]").val($(value).val());
        });
        return $("#braintree-payment-form").submit();
      });
      this.div.find(".report-rent").click(function(event) {
        $(event.currentTarget).find("img").toggleClass("hide");
        return _this.report_credit = !_this.report_credit;
      });
      return this.div.find(".add_roommate").click(function() {
        var div;
        div = "<div class=\"housemate\">\n	<input class=\"gotham-bold input75 email\" type=\"text\" placeholder=\"Housemate's Email\">\n	<input class=\"gotham-bold input25 rent\" type=\"text\" placeholder=\"Rent\">\n</div>";
        return _this.div.find(".housemates").append(div);
      });
    };

    Rentpay.validate_inputs = function(target) {
      var retVal;
      retVal = true;
      if ($(target).closest(".rentpay-step").hasClass("part-three")) return true;
      if ($(target).closest(".rentpay-step").hasClass("part-two") && Rentpay.is_venmo === "yes") {
        return true;
      }
      if ($(target).closest(".rentpay-step").hasClass("part-two") && Rentpay.is_venmo === "no") {
        if ($("#card_number").val().length !== 16) {
          A2Cribs.UIManager.Error("Please type a valid card number");
          return false;
        }
      }
      $(target).closest(".rentpay-step").find("input").each(function(index, value) {
        if (!$(value).val().length) return retVal = false;
      });
      return retVal;
    };

    Rentpay.EncryptFormCallback = function(event) {
      var data, housemates;
      housemates = [];
      $(".housemate").each(function(index, value) {
        var email, rent;
        email = $(value).find(".email").val();
        rent = $(value).find(".rent").val();
        if ((rent != null ? rent.length : void 0) && (email != null ? email.length : void 0)) {
          return housemates.push({
            email: email,
            rent: rent
          });
        }
      });
      data = $("#braintree-payment-form").serializeObject();
      data.housemates = housemates;
      data.build_credit = this.report_credit;
      $.ajax({
        type: 'POST',
        url: '/Rentpays/CreateTransaction',
        data: data,
        success: function() {
          A2Cribs.UIManager.CloseLogs();
          return A2Cribs.UIManager.Success("Thanks for signing up! Your payment has been recorded!");
        },
        error: function() {
          A2Cribs.UIManager.CloseLogs();
          return A2Cribs.UIManager.Error("There has been an error setting up your account. Please chat us in the bottom-left corner for help!");
        }
      });
      return false;
    };

    $(document).ready(function() {
      var password_modal;
      if ((Rentpay.div = $("#rentpay-signup")).length) Rentpay.init();
      password_modal = $("#password-protect");
      if (password_modal.length) {
        password_modal.find(".password_protected").submit(function() {
          if (password_modal.find("input").val() === "GOBLUE") {
            password_modal.modal("hide");
          } else {
            A2Cribs.UIManager.CloseLogs();
            A2Cribs.UIManager.Error("Password was incorrect");
          }
          return false;
        });
        return password_modal.modal({
          backdrop: 'static',
          keyboard: false
        });
      }
    });

    return Rentpay;

  }).call(this);

  $.fn.serializeObject = function()
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
};;

}).call(this);
