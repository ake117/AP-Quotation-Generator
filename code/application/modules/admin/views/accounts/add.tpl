{include file="header.tpl"}
{ci_config name="base_url"}
{ci_language file="wpn_common" lang="$lang"}
<section class="content-header">
    <h1>
        User account
        <small>Manage user accounts here.</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{site_url url="admin/dashboard"}"><i class="fa fa-dashboard"></i> {ci_language line="mod_dashboard"}</a></li>
        <li><a href="{site_url url="admin/accounts"}"><i class="fa fa-users"></i> {ci_language line="mod_useraccount"}</a></li>
        <li>Create user acccount</li>
    </ol>
</section>

<section class="content">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Create user acccount</h3>
        </div>
        <div class="box-body">
              <form action="{site_url url="admin/accounts/add"}" role="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
                <ul class="nav nav-pills" role="tablist" style="margin-bottom:20px;">
                    <li class="active"><a href="#userdata" role="tab" data-toggle="tab">User data</a></li>
                    <li><a href="#permissions" role="tab" data-toggle="tab">Permissions</a></li>
                </ul>
                <div class="tab-content">
                    <!--Painel de configuração geral-->
                    <div class="tab-pane active panel panel-default" id="userdata">
                        <div class="panel-heading">
                            User data
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Full name</label>
                                        <input type="text" name="name" class="form-control" />
                                        {form_error('name')}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" name="email" class="form-control" />
                                        {form_error('email')}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input type="password" name="password" class="form-control" />
                                        {form_error('password')}
                                    </div>
                                </div>
                            </div> <!-- end row -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="userfile">User photo</label>
                                        <input type="file" name="userfile" class="form-control" />
                                        {form_error('image')}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="skin">Color style</label>
                                        {html_options name='skin' options=$skin_options selected=$mySelect class='form-control'}
                                        {form_error('skin')}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="role">User type</label>
                                        {html_options name='role' options=$auth_account_role selected=$mySelect class='form-control'}
                                        {form_error('role')}
                                    </div>
                                </div>
                            </div> <!-- end row -->
                        </div>
                    </div>
                    <div class="tab-pane panel panel-default" id="permissions">
                        <div class="panel-heading">
                            Permissions
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                        {foreach from=$query_module item=module}
                                            <div class="panel panel-default">
                                                <div class="panel-heading" role="tab" id="headingOne">
                                                    <h4 class="panel-title">
                                                        <a
                                                            role="button"
                                                            class="collapsed"
                                                            data-toggle="collapse"
                                                            data-parent="#accordion"
                                                            href="#collapse-{$module.id}"
                                                            aria-expanded="true"
                                                            aria-controls="collapse-{$module.id}">
                                                            <span class="glyphicon glyphicon-triangle-bottom"></span> {$module.name}
                                                        </a>
                                                    </h4>
                                                </div>
                                                <div id="collapse-{$module.id}" class="panel-collapse collapse " role="tabpanel" aria-labelledby="headingOne">
                                                    <div class="panel-body">
                                                        <table class="table table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th><input type="checkbox" onchange="marcardesmarcar({$module.id})" /></th>
                                                                    <th>Description</th>
                                                                    <th>Link</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                {foreach from=$module.actions item=act}
                                                                    <tr>
                                                                        <td><input type="checkbox" name="permission[]" value="{$act.id}" class="marcar-{$module.id}" /></td>
                                                                        <td>{$act.description}</td>
                                                                        <td><a href="{$act.link}" target='_blank' >{$act.link}</a></td>
                                                                    </tr>
                                                                {/foreach}
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        {/foreach}
                                    </div>
                                </div>
                            </div> <!-- end row -->
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <button type="submit" name="submit" class="btn btn-primary">Save</button>
                        <a class="btn btn-danger" href='{site_url url="admin/accounts"}'>Cancel</a>
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