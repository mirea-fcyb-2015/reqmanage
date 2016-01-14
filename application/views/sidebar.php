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
        <!--  -->
        <? if($menu) { ?>
            <li class="divider"></li>
    		<? menu($menu) ?>
            <div id="treeview1"> </div>
        <? } ?>
	</ul>
</nav> 
<script type="text/javascript">

    //     $(function() {

    //     var defaultData = [
    //       {
    //         text: 'Parent 1',
    //         href: '#parent1',
    //         nodes: [
    //           {
    //             text: 'Child 1',
    //             href: '#child1',
    //             nodes: [
    //               {
    //                 text: 'Grandchild 1',
    //                 href: '#grandchild1'
    //               },
    //               {
    //                 text: 'Grandchild 2',
    //                 href: '#grandchild2'
    //               }
    //             ]
    //           },
    //           {
    //             text: 'Child 2',
    //             href: '#child2',
    //           }
    //         ]
    //       },
    //       {
    //         text: 'Parent 2',
    //         href: '#parent2'
    //       },
    //       {
    //         text: 'Parent 3',
    //         href: 'http://req.mg/section/3'
    //       },
    //       {
    //         text: 'Parent 4',
    //         href: '#parent4'
    //       },
    //       {
    //         text: 'Parent 5',
    //         href: '#parent5'
    //       }
    //     ];

    //     $('#treeview1').treeview({
    //       expandIcon: 'fa fa-plus',
    //       collapseIcon: 'fa fa-minus',
    //       color: "#3f444c",
    //       backColor: "transparent",
    //       enableLinks: true,
    //       data: defaultData
    //     });
    // });
</script>