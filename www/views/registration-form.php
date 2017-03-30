<div class="container-fluid registration-container">
    <header>
        <h5><?= $title ?></h5>
    </header>

    <div class="reg-form">
        <form method="POST" class="row">
    		<?php if (isset($err_msg)): ?>
    			<div class="input-helper">
    				<span class="helper-text"><?= htmlspecialchars($err_msg) ?></span>
    			</div>
    		<?php endif ?>
            <div class="reg-form-inputs col-md-4">
                <input type="text" class="username-input form-control" name="username" placeholder="Username">
                <input type="password" class="password-input form-control" name="password" placeholder="Password">
                <input type="submit" class="submit-btn" value="Submit">
            </div>
        </form>
    </div>
</div>