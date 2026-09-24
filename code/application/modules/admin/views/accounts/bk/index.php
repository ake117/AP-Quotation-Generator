<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<section class="content-header">
    <h1>
        User account
        <small>Manage user accounts here.</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= site_url('admin/dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><i class="fa fa-users"></i> User account</li>
    </ol>
</section>

<section class="content">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">List of account</h3>
            <div class="box-tools pull-right">
                <?= anchor('admin/accounts/add', glyphicon('plus-sign') . 'New register', array('class' => 'btn btn-sm btn-primary')); ?>
                <?= anchor('admin/ipbanneds', glyphicon('ban-circle') . 'Banned IPs ', array('class' => 'btn btn-sm btn-danger')); ?>
            </div>
        </div>
        <div class="box-body">x
            <?= $listagem; ?>
            y
        </div>
    </div>
</section>