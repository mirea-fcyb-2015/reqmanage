<div class="panel panel-primary">
    <div class="panel-heading"><h4>Настройки отчета</h4>
        <div class="options">
            <ul class="nav nav-tabs">
                <li>
                    <a href="<?=site_url('project/'. $project->id) ?>">К проекту</a>
                </li>
                <li class="active">
                    <a href="<?=site_url('project/report/'. $project->id) ?>">Настройка отчета</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="panel-body">
        <?php echo form_open(null, 'class="form-horizontal"'); ?>
        <div class="form-group">
            <label for="checkbox" class="col-sm-3 control-label">Вложить матрицу</label>
            <div class="col-sm-6">
                <div class="checkbox block">
                    <label><input checked="" type="checkbox" name="with_matrix">Да</label>
                </div>
            </div>
        </div>
    </div>
    <div class="panel-footer">
        <div class="row">
            <div class="col-sm-6 col-sm-offset-3">
                <div class="btn-toolbar">
                    <?php echo form_submit('submit', 'Показать', 'class="btn btn-primary"'); ?>
                </div>
            </div>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>