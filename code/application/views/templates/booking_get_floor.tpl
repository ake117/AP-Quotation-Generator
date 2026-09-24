{ci_config name="base_url"}
{ci_config name="path"}
<div class='element-wrapper'>
  <div class='element'></div>
  <div class='element'></div>
  <div class='element'></div>
  <div class='loading'>Loading…</div>
  <div class='large-square'></div>
</div>

{if $get_floor_image.0.have_room == 'N'}
    <div class="image_show_floor">
        <a data-fancybox="gallery" href="{$path.floor}{$get_floor_plan.0.floor_plan_image}" class="one_image_floor" style="width: 100% !important;position: relative;display: block;">
            <img src="{$path.floor}{$get_floor_plan.0.floor_plan_image}" style=""/>
        </a>
    </div>
{else}
    <h2><b>{$get_floor_plan.0.roomtype_name} | {$get_floor_plan.0.floor_name}th floor</b></h2>
    <div class="image_show_floor">
    {if $get_floor_image.0.have_booking_status == 'N'}
        <a data-fancybox="gallery" href="{$get_floor_image.0.room_plan_image}" style="width: {$get_floor_image.0.size_room_plan_image};position: relative;display: block;">
            <img src="{$get_floor_image.0.room_plan_image}"/>
        </a>
        <a data-fancybox="gallery" href="{$get_floor_image.0.room_floor_plan_image}" style="width: {$get_floor_image.0.size_room_floor_plan_image};position: relative;display: block;">
            <img src="{$get_floor_image.0.room_floor_plan_image}"/>
        </a>
    {/if}
    {foreach $get_floor_image as $key => $floor}
        {if $floor.booking_status == 'Y'}
            <input type="hidden" name="room_id" id="room_id" value="{*$floor.room_id*}">
            <a data-fancybox="gallery" href="{$floor.room_plan_image}" style="width: {$floor.size_room_plan_image};position: relative;display: block;">
                <img src="{$floor.room_plan_image}"/>
            </a>
            <a data-fancybox="gallery" href="{$floor.room_floor_plan_image}" style="width: {$floor.size_room_floor_plan_image};position: relative;display: block;">
                <img src="{$floor.room_floor_plan_image}"/>
            </a>
            {break}
        {/if}
    {/foreach}
    </div>
{/if}
    

{*$get_floor_image|@print_r*}    
{if $get_floor_room.0.have_room == 'Y'}
<div class="bt_select_room">
    {foreach $get_floor_room as $key => $floor}
        <span class="bt_room {if $floor.booking_status == 'Y'}room_active{/if}" {if $floor.booking_status == 'Y'}data-value="{$floor.room_id}"{/if}>{$floor.room_name}</span>
    {/foreach}
</div>
<div class="stauts_select">
    <span class="s_place">{$lang['booking_active']}</span>
    <span class="s_busy">{$lang['booking_inactive']}</span>
</div>
{/if}

{literal} 
<style>
.image_show_floor {
    position: relative;
    display: flex;
    align-items: center;
    overflow: auto;
}
</style>

<script>
(function($){
    $(".bt_room.room_active").click(function(){
        $(".element-wrapper").show();
        $(".floor_img_show").css( 'pointer-events', 'none' );
        $(".bt_room.room_active").removeClass('checktrue');
        $(this).addClass('checktrue');
        var id = $(this).data("value");
        var v_floor = $("input[name=floor]:checked").val();
        var v_roomtype = $("input[name=roomtype]:checked").val();
        var v_project_id = $("input[name=project_id]").val();
        $.ajax({
            type: "POST",
            url: "/booking/get_room_image",
            data: {project_id:v_project_id ,floor_id:v_floor ,roomtype_id:v_roomtype, room_id:id },
            success: function(msg){
                $(".floor_img_show").html(msg);
                $("#room_id").val(id);
                $(".room_active[data-value='" + id +"']").addClass('checktrue');
                $(".floor_img_show").css( 'pointer-events', 'unset' );
                $(".element-wrapper").hide();
            }
        }); 
    });

})(window.jQuery);
</script>
{/literal} 
