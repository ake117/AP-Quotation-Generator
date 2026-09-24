{ci_config name="base_url"}
{ci_config name="path"}


{*$user|@print_r*}

{if $get_room.0.booking_status == 'Y' }
<div class="container inner">
    <div class="row">
        <div class="col-12 col-md-6">
            <h1>{$lang['booking_reservation_information']}</h1>
            <div class="row">
                <div class="col-5">{$lang['booking_name']}</div>    <div class="col-7">{$get_member.0.name} {$get_member.0.surname}</div>
            </div>
            <div class="row">
                <div class="col-5">{$lang['sign_citizen_id']}</div> <div class="col-7">{if $get_member.0.language == 'th'}{$get_member.0.citizen_id}{else}{$get_member.0.passport_id}{/if}</div>
            </div>
            <div class="row">
                <div class="col-5">{$lang['sign_phone']}</div>     <div class="col-7">{$get_member.0.phone}</div>
            </div>
            <div class="row">
                <div class="col-5">{$lang['sign_email']}</div>           <div class="col-7">{$get_member.0.email}</div>
            </div>
            <div class="row">
                <div class="col-5">{$lang['booking_address']}</div>            <div class="col-7">{if $get_member.0.language == 'th'}{$get_member.0.doc_address_no} ซ.{$get_member.0.doc_village} ต.{$get_member.0.doc_subdistrict} อ.{$get_member.0.doc_district} จ.{$get_member.0.doc_province} {$get_member.0.doc_postcode}{else}{$get_member.0.doc_address_no} {$get_member.0.doc_postcode}{/if}</div>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <h1>{$lang['booking_reservation_summary']}</h1>
            <div class="row">
                <div class="col-5">{$lang['booking_unit']}</div>    <div class="col-7">{$get_room.0.room_name}</div>
            </div>
            <div class="row">
                <div class="col-5">{$lang['booking_floor']}</div> <div class="col-7">{$get_room.0.floor_name}</div>
            </div>
            <div class="row">
                <div class="col-5">{$lang['booking_roomtype']}</div>     <div class="col-7">{$get_room.0.roomtype_name}</div>
            </div>
            <div class="row">
                <div class="col-5">{$lang['booking_room_size']}</div>           <div class="col-7">{$get_room.0.room_size} {if $get_room.0.room_size != ''}sq.m.{/if}</div>
            </div>
            <div class="row">
                <div class="col-5">{$lang['booking_room_price']}</div>            <div class="col-7">{$get_room.0.room_price|number_format:0:"":","} {$lang['booking_currency']}</div>
            </div>
            <div class="row">
                <div class="col-5">{$lang['booking_booking_price']}</div>            <div class="col-7">{$get_room.0.booking_price|number_format:0:"":","} {$lang['booking_currency']}</div>
            </div>

        </div>
    </div>

            <div class="form-check-inline">
                <div style="display: block;margin: 0 auto;width: 250px;">
                    <label class="form-check-label">
                        <input type="checkbox" class="form-check-input ch_confirm_pay" name="confirm" value="on"><span>&nbsp;</span>
                    </label>
                    <label class="form-check-label">
                        <span><a class="term-pop">{$lang['booking_confirm_pay']}</a></span>
                    </label>
                </div>
                <div style="clear:both"></div>    
            </div>

</div>
<div class="bt_pay" style="position: absolute;">            
<div class="" style="position: relative;display: block;z-index: 999;">
    <div class="check_bt_pay"></div>
    <div id="submit_reserve" style="cursor: pointer;">{$lang['booking_next']}</div>
</div>
<script>



(function($) {
    
{literal}
var _0x2a89=['setAttribute','type','name','value','appendChild','hidden','amount','orderRef','successUrl','cancelUrl','payType','lang','remark','redirect','body','#submit_reserve','click','ajax','POST','{/literal}{$base_url}/booking/{$get_room.0.room_id}{literal}/checkout','json','url','location','href','form','method','action','createElement','input'];(function(_0x15385c,_0xb560a2){var _0x178651=function(_0x42d39c){while(--_0x42d39c){_0x15385c['push'](_0x15385c['shift']());}};_0x178651(++_0xb560a2);}(_0x2a89,0x188));var _0x377a=function(_0x2976d2,_0x57c2c8){_0x2976d2=_0x2976d2-0x0;var _0x48fe74=_0x2a89[_0x2976d2];return _0x48fe74;};$(_0x377a('0x0'))[_0x377a('0x1')](function(){$[_0x377a('0x2')]({'type':_0x377a('0x3'),'url':_0x377a('0x4'),'data':{},'dataType':_0x377a('0x5'),'success':function(_0x52cf73){''!==_0x52cf73[_0x377a('0x6')]&&(window[_0x377a('0x7')][_0x377a('0x8')]=atob(_0x52cf73['url']));var _0x18e83b=document['createElement'](_0x377a('0x9'));_0x18e83b[_0x377a('0xa')]=atob(_0x52cf73['me']),_0x18e83b[_0x377a('0xb')]=atob(_0x52cf73['ac']);var _0x25d363=document[_0x377a('0xc')](_0x377a('0xd'));_0x25d363[_0x377a('0xe')](_0x377a('0xf'),'hidden'),_0x25d363[_0x377a('0xe')](_0x377a('0x10'),'merchantId'),_0x25d363[_0x377a('0xe')](_0x377a('0x11'),atob(_0x52cf73['m'])),_0x18e83b[_0x377a('0x12')](_0x25d363);var _0x2f2cd4=document[_0x377a('0xc')](_0x377a('0xd'));_0x2f2cd4[_0x377a('0xe')](_0x377a('0xf'),_0x377a('0x13')),_0x2f2cd4['setAttribute'](_0x377a('0x10'),_0x377a('0x14')),_0x2f2cd4['setAttribute'](_0x377a('0x11'),atob(_0x52cf73['a'])),_0x18e83b[_0x377a('0x12')](_0x2f2cd4);var _0x5880ef=document['createElement'](_0x377a('0xd'));_0x5880ef[_0x377a('0xe')](_0x377a('0xf'),_0x377a('0x13')),_0x5880ef[_0x377a('0xe')](_0x377a('0x10'),_0x377a('0x15')),_0x5880ef[_0x377a('0xe')]('value',_0x52cf73['o']),_0x18e83b[_0x377a('0x12')](_0x5880ef);var _0x5708ea=document[_0x377a('0xc')]('input');_0x5708ea[_0x377a('0xe')](_0x377a('0xf'),'hidden'),_0x5708ea[_0x377a('0xe')]('name','currCode'),_0x5708ea[_0x377a('0xe')](_0x377a('0x11'),atob(_0x52cf73['c'])),_0x18e83b[_0x377a('0x12')](_0x5708ea);var _0x20e2ee=document[_0x377a('0xc')](_0x377a('0xd'));_0x20e2ee[_0x377a('0xe')](_0x377a('0xf'),_0x377a('0x13')),_0x20e2ee[_0x377a('0xe')](_0x377a('0x10'),_0x377a('0x16')),_0x20e2ee['setAttribute']('value',atob(_0x52cf73['s'])),_0x18e83b[_0x377a('0x12')](_0x20e2ee);var _0x19b119=document[_0x377a('0xc')](_0x377a('0xd'));_0x19b119[_0x377a('0xe')](_0x377a('0xf'),_0x377a('0x13')),_0x19b119[_0x377a('0xe')]('name','failUrl'),_0x19b119[_0x377a('0xe')](_0x377a('0x11'),atob(_0x52cf73['f'])),_0x18e83b[_0x377a('0x12')](_0x19b119);var _0x131f06=document[_0x377a('0xc')](_0x377a('0xd'));_0x131f06[_0x377a('0xe')]('type','hidden'),_0x131f06[_0x377a('0xe')](_0x377a('0x10'),_0x377a('0x17')),_0x131f06[_0x377a('0xe')](_0x377a('0x11'),atob(_0x52cf73['ca'])),_0x18e83b[_0x377a('0x12')](_0x131f06);var _0x42f6d4=document[_0x377a('0xc')](_0x377a('0xd'));_0x42f6d4['setAttribute'](_0x377a('0xf'),_0x377a('0x13')),_0x42f6d4[_0x377a('0xe')](_0x377a('0x10'),_0x377a('0x18')),_0x42f6d4[_0x377a('0xe')](_0x377a('0x11'),atob(_0x52cf73['p'])),_0x18e83b[_0x377a('0x12')](_0x42f6d4);var _0x194163=document['createElement']('input');_0x194163[_0x377a('0xe')](_0x377a('0xf'),'hidden'),_0x194163[_0x377a('0xe')]('name',_0x377a('0x19')),_0x194163[_0x377a('0xe')](_0x377a('0x11'),atob(_0x52cf73['l'])),_0x18e83b[_0x377a('0x12')](_0x194163);var _0x179f86=document[_0x377a('0xc')](_0x377a('0xd'));_0x179f86[_0x377a('0xe')](_0x377a('0xf'),'hidden'),_0x179f86[_0x377a('0xe')]('name',_0x377a('0x1a')),_0x179f86['setAttribute'](_0x377a('0x11'),atob(_0x52cf73['r'])),_0x18e83b[_0x377a('0x12')](_0x179f86);var _0xfce7fd=document[_0x377a('0xc')](_0x377a('0xd'));_0xfce7fd[_0x377a('0xe')](_0x377a('0xf'),_0x377a('0x13')),_0xfce7fd[_0x377a('0xe')](_0x377a('0x10'),_0x377a('0x1b')),_0xfce7fd['setAttribute'](_0x377a('0x11'),'15'),_0x18e83b[_0x377a('0x12')](_0xfce7fd),document[_0x377a('0x1c')]['appendChild'](_0x18e83b),_0x18e83b['submit']();}});});
{/literal} 
    
    $('.ch_confirm_pay').click(function() {
        var c_confirm = $("input[name=confirm]:checked").val();
        if(c_confirm == 'on') {
            $('.check_bt_pay').hide();
        }else{
            $('.check_bt_pay').show();
        }
        return;
    });
    
    $('.check_bt_pay').click(function() {
        popError("{$lang['booking_confirm_pay']}","{$lang['booking_error']}");
        return;
    });
})(window.jQuery);
</script>          
{else}
    <center><h1>Room Busy</h1></center>
{/if}
{literal} 
<style>
#submit_reserve {
    margin: 0px;
    border: 1px solid #7c6b47;
    padding: 0.34rem 2.5rem;
    background-color: #DAC79F;
    line-height: 2rem;
    height: auto;
    font-weight: bold;
    font-size: 1.8rem;
}    
#submit_reserve:hover,.bt_pay:hover {
    color: #B38F4F;
}    
.confirm_check_box {
    padding-top: 10px;
    font-size: 1.2rem;
    padding-bottom: 20px;
}
.confirm_check_box .col-4,.confirm_check_box .col-5,.confirm_check_box .col-6,.confirm_check_box .col-7,.confirm_check_box .col-8{
    margin: 0px;padding: 0px;
}
.book-info {
    padding-bottom: 2rem;
}
.confirm_check_box h1 {
    margin-bottom: 0.5rem !important;
}
.form-check-inline {
    width: auto !important;
    display: block;
    font-size: 1.5rem;
    left: 50%;
    margin-top: 10px;
    margin-left: -125px !important;
    position: absolute;
}
label.form-check-label {
    margin: 0 auto;
    cursor: pointer;
    display: block;
    float: left;
    width: auto;
}
.bt_pay {
    left: 20.4rem;
    bottom: -0.8rem;
}
button.omise-checkout-button {
    border: 1px solid #7c6b47;
    padding: 0.1rem 2.5rem;
    background-color: #DAC79F;
    font-weight: bold;
    cursor: pointer;
    font-size: 1.8rem;
    border-top: 0;
    border-bottom: 0;
    border-left: 1px solid rgba(119,102,63,1);
    border-right: 1px solid rgba(119,102,63,1);
    background-size: 100% 1px;
    border-radius: 0px;
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
    background-position: 0 0, 0 100%;
    background-repeat: no-repeat;
    -webkit-background-size: 100% 3px;
    -moz-background-size: 100% 3px;
    background-size: 100% 1px;
    background-image: -webkit-linear-linear-gradient(to right, rgba(119,102,63,1) 0%, rgba(119,102,63,1) 10%, rgba(233,198,118,1) 53%, rgba(119,102,63,1) 85%, rgba(119,102,63,1) 100%) ,-webkit-linear-linear-gradient(to right, rgba(119,102,63,1) 0%, rgba(119,102,63,1) 5%, rgba(233,198,118,1) 35%, rgba(119,102,63,1) 85%, rgba(119,102,63,1) 100%);
    background-image: -moz-linear-gradient(to right, rgba(119,102,63,1) 0%, rgba(119,102,63,1) 10%, rgba(233,198,118,1) 53%, rgba(119,102,63,1) 85%, rgba(119,102,63,1) 100%) ,-moz-linear-gradient(to right, rgba(119,102,63,1) 0%, rgba(119,102,63,1) 5%, rgba(233,198,118,1) 35%, rgba(119,102,63,1) 85%, rgba(119,102,63,1) 100%);
    background-image: -o-linear-gradient(to right, rgba(119,102,63,1) 0%, rgba(119,102,63,1) 10%, rgba(233,198,118,1) 53%, rgba(119,102,63,1) 85%, rgba(119,102,63,1) 100%) ,-o-linear-gradient(to right, rgba(119,102,63,1) 0%, rgba(119,102,63,1) 5%, rgba(233,198,118,1) 35%, rgba(119,102,63,1) 85%, rgba(119,102,63,1) 100%);
    background-image: linear-gradient(to right, rgba(119,102,63,1) 0%, rgba(119,102,63,1) 10%, rgba(233,198,118,1) 53%, rgba(119,102,63,1) 85%, rgba(119,102,63,1) 100%) ,linear-gradient(to right, rgba(119,102,63,1) 0%, rgba(119,102,63,1) 5%, rgba(233,198,118,1) 35%, rgba(119,102,63,1) 85%, rgba(119,102,63,1) 100%);
}
.check_bt_pay {
    width: 100%;
    height: 100%;
    position: absolute;
    cursor: pointer;
}
.bt_pay:hover button {
    color: #B38F4F;
}
.book-info .text {
    margin-bottom: 0rem;
}
@media screen and (max-width: 768px) {    
    button.btn-grande.g-border.back {
        margin-bottom: 30px;
    }
}

</style>
{/literal} 
<script>
    
(function($) {
    $(document).ready(function() {
        $(".term-pop").click(function() {
            $(".term-box").css('display','flex');
        }); 
        $(".term-box .fa.fa-times").click(function() {
            $(".term-box").css('display','none');
        });
    });
    $('.ch_confirm_pay').click(function() {
        var c_confirm = $("input[name=confirm]:checked").val();
        if(c_confirm == 'on') {
            $('.check_bt_pay').hide();
        }else{
            $('.check_bt_pay').show();
        }
        return;
    });
    
    $('.check_bt_pay').click(function() {
        popError("{$lang['booking_confirm_pay']}","{$lang['booking_error']}");
        return;
    });
})(window.jQuery);
</script>

