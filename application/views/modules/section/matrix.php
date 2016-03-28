<div class="panel panel-primary">
    <div class="panel-heading"><h4>Требования</h4>
        <div class="options">
            <ul class="nav nav-tabs">
                <li>
                    <a href="<?=site_url('section/'. $section->id) ?>">Список требований</a>
                </li>
                <li class="active">
                    <a href="<?=site_url('section/matrix/'. $section->id) ?>">Матрица</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="panel-body">
        <div id="matrix">
            
        </div>
        <script>
        var data = '<?=$matrix ?>';
        $.jsontotable(data, { id: '#matrix', header: false });
        </script>
    </div>
</div>
<script type="text/javascript">
jQuery(document).ready(function() {  
        $.fn.editable.defaults.mode = 'popup';
        $('.xedit').editable();     
        $(document).on('click','.editable-submit',function(){
            var key = $(this).closest('.editable-container').prev().attr('key');
            var x = $(this).closest('.editable-container').prev().attr('id');
            var y = $('.input-sm').val();
            var z = $(this).closest('.editable-container').prev().text(y);

            $.ajax({
                url: "<?=site_url('section/matrix/'. $section->id) ?>",
                data: { id: x, data: y, key: key},
                type: 'POST',
                success: function(s){
                    if(s == 'status'){
                    $(z).html(y);}
                    if(s == 'error') {
                    alert('Error Processing your Request!');}
                },
                error: function(e){
                    alert('Error Processing your Request!!');
                }
            });
        });
});
</script>