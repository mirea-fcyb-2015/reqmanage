<header class="navbar navbar-inverse navbar-fixed-top" role="banner">
    <div class="navbar-header pull-left">
        <a class="navbar-brand" href="<?=site_url() ?>">Система управления требованиями</a>
    </div>

    <ul class="nav navbar-nav pull-right toolbar">
    	<li class="dropdown">
    		<a href="#" class="dropdown-toggle username" data-toggle="dropdown"><span class="hidden-xs">Профиль <i class="fa fa-caret-down"></i></span></a>
    		<ul class="dropdown-menu userinfo arrow">
    			<li class="username">
                    <a href="#">
    				    <div class="pull-right"><h5>Йоу, <?=$user->first_name ?>!</h5><small>Вы вошли как <span><?=$user->username ?></span></small></div>
                    </a>
    			</li>
    			<li class="userlinks">
    				<ul class="dropdown-menu">
    					<li><a href="#">Профиль <i class="pull-right fa fa-pencil"></i></a></li>
    					<li><a href="#">Настройки <i class="pull-right fa fa-cog"></i></a></li>
    					<li class="divider"></li>
    					<li><a href="<?=site_url('user/logout') ?>" class="text-right">Выход</a></li>
    				</ul>
    			</li>
    		</ul>
    	</li>
	</ul>
</header>