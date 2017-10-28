<div class="row">
	<div class="col-lg-8 mr-auto">
		<h1>Вход</h1>
		<?php 
		if ( isset($_POST['email']) && isset($_POST['password']) ) {
			
			$error = false;
			
			if ( !$_POST['password'] || strlen($_POST['password']) < 6 
					|| !preg_match('#([\d]+)#', $_POST['password'])
					|| !preg_match('#([\w]+)#', $_POST['password']) ) {
				echo "<p class='error'>Пароль должен быть длиной от шести символов, иметь как минимум одну букву и одну цифру</p>";
				$error = true;
			}
			
			if ( !$error ) {
					$this->template()->end();
					if ( $this->user()->access()->authenticateByEmail($_POST['email'], $_POST['password']) ) {
						header('location: /');
					} else {
						header('location: /login.php?error=1');
					}
					die();
			} else {
				echo "<p class='error'>Неверные email или пароль</p>";
			}
		}
		
		if ( isset($_GET['error']) && $_GET['error'] === "1" ) {
			echo "<p class='error'>Неверные email или пароль</p>";
		}
		?>
		<form action="/login.php" method="post">
			<div class="form-group">
				<label for="email_input">Email</label>
				<input type="email" class="form-control" name="email" id="email_input" value="<?=(isset($_POST['email']))?$_POST['email']:''?>" aria-describedby="email_help">
			</div>
			<div class="form-group">
				<label for="password_input">Пароль</label>
				<input type="password" class="form-control" name="password" id="password_input" placeholder="">
				<i class="info text-muted">Пароль должен быть длиной от шести символов, иметь как минимум одну букву и одну цифру</i>
			</div>
			<button type="submit" class="btn btn-primary">Войти</button>
		</form>
	</div>
</div>
