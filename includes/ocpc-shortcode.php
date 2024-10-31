<?php

if (!defined('ABSPATH'))
exit;

if (!class_exists('OCPC_shortcode')) {

    class OCPC_shortcode {

        protected static $instance;

        //add read more
        function OCPC__excerpt_more( $ocpc_read_more_text ) {
            return sprintf( '<a id="ocpc-postlink" class="read-more" href="%1$s">%2$s</a>',
                get_permalink( get_the_ID() ),
                $ocpc_read_more_text
            );
        }


        // get limited content form post
        function OCPC_limited_content($limit = 15,$ocpc_read_more_text,$datacontain) {
            $ocpc_content = explode(' ', $datacontain, $limit);
            if (count($ocpc_content)>=$limit) {
                array_pop($ocpc_content);
                $moreText = apply_filters('excerpt_more', $ocpc_read_more_text);
                $ocpc_content = implode(" ",$ocpc_content).'.....<div class="ocpc-moretext">'.$moreText.'</div>';
            } else {
                $ocpc_content = implode(" ",$ocpc_content);
            }
            $ocpc_content = preg_replace('`\[[^\]]*\]`','',$ocpc_content);
            return '<p>'.$ocpc_content.'</p>';
        }


        function OCPC_post_carousel_code($atts, $content = null) {
     
            ob_start();
            extract(shortcode_atts(array(
                'id' => '',
            ), $atts));

            //enable slider
            $sliderval = get_post_meta( $id, 'ocpc-enabled', true );

            //get post type
            $posttype = get_post_meta( $id, 'ocpc-posttype', true );

            //get slider title
            $slidertitle = get_post_meta( $id, 'ocpc-slidertitle', true );
            $slidertitlecolor = get_post_meta( $id, 'ocpc-slidertitlecolor', true );
            $slidertitlefontsize = get_post_meta( $id, 'ocpc-slidertitlefontsize', true );
            $slidertitleposition = get_post_meta( $id, 'ocpc-slidertitleposition', true );
            $slidertitlefweight = get_post_meta( $id, 'ocpc-slidertitlefweight', true );

            //get totalpost
            $totalpostss     = get_post_meta( $id, 'ocpc-totalposts', true );
            $showpagination = get_post_meta( $id, 'ocpc-showpagination', true );
            $perpage = get_post_meta( $id, 'ocpc-perpage', true );
            if($posttype != "attachment") {
              if($showpagination == "on") {
                $totalposts = $perpage;
              }else{
                $totalposts = $totalpostss;
              }
            }else{
              $totalposts = $totalpostss;
            }
            

            //get post title data
            $posttitledata = unserialize(get_post_meta( $id, 'ocpc-posttitledata', true ));
            $ocpc_posttitle = $posttitledata['ocpc_posttitle'];
            $ocpc_posttitlecolor = $posttitledata['ocpc_posttitlecolor'];
            $ocpc_posttitlefontsize = $posttitledata['ocpc_posttitlefontsize'];
            $ocpc_posttitleposition = $posttitledata['ocpc_posttitleposition'];
            $ocpc_posttitlefweight = $posttitledata['ocpc_posttitlefweight'];


            //get post title date
            $postdatedata = unserialize(get_post_meta( $id, 'ocpc-postdatedata', true ));
            $ocpc_postdate = $postdatedata['ocpc_postdate'];
            $ocpc_postdatecolor = $postdatedata['ocpc_postdatecolor'];
            $ocpc_postdatefontsize = $postdatedata['ocpc_postdatefontsize'];
            $ocpc_postdateposition = $postdatedata['ocpc_postdateposition'];
            $ocpc_postdatefweight = $postdatedata['ocpc_postdatefweight'];


            //get post author
            $postdatedata = unserialize(get_post_meta( $id, 'ocpc-postauthordata', true ));
            $ocpc_postauthor = $postdatedata['ocpc_postauthor'];
            $ocpc_postauthorcolor = $postdatedata['ocpc_postauthorcolor'];
            $ocpc_postauthorfontsize = $postdatedata['ocpc_postauthorfontsize'];
            $ocpc_postauthorposition = $postdatedata['ocpc_postauthorposition'];
            $ocpc_postauthorfweight = $postdatedata['ocpc_postauthorfweight'];


            //get image data
            $placeholderimage = get_post_meta($id, 'ocpc-placeholderimage', true );
      

            //get post title date
            $postdescriptiondata = unserialize(get_post_meta( $id, 'ocpc-postdescriptiondata', true ));
            $ocpc_postdescription = $postdescriptiondata['ocpc_postdescription'];
            $ocpc_postdescriptioncolor = $postdescriptiondata['ocpc_postdescriptioncolor'];
            $ocpc_postdescriptionfontsize = $postdescriptiondata['ocpc_postdescriptionfontsize'];
            $ocpc_postdescriptionposition = $postdescriptiondata['ocpc_postdescriptionposition'];
            $ocpc_postdescriptionfweight = $postdescriptiondata['ocpc_postdescriptionfweight'];
            $readmoretext = get_post_meta( $id, 'ocpc-readmoretext', true );
            $postdesclength = get_post_meta( $id, 'ocpc-postdesclength', true );


            //get orderby data
            $orderby = get_post_meta( $id, 'ocpc-orderby', true );

            //get sortingdata
            $sortorder = get_post_meta( $id, 'ocpc-sortorder', true );

            //get post data
            $datasource = get_post_meta( $id, 'ocpc-datasource', true );
            $postbyids = explode(",",get_post_meta( $id, 'ocpc-postbyids', true ));
            $posttags = explode(",",get_post_meta( $id, 'ocpc-posttags', true ));
            $postcategories = explode(",",get_post_meta( $id, 'ocpc-postcategories', true ));

            //get readmore text color
            $readmoretextcolor = get_post_meta( $id, 'ocpc-readmoretextcolor', true );

            //set randnumber slider main class
            $ocpc_random_number_id = rand();

            //get post layout option
            $post_layout = get_post_meta( $id, 'ocpc-option', true );

            //get post template
            $post_template = get_post_meta( $id, 'ocpc-template', true );

            $ocpc_gl_space_img = get_post_meta( $id, 'ocpc_gl_space_img', true );
            $ocpc_gl_clm = get_post_meta( $id, 'ocpc_gl_clm', true );

            $ocpc_ms_clm = get_post_meta( $id, 'ocpc_ms_clm', true );
            include ('ocpc-style.php');

            //Check Enable or Disable Slider
            if(!empty($sliderval)) { 
            
                echo '<div class="ocpc_post_layout '.$post_template.' '.$post_layout.'">';


                    if(!empty($slidertitle)) {
                       ?>
                          <div class="ocpc-slider-title"><?php echo $slidertitle;?></div>
                       <?php 
                    } 


                    //create args for post.
                    $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
                    //print_r($paged);
                    $ocpc_args = array();
                    $limit = (!empty($totalposts) ?  absint($totalposts) : 5);
                    $offset = ( $limit * $paged ) - $limit;
                    $ocpc_defualt_args = [
                       'post_type' => $posttype,
                       'posts_per_page'=> (!empty($totalposts) ?  absint($totalposts) : 5),
                       'post_status'    => 'publish',
                       //'no_found_rows'=> true, // remove if pagination needed
                       'orderby'   => $orderby,
                       'order'     => $sortorder,
                       'paged' => $paged,
                       'offset' => $offset,
                       'caller_get_posts' => 1,
                    ];


                    $ocpc_args_img = array(
                       'post_type'      => 'attachment',
                       'numberposts'    => (!empty($totalposts) ?  absint($totalposts) : 10),
                       'post_status'    => 'any',
                       'post_mime_type' => 'image',
                       'orderby'   => $orderby,
                       'order'     => $sortorder,
                    );


                    if($datasource == 'ocpc-op_categories') {
                       if($posttype == 'product') {
                          $op_categories = [
                             'tax_query' => array(
                                'relation' => 'AND',
                                array(
                                   'taxonomy' => 'product_cat',
                                   'field' => 'id',
                                   'terms' => $postcategories,
                                   'operator' => 'IN',
                                )
                             ),
                          ];
                       }else if($posttype == 'post') {
                          $op_categories = [
                             'category__in' => $postcategories,
                          ];
                       }
                       $ocpc_args = array_merge($ocpc_defualt_args, $op_categories);
                    }else if( $datasource == 'ocpc-op_id') {
                       $op_id = [
                          'post__in' => $postbyids,
                       ];
                       $ocpc_args = array_merge($ocpc_defualt_args, $op_id);
                    }else if( $datasource == 'ocpc-op_tags') {
                       if($posttype == 'product') {
                          $op_tags = [
                             'tax_query' => array(
                                 'relation' => 'AND',
                                 array(
                                     'taxonomy' => 'product_tag',
                                     'field' => 'id',
                                     'terms' => $posttags,
                                     'operator' => 'IN',
                                 )
                             ),
                          ];
                       }else if($posttype == 'post') {
                          $op_tags = [
                             'tag__in' => $posttags,
                          ];
                       }
                       $ocpc_args = array_merge($ocpc_defualt_args, $op_tags);
                    }else {
                       $ocpc_args = $ocpc_defualt_args;
                    }
                    ?>
                    <div class="ocpc_post_main_class">
                       	<?php
                          	if($post_layout == "gallery") {
                             	include ('ocpcgallery.php');
                          	}

                          	if($post_layout == "carousel") {
                             	include ('ocpcslider.php'); 
                          	} 

                          	

                       	?>
                    </div>   
                    <?php 
                echo '<div >';
            } 

            ?>
                <!--  Slider Settings -->
                <script type="text/javascript">
                    jQuery(document).ready(function(){
                       var ocpc_post_slider = jQuery(".ocpc-main<?php echo $ocpc_random_number_id; ?>");
                       var ocpc_slider_aero = <?php echo esc_attr(get_post_meta( $id, 'ocpc-sliderarrow', true ));?>;
                       ocpc_post_slider.owlCarousel({
                                       loop: true,
                                       autoplay: <?php echo  esc_attr(get_post_meta( $id, 'ocpc-autoplay', true ));?>,
                                       autoplayTimeout: <?php echo  esc_attr(get_post_meta( $id, 'ocpc-autoplaytimeout', true ));?>,
                                       autoplayHoverPause: <?php echo  esc_attr(get_post_meta( $id, 'ocpc-autoplayhoverpause', true ));?>,
                                       margin:<?php echo esc_attr(get_post_meta( $id, 'ocpc-spacingbetwee', true ));?>,
                                       nav: true,
                                       responsive:{
                                         1199:{
                                              items: <?php echo  esc_attr(get_post_meta( $id, 'ocpc-perrow', true ));?>,
                                         }
                                     }
                       });

                       if(ocpc_slider_aero == true) {
                          ocpc_post_slider.find('.owl-nav').removeClass('disabled');
                          ocpc_post_slider.on('changed.owl.carousel', function(event) {
                                ocpc_post_slider.find('.owl-nav').removeClass('disabled');
                          });
                       }

                       if(ocpc_slider_aero == false) {
                          ocpc_post_slider.find('.owl-nav').addClass('disabled');
                          ocpc_post_slider.on('changed.owl.carousel', function(event) {
                                      ocpc_post_slider.find('.owl-nav').addClass('disabled');
                          });
                       }
                    }); 
                </script>

            <?php
            return $var = ob_get_clean();
        }


        function init() {
            add_shortcode( 'ocpc-post-carousel', array($this,'OCPC_post_carousel_code'));
            add_filter( 'excerpt_more',  array($this,'OCPC__excerpt_more'));
        }

        public static function instance() {
            if (!isset(self::$instance)) {
                self::$instance = new self();
                self::$instance->init();
            }
            return self::$instance;
        }
    }
    OCPC_shortcode::instance();
}

