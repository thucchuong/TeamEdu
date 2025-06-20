<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_update extends CI_Migration {

	public function __construct() {
		parent::__construct();
		$this->load->dbforge();
	}

	public function up() {  
		$fields = array(
            'date_of_birth' => array(
                'type'       => 'DATE',
                'after'      => 'address',
                'NULL'       => false,
            ),
            'date_of_joining' => array(
                'type'       => 'DATE',
                'after'      => 'date_of_birth',
                'NULL'       => false,
            ),
		);
		$this->dbforge->modify_column('users', $fields);

	}

	public function down() {
		$this->dbforge->drop_column('users', 'date_of_birth');
		$this->dbforge->drop_column('users', 'date_of_joining');
	}
}
