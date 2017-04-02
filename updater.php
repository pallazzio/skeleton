<?php
class Smashing_Updater2 {
	private $file;
	private $theme;
	private $basename;
	private $active;
	private $username;
	private $repository;
	private $authorize_token;
	private $github_response;

	public function __construct( $file ) {
		$this->file = $file;
		add_action( 'admin_init', array( $this, 'set_theme_properties' ) );
		return $this;
	}

	public function set_theme_properties() {
		//$this->file = '';
		$this->theme = array(
			'Name' => 'Skeleton',
			'ThemeURI' => 'http://pallazzio.com/',
			'Version' => '0.0.0',
			'Description' => 'This is for updating your Wordpress plugin. By Matthew Ray.',
			'Author' => 'Jeremy Kozan',
			'AuthorURI' => 'http://pallazzio.net/',
			'TextDomain' => 'skeleton',
			'Title' => 'Skeleton',
			'AuthorName' => 'Jeremy Kozan'
		);
		$this->basename = 'skeleton/style.css';
		$this->active = 1;
		//echo('<div style="clear:both;"></div><pre style="padding-left:15em;">');print_r($this);echo('</pre><div style="clear:both;"></div>');
	}

	public function set_username( $username ) {
		$this->username = $username;
	}

	public function set_repository( $repository ) {
		$this->repository = $repository;
	}

	public function authorize( $token ) {
		$this->authorize_token = $token;
	}

	public function initialize() {
		add_filter( 'pre_set_site_transient_update_themes', array( $this, 'modify_transient' ), 10, 1 );
		add_filter( 'upgrader_post_install', array( $this, 'after_install' ), 10, 3 );
	}

	public function modify_transient( $transient ) {

		if( property_exists( $transient, 'checked') ) { // Check if transient has a checked property

			if( $checked = $transient->checked ) { // Did Wordpress check for updates?

				$this->get_repository_info(); // Get the repo info

				$out_of_date = version_compare( $this->github_response['tag_name'], $checked[ $this->basename ], 'gt' ); // Check if we're out of date

				if( $out_of_date ) {

					$new_files = $this->github_response['zipball_url']; // Get the ZIP

					$slug = current( explode('/', $this->basename ) ); // Create valid slug

					$theme = array( // setup our plugin info
						'url' => $this->theme["ThemeURI"],
						'slug' => $slug,
						'package' => $new_files,
						'new_version' => $this->github_response['tag_name']
					);

					$transient->response[$this->basename] = (object) $theme; // Return it in response
				}
			}
		}

		return $transient; // Return filtered transient
	}

	public function after_install( $response, $hook_extra, $result ) {
		global $wp_filesystem; // Get global FS object

		$install_directory = get_template_directory(); // Our plugin directory
		$wp_filesystem->move( $result['destination'], $install_directory ); // Move files to the plugin dir
		$result['destination'] = $install_directory; // Set the destination for the rest of the stack

		//if ( $this->active ) { // If it was active
		//	activate_plugin( $this->basename ); // Reactivate
		//}

		return $result;
	}
	
	private function get_repository_info() {
	    if ( is_null( $this->github_response ) ) { // Do we have a response?
	        $request_uri = sprintf( 'https://api.github.com/repos/%s/%s/releases', $this->username, $this->repository ); // Build URI

	        if( $this->authorize_token ) { // Is there an access token?
	            $request_uri = add_query_arg( 'access_token', $this->authorize_token, $request_uri ); // Append it
	        }

	        $response = json_decode( wp_remote_retrieve_body( wp_remote_get( $request_uri ) ), true ); // Get JSON and parse it

	        if( is_array( $response ) ) { // If it is an array
	            $response = current( $response ); // Get the first item
	        }

	        if( $this->authorize_token ) { // Is there an access token?
	            $response['zipball_url'] = add_query_arg( 'access_token', $this->authorize_token, $response['zipball_url'] ); // Update our zip url with token
	        }

	        $this->github_response = $response; // Set it to our property
	    }
	}
}
