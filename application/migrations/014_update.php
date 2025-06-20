<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Migration_Update extends CI_Migration
{
    public function up()
    {
        /* adding new column allowance */
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
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '512',
            ],
            'amount' => [
                'type' => 'DOUBLE',
            ],
            'created_at datetime default current_timestamp',
        ));
        $this->dbforge->add_key('id', true);
        $this->dbforge->create_table('allowance');

        /* adding new column deductions */
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
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '512',
            ],
            'deduction_type' => [
                'type' => 'VARCHAR',
                'constraint' => '512',
            ],
            'percentage' => [
                'type' => 'DOUBLE',
            ],
            'amount' => [
                'type' => 'DOUBLE',
            ],
            'created_at datetime default current_timestamp',
        ));
        $this->dbforge->add_key('id', true);
        $this->dbforge->create_table('deductions');

        /* adding new column payslips */
        $this->dbforge->add_field(array(
            'id' => [
                'type'           => 'INT',
                'constraint'     => '11',
                'auto_increment' => TRUE
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => '11',
            ],
            'workspace_id' => [
                'type' => 'INT',
                'constraint' => '11',
            ],
            'allowance_item_ids' => [
                'type' => 'TEXT',
            ],
            'deduction_item_ids' => [
                'type' => 'TEXT',
            ],
            'payslip_month' => [
                'type' => 'DATETIME',
            ],
            'working_days' => [
                'type' => 'DOUBLE',
            ],
            'lop_days' => [
                'type' => 'DOUBLE',
            ],
            'paid_days' => [
                'type' => 'DOUBLE',
            ],
            'basic_salary' => [
                'type' => 'DOUBLE',
            ],
            'leave_deduction' => [
                'type' => 'DOUBLE',
            ],
            'ot_hours' => [
                'type' => 'DOUBLE',
            ],
            'ot_rate' => [
                'type' => 'DOUBLE',
            ],
            'ot_payment' => [
                'type' => 'DOUBLE',
            ],
            'total_allowance' => [
                'type' => 'DOUBLE',
            ],
            'incentives' => [
                'type' => 'DOUBLE',
            ],
            'bonus' => [
                'type' => 'DOUBLE',
            ],
            'total_earnings' => [
                'type' => 'DOUBLE',
            ],
            'total_deductions' => [
                'type' => 'DOUBLE',
            ],
            'net_pay' => [
                'type' => 'DOUBLE',
            ],
            'payment_date' => [
                'type' => 'TIMESTAMP',
            ],
            'payment_method' => [
                'type' => 'VARCHAR',
                'constraint'     => '512',
            ],
            'status' => [
                'type' => 'TINYINT',
                'constraint'     => '4',
            ],
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp',
        ));
        $this->dbforge->add_key('id', true);
        $this->dbforge->create_table('payslips');

        /* adding new column payslip_allowance */
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
            'allowance_id' => [
                'type' => 'INT',
                'constraint' => '11',
            ],
            'allowance_name' => [
                'type' => 'VARCHAR',
                'constraint' => '512',
            ],
            'payslip_id' => [
                'type' => 'INT',
                'constraint' => '11',
            ],
            'amount' => [
                'type' => 'DOUBLE',
            ],
            'created_at datetime default current_timestamp',
        ));
        $this->dbforge->add_key('id', true);
        $this->dbforge->create_table('payslip_allowance');

        /* adding new column deductions */
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
            'payslip_id' => [
                'type' => 'INT',
                'constraint' => '11',
            ],
            'deduction_id' => [
                'type' => 'INT',
                'constraint' => '11',
            ],
            'deduction_name' => [
                'type' => 'VARCHAR',
                'constraint' => '512',
            ],
            'deduction_type' => [
                'type' => 'VARCHAR',
                'constraint' => '512',
            ],
            'percentage' => [
                'type' => 'DOUBLE',
            ],
            'amount' => [
                'type' => 'DOUBLE',
            ],
            'created_at datetime default current_timestamp',
        ));
        $this->dbforge->add_key('id', true);
        $this->dbforge->create_table('payslip_deductions');
    }
    public function down()
    {
        // Drop Table
        $this->dbforge->drop_table('allowance');
        $this->dbforge->drop_table('deductions');
        $this->dbforge->drop_table('payslips');
        $this->dbforge->drop_table('payslip_allowance');
        $this->dbforge->drop_table('payslip_deductions');
    }
}
