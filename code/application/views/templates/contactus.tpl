{include file="header.tpl"}
{ci_config name="base_url"}
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key=AIzaSyBHHVJy2A8i3DoAo99fWocmxrZKKhd7I0U"></script>
<script src='https://www.google.com/recaptcha/api.js'></script>
{*foreach from=$articles->data item=article}
    <li>{$article->category_name}</li>
{/foreach*}

  <div class="tp-fullscreen-container revolution">
    <div class="tp-fullscreen">
      <ul>
        <!--<li data-transition="fade"> <img src="{$base_url}/assets/images/big-banner.jpg"  alt="" data-bgposition="center top" data-bgfit="cover" data-bgrepeat="no-repeat" />
          <h1 class="tp-caption large sfr" data-x="30" data-y="263" data-speed="900" data-start="800" data-easing="Sine.easeOut">ทางเลือก</h1>
          <div class="tp-caption large sfr" data-x="30" data-y="348" data-speed="900" data-start="1500" data-easing="Sine.easeOut">ของความอยู่รอด</div>
        </li>-->
        <li data-transition="fade"> <img src="{$base_url}/assets/images/banner-contact.jpg"  alt="" data-bgposition="center top" data-bgfit="cover" data-bgrepeat="no-repeat" />
          <div class="tp-caption large text-center sfl" data-x="center" data-y="130" data-speed="900" data-start="800" data-easing="Sine.easeOut"></div>
          <div class="tp-caption large text-center sfr" data-x="center" data-y="330" data-speed="900" data-start="1500" data-easing="Sine.easeOut"></div>
        </li>
        <!--<li data-transition="fade"> <img src="{$base_url}/assets/images/big-banner.jpg"  alt="" data-bgposition="center top" data-bgfit="cover" data-bgrepeat="no-repeat" />
          <div class="tp-caption large text-<!--center sfl" data-x="center" data-y="130" data-speed="900" data-start="800" data-easing="Sine.easeOut">ทางเลือก</div>
          <div class="tp-caption large text-center sfr" data-x="center" data-y="330" data-speed="900" data-start="1500" data-easing="Sine.easeOut">ของความอยู่รอด</div>
        </li>
        <li data-transition="fade"> <img src="{$base_url}/assets/images/banner-crop.jpg"  alt="" data-bgposition="center top" data-bgfit="cover" data-bgrepeat="no-repeat" />
          <div class="tp-caption large text-center sfl" data-x="center" data-y="130" data-speed="900" data-start="800" data-easing="Sine.easeOut">ทางเลือก</div>
          <div class="tp-caption large text-center sfr" data-x="center" data-y="330" data-speed="900" data-start="1500" data-easing="Sine.easeOut">ของความอยู่รอด</div>
        </li>-->

      </ul>
      <div class="tp-bannertimer tp-bottom"></div>
    </div>
    <!-- /.tp-fullscreen-container -->
  </div>
  <!-- /.revolution -->

	<div class="show_fade_pop_mobile"></div>
  <div class="light-wrapper divgoto">
    <div class="container inner">
        <h3 class="section-title text-center">Contact us</h3>
        <hr class="hr-title" color=#79b6e4 style="margin-bottom: 10px;padding-top: 5px;width: 55px;">
        <div class="divide10"></div>
        <h4 class="text-center">นิตยสารส่งเสริมความคิดสร้างสรรค์ผลักดันเศรษฐกิจไทย</h4>
        <div class="divide10"></div>
        <div class="divide10"></div>
        <div class="row font-rsu">
            <div class="col-12 col-md-6">
              นิตยสาร Creative Thailand จัดทำขึ้นในรูปแบบนิตยสารฉบับแจกฟรี
(Free Copy) รายเดือน มีวัตถุประสงค์เพื่อสร้างแรงบันดาลใจ และกระตุ้น
ให้สังคมเล็งเห็นความสำคัญของ “ความคิดสร้างสรรค์” ในฐานะปัจจัย
การผลิตหลักสำหรับเศรษฐกิจไทยในอนาคต<br/><br/>

คุณสามารถหยิบ นิตยสาร Creative Thailand ได้ทุกเดือน ที่ TCDC และสถานที่ฝากวางนิตยสาร
            <a target="_blank" class="place_magazine" href="{$base_url}/assets/ct_mag/CT_MAG_2018.pdf">"สถานที่ฝากวางนิตยสารคิด"</a>
<br/><br/>

	<img src="{$base_url}/assets/images/ic-s-home.png" alt="ที่อยู่" style="width: auto;"/> ที่อยู่: ศูนย์สร้างสรรค์งานออกแบบ (TCDC) <br/>
อาคารไปรษณีย์กลาง เลขที่ 1160  ถนนเจริญกรุง แขวงบางรัก <br/>
เขตบางรัก กรุงเทพฯ 10501 <br/><br/>

	<img src="{$base_url}/assets/images/ic-s-tel.png" alt="โทร" style="width: auto;"/> โทร. +66 2105 7400  <br/>
        <img src="{$base_url}/assets/images/ic-s-fax.png" alt="แฟกซ์" style="width: auto;"/> แฟกซ์ +66 22 105 7450 <br/>
	<img src="{$base_url}/assets/images/ic-s-mail.png" alt="อีเมล" style="width: auto;"/> อีเมล creativethailand@tcdc.or.th <br/><br/>
        <div class="widget">
            <ul class="social color-black">
                <li><a href="{$facebook_url}" target="_blank"><i class="icon-s-facebook"></i></a></li>
                <li><a href="{$twitter_url}" target="_blank"><i class="icon-s-twitter"></i></a></li>
                <li><a href="{$youtube_url}" target="_blank"><i class="icon-s-youtube"></i></a></li>
                <li><a href="{$instagram_url}" target="_blank"><i class="icon-s-instagram"></i></a></li>
            </ul>
        </div><br/>
        <a target='_blank' href="{$base_url}/assets/ct_mag/CT_MAG_AD_RATE_ONLINE&OFFLINE_2018.pdf">
        <button class="btn btn-sponsor">
            ติดต่อโฆษณา
        </button>
        </a>
            </div>
            <div class="col-12 col-md-6">
<form name="userinput" id="myform" action="{$base_url}/contactus/contactus_mail" method="post" class="contact-form">
  <label for="name">ชื่อ (required)</label>
  <input type="text" name="name" placeholder="Name" id="name" />

  <label for="email">อีเมล (required)</label>
  <input type="text" name="email" placeholder="Enter email" id="email" />

  <label for="title">เรื่อง</label>
  <input type="text" name="title" placeholder="Subject" id="title" />

  <label for="message">ข้อความ</label>
  <textarea name="message" id="message" cols="1" rows="1"></textarea>

  <div style="position: relative;display: block;clear: both;">
      <div class="g-recaptcha" data-sitekey="6LfOImEUAAAAAC9UaYT4aD-tufXWgk9ugA9i-Bbj"></div>
  </div>
  <div class="divide10"></div>
  <input type="submit" value="ส่งข้อความ" class="btn btn-contact" />
</form>
            </div>

        </div>

    </div>
    <!--/.container -->

  </div>
<div class="light-wrapper">
    <div id="map_container"></div>
    <div id="map"></div>
</div>
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Warning</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p id="error"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<style>
    .font-rsu {
        font-size: 1.3rem;
        line-height: 2.0rem;
        letter-spacing: 0.5px;
    }
    .color-black a i {
        color: #000 !important;
        padding: 0.5rem;
        font-size: 1.5rem !important;
        border: 1px solid #000 !important;
    }
    .font-rsu img {
        vertical-align: unset;
    }
    button.btn.btn-sponsor {
        background: none;
        color: #000 !important;
        border: 1px solid #000;
        font-family: 'RSU', sans-serif;
        font-size: 1.2rem !important;
        padding: 10px 45px;
    }
    input.btn-contact {
        padding: 10px 40px;
        font-size: 1.2rem;
        background: #79b6e4;
        font-family: 'RSU', sans-serif;
    }

    #map_container {
        position: relative;
    }
    #map {
          height: 0;
          overflow: hidden;
          padding-bottom: 22.25%;
          padding-top: 30px;
          position: relative;
    }
    .contact-form,
    .contact-form label,
    .contact-form input[type='text'],
    .contact-form textarea {
      width: 100%;
      float: left;
    }

    .contact-form {
      margin-bottom: 15px;
    }

    .contact-form label {
        font-weight: bold;
    }

    .contact-form input[type='text'] {
        border: 1px solid rgb(0,0,0);
        background: none;
    }

    .contact-form input[type='text']:hover,
    .contact-form input[type='text']:focus,
    .contact-form textarea:hover,
    .contact-form textarea:focus {
      border: 1px solid rgb(0,0,0);
    }

    .contact-form textarea {
        border: 1px solid rgb(0,0,0);
        padding: 10px;
        margin-bottom: 10px;
        resize: none;
        background: none;
        min-height: 120px;
    }
@media (max-width: 500px){
    .inner {
        padding-bottom: 20px;
    }
    #map {
          height: 500px;
    }
}

</style>
<script>
$( document ).ready( function() {
    var error = '{$error}';
    if(error !== ''){
        $('#name').val('{$smarty.session.name}');
        $('#email').val('{$smarty.session.email}');
        $('#title').val('{$smarty.session.title}');
        $('#message').text('{$smarty.session.message}');
        $("#error").html(error);
        $('#myModal').modal("show");
    }

    $('#myform').submit(function() {
        var response = grecaptcha.getResponse();
        var name = $('#name').val();
        var email = $('#email').val();
        var message = $('#message').val();

        if (name.length === 0){
            $("#error").html('กรุณากรอก ชื่อ');
            $('#myModal').modal("show");
            return false;
        } else if(email.length === 0){
            $("#error").html('กรุณากรอก อีเมล');
            $('#myModal').modal("show");
            return false;
        } else if(message.length === 0){
            $("#error").html('กรุณากรอก ข้อความ');
            $('#myModal').modal("show");
            return false;
        } else if(response.length === 0){
            $("#error").html("I'm not a robot");
            $('#myModal').modal("show");
            return false;
        } else {

            return true;
        }
    });

    //Google Maps JS
    //Set Map
    function initialize() {
                    var myLatlng = new google.maps.LatLng(13.726671,100.514797);
                    var imagePath = '{$base_url}/assets/images/iconmap.png';
                    var mapOptions = {
                            zoom: 16,
                            center: myLatlng,
                            mapTypeId: google.maps.MapTypeId.ROADMAP
                    };

            var map = new google.maps.Map(document.getElementById('map'), mapOptions);
            //Callout Content
            var contentString = 'ศูนย์สร้างสรรค์งานออกแบบ (TCDC)<br/>Thailand Creative & Design Centre (TCDC)<br/>The Grand Postal Building 1160 Charoenkrung Road,<br/>Bangrak Bangkok 10501 Tel : 02-105-7400';
            //Set window width + content
            var infowindow = new google.maps.InfoWindow( {
                    content: contentString,
                    maxWidth: 500
            } );

            //Add Marker
            var marker = new google.maps.Marker( {
                    position: myLatlng,
                    map: map,
                    icon: imagePath,
                    title: 'ศูนย์สร้างสรรค์งานออกแบบ (TCDC)'
            });

            google.maps.event.addListener(marker, 'click', function() {
                    infowindow.open(map,marker);
            });

            //Resize Function
            google.maps.event.addDomListener(window, "resize", function() {
                    var center = map.getCenter();
                    google.maps.event.trigger(map, "resize");
                    map.setCenter(center);
            });
    }

    google.maps.event.addDomListener(window, 'load', initialize);

});
</script>
{include file="footer.tpl"}