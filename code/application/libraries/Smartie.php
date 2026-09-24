<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Smartie Class
 *
 * @package        CodeIgniter
 * @subpackage     Libraries
 * @category       Smarty
 * @author         Kepler Gelotte
 * @link           http://www.coolphptools.com/codeigniter-smarty
 */
require_once( APPPATH.'/third_party/smarty/libs/Smarty.class.php' );

class Smartie extends Smarty {

    var $debug = false;

    function __construct()
    {
        parent::__construct();
        $CI =& get_instance();
        
        if($CI->uri->segment(1) === 'admin'){
            $this->setTemplateDir(APPPATH . "modules/admin/views/_layout")
                    ->addTemplateDir(APPPATH . 'modules/admin/views', 'one');
        }else{
            $this->setTemplateDir(APPPATH . "views/templates");
        }
        
        $this->cache_dir = APPPATH . "modules/admin/views/_layout/cache";
        $this->compile_dir = APPPATH . "modules/admin/views/_layout/templates_c";
        $this->caching = 0;
        $this->cache_lifetime = 1800; // 30 minutes
        $this->compile_check = true;

        if ( ! is_writable( $this->compile_dir ) )
        {
            // make sure the compile directory can be written to
            @chmod( $this->compile_dir, 0777 );
        }

        // Uncomment these 2 lines to change Smarty's delimiters
        // $this->left_delimiter = '{{';
        // $this->right_delimiter = '}}';

        $this->assign( 'FCPATH', FCPATH );     // path to website
        $this->assign( 'APPPATH', APPPATH );   // path to application directory
        $this->assign( 'BASEPATH', BASEPATH ); // path to system directory

        
        $CI->load->config('tcdc', true);
        $config_tcdc =  $CI->config->item('tcdc');
        $this->assign( 'facebook_url', $config_tcdc['facebook_url'] );
        $this->assign( 'twitter_url', $config_tcdc['twitter_url'] );
        $this->assign( 'youtube_url', $config_tcdc['youtube_url'] );
        $this->assign( 'instagram_url', $config_tcdc['instagram_url'] );

        log_message('debug', "Smarty Class Initialized");
    }

    public function set_admin_template(){
        $CI =& get_instance();
        $this->addTemplateDir(APPPATH . "modules/admin/views/". $CI->uri->segment(2, null, '-', '_') ,'two');
        //var_dump($this->template_dir);
    }

    function setDebug( $debug=true )
    {
        $this->debug = $debug;
    }

    /**
     *  Parse a template using the Smarty engine
     *
     * This is a convenience method that combines assign() and
     * display() into one step.
     *
     * Values to assign are passed in an associative array of
     * name => value pairs.
     *
     * If the output is to be returned as a string to the caller
     * instead of being output, pass true as the third parameter.
     *
     * @access    public
     * @param    string
     * @param    array
     * @param    bool
     * @return    string
     */
    function view($template, $cache_id=null, $data = array(), $return = FALSE)
    {
        if ( ! $this->debug )
        {
            $this->error_reporting = false;
        }

        $this->error_unassigned = false;

        foreach ($data as $key => $val)
        {
            $this->assign($key, $val);
        }

        if ($return == FALSE)
        {
            $CI =& get_instance();
            if (method_exists( $CI->output, 'set_output' ))
            {
                $CI->output->set_output( $this->fetch($template, $cache_id) );
            }else{
                $CI->output->final_output = $this->fetch($template, $cache_id);
            }
            return;
        }else{
            return $this->fetch($template, $cache_id);
        }
    }

    function view_admin($template, $cache_id=null, $data = array(), $return = FALSE)
    {
        $CI =& get_instance();
        $msg_system = $CI->session->flashdata('msg_system');
        $lang = wpn_config('language');
        $anchor_profile = anchor('admin/accounts/profile', glyphicon('user') . wpn_lang('lnk_profile', 'Profile'), array('class'=>'btn btn-primary btn-flat'));
        $anchor_logout = anchor('/admin/logout', glyphicon('off') . wpn_lang('lnk_logout', 'Logout'), array('class'=>'btn btn-danger btn-flat'));

        $this->assign('skin', auth_extra_data('skin'));
        $this->assign('account_name', auth_extra_data('name'));

        $avatar = auth_extra_data('avatar');
	if($avatar){
            $avatar = base_url('media/avatar/'.$avatar);
        }else{
            $avatar = base_url('lib/img/no-user.jpg');
        }
        $this->assign('avatar', $avatar);
        $this->assign('anchor_profile', $anchor_profile);
        $this->assign('anchor_logout', $anchor_logout);


        $this->assign('activelink', $CI->uri->segment(2) );
        $this->assign('msg_system', $msg_system);
        $this->assign('lang', $lang);
        $this->assign('copyright_year', date('Y'));
        $this->assign('since_created', datetime_for_user(auth_login_data('created'), false) );

        $this->assign('elapsed_time', $CI->benchmark->elapsed_time());


        if ( ! $this->debug )
        {
            $this->error_reporting = false;
        }
        $this->error_unassigned = false;

        foreach ($data as $key => $val)
        {
            $this->assign($key, $val);
        }

        if ($return == FALSE)
        {
            $CI =& get_instance();
            if (method_exists( $CI->output, 'append_output' ))
            {
                $CI->output->append_output( $this->fetch($template, $cache_id) );
            }
            else
            {
                $CI->output->final_output .= $this->fetch($template, $cache_id);
            }
            return;
        }else{
            return $this->fetch($template, $cache_id);
        }
    }
}
// END Smartie Class