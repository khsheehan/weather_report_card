<div class='row'>
	<div class='two column'>
	</div>
	<div class='four column'>
		<form method='post' action='<?=site_url('signup');?>' class='sign_up'>

		<label>Display Name</label>
		<input type="text" required='required' name='name' />

		<label>Email Address</label>
		<input type="text" required='required' name='email' />

		<label>Zip Code (optional - U.S. only)</label>
		<input type="text" name='zip' />

	</div>
	<div class='four column'>
		<label>Password</label>
		<input type="password" required='required' name='pass' />

		<label>Confirm Password</label>
		<input type="password" required='required' name='pass_conf' />

		<label>Submit</label>
		<input type='submit' class='button' value='login' />

		</form>
	</div>
	<div class='two column'>
	</div>
</div>