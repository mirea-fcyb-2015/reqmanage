<?php echo $header; ?>

<div id="page-container">
	<?php echo $sidebar; ?>
	<div id="page-content">
		<div id="wrap">
			<div id="page-heading">
			    <ol class="breadcrumb">
			    	<?php echo breadcrumb($breadcrumb); ?>
			    </ol>
				<h1><?=$title; ?></h1>
			</div>
			<div class="container">
				<?php echo $main_content; ?>
			</div>
		</div>
	</div>
</div>

<?php echo $footer; ?>