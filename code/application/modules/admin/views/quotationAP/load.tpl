<table id="grid" class="table table-bordered table-striped">
    <thead>
    <tr>
      <th>#</th>
      <th>Project</th>
      <th>Name</th>
      <th style="text-align: right;">Work Hrs.</th>
      <th>Status</th>
      <th>PM</th>
      <th>Actions</th>
      <th>Export</th>
      <th>Created Date</th>
    </tr>
    </thead>
    <tbody>
        {foreach from=$accounts item=row}
            <tr>
              <td>{$row->quotation_id}</td>
              <td>{$row->project_id}</td>
              <td>{$row->production_name}</td>
              <td style="text-align: right;">{$row->total_work_hours}</td>
              <td>{$row->quotation_status}</td>
              <td>{$row->pm_member_id}</td>
              <td>{$row->actions}</td>
              <td><a href="{site_url url="admin/quotation/export"}/{$row->quotation_id}" class="btn btn-sm btn-primary link_export"><span class="glyphicon glyphicon-download"></span> Export</a></td>
              <td>{$row->create_date}</td>
            </tr>
        {/foreach}
    </tbody>
</table>