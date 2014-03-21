<?php
	echo $this->Html->script('src/Rentpay');
?>
<html>
	<head>
	</head>
	<body>
		<h1>Braintree Credit Card Transaction Form</h1>
		<div>
			<form id="braintree-payment-form">
				<p>
					<label>First Name</label>
					<input type="text" size="20" autocomplete="off" name="first_name" />
				</p>
				<p>
					<label>Last Name</label>
					<input type="text" size="20" autocomplete="off" name="last_name" />
				</p>
				<p>
					<label>Email</label>
					<input type="text" size="20" autocomplete="off" name="email" />
				</p>

				<p>
					<label>Amount</label>
					$<input type="text" size="20" autocomplete="off" data-encrypted-name="amount" />
				</p>
				<p>
					<label>Card Number</label>
					<input type="text" size="20" autocomplete="off" data-encrypted-name="number" />
				</p>
				<p>
					<label>CVV</label>
					<input type="text" size="4" autocomplete="off" data-encrypted-name="cvv" />
				</p>
				<p>
					<label>Expiration (MM/YYYY)</label>
					<input type="text" size="2" data-encrypted-name="month" /> / <input type="text" size="4" data-encrypted-name="year" />
				</p>
				<input type="submit" id="paymentSubmit" />
			</form>
		</div>

<script src="https://js.braintreegateway.com/v1/braintree.js"></script> <script>
A2Cribs.Rentpay.init()
</script>

	</body>
</html>
