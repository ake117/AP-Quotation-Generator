{include file="header.tpl"}
{ci_config name="base_url"}
{ci_language file="wpn_common" lang="$lang"}

<section class="content-header">
	<h1>
                {ci_language line="mod_dashboard" default="Dashboard"}
		<small>{ci_language line="wpn_welcome" default="Welcome to Production Cost CMS"}</small>
	</h1>
	<ol class="breadcrumb">
		<li><i class="fa fa-dashboard"></i> {ci_language line="mod_dashboard" default="Dashboard"}</li>
	</ol>
</section>

<section class="content">
	<div class="box">
		<div class="box-body">
			<div class="row">
				<div class="col-md-3 hidden-xs"><img src="{$avatar}" class="img-responsive img-thumbnail" alt="{$account_name}"/></div>
				<div class="col-md-4">
					<h2>{ci_language line="wpn_hello"} {$account_name}.</h2>
					<p>{$account_name} - {$account_email}</p>
					<p>{ci_language line="wpn_since"} {$account_created}</p>

					<p><a href="{site_url url="admin/accounts/profile"}" class="btn btn-primary">{ci_language line="lnk_manageprofile"}</a></p>
				</div>
				<div class="col-md-5">
					<h4>{ci_language line="wpn_sumary"}</h4>
					<table class="table table-striped">
						<tbody>
                                                    {*if wpn_link_permission('admin/booking') }
							<tr>
								<td>All Units</td>
								<td>{$total_booking}</td>
							</tr>
                                                    {/if*}

						</tbody>
					</table>
				</div>
			</div>
			<div class="row">
				<div class="col-md-4" style="margin-top:10px;">
					<a
					href="{site_url url=""}" target="_blank"
					class="btn btn-success btn-lg btn-block"
					style="padding-top:30px;padding-bottom:30px;">
						<span class="glyphicon glyphicon-globe"></span> {ci_language line="bot_view_site"}
					</a>
				</div>
			</div>
		</div>
	</div>
</section>

</script>

{include file="footer.tpl"}