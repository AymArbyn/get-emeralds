<div class="container-fluid login-container">
    <header>
        <h1 class="logo">
            <a href="/"><img src="/img/portrait_logo.png"></a>
        </h1>
    </header>

    <div class="login-form">
        <form method="POST">
			<?php if (isset($err_msg)): ?>
				<div class="input-helper">
					<span class="helper-text"><?= htmlspecialchars($err_msg) ?></span>
				</div>
			<?php endif ?>
            <div class="login-form-inputs">
                <input type="text" class="username-input" name="username" placeholder="Username" autocomplete="off">
                <input type="password" class="password-input" name="password" placeholder="Password">
                <input type="submit" class="login-btn" value="Log In">
            </div>
        </form>
    </div>
</div>