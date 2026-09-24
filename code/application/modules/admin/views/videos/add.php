<section class="content-header">
	<h1>
		Videos
		<small>Painel de gerenciamento de Videos.</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="<?= site_url('admin/dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
		<li><a href="<?= site_url('admin/videos'); ?>"><i class="fa fa-tag"></i> Videos</a></li>
		<li>New register</li>
	</ol>
</section>
<section class="content">
	<div class="panel panel-default">
		<div class="panel-heading">New register de Videos</div>
		<div class="panel-body">
			<?= form_open('admin/videos/add', array('role'=>'form')); ?>
				<div class="form-group">
					<label for="titulo">Titulo</label>
					<input type="text"  class="form-control" id="titulo" name="titulo" value="" placeholder="">
				</div>
				<div class="form-group">
					<label for="descricao">Description</label>
					<textarea class="form-control" id="descricao" name="descricao" placeholder="" rows=""></textarea>
				</div>
				<div class="form-group">
					<label for="link">Link</label>
					<input type="text"  class="form-control" id="link" name="link" value="" placeholder="">
				</div>
				<div class="form-group">
					<?php
                    // Status do usuário
                    $options = array(
						'0'  => 'Unavailable',
						'1'    => 'Published'
                    );
                    ?>
                    <label for="status">Status</label>
                    <?= form_dropdown('status', $options, null, array('class'=>'form-control')); ?>
				</div>
				<button type="submit" class="btn btn-success">Save</button>
				<a href="<?= site_url('admin/videos'); ?>" class="btn btn-danger">Cancel</a>
			</form>
		</div>
	</div>
</section>