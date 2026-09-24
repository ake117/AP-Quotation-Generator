<section class="content-header">
    <h1>
       Banned IPs
        <small>Banned IPs management module.</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= site_url('admin/dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="<?= site_url('admin/ipbanneds'); ?>"><i class="fa fa-cog"></i>Banned IPs</a></li>
        <li>Cadastro</li>
    </ol>
</section>
<section class="content">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Registration Form</h3>
        </div>
        <div class="box-body">
			<form action="<?= site_url('admin/ipbanneds/add'); ?>" role="form" class="form-horizontal" method="post" accept-charset="utf-8">
				
				<div class="form-group">
					<label for="id" class="col-sm-2 col-md-2 control-label">IP address</label>
					<div class="col-sm-10 col-md-10">
						<input type="text" name="ip_address" id="ip_address" class="form-control" />
						<?= form_error('ip_address'); ?>
					</div>
				</div>
				
				<hr/>
				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-10 col-md-offset-2 col-md-10">
						<button type="submit" class="btn btn-primary" >Save</button>
						<?= anchor('admin/ipbanneds', 'Cancel', array('class'=>'btn btn-danger')); ?>
					</div>
				</div>
			</form>
        </div>
    </div>
</section>