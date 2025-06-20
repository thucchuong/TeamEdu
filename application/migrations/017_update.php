<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Migration_Update extends CI_Migration
{
    public function up()
    {
        /* adding new column meetings */
        $fields = array(
            'type' => array(
                'type'       => 'VARCHAR',
                'constraint' => '64',
                'after'      => 'slug',
                'NULL'       => false,
            ),
            'platform' => array(
                'type'       => 'VARCHAR',
                'constraint' => '64',
                'after'      => 'type',
                'NULL'       => false,
            ),
            'link' => array(
                'type'       => 'TEXT',
                'after'      => 'platform',
                'NULL'       => false,
            ),
            'venue' => array(
                'type'       => 'TEXT',
                'after'      => 'link',
                'NULL'       => false,
            ),
        );
        $this->dbforge->add_column('meetings', $fields);

        /* adding new column time_tracker_sheet */
        $fields = array(
            'project_id' => array(
                'type'       => 'INT',
                'constraint' => '11',
                'after'      => 'workspace_id',
                'NULL'       => TRUE,
            ),
        );
        $this->dbforge->add_column('time_tracker_sheet', $fields);

        /* adding new table statuses */
        $this->dbforge->add_field(array(
            'id' => [
                'type'           => 'INT',
                'constraint'     => '11',
                'auto_increment' => TRUE
            ],
            'workspace_id' => [
                'type' => 'INT',
                'constraint' => '11',
            ],
            'type' => [
                'type' => 'TEXT',
            ],
            'text_color' => [
                'type' => 'VARCHAR',
                'constraint' => '128',
            ],
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp',
        ));
        $this->dbforge->add_key('id', true);
        $this->dbforge->create_table('statuses');
    }
    public function down()
    {
        // Drop column 
        $this->dbforge->drop_column('meetings', 'date_of_birth');
        $this->dbforge->drop_column('meetings', 'date_of_joining');
        $this->dbforge->drop_column('meetings', 'gender');
        $this->dbforge->drop_column('meetings', 'designation');
        $this->dbforge->drop_column('time_tracker_sheet', 'project_id');
        // Drop Table
        $this->dbforge->drop_table('statuses');
    }
}
