<?php
class Migration_update extends CI_Migration
{

    public function __construct()
    {
        parent::__construct();
        $this->load->dbforge();
    }
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type'           => 'INT',
                'constraint'     => '11',
                'auto_increment' => TRUE
            ],
            'admin_id' => [
                'type'           => 'INT',
                'constraint'     => '11'
            ],
            'workspace_id' => [
                'type'           => 'INT',
                'constraint'     => '11'
            ],
            'title' => [
                'type'           => 'VARCHAR',
                'constraint'     => '256'
            ],
            'slug' => [
                'type'           => 'VARCHAR',
                'constraint'     => '256'
            ],
            'user_ids' => [
                'type'           => 'TEXT',
                'NULL'              => TRUE
            ],
            'client_ids' => [
                'type'           => 'TEXT',
                'NULL'              => TRUE
            ],
            'start_date DATETIME default NULL',
            'end_date DATETIME default NULL',
            'date_created TIMESTAMP default CURRENT_TIMESTAMP',
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('meetings');

        $fields = array(
            'version' => array(
                'name' => 'version',
                'type' => 'VARCHAR',
                'constraint'     => '30'
            )
        );
        $this->dbforge->modify_column('updates', $fields);
    }
}
