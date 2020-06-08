<!DOCTYPE html>
<html>

<head>
	<title>Register</title>
</head>

<body>
	<h2>Register</h2>
	<form action="save_user.php" method="POST">
		<p>
			<label>Your login</label>
			<input type="text" name="login" size="15" maxlength="15">
		</p>
		<p>
			<label>Your password</label>
			<input type="password" name="password" size="15" maxlength="15">
		</p>
		<p>
			<input type="submit" name="submit" value="Register">
		</p>
	</form>
</body>

</html>