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
        /* adding new table time_tracker_sheet */
        $this->dbforge->add_field([
            'id' => [
                'type'           => 'INT',
                'constraint'     => '11',
                'auto_increment' => TRUE
            ],
            'user_id' => [
                'type'           => 'INT',
                'constraint'     => '11'
            ],
            'workspace_id' => [
                'type'           => 'INT',
                'constraint'     => '11'
            ],
            'start_time' => [
                'type' => 'time',
                'NULL' => TRUE,
            ],
            'end_time' => [
                'type' => 'time',
                'NULL' => TRUE,
            ],
            'duration' => [
                'type' => 'time',
                'NULL' => TRUE,
            ],
            'date' => [
                'type' => 'date',
            ],
            'created_at TIMESTAMP default CURRENT_TIMESTAMP',
            'updated_at TIMESTAMP on update CURRENT_TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('time_tracker_sheet');

        /* adding new table todos */
        $this->dbforge->add_field([
            'id' => [
                'type'           => 'INT',
                'constraint'     => '11',
                'auto_increment' => TRUE
            ],
            'workspace_id' => [
                'type'           => 'INT',
                'constraint'     => '11'
            ],
            'user_id' => [
                'type'           => 'INT',
                'constraint'     => '11'
            ],
            'description' => [
                'type' => 'TEXT',
                'NULL' => TRUE,
            ],
            'status' => [
                'type' => 'TINYINT',
                'constraint' => '1'
            ],
            'position' => [
                'type' => 'INT',
                'constraint' => '11'
            ],
            'created_at TIMESTAMP default CURRENT_TIMESTAMP',
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('todos');
    }

    public function down()
    {
        $this->dbforge->drop_table('time_tracker_sheet', TRUE);
        $this->dbforge->drop_table('todos', TRUE);
    }
}
