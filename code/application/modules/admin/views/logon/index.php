<!DOCTYPE html>
<html>
	<head>

		<meta charset="UTF-8">
		<title>PRODUCTION COST CMS | Login</title>
		<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

                <link rel="apple-touch-icon" sizes="76x76" href="<?= base_url('lib/img/favicon') ?>/apple-touch-icon.png">
                <link rel="icon" type="image/png" sizes="32x32" href="<?= base_url('lib/img/favicon') ?>/favicon-32x32.png">
                <link rel="icon" type="image/png" sizes="16x16" href="<?= base_url('lib/img/favicon') ?>/favicon-16x16.png">
                <link rel="manifest" href="<?= base_url('lib/img/favicon') ?>/site.webmanifest">
                <link rel="mask-icon" href="<?= base_url('lib/img/favicon') ?>/safari-pinned-tab.svg" color="#5bbad5">
                <meta name="msapplication-TileColor" content="#998e5f">
                <meta name="theme-color" content="#ffffff">

		<!-- bootstrap 3.3.7 -->
		<link href="<?= base_url('lib/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet" type="text/css" />
		<!-- Theme style -->

		<link href="<?= base_url('lib/2.4/css/AdminLTE.css') ?>" rel="stylesheet" type="text/css" />
		<link href="<?= base_url('lib/css/grande.css') ?>" rel="stylesheet" type="text/css" />
	</head>
	<body class="login-page">
		<div class="login-box">
			<div class="login-logo">
                            <img style="height: 100px;max-width: 360px;" src="<?= base_url('lib/img') ?>/logo.png" />
			</div><!-- /.login-logo -->
			<div class="login-box-body">
				<p class="login-box-msg"><?= wpn_lang('wpn_msg_logon', 'Access with your credentials.'); ?></p>
				<?php
				$msg_system = $this->session->flashdata('msg_auth');
				if ($msg_system)
					echo alerts($msg_system, 'warning', true);
				echo form_open('admin/login', array('role' => 'form'));
				?>
					<div class="form-group has-feedback">
						<input type="text" name="email" class="form-control" placeholder="<?= wpn_lang('input_email', 'Email'); ?>"/>
						<?= form_error('email'); ?>
						<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
					</div>
					<div class="form-group has-feedback">
						<input type="password" name="password" class="form-control" placeholder="<?= wpn_lang('input_password', 'Password'); ?>"/>
						<?= form_error('password'); ?>
						<span class="glyphicon glyphicon-lock form-control-feedback"></span>
					</div>
					<div class="row">
						<div class="col-xs-8">
							<?= anchor('admin/recovery', wpn_lang('lnk_recoverpass', 'Password recovery')); ?>
						</div><!-- /.col -->
						<div class="col-xs-4">
							<button type="submit" class="btn btn-primary btn-block btn-flat"><?= wpn_lang('bot_login', 'Login'); ?></button>
						</div><!-- /.col -->
					</div>
				<?= form_close(); ?>
			</div><!-- /.login-box-body -->
		</div><!-- /.login-box -->
		<!-- jQuery 3.3.1 -->
		<script src="<?= base_url('lib/plugins/jQuery/jquery.min.js') ?>"></script>
		<!-- bootstrap 3.3.7 JS -->
		<script src="<?= base_url('lib/bootstrap/js/bootstrap.min.js') ?>" type="text/javascript"></script>
	</body>
</html>