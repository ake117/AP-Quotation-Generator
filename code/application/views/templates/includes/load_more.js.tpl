<div class="seemore">
        <!--<div><span class="unu"></span></div>-->
        <div class="mouse icon_seemore"><div class="wheel"></div></div>
        <div><span class="doi"></span></div>
        <div><span class="seemore_text ">See More</span></div>
</div>

<script>
      (function($, viewport){
            var current_page = '{$articles.current_page}';
            var next_page_url = '{$articles.next_page_url}';
            var cat = '{$current_menu}';
            $("div.seemore").click(function()
            {
                $('.seemore .unu').addClass('unu_motion');
                $('.seemore .doi').addClass('doi_motion');
                $('.seemore .seemore_text').addClass('seemore_text_motion');
                $('.seemore .wheel').addClass('wheel_motion');

                $.ajax({
                    type: "POST",
                    url: "{$base_url}/service/load_more",
                    data: { current_page: current_page, next_page_url: next_page_url, cat:cat },
                    dataType: "json",
                    cache:false,
                    success:
                         function(data){
                                current_page =  data.current_page;
                                next_page_url = data.next_page_url;

                                if(next_page_url === ''){
                                    $('div.seemore').hide();
                                }

                                if( viewport.is('>=sm') ){
                                    var $content = $( data.result );
                                    window.$grid.append( $content ).masonry( 'appended', $content );

                                        $(".article_masonry figure img").load(function() {
                                            window.$grid.masonry({ itemSelector : '.article_masonry' });
                                        }).each(function() {
                                            if(this.complete) {
                                                console.log('complete');
                                                $(this).load();
                                            }
                                        });

                                }else{
                                    $('.row.row_masonry').append(data.result);
                                }

                                $('.seemore .unu').removeClass('unu_motion');
                                $('.seemore .doi').removeClass('doi_motion');
                                $('.seemore .seemore_text').removeClass('seemore_text_motion');
                                $('.seemore .wheel').removeClass('wheel_motion');

                           }
                });
                return false;
            });

      })(jQuery, ResponsiveBootstrapToolkit);

  </script>