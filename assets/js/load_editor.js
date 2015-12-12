$(document).ready(function() {
    // text editor
    $('#description').trumbowyg({
        btnsDef: {
            image: {
                dropdown: ['insertImage', 'upload'],
                ico: 'insertImage'
            }
        },
        btns: ['viewHTML',
                '|', 'formatting',
                '|', 'btnGrp-design',
                '|', 'link',
                '|', 'image',
                '|', 'btnGrp-justify',
                '|', 'btnGrp-lists',
                '|', 'horizontalRule',
                '|', 'removeformat']
    });
});