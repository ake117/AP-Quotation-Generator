<section class="content-header">
    <h1>
        Photo albums
        <small>Manage your site's photo albums here.</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= site_url('admin/dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="<?= site_url('admin/albuns'); ?>"><i class="fa fa-camera"></i> Albums</a></li>
        <li>Add photo album</li>
    </ol>
</section>

<section class="content">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Add photo album</h3>
        </div>
        <div class="box-body">
            <?= form_open_multipart('admin/albuns/add', array('role'=>'form')); ?>
				<div class="form-group">
					<label for="titulo">Title</label>
					<input type="text" name="titulo" value="<?= set_value('titulo'); ?>" class="form-control" />
					<?= form_error('titulo'); ?>
				</div>
				<div class="form-group">
					<label for="descricao">Description</label>
					<textarea name="descricao" rows="5" class="form-control"><?= set_value('descricao'); ?></textarea>
					<?= form_error('descricao'); ?>
				</div>
				<div class="row">
					<div class="col-sm-4 col-md-4">
						<div class="form-group">
							<label for="userfile">Image Cover</label>
							<input type="file" name="userfile" class="form-control" />
						</div>
					</div>
					<div class="col-sm-3 col-md-3">
						<div class="form-group">
							<label for="status">Status</label>
							<select name="status" class="form-control">
								<option value="0">Unavailable</option>
								<option value="1">Published</option>
							</select>
						</div>
					</div>
				</div>
				<hr/>
				<div class="row">
					<div class="col-sm-12 col-md-12">
						<button type="submit" class="btn btn-primary">Create</button>
						&nbsp;<?= anchor('admin/albuns', 'Cancel', array('class' => 'btn btn-danger')); ?>
					</div>
				</div>
			<?= form_close(); ?>
        </div>
    </div>
</section>
