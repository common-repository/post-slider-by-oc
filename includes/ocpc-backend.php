<?php

if (!defined('ABSPATH'))
  exit;

if (!class_exists('OCPC_menu')) {

    class OCPC_menu {

        protected static $instance;
        /**
         * Registers ADL Post Slider post type.
         */

        function OCPC_create_menu() {
            $post_type = 'ocpostcarousel';
            $singular_name = 'Post Carousel';
            $plural_name = 'Post Carousel';
            $slug = 'ocpostcarousel';
            $labels = array(
                'name'               => _x( $plural_name, 'post type general name', 'ocpc' ),
                'singular_name'      => _x( $singular_name, 'post type singular name', 'ocpc' ),
                'menu_name'          => _x( $singular_name, 'admin menu name', 'ocpc' ),
                'name_admin_bar'     => _x( $singular_name, 'add new name on admin bar', 'ocpc' ),
                'add_new'            => __( 'Add New', 'ocpc' ),
                'add_new_item'       => __( 'Add New '.$singular_name, 'ocpc' ),
                'new_item'           => __( 'New '.$singular_name, 'ocpc' ),
                'edit_item'          => __( 'Edit '.$singular_name, 'ocpc' ),
                'view_item'          => __( 'View '.$singular_name, 'ocpc' ),
                'all_items'          => __( 'All '.$plural_name, 'ocpc' ),
                'search_items'       => __( 'Search '.$plural_name, 'ocpc' ),
                'parent_item_colon'  => __( 'Parent '.$plural_name.':', 'ocpc' ),
                'not_found'          => __( 'No sliders found.', 'ocpc' ),
                'not_found_in_trash' => __( 'No books found in Trash.', 'ocpc' )
            );

            $args = array(
                'labels'             => $labels,
                'description'        => __( 'Description.', 'ocpc' ),
                'public'             => false,
                'publicly_queryable' => true,
                'show_ui'            => true,
                'show_in_menu'       => true,
                'query_var'          => true,
                'rewrite'            => array( 'slug' => $slug ),
                'capability_type'    => 'post',
                'has_archive'        => true,
                'hierarchical'       => false,
                'menu_position'      => null,
                'supports'           => array( 'title' ),
                'menu_icon'          => 'dashicons-images-alt'


            );
            register_post_type( $post_type, $args );
        }

        // Add post Meta box for Slider Settings
        function OCPC_add_meta_box() {
            add_meta_box(
                'OCPC_metabox',
                __( 'Slider Settings', 'ocpc' ),
                array($this, 'OCPC_metabox_cb'),
                'ocpostcarousel',
                'normal'
            );
        }

        //Add all slider Options
        function OCPC_metabox_cb( $post ) {
            // Add a nonce field so we can check for it later.
            wp_nonce_field( 'OCPC_meta_save', 'OCPC_meta_save_nounce' );
            ?> 
             <div class="ocpc-container">
                <div class="ocpc_shortcode">
                   <span><?php echo __( 'Shortcode:', OCPC_DOMAIN );?></span><input type="text" id="ocpc-selectdata_<?php echo $post->ID;;?>" value="[ocpc-post-carousel id=<?php echo $post->ID;?>]" size="30" onclick="ocpc_select_data(this.id)" readonly>
                </div>
                <ul class="tabs">
                    <li class="tab-link current" data-tab="tab-general"><?php echo __( 'General Settings', OCPC_DOMAIN );?></li>
                    <li class="tab-link" data-tab="tab-title"><?php echo __( 'Title Settings', OCPC_DOMAIN );?></li>
                    <li class="tab-link" data-tab="tab-data"><?php echo __( 'Data Settings', OCPC_DOMAIN );?></li>
                    <li class="tab-link" data-tab="tab-template"><?php echo __( 'Template Settings', OCPC_DOMAIN );?></li>
                </ul>
                <div id="tab-general" class="tab-content current">
                    <fieldset>
                        <legend><?php echo __( 'You can Enable/Disable this Post Slider and also you can use other setting related to Slider.', OCPC_DOMAIN );?></legend>
                            <p><label>
                                <input type="checkbox" name="ocpc-enabled" value="enabled" <?php if (isset($_REQUEST['post'])){if (get_post_meta( $post->ID, 'ocpc-enabled', true ) == $post->ID) {echo 'checked="checked"';}} ?>><?php echo __( 'Enable/Disable this Post Slider', OCPC_DOMAIN );?>
                            </label></p>
                        <table class="form-table">
                            <tbody>
                                <tr>
                                    <th scope="row">
                                        <label><?php echo __( 'Post Type', OCPC_DOMAIN );?></label>
                                    </th>
                                    <td>
                                        <?php

                                            $ocpc_args = array(
                                                        'public'   => true
                                                    );
                                            $ocpc_output = 'names'; // names or objects, note names is the default
                                            $ocpc_operator = 'and'; // 'and' or 'or'
                                            $ocpc_post_types = get_post_types( $ocpc_args, $ocpc_output, $ocpc_operator ); 

                                        ?>
                                        <select name="ocpc-posttype" class='posttype_change'>
                                            <?php foreach ( $ocpc_post_types  as $ocpc_post_type ) { ?>
                                            <option value="<?php echo $ocpc_post_type; ?>" <?php if(get_post_meta( $post->ID, 'ocpc-posttype', true ) == $ocpc_post_type){echo "selected";} ?>><?php echo __( $ocpc_post_type , OCPC_DOMAIN );?></option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr class="ocpc_add_sourcedata">
                                <?php $defult_posttype = get_post_meta( $post->ID, 'ocpc-posttype', true ); 
                                if($defult_posttype == 'post' || $defult_posttype == 'product') {
                                ?>
                                    <th scope="row">
                                        <label><?php echo __( 'Select Data Source', OCPC_DOMAIN );?></label>
                                    </th>
                                    <td>
                                        <select name="ocpc-datasource" class="ocpc-datasource">
                                            <option value=""> <?php echo __( '--- Select Options ----', OCPC_DOMAIN );?> </option>
                                            <option value="ocpc-op_categories" <?php if(get_post_meta( $post->ID, 'ocpc-datasource', true ) == 'ocpc-op_categories'){echo "selected";} ?>><?php echo __( 'Categories', OCPC_DOMAIN );?></option>
                                            <option value="ocpc-op_id" <?php if(get_post_meta( $post->ID, 'ocpc-datasource', true ) == 'ocpc-op_id'){echo "selected";} ?>><?php echo __( 'ID', OCPC_DOMAIN );?></option>
                                            <option value="ocpc-op_tags" <?php if(get_post_meta( $post->ID, 'ocpc-datasource', true ) == 'ocpc-op_tags'){echo "selected";} ?>><?php echo __( 'Tags', OCPC_DOMAIN );?></option>
                                        </select>
                                    </td>
                               <?php } else if($defult_posttype == 'page') {?>
                                    <th scope="row">
                                        <label><?php echo __( 'Select Data Source', OCPC_DOMAIN );?></label>
                                    </th>
                                    <td>
                                        <select name="ocpc-datasource" class="ocpc-datasource">
                                            <option value=""> <?php echo __( '--- Select Options ----', OCPC_DOMAIN );?> </option>
                                            <option value="ocpc-op_id" <?php if(get_post_meta( $post->ID, 'ocpc-datasource', true ) == 'ocpc-op_id'){echo "selected";} ?>><?php echo __( 'ID', OCPC_DOMAIN );?></option>>
                                        </select>
                                    </td>
                               <?php } ?> 
                                </tr>
                                <tr class="ocps_options_cat" id='ocps_options_comman' style="display:none;">
                                    <?php
                                     if($defult_posttype == 'post') {
                                        $ocpc_categories = get_categories( array(
                                                            'orderby' => 'name',
                                                            'order'   => 'ASC'
                                                        ) );
                                     } else if($defult_posttype == 'product'){
                                         $orderby = 'name';
                                        $order = 'asc';
                                        $hide_empty = false;
                                        $cat_args = array(
                                            'orderby'    => $orderby,
                                            'order'      => $order,
                                            'hide_empty' => $hide_empty,
                                        );
                                        
                                        $ocpc_categories = get_terms( 'product_cat', $cat_args );
                                     }   
                                    ?>
                                    <th scope="row">
                                        <label><?php echo __( 'Categories', OCPC_DOMAIN );?></label>
                                    </th>
                                    <td>
                                        <select multiple name="ocpc-postcategories[]">
                                            <option value=""> <?php echo __( '--- Select Categories ----', OCPC_DOMAIN );?> </option>
                                            <?php
                                            foreach( $ocpc_categories as $ocpc_category ) {
                                            ?>
                                            <option value="<?php echo $ocpc_category->term_id;?>" <?php if(in_array($ocpc_category->term_id,explode(",",get_post_meta( $post->ID, 'ocpc-postcategories', true )))){echo "selected";} ?>><?php echo $ocpc_category->name;?></option>
                                        <?php } ?>
                                        </select>
                                        <p class="ocpc-tips"><?php echo __( 'Note: Press and hold Ctrl Key and select more than one item from the list.', OCPC_DOMAIN );?></p>
                                    </td>
                                </tr>
                                <tr class="ocps_options_tags" id='ocps_options_comman' style="display:none;">
                                    <?php
                                     if($defult_posttype == 'post') {
                                         $ocpc_tags = get_tags();
                                     } else if($defult_posttype == 'product'){
                                        $ocpc_tags = $terms = get_terms(array('taxonomy' => 'product_tag', 'hide_empty' => false));
                                     }   
                                    ?>
                                    <th scope="row">
                                        <label><?php echo __( 'Tags', OCPC_DOMAIN );?></label>
                                    </th>
                                    <td>
                                        <select multiple name="ocpc-posttags[]">
                                            <option value=""> <?php echo __( '--- Select Tags ----', OCPC_DOMAIN );?> </option>
                                            <?php
                                            foreach( $ocpc_tags as $ocpc_tag ) {
                                            ?>
                                            <option value="<?php echo $ocpc_tag->term_id;?>" <?php if(in_array($ocpc_tag->term_id,explode(",",get_post_meta( $post->ID, 'ocpc-posttags', true )))){echo "selected";} ?>><?php echo $ocpc_tag->name;?></option>
                                        <?php } ?>
                                        </select>
                                        <p class="ocpc-tips"><?php echo __( 'Note: Press and hold Ctrl Key and select more than one item from the list.', OCPC_DOMAIN );?></p>
                                    </td>
                                </tr>
                                <tr class="ocps_options_ids" style="display:none;">
                                    <th scope="row">
                                        <label><?php echo __( 'Post By IDs', OCPC_DOMAIN );?></label>
                                    </th>
                                    <td>
                                        <input type="text" name="ocpc-postbyids" size="100" value="<?php echo get_post_meta( $post->ID, 'ocpc-postbyids', true ); ?>">
                                        <p class="ocpc-tips"><?php echo __( 'Enter posts IDs to display only those records (Note: separate values by commas (,))', OCPC_DOMAIN );?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">
                                        <label><?php echo __( 'Order by', OCPC_DOMAIN );?></label>
                                    </th>
                                    <td>
                                        <select name="ocpc-orderby">
                                            <option  value="date" <?php if(get_post_meta( $post->ID, 'ocpc-orderby', true ) == 'date'){echo "selected";} ?>><?php echo __( 'Date', OCPC_DOMAIN );?></option>
                                            <option  value="ID" <?php if(get_post_meta( $post->ID, 'ocpc-orderby', true ) == 'ID'){echo "selected";} ?>><?php echo __( 'Order by post ID', OCPC_DOMAIN );?></option>
                                            <option  value="author" <?php if(get_post_meta( $post->ID, 'ocpc-orderby', true ) == 'author'){echo "selected";} ?>><?php echo __( 'Author', OCPC_DOMAIN );?></option>
                                            <option  value="rand" <?php if(get_post_meta( $post->ID, 'ocpc-orderby', true ) == 'rand'){echo "selected";} ?>><?php echo __( 'Random order', OCPC_DOMAIN );?></option>
                                        </select>
                                        <p class="ocpc-tips"><?php echo __( 'Select order type.', OCPC_DOMAIN );?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">
                                        <label><?php echo __( 'Sort order', OCPC_DOMAIN );?></label>
                                    </th>
                                    <td>
                                        <select name="ocpc-sortorder">
                                            <option value="DESC" <?php if(get_post_meta( $post->ID, 'ocpc-sortorder', true ) == 'DESC'){echo "selected";} ?>><?php echo __( 'Descending', OCPC_DOMAIN );?></option>
                                            <option value="ASC" <?php if(get_post_meta( $post->ID, 'ocpc-sortorder', true ) == 'ASC'){echo "selected";} ?>><?php echo __( 'Ascending', OCPC_DOMAIN );?></option>
                                        </select>
                                        <p class="ocpc-tips"><?php echo __( 'Select sorting order.', OCPC_DOMAIN );?></p>
                                    </td>
                                </tr>
                               
                            </tbody>
                        </table>
                    </fieldset>
                </div>
                <div id="tab-title" class="tab-content">
                    <fieldset>
                        <table class="form-table">
                            <tbody>
                                <tr>
                                    <th scope="row">
                                        <label><?php echo __( 'Title', OCPC_DOMAIN );?></label>
                                    </th>
                                    <td>
                                        <input type="text" name="ocpc-slidertitle" size="50" placeholder="Latest Post" value="<?php echo get_post_meta( $post->ID, 'ocpc-slidertitle', true ); ?>">
                                        <p class="ocpc-tips"><?php echo __( 'Add Slider title', OCPC_DOMAIN );?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">
                                        <label><?php echo __( 'Title Color', OCPC_DOMAIN );?></label>
                                    </th>
                                    <td>
                                        <input type="color" name="ocpc-slidertitlecolor" value="<?php echo get_post_meta( $post->ID, 'ocpc-slidertitlecolor', true ); ?>">
                                        <p class="ocpc-tips"><?php echo __( 'Add Slider title color', OCPC_DOMAIN );?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">
                                        <label><?php echo __( 'Title Font Size', OCPC_DOMAIN );?></label>
                                    </th>
                                    <td>
                                        <input type="number" name="ocpc-slidertitlefontsize" placeholder="eg. 30" value="<?php echo get_post_meta( $post->ID, 'ocpc-slidertitlefontsize', true ); ?>">
                                        <p class="ocpc-tips"><?php echo __( 'Add Slider title Font Size', OCPC_DOMAIN );?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">
                                        <label><?php echo __( 'Title Position', OCPC_DOMAIN );?></label>
                                    </th>
                                    <td>
                                        <select name="ocpc-slidertitleposition" >
                                            <option value="Center" <?php if(get_post_meta( $post->ID, 'ocpc-slidertitleposition', true ) == 'Center'){echo "selected";} ?>><?php echo __( 'Center', OCPC_DOMAIN );?></option>
                                            <option value="Left" <?php if(get_post_meta( $post->ID, 'ocpc-slidertitleposition', true ) == 'Left'){echo "selected";} ?>><?php echo __( 'Left', OCPC_DOMAIN );?></option>
                                            <option value="Right" <?php if(get_post_meta( $post->ID, 'ocpc-slidertitleposition', true ) == 'Right'){echo "selected";} ?>><?php echo __( 'Right', OCPC_DOMAIN );?></option>
                                        </select>
                                        <p class="ocpc-tips"><?php echo __( 'Add Slider title Position', OCPC_DOMAIN );?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">
                                        <label><?php echo __( 'Title Font Weight', OCPC_DOMAIN );?></label>
                                    </th>
                                    <td>
                                        <?php 
                                        $defultvalue = get_post_meta( $post->ID, 'ocpc-slidertitlefweight', true );
                                        if(!empty($defultvalue))
                                        {
                                            $slidertitlefweight_dval = $defultvalue;
                                        }
                                        else
                                        {
                                            $slidertitlefweight_dval = 'Bold';
                                        }
                                        ?>
                                        <select name="ocpc-slidertitlefweight">
                                            <option value="Normal" <?php if($slidertitlefweight_dval == 'Normal'){echo "selected";} ?>><?php echo __( 'Normal', OCPC_DOMAIN );?></option>
                                            <option value="Bold" <?php if($slidertitlefweight_dval == 'Bold'){echo "selected";} ?>><?php echo __( 'Bold', OCPC_DOMAIN );?></option>
                                        </select>
                                        <p class="ocpc-tips"><?php echo __( 'Add Slider Title Font Weight', OCPC_DOMAIN );?></p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </fieldset>
                </div>
                <div id="tab-data" class="tab-content">
                	<?php 
                        $posttitledata = unserialize(get_post_meta( $post->ID, 'ocpc-posttitledata', true ));
                        $postdatedata = unserialize(get_post_meta( $post->ID, 'ocpc-postdatedata', true ));
                        $postauthordata = unserialize(get_post_meta( $post->ID, 'ocpc-postauthordata', true ));
                        $postdescriptiondata = unserialize(get_post_meta( $post->ID, 'ocpc-postdescriptiondata', true ));

                        if(empty($posttitledata['ocpc_posttitle'])){
                            $ocpc_posttitle_val = 'yes';
                        } else {
                            $ocpc_posttitle_val = $posttitledata['ocpc_posttitle'];
                        }

                        if(empty($postdatedata['ocpc_postdate'])){
                            $ocpc_postdate_val = 'yes';
                        } else {
                            $ocpc_postdate_val = $postdatedata['ocpc_postdate'];
                        }
                        
                        if(empty($postauthordata['ocpc_postauthor'])){
                            $ocpc_postauthor_val = 'yes';
                        } else {
                            $ocpc_postauthor_val = $postauthordata['ocpc_postauthor'];
                        }


                        if(empty($postdescriptiondata['ocpc_postdescription'])){
                            $ocpc_postdescription_val = 'yes';
                        } else {
                            $ocpc_postdescription_val = $postdescriptiondata['ocpc_postdescription'];
                        }
                    ?>
                    <fieldset>
                        <table class="form-table">
                            <tbody>
                            	<h3><?php echo __( 'Show/Hide Post Data', OCPC_DOMAIN );?></h3>
                                <tr>
                                    <th scope="row">
                                        <input type="checkbox" name="ocpc-posttitle" id='posttitle' value="yes" <?php if($ocpc_posttitle_val == 'yes'){echo "checked";} ?>> <?php echo __( 'Post Title', OCPC_DOMAIN );?>
                                        <p class="ocpc-tips"><?php echo __( 'Show the Post Title.', OCPC_DOMAIN );?></p>
                                    </th>
                                    <td>
                                       	<div class="post_option" id="post_title_option" style="display: none">
                                            <ul>
                                                <li>
                                                	<label><?php echo __( 'Color', OCPC_DOMAIN );?></label>
                                                </li>
                                                <li>
                                                	<input type="color" name="ocpc-posttitlecolor" value="<?php echo $posttitledata['ocpc_posttitlecolor']; ?>">
                                                </li>
                                                <li>
                                                	<label><?php echo __( 'Font Size', OCPC_DOMAIN );?></label>
                                                </li>
                                                <li>
                                                	<input type="number" name="ocpc-posttitlefontsize" placeholder="eg. 15" value="<?php echo $posttitledata['ocpc_posttitlefontsize']; ?>">
                                                </li>
                                                <li>
                                                	<label><?php echo __( 'Position', OCPC_DOMAIN );?></label>
                                                </li>
                                                <li>
    	                                            <?php
    		                                            $defultvalue_ocpc_posttitleposition = $posttitledata['ocpc_posttitleposition'];
    		                                            if(!empty($defultvalue_ocpc_posttitleposition)) {
    		                                                $ocpc_posttitleposition_dval = $defultvalue_ocpc_posttitleposition;
    		                                            }else{
    		                                                $ocpc_posttitleposition_dval = 'Left';
    		                                            }
    	                                            ?>
                                                	<select name="ocpc-posttitleposition" >
                                                        <option value="Center" <?php if($ocpc_posttitleposition_dval == 'Center'){echo "selected";} ?>><?php echo __( 'Center', OCPC_DOMAIN );?></option>
                                                        <option value="Left" <?php if($ocpc_posttitleposition_dval == 'Left'){echo "selected";} ?>><?php echo __( 'Left', OCPC_DOMAIN );?></option>
                                                        <option value="Right" <?php if($ocpc_posttitleposition_dval == 'Right'){echo "selected";} ?>><?php echo __( 'Right', OCPC_DOMAIN );?></option>
                                                    </select>
                                                </li>
                                                <li>
                                                	<label><?php echo __( 'Font Weight', OCPC_DOMAIN );?></label>
                                                </li>
                                                <li>
    	                                            <?php
    	                                            	$defultvalue_ocpc_posttitlefweight = $posttitledata['ocpc_posttitlefweight'];
    	                                            	if(!empty($defultvalue_ocpc_posttitlefweight)) {
    	                                                	$ocpc_posttitlefweight_dval = $defultvalue_ocpc_posttitlefweight;
    	                                            	}else{
    	                                                	$ocpc_posttitlefweight_dval = 'Bold';
    	                                            	}
    	                                            ?>
                                                	<select name="ocpc-posttitlefweight">
                                                        <option value="Normal" <?php if($ocpc_posttitlefweight_dval == 'Normal'){echo "selected";} ?>><?php echo __( 'Normal', OCPC_DOMAIN );?></option>
                                                        <option value="Bold" <?php if($ocpc_posttitlefweight_dval == 'Bold'){echo "selected";} ?>><?php echo __( 'Bold', OCPC_DOMAIN );?></option>
                                                    </select>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                	<th>
                                        <input type="checkbox" name="ocpc-postdate" value="yes" <?php if($ocpc_postdate_val == 'yes'){echo "checked";} ?>> <?php echo __( 'Post Date', OCPC_DOMAIN );?>
                                        <p class="ocpc-tips"><?php echo __( 'Show the Post Date under the title.', OCPC_DOMAIN );?></p>
                                    </th>
                                    <td>
                                        <div class="post_option" id="post_date_option" style="display: none">
                                            <ul>
                                                <?php
                                                $defultvalue_postdatecolor = $postdatedata['ocpc_postdatecolor'];
                                                if(!empty($defultvalue_postdatecolor))
                                                {
                                                    $postdatecolor_dval = $defultvalue_postdatecolor;
                                                }
                                                else
                                                {
                                                    $postdatecolor_dval = '#a5a4ae';
                                                }
                                                ?>
                                                <li><label><?php echo __( 'Color', OCPC_DOMAIN );?></label></li>
                                                <li><input type="color" name="ocpc-postdatecolor" value="<?php echo $postdatecolor_dval; ?>"></li>
                                                <li><label><?php echo __( 'Font Size', OCPC_DOMAIN );?></label></li>
                                                <li><input type="number" name="ocpc-postdatefontsize" placeholder="eg. 30" value="<?php echo $postdatedata['ocpc_postdatefontsize']; ?>"></li>
                                                <li><label><?php echo __( 'Position', OCPC_DOMAIN );?></label></li>
                                                <?php
                                                $defultvalue_postdateposition = $postdatedata['ocpc_postdateposition'];
                                                if(!empty($defultvalue_postdateposition))
                                                {
                                                    $postdateposition_dval = $defultvalue_postdateposition;
                                                }
                                                else
                                                {
                                                    $postdateposition_dval = 'Left';
                                                }
                                                ?>
                                                <li><select name="ocpc-postdateposition" >
                                                        <option value="Center"  <?php if($postdateposition_dval == 'Center'){echo "selected";} ?>><?php echo __( 'Center', OCPC_DOMAIN );?></option>
                                                        <option value="Left"  <?php if($postdateposition_dval == 'Left'){echo "selected";} ?>><?php echo __( 'Left', OCPC_DOMAIN );?></option>
                                                        <option value="Right"  <?php if($postdateposition_dval == 'Right'){echo "selected";} ?>><?php echo __( 'Right', OCPC_DOMAIN );?></option>
                                                    </select>
                                                </li>
                                                <li><label><?php echo __( 'Font Weight', OCPC_DOMAIN );?></label></li>
                                                <li><select name="ocpc-postdatefweight">
                                                        <option value="Normal"  <?php if($postdatedata['ocpc_postdatefweight'] == 'Normal'){echo "selected";} ?>><?php echo __( 'Normal', OCPC_DOMAIN );?></option>
                                                        <option value="Bold"  <?php if($postdatedata['ocpc_postdatefweight'] == 'Bold'){echo "selected";} ?>><?php echo __( 'Bold', OCPC_DOMAIN );?></option>
                                                    </select>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>




                                <tr>
                                    <th>
                                        <input type="checkbox" name="ocpc-postauthor" value="yes" <?php if($ocpc_postauthor_val == 'yes'){echo "checked";} ?>> <?php echo __( 'Post Author', OCPC_DOMAIN );?>
                                        <p class="ocpc-tips"><?php echo __( 'Show the Post Author.', OCPC_DOMAIN );?></p>
                                    </th>
                                    <td>
                                        <div class="post_option" id="post_author_option" style="display: none">
                                            <ul>
                                                <?php
                                                $defultvalue_postauthorcolor = $postauthordata['ocpc_postauthorcolor'];
                                                if(!empty($defultvalue_postauthorcolor))
                                                {
                                                    $postauthorcolor_dval = $defultvalue_postauthorcolor;
                                                }
                                                else
                                                {
                                                    $postauthorcolor_dval = '#a5a4ae';
                                                }
                                                ?>
                                                <li><label><?php echo __( 'Color', OCPC_DOMAIN );?></label></li>
                                                <li><input type="color" name="ocpc-postauthorcolor" value="<?php echo $postauthorcolor_dval; ?>"></li>
                                                <li><label><?php echo __( 'Font Size', OCPC_DOMAIN );?></label></li>
                                                <li><input type="number" name="ocpc-postauthorfontsize" placeholder="eg. 30" value="<?php echo $postauthordata['ocpc_postauthorfontsize']; ?>"></li>
                                                <li><label><?php echo __( 'Position', OCPC_DOMAIN );?></label></li>
                                                <?php
                                                $defultvalue_postauthorposition = $postauthordata['ocpc_postauthorposition'];
                                                if(!empty($defultvalue_postauthorposition))
                                                {
                                                    $postauthorposition_dval = $defultvalue_postauthorposition;
                                                }
                                                else
                                                {
                                                    $postauthorposition_dval = 'Left';
                                                }
                                                ?>
                                                <li><select name="ocpc-postauthorposition" >
                                                        <option value="Center"  <?php if($postauthorposition_dval == 'Center'){echo "selected";} ?>><?php echo __( 'Center', OCPC_DOMAIN );?></option>
                                                        <option value="Left"  <?php if($postauthorposition_dval == 'Left'){echo "selected";} ?>><?php echo __( 'Left', OCPC_DOMAIN );?></option>
                                                        <option value="Right"  <?php if($postauthorposition_dval == 'Right'){echo "selected";} ?>><?php echo __( 'Right', OCPC_DOMAIN );?></option>
                                                    </select>
                                                </li>
                                                <li><label><?php echo __( 'Font Weight', OCPC_DOMAIN );?></label></li>
                                                <li><select name="ocpc-postauthorfweight">
                                                        <option value="Normal"  <?php if($postauthordata['ocpc_postauthorfweight'] == 'Normal'){echo "selected";} ?>><?php echo __( 'Normal', OCPC_DOMAIN );?></option>
                                                        <option value="Bold"  <?php if($postauthordata['ocpc_postauthorfweight'] == 'Bold'){echo "selected";} ?>><?php echo __( 'Bold', OCPC_DOMAIN );?></option>
                                                    </select>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                	<th>
                                        <input type="checkbox" name="ocpc-postdescription" value="yes" <?php if($ocpc_postdescription_val == 'yes'){echo "checked";} ?>> <?php echo __( 'Post Description', OCPC_DOMAIN );?>
                                        <p class="ocpc-tips"><?php echo __( 'Show the Post Description.', OCPC_DOMAIN );?></p>
                                    </th>
                                    <td>
                                        <div class="post_option" id="post_description_option" style="display: none">
                                            <ul>
                                                <li><label><?php echo __( 'Color', OCPC_DOMAIN );?></label></li>
                                                <li><input type="color" name="ocpc-postdescriptioncolor" value="<?php echo $postdescriptiondata['ocpc_postdescriptioncolor']; ?>"></li>
                                                <li><label><?php echo __( 'Font Size', OCPC_DOMAIN );?></label></li>
                                                <li><input type="number" name="ocpc-postdescriptionfontsize" placeholder="eg. 30" value="<?php echo $postdescriptiondata['ocpc_postdescriptionfontsize']; ?>"></li>
                                                <li><label><?php echo __( 'Position', OCPC_DOMAIN );?></label></li>
                                                <?php
                                                $defultvalue_postdescriptionposition = $postdescriptiondata['ocpc_postdescriptionposition'];
                                                if(!empty($defultvalue_postdescriptionposition))
                                                {
                                                    $postdescriptionposition_dval = $defultvalue_postdescriptionposition;
                                                }
                                                else
                                                {
                                                    $postdescriptionposition_dval = 'Left';
                                                }
                                                ?>
                                                <li><select name="ocpc-postdescriptionposition" >
                                                        <option value="Center"  <?php if($postdescriptionposition_dval == 'Center'){echo "selected";} ?>><?php echo __( 'Center', OCPC_DOMAIN );?></option>
                                                        <option value="Left"  <?php if($postdescriptionposition_dval == 'Left'){echo "selected";} ?>><?php echo __( 'Left', OCPC_DOMAIN );?></option>
                                                        <option value="Right"  <?php if($postdescriptionposition_dval == 'Right'){echo "selected";} ?>><?php echo __( 'Right', OCPC_DOMAIN );?></option>
                                                    </select>
                                                </li>
                                                <li><label><?php echo __( 'Font Weight', OCPC_DOMAIN );?></label></li>
                                                <li><select name="ocpc-postdescriptionfweight">
                                                        <option value="Normal"  <?php if($postdescriptiondata['ocpc_postdescriptionfweight'] == 'Center'){echo "selected";} ?>><?php echo __( 'Normal', OCPC_DOMAIN );?></option>
                                                        <option value="Bold"  <?php if($postdescriptiondata['ocpc_postdescriptionfweight'] == 'Center'){echo "selected";} ?>><?php echo __( 'Bold', OCPC_DOMAIN );?></option>
                                                    </select>
                                                </li>
                                                <li><label><?php echo __( 'Post Description Length', OCPC_DOMAIN );?></label></li>
                                                <li> 
                                                    <input type="number" name="ocpc-postdesclength" placeholder="eg. 15" value="<?php echo get_post_meta( $post->ID, 'ocpc-postdesclength', true ); ?>">
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">
                                        <label><?php echo __( 'Read More Text', OCPC_DOMAIN );?></label>
                                    </th>
                                    <td>
                                        <input type="text" name="ocpc-readmoretext" placeholder="read more" value="<?php echo get_post_meta( $post->ID, 'ocpc-readmoretext', true ); ?>">
                                        <p class="ocpc-tips"><?php echo __( 'Add Read More Text', OCPC_DOMAIN );?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">
                                        <label><?php echo __( 'Read More Text Color', OCPC_DOMAIN );?></label>
                                    </th>
                                    <td>
                                        <?php
                                        if(empty(get_post_meta( $post->ID, 'ocpc-readmoretextcolor', true ))){
                                             $readmorecolor = '#0a2dda';
                                        } else {
                                            $readmorecolor = get_post_meta( $post->ID, 'ocpc-readmoretextcolor', true );
                                        }
                                        ?>
                                        <input type="color" name="ocpc-readmoretextcolor" value="<?php echo $readmorecolor; ?>">
                                        <p class="ocpc-tips"><?php echo __( 'Add Read More Text Color', OCPC_DOMAIN );?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">
                                        <label><?php echo __( 'Upload Placeholder image', OCPC_DOMAIN );?></label>
                                    </th>
                                    <td>
                                        <div class="get_postpro">
                                            <input type="file" name="ocpc-image">
                                            
                                            
                                            
                                        </div>
                                        <a href="https://www.xeeshop.com/product/post-slider-carousel-custom-post-grids-pro/" class="getpost_pro" target="_blank">Get Pro</a>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </fieldset>
                </div>
               
                <div id="tab-template" class="tab-content">
                    <fieldset>
                        <table class="form-table">
                            <tr id="ocpc-showpagination">
                                <th>
                                    <label><?php echo __( 'Show Pagination', OCPC_DOMAIN );?></label>
                                </th>
                                <td>
                                    <?php $pagenat = get_post_meta( $post->ID, 'ocpc-showpagination', true ); ?>
                                    <input type="checkbox" name="ocpc-showpagination" <?php if($pagenat == "on"){ echo "checked"; } ?>>
                                    
                                </td>
                            </tr>
                            <tr id="ocpc-totalposts" style="display: none;">
                                <th>
                                    <label><?php echo __( 'Total Posts', OCPC_DOMAIN );?></label>
                                </th>
                                <td>
                                    <input type="text" name="ocpc-totalposts" placeholder="eg.4" value="<?php echo get_post_meta( $post->ID, 'ocpc-totalposts', true ); ?>">
                                    
                                </td>
                            </tr>
                            <tr id="ocpc-perpage">
                                <th>
                                    <label><?php echo __( 'Posts Per Page', OCPC_DOMAIN );?></label>
                                </th>
                                <td>
                                    <input type="text" name="ocpc-perpage" placeholder="eg.6" value="<?php echo get_post_meta( $post->ID, 'ocpc-perpage', true ); ?>">
                                    
                                </td>
                            </tr>
                            <tr class="space_betwwen">
                                    <th>Space between images</th>  
                                    <td>
                                        <input type="number" name="ocpc_gl_space_img" class="insta_space_img" value="<?php if(!empty(get_post_meta( $post->ID, 'ocpc_gl_space_img', true ))){ echo get_post_meta( $post->ID, 'ocpc_gl_space_img', true ); }else{ echo "5"; }?>">
                                    </td>
                                </tr>
                            <tr>
                                <th>
                                    <label><?php echo __( 'Template', OCPC_DOMAIN );?></label>
                                </th>
                                <td>
                                    <input type="radio" name="sel_template" value="tem1" <?php if(get_post_meta( $post->ID, 'ocpc-template', true ) == "tem1"){ echo "checked"; } ?>>Template 1
                                    <input type="radio" name="sel_template" value="tem2" <?php if(get_post_meta( $post->ID, 'ocpc-template', true ) == "tem2"){ echo "checked"; } ?>>Template 2
                                    <input type="radio" name="sel_template" value="tem3" <?php if(get_post_meta( $post->ID, 'ocpc-template', true ) == "tem3"){ echo "checked"; } ?>>Template 3
                                    <div class="get_postpro">
                                        <input type="radio" name="sel_template" value="tem4" <?php if(get_post_meta( $post->ID, 'ocpc-template', true ) == "tem4"){ echo "checked"; } ?>>Template 4
                                        <input type="radio" name="sel_template" value="tem5" <?php if(get_post_meta( $post->ID, 'ocpc-template', true ) == "tem5"){ echo "checked"; } ?>>Template 5
                                    </div>
                                    <a href="https://www.xeeshop.com/product/post-slider-carousel-custom-post-grids-pro/" class="getpost_pro" target="_blank">Get Pro</a>
                                </td>
                            </tr>
                        </table>
                        <table class="form-table">
                        	<tr>
                                <td>
                                    <label class="radio">
                                        <input type="radio" name="ocpc_option" id="radiogallery" value="gallery" class="ocpc_option with-gap" <?php if(get_post_meta( $post->ID, 'ocpc-option', true ) == "gallery" || empty(get_post_meta( $post->ID, 'ocpc-option', true ))) { echo 'checked'; } ?>>
                                        <span>Gallery</span>
                                    </label>
                                    <label class="radio">
                                        <input type="radio" name="ocpc_option" id="radioslider" value="carousel" class="ocpc_option" <?php if(get_post_meta( $post->ID, 'ocpc-option', true ) == "carousel" ) { echo 'checked'; } ?>>
                                        <span>Carousel</span>
                                    </label>
                                    <div class="get_postpro">
                                        <label class="radio">
                                            <input type="radio" name="ocpc_option" id="radiomasonry" value="masonry" class="ocpc_option" <?php if(get_post_meta( $post->ID, 'ocpc-option', true ) == "masonry" ) { echo 'checked'; } ?>>
                                            <span>Masonry</span>
                                        </label>
                                    </div>
                                    <a href="https://www.xeeshop.com/product/post-slider-carousel-custom-post-grids-pro/" class="getpost_pro" target="_blank">Get Pro</a>
    							</td>
                            </tr>
                        </table>
                        <div class="gallery">
                            <table class="form-table">
                                <tr>
                                    <td><h3>Gallery Setting</h3></td>
                                </tr>
                                <tr class="columns"> 
                                    <td>Columns</td>
                                    <td>
                                        <input type="number" name="ocpc_gl_clm" value="<?php if(!empty(get_post_meta( $post->ID, 'ocpc_gl_clm', true ))){ echo get_post_meta( $post->ID, 'ocpc_gl_clm', true ); }else{ echo "3"; }?>">
                                    </td>
                                </tr>
                                
                                
                                
                            </table>
                        </div>
                        <div class="carousel" style="display: none;">
                            <table class="form-table">
                                <tr>
                                    <td><h3>Carousel Setting</h3></td>
                                </tr>
                                <tr>
                                    <td>
                                        <label><?php echo __( 'Slides per row', OCPC_DOMAIN );?></label>
                                    </td>
                                    <td>
                                        <input type="text" name="ocpc-perrow" placeholder="eg.3" min='3' value="<?php echo get_post_meta( $post->ID, 'ocpc-perrow', true ); ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label><?php echo __( 'Auto Play', OCPC_DOMAIN );?></label>
                                    </td>
                                    <td>
                                        <select name="ocpc-autoplay">
                                            <option value="true"  <?php if(get_post_meta( $post->ID, 'ocpc-autoplay', true ) == 'true'){echo "selected";} ?>><?php echo __( 'Yes', OCPC_DOMAIN );?></option>
                                            <option value="false"  <?php if(get_post_meta( $post->ID, 'ocpc-autoplay', true ) == 'false'){echo "selected";} ?>><?php echo __( 'No', OCPC_DOMAIN );?></option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label><?php echo __( 'Auto Play Timeout', OCPC_DOMAIN );?></label>
                                    </td>
                                    <td>
                                        <input type="text" name="ocpc-autoplaytimeout" placeholder="eg.1000" value="<?php echo get_post_meta( $post->ID, 'ocpc-autoplaytimeout', true ); ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label><?php echo __( 'Auto Play Hover Pause', OCPC_DOMAIN );?></label>
                                    </td>
                                    <td>
                                        <select name="ocpc-autoplayhoverpause">
                                            <option value="true"  <?php if(get_post_meta( $post->ID, 'ocpc-autoplayhoverpause', true ) == 'true'){echo "selected";} ?>><?php echo __( 'Yes', OCPC_DOMAIN );?></option>
                                            <option value="false"  <?php if(get_post_meta( $post->ID, 'ocpc-autoplayhoverpause', true ) == 'false'){echo "selected";} ?>><?php echo __( 'No', OCPC_DOMAIN );?></option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label><?php echo __( 'Navigation arrows', OCPC_DOMAIN );?></label>
                                    </td>
                                    <td>
                                        <select name="ocpc-sliderarrow">
                                            <option value="true"  <?php if(get_post_meta( $post->ID, 'ocpc-sliderarrow', true ) == 'true'){echo "selected";} ?>><?php echo __( 'Yes', OCPC_DOMAIN );?></option>
                                            <option value="false"  <?php if(get_post_meta( $post->ID, 'ocpc-sliderarrow', true ) == 'false'){echo "selected";} ?>><?php echo __( 'No', OCPC_DOMAIN );?></option>
                                        </select>
                                    </td>
                                </tr>
                               	<tr>
                                    <td>
                                        <label><?php echo __( 'Mobile Per Row', OCPC_DOMAIN );?></label>
                                    </td>
                                    <td>
                                        <div class="get_postpro">
                                            <input type="text" name="ocpc-mobileperrow" placeholder="eg.1" min='1' value="<?php echo get_post_meta( $post->ID, 'ocpc-mobileperrow', true ); ?>">
                                            <p class="ocpc-tips"><?php echo __( 'Per Row must be 1 and greater than 1', OCPC_DOMAIN );?></p>
                                        </div>
                                        <a href="https://www.xeeshop.com/product/post-slider-carousel-custom-post-grids-pro/" class="getpost_pro" target="_blank">Get Pro</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label><?php echo __( 'Tablet Per Row', OCPC_DOMAIN );?></label>
                                    </td>
                                    <td>
                                        <div class="get_postpro">
                                            <input type="text" name="ocpc-tabletperrow" placeholder="eg.2" min='2' value="<?php echo get_post_meta( $post->ID, 'ocpc-tabletperrow', true ); ?>">
                                            <p class="ocpc-tips"><?php echo __( 'Per Row must be 2 and greater than 2', OCPC_DOMAIN );?></p>
                                        </div>
                                        <a href="https://www.xeeshop.com/product/post-slider-carousel-custom-post-grids-pro/" class="getpost_pro" target="_blank">Get Pro</a>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="masonry">
                            <table  class="form-table">
                                <tr>
                                    <td><h3>Masonry Setting</h3></td>
                                </tr>
                                
                                <tr> 
                                    <td>Columns</td>
                                    <td>
                                        <input type="number" name="ocpc_ms_clm" value="<?php if(!empty(get_post_meta( $post->ID, 'ocpc_ms_clm', true ))){ echo get_post_meta( $post->ID, 'ocpc_ms_clm', true ); }else{ echo "3"; }?>">
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </fieldset>
                </div>
            </div>
            <?php
        }

        //Upload Background Image function
       
        //Modify Post columns

        function OCPC_add_new_columns($new_columns){
            $new_columns = array();
            $new_columns['cb']   = '<input type="checkbox" />';
            $new_columns['title']   = esc_html__('Name', OCPC_DOMAIN);
            $new_columns['shortcode']   = esc_html__('Shortcode', OCPC_DOMAIN);
            $new_columns['date']   = esc_html__('Created at', OCPC_DOMAIN);
            return $new_columns;
        }

        //Add shortcode column
        function OCPC_manage_custom_columns( $column_name, $post_id ) {

            switch($column_name){
                case 'shortcode': ?>
                    <input type="text" id="ocpc-selectdata_<?php echo $post_id;?>" value="[ocpc-post-carousel id=<?php echo $post_id;?>]" size="30" onclick="ocpc_select_data(this.id)" readonly>
                <?php
                break;
                default:
                break;

            }
        }

        //Load media function
        function OCPC_load_media_files() {
            wp_enqueue_media();
        }

        //Change Post Source Option by post type
        function OCPC_posttypedata_method() {
            $posttype = $_REQUEST['posttype'];
            if($posttype == 'post' || $posttype == 'product') {
	            ?>
	                <th scope="row">
	                    <label><?php echo __( 'Select Data Source', OCPC_DOMAIN );?></label>
	                </th>
	                <td>
	                    <select name="ocpc-datasource" class="ocpc-datasource">
	                        <option value=""> <?php echo __( '--- Select Options ----', OCPC_DOMAIN );?> </option>
	                        <option value="ocpc-op_categories"><?php echo __( 'Categories', OCPC_DOMAIN );?></option>
	                        <option value="ocpc-op_id"><?php echo __( 'ID', OCPC_DOMAIN );?></option>
	                        <option value="ocpc-op_tags"><?php echo __( 'Tags', OCPC_DOMAIN );?></option>
	                    </select>
	                </td>
	        	<?php 
            } else if($posttype == 'page') { 
            	?>
	                <th scope="row">
	                    <label><?php echo __( 'Select Data Source', OCPC_DOMAIN );?></label>
	                </th>
	                <td>
	                    <select name="ocpc-datasource" class="ocpc-datasource">
	                        <option value=""> <?php echo __( '--- Select Options ----', OCPC_DOMAIN );?> </option>
	                        <option value="ocpc-op_id"><?php echo __( 'ID', OCPC_DOMAIN );?></option>
	                    </select>
	                </td>
            	<?php
            }
            exit();
        }

        //Change Post type wise post Categories and tag value
        function OCPC_cat_tag_data_method() {
            $post_type = $_REQUEST['post_type'];
            $source_val = $_REQUEST['source_val'];
            if($post_type == 'post' && $source_val == 'ocpc-op_categories') {
            ?>
                <th scope="row">
                    <label><?php echo __( 'Categories', OCPC_DOMAIN );?></label>
                </th>
                <td>
                    <select multiple name="ocpc-postcategories[]">
                        <option value=""> <?php echo __( '--- Select Categories ----', OCPC_DOMAIN );?> </option>
                            <?php
                                $ocpc_categories = get_categories( array(
                                    'orderby' => 'name',
                                    'order'   => 'ASC'
                                ) );

                        foreach( $ocpc_categories as $ocpc_category ) { ?>
                        <option value="<?php echo $ocpc_category->term_id;?>"><?php echo $ocpc_category->name;?></option>
                        <?php } ?>
                    </select>
                     <p class="ocpc-tips"><?php echo __( 'Note: Press and hold Ctrl Key and select more than one item from the list.', OCPC_DOMAIN );?></p>
                </td>
            <?php 
            } else if($post_type == 'product' && $source_val == 'ocpc-op_categories') {
            ?>
                <th scope="row">
                    <label><?php echo __( 'Categories', OCPC_DOMAIN );?></label>
                </th>
                <td>
                    <select multiple name="ocpc-postcategories[]">
                        <option value=""> <?php echo __( '--- Select Categories ----', OCPC_DOMAIN );?> </option>
                            <?php
                                $orderby = 'name';
                                $order = 'asc';
                                $hide_empty = false ;
                                $cat_args = array(
                                    'orderby'    => $orderby,
                                    'order'      => $order,
                                    'hide_empty' => $hide_empty,
                                );
                                
                                $ocpc_categories = get_terms( 'product_cat', $cat_args );
                        foreach( $ocpc_categories as $ocpc_category ) { ?>
                        <option value="<?php echo $ocpc_category->term_id;?>"><?php echo $ocpc_category->name;?></option>
                        <?php } ?>
                    </select>
                     <p class="ocpc-tips"><?php echo __( 'Note: Press and hold Ctrl Key and select more than one item from the list.', OCPC_DOMAIN );?></p>
                </td>
            <?php
            } else if($post_type == 'post' && $source_val == 'ocpc-op_tags') {
            ?>
                <th scope="row">
                    <label><?php echo __( 'Tags', OCPC_DOMAIN );?></label>
                </th>
                <td>
                    <select multiple name="ocpc-posttags[]">
                        <option value=""> <?php echo __( '--- Select Tags ----', OCPC_DOMAIN );?> </option>
                        <?php   $ocpc_tags = get_tags();
                                foreach( $ocpc_tags as $ocpc_tag ) { ?>
                        <option value="<?php echo $ocpc_tag->term_id;?>"><?php echo $ocpc_tag->name;?></option>
                                            <?php } ?>
                    </select>
                    <p class="ocpc-tips"><?php echo __( 'Note: Press and hold Ctrl Key and select more than one item from the list.', OCPC_DOMAIN );?></p>
                </td>
            <?php    
            } else if($post_type == 'product' && $source_val == 'ocpc-op_tags') {
            ?>
                <th scope="row">
                    <label><?php echo __( 'Tags', OCPC_DOMAIN );?></label>
                </th>
                <td>
                    <select multiple name="ocpc-posttags[]">
                        <option value=""> <?php echo __( '--- Select Tags ----', OCPC_DOMAIN );?> </option>
                        <?php   $ocpc_tags = $terms = get_terms(array('taxonomy' => 'product_tag', 'hide_empty' => false));
                                foreach( $ocpc_tags as $ocpc_tag ) { ?>
                        <option value="<?php echo $ocpc_tag->term_id;?>"><?php echo $ocpc_tag->name;?></option>
                                            <?php } ?>
                    </select>
                    <p class="ocpc-tips"><?php echo __( 'Note: Press and hold Ctrl Key and select more than one item from the list.', OCPC_DOMAIN );?></p>
                </td>
            <?php    
            }
            exit();
        }


        function OCPC_add_events_metaboxes() {
            add_meta_box(
                'wpt_events_location',
                'Rating',
                array($this, 'wpt_events_location'),
                'ocpostcarousel',
                'side',
                'default'
            );
        }


        function wpt_events_location() {
            global $post;
            // Nonce field to validate form request came from current site
            wp_nonce_field( basename( __FILE__ ), 'event_fields' );
            // Get the location data if it's already been entered
            //$location = get_post_meta( $post->ID, 'location', true );
            // Output the field
            echo '<a href="https://wordpress.org/support/plugin/post-slider-by-oc/reviews/#new-post" target="_blank"><img src="'.OCPC_PLUGIN_DIR.'/asset/images/star.png" class="ocpc_rating_div"></a>';
        }


        function init() {
           add_action('init', array($this, 'OCPC_create_menu'));
           add_action('add_meta_boxes', array($this, 'OCPC_add_meta_box'));
           add_filter('manage_ocpostcarousel_posts_columns', array($this,'OCPC_add_new_columns'));
           add_action('manage_ocpostcarousel_posts_custom_column', array($this, 'OCPC_manage_custom_columns'), 10, 2);
           add_action('admin_enqueue_scripts', array($this,'OCPC_load_media_files'));
           add_action( 'wp_ajax_posttypedata', array($this,'OCPC_posttypedata_method'));
           add_action( 'wp_ajax_nopriv_posttypedata', array($this,'OCPC_posttypedata_method'));
           add_action( 'wp_ajax_cat_tag_data', array($this,'OCPC_cat_tag_data_method'));
           add_action( 'wp_ajax_nopriv_cat_tag_data', array($this,'OCPC_cat_tag_data_method'));
           add_action( 'add_meta_boxes', array($this,'OCPC_add_events_metaboxes'));
        }

        public static function instance() {
          if (!isset(self::$instance)) {
            self::$instance = new self();
            self::$instance->init();
          }
          return self::$instance;
        }

    }
    OCPC_menu::instance();
}

