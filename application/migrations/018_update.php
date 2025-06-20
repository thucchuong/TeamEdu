<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Migration_Update extends CI_Migration
{
    public function up()
    {
        /* adding new column payslips */
        $fields = array(
            'signature' => array(
                'type'       => 'TEXT',
                'after'      => 'status',
            ),
        );
        $this->dbforge->add_column('payslips', $fields);
    }
    public function down()
    {
        // Drop column 
        $this->dbforge->drop_column('payslips', 'signature');
    }
}
