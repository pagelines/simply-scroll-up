jQuery(document).ready(function($){
	$.scrollUp({
        scrollName: 'button-back-to-top',
        scrollText: ('image' === pagelines_scroll_up.style) ? '' : pagelines_scroll_up.text,
        scrollImg: ('image' === pagelines_scroll_up.style) ? true : false,
        zIndex: parseInt(pagelines_scroll_up.zIndex)
    });

	jQuery('#back-to-top #back-to-top-style').live('change',function(){ 
		back_to_top_show(jQuery(this).val());
	});
	setTimeout(function() {
		back_to_top_show(jQuery('#back-to-top-style').val());	
	}, 150);
	
	
	jQuery('.el-settings').on('click',function(){
		back_to_top_show(jQuery('#back-to-top-style').val());
	});
});

function back_to_top_show(val){
	if(val == 'link' || val == 'pill'){
		jQuery('#back-to-top-text').parent('.input-wrap').css('display','block');
		jQuery('#page_background_image_url_back_top').parent('.uploader-input').parent('.image-uploader').parent('.input-wrap').css('display','none');
		jQuery('#icon_back_top').parent('.input-wrap').css('display','none');
	}
	if(val == 'icon'){
		jQuery('#back-to-top-text').parent('.input-wrap').css('display','none');
		jQuery('#page_background_image_url_back_top').parent('.uploader-input').parent('.image-uploader').parent('.input-wrap').css('display','none');
		jQuery('#icon_back_top').parent('.input-wrap').css('display','block');
	}
	if(val == 'image'){
		jQuery('#back-to-top-text').parent('.input-wrap').css('display','none');
		jQuery('#page_background_image_url_back_top').parent('.uploader-input').parent('.image-uploader').parent('.input-wrap').css('display','block');
		jQuery('#icon_back_top').parent('.input-wrap').css('display','none');
		jQuery('#back-to-top-text-color').parent('.colorContainer').parent('.input-prepend').parent('.input-wrap').css('display','none');
	}
	else {
		jQuery('#back-to-top-text-color').parent('.colorContainer').parent('.input-prepend').parent('.input-wrap').css('display','block');
	}
	if(val == 'pill'){
		jQuery('#back-to-top-radius').parent('.input-wrap').css('display','block');
	}else{
		jQuery('#back-to-top-radius').parent('.input-wrap').css('display','none');
	}
	if(val != 'link' && val != 'image' && val != 'icon' ){
		jQuery('#back-to-top-background-color').parent('.colorContainer').parent('.input-prepend').parent('.input-wrap').css('display','block');
		jQuery('#back-to-top-background-hover-color').parent('.colorContainer').parent('.input-prepend').parent('.input-wrap').css('display','block');
	}else{
		jQuery('#back-to-top-background-color').parent('.colorContainer').parent('.input-prepend').parent('.input-wrap').css('display','none');
		jQuery('#back-to-top-background-hover-color').parent('.colorContainer').parent('.input-prepend').parent('.input-wrap').css('display','none');
	}


}