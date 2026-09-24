<?php


defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('is_logged'))
{
    // Retorna se está ou não logado.
    function is_logged()
    {
        $CI =& get_instance();
        if($CI->session->userdata('logged_in'))
            return TRUE;
        else
            return FALSE;
    }
}

if (!function_exists('has_permission'))
{
    /**
     * Verifica se um usuário tem permissão para determinado link. A opção $override_root
     * serve para não considerar o root na hora da checagem.
     *
     * @author Eliel de Paula <dev@elieldepaula.com.br>
     * @param String $url Link da pagina Eg. 'admin/users'
     * @param Integer $account_id ID da conta a ser testada. Caso seja null usa-se o usuário logado.
     * @param Bool $override_root Ignora o ROOT na verificação.
     * @return Mixed
     */
    function has_permission($url, $account_id = NULL, $override_root = FALSE)
    {
        $CI =& get_instance();
        return $CI->auth->has_permission($url, $account_id, $override_root);
    }
}

if (!function_exists('auth_extra_data'))
{
    /**
     * This helper return a 'extra-data' item from the session login.
     *
     * @param $item string
     * @return mixed
     */
    function auth_extra_data($item = null, $json = null)
    {
        $CI =& get_instance();
        if($item == null)
            return $CI->auth->get_extra_data(null, $json);
        else
            return $CI->auth->get_extra_data($item, $json);
    }
}

if (!function_exists('auth_login_data'))
{
    /**
     * This helper return a item from the session login.
     *
     * @param $var string
     * @return mixed
     */
    function auth_login_data($var)
    {
        $CI =& get_instance();
        return $CI->auth->get_login_data($var);
    }
}