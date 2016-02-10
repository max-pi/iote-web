<html>
	<body>
		<p>Hello {{ $params['name'] }},</p>
		<p>
			You have recently made an attempt to verify this email address on IOTE.<br>
			For security reasons, please enter the following verification code into the prompt on the mobile application.
		</p>
		<p>
			Verification code:
			<strong>{{ $params['code'] }}</strong>
		</p>
		<p>
			Let us know immediately if you believe someone is trying to claim ownership of this email address.
		</p>
		<p>
			Happy tracking!<br>
			Team IOTE
		</p>
	</body>
</html>