<?php


defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * MY_Controller Controller Class
 *
 * This class maintain the common code for the WpanelCMS Controllers.
 *
 * @package     WpanelCms
 * @subpackage  Core
 * @category    Core
 * @author      Eliel de Paula <dev@elieldepaula.com.br>
 * @link        https://wpanelcms.com.br
 * @version     0.0.1
 */
class MY_Controller extends CI_Controller
{

    /**
     * Enable or disable CodeIgniter profiler.
     * 
     * $var $wpn_profiler
     */
    var $wpn_profiler = FALSE;

    /**
     * Default template folder.
     * 
     * $var $wpn_template
     */
    var $wpn_template = 'default';

    /**
     * Default posts view list.
     * 
     * $var $wpn_posts_view
     */
    var $wpn_posts_view = 'list';

    /**
     * Default column number.
     * 
     * $var $wpn_cols_mosaic
     */
    var $wpn_cols_mosaic = 3;

    /**
     * Common data to header view.
     * 
     * $var $data_header
     */
    var $data_header = array();

    /**
     * Common data to content view.
     * 
     * $var $data_content
     */
    var $data_content = array();

    /**
     * Common data to footer.
     * 
     * $var $data_footer
     */
    var $data_footer = array();

    /**
     * Class constructor.
     *
     * @return void
     */
    function __construct()
    {
        parent::__construct();
        $this->output->enable_profiler($this->wpn_profiler);
    }

    /**
     * This method send a Notice message to template. It was created to work
     * with notice widget.
     * 
     * @param string $message
     * @param string $title
     * @param string $class
     */
    public function notice($message, $title, $class)
    {
        $data = array(
            'title' => $title,
            'message' => $message,
            'class' => $class
        );
        $this->session->set_flashdata('_notice', $data);
    }

    /**
     * The render () method encapsulates the common code of views. It was 
     * thought that you will carry the views header, content and footer 
     * on each method in the controller.
     *
     * If you need to, you can change this method depending on your project.
     *
     * @param $view string Name of the view.
     * @return void
     */
    public function render($view)
    {
        $this->load->view($this->wpn_template . '/header', $this->data_header);
        $this->load->view($this->wpn_template . '/' . $view, $this->data_content);
        $this->load->view($this->wpn_template . '/footer', $this->data_footer);
    }

}
