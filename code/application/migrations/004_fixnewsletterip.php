<?php


defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Migration class.
 *
 * This class fix the miss of 'ipaddress' field to 'newsletter_email' table on WpanelCms.
 *
 * @package     WpanelCms
 * @subpackage  Migrations
 * @category    Migrations
 * @author      Eliel de Paula <dev@elieldepaula.com.br>
 * @link        https://wpanelcms.com.br
 * @version     0.0.1
 */
class Migration_fixnewsletterip extends CI_Migration
{

	protected $fields = array(
        'ipaddress' => array(
            'type' => 'VARCHAR',
            'constraint'=> 20,
            'default' => null
        )
    );

	public function up()
	{
		$this->dbforge->add_column('newsletter_email', $this->fields);
	}

	public function down()
	{
		$this->dbforge->drop_column('newsletter_email', 'ipaddress');
	}
}