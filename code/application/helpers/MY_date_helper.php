<?php


defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Wpanel aditional date helper.
 *
 * This helper contains some aditional functions to
 * Date Helper of Codeigniter to Wpanel CMS.
 *
 * @todo        Alterar para usar a função mdate() do CodeIgniter.
 * @package     WpanelCms
 * @subpackage  Helpers
 * @category    Helpers
 * @author      Eliel de Paula <dev@elieldepaula.com.br>
 * @link        http://elieldepaula.com.br
 */

if (!function_exists('date_for_mysql'))
{

    /**
    * This helper return an human date to mysql date.
    *
    * @param $date string Ex: 10/12/2015
    * @return string Ex: 2015-12-10
    */
    function date_for_mysql($date)
    {
        $disassemble = explode("/", $date);
        $day = $disassemble[0];
        $month = $disassemble[1];
        $year = $disassemble[2];
        $result = $year."-".$month."-".$day;
        return $result;
    }
}

if (!function_exists('date_for_user'))
{
    /**
    * This helper return an mysql date to human date.
    *
    * @param $date string Ex: 2015-12-10
    * @return string Ex: 10/12/2015
    */
    function date_for_user($date)
    {
        $disassemble = explode("-", $date);
        $year = $disassemble[0];
        $month = $disassemble[1];
        $day = $disassemble[2];
        $result = $day."/".$month."/".$year;
        return $result;
    }
}

if (!function_exists('datetime_for_mysql'))
{
    /**
    * This helper return an human date with time to mysql date.
    *
    * @param $date string Ex: 10/12/2015 12:00:00
    * @return string Ex: 2015-12-10 12:00:00
    */
    function datetime_for_mysql($date, $show_time = True)
    {
        $disassemble = explode(" ", $date);
        $data = $disassemble[0];
        $hora = $disassemble[1];
        if($show_time)
            $result = date_for_mysql($data).' '.$hora;
        else
            $result = date_for_mysql($data);
        return $result;
    }

}

if (!function_exists('datetime_for_user'))
{

    /**
    * This helper return an mysql date with time to human date.
    *
    * @param $date string Ex: 2015-12-10 12:00:00
    * @return string Ex: 10/12/2015 12:00:00
    */
    function datetime_for_user($date, $show_time = True)
    {
        $disassemble = explode(" ", $date);
        $data = $disassemble[0];
        $hora = $disassemble[1];
        if($show_time)
            $result = date_for_user($data).' '.$hora;
        else
            $result = date_for_user($data);
        return $result;
    }
}