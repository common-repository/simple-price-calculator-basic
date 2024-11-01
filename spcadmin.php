<?php

class SimplePriceCalcAdmin {

//Admin Panel Functions

	public function __construct() {
	
		add_action('init', array($this, 'simple_admin_panel'));
		add_action( 'admin_init', array($this,'admin_panel_meta' ));
		add_filter( 'manage_edit-simple_price_calc_columns', array( $this, 'admin_table_columns' ) );
		add_action('manage_simple_price_calc_posts_custom_column', array( $this, 'admin_table_columns_data'), 10, 2 );
		add_action('edit_form_after_title', array($this,'default_form_content'));
		add_filter('post_updated_messages', array($this,'admin_updated_messages' ));
		add_action('admin_menu', array($this,'simple_price_calc_support_page'));
	}

  function simple_admin_panel() {
		register_post_type('simple_price_calc', array(
				'labels' => array(
				'name' => 'Simple Price Calculator Forms' ,
				'singular_name' =>  'Simple Price Calculator Form',
				'add_new_item' => 'Add New Form' ,
				'edit_item' => 'Edit Form'
				),
				'public' => false,
				'rewrite' => false,
				'has_archive' => true,
				'menu_position' => 100,
				'menu_icon' => 'dashicons-media-text',
				'show_ui' => true
			)
		);
	}
	
	function admin_panel_meta() {
		add_meta_box( 'spc_formtag_meta_box', 'Form Tag Generator', array($this,'admin_formtag_box'), 'simple_price_calc', 'side', 'default');
		add_meta_box( 'spc_mail_meta_box', 'Optional Price Form Settings', array($this,'admin_form_settings'), 'simple_price_calc', 'normal', 'default');			
	}
	
	function admin_formtag_box( ) {
		
		include('formgencode.php'); 
	}
	
	function admin_form_settings($post) {
		?>
<style type="text/css">
#spc-admin-panel label {width:200px; display:inline-block;}
#spc-admin-panel .spcrow {margin:10px 0;}
</style>
	<div id="spc-admin-panel">
		<h3>Functions below available in premium version.</h3> <a href="http://shop.premiumbizthemes.com/?download=simple-price-calculator-wordpress-version" target="_blank"> Click here to download premium version </a> <br /> <br />

		<h3> Main Form Settings </h3>
		<div class="spcrow">
			<label> Form Currency Symbol: </label>
			<select name="spc_currency_setting" disabled>
				<option value="dollar">$ </option>
				<option value="euro">&euro; </option>
			</select> 
		</div>
		<div class="spcrow">
			<label> Total Box Location:  </label>
			<select name="spc_totalbox_setting" disabled>
				<option value="right"> Right Side of Screen</option>
				<option value="below"> Below Form </option>
			</select> 
		</div>
		<div class="spcrow">
			<label> Total Label Text: </label> <input type="text" name="spc_totallabel_setting" placeholder="Total:" value="" disabled/>
		</div>
		<div class="spcrow">
			<label>Details Label Text:</label> <input type="text" name="spc_detailslabel_setting" placeholder="Details:" value="" disabled/>
		</div>

		<h3> E-mail Settings </h3>
		<input type="checkbox" name="spc_email_func" value="1" disabled/>
Add e-mail functionality to form?
		<div class="spcrow">
			<label>E-mail to:</label>
			<input type="email" name="spc_email" value="<?php echo get_bloginfo('admin_email'); ?>" disabled/> 
		</div>
		<div class="spcrow">
			<label>E-mail Subject: </label> 
			<input type="text" name="spc_email_subject" placeholder="Price Quote" disabled /> 
		</div>
		<div class="spcrow">
			<label>Thank You Message:</label> <br />
			<input type="text" name="spc_thankyou_message" placeholder="Displays after successful form submission" value="" style="width:50%;" disabled/>
		</div>
		<div class="spcrow">
			<label>E-mail Message:</label> <br />
			<textarea name="spc_email_message" placeholder="Message to display above price quote (optional)"  rows="5" cols="50"  disabled /></textarea>
		</div>
		<input type="checkbox" name="spc_email_admin" value="1" disabled />
		Send copy of quote to visitors e-mail address? (<strong>Default: Only send a copy to e-mail above</strong>)    <br />
</div>
		
	<?php
	}
	
	function admin_table_columns($columns) {
		$columns['shortcode'] = 'Shortcode';
		$columns['email'] = 'Email';
		
		return $columns;
	}
	
	function admin_table_columns_data($column,$post_id) {
	
		switch($column){
			case 'shortcode':			
			if($post_id)			
			echo "[spc-form id=" . $post_id . "]";
			break;		
			
			case 'email':			
			$savedemail='No e-mail specified';
			echo $savedemail;
			break;		

			default:
			echo $column . $post_id;			
		}
	}
	
	
	//Displays default form content if post is empty
	
	function default_form_content() {
		global $post;
		if ($post->post_type == 'simple_price_calc'  && $post->post_content == '') {
			
			$sampformcontent='
			<h2> Sample Heading </h2>
			<select>
				<option> Choose Option Type  </option> 
				<option> Basic Type </option>
				<option> Medium Type </option>
				<option> Advanced Type </option>
			</select> 
			
			<br />			
            <br />
			
			<h4> Sample Checkbox Settings </h4>
             
			<input type="checkbox" value="10"> Sample Checkbox 1
			<input type="checkbox" value="12"> Sample Checkbox 2
			<input type="checkbox" value="14"> Sample Checkbox 3			
			
			<br />
		    <br />
			
			<h4> Sample Radio Settings </h4>
			<input type="radio" name="css" value="0"> None <br />
			<input type="radio" name="css" value="5"> Radio Setting 1  <br />
			<input type="radio" name="css" value="10"> Radio Setting 2  <br />			
			
             <br />';
			
			$post->post_content = $sampformcontent;		
		}
    
	}		

	function admin_updated_messages( $messages ) {
		$messages['simple_price_calc'] = array(
			1  => sprintf(__( 'Form updated. <a href="%s">View Shortcode</a>' ), esc_url(admin_url('edit.php?post_type=simple_price_calc') ) ) ,
			6  => sprintf(__( 'Form published. <a href="%s">View Shortcode</a>' ), esc_url(admin_url('edit.php?post_type=simple_price_calc') ) ),
			7  => __ ('Form saved.' ),
			10  => __ ('Form draft updated.' )
		);
		return $messages;
	}
	
		// Adds a submenu page under a custom post type parent.
 	
	function simple_price_calc_support_page() {
		add_submenu_page(
			'edit.php?post_type=simple_price_calc',
			__( 'Simple Price Calculator FAQ', 'textdomain' ),
			__( 'FAQ', 'textdomain' ),
			'manage_options',
			'simple-price-calc-support',
			array($this,'support_page_callback')
		);
	}
 

	// Display callback for the submenu page.
	
	function support_page_callback() { 
		?>
		
		<div class="wrap">
			<h1 style="margin-bottom:10px;">Simple Price Calculator FAQ</h1>

	         <div style="background:white; padding:10px 20px;">
			<p>
				<h3>How do I create a new form?</h3>

				<ol>
					<li><p>Once the plugin is activated, click the Simple Price Calculator Forms tab in your WordPress admin panel menu and then the "Add New" link.</p></li>
					<li><p>A new post will appear with an example form that you can edit.</p></li>
					<li><p>Once you are done making changes, click publish to save.</p></li>
				</ol>

				<h3>How do I add my form to a post or page?</h3>

				<ol>
					<li><p>Click on the Simple Calculator Forms Tab.</p></li>
					<li><p>Under the Shortcode column for your form, you should see a shortcode that you can copy and paste into a WordPress post or page for the form to appear.</p></li>
				</ol>

				<h3>What HTML attributes can the form add?</h3>

				<p>The basic version of this form can only add checkbox or radio buttons. <strong><a href="http://shop.premiumbizthemes.com/?download=simple-price-calculator-wordpress-version" target="blank" rel="nofollow">Download the premium version</a></strong> for a wider variety.</p>

				<h3>How can my form values be calculated?</h3>

				<ol>
					<li><p>Create a new checkbox or radio button (If unsure how to do this, just follow sample post and edit).</p></li>
					<li><p>Add a new value attribute for checkbox or radio button and it will automatically be added to total when clicked.</p></li>
				</ol>

				<p>Ex: &lt;input type="checkbox" value="14"&gt; or &lt;input type="radio" value="10"&gt;</p>

				<p>Note: If you wish to group a bunch of radio buttons together and want them to switch, make sure each radio has the same name attribute.</p>

				<h4> Where can I download the premium version? </h4>

				<p> If you are looking for more features, I would recommend purchasing the premium version. It provides the ability to use more html elements in pricing, the ability to e-mail the form along with details of each element that has been selected, a form generator and more!  </p>

				<p>
					<strong><a href="http://shop.premiumbizthemes.com/?download=simple-price-calculator-wordpress-version"  target="blank" rel="nofollow"> Click to download premium version  </a></strong>
				</p>

				<h3>What is the difference between the basic and premium version?</h3>

				<p>With the premium version, your form details can be generated dynamically along with pricing. It also includes the ability to e-mail the form w/ details, a form generator, detailed documentation and increased functionality for more html tags, including the ability to merge two fields and multiply products. </p>

				<p><strong><a href="http://shop.premiumbizthemes.com/?download=simple-price-calculator-wordpress-version" target="blank" rel="nofollow"> Click here for info about the premium version </a></strong></p>

		</p>
	  </div>
	 </div>
		<?php
	}
	
}	

$simplepricecalcadmin= new SimplePriceCalcAdmin();