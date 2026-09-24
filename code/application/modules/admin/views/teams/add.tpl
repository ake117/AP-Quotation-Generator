{include file="header.tpl"}
{ci_config name="base_url"}
{ci_language file="wpn_common" lang="$lang"}
<section class="content-header">
    <h1>
        Team
        <small>Manage Teams here.</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{site_url url="admin/dashboard"}"><i class="fa fa-dashboard"></i> {ci_language line="mod_dashboard"}</a></li>
        <li><a href="{site_url url="admin/teams"}"><i class="fa fa-users"></i> {ci_language line="mod_teams" default='Teams'}</a></li>
        <li>Create user team</li>
    </ol>
</section>

<section class="content">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Create Team</h3>
        </div>
        <div class="box-body">
              <form action="{site_url url="admin/teams/add"}" role="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
                <ul class="nav nav-pills" role="tablist" style="margin-bottom:20px;display:none;">
                    <li class="active"><a href="#userdata" role="tab" data-toggle="tab">Team data</a></li>
                </ul>
                <div class="tab-content">
                    <!--Painel de configuração geral-->
                    <div class="tab-pane active panel panel-default" id="userdata">
                        <div class="panel-heading">
                            Team data
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" name="name" class="form-control" />
                                        {form_error('name')}
                                    </div>
                                </div>
                            </div> <!-- end row -->
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="role">Status</label>
                                        {html_options name='status' options=$status_options selected=$statusSelect class='form-control'}
                                        {form_error('role')}
                                    </div>
                                </div>
                            </div> <!-- end row -->
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <button type="submit" name="submit" class="btn btn-primary">Save</button>
                        <a class="btn btn-danger" href='{site_url url="admin/teams"}'>Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
<script type="text/javascript">
    function marcardesmarcar(id){
      $('.marcar-'+id).each(
            function(){
               if ($(this).prop( "checked")){ $(this).prop("checked", false); }
               else { $(this).prop("checked", true); }
            }
        );
    }
</script>

{include file="footer.tpl"}