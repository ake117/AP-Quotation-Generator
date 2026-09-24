<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/*
 * data Omise
 */

$config['omise']['pkey'] = 'pkey_test_5d5pclgp1bfzmxmqa4d';
$config['omise']['skey'] = 'skey_test_5d5pcsq4qdbj61k23rr';


/*
 * metata data
 */
$config['default']['title'] = 'iBooking - Geande Asset';
$config['default']['title_nodash'] = 'iBooking';
$config['default']['description'] = 'ลงทะเบียนรับสิทธิ์ BOOKING ONLINE ลงทะเบียนรับส่วนลด 300,000 บาท';
$config['default']['keywords'] = "ลงทะเบียน, ลงทะเบียนรับสิทธิ์, ส่วนลด, BOOKING ONLINE ";

$config['default']['author'] = "Grande Asset";
$config['default']['og_site_name'] = "Grande Asset";
$config['default']['og_image'] = 'http://';
$config['default']['og_url'] = 'http://';


/*
 * social media sharing link
 */

/*
 * path image
 */
$CI =& get_instance();

$config['path']['projects'] = $CI->config->item('base_url').'/media/projects/';
$config['path']['rooms'] = $CI->config->item('base_url').'/media/rooms/';
$config['path']['roomtype'] = $CI->config->item('base_url').'/media/roomtype_gallery/';
$config['path']['floor'] = $CI->config->item('base_url').'/media/floor/';
$config['path']['gallery'] = $CI->config->item('base_url').'/media/gallery/';
$config['path']['facility'] = $CI->config->item('base_url').'/media/facility/';

/*
 *
 */

/*
 * booking status
 */

$config['booking_status'] = array('P'=>'รอจ่ายเงิน','S'=>'จ่ายเงินเรียบร้อย','F','จ่ายไม่สำเร็จ');
$config['room_booking_status'] = array('P' => 'รอจ่ายเงิน', 'Y'=>'ห้องว่างพร้อมจอง', 'N'=> 'ห้องไม่ว่าง' );

/*
 * BBL payment
 */

//$config['bbl']['pay_url'] = "https://ipay.bangkokbank.com/b2c/eng/payment/payForm.jsp";
//$config['bbl']['merchantid'] = "5964";

$config['bbl']['pay_url'] = "https://psipay.bangkokbank.com/b2c/eng/payment/payForm.jsp";
$config['bbl']['merchantid'] = "4718";

$config['bbl']['timeout'] = 15; //MINUTE
