<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<section class="content-header">
    <h1>
        Newsletters
        <small>Export your email to newsletter.</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= site_url('admin/dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><i class="fa fa-envelope-o"></i> Newsletters</li>
    </ol>
</section>

<section class="content">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Contact list</h3>
            <div class="box-tools pull-right">
                <?= anchor('admin/newsletters/export', glyphicon('download').'Export CSV', array('class' => 'btn btn-success')); ?>
                <button class="btn btn-danger" onClick="return confirm('<?= site_url('admin/newsletters/clear'); ?>');"><?= glyphicon('trash'); ?> Clear</button>
            </div>
        </div>
        <div class="box-body">
            <p class="text-danger">
                <b>Note:</b>
                WPanel does not send bulk messages, use a separate service for this.
            </p>

            <table id="grid" class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Data</th>
                        <th>Ip</th>
                    </tr>
                </thead>
                <tbody class="sortable">
                    <?php
                    foreach($contatos as $row){
                        ?>
                        <tr id="item-<?= $row->id; ?>" style="cursor:move;">
                            <td><?= $row->id; ?></td>
                            <td><?= $row->nome; ?></td>
                            <td><?= $row->email; ?></td>
                            <td><?= datetime_for_user($row->created); ?></td>
                            <td><?= $row->ipaddress; ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>

        </div>
    </div>
</section>