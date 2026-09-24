<?php


defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Migration class.
 *
 * This class updates a initial database to WpanelCms.
 *
 * @package     WpanelCms
 * @subpackage  Migrations
 * @category    Migrations
 * @author      Eliel de Paula <dev@elieldepaula.com.br>
 * @link        https://wpanelcms.com.br
 * @version     0.0.1
 */
class Migration_fixcontactlink extends CI_Migration
{
	public function up()
	{
		$this->db->query("UPDATE menu_itens SET href = 'contact' WHERE href = 'contato';");
	}

	public function down()
	{
		$this->db->query("UPDATE menu_itens SET href = 'contato' WHERE href = 'contact';");
	}
}