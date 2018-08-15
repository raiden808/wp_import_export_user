<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class WPIE_Export{
	public function __construct() {
		add_action( 'wp', array($this,'generate_csv_callback') );
		add_shortcode( 'wpie_csv_button',array($this,'wpie_csv_button_callback') );
	}

	function wpie_csv_button_callback($atts){
		$a = shortcode_atts( array(
		'role_slug' => ''
		), $atts );

		$role_slug = esc_attr($a['role_slug']);

		?>
		<a href="?print=<?php echo $role_slug; ?>">Export to CSV</a>
		<?php
	}

	function generate_csv_callback(){

		if(isset($_GET['print'])){

			$file_name = $_GET['print']."_".date("Y-m-d").'.csv';

			header('Content-Type: application/csv');
		    header("Content-Disposition: attachment; filename=".$file_name."");
            header('Pragma: no-cache');
            header("Expires: 0");

			$defaults = array( 'content'    => 'all',
			                   'author'     => false,
			                   'category'   => false,
			                   'start_date' => false,
			                   'end_date'   => false,
			                   'status'     => false,
			);

			$user_args = array(
				'role'   => wp_kses_post( $_GET['print'] ),
				'fields' => 'all_with_meta',
			);

			$merge_args = array_merge( $defaults, $user_args );

			$users = get_users( $merge_args );

			$fields = $this->wpie_user_db();

			$headers = $this->users_header($fields);

			$file = fopen('php://output', 'w');

			fputcsv($file, $headers);

			foreach ( $users as $user ) {
				$data = array();
				foreach ( $fields as $field ) {
					$value  = isset( $user->{$field} ) ? $user->{$field} : '';
					$value  = is_array( $value ) ? serialize( $value ) : $value;
					$data[] = $value;
				}
				fputcsv($file, $data);
			}
            exit;
		}
	}

	//replaces id to 'user_id' to avoid conflict
	public function users_header($fields){
		$headers = array();
		foreach ( $fields as $key => $field ) {
			if($field === 'id'){
				$field = 'user_id';
			}
			$headers[] = strtolower( $field );
		}
		foreach($headers as $header=>$head){
			if ($headers[$header] == 'id' ){
				$headers[$header] = 'user_id';
			}
		}
		return $headers;
	}

	//Standard params for creating WP Users
	public function wpie_user_db(){
		global $wpdb;

		$data_keys = array(
			'ID',
			'user_login',
			'user_pass',
			'user_nicename',
			'user_email',
			'user_url',
			'user_registered',
			'user_activation_key',
			'user_status',
			'display_name'
		);
	
		$meta_keys = $wpdb->get_results( "SELECT distinct(meta_key) FROM $wpdb->usermeta" );
		$meta_keys = wp_list_pluck( $meta_keys, 'meta_key' );
		$fields    = array_merge( $data_keys, $meta_keys );

		return $fields;
	}
}



// new WPIE_Export;