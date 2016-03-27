<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Migration_Add_session
 *
 * @author marwansaleh
 */
class Migration_Add_session extends MY_Migration {
    protected $_table_name = 'ci_sessions';
    protected $_primary_key = 'session_id';
    protected $_fields = array(
        'session_id'    => array (
            'type'  => 'VARCHAR',
            'constraint' => 40,
            'NULL' => FALSE
        ),
        'ip_address'    => array(
            'type' => 'VARCHAR',
            'constraint' => 45,
            'NULL' => FALSE
        ),
        'user_agent' => array(
            'type'  => 'VARCHAR',
            'constraint' => 120,
            'NULL' => FALSE
        ),
        'last_activity' => array(
            'type'  => 'INT',
            'constraint' => 10,
            'NULL' => FALSE
        ),
        'user_data' => array(
            'type' => 'TEXT',
            'NULL' => FALSE
        )
    );
}

/*
 * filename : 001_add_session.php
 * location : /application/migrations/001_add_session.php
 */
