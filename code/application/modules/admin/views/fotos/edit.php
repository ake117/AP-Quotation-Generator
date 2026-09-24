<section class="content-header">
    <h1>
        Photo albums
        <small>Manage your site's photo albums here.</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= site_url('admin/dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="<?= site_url('admin/albuns'); ?>"><i class="fa fa-camera"></i> Albums</a></li>
        <li>Alter photo</li>
    </ol>
</section>

<section class="content">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Alter photo</h3>
        </div>
        <div class="box-body">
			<?= form_open_multipart('admin/fotos/edit/'.$row->id, array('role'=>'form')); ?>
				<div class="form-group">
					<label>Selecione a imagem</label>
					<input type="file" name="userfile" class="form-control" />
				</div>
            	<p style="margin-top:15px;"><b>Image preview</b></p>
				<?php
            	$data = array(
					'src'=>'media/albuns/'.$row->album_id.'/'.$row->filename, 
					'class'=>'img-responsive img-thumbnail', 
					'style'=>'margin-top:5px;'
				);
				echo img($data);
				?>
				<div class="checkbox">
					<label>
						<input type="checkbox" name="alterar_imagem" value="1" />
						Alter image
					</label>
				</div>
				<div class="form-group">
					<label>Description</label>
					<input type="text" name="descricao" value="<?= $row->descricao; ?>" class="form-control" />
				</div>
				<div class="form-group">
					<label>Status</label>
					<select name="status" class="form-control">
						<option value="0" <?php if($row->status == 0){ echo 'selected'; } ?> >Unavailable</option>
						<option value="1" <?php if($row->status == 1){ echo 'selected'; } ?> >Published</option>
					</select>
				</div>
				<hr/>
				<div class="row">
					<div class="col-sm-12 col-md-12">
						<button type="submit" class="btn btn-primary">Save change</button>
						&nbsp; <?= anchor('admin/fotos/index/'.$row->album_id, 'Cancel', array('class' => 'btn btn-danger')); ?>
					</div>
				</div>
			<?= form_close(); ?>
        </div>
    </div>
</section>
