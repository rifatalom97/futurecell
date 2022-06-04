// Search box
function show_search_box(){
    $('#search_box').fadeIn();
}
function hide_search_box(){
    $('#search_box').fadeOut();
}
// end 

// Close sussess alert box
$('button.close').on('click',function(){
    $('.alert').fadeOut();
});

$(document).ready(function(){
    // Home banner
    $('.home_banner').owlCarousel({
        items:1,
        loop:true,
        nav:false,
        dots:false,
    });
    $('.product_carousel_items').owlCarousel({
        items   : 4,
        loop    : true,
        nav     : true,
        dots    : false,
    });
    $('.product_gallery .thumbs').owlCarousel({
        items   : 5,
        loop    : true,
        nav     : true,
        dots    : false,
        responsive : {
            0 : {
                items   : 2,
            },
            400 : {
                items   : 3,
            },
            560 : {
                items   : 4,
            }
        }
    });
});
function show_bing_one(e){
    let src = e.src;
    let elem = $('.big_image img');
    elem.fadeOut(200,function(){
        $(this).attr('src',src);
        $(this).fadeIn(200);
    });
}





// Nav menu
function openNavMenu(){
    $('#navMenu').toggleClass('show');
}
$(document).click(function(e){
    if(!$(e.target).parents('.navbar-toggler').length&&!$(e.target).parents('.navbar-collapse').length){
        $('#navMenu').removeClass('show');
    }
});
// end





$('#add_to_cart').on('submit',function(e){
    e.preventDefault();
    var _this = $(this);
    $.ajax({
        url: _this.attr('action'),
        method: 'POST',
        data: _this.serializeArray(),
        dataType: 'JSON',
        success: (r)=>{
            if(r.status==200){
                let quantity = r.carts.length;
                $('.cart_counter').show().html(quantity);
                $('#minicart').addClass('has_items');
                $('.minicart_header span').html(quantity);
                let total = 0;
                let html = '';
                for(var i=0;i<quantity;i++){
                    var cart = r.carts[0];
                    console.log(cart);

                    html += '<tr id="minicart_product_{{ cart.product_id }}">';
                        html += '<td class="mn_thumbnail">';
                            html += '<img src="http://127.0.0.1:8000/uploads/'+cart.thumbnail+'" alt="'+cart.title+'">';
                        html += '</td>';
                        html += '<td class="mn_title">';
                            html += '<b>'+cart.title+'</b>';
                        html += '</td>';
                        html += '<td class="mn_price">';
                            html += '<span>₪</span> '+cart.price+'</td>';
                        html += '<td class="mn_remove">';
                            html += '<img src="http://127.0.0.1:8000/assets/frontend/img/cross_icon.svg" height="16" width="16" class="pointer" data-id="'+cart.product_id+'" data-action="http://127.0.0.1:8000/remove-cart-item">';
                        html += '</td>';
                    html += '</tr>';

                    total = total+parseInt(cart.price);
                }
                $('#minicart').find('table tbody').html(html);
                $('#minicart').find('.minicart_footer_top span').html(total);
                
                // handle buttons
                _this.hide();
                $('.cart_page_button').show();
            }
        },
        error: (r)=>{
            console.log(r);
        }
    })
});
// update cart quantity
function update_cart_quantity(e,val, pd_id, action){
    var elem = $(e);
    var current_val     = parseInt($('.handle_epd_cart_count span').html());
    var new_quantity    = current_val + parseInt(val);
    new_quantity        = new_quantity>0?new_quantity:1;

    $('.handle_epd_cart_count span').html(new_quantity);

    $.ajax({
        url: action,
        method: 'POST',
        data: {_token:$('meta[name="csrf-token"]').attr('content'),product_id:pd_id,quantity:new_quantity},
        dataType: 'JSON',
        beforeSend:function(){
            elem.parents('tr').css('opacity','.5');
        },
        success: (r)=>{
            if(r.total_item_amount){
                $('.mn_total span').html(r.total_item_amount)
            }
            if(r.total_amount){
                $('.cart_total_box span').html(r.total_amount)
            }
            elem.parents('tr').css('opacity','1');
        },
        error: (r)=>{
            console.log(r);
        }
    })
}

// Mini cart
function show_minicart(){
    $('#minicart.has_items').fadeToggle();
}
$(document).click(function(e){
    if(!$(e.target).parents('.minicart_area').length&&!$(e.target).parents('.show_minicart').length){
        $('#minicart').fadeOut();
    }
});
function hide_minicart(){
    $('#minicart').fadeOut();
}
$(document).on('click','.mn_remove img',function(){
    // this will work for minicart and the cart page both 
    // cart page defined by parent class
    // if cart page then it will refresh the page
    // else it will remove the item
    let product_id  = $(this).data('id');
    let action      = $(this).data('action');
    let row         = $(this).parents('tr');
    $.ajax({
        url         : action,
        method      : 'post',
        data        : {_token:$('meta[name="csrf-token"]').attr('content'),product_id:product_id},
        dataType    : 'json',
        beforeSend  : ()=>{
            row.css('opacity','.5');
        },
        success     : (r)=>{
            if(r.status==200){
                if(row.parents('.cart_page_content').length){
                    location.reload();
                }else{
                    if($('.single_product').length && $('input[name="product_id"]').val()==product_id){
                        $('.cart_page_button').hide();
                        $('#add_to_cart').show();
                    }else if($('.single_product').length){
                        location.reload();
                    }
                }
                row.remove();
                if(!row.siblings().length){
                    $('#minicart').removeClass('has_items').fadeOut();
                    $('.cart_counter').hide();
                }
            }else{
                row.css('opacity','1');
                alert('OOPs! Somthing went wrong');
            }
        },
        error       : (r)=>{
            console.log(r);
            row.css('opacity','1');
        }
    })
});
// End minicart and cart


// Load more products
function load_more_products(e){
    let action = e.dataset['action'];
    let offset = e.dataset['offset'];

    $.ajax({
        url: action,
        method:'get',
        dataType:'JSON',
        data:{offset:offset},
        beforeSend:()=>{
            $('.products').addClass('loading');
        },
        success:(r)=>{
            if(r.data){
                $('#products').append(r.data);
                $('.load_more_products button').attr('data-offset',r.offset);
            }
            if(r.offset >= r.total_count){
                $('.load_more_products').remove();
            }
            $('.products').removeClass('loading');
        }
    })
}

// Product sort by
$('.dropdown.sort_by button').on('click',function(){
    $(this).parent().find('.dropdown-menu').toggleClass('show');
});
$(document).on('click',function(e){
    if(!$(e.target).hasClass('dropdown-toggle')){
        $('.dropdown.sort_by .dropdown-menu').removeClass('show');
    }
});
// Product filter
let active_flits = $('.filter_content').find('.flit.active');
window.product_filter_flits = {
    'colors'    : [],
    'brands'    : [],
    'models'    : [],
    'categories': [],
    'sizes'     : [],
};
active_flits.each((i,e)=>{
    update_filter_flit_basket(e.dataset['type'], e.dataset['value'],true);
});
$('.filter_box_header button').click((e)=>{
    let _this = $(e.target);
    let target = _this.data('target');
    _this.addClass('active').siblings().removeClass('active');
    $('.filter_content').removeClass('active');
    $('#'+target+'.filter_content').addClass('active');
});
$('.filter_box_content span.flit').on('click',(e)=>{
    $(e.target).toggleClass('active');
    update_filter_flit_basket(e.target.dataset['type'],e.target.dataset['value']);
    filter_active_flit_quantity();
});
// for first loading calculation
filter_active_flit_quantity();
function filter_active_flit_quantity(){
    let quantity = $('.filter_content').find('.flit.active').length;
    $('#flit_quantity').html( parseInt(quantity) );
}
function filter_inactive_flit(){
    $('.filter_content').find('.flit.active').removeClass('active');
    filter_active_flit_quantity();

    let href    = window.location.href;
    let url     = new URL(href);
    window.location = url.origin + url.pathname;
}
function update_filter_flit_basket(type,value,first_type=false){
    let group = window.product_filter_flits[type];

    let find = group.indexOf(value);
    if(find!=-1){
        group = group.filter((item)=>{
            if(item!=value){
                return item;
            }
        });
    }else{
        group.push(value);
    }
    window.product_filter_flits = {
        ...window.product_filter_flits,
        [type] : group
    }

    if(first_type==false){
        let href    = window.location.href;
        let url     = new URL(href);
        let params  = url.searchParams;
        let flits = window.product_filter_flits;
        if( flits.colors.length ){
            params.set('color',flits.colors.join(' '));
        }else{
            params.delete('color');
        }
        if( flits.sizes.length ){
            params.set('size',flits.sizes.join(' '));
        }else{
            params.delete('size');
        }
        if( flits.brands.length ){
            params.set('brand',flits.brands.join(' '));
        }else{
            params.delete('brand');
        }
        if( flits.models.length ){
            params.set('model',flits.models.join(' '));
        }else{
            params.delete('model');
        }
        if( flits.categories.length ){
            params.set('category',flits.categories.join(' '));
        }else{
            params.delete('category');
        }
        let param_string = params.toString();
        let new_url = url.origin + url.pathname + (param_string.length?'?'+param_string:'');
        
        // add class loading on 
        $('.products').addClass('loading');

        window.location = new_url;
    }
}
function generate_params(params,){
    return data?data.split(' '):[];
}
// End product filter