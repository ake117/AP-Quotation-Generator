<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['url'] = 'http://web.tcdc.or.th:8443/api/v1/login';
$config['username'] = 'suree@ewitewit.com';
$config['password'] = 'tcdcsBBX75jN';

$config['get-article'] = 'http://web.tcdc.or.th:8443/api/v1/get-article';
$config['get-article-data'] = 'http://web.tcdc.or.th:8443/api/v1/get-article-data';
$config['get-category'] = 'http://web.tcdc.or.th:8443/api/v1/get-category';
$config['get-brand'] = 'http://web.tcdc.or.th:8443/api/v1/get-brand';
$config['get-publication'] = 'http://web.tcdc.or.th:8443/api/v1/get-publication';
$config['get-publication-data'] = 'http://web.tcdc.or.th:8443/api/v1/get-publication-data';
$config['get-tag'] = 'http://web.tcdc.or.th:8443/api/v1/get-tag';
$config['update-article'] = 'http://web.tcdc.or.th:8443/api/v1/update-article';

/*
 * youtube api
 */
$config['y_channel_id'] = 'UC7SClSviPxX_haIM1gpNbBg';
$config['y_api_key'] = 'AIzaSyB7MUljtjEfoLUc6lYj3sdnHTN3kV0HlNY';
$config['y_get_list'] = 'https://www.googleapis.com/youtube/v3/search';
$config['y_get_video'] = 'https://www.googleapis.com/youtube/v3/videos';

/*
 * metata data
 */
$config['default']['title'] = 'Creative Thailand - นิตยสารส่งเสริมความคิดสร้างสรรค์ ผลักดันเศรษฐกิจไทย';
$config['default']['title_nodash'] = 'Creative Thailand';
$config['default']['description'] = 'นิตยสาร Creative Thailand  “คิด” นิตยสารฉบับแจกฟรี (Free Copy Magazine) รายเดือน  เผยแพร่ความรู้เกี่ยวกับความคิดสร้างสรรค์ เศรษฐกิจสร้างสรรค์';
$config['default']['keywords'] = "นิตยสารความคิดสร้างสรรค์, นิตยสารคิด, อุตสาหกรรมสร้างสรรค์, ธุรกิจสร้างสรรค์, เศรษฐกิจสร้างสรรค์, เศรษฐกิจสร้างสรรค์, บทความการออกแบบ, บทความความคิดสร้างสรรค์, ข้อมูลเศรษฐกิจสร้างสรรค์, ข้อมูลธุรกิจสร้างสรรค์,  &ldquo;Creative Thailand&rdquo;, ความคิดสร้างสรรค์";

$config['default']['og_image'] = 'http://www.tcdc.or.th/upload/og-fbshare-creative-thailand-default.jpg';
$config['default']['og_url'] = 'http://www.tcdc.or.th/creativethailand/';


/*
 * social media sharing link
 */

$config['facebook_url'] = "https://www.facebook.com/creativethailand";
$config['twitter_url'] = "https://twitter.com/creative_TH";
$config['youtube_url'] = "https://www.youtube.com/channel/{$config['y_channel_id']}";
$config['instagram_url'] = "https://www.instagram.com/creativethailandmagazine/";