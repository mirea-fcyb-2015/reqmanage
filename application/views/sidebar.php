<nav id="page-leftbar" role="navigation">
    <ul class="acc-menu" id="sidebar">
        <li id="search">
            <a href="javascript:;"><i class="fa fa-search opacity-control"></i></a>
            <form>
                <input type="text" class="search-query" placeholder="Поиск...">
                <button type="submit"><i class="fa fa-search"></i></button>
            </form>
        </li>
        <li>
        	<a href="<?=site_url() ?>">
				<i class="fa fa-home"></i>Главная
			</a>
		</li>
        <li>
        	<a href="<?=site_url('users') ?>">
				<i class="fa fa-user"></i>Пользователи
			</a>
		</li>
        <li>
        	<a href="<?=site_url('project') ?>">
				<i class="fa fa-th"></i>Все проекты
			</a>
		</li>
        <li>
        	<a href="<?=site_url('changes') ?>">
				<i class="fa fa-pencil"></i>Последние изменения
			</a>
		</li>
        <? if($menu) { ?>
            <li class="divider"></li>
    		<? menu($menu, $type, $recursion) ?>
        <? } ?>
	</ul>
</nav> 