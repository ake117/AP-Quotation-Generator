<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>AP.QT.GEN | <?= wpn_lang('wpn_recoverypass', 'Password recovery'); ?></title>
		<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
		<!-- bootstrap 3.3.4 -->
		<link href="<?= base_url('lib/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet" type="text/css" />
		<!-- Font Awesome Icons -->
                <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
		<!-- Theme style -->
		<link href="<?= base_url('lib/2.4/css/AdminLTE.css') ?>" rel="stylesheet" type="text/css" />

                <link href="<?= base_url('lib/css/grande.css') ?>" rel="stylesheet" type="text/css" />

		<!-- iCheck -->
		<link href="<?= base_url('lib/plugins') ?>/iCheck/square/blue.css" rel="stylesheet" type="text/css" />
		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<body class="login-page">
		<div class="login-box">
			<div class="login-logo">
				<img style="height: 100px;;" src="<?= base_url('lib/img') ?>/logo.png" />
			</div><!-- /.login-logo -->
			<div class="login-box-body">
				<p class="login-box-msg"><?= wpn_lang('wpn_recoverypass', 'Password recovery'); ?></p>
				<?php
				$msg_system = $this->session->flashdata('msg_recover');
				if ($msg_system)
					echo alerts($msg_system, 'warning', true);
				echo form_open('admin/recovery', array('role' => 'form'));
				?>
				<div class="row">
					<div class="col-md-12">
						<p><?= wpn_lang('wpn_msg_recovery', 'Inform your email, then we\'ll send instructions to recovery your password.'); ?></p>
					</div>
				</div>
				<div class="form-group has-feedback">
					<label class="control-label" for="email"><?= wpn_lang('lab_email', 'Email'); ?></label>
					<input class="form-control" id="email" name="email" placeholder="<?= wpn_lang('pch_youremail', 'Your email'); ?>" type="email"/>
					<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
					<?= form_error('email');?>
				</div>
				<div class="row">
					<div class="col-xs-8">&nbsp;&nbsp;<?= anchor('admin/login', wpn_lang('lnk_back', 'Back')); ?></div>
					<div class="col-xs-4">
						<button type="submit" class="btn btn-primary btn-block btn-flat"><?= wpn_lang('bot_send', 'Send'); ?></button>
					</div>
				</div>
				<?= form_close(); ?>
			</div><!-- /.login-box-body -->
		</div><!-- /.login-box -->

		<!-- jQuery 3.3.1 -->
		<script src="<?= base_url('lib/plugins/jQuery/jquery.min.js') ?>"></script>
		<!-- bootstrap 3.3.7 JS -->
		<script src="<?= base_url('lib/bootstrap/js/bootstrap.min.js') ?>" type="text/javascript"></script>

		<!-- iCheck -->
		<script src="<?= base_url('lib/plugins') ?>/iCheck/icheck.min.js" type="text/javascript"></script>
		<script>
		$(function () {
			$('input').iCheck({
				checkboxClass: 'icheckbox_square-blue',
				radioClass: 'iradio_square-blue',
				increaseArea: '20%' // optional
			});
		});
		</script>
	</body>
</html>