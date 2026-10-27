{include file="header.tpl"}
{ci_config name="base_url"}
{ci_language file="wpn_common" lang="$lang"}

<section class="content-header">
    <h1>
        Member
        <small>Manage Members here.</small>
    </h1>
    <ol class="breadcrumb">
        
        <li><i class="fa fa-users"></i> {ci_language line="mod_members" default='Members'}</li>
    </ol>
</section>

<section class="content">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">List of members</h3>
            <div class="box-tools pull-right">
                <a href="{site_url url="admin/members/add"}" class="btn btn-sm btn-primary"><span class="glyphicon glyphicon-plus-sign"></span>
New Member</a>
            </div>
        </div>
        <div class="box-body">
            <table id="grid" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>{ci_language line="col_name22" default='Name'}</th>
                        <th>{ci_language line="col_email11" default='Fullname'}</th>
                        <th>{ci_language line="col_role22" default='Team Name'}</th>
                        <th>{ci_language line="col_job2" default='Job Name'}</th>
                        <th>{ci_language line="col_date" default='Date'}</th>
                        <th>{ci_language line="col_status" default='Status'}</th>
                        <th>{ci_language line="col_actions" default='Actions'}</th>
                      </tr>
                    </thead>
                    <tbody>
                        {foreach from=$members item=row}
                            <tr>
                              <td>{$row->member_id}</td>
                              <td>{$row->member_name}</td>
                              <td>{$row->member_fullname}</td>
                              <td>{$row->team_name}</td>
                              <td>{$row->job_title}</td>
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