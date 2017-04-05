<?php
class Pallazzio_Theme_Updater {
	private $theme;
	private $username;
	private $repository;
	private $authorize_token;
	private $github_response;

	public function __construct( $theme ) {
		$this->theme = $theme;
		add_action( 'admin_init', array( $this, 'set_theme_properties' ) );
		return $this;
	}

	public function set_theme_properties() {
		$this->theme = wp_get_theme( $this->theme );
		
		//echo('<div style="clear:both;"></div><pre style="padding-left:15em;">');print_r($this->theme->template);echo('</pre><div style="clear:both;"></div>');
		//echo('<div style="clear:both;"></div><pre style="padding-left:15em;">');print_r($this->theme->get( 'ThemeURI' ));echo('</pre><div style="clear:both;"></div>');
		//echo('<div style="clear:both;"></div><pre style="padding-left:15em;">');print_r($this);echo('</pre><div style="clear:both;"></div>');
		
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
		add_filter( 'upgrader_pre_install', array( $this, 'before_install' ), 10, 3 );
		add_filter( 'upgrader_post_install', array( $this, 'after_install' ), 10, 3 );
	}

	public function modify_transient( $transient ) {
		if( property_exists( $transient, 'checked') ) { // Check if transient has a checked property

			if( $checked = $transient->checked ) { // Did Wordpress check for updates?


				$this->get_repository_info(); // Get the repo info

				$out_of_date = version_compare( $this->github_response['tag_name'], $checked[ $this->theme->template ], 'gt' ); // Check if we're out of date

				if( $out_of_date ) {

					$new_files = $this->github_response['zipball_url']; // Get the ZIP

					//$slug = current( explode('/', $this->basename ) ); // Create valid slug

					$theme = array( // setup our theme info
						'theme' => $this->theme->template,
						'url' => $this->theme->get( 'ThemeURI' ),
						'slug' => $this->theme->template,
						'package' => $new_files,
						'new_version' => $this->github_response['tag_name']
					);

					$transient->response[$this->theme->template] = $theme; // Return it in response
					
					//echo('<div style="clear:both;"></div><pre style="padding-left:15em;">');print_r($transient);echo('</pre><div style="clear:both;"></div>');

				}
			}
		}

		return $transient; // Return filtered transient
	}

	public function before_install( $response, $hook_extra ) {
		//global $wp_filesystem; // Get global FS object
		
		write_log('pppppppppppppppppp');
		write_log($response);
		//write_log($hook_extra);
		//write_log($result);

		$install_directory = get_template_directory(); // Our theme directory
		//$result['destination_name'] = $this->theme->template; // Set the destination name for the rest of the stack
		//$result['remote_destination'] = $install_directory; // Set the remote destination for the rest of the stack
		//$wp_filesystem->move( $result['destination'], $install_directory ); // Move files to the theme dir
		//$result['destination'] = $install_directory; // Set the destination for the rest of the stack
		
		write_log($response);
		//write_log($hook_extra);
		//write_log($result);

		return $response;
	}

	public function after_install( $response, $hook_extra, $result ) {
		global $wp_filesystem; // Get global FS object
		
		write_log($response);
		write_log($hook_extra);
		write_log($result);

		$install_directory = get_template_directory(); // Our theme directory
		$result['destination_name'] = $this->theme->template; // Set the destination name for the rest of the stack
		$result['remote_destination'] = $install_directory; // Set the remote destination for the rest of the stack
		$wp_filesystem->move( $result['destination'], $install_directory ); // Move files to the theme dir
		$result['destination'] = $install_directory; // Set the destination for the rest of the stack
		
		switch_theme( $this->theme->template );
		
		write_log($response);
		write_log($hook_extra);
		write_log($result);

		return $result;
	}
	
	private function get_repository_info() {
		//echo('<pre style="padding-left:15em;">getting...</pre><br />');
		if ( is_null( $this->github_response ) ) { // Do we have a response?
				$request_uri = sprintf( 'https://api.github.com/repos/%s/%s/releases', $this->username, $this->repository ); // Build URI

				if( $this->authorize_token ) { // Is there an access token?
						$request_uri = add_query_arg( 'access_token', $this->authorize_token, $request_uri ); // Append it
				}

				$response = json_decode( wp_remote_retrieve_body( wp_remote_get( $request_uri ) ), true ); // Get JSON and parse it
		//echo('<div style="clear:both;"></div><pre style="padding-left:15em;">');print_r($response);echo('</pre><div style="clear:both;"></div>');

				if( is_array( $response ) ) { // If it is an array
		//echo('<pre style="padding-left:15em;">got...</pre><br />');
						$response = current( $response ); // Get the first item
				}

				if( $this->authorize_token ) { // Is there an access token?
						$response['zipball_url'] = add_query_arg( 'access_token', $this->authorize_token, $response['zipball_url'] ); // Update our zip url with token
				}
write_log($response);
				$this->github_response = $response; // Set it to our property
		}
	}
}

















