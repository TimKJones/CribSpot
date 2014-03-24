// Generated by CoffeeScript 1.6.3
(function() {
  A2Cribs.Rentpay = (function() {
    var _this = this;

    function Rentpay() {}

    Rentpay.init = function() {
      var _this = this;
      this.report_credit = true;
      this.braintree = Braintree.create("MIIBCgKCAQEAoae5cN5m4spsJAXDUy7MxIH8hH3PcCO/M4PhXEZI51y5LAk6aT4zsNMzdA0G/+nJyhDnPitc3L3PCzNn+JJjeuKNwP5Il59JAmojqw5y6REzDIpFjCWHZId2qocQTbB56SGpfNd/OJIcBL+xv7ndJhM8uqX5byEpTuWXWOf+Sj83GszqfpQQtNDEWrW1a79ayl+Eg7PtGA/BHKEftlxtKJ1GVkOOdek8P4B2jHqnvfchMN2dMTetZiOWeIkcquCGn55k4cRgDj0i4v2CIQ7BFI+XTmqoaW6zcZHPkZKEWg0tWBhBXTB8JvttF9hZPqJkXR+eaHwJ2OCi6l44GTQHdwIDAQAB");
      this.braintree.onSubmitEncryptForm('braintree-payment-form', this.EncryptFormCallback);
      this.div.find(".back").click(function(event) {
        _this.div.find(".rentpay-step").hide();
        return _this.div.find("." + $(event.currentTarget).data('back')).show();
      });
      this.div.find(".next-step").click(function(event) {
        if ($(event.currentTarget).hasClass("finish-rentpay")) {
          $("#rentpay-signup").modal("hide");
          _this.div.find(".rentpay-step").hide();
          _this.div.find(".part-one").show();
          return;
        }
        _this.div.find(".rentpay-step").hide();
        return _this.div.find("." + $(event.currentTarget).data('next-step')).show();
      });
      this.div.find(".pay-option").click(function(event) {
        if (!$(event.currentTarget).hasClass("inactive")) {
          _this.div.find(".pay-option").removeClass("active");
          $(event.currentTarget).addClass("active");
          if ($(event.currentTarget).hasClass("show-card")) {
            _this.div.find(".white-cover").hide();
            return $("#braintree-payment-form").find("[name=venmo]").val("no");
          } else {
            _this.div.find(".white-cover").show();
            return $("#braintree-payment-form").find("[name=venmo]").val("yes");
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

    Rentpay.EncryptFormCallback = function(event) {
      var data, housemates,
        _this = this;
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
      data.report_credit = this.report_credit;
      $.post('/Rentpays/CreateTransaction', data, function() {
        return console.log('posted');
      });
      return false;
    };

    $(document).ready(function() {
      var password_modal;
      if ((Rentpay.div = $("#rentpay-signup")).length) {
        Rentpay.init();
      }
      password_modal = $("#password-protect");
      if (password_modal.length) {
        password_modal.find(".password_protected").submit(function() {
          if (password_modal.find("input").val() === "GOBLUE") {
            password_modal.modal("hide");
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
