<table id="grid" class="table table-striped">
    <thead>
        <tr>
            {*<th>#</th>*}
            <th>Transaction Date</th>
            <th>Merchant Ref.</th>
            <th>System Ref.</th>
            <th>Name</th>
            <th>E-mail</th>
            <th>Mobile</th>
            <th>Project</th>
            <th>Booking Price</th>
            <th>Agent Name</th>
            <th>Status</th>
            <th>Created</th>
        </tr>
    </thead>
    <tbody class="sortable">
        {foreach from=$booking item=row}
            <tr id="item-{$row.payment_id}" style="/*cursor:move;*/">
                {*<td>{$row.payment_id}</td>*}
                <td>{$row.txtime|date_format:"%d/%m/%Y %H:%M"}</td>
                <td>{$row.token}</td>
                <td style="text-align: right;">{$row.payref}</td>
                <td>{$row.prefix} {$row.name} {$row.surname}</td>
                <td>{$row.email}</td>
                <td>{$row.phone}</td>
                <td>{$row.project_unit}</td>
                <td>{$row.total_paid}</td>
                <td>{$row.agent_name}</td>
                <td>{$row.payment_status}</td>
                <td>{$row.create_date|date_format:"%d/%m/%Y %H:%M"}</td>
            </tr>
        {/foreach}
    </tbody>
</table>