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
        <li><i class="fa fa-users"></i> {ci_language line="mod_useraccount"}</li>
    </ol>
</section>

<section class="content">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">List of account</h3>
            <div class="box-tools pull-right">
                <a href="{site_url url="admin/accounts/add"}" class="btn btn-sm btn-primary"><span class="glyphicon glyphicon-plus-sign"></span>
New register</a>
                <a href="{site_url url="admin/ipbanneds"}" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-ban-circle"></span>
Banned IPs </a>
            </div>
        </div>
        <div class="box-body">
            <table id="grid" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>{ci_language line="col_name"}</th>
                        <th>{ci_language line="col_email"}</th>
                        <th>{ci_language line="col_role"}</th>
                        <th>{ci_language line="col_date" default='Date'}</th>
                        <th>{ci_language line="col_status" default='Status'}</th>
                        <th>{ci_language line="col_actions" default='Actions'}</th>
                      </tr>
                    </thead>
                    <tbody>
                        {foreach from=$accounts item=row}
                            <tr>
                              <td>{$row->id}</td>
                              <td>{$row->name}</td>
                              <td>{$row->email}</td>

                              <td>{$row->role}</td>
                              <td>{$row->created}</td>
                              <td>{$row->status}</td>
                              <td>{$row->actions}</td>
                            </tr>
                        {/foreach}
                    </tbody>
            </table>
        </div>
    </div>
</section>

{include file="footer.tpl"}