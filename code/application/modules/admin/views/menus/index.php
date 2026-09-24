<section class="content-header">
    <h1>
        Menus
        <small>Manage your site navigation menus and options here.</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= site_url('admin/dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><i class="fa fa-reorder"></i> Menus</li>
    </ol>
</section>

<section class="content">
    <div class="box box-solid">
        <div class="box-header with-border">
            <h3 class="box-title">List of menus</h3>
            <div class="box-tools pull-right">
                <?= anchor('admin/menus/add', glyphicon('plus-sign') . 'New menu', array('class'=>'btn btn-sm btn-primary')); ?>
            </div>
        </div>
        <div class="box-body">
            <ul class="list-group">
		        <?= $listagem; ?>
		    </ul>
        </div>
    </div>
</section>