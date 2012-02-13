<?php
class R4e6a046ba53c41d19ec701486318cd70 extends CakeRelease {

/**
 * Migration description
 *
 * @var string
 * @access public
 */
	public $description = 'Migration for ViewCounter version 0.8.3';

/**
 * Plugin name
 *
 * @var string
 * @access public
 */
	public $plugin = 'ViewCounter';

/**
 * Actions to be performed
 *
 * @var array $migration
 * @access public
 */
	public $migration = array(
		'up' => array(
			'alter_field' => array(
				'view_counts' => array(
					'user_id' => array('type' => 'string', 'null' => false, 'default' => '0', 'length' => 36, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
				),
			),
		),
		'down' => array(
			'alter_field' => array(
				'view_counts' => array(
					'user_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 10),
				),
			),
		),
	);

	
/**
 * Before migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
	public function before($direction) {
		return true;
	}

/**
 * After migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
	public function after($direction) {
		return true;
	}
}
?>