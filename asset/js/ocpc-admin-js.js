jQuery(document).ready(function(){
   //Create Ajax for post Options
	jQuery('body').on('change', 'select.posttype_change', function() {
		var posttype = this.value;
		jQuery("select.ocpc-datasource").val("");
		if(posttype == 'attachment' || posttype == 'page' || posttype == 'product' || posttype == 'post'){
			jQuery("tr.ocps_options_tags").hide(); 
			jQuery("tr.ocps_options_ids").hide();
		  	jQuery("tr.ocps_options_cat").hide();  
		}
		jQuery.ajax({
            url: ajaxurl,
            type:'POST',
            data:"action=posttypedata&posttype="+posttype,
            success:function(results)
                	{
                		jQuery("tr.ocpc_add_sourcedata").html(results);
                    	return false;  
                    }
        });
	});



    //one page load selected datasource show
	var datasourceval = jQuery("select.ocpc-datasource").val();

	if(datasourceval == "ocpc-op_categories"){
        jQuery("tr.ocps_options_cat").show(); 
    }
    if(datasourceval == "ocpc-op_tags"){
        jQuery("tr.ocps_options_tags").show(); 
    }
    if(datasourceval == "ocpc-op_id"){
        jQuery("tr.ocps_options_ids").show(); 
    }

    if(jQuery("input[name='ocpc-posttitle']").is(':checked') == true) 
    {
	        jQuery("#post_title_option").show();
	}
	if(jQuery("input[name='ocpc-postdate']").is(':checked') == true) 
    {
	        jQuery("#post_date_option").show();
	}
    if(jQuery("input[name='ocpc-postauthor']").is(':checked') == true) 
    {
            jQuery("#post_author_option").show();
    }
	if(jQuery("input[name='ocpc-postfeaturedimg']").is(':checked') == true) 
    {
	        jQuery("#post_featuredimg_option").show();
	}
	if(jQuery("input[name='ocpc-postdescription']").is(':checked') == true) 
    {
	        jQuery("#post_description_option").show();
	}



    //slider setting options by tabbing
    jQuery('ul.tabs li').click(function(){
        var tab_id = jQuery(this).attr('data-tab');
        jQuery('ul.tabs li').removeClass('current');
        jQuery('.tab-content').removeClass('current');
        jQuery(this).addClass('current');
        jQuery("#"+tab_id).addClass('current');
    })

    //Select post data source options
    jQuery('body').on('change', 'select.ocpc-datasource', function() {
	  if(this.value == 'ocpc-op_categories')
	  {
	  	jQuery("tr.ocps_options_cat").show(); 
	  	jQuery("tr.ocps_options_tags").hide();
	  	jQuery("tr.ocps_options_ids").hide();  
	  } 
	  else if(this.value == 'ocpc-op_tags')
	  {
	  	jQuery("tr.ocps_options_tags").show();
	  	jQuery("tr.ocps_options_cat").hide();
	  	jQuery("tr.ocps_options_ids").hide();   
	  }
	  else if(this.value == 'ocpc-op_id')
	  {
	  	jQuery("tr.ocps_options_ids").show();
	  	jQuery("tr.ocps_options_tags").hide();
	  	jQuery("tr.ocps_options_cat").hide();   
	  }
	  else
	  {
	  	jQuery("tr.ocps_options_ids").hide();
	  	jQuery("tr.ocps_options_tags").hide();
	  	jQuery("tr.ocps_options_cat").hide();  
	  }
	  var post_type = jQuery("select.posttype_change").val();
      var source_val = this.value;
      jQuery.ajax({
            url: ajaxurl,
            type:'POST',
            data:"action=cat_tag_data&post_type="+post_type+"&source_val="+source_val,
            success:function(results)
                	{
                		jQuery("tr#ocps_options_comman").html(results);
                    	return false;  
                    }
        });
	});

	jQuery("input[name='ocpc-posttitle']").change(function() {
	    if(this.checked) {
	        jQuery("#post_title_option").show();
	    }
	    else
	    {
	    	jQuery("#post_title_option").hide();	
	    }
	});
	jQuery("input[name='ocpc-postdate']").change(function() {
	    if(this.checked) {
	        jQuery("#post_date_option").show();
	    }
	    else
	    {
	    	jQuery("#post_date_option").hide();	
	    }
	});
    jQuery("input[name='ocpc-postauthor']").change(function() {
        if(this.checked) {
            jQuery("#post_author_option").show();
        }
        else
        {
            jQuery("#post_author_option").hide(); 
        }
    });
	jQuery("input[name='ocpc-postfeaturedimg']").change(function() {
	    if(this.checked) {
	        jQuery("#post_featuredimg_option").show();
	    }
	    else
	    {
	    	jQuery("#post_featuredimg_option").hide();	
	    }
	});
	jQuery("input[name='ocpc-postdescription']").change(function() {
	    if(this.checked) {
	        jQuery("#post_description_option").show();
	    }
	    else
	    {
	    	jQuery("#post_description_option").hide();	
	    }
	});










    jQuery("input[name='ocpc-showpagination']").change(function() {
        if(this.checked) {
            jQuery("#ocpc-perpage").show();
            jQuery("#ocpc-totalposts").hide(); 
        }else{
            jQuery("#ocpc-totalposts").show();  
            jQuery("#ocpc-perpage").hide();
        }
    });


    if(jQuery("input[name='ocpc-showpagination']").is(':checked') == true) {
        jQuery("#ocpc-perpage").show();
        jQuery("#ocpc-totalposts").hide(); 
    }else{
        jQuery("#ocpc-totalposts").show();  
        jQuery("#ocpc-perpage").hide();
    } 



    var posttype2 = jQuery("select.posttype_change").val();
    if(posttype2 == 'attachment') {
        jQuery("#ocpc-totalposts").show();  
        jQuery("#ocpc-perpage").hide();
        jQuery("#ocpc-showpagination").hide();
    }else{
        jQuery("#ocpc-perpage").show();
        jQuery("#ocpc-showpagination").show();
        jQuery("#ocpc-totalposts").hide(); 
    }


    jQuery('body').on('change', 'select.posttype_change', function() {
        var posttype = this.value;
        if(posttype == 'attachment') {
            jQuery("#ocpc-totalposts").show();  
            jQuery("#ocpc-perpage").hide();
            jQuery("#ocpc-showpagination").hide();
            jQuery("input[name='ocpc-showpagination']").prop("checked",false);
            
        }else{
            jQuery("#ocpc-perpage").show();
            jQuery("#ocpc-showpagination").show();
            jQuery("#ocpc-totalposts").hide(); 
            jQuery("input[name='ocpc-showpagination']").prop("checked",true);
        }
    })
})

//Copy shortcode
function ocpc_select_data(id) {
    var copyText = id;
    jQuery("#"+copyText).select();
    document.execCommand("copy");
}




jQuery(document).ready(function(){
    var val = jQuery('input[type=radio][name=ocpc_option]:checked').val();
    if (val == 'carousel') {
        jQuery(".carousel").show();
        jQuery(".gallery").hide();
        jQuery(".masonry").hide();
    }
    if (val == 'masonry') {
        jQuery(".carousel").hide();
        jQuery(".gallery").hide();
        jQuery(".masonry").show();
    }
    if (val == 'gallery') {
        jQuery(".gallery").show();
        jQuery(".carousel").hide();
        jQuery(".masonry").hide();
    }
    
  


    jQuery('body').on('change', '.ocpc_option', function () {
        if (this.value == 'gallery') {
            jQuery(".gallery").show();
            jQuery(".carousel").hide();
            jQuery(".masonry").hide();
        }else if (this.value == 'carousel') {
            jQuery(".carousel").show();
            jQuery(".gallery").hide();
            jQuery(".masonry").hide();
        }else if(this.value == 'masonry') {
            jQuery(".carousel").hide();
            jQuery(".gallery").hide();
            jQuery(".masonry").show();
        }        
    });


    jQuery(".sel_template").change(function(){
        var selectedtem= $(this).children("option:selected").val();
        if(selectedtem == "tem1"){
        	jQuery("input[name=ocpc-posttitlecolor]").val("#0d1d88");
        	jQuery("input[name=ocpc-posttitlefontsize]").val("18");
        	jQuery("select[name=ocpc-posttitleposition]").val("Center");
        	jQuery("input[name=ocpc-posttitlefweight]").val("Bold");

        	jQuery("input[name=ocpc-postdatecolor]").val("#a5a4ae");
        	jQuery("input[name=ocpc-postdatefontsize]").val("15");
        	jQuery("select[name=ocpc-postdateposition]").val("Center");
        	jQuery("input[name=ocpc-postdatefweight]").val("Bold");

        	jQuery("input[name=ocpc-postdescriptioncolor]").val("#363c70");
        	jQuery("input[name=ocpc-postdescriptionfontsize]").val("16");
        	jQuery("select[name=ocpc-postdescriptionposition]").val("Center");
        	jQuery("input[name=ocpc-postdescriptionfweight]").val("Bold");
        }

        if(selectedtem == "tem2"){
        	jQuery("input[name=ocpc-posttitlecolor]").val("#ffffff");
        	jQuery("input[name=ocpc-posttitlefontsize]").val("16");
        	jQuery("select[name=ocpc-posttitleposition]").val("Center");
        	jQuery("input[name=ocpc-posttitlefweight]").val("Bold");

        	jQuery("input[name=ocpc-postdatecolor]").val("#ffffff");
        	jQuery("input[name=ocpc-postdatefontsize]").val("15");
        	jQuery("select[name=ocpc-postdateposition]").val("Center");
        	jQuery("input[name=ocpc-postdatefweight]").val("Bold");

        	jQuery("input[name=ocpc-postdescriptioncolor]").val("#ffffff");
        	jQuery("input[name=ocpc-postdescriptionfontsize]").val("16");
        	jQuery("select[name=ocpc-postdescriptionposition]").val("Center");
        	jQuery("input[name=ocpc-postdescriptionfweight]").val("Bold");

        	jQuery("input[name=ocpc-readmoretextcolor]").val("#ffffff");
        }

        if(selectedtem == "tem3"){
        	jQuery("input[name=ocpc-posttitlecolor]").val("#000000");
        	jQuery("input[name=ocpc-posttitlefontsize]").val("16");
        	jQuery("select[name=ocpc-posttitleposition]").val("Left");
        	jQuery("input[name=ocpc-posttitlefweight]").val("Bold");

        	jQuery("input[name=ocpc-postdatecolor]").val("#000000");
        	jQuery("input[name=ocpc-postdatefontsize]").val("15");
        	jQuery("select[name=ocpc-postdateposition]").val("Left");
        	jQuery("input[name=ocpc-postdatefweight]").val("Bold");

        	jQuery("input[name=ocpc-postdescriptioncolor]").val("#000000");
        	jQuery("input[name=ocpc-postdescriptionfontsize]").val("16");
        	jQuery("select[name=ocpc-postdescriptionposition]").val("Left");
        	jQuery("input[name=ocpc-postdescriptionfweight]").val("Bold");

        	jQuery("input[name=ocpc-readmoretextcolor]").val("#000000");
        }

        if(selectedtem == "tem4"){
        	jQuery("input[name=ocpc-posttitlecolor]").val("#000000");
        	jQuery("input[name=ocpc-posttitlefontsize]").val("16");
        	jQuery("select[name=ocpc-posttitleposition]").val("Left");
        	jQuery("input[name=ocpc-posttitlefweight]").val("Bold");

        	jQuery("input[name=ocpc-postdatecolor]").val("#000000");
        	jQuery("input[name=ocpc-postdatefontsize]").val("15");
        	jQuery("select[name=ocpc-postdateposition]").val("Left");
        	jQuery("input[name=ocpc-postdatefweight]").val("Bold");

        	jQuery("input[name=ocpc-postdescriptioncolor]").val("#000000");
        	jQuery("input[name=ocpc-postdescriptionfontsize]").val("16");
        	jQuery("select[name=ocpc-postdescriptionposition]").val("Left");
        	jQuery("input[name=ocpc-postdescriptionfweight]").val("Bold");

        	jQuery("input[name=ocpc-readmoretextcolor]").val("#000000");
        }


        if(selectedtem == "tem5"){
        	jQuery("input[name=ocpc-posttitlecolor]").val("#000000");
        	jQuery("input[name=ocpc-posttitlefontsize]").val("16");
        	jQuery("select[name=ocpc-posttitleposition]").val("Left");
        	jQuery("input[name=ocpc-posttitlefweight]").val("Bold");

        	jQuery("input[name=ocpc-postdatecolor]").val("#000000");
        	jQuery("input[name=ocpc-postdatefontsize]").val("15");
        	jQuery("select[name=ocpc-postdateposition]").val("Left");
        	jQuery("input[name=ocpc-postdatefweight]").val("Bold");

        	jQuery("input[name=ocpc-postdescriptioncolor]").val("#000000");
        	jQuery("input[name=ocpc-postdescriptionfontsize]").val("16");
        	jQuery("select[name=ocpc-postdescriptionposition]").val("Left");
        	jQuery("input[name=ocpc-postdescriptionfweight]").val("Bold");

        	jQuery("input[name=ocpc-readmoretextcolor]").val("#000000");
        }

    });
})