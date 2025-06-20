<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Migration_update extends CI_Migration
{
          public function up()
          {
                    /* adding new table email_templates */
                    $this->dbforge->add_field([
                              'id' => [
                                        'type'           => 'INT',
                                        'constraint'     => '11',
                                        'auto_increment' => TRUE
                              ],
                              'type' => [
                                        'type'       => 'VARCHAR',
                                        'constraint' => '64',
                                        'NULL'       => TRUE,
                              ],
                              'subject' => [
                                        'type'       => 'VARCHAR',
                                        'constraint' => '1024',
                                        'NULL'       => TRUE,
                              ],
                              'message' => [
                                        'type' => 'LONGTEXT',
                                        'NULL' => TRUE,
                              ],
                              'date_sent TIMESTAMP default CURRENT_TIMESTAMP',
                    ]);
                    $this->dbforge->add_key('id', TRUE);
                    $this->dbforge->create_table('email_templates');

                    /* adding new table leads */
                    $this->dbforge->add_field([
                              'id' => [
                                        'type'           => 'INT',
                                        'constraint'     => '11',
                                        'auto_increment' => TRUE
                              ],
                              'workspace_id' => [
                                        'type'       => 'INT',
                                        'constraint' => '11',
                              ],
                              'title' => [
                                        'type'       => 'VARCHAR',
                                        'constraint' => '255',
                              ],
                              'email' => [
                                        'type'       => 'VARCHAR',
                                        'constraint' => '255',
                              ],
                              'phone' => [
                                        'type'       => 'VARCHAR',
                                        'constraint' => '20',
                              ],
                              'user_id' => [
                                        'type' => 'TEXT',
                                        'NULL' => TRUE,
                              ],
                              'description' => [
                                        'type' => 'TEXT',
                              ],
                              'status' => [
                                        'type' => 'VARCHAR',
                                        'constraint' => '64',
                              ],
                              'assigned_date' => [
                                        'type' => 'DATETIME',
                              ],
                              'created_at TIMESTAMP default CURRENT_TIMESTAMP',
                    ]);
                    $this->dbforge->add_key('id', TRUE);
                    $this->dbforge->create_table('leads');
                    $fields = array(
                              'updated_at TIMESTAMP on update CURRENT_TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
                    );
                    $this->dbforge->add_column('projects', $fields);

                    $fields = array(
                              'updated_at TIMESTAMP on update CURRENT_TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
                    );
                    $this->dbforge->add_column('tasks', $fields);
                    $this->db->query('ALTER TABLE `projects` CHANGE `date_created` `date_created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
                    $this->db->query('ALTER TABLE `tasks` CHANGE `date_created` `date_created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
          }
          public function down()
          {
                    // Drop table 
                    $this->dbforge->drop_table('email_templates');
                    $this->dbforge->drop_table('leads');
                    // Drop column 
                    $this->dbforge->drop_column('projects', 'updated_at');
                    $this->dbforge->drop_column('tasks', 'updated_at');
          }
}
