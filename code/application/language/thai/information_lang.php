<?php
$ci =& get_instance();

$lang['username']  = "User Name";
$lang['password']  = "Password";
$lang['passconf']  = "Confirm";
$lang['email']     = "Email";
$lang['state']     = "State";

$lang['lang']  = "th";

/* home */
$lang['promotion_title']    = "ลงทะเบียนรับสิทธิ์<br class='show-mobile'> BOOKING ONLINE";
$lang['promotion_date']     = "ONLINE BOOKING 16 AUGUST";
$lang['promotion_time']     = "TIME 19.00 - 21.00";
$lang['promotion_desc']     = "ลงทะเบียนรับส่วนลด 300,000 บาท<br>เริ่มต้น 160,000 บาทต่อตารางเมตร";

$lang['menu']    = "เมนู";
$lang['signup']  = "ลงทะเบียน";
$lang['signin']  = "เข้าสู่ระบบ";
$lang['howto']   = "วิธีการใช้งาน";

if (isset($ci->user)) {
    $lang['menu_array'] = array(
        'profile' => array('txt'=>'ข้อมูลส่วนตัว', 'type'=>'cat', 'search'=>'tag', 'search_value'=>'', 'display_type' => '', 'result'=>'', 'link'=>'profile', 'display'=>'Y', 'article_short_desc'=>'', 'target'=>'' )
      , 'change-password' => array('txt'=>'เปลี่ยนรหัสผ่าน', 'type'=>'cat', 'search'=>'tag', 'search_value'=>'', 'display_type' => '' , 'result'=>'', 'link'=>'password/change', 'display'=>'Y', 'article_short_desc'=>'', 'target'=>'' )
      , 'booking-history' => array('txt'=>'ประวัติการจอง', 'type'=>'cat', 'search'=>'tag', 'search_value'=>'', 'display_type' => '' , 'result'=>'', 'link'=>'history', 'display'=>'Y', 'article_short_desc'=>'', 'target'=>'' )
      , 'howto' => array('txt'=>'วิธีการใช้งาน', 'type'=>'cat', 'search'=>'tag', 'search_value'=>'', 'display_type' => '' , 'result'=>'', 'link'=>'howto', 'display'=>'Y', 'article_short_desc'=>'', 'target'=>'_blank' )
      , 'privacy policy' => array('txt'=>'นโยบายความเป็นส่วนตัว', 'type'=>'cat', 'search'=>'tag', 'search_value'=>'', 'display_type' => '' , 'result'=>'', 'link'=>'privacy', 'display'=>'Y', 'article_short_desc'=>'', 'target'=>'_blank' )
      , 'signout' => array('txt'=>'ออกจากระบบ', 'type'=>'cat', 'search'=>'tag', 'search_value'=>'', 'display_type' => '' , 'result'=>'', 'link'=>'signout', 'display'=>'Y', 'article_short_desc'=>'', 'target'=>'' )
    );
} else {
    $lang['menu_array'] = array(
        'signup' => array('txt'=>'ลงทะเบียน', 'type'=>'cat', 'search'=>'tag', 'search_value'=>'', 'display_type' => '', 'result'=>'', 'link'=>'signup', 'display'=>'Y', 'article_short_desc'=>'ลงทะเบียน', 'target'=>'' )
      , 'signin' => array('txt'=>'เข้าสู่ระบบ', 'type'=>'cat', 'search'=>'tag', 'search_value'=>'', 'display_type' => '' , 'result'=>'', 'link'=>'signin', 'display'=>'Y', 'article_short_desc'=>'เข้าสู่ระบบ', 'target'=>'' )
      , 'forgot-password' => array('txt'=>'ลืมรหัสผ่าน', 'type'=>'cat', 'search'=>'tag', 'search_value'=>'', 'display_type' => '' , 'result'=>'', 'link'=>'password/reset', 'display'=>'Y', 'article_short_desc'=>'ลืมรหัสผ่าน', 'targrt'=>'' )  
      , 'howto' => array('txt'=>'วิธีการใช้งาน', 'type'=>'cat', 'search'=>'tag', 'search_value'=>'', 'display_type' => '' , 'result'=>'', 'link'=>'howto', 'display'=>'Y', 'article_short_desc'=>'วิธีการใช้งาน', 'target'=>'_blank' )
      , 'privacy policy' => array('txt'=>'นโยบายความเป็นส่วนตัว', 'type'=>'cat', 'search'=>'tag', 'search_value'=>'', 'display_type' => '' , 'result'=>'', 'link'=>'privacy', 'display'=>'Y', 'article_short_desc'=>'', 'target'=>'_blank' )  
    );
}

/* email */
$lang['email_from'] = "Production Cost";
$lang['email_title'] = "Online Booking:";

$lang['name_prefix']  = "คุณ";

/* Signin */
$lang['signin_prefix']  = array("นาย"=>"นาย","นางสาว"=>"นางสาว","นาง"=>"นาง");
$lang['signin_title']    = "เข้าสู่ระบบ";
$lang['signin_subtitle']  = "กรุณาเข้าสู่ระบบ<br class='show-mobile'>เพื่อทำการจองห้องออนไลน์";
$lang['signin_email']  = "อีเมล";
$lang['signin_password']   = "รหัสผ่าน";
$lang['signin_button']    = "เข้าสู่ระบบ";
$lang['signin_forgetpassword']   = "ลืมรหัสผ่าน";

/* Signup */
$lang['sign_back']   = "ย้อนกลับ";	
$lang['sign_next']   = "ต่อไป";	
$lang['sign_confirm']   = "ยืนยัน";	
	
$lang['sign_title']    = "ลงทะเบียน";

$lang['sign_subtitle1']  = "สร้างบัญชีผู้ใช้";
$lang['sign_subtitle11']  = "(กรุณากรอกข้อมูลให้ครบถ้วน)";
$lang['sign_email']  = "อีเมล";
$lang['sign_password']   = "รหัสผ่าน";
$lang['sign_password_confirm']    = "ยืนยันรหัสผ่าน";
$lang['sign_password_valid']    ="รหัสผ่านต้องมีอย่างน้อย 8 ตัวอักษร";

$lang['sign_subtitle2']  = "กรอกข้อมูลผู้ใช้ ";
$lang['sign_subtitle21']  = "(กรุณากรอกข้อมูลให้ครบถ้วน)";
$lang['sign_subtitle22']  = "ที่อยู่ตามบัตรประชาชน";
$lang['sign_subtitle23']  = "(ข้อมูลสำคัญการจอง ONLINE BOOKING)";
$lang['sign_prefix']  = "คำนำหน้า";
$lang['sign_name']   = "ชื่อ";
$lang['sign_surname']    = "นามสกุล";
$lang['sign_citizen_id']   = "เลขที่บัตรประชาชน";
$lang['sign_passport_id']   = "Passport No.";
$lang['sign_phone']   = "เบอร์โทรศัพท์มือถือ";
$lang['sign_address_no']   = "บ้านเลขที่";
$lang['sign_village']   = "หมู่บ้าน/อาคาร";
$lang['sign_soi']   = "ซอย";
$lang['sign_province']   = "จังหวัด";
$lang['sign_country']   = "ประเทศ";
$lang['sign_district']   = "อำเภอ/เขต";
$lang['sign_subdistrict']   = "ตำบล/แขวง";
$lang['sign_postcode']   = "รหัสไปรษณีย์";
$lang['sign_province_info']   = "กรุณาระบุจังหวัด";
$lang['sign_district_info']   = "กรุณาระบุอำเภอ/เขต";
$lang['sign_subdistrict_info']   = "กรุณาระบุตำบล/แขวง";

$lang['sign_subtitle3']  = "กรุณากรอกข้อมูล<br class='show-mobile'เพื่อสร้างบัญชีผู้ใช้ก่อนทำการจอง ";
$lang['sign_subtitle31']  = "ที่อยู่ในการจัดส่งเอกสาร ";
$lang['sign_sameas']  = "ใช้ที่อยู่ตามบัตรประชาชน ";

$lang['sign_subtitle4']  = "กรุณายืนยันข้อมูล<br class='show-mobile'เพื่อสร้างบัญชีผู้ใช้ก่อนทำการจอง ";
$lang['sign_name_surname']   = "ชื่อ - นามสกุล";
$lang['sign_address']   = "ที่อยู่ตามบัตรประชาชน";
$lang['sign_address_doc']   = "ที่อยู่ในการจัดส่งเอกสาร";

$lang['sign_status_1'] = "สร้างบัญชีผู้ใช้";
$lang['sign_status_2'] = "กรอกบัญชีผู้ใช้";
$lang['sign_status_3'] = "ยืนยัน";

$lang['sign_success'] = "ลงทะเบียนสำเร็จ";

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
$lang['sign_verify_email_body'] = "<b>คุณได้ทำการลงทะเบียนเพื่อจองผ่านช่องทางออนไลน์<br>ของบริษัท แกรนด์แอสเสท โฮเทลล์ แอนด์ พรอพเพอร์ตี้ จำกัด (มหาชน)<br>กรุณายืนยันการลงทะเบียน โดยการคลิกปุ่มด้านล่าง</b><br><br>บริษัท แกรนด์ แอสเสท แอนด์ พรอพเพอร์ตี้ จำกัด (มหาชน)<br>สอบถามข้อมูลเพิ่มเติม CALL CENTER 02 204 9900<br><br><i>อีเมลนี้ถูกจัดส่งด้วยระบบอัติโนมัติ โปรดอย่าตอบกลับอีเมลนี้</i><br><br><a href='%s'><div style='border: 1px solid #7c6b47;padding: 8px 16px;display: inline-block;margin-top: 25px;background-color: #ecd9af;font-weight: bold;;color:#000'>%s</div></a>";
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
$lang['booking_title'] = "เลือกห้อง";
$lang['booking_status_1'] = "เลือกโครงการ";
$lang['booking_status_2'] = "เลือกห้อง";
$lang['booking_status_3'] = "ยืนยันและชำระเงิน";
$lang['booking_active'] = "ห้องว่าง";
$lang['booking_inactive'] = "จองแล้ว";
$lang['booking_back'] = "ย้อนกลับ";
$lang['booking_next'] = "จอง";
$lang['booking_select_floor'] = "เลือกชั้น";
$lang['booking_select_roomtype'] = "เลือกประเภทห้อง";
$lang['booking_title_select'] = "กรุณาเลือกชั้น และประเภทห้อง<span class='br'> </span>ที่ท่านต้องการจอง BOOKING ONLINE";
$lang['booking_title_confirm'] = "กรุณาตรวจสอบข้อมูลและรายละเอียดเพื่อความถูกต้อง";
$lang['booking_please_select'] = "กรุณาเลือกห้อง";
$lang['booking_name'] = "ชื่อ - นามสกุล";
$lang['booking_address']   = "ที่อยู่";
$lang['booking_currency'] = "บาท";
$lang['booking_reservation_information'] = "ข้อมูลผู้จอง";
$lang['booking_reservation_summary'] = "สรุปการจอง";
$lang['booking_unit'] = "ยูนิต";
$lang['booking_floor'] = "ชั้น";
$lang['booking_roomtype'] = "แบบห้อง";
$lang['booking_room_size'] = "ขนาดห้อง";
$lang['booking_room_price'] = "ราคาห้อง";
$lang['booking_booking_price'] = "ค่าใช้จ่ายในการจอง";
$lang['booking_error'] = "แจ้งเตือน";
$lang['booking_confirm_pay'] = "ยอมรับเงื่อนไขการจองและชำระเงิน";
$lang['booking_confirm_hi'] = "สวัสดีคุณ";
$lang['booking_confirm_success'] = "ยืนยันคำสั่งซื้อของคุณ";
$lang['booking_confirm_fail'] = "ทำรายการจองไม่สำเร็จ";
$lang['booking_confirm_text'] = "ทางเราได้รับข้อมูลของท่านแล้ว ทางเราจะรีบติดต่อกลับโดยเร็วที่สุด";


/* Projects */
$lang['projects_title'] = "เลือกโครงการ";
$lang['projects_subtitle'] = "กรุณาเลือกโครงการที่ต้องการ<br class='show-mobile'>จอง BOOKING ONLINE";
$lang['projects_select'] = "เลือก";


/* Project */
$lang['project_home'] = "หน้าแรก"; 
$lang['project_detail'] = "รายละเอียดโครงการ"; 
$lang['project_facility'] = "สิ่งอำนวยความสะดวก"; 
$lang['project_roomtype'] = "แบบห้อง"; 
$lang['project_gallery'] = "แกลลอรี่"; 
$lang['project_360'] = "มุมมอง 360";
$lang['project_booking'] = "จอง";
$lang['project_back'] = "ย้อนกลับ";

$lang['project_home_url'] = url_title($lang['project_home']); 
$lang['project_detail_url'] = url_title($lang['project_detail']); 
$lang['project_facility_url'] = url_title($lang['project_facility']); 
$lang['project_roomtype_url'] = url_title($lang['project_roomtype']); 
$lang['project_gallery_url'] = url_title($lang['project_gallery']); 
$lang['project_360_url'] = url_title($lang['project_360']); 
$lang['project_booking_url'] = url_title($lang['project_booking']); 
$lang['project_back_url'] = url_title($lang['project_back']); 


/* profile */
$lang['profile_title'] = "ข้อมูลส่วนตัว";
$lang['profile_edit'] = "แก้ไข";
$lang['profile_home'] = "หน้าหลัก";


/* change password */
$lang['chpass_title'] = "เปลี่ยนรหัสผ่าน"; 
$lang['chpass_email'] = "อีเมล";
$lang['chpass_oldpassword'] = "รหัสผ่านเดิม";
$lang['chpass_newpassword'] = "รหัสผ่านใหม่";
$lang['chpass_confirm'] = "ยืนยันรหัสผ่านใหม่";
$lang['chpass_button'] = "ยืนยัน";
$lang['chpass_success'] = "คุณได้ทำการแก้ไขข้อมูลรหัสผ่านเรียบร้อยแล้ว";
$lang['chpass_home'] = "กลับหน้าหลัก";


/* reset password */
$lang['resetpass_title'] = "Reset Password"; 
$lang['resetpass_detail'] = "We can help you reset your password using email address linked to your account.";
$lang['resetpass_email'] = "Email";
$lang['resetpass_button'] = "Reset Password"; 
$lang['resetpass_success'] = "Success reset your password. <br>Please check you email for create new password within 30 minutes";
$lang['resetpass_home'] = "Home";
$lang['resetpass_link'] = "ตั้งรหัสผ่านใหม่";
$lang['resetpass_emaildear'] = "เรียนคุณ";
$lang['resetpass_emailcontent'] = "คุณได้แจ้งลืมรหัสผ่าน<br>กรุณาคลิก %s เพื่อตั้งรหัสผ่านใหม่";


/* new password */
$lang['newpass_title'] = "Reset Password"; 
$lang['newpass_detail'] = "Create your new password."; 
$lang['newpass_success'] = "Success reset your password. Please login with your new password."; 
$lang['newpass_detail_invalid'] = "Invalid link for reset password."; 


/* History */
$lang['history_title'] = "ประวัติการจอง"; 
$lang['history_button'] = "กลับหน้าหลัก"; 
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
$lang['term_title'] = "<h1>ข้อตกลงและเงื่อนไข</h1>"; 
$lang['term_detail_old'] = <<<TERM
ทั่วไป<br>
การเข้าและการใช้งานเว็บไซต์ของ บริษัท แกรนด์ แอสเสท โฮเทลส์ แอนด์ พรอพเพอร์ตี้ จำกัด (มหาชน) 
เป็นไปตามกฎหมายของประเทศไทยและกฎและเงื่อนไขดังต่อไปนี้<br><br>
ลิขสิทธิ์<br>
ข้อความทั้งหมดภายในเว็บไซต์นี้อยู่ภายใต้ลิขสิทธิ์ของบริษัท แกรนด์ แอสเสท โฮเทลส์ แอนด์ พรอพเพอร์ตี้ จำกัด (มหาชน) (2559) 
ขอสงวนสิทธิ์ในข้อความ รูปภาพ เสียง ซอฟท์แวร์ และอื่นๆ ที่ปรากฏอยู่ในเว็บไซต์แห่งนี้ซึ่ง
บริษัท แกรนด์ แอสเสท โฮเทลส์ แอนด์ พรอพเพอร์ตี้ จำกัด (มหาชน) เป็นเจ้าของลิขสิทธิ์และทรัพย์สินทางปัญญาแต่เพียงผู้เดียว 
บริษัท แกรนด์ แอสเสท โฮเทลส์ แอนด์ พรอพเพอร์ตี้ จำกัด (มหาชน) อนุญาตให้ท่านเรียกดูเว็บไซต์ หรือตัดทอนข้อความเหล่านี้
ด้วยวิธีสั่งพิมพ์บนกระดาษ การถ่ายโอนข้อมูลใส่สื่อ บันทึก หรือแจกจ่ายข้อมูลให้บุคคลอื่น เพื่อใช้เป็นข้อมูลส่วนบุคคล 
อันมิใช่เพื่อผลประโยชน์ทางการค้า ห้ามมิให้ใช้ในกรณีใดๆ เพื่อ การค้า หรือ เพื่อผลประโยชน์ทางการค้า หรือ ดัดแปลง
และเอาไปใช้ในงานอื่นๆ ทั้งด้านสื่อสิ่งพิมพ์และเว็บไซต์ไม่ว่าจะในรูปแบบใดๆ รวมถึงการนำไปใช้ในเว็บไซต์อื่นๆ<br><br>
เครื่องหมายการค้า<br>
เครื่องหมายการค้าที่ปรากฏในเว็บไซต์นี้เป็นกรรมสิทธิ์ของบริษัท แกรนด์ แอสเสท โฮเทลส์ แอนด์ พรอพเพอร์ตี้ จำกัด (มหาชน)<br><br>
เนื้อหา<br>
ข้อมูลในเว็บไซต์นี้ใช้สาหรับเป็นข้อมูลทั่วไปเท่านั้น มิได้มีเพื่อวัตถุประสงค์ใดวัตถุประสงค์หนึ่งโดยเฉพาะ และไม่มีการประกันความ
เที่ยงตรงและความสมบูรณ์ ทั้งบริษัท แกรนด์ แอสเสท โฮเทลส์ แอนด์ พรอพเพอร์ตี้ จำกัด (มหาชน) และพนักงาน หรือตัวแทน 
TERM;

$lang['term_detail'] = <<<TERM
<b>ไทย</b><br>
<b>รายละเอียดการชำระเงิน</b><br>
- <b>เงินทำสัญญา (บาท)(5%)</b> : ชำระในวันที่ลงนามในสัญญาฯ (7 วันหลังจากวันจอง)<br>
- เงินงวดสุดท้าย (บาท) : ชำระในวันที่โอนกรรมสิทธิ์ห้องชุดที่จอง<br><br>
<b>ชาวต่างชาติ</b><br>
<b>รายละเอียดการชำระเงิน</b><br>
- <b>เงินทำสัญญา (บาท)(15%)</b> : ชำระในวันที่ลงนามในสัญญาฯ (7 วันหลังจากวันจอง)<br>
- เงินงวดสุดท้าย (บาท) : ชำระในวันที่โอนกรรมสิทธิ์ห้องชุดที่จอง<br><br>
<b>ข้อกำหนด:</b><br>
1) ราคานี้มีผลบังคับใช้ภายใน 1 วัน<br>
2) การชำระเงินให้สั่งจ่ายในนาม <b>“บริษัท แกรนด์ แอสเสท โฮเทลส์ แอนด์ พรอพเพอร์ตี้ จำกัด (มหาชน)”</b><br>
3) ผู้จะซื้อจ่ายเงินกองทุน 600 บาท ต่อตารางเมตร (จ่ายครั้งเดียว)<br>
4) ผู้จะซื้อจ่ายค่าส่วนกลาง 60 บาท ต่อตารางเมตร จ่ายล่วงหน้า 12 เดือน<br>
5) ค่าธรรมเนียมโอน ผู้ขายและผู้ซื้อออกค่าใช้จ่ายคนละครึ่ง<br>
*ราคาดังกล่าวขึ้นอยู่กับห้องชุดที่มี และอาจมีการเปลี่ยนแปลงโดยไม่ต้องมีการแจ้งให้ทราบล่วงหน้า<br>
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
<h2>นโยบายการรักษาข้อมูลส่วนบุคคลไว้เป็นความลับ</h2><br>
<p style='text-indent: 2em'>เว็บไซต์นี้จัดทำขึ้นและดำเนินการโดย บริษัท แกรนด์ แอสเสท โฮเทลส์ แอนด์ พรอพเพอร์ตี้ จำกัด (มหาชน) (“บริษัท”) บริษัทจะคุ้มครองความเป็นส่วนตัวและจัดการกับข้อมูลส่วนบุคคลใด ๆ ท่านส่งให้บริษัทด้วยความใส่ใจและด้วยความเคารพ เพื่อให้ท่านสามารถจองห้องผ่านเว็บไซต์ได้ ก่อนที่ท่านจะใช้งานเว็บไซต์นี้ ขอความร่วมมือท่านอ่านกำหนดและเงื่อนไข และนโยบายการรักษาข้อมูลส่วนบุคคลไว้เป็นความลับของเว็บไซต์นี้ หากท่านไม่สามารถยอมรับข้อกำหนดและเงื่อนไขและนโยบายการรักษาข้อมูลส่วนบุคคลไว้เป็นความลับในการใช้งานเว็บไซต์นี้ ใคร่ขอให้ท่านยุติการใช้งานเว็บไซต์นี้ ทันที หากท่านตกลงใช้เว็บไซต์ต่อไป ถือว่าท่านยินยอมตามข้อกำหนดและเงื่อนไขและนโยบายการรักษาข้อมูลส่วนบุคคลไว้เป็นความลับ โดยมีรายละเอียดดังนี้</p>
<b>1. คำนิยาม</b><br>
<div class='indent'><b>“ข้อมูลส่วนบุคคล”</b> หมายถึง ข้อมูลเกี่ยวกับบุคคล ซึ่งทำให้สามารถระบุตัวบุคคลนั้นได้ไม่ว่าทางตรงหรือทางอ้อม</div>
<div class='indent'><b>“คุกกี้ (Cookies)”</b> หมายถึง ข้อมูลที่ได้ส่งจากเว็บไซต์ไปยังเครื่องคอมพิวเตอร์ หรือเครื่องมือสื่อสารอื่น ๆ ของผู้เข้าเยี่ยมชมเว็บไซต์ ในขณะที่ผู้เข้าเยี่ยมชมเว็บไซต์นั้นกำลังเข้าเยี่ยมชมเว็บไซต์</div><br>
<b>2. การเก็บรวบรวมข้อมูลส่วนบุคคล</b><br>
<div class='indent'>2.1 บริษัทจะเก็บรวบรวมข้อมูลส่วนบุคคลของท่านที่บริษัทได้รับ อันประกอบด้วย<br>
<div class='indent'>• ข้อมูลส่วนตัว เช่น ชื่อ นามสกุล เพศ อายุ สัญชาติ วันเกิด สถานภาพสมรส ที่อยู่ อาชีพ สถานที่ทำงาน รหัสไปรษณีย์ ที่อยู่ไปรษณีย์อิเล็กทรอนิกส์ (Email Address) หมายเลขโทรศัพท์ บัตรเครดิต เป็นต้น<br>
• บริษัทรับรองว่าจะไม่นำข้อมูลส่วนบุคคลของท่าน ที่ได้เก็บรวบรวมผ่านเว็บไซต์นี้ไปขายหรือเผยแพร่ให้แก่บุคคลภายนอกโดยเด็ดขาด เว้นแต่จะได้รับอนุญาตจากท่านเท่านั้น<br>
• บริษัทจะเก็บรวบรวมข้อมูลส่วนบุคคลของท่านเพียงเท่าที่จำเป็นหรือเพื่อประโยชน์ตามวัตถุประสงค์ในการเก็บรวบรวมเท่านั้น<br>
• บริษัทอาจส่งจดหมายข่าว หรือรายการส่งเสริมการขายไปให้ท่านเป็นครั้งคราว หากท่านไม่ประสงค์จะรับจดหมายข่าว หรือรายการส่งเสริมการขายเช่นว่านั้น ท่านอาจเลือก “ไม่รับ” ก็ได้</div></div><br>
<b>3. วัตถุประสงค์ในการเก็บรวบรวม</b><br>
<div class='indent'>• เพื่อการทำรายการตามที่ท่านประสงค์ในกรณีการจองออนไลน์ (Online Booking) บริษัทจะทำการจัดเก็บและใช้ข้อมูลที่จำเป็นในกาออกเอกสารสำคัญเพื่อการทำรายการจอง<br>
• บริษัทจะไม่ใช้หรือเปิดเผยข้อมูลส่วนบุคคลของท่าน เว้นแต่ เป็นการใช้หรือเปิดเผยเท่าที่จำเป็นตามวัตถุประสงค์ที่ระบุไว้ข้างต้น หรือ เป็นการเปิดเผยต่อบุคคลในบริษัทหรือบริษัทร่วม หรือบุคคลที่เกี่ยวข้องตามสัญญา หรือได้รับความยินยอมจากท่าน<br>
• เพื่อวิเคราะห์แนวโน้ม จัดการเว็บไซต์ เพื่อเป็นส่วนประกอบในการเสนอบริการที่ดียิ่งขึ้นแก่ลูกค้า</div><br>
<b>4. การใช้คุกกี้ (Cookies)</b><br>
<div class='indent'>คุ้กกี้ หมายถึงข้อมูลที่ถูกวางในคอมพิวเตอร์ของท่านโดยเว็บเซิร์ฟเวอร์หลังจากคุ้กกี้ได้ถูกวางในคอมพิวเตอร์ของท่าน คุ้กกี้จะเก็บหรือจดจำข้อมูลของผู้ใช้จนกว่าผู้ใช้จะปิดบราวเซอร์นั้น หรือจนกว่าผู้ใช้จะลบหรือปฏิเสธคุ้กกี้ อย่างไรก็ตาม ท่านจะพบว่าจะเป็นการสะดวกในการนำทางการใช้เว็บไซต์ได้อย่างง่ายดาย เพราะคุ้กกี้จะช่วยเก็บข้อมูลเว็บไซต์ซึ่งท่านเยี่ยมชมหรือเปิดขึ้น</div><br>
<b>5. การปรับปรุงแก้ไขรายละเอียดเกี่ยวกับนโยบายการรักษาข้อมูลส่วนบุคคลไว้เป็นความลับ</b><br>
<div class='indent'>หากท่านมีคำถาม หรือข้อแนะนำติชมใดๆ เกี่ยวกับข้อกำหนดของนโยบายนี้ หรือการบังคับใช้นโยบายดังกล่าว โปรดติดต่อบริษัท และบริษัทยินดีที่จะตอบคำถามและรับฟังข้อแนะนำติชม ซึ่งจะนำไปสู่การพัฒนาการบริการของบริษัท แกรนด์ แอสเสท โฮเทลส์ แอนด์ พรอพเพอร์ตี้ จำกัด (มหาชน) ให้ดีขึ้นต่อไป ท่านสามารถติดต่อเราได้ที่ <a href='onlinebooking@grandeasset.com' target='_blank'>onlinebooking@grandeasset.com.</a> อนึ่ง บริษัทอาจปรับปรุงนโยบายนี้ไม่ว่าในเวลาใดโดยไม่ต้องบอกกล่าวแก่ท่าน ซึ่งบริษัทจะนำขึ้นแสดงที่เว็บไซต์ที่หน้าแรกตามลำดับ และในสถานที่อื่น ๆ ตามที่บริษัทเห็นสมควร นโยบายที่มีการปรับปรุงแล้วนี้ จะใช้บังคับต่อข้อมูลส่วนบุคคลทุกประการของท่าน</div><br>
<b>6. วันที่มีผลบังคับใช้</b><br>
<div class='indent'>นโยบายนี้ให้มีผลบังคับใช้ตั้งแต่ ( วันที่เปิดใช้เว็บไซต์  ) นโยบายที่มีการปรับปรุงในภายหลังให้มีผลในวันที่บริษัทแสดงไว้ที่หน้าแรกของเว็บไซต์ตามลำดับ และในสถานที่อื่น ๆ ตามที่บริษัทเห็นสมควร</div><br>
EOT;
