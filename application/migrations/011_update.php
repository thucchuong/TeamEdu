<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Migration_update extends CI_Migration
{
    public function up()
    {
        /* adding new column time_tracker_sheet */
        $fields = array(
            'message' => array(
                'type'       => 'MEDIUMTEXT',
                'after'      => 'duration',
                'default'    =>  NULL,
            )
        );
        $this->dbforge->add_column('time_tracker_sheet', $fields);
    }
    public function down()
    {
        // Drop column 
        $this->dbforge->drop_column('time_tracker_sheet', 'message');
    }
}
