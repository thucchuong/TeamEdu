<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Migration_update extends CI_Migration
{
    public function up()
    {
        /* adding new column projects */
        $fields = array(
            'priority' => array(
                'type'       => 'VARCHAR',
                'constraint' => '64',
                'after'      => 'class',
                'NULL'       => true,
            ),
        );
        $this->dbforge->add_column('projects', $fields);
    }
    public function down()
    {
        // Drop column 
        $this->dbforge->drop_column('projects', 'priority');
    }
}
