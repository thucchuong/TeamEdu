<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Migration_update extends CI_Migration
{
    public function up()
    {
        /* adding new column contracts */
        $this->dbforge->add_field(array(
            'id' => [
                'type'           => 'INT',
                'constraint'     => '11',
                'auto_increment' => TRUE
            ],
            'users_id' => [
                'type' => 'INT',
                'constraint' => '11',
            ],
            'workspace_id' => [
                'type' => 'INT',
                'constraint' => '11',
            ],
            'project_id' => [
                'type' => 'INT',
                'constraint'     => '11',
            ],
            'contract_type_id' => [
                'type' => 'INT',
                'constraint' => '11',
            ],
            'title' => [
                'type' => 'TEXT',
            ],
            'start_date' => [
                'type' => 'DATETIME',
            ],
            'end_date' => [
                'type' => 'DATETIME',
            ],
            'description' => [
                'type' => 'LONGTEXT',
            ],
            'value' => [
                'type' => 'DOUBLE',
            ],
            'provider_first_name' => [
                'type' => 'VARCHAR',
                'constraint' => '64',
            ],
            'provider_last_name' => [
                'type' => 'VARCHAR',
                'constraint' => '64',
            ],
            'client_first_name' => [
                'type' => 'VARCHAR',
                'constraint' => '64',
            ],
            'client_last_name' => [
                'type' => 'VARCHAR',
                'constraint' => '64',
            ],
            'provider_sign' => [
                'type' => 'TEXT',
            ],
            'client_sign' => [
                'type' => 'TEXT',
            ],
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp',
        ));
        $this->dbforge->add_key('id', true);
        $this->dbforge->create_table('contracts');

        /* adding new column contracts type */
        $this->dbforge->add_field(array(
            'id' => [
                'type' => 'INT',
                'constraint' => '11',
                'auto_increment' => true,
            ],
            'type' => [
                'type' => 'TEXT',
            ],
        ));
        $this->dbforge->add_key('id', true);
        $this->dbforge->create_table('contracts_type');

        /* adding new column articles */
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
            'group_id' => [
                'type' => 'INT',
                'constraint'     => '11',
                'null' => TRUE
            ],
            'title' => [
                'type' => 'TEXT',
            ],
            'description' => [
                'type' => 'LONGTEXT',
            ],
            'slug' => [
                'type' => 'MEDIUMTEXT',
            ],
            'date_published datetime default current_timestamp',
        ));
        $this->dbforge->add_key('id', true);
        $this->dbforge->create_table('articles');

        /* adding new column article_group */
        $this->dbforge->add_field(array(
            'id' => [
                'type'           => 'INT',
                'constraint'     => '11',
                'auto_increment' => TRUE
            ],
            'title' => [
                'type' => 'MEDIUMTEXT',
            ],
            'description' => [
                'type' => 'LONGTEXT',
            ],
        ));
        $this->dbforge->add_key('id', true);
        $this->dbforge->create_table('article_group');
        /* adding new column projects */
        $fields = array(
            'is_favorite' => array(
                'type'       => 'tinyint',
                'constraint' => '1',
                'after'      => 'comment_count',
                'DEFAULT'     => 0,
            ),
        );
        $this->dbforge->add_column('projects', $fields);
        /* adding new column permissions */
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ),
            'user_id' => array(
                'type' => 'INT',
                'unsigned' => true,
            ),
            'workspace_id' => array(
                'type' => 'INT',
                'unsigned' => true,
            ),
            'permissions' => array(
                'type' => 'TEXT',
                'null' => true,
            ),
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp',
        ));
        $this->dbforge->add_key('id', true);
        $this->dbforge->create_table('permissions');
        /* adding new column users_groups */
        $fields = array(
            'member_permissions' => array(
                'type'       => 'MEDIUMTEXT',
                'after'      => 'group_id',
                'default'    =>  NULL,
                'NULL'       =>  TRUE,
            )
        );
        $this->dbforge->add_column('users_groups', $fields);
        $fields = array(
            'client_permissions' => array(
                'type'       => 'MEDIUMTEXT',
                'after'      => 'member_permissions',
                'default'    =>  NULL,
                'NULL'       =>  TRUE,
            )
        );
        $this->dbforge->add_column('users_groups', $fields);
    }
    public function down()
    {
        // Drop Table
        $this->dbforge->drop_table('contracts');
        $this->dbforge->drop_table('contracts_type');
        $this->dbforge->drop_table('articles');
        $this->dbforge->drop_table('article_group');
        $this->dbforge->drop_column('projects','is_favorite');
        $this->dbforge->drop_table('permissions');
        $this->dbforge->drop_column('users_groups', 'member_permissions');
        $this->dbforge->drop_column('users_groups', 'client_permissions');
    }
}
