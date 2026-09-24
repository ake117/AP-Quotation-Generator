<div class="col-md-12 column">
        <table class="table table-bordered table-hover" id="tab_logic">
                <thead>
                        <tr>
                            <th class="text-center" style="width: 5%;">
                                    #
                            </th>
                            <th class="text-center" style="width: 10%">
                                    No.
                            </th>
                            <th class="text-center" style="width: 35%">
                                    Name
                            </th>
                            <th class="text-center" style="width: 15%">
                                    Quantity
                            </th>
                            <th class="text-center" style="width: 15%">
                                    Unit Price
                            </th>
                            <th class="text-center" style="width: 20%">
                                    Amount
                            </th>
                            <th class="text-center" style="width: 5%">
                                   Delete
                            </th>
                    </tr>
                </thead>
                <tbody>
                    <tr id='addr1'>
                        <td valign="center">
                            <span class="glyphicon glyphicon-sort" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="Move"></span>
                            <span class="glyphicon glyphicon-plus" onclick="plus_desc_task(this)" data-toggle="tooltip" data-placement="bottom" title="Expand"></span>
                        </td> 
                        <td>
                            <input type="text" name='no[]'  placeholder='No.' value="1" class="form-control" readonly style="text-align:center"/>
                        </td>
                        <td>
                            <input type="text" name='name_task[]' placeholder='' value="System Feature" class="form-control"/>
                            <div class="form-group desc_task">
                                <label for="comment">Description:</label>
                                <textarea class="form-control" rows="5" name="description[]" id="description"></textarea>
                            </div>
                        </td>
                        <td>
                            <input type="number" name='quantity[]' placeholder='' value="1" class="form-control"/>
                        </td>
                        <td>
                            <input type="text" name='unit_price[]' placeholder='' value="{$row_quotations.total_cost_amount}" class="form-control numberfomat"/>
                        </td>
                        <td>
                            <input type="text" name='amount[]' placeholder='' value="{$row_quotations.total_cost_amount}" class="form-control numberfomat"/>
                        </td>
                        <td>
                            <span class="glyphicon glyphicon-remove btns_delete" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="Delete"></span>
                        </td>
                    </tr>
                    
                    {foreach from=$row_task key=k item=v}
                        <tr id='addr{$k+2}'>
                            <td valign="center">
                                <span class="glyphicon glyphicon-sort" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="Move"></span>
                                <span class="glyphicon glyphicon-plus" onclick="plus_desc_task(this)" data-toggle="tooltip" data-placement="bottom" title="Expand"></span>
                            </td>                            <td>
                                <input type="text" name='no[]'  placeholder='No.' value="{$k+2}" class="form-control number_no" readonly style="text-align:center"/>
                            </td>
                            <td>
                                <input type="text" name='name_task[]' placeholder='Enter Name' value="{$v.task_name}" class="form-control"/>
                                <div class="form-group desc_task">
                                    <label for="comment">Description:</label>
                                    <textarea class="form-control" rows="5" name="description[]" id="description">{$v.task_description}</textarea>
                                </div>
                            </td>
                            <td>
                                <input type="number" name='quantity[]' placeholder='' class="form-control"/>
                            </td>
                            <td>
                                <input type="text" name='unit_price[]' placeholder='' class="form-control numberfomat"/>
                            </td>
                            <td>
                                <input type="text" name='amount[]' placeholder='' class="form-control numberfomat"/>
                            </td>
                            <td>
                                <span class="glyphicon glyphicon-remove btns_delete" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="Delete"></span>
                            </td>
                        </tr>
                    {/foreach}
                </tbody>
        </table>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('.numberfomat').inputmask( { alias: 'numeric', groupSeparator: ',', digits: 2, digitsOptional: false, placeholder: '0' } );
    } );
</script>