<?php

if (!defined('ABSPATH'))
  exit;

if (!class_exists('OCPC_meta_update')) {

  class OCPC_meta_update {

    protected static $instance;
   
    function OCPC_meta_save( $post_id, $post ) {
        // the following line is needed because we will hook into edit_post hook, so that we can set default value of checkbox.
        if ($post->post_type != 'ocpostcarousel') {
            return;
        }


        // Is the user allowed to edit the post or page?
        if ( !current_user_can( 'edit_post', $post_id ))
            return;



        // Perform checking for before saving
        $is_autosave = wp_is_post_autosave($post_id);
        $is_revision = wp_is_post_revision($post_id);
        $is_valid_nonce = (isset($_POST['OCPC_meta_save_nounce']) && wp_verify_nonce( $_POST['OCPC_meta_save_nounce'], 'OCPC_meta_save' )? 'true': 'false');

        if ( $is_autosave || $is_revision || !$is_valid_nonce ) return;

        // get all data to save

        // General Settings tab1
        $ocpc_enabled = (!empty($_POST['ocpc-enabled']))? $post_id : '';
        $ocpc_posttype = (!empty($_POST['ocpc-posttype']))? sanitize_text_field( $_POST["ocpc-posttype"] ): 'post';
        $ocpc_datasource = (!empty($_POST['ocpc-datasource']))? sanitize_text_field( $_POST["ocpc-datasource"] ): '';
        $ocpc_postcategories = (!empty($_POST['ocpc-postcategories']))? implode(",",$_POST["ocpc-postcategories"] ): '';
        $ocpc_posttags = (!empty($_POST['ocpc-posttags']))? implode(",",$_POST["ocpc-posttags"] ): '';
        $ocpc_postbyids = (!empty($_POST['ocpc-postbyids']))? sanitize_text_field( $_POST["ocpc-postbyids"] ): '';
        $ocpc_orderby = (!empty($_POST['ocpc-orderby']))? sanitize_text_field( $_POST["ocpc-orderby"] ): 'date';
        $ocpc_sortorder = (!empty($_POST['ocpc-sortorder']))? sanitize_text_field( $_POST["ocpc-sortorder"] ): 'DESC';
        $ocpc_slidertitle = (!empty($_POST['ocpc-slidertitle']))? sanitize_text_field( $_POST["ocpc-slidertitle"] ): 'Latest Post';
        $ocpc_slidertitlecolor = (!empty($_POST['ocpc-slidertitlecolor']))? sanitize_text_field( $_POST["ocpc-slidertitlecolor"] ): '#000000';
        $ocpc_slidertitlefontsize = (!empty($_POST['ocpc-slidertitlefontsize']))? sanitize_text_field( $_POST["ocpc-slidertitlefontsize"] ): 30;
        $ocpc_slidertitleposition = (!empty($_POST['ocpc-slidertitleposition']))? sanitize_text_field( $_POST["ocpc-slidertitleposition"] ): 'Center';
        $ocpc_slidertitlefweight = (!empty($_POST['ocpc-slidertitlefweight']))? sanitize_text_field( $_POST["ocpc-slidertitlefweight"] ): 'Bold';



        update_post_meta( $post_id, 'ocpc-enabled', $ocpc_enabled );
        update_post_meta( $post_id, 'ocpc-posttype', $ocpc_posttype );
        update_post_meta( $post_id, 'ocpc-datasource', $ocpc_datasource );
        update_post_meta( $post_id, 'ocpc-postcategories', $ocpc_postcategories );
        update_post_meta( $post_id, 'ocpc-posttags', $ocpc_posttags );
        update_post_meta( $post_id, 'ocpc-postbyids', $ocpc_postbyids );
        update_post_meta( $post_id, 'ocpc-orderby', $ocpc_orderby );
        update_post_meta( $post_id, 'ocpc-sortorder', $ocpc_sortorder );
        update_post_meta( $post_id, 'ocpc-slidertitle', $ocpc_slidertitle );
        update_post_meta( $post_id, 'ocpc-slidertitlecolor', $ocpc_slidertitlecolor );
        update_post_meta( $post_id, 'ocpc-slidertitlefontsize', $ocpc_slidertitlefontsize );
        update_post_meta( $post_id, 'ocpc-slidertitleposition', $ocpc_slidertitleposition );
        update_post_meta( $post_id, 'ocpc-slidertitlefweight', $ocpc_slidertitlefweight );
        // End General Settings tab1



        //Post Content Settings        
        $ocpc_postdesclength = (!empty($_POST['ocpc-postdesclength']))? sanitize_text_field( $_POST["ocpc-postdesclength"] ): 15;
        $ocpc_readmoretext = (!empty($_POST['ocpc-readmoretext']))? sanitize_text_field( $_POST["ocpc-readmoretext"] ): 'Read More';
        $ocpc_readmoretextcolor = (!empty($_POST['ocpc-readmoretextcolor']))? sanitize_text_field( $_POST["ocpc-readmoretextcolor"] ): '#0a2dda';
          
        

        update_post_meta( $post_id, 'ocpc-postdesclength', $ocpc_postdesclength );
        update_post_meta( $post_id, 'ocpc-readmoretext', $ocpc_readmoretext );
        update_post_meta( $post_id, 'ocpc-readmoretextcolor', $ocpc_readmoretextcolor );
       
        //End Post Content Settings  


        //template setting data 
        $ocpc_totalposts = (!empty($_POST['ocpc-totalposts']))? sanitize_text_field( $_POST["ocpc-totalposts"] ): 5;
        update_post_meta( $post_id, 'ocpc-totalposts', $ocpc_totalposts );

        $ocpc_perpage = $_POST['ocpc-perpage'];
        update_post_meta( $post_id, 'ocpc-perpage', $ocpc_perpage );

        $ocpc_showpagination = $_POST['ocpc-showpagination'];
        update_post_meta( $post_id, 'ocpc-showpagination', $ocpc_showpagination );


        update_post_meta( $post_id, 'ocpc-template', $_POST['sel_template'] );
        update_post_meta( $post_id, 'ocpc-option', $_POST['ocpc_option'] );

        update_post_meta( $post_id, 'ocpc_gl_space_img', $_POST['ocpc_gl_space_img'] );
        update_post_meta( $post_id, 'ocpc_gl_clm', $_POST['ocpc_gl_clm'] );

        update_post_meta( $post_id, 'ocpc_ms_clm', $_POST['ocpc_ms_clm'] );
        //End template setting data 

        //Post Title
        $ocpc_posttitle = (!empty($_POST['ocpc-posttitle']))? sanitize_text_field( $_POST["ocpc-posttitle"] ): 'no';
        $ocpc_posttitlecolor = (!empty($_POST['ocpc-posttitlecolor']))? sanitize_text_field( $_POST["ocpc-posttitlecolor"] ): '';
        $ocpc_posttitlefontsize = (!empty($_POST['ocpc-posttitlefontsize']))? sanitize_text_field( $_POST["ocpc-posttitlefontsize"] ): 15;
        $ocpc_posttitleposition = (!empty($_POST['ocpc-posttitleposition']))? sanitize_text_field( $_POST["ocpc-posttitleposition"] ): 'left';
        $ocpc_posttitlefweight = (!empty($_POST['ocpc-posttitlefweight']))? sanitize_text_field( $_POST["ocpc-posttitlefweight"] ): 'Bold';
        $ocpc_posttitledata = array('ocpc_posttitle' => $ocpc_posttitle,
                                    'ocpc_posttitlecolor' => $ocpc_posttitlecolor,
                                    'ocpc_posttitlefontsize' => $ocpc_posttitlefontsize,
                                    'ocpc_posttitleposition' => $ocpc_posttitleposition,
                                    'ocpc_posttitlefweight' => $ocpc_posttitlefweight);
        $ocpc_posttitledata=serialize($ocpc_posttitledata);
        update_post_meta( $post_id, 'ocpc-posttitledata', $ocpc_posttitledata);





        //Post Date
        $ocpc_postdate = (!empty($_POST['ocpc-postdate']))? sanitize_text_field( $_POST["ocpc-postdate"] ): 'no';
        $ocpc_postdatecolor = (!empty($_POST['ocpc-postdatecolor']))? sanitize_text_field( $_POST["ocpc-postdatecolor"] ): '#a5a4ae';
        $ocpc_postdatefontsize = (!empty($_POST['ocpc-postdatefontsize']))? sanitize_text_field( $_POST["ocpc-postdatefontsize"] ): 12;
        $ocpc_postdateposition = (!empty($_POST['ocpc-postdateposition']))? sanitize_text_field( $_POST["ocpc-postdateposition"] ): 'left';
        $ocpc_postdatefweight = (!empty($_POST['ocpc-postdatefweight']))? sanitize_text_field( $_POST["ocpc-postdatefweight"] ): 'Normal';
        $ocpc_postdatedata = array('ocpc_postdate' => $ocpc_postdate,
                                    'ocpc_postdatecolor' => $ocpc_postdatecolor,
                                    'ocpc_postdatefontsize' => $ocpc_postdatefontsize,
                                    'ocpc_postdateposition' => $ocpc_postdateposition,
                                    'ocpc_postdatefweight' => $ocpc_postdatefweight);
        $ocpc_postdatedata=serialize($ocpc_postdatedata);
        update_post_meta( $post_id, 'ocpc-postdatedata', $ocpc_postdatedata);



        //Post author
        $ocpc_postauthor = (!empty($_POST['ocpc-postauthor']))? sanitize_text_field( $_POST["ocpc-postauthor"] ): 'no';
        $ocpc_postauthorcolor = (!empty($_POST['ocpc-postauthorcolor']))? sanitize_text_field( $_POST["ocpc-postauthorcolor"] ): '#a5a4ae';
        $ocpc_postauthorfontsize = (!empty($_POST['ocpc-postauthorfontsize']))? sanitize_text_field( $_POST["ocpc-postauthorfontsize"] ): 12;
        $ocpc_postauthorposition = (!empty($_POST['ocpc-postauthorposition']))? sanitize_text_field( $_POST["ocpc-postauthorposition"] ): 'left';
        $ocpc_postauthorfweight = (!empty($_POST['ocpc-postauthorfweight']))? sanitize_text_field( $_POST["ocpc-postauthorfweight"] ): 'Normal';
        $ocpc_postauthordata = array('ocpc_postauthor' => $ocpc_postauthor,
                                    'ocpc_postauthorcolor' => $ocpc_postauthorcolor,
                                    'ocpc_postauthorfontsize' => $ocpc_postauthorfontsize,
                                    'ocpc_postauthorposition' => $ocpc_postauthorposition,
                                    'ocpc_postauthorfweight' => $ocpc_postauthorfweight);
        $ocpc_postauthordata=serialize($ocpc_postauthordata);
        update_post_meta( $post_id, 'ocpc-postauthordata', $ocpc_postauthordata);



        //Post Description Data
        $ocpc_postdescription = (!empty($_POST['ocpc-postdescription']))? sanitize_text_field( $_POST["ocpc-postdescription"] ): 'no';
        $ocpc_postdescriptioncolor = (!empty($_POST['ocpc-postdescriptioncolor']))? sanitize_text_field( $_POST["ocpc-postdescriptioncolor"] ): '';
        $ocpc_postdescriptionfontsize = (!empty($_POST['ocpc-postdescriptionfontsize']))? sanitize_text_field( $_POST["ocpc-postdescriptionfontsize"] ): 14;
        $ocpc_postdescriptionposition = (!empty($_POST['ocpc-postdescriptionposition']))? sanitize_text_field( $_POST["ocpc-postdescriptionposition"] ): 'Left';
        $ocpc_postdescriptionfweight = (!empty($_POST['ocpc-postdescriptionfweight']))? sanitize_text_field( $_POST["ocpc-postdescriptionfweight"] ): 'Normal';
        $ocpc_postdescriptiondata = array('ocpc_postdescription' => $ocpc_postdescription,
                                    'ocpc_postdescriptioncolor' => $ocpc_postdescriptioncolor,
                                    'ocpc_postdescriptionfontsize' => $ocpc_postdescriptionfontsize,
                                    'ocpc_postdescriptionposition' => $ocpc_postdescriptionposition,
                                    'ocpc_postdescriptionfweight' => $ocpc_postdescriptionfweight);
        $ocpc_postdescriptiondata=serialize($ocpc_postdescriptiondata);
        update_post_meta( $post_id, 'ocpc-postdescriptiondata', $ocpc_postdescriptiondata);

        //slider setting in desktop
        $ocpc_perrow = (!empty($_POST['ocpc-perrow']))? sanitize_text_field( $_POST["ocpc-perrow"] ) : 3;
        $ocpc_autoplay = (!empty($_POST['ocpc-autoplay']))? sanitize_text_field( $_POST["ocpc-autoplay"] ) : 'true';
        $ocpc_spacingbetwee = (!empty($_POST['ocpc-spacingbetwee']))? sanitize_text_field( $_POST["ocpc-spacingbetwee"] ) : 10;
        $ocpc_autoplaytimeout = (!empty($_POST['ocpc-autoplaytimeout']))? sanitize_text_field( $_POST["ocpc-autoplaytimeout"] ) : 1000;
        $ocpc_autoplayhoverpause = (!empty($_POST['ocpc-autoplayhoverpause']))? sanitize_text_field( $_POST["ocpc-autoplayhoverpause"] ) : 'true';
        $ocpc_sliderarrow = (!empty($_POST['ocpc-sliderarrow']))? sanitize_text_field( $_POST["ocpc-sliderarrow"] ) : 'true';
        


        update_post_meta( $post_id, 'ocpc-perrow', $ocpc_perrow );
        update_post_meta( $post_id, 'ocpc-autoplay', $ocpc_autoplay);
        update_post_meta( $post_id, 'ocpc-spacingbetwee', $ocpc_spacingbetwee );
        update_post_meta( $post_id, 'ocpc-autoplaytimeout', $ocpc_autoplaytimeout );
        update_post_meta( $post_id, 'ocpc-autoplayhoverpause', $ocpc_autoplayhoverpause );
        update_post_meta( $post_id, 'ocpc-sliderarrow', $ocpc_sliderarrow );
       

    }

    function init() {
        // Update all slider options
        add_action( 'edit_post', array($this, 'OCPC_meta_save'), 10, 2);
    }

    public static function instance() {
      if (!isset(self::$instance)) {
        self::$instance = new self();
        self::$instance->init();
      }
      return self::$instance;
    }

  }

  OCPC_meta_update::instance();
}
