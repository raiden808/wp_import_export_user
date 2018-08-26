<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class WPIE_Import{
	//default user meta that is needed
	public $accepted_user_data = array('ID','user_login','user_pass','user_nicename','user_email','user_url','user_registered','display_name');

	public function __construct(){
		add_shortcode( 'wpie_import_user', array($this,'wpie_import_user_callback') );
	}

	public function wpie_import_user_callback(){
		global $wpdb;
		$prefix = $wpdb->prefix;
		?>
		<form action="" method="post" enctype="multipart/form-data">
			<input type="file" accept=".csv" name="csv" value="" />
			<input type="submit" name="submit" value="Save" />
		</form>
		<?php
		//retrieves the array
		if(isset($_POST['submit']))
		{
			$tmpName    = $_FILES['csv']['tmp_name'];
			$csvAsArray = array_map('str_getcsv', file($tmpName));


			// echo "<pre>";
			// print_r($csvAsArray);
			// echo "</pre>";


			//set the header for the array
			$headers    = $csvAsArray[0];

			
			// //replace user_id to ID
			$headers1 = array_replace($headers,
	    	array_fill_keys(
			        array_keys($headers,'user_id'),
			        'ID'
			    )
			);

			echo "<pre>";
			print_r($headers1);
			echo "</pre>";

			//set the contents
			// unset($csvAsArray[0]);
			// $user_meta  = $csvAsArray;

			// //sets and combine array
			// $new_container = $this->combine_header_and_meta($headers,$user_meta);

			// foreach ($new_container as $container) {
			// 	//retrieves standard fields for user
			// 	$accepted_user_data = $this->standard_keys($container);
			// 	//retrieves custom metas
			// 	$accessed_user_meta = $this->user_meta_list($container);

			// 	$fields = $this->accepted_user_data;

			// 	$sql =  "INSERT INTO `".$prefix."users` 
			// 	    (`".implode('`,`', $fields)."`)
			// 	    VALUES('".implode("','", $accepted_user_data)."')";

			// 	if($wpdb->query($sql)) {
			// 		echo "success";
			// 		$u = new WP_User($accepted_user_data['ID']);
			// 		//extract the roles in an array
			// 		foreach (maybe_unserialize($accessed_user_meta[$prefix.'capabilities']) as $key=>$value) {
			// 			$u->add_role( $key );
			// 		}
			// 		//remove capability for the role
			// 		unset($accessed_user_meta[$prefix.'capabilities']);
			// 		//upload each user meta
			// 		foreach ($accessed_user_meta as $key => $value) {
			// 			update_user_meta( $accepted_user_data['ID'],$key,$value);
			// 		}
			// 	}
			// }

		}
	}	
	//default userdata keys
	protected function standard_keys($accepted_array){
		$result_array = array();
		foreach ($accepted_array as $key => $value) {
			# code...
			if(in_array($key, $this->accepted_user_data)){
				$result_array[$key] = $value;
			}
		}

		return $result_array;
	}
	//list of user metas
	protected function user_meta_list($accepted_array){
		$result_array = array();
		foreach ($accepted_array as $key => $value) {
			# code...
			if(!in_array($key, $this->accepted_user_data)){
				$result_array[$key] = $value;
			}
		}
		return $result_array;
	}

	protected function combine_header_and_meta($headers,$user_meta){
		$new_container = array();
		$i = 0;
		foreach ($user_meta as $meta) {
			$new_container[$i] = array_combine($headers,$meta);
			$i++;
		}
		return $new_container;
	}
}