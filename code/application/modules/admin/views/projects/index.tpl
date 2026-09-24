{include file="header.tpl"}
{ci_config name="base_url"}
{ci_language file="wpn_common" lang="$lang"}

<section class="content-header">
    <h1>
        Project
        <small>Manage Projects here.</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{site_url url="admin/dashboard"}"><i class="fa fa-dashboard"></i> {ci_language line="mod_dashboard"}</a></li>
        <li><i class="fa fa-users"></i> {ci_language line="mod_projects" default='Projects'}</li>
    </ol>
</section>

<section class="content">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">List of projects</h3>
            <div class="box-tools pull-right">
                <a href="{site_url url="admin/projects/add"}" class="btn btn-sm btn-primary"><span class="glyphicon glyphicon-plus-sign"></span>
New Project</a>
            </div>
        </div>
        <div class="box-body">
            <table id="grid" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>{ci_language line="col_name22" default='Name'}</th>
                        <th>{ci_language line="col_email11" default='Description'}</th>
                        <th>{ci_language line="col_role22" default='Customer'}</th>
                        <th>{ci_language line="col_date" default='Date'}</th>
                        <th>{ci_language line="col_status" default='Status'}</th>
                        <th>{ci_language line="col_actions" default='Actions'}</th>
                      </tr>
                    </thead>
                    <tbody>
                        {foreach from=$projects item=row}
                            <tr>
                              <td>{$row->project_id}</td>
                              <td>{$row->project_name}</td>
                              <td>{$row->project_description}</td>
                              <td>{$row->customer_name}</td>
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