<html>
	<body>
		<p>Hello <?= $params['name'] ?>,</p>
		<p>
			You have recently made an attempt to verify this email address on IOTE.<br>
			For security reasons, please enter the following verification code into the prompt on the mobile application.
		</p>
		<p>
			Verification code:
			<strong><?= $params['code'] ?></strong>
		</p>
		<p>
			Be sure to let us know if you believe that someone is trying to claim ownership of this email address on IOTE.
		</p>
		<p>
			Happy tracking!<br>
			Team IOTE
		</p>
	</body>
</html>