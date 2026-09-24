<?php
$ci =& get_instance();

$lang['username']  = "User Name";
$lang['password']  = "Password";
$lang['passconf']  = "Confirm";
$lang['email']     = "Email";
$lang['state']     = "State";

$lang['lang']  = "en";


$lang['promotion_title']    = "SIGNUP FOR<br class='show-mobile'> BOOKING ONLINE";
$lang['promotion_date']     = "ONLINE BOOKING 16 AUGUST";
$lang['promotion_time']     = "TIME 19.00 - 21.00";
$lang['promotion_desc']     = "SIGNUP FOR DISCOUNT 300,000 BAHT<br>START AT 160,000 BATH PER SQM.";

$lang['menu']    = "MENU";
$lang['signup']  = "REGISTER";
$lang['signin']  = "LOGIN";
$lang['howto']   = "HOW TO";

if (isset($ci->user)) {
    $lang['menu_array'] = array(
        'profile' => array('txt'=>'PROFILE', 'type'=>'cat', 'search'=>'tag', 'search_value'=>'', 'display_type' => '', 'result'=>'', 'link'=>'profile', 'display'=>'Y', 'article_short_desc'=>'', 'target'=>'')
      , 'change-password' => array('txt'=>'CHANGE PASSWORD', 'type'=>'cat', 'search'=>'tag', 'search_value'=>'', 'display_type' => '' , 'result'=>'', 'link'=>'password/change', 'display'=>'Y', 'article_short_desc'=>'', 'target'=>'' )
      , 'booking-history' => array('txt'=>'BOOKING HISTORY', 'type'=>'cat', 'search'=>'tag', 'search_value'=>'', 'display_type' => '' , 'result'=>'', 'link'=>'history', 'display'=>'Y', 'article_short_desc'=>'', 'target'=>'' )
      , 'howto' => array('txt'=>'HOW TO', 'type'=>'cat', 'search'=>'tag', 'search_value'=>'', 'display_type' => '' , 'result'=>'', 'link'=>'howto', 'display'=>'Y', 'article_short_desc'=>'', 'target'=>'_blank' )
      , 'privacy policy' => array('txt'=>'Privacy Policy', 'type'=>'cat', 'search'=>'tag', 'search_value'=>'', 'display_type' => '' , 'result'=>'', 'link'=>'privacy', 'display'=>'Y', 'article_short_desc'=>'', 'target'=>'_blank' )
      , 'signout' => array('txt'=>'SIGN OUT', 'type'=>'cat', 'search'=>'tag', 'search_value'=>'', 'display_type' => '' , 'result'=>'', 'link'=>'signout', 'display'=>'Y', 'article_short_desc'=>'', 'target'=>'' )
    );
} else {
    $lang['menu_array'] = array(
        'signup' => array('txt'=>'REGISTER', 'type'=>'cat', 'search'=>'tag', 'search_value'=>'', 'display_type' => '', 'result'=>'', 'link'=>'signup', 'display'=>'Y', 'article_short_desc'=>'REGISTER', 'target'=>'' )
      , 'signin' => array('txt'=>'LOGIN', 'type'=>'cat', 'search'=>'tag', 'search_value'=>'', 'display_type' => '' , 'result'=>'', 'link'=>'signin', 'display'=>'Y', 'article_short_desc'=>'LOGIN', 'target'=>'' )
      , 'forgot-password' => array('txt'=>'FORGOT PASSWORD', 'type'=>'cat', 'search'=>'tag', 'search_value'=>'', 'display_type' => '' , 'result'=>'', 'link'=>'password/reset', 'display'=>'Y', 'article_short_desc'=>'FORGOT PASSWORD', 'target'=>'' )        
      , 'howto' => array('txt'=>'HOW TO', 'type'=>'cat', 'search'=>'tag', 'search_value'=>'', 'display_type' => '' , 'result'=>'', 'link'=>'howto', 'display'=>'Y', 'article_short_desc'=>'HOW TO', 'target'=>'_blank' )
      , 'privacy policy' => array('txt'=>'Privacy Policy', 'type'=>'cat', 'search'=>'tag', 'search_value'=>'', 'display_type' => '' , 'result'=>'', 'link'=>'privacy', 'display'=>'Y', 'article_short_desc'=>'', 'target'=>'_blank' )
            
    );
}

/* email */
$lang['email_from'] = "Production Cost";
$lang['email_title'] = "Online Booking:";

$lang['name_prefix']  = "";

/* Signin */
$lang['signin_prefix']  = array("Mr."=>"Mr.","Miss"=>"Miss","Mrs."=>"Mrs.");
$lang['signin_title']    = "LOGIN";
$lang['signin_subtitle']  = "Login To Your Account";
$lang['signin_email']  = "Email";
$lang['signin_password']   = "Password";
$lang['signin_button']    = "LOGIN";
$lang['signin_forgetpassword']   = "Forgot Password";

/* Signup */
$lang['sign_back']   = "BACK";	
$lang['sign_next']   = "NEXT";	
$lang['sign_confirm']   = "CONFIRM";	
	
$lang['sign_title']    = "REGISTER";

$lang['sign_subtitle1']  = "Create Account";
$lang['sign_subtitle11']  = "(Please fill in all required info)";
$lang['sign_email']  = "Email";
$lang['sign_password']   = "Password";
$lang['sign_password_confirm']    = "Confirm Password";
$lang['sign_password_valid']    ="Password must be at least 8 characters.";

$lang['sign_subtitle2']  = "Update Profile ";
$lang['sign_subtitle21']  = "(Please fill in all required info)";
$lang['sign_subtitle22']  = "Billing Address";
$lang['sign_subtitle23']  = "(Important Information for ONLINE BOOKING)";
$lang['sign_prefix']  = "Title";
$lang['sign_name']   = "First Name";
$lang['sign_surname']    = "Last Name";
$lang['sign_citizen_id']   = "Citizen ID.";
$lang['sign_passport_id']   = "Passport No.";
$lang['sign_phone']   = "Mobile Phone";
$lang['sign_address_no']   = "Address NO.";
$lang['sign_village']   = "Village";
$lang['sign_soi']   = "Soi";
$lang['sign_province']   = "Province";
$lang['sign_country']   = "Country";
$lang['sign_district']   = "District";
$lang['sign_subdistrict']   = "Subdistrict";
$lang['sign_postcode']   = "Postcode";
$lang['sign_province_info']   = "Please select Province";
$lang['sign_district_info']   = "Please select District";
$lang['sign_subdistrict_info']   = "Please select Subdistrice";

$lang['sign_subtitle3']  = "Mailing Address";
$lang['sign_subtitle31']  = "Address for send Document ";
$lang['sign_sameas']  = "Same as billing address";

$lang['sign_subtitle4']  = "PLEASE CONFIRM YOUR INFORMATION<br>BEFORE CREATE ACCOUNT ";
$lang['sign_name_surname']   = "Name - Surname";
$lang['sign_address']   = "Address fron Citizen ID.";
$lang['sign_address_doc']   = "Address for send Document";

$lang['sign_status_1'] = "CREATE ACCOUNT";
$lang['sign_status_2'] = "FILL INFORMATIONS";
$lang['sign_status_3'] = "CONFIRM";

$lang['sign_success'] = "Register Success.";

$lang['sign_country'] = "Country";
$lang['sign_address1'] = "Address Line 1";
$lang['sign_address2'] = "Address Line 2";

$lang['sign_doc_country'] = "Country";
$lang['sign_doc_address1'] = "Address Line 1";
$lang['sign_doc_address2'] = "Address Line 2";

$lang['sign_address_en'] = "Address";

$lang['sign_verify_title'] = "การลงทะเบียนยังไม่เสร็จสมบูรณ์";
$lang['sign_verify_body'] = "กรุณาตรวจสอบอีเมล <span><a href='mailto:%s' class='gold'>%s</a></span> เพื่อคลิกยืนยันการลงทะเบียน<br class='show-desktop'>ขณะนี้ระบบได้ส่งลิ้งค์ไปยังอีเมลของท่านเรียบร้อยแล้ว<br class='show-desktop'>ถ้ายังไม่ได้รับอีเมลกรุณาตรวจสอบที่ Junk Mail<br class='show-desktop'>หรือโทรสอบถามข้อมูลเพิ่มเติมได้ที่ 02 204 9900";
$lang['sign_verify_button'] = 'กลับหน้าหลัก';

$lang['sign_verify_email_title'] = "เรียนคุณ %s";
$lang['sign_verify_email_body'] = "<b>คุณได้ทำการลงทะเบียนเพื่อจองผ่านช่องทางออนไลน์<br>ของบริษัท แกรนด์แอสเสท โฮเทลล์ แอนด์ พรอพเพอร์ตี้ จำกัด (มหาชน)<br>กรุณายืนยันการลงทะเบียน โดยการคลิกปุ่มด้านล่าง</b><br><br>บริษัท แกรนด์ แอสเสท แอนด์ พรอพเพอร์ตี้ จำกัด (มหาชน)<br>สอบถามข้อมูลเพิ่มเติม CALL CENTER 02 204 9900<br><br><i>อีเมลนี้ถูกจัดส่งด้วยระบบอัติโนมัติ โปรดอย่าตอบกลับอีเมลนี้</i><br><br><a href='%s'><div style='border: 1px solid #7c6b47;padding: 8px 16px;display: inline-block;margin-top: 25px;background-color: #ecd9af;font-weight: bold;color:#000'>%s</div></a>";
$lang['sign_verify_email_button'] = 'ยืนยันการลงทะเบียน';

$lang['sign_success_title'] = "ขอบคุณสำหรับการลงทะเบียน";
$lang['sign_success_title_invalid'] = "การลงทะเบียนยังไม่เสร็จสมบูรณ์";
$lang['sign_success_body'] = "กรุณาใช้อีเมล <span class='gold'>%s</span> และรหัสผ่านที่กำหนด<br class='show-desktop'>เข้าสู่ระบบ GRANDE ASSET ONLINE BOOKING ในครั้งต่อไป";
$lang['sign_success_button'] = 'กลับหน้าหลัก';

$lang['sign_success_email_title'] = "ขอบคุณสำหรับการลงทะเบียน<br>กรุณาใช้อีเมล %s และรหัสผ่านที่กำหนด<br class='show-desktop'>เข้าสู่ระบบ GRANDE ASSET ONLINE BOOKING ในครั้งต่อไป";
$lang['sign_success_email_body'] = "<div style='text-align:left;margin-top: 25px;'><b>เตรียมความพร้อมก่อนจอง</b><br><ul style='padding-left:15px;margin-top: 5px;'>"
    . "<li>ลงทะเบียนเพื่อรับ Usename และ Password ที่ <a href='https://www.grandeasset.com/onlinebooking'>www.grandeasset.com/onlinebooking</a> และตรวจสอบ Email ของคุณ เพื่อยืนยัน สิทธิในการจองในวันที่ xx xx (เวลา xx.xx-xx.xx น.)</li>"
    . "<li>เช็ควงเงินบัตรเครดิตของคุณ ว่าเพียงพอหรือไม่ กรณีที่เป็นบัตร Debit กรุณาติดต่อ ธนาคาร ประจำบัตรเพื่อ Activate บัญชีการชำระเงินผ่านทางออนไลน์</li>"
    . "<li>ศึกษาข้อมูลโครงการและห้องที่คุณสนใจพร้อมทั้งเลือกยูนิตในใจ 2-3 ยูนิต เผื่อกรณีที่จองไม่ทันซึ่งคุณสามารถดาวน์โหลดข้อมูลโครงการที่สนใจได้ที่ <a href='https://www.grandeasset.com/onlinebooking'>www.grandeasset.com/onlinebooking</a></li></ul></div>";

$lang['sign_verify_invalid'] = "Invalid link for verify email."; 

        
/* Booking*/
$lang['booking_title'] = "Room select";
$lang['booking_status_1'] = "Project select";
$lang['booking_status_2'] = "Room select";
$lang['booking_status_3'] = "Confirm & Pay";
$lang['booking_active'] = "Available";
$lang['booking_inactive'] = "Booked";
$lang['booking_back'] = "Back";
$lang['booking_next'] = "Reserve";
$lang['booking_select_floor'] = "Floor select";
$lang['booking_select_roomtype'] = "Roomtype select";
$lang['booking_title_select'] = "Please choose floor And room type<span class='br'> </span>BOOKING ONLINE";
$lang['booking_title_confirm'] = "Please check the details.";
$lang['booking_please_select'] = "Please select room";
$lang['booking_name'] = "Name";
$lang['booking_address']   = "Address";
$lang['booking_currency'] = "Baht";
$lang['booking_reservation_information'] = "Information";
$lang['booking_reservation_summary'] = "Summary";
$lang['booking_unit'] = "Unit";
$lang['booking_floor'] = "Floor";
$lang['booking_roomtype'] = "Roomtype";
$lang['booking_room_size'] = "Room Size";
$lang['booking_room_price'] = "Room Price";
$lang['booking_booking_price'] = "Booking Price";
$lang['booking_error'] = "Error";
$lang['booking_confirm_pay'] = "Accept terms of payment.";
$lang['booking_confirm_hi'] = "Hi,";
$lang['booking_confirm_success'] = "Your Order has been Confirmed";
$lang['booking_confirm_fail'] = "Unsuccessful booking";
$lang['booking_confirm_text'] = "We have received your information. We will get back to you as soon as possible.";


/* Projects */
$lang['projects_title'] = "Select Project";
$lang['projects_subtitle'] = "Please Select Projects<br class='show-mobile'> for BOOKING ONLINE";
$lang['projects_select'] = "SELECT";

/* Project */
$lang['project_home'] = "Home"; 
$lang['project_detail'] = "Project Detail"; 
$lang['project_facility'] = "Facility"; 
$lang['project_roomtype'] = "Room Type"; 
$lang['project_gallery'] = "Gallery"; 
$lang['project_360'] = "360 View";
$lang['project_booking'] = "Booking";
$lang['project_back'] = "Back";

$lang['project_home_url'] = url_title($lang['project_home']); 
$lang['project_detail_url'] = url_title($lang['project_detail']); 
$lang['project_facility_url'] = url_title($lang['project_facility']); 
$lang['project_roomtype_url'] = url_title($lang['project_roomtype']); 
$lang['project_gallery_url'] = url_title($lang['project_gallery']); 
$lang['project_360_url'] = url_title($lang['project_360']); 
$lang['project_booking_url'] = url_title($lang['project_booking']); 
$lang['project_back_url'] = url_title($lang['project_back']); 


/* profile */
$lang['profile_title'] = "Personal Information"; 
$lang['profile_edit'] = "Edit";
$lang['profile_home'] = "Home";


/* change password */
$lang['chpass_title'] = "Change Password"; 
$lang['chpass_email'] = "Email";
$lang['chpass_oldpassword'] = "Old Password";
$lang['chpass_newpassword'] = "New Password";
$lang['chpass_confirm'] = "Confirm Password";
$lang['chpass_button'] = "Confirm";
$lang['chpass_success'] = "Success change password.";
$lang['chpass_home'] = "Home";


/* reset password */
$lang['resetpass_title'] = "Reset Password"; 
$lang['resetpass_detail'] = "We can help you reset your password using email address linked to your account.";
$lang['resetpass_email'] = "Email";
$lang['resetpass_button'] = "Reset Password"; 
$lang['resetpass_success'] = "Success reset your password. <br>Please check you email for create new password within 30 minutes";
$lang['resetpass_home'] = "Home";
$lang['resetpass_link'] = "Reset Password";
$lang['resetpass_emaildear'] = "Dear";
$lang['resetpass_emailcontent'] = "You request for reset password.<br>Please click %s for create new password.";


/* new password */
$lang['newpass_title'] = "Reset Password"; 
$lang['newpass_detail'] = "Create your new password."; 
$lang['newpass_success'] = "Success reset your password. Please login with your new password."; 
$lang['newpass_detail_invalid'] = "Invalid link for reset password."; 


/* History */
$lang['history_title'] = "Booking History"; 
$lang['history_button'] = "Home"; 
$lang['history_subtitle'] = "คุณได้ทำการจองโครงการ"; 
$lang['history_date'] = "เมื่อวันที่"; 
$lang['history_click'] = "คลิกดูรายละเอียด";
$lang['history_unit'] = "ยูนิต"; 
$lang['history_floor'] = "ชั้น"; 
$lang['history_roomtype'] = "แบบห้อง"; 
$lang['history_roomsize'] = "ขนาดห้อง"; 
$lang['history_roomprice'] = "ราคาห้อง"; 
$lang['history_bookingprice'] = "ค่าใช้จ่ายในการจอง"; 
$lang['history_baht'] = "บาท"; 
$lang['history_sqm'] = "sq.m."; 

/* Term */
$lang['term_title'] = "<h1>TERMS & CONDITIONS</h1>"; 
$lang['term_detail_old'] = <<<TERM
General<br>
Access to and use of this Production Cost Hotels And Property Public Company Limited site is subject to the laws
of Thailand and the following terms and conditions<br><br>
Copyrights<br>
All copy rights on this site is reserved by Production Cost Hotels And Property Public Company Limited (2016).
All copy rights and other intellectual property rights in all text, images, sound, software and other materials
on this site are owned by Production Cost Hotels And Property Public Company Limited
You are permitted to browse this site and to reproduce extracts by way of printing, downloading to a hard disk 
and by distribution to other people but, in all cases, for non-commercial and personal use only. No reproduction
of any part of the site may be used for the purpose of business, commercial gain nor shall it be modified or 
incorporated in any other work,including but not limit to publication or site, whether in hard copy or electronic format,
including posting to any other site.<br><br>
Trademark <br>
All trademarks displayed on this site are owned and used under license by Production Cost Hotels And Property Public 
Company Limited.ContentAll information on this site is for general information purpose only. It should not be relied 
TERM;

$lang['term_detail'] = <<<TERM
<b>THAI</b><br>
<b>Terms of Payment</b><br>
- <b>Contract fee 5%</b> : On Contract Signing Date (7 days after the Reservation Date)<br>
- Final Payment (Baht) : Final Payment upon Registration for the Transfer of Ownership<br><br>
<b>FOREIGNER</b><br>
<b>Terms of Payment</b><br>
- <b>Contract Fee 15%</b> : On Contract Signing Date (7 days after the Reservation Date)<br>
- Final Payment (Baht) : Final Payment upon Registration for the Transfer of Ownership<br><br>
<b>CONDITIONS:</b><br>
1) PRICE IS VALID 1 DAY AFTER A DATE OF QUOTATION.<br>
2) ALL PAYMENTS SHALL BE MADE PAYABLE TO <b>“GRANDE ASSET HOTELS AND PROPERTY PCL.”</b><br>
3) SINKING FUND 600 BAHT PER SQ.M. (ONE TIME ONLY).<br>
4) MAINTENANCE FEE 60 BAHT PER SQ.M., PAYABLE 12 MONTHS IN ADVANCE.<br>
5) TRANSFER FEE IS EQUALLY PAID BY SELLER AND BUYER<br>
*The above price is subject to unit available and may be changed without prior notice.<br>
*No Refund
TERM;

$base_url = base_url();
/* howto */
$lang['howto_title'] = "Online Booking จองออนไลน์ง่ายๆ เพียง 3 ขั้นตอน"; 
$lang['howto_topic1'] = "1. ลงทะเบียน"; 
$lang['howto_topic1_detail'] = "ลงทะเบียนเพื่อสร้าง Account ของคุณในเว็บไซต์ และตรวจสอบ Email เพื่อยืนยันสิทธิ์";
$lang['howto_topic2'] = "2. เลือกยูนิต"; 
$lang['howto_topic2_detail'] = "ศึกษาข้อมูลโครงการและห้อง ที่คุณสนใจพร้อมทั้งเลือกยูนิต";
$lang['howto_topic3'] = "3. ชำระเงิน"; 
$lang['howto_topic3_detail'] = "ยืนยันการจอง พร้อมชำระเงิน ผ่านทาง VISA, MasterCard, JCB, TPN และ UnionPay";   
$lang['howto_detail_title'] = "เตรียมความพร้อม";
$lang['howto_detail'] = "<div><ul>
    <li>การลงทะเบียนลูกค้าต้องกรอกข้อมูลให้ครบถ้วนและเป็นความจริง ลงทะเบียนเพื่อรับ Username และ Password ที่ <a href='".$base_url."' target='_blank'>".$base_url."</a> และตรวจสอบ Email ของคุณ เพื่อยืนยันสิทธิ์ในการจอง</li>        
    <li>ศึกษาข้อมูลโครงการและห้องที่คุณสนใจตำแหน่งห้อง ทิศ ชั้น และ Type ที่ต้องการเพื่อความรวดเร็วในการจอง</li>
    <li>เช็ควงเงินบัตรเครดิตของคุณว่าเพียงพอหรือไม่ กรณีที่เป็นบัตร Debit กรุณาติดต่อธนาคารประจําบัตร เพื่อ Activate บัญชีการชําระเงินผ่านทางออนไลน์</li>";


/* privacy policy */
$lang['privacy'] = <<<EOT
<h2>Data Protection Policy</h2><br>
<p style='text-indent: 2em'>This Website (“Site”) is operated by Production Cost Hotels and Property Public Company Limited (the “Company”), allowing you to online booking.  The Company shall protect your privacy and handling any personal data you provide to us with care and respect. Before you use this Site, please read the Terms and Conditions and the Data Protection Policy (the “Policy”) of this Site. If you do not accept these terms and conditions and the Policy in this Site, we request you stop using this Site immediately. Your continued use of the Site will indicate your agreement with the Terms and Conditions and the Policy in which the Company has the Policy as follows:</p>
<b>1. Definitions</b><br>
<div class='indent'><b>“Personal Data”</b> means personal data that enables an individual to be identified directly or indirectly.</div>
<div class='indent'><b>“Cookies”</b> means any data sent from the Site to visitor’s computer or other communication devices when a visitor access the Site.</div><br>
<b>2. Personal Data collection</b><br>
<div class='indent'>2.1 The Company will collect your Personal Data which consists of:<br>
<div class='indent'>• Personal data such as name, surname, gender, age, nationality, date of birth, marital status, address, occupation, place of work, postal code, email address, telephone number and credit card.<br>
• The Company respects the privacy of the user of this Site and application and all data/contents shall not be shared or sold to anyone else.<br>
•  The Company collects your Personal Data only as deemed necessary or for the purpose of collection.<br>
• The Company may distribute bulletins or marketing materials to you from time to time.  If you do not wish to receive such bulletins or marketing materials, you may indicate your “opt-out”.</div></div><br>
<b>3. Purpose of collecting/using Personal Data</b><br>
<div class='indent'>• To provide service you requested: in case of online booking, the Company will store and use the necessary data to issue booking documents.<br>
• The Company will not use or disclose your Personal Data UNLESS it is used or disclosed as deemed necessary for the purposes set out above or disclosed to any person in the Company or Affiliates or the parties involved in the contract or with your consent.<br>
• To proceed with analysis and management of the Site so as to create new services to you.</div><br>
<b>4. Cookies</b><br>
<div class='indent'>Cookies are tracking data placed on your computer or other communication devises by the web server. After cookies are placed on your computer or other communication devices, cookies will store or memorize the data of the users until the users close the browsers or until the users delete or reject cookies. However, if you choose to use cookies, you will find it more convenient to navigate through the Site easily because cookies will help store the data about any websites through which you have visited or browsed.</div><br>
<b>5. Changes to our Policy</b><br>
<div class='indent'>If you have any questions, recommendations or any complaints about the protection statements or its implementation, please do not hesitate to contact us. The Company is pleased and willing to answer and listen to the recommendations and complaints which will contribute to better services of Production Cost Hotels and Property Public Company Limited. You can communicate with us via <a href='onlinebooking@grandeasset.com' target='_blank'>onlinebooking@grandeasset.com.</a>  The Company may change/update this Policy without notice to you and shall post same on the first page of the Site or other places as deemed appropriate.  The changed/updated Policy shall become effective to all data you have provided to us.</div><br>
<b>6. Effective Date</b><br>
<div class='indent'>This Data Protection Policy shall become effective as from ( Website Launch Date ) onwards.  The Terms and Conditions which may be changed/updated subsequently shall become effective from the date notified on the first page of the Site or other places as deemed appropriate. </div><br>
EOT;

        
        



