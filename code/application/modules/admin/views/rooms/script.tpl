{ci_config name="base_url"}
<script>
    $(function() {
        $("#project_id").change(function () {
            var project_id = this.value;

            $('#floor_id').prop('disabled',true);
            $.ajax({
             type: "POST",
             url: '{$base_url}' + "/admin/service/get_floor_option/" + project_id,
             data: {},
             dataType: "json",
             cache:false,
             success:
                    function(data){
                        $('#floor_id').find('option').remove().end().append(data.options);
                        $('#floor_id').prop('disabled', false);
                    }
            });
        });
    });
</script>