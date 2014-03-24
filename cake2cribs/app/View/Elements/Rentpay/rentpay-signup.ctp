<div id="rentpay-signup" class="modal text-center">
	<div class="part-one rentpay-step">
		<div class="background green">
			<div class="white f14 gotham-bold">Your Monthly Rent:</div>
			<input class="form-field f40 gotham-bold" id="rent-amount" data-field-name="amount" type="text" autocomplete="off" value="$0" />
			<div class="personal-info">
				<input class="input50 form-field gotham-bold" data-field-name="full_name" type="text" autocomplete="off" placeholder="Full Name" />
				<input class="input50 form-field gotham-bold" data-field-name="email" type="email" autocomplete="off" placeholder="Email" />
				<input class="form-field gotham-bold" data-field-name="address" type="text" autocomplete="off" placeholder="Your Street Address or Building Name" />
				<input class="form-field gotham-bold" data-field-name="property_manager" type="text" autocomplete="off" placeholder="Property Manager Name" />
			</div>
		</div>

		<div class="f14 light-gray gotham-bold">Guaranteed On-Time</div>

		<button class="next-step gotham-bold btn" data-next-step="part-two">Set Up Rental Payment</button>

		<div class="footer">
			<img class="pull-left" height="50px" width="50px" src="/img/founders/indiana.png" alt="Alex" />
			<div class="pull-left f14 gotham-bold blue">Questions</div>
			<div class="pull-left f12 gotham-bold">Alex is your personal rent payment rep.</div>

			<span class="pull-right">Secure</span>

		</div>

	</div>

	<div class="part-two hide rentpay-step">
		<a href="#">Back</a>
		<div class="f15 dark-gray gotham-bold">Set up rent pay with:</div>
		<div class="pay-options">
			<div class="pay-option">
				<img src="" alt="Card Logo">
				<div class="white f14">Card</div>
			</div>
			<div class="pay-option">
				<img src="" alt="Venmo Logo">
				<div class="white f14">Venmo</div>
			</div>
			<div class="pay-option">
				<div class="coming-soon">COMING SOON</div>
				<img src="" alt="Bank Logo">
				<div class="white f14">Bank</div>
			</div>
		</div>
		<div class="background blue">
			<span>Enter debit card information:</span>
			<input class="form-field" data-field-name="number" type="text" name="card_number" placeholder="Card Number">
			<input class="form-field" data-field-name="month" type="text" name="month" placeholder="MM">
			<input class="form-field" data-field-name="year" type="text" name="year" placeholder="YY">
			<input class="form-field" data-field-name="cvv" type="text" name="cvc" placeholder="CVC">
		</div>
		<span>Next rent payment will be made on <span>March 27th 4pm</span>. A confirmation email will be sent the day after.</span>
		<button class="next-step" data-next-step="part-three">Schedule Rent &amp; Invite Housemates</button>
	</div>

	<div class="part-three hide rentpay-step">
		<img src="" alt="Thumbs Up!">
		<span>Awesome, No More Checks!</span>
		<span>Payment Scheduled for: March 27th</span>
		<div class="background orange">
			<span>Now invite Housemates to Pay Rent:</span>
			<input type="text" placeholder="">
			<input type="text" placeholder="">
			<input type="text" placeholder="">
		</div>
		<a href=""></a>
		<a href=""></a>
		<button class='finish-rentpay'>Finish</button>
	</div>
</div>

<form id="braintree-payment-form" class="hide">
	<input type="text" size="20" autocomplete="off" name="full_name" />
	<input type="text" size="20" autocomplete="off" name="email" />
	<input type="text" size="20" autocomplete="off" name="address" />
	<input type="text" size="20" autocomplete="off" name="property_manager" />
	<input type="text" size="20" autocomplete="off" name="venmo" />
	<input type="text" size="20" autocomplete="off" data-encrypted-name="amount"  name="amount"/>
	<input type="text" size="20" autocomplete="off" data-encrypted-name="number" name="number" />
	<input type="text" size="4" autocomplete="off" data-encrypted-name="cvv" name="cvv" />
	<input type="text" size="2" data-encrypted-name="month" name="month" />
	<input type="text" size="4" data-encrypted-name="year" name="year" />
</form>

