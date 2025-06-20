<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Migration_Update extends CI_Migration
{
    public function up()
    {
        /* adding new column users */
        $fields = array(
            'date_of_birth' => array(
                'type'       => 'DATE',
                'after'      => 'address',
                'NULL'       => true,
            ),
            'date_of_joining' => array(
                'type'       => 'DATE',
                'after'      => 'date_of_birth',
                'NULL'       => true,
            ),
            'gender' => array(
                'type'       => 'VARCHAR',
                'constraint' => '64',
                'after'      => 'date_of_joining',
                'NULL'       => true,
            ),
            'designation' => array(
                'type'       => 'VARCHAR',
                'constraint' => '256',
                'after'      => 'gender',
                'NULL'       => true,
            ),
        );
        $this->dbforge->add_column('users', $fields);
    }
    public function down()
    {
        // Drop column 
        $this->dbforge->drop_column('users', 'date_of_birth');
        $this->dbforge->drop_column('users', 'date_of_joining');
        $this->dbforge->drop_column('users', 'gender');
        $this->dbforge->drop_column('users', 'designation');
    }
}
