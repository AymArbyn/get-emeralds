<nav class="navbar navbar-inverse navbar-fixed-top navbar-default">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">
                <img alt="Brand" src="/img/logo.png" height="26px">
            </a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <!-- <span class="title"><?= $title ?></span> -->
            <ul class="nav navbar-nav navbar-right">
                <li>
                    <a href="/"><span class="navbar-label">System Overview</span> <i class="fui-home"></i></a>
                </li>
                <!-- <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="navbar-label">Settings <span class="fui-triangle-down"></span></span><i class="fui-gear"></i></a>

                    <ul class="dropdown-menu">
                        <li><a href="#">Account Settings</a></li>
                        <li><a href="#">System Settings</a></li>
                        <?php if (isset($_SESSION["type"]) && $_SESSION['type'] == 1): ?>
							<li role="separator" class="divider"></li>
							<li><a href="/register/">Register New User</a></li>
						<?php endif ?>
                    </ul>
                </li> -->
                <li>
                    <a href="/register/"><span class="navbar-label">Register New User</span> <i class="fui-plus-circle"></i></a>
                </li>
                <li>
                    <a href="/logout/"><span class="navbar-label">Logout</span> <i class="fui-exit"></i></a>
                </li>
                <li class="active">
                    <a href="/"><span class="username-label">Hello, <?= $_SESSION["username"] ?></span></i></a>
                </li>
            </ul>
        </div>
    </div>
</nav>