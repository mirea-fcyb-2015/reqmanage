<nav id="page-leftbar" role="navigation">
    <ul class="acc-menu" id="sidebar">
        <li>
        	<a href="<?=site_url('project') ?>">
				<i class="fa fa-th"></i>Все проекты
			</a>
		</li>
        <li>
        	<a href="<?=site_url('users') ?>">
				<i class="fa fa-group"></i>Пользователи
			</a>
		</li>
        <li>
        	<a href="<?=site_url('changes') ?>">
				<i class="fa fa-pencil"></i>Последние изменения
			</a>
		</li>
        <li>
            <a href="<?=site_url('dump') ?>">
                <i class="fa fa-briefcase"></i>Импорт проекта
            </a>
        </li>
        <!--  -->
        <? if($menu) { ?>
            <li class="divider"></li>
    		<? make_menu($menu) ?>
            <div id="treeview1"> </div>
        <? } ?>
	</ul>
</nav> 