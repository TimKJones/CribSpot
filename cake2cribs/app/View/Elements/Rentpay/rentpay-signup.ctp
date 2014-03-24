<div id="rentpay-signup" class="modal text-center">
	<div class="part-one rentpay-step">
		<div class="background green">
			<div class="white f14 gotham-bold m10">Your Monthly Rent:</div>
			<input class="form-field f40 gotham-bold" id="rent-amount" data-field-name="amount" type="text" autocomplete="off" value="$0" />
			<div class="personal-info">
				<input id="full_name" class="input50 form-field gotham-bold" data-field-name="full_name" type="text" autocomplete="off" placeholder="Full Name" />
				<input id="email" class="input50 form-field gotham-bold" data-field-name="email" type="email" autocomplete="off" placeholder="Email" />
				<input class="form-field gotham-bold" data-field-name="address" type="text" autocomplete="off" placeholder="Your Street Address & Apt #" />
				<input class="form-field gotham-bold" data-field-name="property_manager" type="text" autocomplete="off" placeholder="Property Manager Name" />
			</div>
		</div>

		<div id="guarantee" class="f14 light-gray gotham-bold">Guaranteed On-Time</div>

		<button id="setup_rental" class="next-step gotham-bold btn" data-next-step="part-two">Set Up Rental Payment</button>

		<div class="footer">
			<img class="pull-left" height="50px" width="50px" src="/img/founders/indiana.png" alt="Alex" />
			<div class="text pull-left f14 gotham-bold blue">Questions</div>
			<div class="text small-text pull-left f12 gotham-bold">Alex is your personal rent payment rep.</div>

			<span id="secure" class="pull-right f14 light-gray gotham-bold">Secure <i class="icon-lock blue icon-large"></i></span>

		</div>

	</div>

	<div class="part-two hide rentpay-step">
		<a href="#" class="back f13 light-gray" data-back="part-one">Back</a>
		<div class="f15 dark-gray gotham-bold m10">Set up rent pay with:</div>
		<div class="pay-options">
			<div class="pay-option active show-card">
				<img src="/img/rentpay/card.png" alt="Card Logo">
				<div class="gotham-bold white f14 m10">Card</div>
			</div>
			<div class="pay-option">
				<img src="/img/rentpay/venmo.png" alt="Venmo Logo">
				<div class="gotham-bold white f14 m10">Venmo</div>
			</div>
			<div class="pay-option inactive">
				<div class="coming-soon">COMING SOON</div>
				<img src="/img/rentpay/bank.png" alt="Bank Logo">
				<div class="gotham-bold white f14 m10">Bank</div>
			</div>
		</div>
		<div class="light-gray gotham-bold m10">
			<i class="icon-lock"></i> SSL Encrypted - Secured by BrainTree
		</div>
		<div class="background blue card-info">
			<div class="white-cover hide"></div>
			<div class="white f14 gotham-bold m10">Enter debit card information:</div>
			<input class="gotham-bold form-field" data-field-name="number" type="text" name="card_number" placeholder="Card Number">
			<input class="gotham-bold pull-left input25 form-field" data-field-name="month" type="text" name="month" placeholder="MM">
			<input class="gotham-bold pull-left input25 form-field" data-field-name="year" type="text" name="year" placeholder="YY">
			<input class="gotham-bold pull-right input25 form-field" data-field-name="cvv" type="text" name="cvc" placeholder="CVC">
		</div>
		<div class="f12 gotham-bold light-gray m10">Next rent payment will be made on <span class="blue">March 27th 4pm</span>. A confirmation email will be sent the day after.</div>
		<button class="next-step gotham-bold btn" data-next-step="part-three">Schedule Rent &amp; Invite Housemates</button>
	</div>

	<div class="part-three hide rentpay-step">
		<a href="#" class="back f13 light-gray" data-back="part-two">Back</a>
		<img src="" alt="Thumbs Up!">
		<div class="f20 dark-gray gotham-bold">Awesome, No More Checks!</div>
		<div class="f13 dark-gray gotham-bold">Payment Scheduled for: March 27th</div>
		<div class="background orange email-info">
			<div class="f13 white gotham-bold">Now invite Housemates to Pay Rent:</div>
			<input class="gotham-bold input75" type="text" placeholder="Housemate's Email">
			<input class="gotham-bold input25" type="text" placeholder="Rent">

			<input class="gotham-bold input75" type="text" placeholder="Housemate's Email">
			<input class="gotham-bold input25" type="text" placeholder="Rent">
			<input class="gotham-bold input75" type="text" placeholder="Housemate's Email">
			<input class="gotham-bold input25" type="text" placeholder="Rent">
		</div>
		<a href=""></a>
		<a href=""></a>
		<button class='next-step btn gotham-bold finish-rentpay'>Finish</button>
	</div>
</div>

<form id="braintree-payment-form" class="hide">
	<input type="text" size="20" autocomplete="off" name="full_name" />
	<input type="text" size="20" autocomplete="off" name="email" />
	<input type="text" size="20" autocomplete="off" name="address" />
	<input type="text" size="20" autocomplete="off" name="property_manager" />
	<input type="text" size="20" autocomplete="off" name="venmo" />
	<input type="text" size="20" autocomplete="off" name="amount"/>
	<input type="text" size="20" autocomplete="off" data-encrypted-name="number" name="number" />
	<input type="text" size="4" autocomplete="off" data-encrypted-name="cvv" name="cvv" />
	<input type="text" size="2" data-encrypted-name="month" name="month" />
	<input type="text" size="4" data-encrypted-name="year" name="year" />
</form>

