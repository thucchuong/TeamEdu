<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Migration_Update extends CI_Migration
{
    public function up()
    {
        /* adding new column payslips */
        $this->dbforge->add_field([
            'id' => [
                'type'           => 'INT',
                'constraint'     => '11',
                'auto_increment' => TRUE
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => '11',
                'NULL'       => FALSE,
            ],
            'project_id' => [
                'type'       => 'INT',
                'constraint' => '11',
                'NULL'       => FALSE,
            ],
            'workspace_id' => [
                'type'       => 'INT',
                'constraint' => '11',
                'NULL'       => FALSE,
            ],

            'date_created TIMESTAMP default CURRENT_TIMESTAMP',
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('favourite_projects');
    }
    public function down()
    {

        // Drop table 
        $this->dbforge->drop_table('favourite_projects');
    }
}
