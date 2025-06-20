<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Migration_update extends CI_Migration
{
    public function up()
    {
        /* adding new column groups */
        $fields = array(
            'permissions' => array(
                'type'       => 'MEDIUMTEXT',
                'after'      => 'description',
                'default'    =>  NULL,
                'NULL'       =>  TRUE,
            )
        );
        $this->dbforge->add_column('groups', $fields);
    }
    public function down()
    {
        // Drop column 
        $this->dbforge->drop_column('groups', 'permissions');
    }
}
