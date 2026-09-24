<section class="content-header">
    <h1>
        <?= wpn_lang('mod_profile', 'Account profile'); ?>
        <small>Keep your data always up to date.</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= site_url('admin/dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><?= wpn_lang('mod_profile', 'Account profile'); ?></li>
    </ol>
</section>

<section class="content">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Alter user profile</h3>
        </div>
        <div class="box-body">
            <?= form_open_multipart('admin/accounts/profile', array('role'=>'form')); ?>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Full name</label>
                            <input type="text" name="name" value="<?= $extra_data->name; ?>" class="form-control" />
                            <?= form_error('name'); ?>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" value="<?= $row->email; ?>" class="form-control" />
                            <?= form_error('email'); ?>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password" class="form-control" />
                            <div class="checkbox">
                                <label>
                                    <?= form_checkbox('change_password', '1', false); ?>
                                    Alter password
                                </label>
                            </div>
                            <?= form_error('password'); ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="thumbnail">
                            <?php if($extra_data->avatar){ ?>
                                <img src="<?= base_url('media/avatar/'.$extra_data->avatar); ?>" class="img-responsive" />
                            <?php } else { ?>
                                <img src="<?= base_url('lib/img/no-user.jpg'); ?>" class="img-responsive" />
                            <?php } ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="userfile">User photo</label>
                            <input type="file" name="userfile" class="form-control" />
                            <input type="hidden" name="avatar" value="<?= $extra_data->avatar; ?>"/>
                            <div class="checkbox">
                                <label>
                                    <?= form_checkbox('change_avatar', '1', false); ?>
                                    Alter photo
                                </label>
                            </div>
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
                            <?= form_dropdown('skin', $options, array($extra_data->skin), array('class'=>'form-control')); ?>
                            <?= form_error('skin'); ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" name="submit" class="btn btn-primary">Save changes</button>
                        <?= anchor('admin/dashboard', 'Cancel', array('class'=>'btn btn-danger')); ?>
                    </div>
                </div>
            <?= form_close(); ?>
        </div>
    </div>
</section>