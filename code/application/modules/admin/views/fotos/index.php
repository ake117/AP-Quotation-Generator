<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<section class="content-header">
    <h1>
        Photo albums
        <small>Manage your site's photo albums here.</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= site_url('admin/dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="<?= site_url('admin/albuns'); ?>"><i class="fa fa-camera"></i> Albums</a></li>
        <li>Fotos</li>
    </ol>
</section>

<section class="content">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Photo list</h3>
            <div class="box-tools pull-right">
                <?= anchor('admin/albuns', glyphicon('chevron-left') . 'Go back', array('class' => 'btn btn-sm btn-primary')); ?>
                <?= anchor('admin/fotos/add/'.$album_id, glyphicon('plus-sign') . 'Add photo', array('class' => 'btn btn-sm btn-primary')); ?>
                <?= anchor('admin/fotos/addmass/'.$album_id, glyphicon('plus-sign') . 'Add photo in bulk', array('class' => 'btn btn-sm btn-primary')); ?>
            </div>
        </div>
        <div class="box-body">
            <?= $listagem; ?>
        </div>
    </div>
</section>