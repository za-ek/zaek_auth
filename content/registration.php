<div class="row">
	<div class="col-lg-8 mr-auto">
		<h1>Регистрация</h1>
		<?php 
		if ( isset($_POST['email']) && isset($_POST['login']) && isset($_POST['password']) ) {
			$error = false;
			
			if ( !isset($_POST['agree']) || $_POST['agree'] != 'on' ) {
				echo "<p class='error'>Вы должны согласиться на всё!</p>";
			}
			
			if ( !$_POST['email'] || strpos($_POST['email'], '@') == false ) {
				echo "<p class='error'>Неправильно указан email</p>";
				$error = true;
			}
			
			if ( !$_POST['login'] ) {
				echo "<p class='error'>Заполните поле логин!</p>";
				$error = true;
			} 

			$_POST['login'] = preg_replace('/([^\w\d_\-\.]*)/', '', $_POST['login']);
			
			if ( strlen($_POST['login']) < 4 ) {
				echo "<p class='error'>Логин должен быть длиннее четырёх символов</p>";
				$error = true;
			} else if ( strlen($_POST['login']) > 32 ) { 
				echo "<p class='error'>Ограничьтесь 32 символами для логина</p>";
				$error = true;
			}
			
			if ( !$_POST['password'] || strlen($_POST['password']) < 6 
					|| !preg_match('#([\d]+)#', $_POST['password'])
					|| !preg_match('#([\w]+)#', $_POST['password']) ) {
				echo "<p class='error'>Пароль должен быть длиной от шести символов, иметь как минимум одну букву и одну цифру</p>";
				$error = true;
			}
			
			if ( !$error ) {
				$salt = substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(($length = rand(8,12))/strlen($x)) )),1,$length);
				$hash = $this->user()->access()->hash($_POST['login'], $_POST['password'], $salt);
				
				$link = $this->data()->getLink();
				mysqli_query($link, "USE DATABASE sgotovil");
				$stmt = mysqli_prepare($link, "INSERT INTO users (email, login, hash, salt) VALUES (?,?,?,?)");
				if ( $stmt ) {
					$stmt->bind_param('ssss', $_POST['email'], $_POST['login'], $hash, $salt);
					$stmt->execute();
					$user_id = $stmt->insert_id;
					
					$this->template()->end();
					$this->user()->access()->authenticate($_POST['login'], $_POST['password']);
					header('location: /');
					die();
				} else {
					echo "<p class='error'>Извините, возникли технические неполадки, попробуйте зарегистрироваться позже.</p>";
				}
			}
		}
		?>
		<form action="/registration.php" method="post">
			<div class="form-group">
				<label for="email_input">Email</label>
				<input type="email" class="form-control" name="email" id="email_input" value="<?=(isset($_POST['email']))?$_POST['email']:''?>" aria-describedby="email_help">
			</div>
			<div class="form-group">
				<label for="password_input">Пароль</label>
				<input type="password" class="form-control" name="password" id="password_input" placeholder="">
			</div>
			<div class="form-group">
				<label for="login_input">Логин</label>
				<input type="text" class="form-control" name="login" value="<?=(isset($_POST['login']))?$_POST['login']:''?>" id="login_input">
				<small id="login_help" class="form-text text-muted">Разрешены буквы, цифры, тире, нижнее подчёркивание и точка</small>
			</div>
			<div class="form-check">
				<label class="form-check-label">
					<input type="checkbox" name="agree" class="form-check-input">
					Я согласен, что все предоставляемые мною данные могут быть использованы как угодно 
				</label>
			</div>
			<button type="submit" class="btn btn-primary">Зарегистрироваться</button>
		</form>
	</div>
</div>
