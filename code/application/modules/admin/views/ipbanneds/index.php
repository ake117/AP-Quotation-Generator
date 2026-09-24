<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<section class="content-header">
    <h1>
       Banned IPs
        <small>Banned IPs management module.</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= site_url('admin/dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="<?= site_url('admin/accounts'); ?>"><i class="fa fa-users"></i> User account</a></li>
        <li><i class="fa fa-cog"></i>Banned IPs</li>
    </ol>
</section>

<section class="content">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Listing</h3>
            <div class="box-tools pull-right">
                <?= anchor('admin/accounts', glyphicon('user') . 'User accounts', array('class' => 'btn btn-sm btn-info')); ?>
                <?= anchor('admin/ipbanneds/add', glyphicon('plus-sign') . 'New record', array('class' => 'btn btn-sm btn-primary')); ?>
            </div>
        </div>
        <div class="box-body">
            <?= $listagem; ?>
        </div>
    </div>
</section>