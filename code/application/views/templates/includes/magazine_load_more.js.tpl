<div class="seemore">
        <!--<div><span class="unu"></span></div>-->
        <div class="mouse icon_seemore"><div class="wheel"></div></div>
        <div><span class="doi"></span></div>
        <div><span class="seemore_text ">See More</span></div>
</div>
<script>
    $(document).ready(function(){
            var current_page = '{$publications.current_page}';
            var next_page_url = '{$publications.next_page_url}';
            var cat = '{$current_menu}';
            $(".icon_seemore").click(function()
            {
                $('.seemore .unu').addClass('unu_motion');
                $('.seemore .doi').addClass('doi_motion');
                $('.seemore .seemore_text').addClass('seemore_text_motion');
                $('.seemore .wheel').addClass('wheel_motion');
                
                $.ajax({
                    type: "POST",
                    url: "{$base_url}/service/magazine_load_more",
                    data: { current_page: current_page, next_page_url: next_page_url, cat:cat },
                    dataType: "json",
                    cache:false,
                    success:
                         function(data){
                                current_page =  data.current_page;
                                next_page_url = data.next_page_url;

                                if(next_page_url === ''){
                                    $('.seemore').hide();
                                }

                                $('.magazine_content').append(data.result);

                                /*setTimeout(function(){
                                    $('figure.main.magazine img').each(function( index ) {
                                        var h = (600 * $(this).width())/456;
                                        $(this).height(h);
                                    });
                                }, 300);*/
                                
                                $('.seemore .unu').removeClass('unu_motion');
                                $('.seemore .doi').removeClass('doi_motion');
                                $('.seemore .seemore_text').removeClass('seemore_text_motion');
                                $('.seemore .wheel').removeClass('wheel_motion');
                           }
                });/* you have missed this bracket */
                return false;
            });
    });

  </script>