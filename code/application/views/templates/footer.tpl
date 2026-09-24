<!--/.body-wrapper  -->


  <!-- /.light-wrapper -->
  <footer class="footer inverse-wrapper">
    <div class="container inner">
      <div class="row">
        <div class="col-sm-12 col-md-6 col-lg-3 offset-lg-1">
            <div class="row">
                <div class="col-sm-12 col-lg-6">
                    <div class="widget">
                      <h3 class="widget-title d-none d-sm-block ">MENU</h3>
                      <ul class="category post-list">
                        {foreach from=$main_menu key=k item=a}
                            {if $a.display==='Y'}
                                <li><a href="{$base_url}/{$a.link}" > {$a.txt} </a></li>
                            {/if}
                        {/foreach}
                      </ul>
                      <!-- /.post-list -->
                    </div>
                    <!-- /.widget -->
                </div>
                <div class="col-sm-12 col-lg-6">
                    <div class="widget">
                      <!--h3 class="widget-title d-none d-sm-block ">about us</h3-->
                      <ul class="category post-list">
                          <li>
                              <!--a href="https://web.tcdc.or.th/th/aboutus">
                                    <span class="show">Creative Thailand</span>
                                    <span>ศูนย์สร้างสรรค์งานออกแบบ (TCDC)</span>
                              </a-->
                              <a href="{$base_url}/contactus">
                                    <span class="show">CREATIVE THAILAND</span>
                                    <!--span>ศูนย์สร้างสรรค์งานออกแบบ (TCDC)</span-->
                              </a>
                          </li>
                      </ul>
                      <!-- /.post-list -->
                    </div>
                <!-- /.widget -->
                </div>
            </div>
        </div>
        <!-- /column -->

        <div class="col-lg-05 d-none d-sm-none d-lg-block separate1"><img src="{$base_url}/assets/images/separate.png" alt=""/></div>

        <div class="col-sm-12 col-md-6 col-lg-3 newsletter">
          <div class="widget">
            <h3 class="newsletter widget-title">sign up for newsletter</h3>
            <form id="subscribe" method="get">
                <input type="text" id="s2" name="subscribe" class="txt_subscribe" value="" placeholder="E-MAIL ADDRESS" style="width: 70%;float: left;">
                <span id="submit_subscribe" class="btn btn-default" style="">Submit</span>
            </form>
          </div>
          <!-- /.widget -->

          <div class="widget">
            <h4 class="widget-title">Follow</h4>
            <ul class="social">
                <li><a href="{$facebook_url}" target="_blank"><img src="{$base_url}/assets/images/fb-share.png" alt=""></i></a></li>
                <li><a href="{$twitter_url}" target="_blank"><img src="{$base_url}/assets/images/tw-share.png"</i></a></li>
                <li><a href="{$youtube_url}" target="_blank"><img src="{$base_url}/assets/images/yt-share.png"</i></a></li>
                <li><a href="{$instagram_url}" target="_blank"><img src="{$base_url}/assets/images/ig-share.png"</i></a></li>
            </ul>
            <!-- .social -->

          </div>
        </div>
        <!-- /column -->

        <div class="col-lg-05 d-none d-sm-none d-lg-block separate2" style="text-align:center"><img src="{$base_url}/assets/images/separate.png" alt=""/></div>
        <div class="col-sm-12 col-md-6 col-lg-3">
          <div class="widget">
                    <div class="divide10 d-none d-sm-block"></div>
                    <div class="row">
                        <div class="col-4 col-sm-4">
                            <a href="https://www.egov.go.th/th/government-agency/24/" target="_blank"><img src="{$base_url}/assets/images/footer-opm.png" alt=""/></a>
                        </div>
                        <div class="col-4 col-sm-4" style="margin-top: 12px;">
                            <a href="http://www.okmd.or.th/" target="_blank"><img src="{$base_url}/assets/images/footer-okmd.png" alt=""/></a>
                        </div>
                        <div class="col-4 col-sm-4"  style="margin-top: 5px;">
                            <a href="https://web.tcdc.or.th/th" target="_blank"><img src="{$base_url}/assets/images/footer-tcdc.png" alt=""/></a>
                        </div>
                    </div>
                    <!--div class="row"><div class="divide20"></div></div>
                    <div class="row">
                        <div class="col-1 d-block d-sm-none">
                        </div>
                        <div class="col-4 col-sm-4">
                            <img src="assets/images/footer-tcdcconnect.png" alt=""/>
                        </div>
                        <div class="col-6 col-sm-8">
                            <img src="assets/images/footer-tcdc_mBangkok.png" alt=""/>
                        </div>
                        <div class="col-1 d-block d-sm-none">
                        </div>
                    </div-->
          </div>
          <!-- /.widget -->

        </div>
        <!-- /column -->

      </div>
      <!-- /.row -->
    </div>
    <!-- .container -->

    <div class="sub-footer">
      <div class="container inner">
          <p class="text-center">
              <span>Copyright © 2018 TCDC </span>
              <span>(Thailand Creative & Design Center). All rights reserved.</span>
              <span><a href="https://web.tcdc.or.th/th/privacy" target="_blank">นโยบายความเป็นส่วนตัว</a> | <a href="https://web.tcdc.or.th/th/privacy/terms" target="_blank">ข้อกำหนดและเงื่อนไข</a></span></p>      </div>
      <!-- .container -->
    </div>
    <!-- .sub-footer -->
  </footer>
  <!-- /footer -->

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Warning</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div class="scroll-top-wrapper ">
  <span class="scroll-top-inner">
    <i class="fa fa-2x fa-toggle-up"></i>
  </span>
</div>
</main>
</body>
</html>