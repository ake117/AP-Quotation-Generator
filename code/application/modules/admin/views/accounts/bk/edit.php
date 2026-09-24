<section class="content-header">
    <h1>
        User account
        <small>Manage user accounts here.</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= site_url('admin/dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="<?= site_url('admin/accounts'); ?>"><i class="fa fa-users"></i> User account</a></li>
        <li>Change user account</li>
    </ol>
</section>

<section class="content">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Change user account</h3>
        </div>
        <div class="box-body">
            <?= form_open_multipart('admin/accounts/edit/'.$row->id, array('role'=>'form')); ?>
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
                                        <input type="text" name="name" value="<?= auth_extra_data('name', $row->extra_data); ?>" class="form-control" />
                                        <?= form_error('name'); ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" name="email" value="<?= $row->email; ?>" class="form-control" />
                                        <?= form_error('email'); ?>
                                    </div>
                                </div>
                                <!-- <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input type="password" name="password" class="form-control" />
                                        <?= form_error('password'); ?>
                                    </div>
                                </div> -->
                            </div> <!-- end row -->
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="thumbnail">
                                        <?php if(auth_extra_data('avatar', $row->extra_data)){ ?>
                                            <img src="<?= base_url('media/avatar') . '/'.auth_extra_data('avatar', $row->extra_data); ?>" class="img-responsive" />
                                        <?php } else { ?>
                                            <img src="<?= base_url('lib/img'); ?>/no-user.jpg" class="img-responsive" />
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="userfile">User photo</label>
                                        <input type="file" name="userfile" class="form-control" />
                                        <input type="hidden" name="avatar" value="<?= auth_extra_data('avatar', $row->extra_data); ?>"/>
                                        <div class="checkbox">
                                            <label>
                                                <?= form_checkbox('change_avatar', '1', false); ?>
                                                Change avatar
                                            </label>
                                        </div>
                                        <?= form_error('image'); ?>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <?php
                                        // Opções de skin
                                        $options = array(
                                          'black'  => 'Black',
                                          'black-light'  => 'Black-Light',
                                          'blue'  => 'Blue',
                                          'blue-light'  => 'Blue-Light',
                                          'green'  => 'Green',
                                          'green-light'  => 'Green-Light',
                                          'purple'  => 'Purple',
                                          'purple-light'  => 'Purple-Light',
                                          'red'  => 'Red',
                                          'red-light'  => 'Red-Light',
                                          'yellow'  => 'Yellow',
                                          'yellow-light'  => 'Yellow-Light'
                                        );
                                        ?>
                                        <label for="skin">Color style</label>
                                        <?= form_dropdown('skin', $options, array(auth_extra_data('skin', $row->extra_data)), array('class'=>'form-control')); ?>
                                        <?= form_error('skin'); ?>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="role">User type</label>
                                        <?= form_dropdown('role', config_item('auth_account_role'), array($row->role), array('class'=>'form-control')); ?>
                                        <?= form_error('role'); ?>
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
                                        <?php foreach($query_module as $key => $mod){ ?>
                                            <div class="panel panel-default">
                                                <div class="panel-heading" role="tab" id="headingOne">
                                                    <h4 class="panel-title">
                                                        <a 
                                                            role="button" 
                                                            class="collapsed"
                                                            data-toggle="collapse" 
                                                            data-parent="#accordion" 
                                                            href="#collapse-<?= $mod['id']; ?>" 
                                                            aria-expanded="true" 
                                                            aria-controls="collapse-<?= $mod['id']; ?>">
                                                            <span class="glyphicon glyphicon-triangle-bottom"></span> <?= $mod['name']; ?>
                                                        </a>
                                                    </h4>
                                                </div>
                                                <div id="collapse-<?= $mod['id']; ?>" class="panel-collapse collapse " role="tabpanel" aria-labelledby="headingOne">
                                                    <div class="panel-body">
                                                        <table class="table table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th><input type="checkbox" onchange="marcardesmarcar(<?= $mod['id']; ?>)" /></th>
                                                                    <th>Description</th>
                                                                    <th>Link</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php foreach($mod['actions'] as $act){ ?>
                                                                    <tr>
                                                                        <td>
                                                                            <input 
                                                                            type="checkbox" 
                                                                            name="permission[]" 
                                                                            value="<?= $act['id']; ?>" 
                                                                            class="marcar-<?= $mod['id']; ?>" 
                                                                            <?php if(has_permission($act['link'], $row->id, true))echo "checked"; ?>
                                                                            />
                                                                        </td>
                                                                        <td><?= $act['description']; ?></td>
                                                                        <td><?= anchor($act['link'], $act['link'], array('target'=>'_blank')); ?></td>
                                                                    </tr>
                                                                <?php } ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div> <!-- end row -->
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <button type="submit" name="submit" class="btn btn-primary">Save</button>
                        <?= anchor('admin/accounts', 'Cancel', array('class'=>'btn btn-danger')); ?>
                    </div>
                    <div class="col-md-6 text-right">
                        <?php
                        if($row->status == 1)
                            echo anchor('admin/accounts/deactivate/'.$row->id, 'Disable user', array('class'=>'btn btn-danger')); 
                        else
                            echo anchor('admin/accounts/activate/'.$row->id, 'Activate user', array('class'=>'btn btn-success')); 
                        ?>
                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target=".change-password-modal">Change password</button>
                    </div>
                </div>
            <?= form_close(); ?>
        </div>
    </div>
</section>

<?= form_open('admin/accounts/changepassword/'.$row->id); ?>
<div class="modal fade change-password-modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                Alter the password
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password" class="form-control" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Save</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?= form_close(); ?>

<script type="text/javascript">
    function marcardesmarcar(id){
      $('.marcar-'+id).each(
             function(){
               if ($(this).prop( "checked")) 
               $(this).prop("checked", false);
               else $(this).prop("checked", true);               
             }
        );
    }
</script>