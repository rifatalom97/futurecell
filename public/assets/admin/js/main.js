$('ul li.has_child').on('click',function(){
    $(this).siblings().removeClass('sub_menu_open');
    
    $(this).toggleClass('sub_menu_open');
});


// slug generator and adder
function generate_slug(e){
    let value = e.value;

    let slug = value
                .toLowerCase()
                .trim()
                // .replace(/[^\w\s-]/g, '')
                .replace(/[\s_-]+/g, '-')
                .replace(/^-+|-+$/g, '');
    $('#slug_field').val(slug);
    $('#slug_viewer').html(slug);
}
function slug_filter(e){
    let value = e.value;

    let slug = value
                .toLowerCase()
                .trim()
                // .replace(/[^\w\s-]/g, '')
                .replace(/[\s_-]+/g, '-');
    e.value = slug;
    $('#slug_viewer').html(slug);
}

// Alert box
$('.alert button.close').on('click',(e)=>{
    let n = $(e.target).parents('.alert');
    n.fadeOut();
    setTimeout(()=>{
        n.remove();
    },500);
});


// ajax uploader
$(document).on('change','.ajax_uploader input[type="file"]',function(e){
    let _t      = $(this);
    let action  = _t.parent().data('action');
    let parent  = _t.parent();
    if(_t.val()){
        let formData = new FormData();
        formData.append('uploading_file',e.target.files[0]);
        parent.addClass('uploading');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')  // must need for file upload
            },
            url: action,
            method:'POST', // must need for file upload
            data: formData,
            dataType: 'JSON',
            contentType:false, // must need for file upload
            processData:false, // must need for file upload
            cache:false, // must need for file upload
            mimeType:'multipart/form-data', // must need for file upload
            success:function(r){
                if(r.result){
                    parent.find('.file').val(r.filename);
                    parent.find('img').attr('src',r.fileurl);
                    parent.find('img').show();
                }
                _t.val(null);
                parent.removeClass('uploading');
            },
            error:function(r){
                _t.val(null);
                parent.removeClass('uploading');
            }
        });
    }
});

// Tinymce
tinymce.init({
    selector: 'textarea#tinymce_handler',
    height: 500,
    menubar: false,
    plugins: [
      'advlist autolink lists link image charmap print preview anchor',
      'searchreplace visualblocks code fullscreen',
      'insertdatetime media table paste code help wordcount'
    ],
    toolbar: 'undo redo | formatselect | ' +
    'bold italic backcolor | alignleft aligncenter ' +
    'alignright alignjustify | bullist numlist outdent indent | ' +
    'removeformat | help',
    content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
});