<section class="content-header">
    <h1>
        Module
        <small>Manage the system modules</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= site_url('admin/dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="<?= site_url('admin/modulos'); ?>"><i class="fa fa-cog"></i> Module</a></li>
        <li><a href="<?= site_url('admin/modulos/edit/'.$module_id); ?>"><i class="fa fa-cog"></i> Actions</a></li>
        <li>Cadastro</li>
    </ol>
</section>
<section class="content">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Registration Form</h3>
        </div>
        <div class="box-body">
			<form action="<?= site_url('admin/moduloitens/add/'.$module_id); ?>" role="form" class="form-horizontal" method="post" accept-charset="utf-8">
				<!-- <input type="hidden" name="module_id" value="<?= $module_id; ?>" /> -->
				<div class="form-group">
					<label for="id" class="col-sm-2 col-md-2 control-label">Description</label>
					<div class="col-sm-10 col-md-10">
						<input type="text" name="description" id="description" class="form-control" />
						<?= form_error('description'); ?>
					</div>
				</div>
				
				<div class="form-group">
					<label for="id" class="col-sm-2 col-md-2 control-label">Link</label>
					<div class="col-sm-10 col-md-10">
						<input type="text" name="link" id="link" class="form-control" />
						<?= form_error('link'); ?>
					</div>
				</div>
				
				<div class="form-group">
					<div class="col-sm-10 col-md-10 col-sm-offset-2 col-md-offset-2">
						<label>
							<input type="checkbox" name="whitelist" value="1" />
							White list
						</label>
					</div>
				</div>
				
				<hr/>
				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-10 col-md-offset-2 col-md-10">
						<button type="submit" class="btn btn-primary" >Save</button>
						<?= anchor('admin/modulos/edit/'.$module_id, 'Cancel', array('class'=>'btn btn-danger')); ?>
					</div>
				</div>
			</form>
        </div>
    </div>
</section>