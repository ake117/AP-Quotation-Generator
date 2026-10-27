{include file="header.tpl"}
{ci_config name="base_url"}
{ci_language file="wpn_common" lang="$lang"}

<section class="content-header">
    <h1>
        {ci_language line="mod_profile"}
        <small>Keep your data always up to date.</small>
    </h1>
    <ol class="breadcrumb">
        
        <li>{ci_language line="mod_profile"}</li>
    </ol>
</section>

<section class="content">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Alter user profile</h3>
        </div>
        <div class="box-body">
            <form action="{site_url url="admin/accounts/profile"}" role="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Full name</label>
                            <input type="text" name="name" value="{$row_account->name}" class="form-control" />
                            {form_error('name')}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" value="{$row_account->email}" class="form-control" />
                            {form_error('email')}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password" class="form-control" />
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="change_password" value="1">
                                    Alter password
                                </label>
                            </div>
                            {form_error('password')}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="thumbnail">
                            {if !empty($row_account->avatar)}
                                <img src="{$base_url}/media/avatar/{$row_account->avatar}" class="img-responsive" />
                            {else}
                                <img src="{$base_url}/lib/img/no-user.jpg" class="img-responsive" />
                            {/if}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="userfile">User photo</label>
                            <input type="file" name="userfile" class="form-control" />
                            <input type="hidden" name="avatar" value="{$row_account->avatar}"/>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="change_avatar" value="1">
                                    Alter photo
                                </label>
                            </div>
                        </div>
                    </div>
                     <div class="col-md-3">
                        <div class="form-group">
                            <label for="skin">Color style</label>
                            {html_options name='skin' options=$skin_options selected=$row_account->skin_selected class='form-control'}
                            {form_error('skin')}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" name="submit" class="btn btn-primary">Save changes</button>
                        <a href="{site_url url="admin/accounts"}" class="btn btn-danger">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
{include file="footer.tpl"}