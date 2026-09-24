{include file="header.tpl"}
{ci_config name="base_url"}
{ci_language file="wpn_common" lang="$lang"}
<section class="content-header">
    <h1>
        {ci_language line="mod_agents"}
        <small>Manage the Agents displayed on the site here.</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{site_url url="admin/dashboard"}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{site_url url="admin/agents"}"><i class="fa fa-shirtsinbulk" aria-hidden="true"></i> {ci_language line="mod_agents"}</a></li>
        <li>Add {ci_language line="mod_agents"}</li>
    </ol>
</section>

<section class="content">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Add {ci_language line="mod_agents"}</h3>
        </div>
        <div class="box-body">
            <form action="{site_url url="admin/agents/add"}" name="myform" role="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
            <div class="row">
                <div class="col-sm-12 col-md-8">
                    <div class="row">
                        <div class="col-sm-2 col-md-2">
                            <div class="form-group">
                                <label for="prefix">Prefix</label>
                                {html_options name='prefix' options=$prefix_options selected={$prefix} class='form-control'}
                                {form_error('prefix')}
                            </div>    
                        </div>
                        <div class="col-sm-5 col-md-5">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" name="name" value="{$name}" class="form-control">
                                {form_error('name')}
                            </div>
                        </div>
                        <div class="col-sm-5 col-md-5">
                            <div class="form-group">
                                <label for="surname">Surname</label>
                                <input type="text" name="surname" value="{$surname}" class="form-control">
                                {form_error('surname')}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                            
            <div class="row">
                <div class="col-sm-12 col-md-8">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="text" name="email" value="{$email}" class="form-control">
                        {form_error('email')}
                    </div>
                </div>
            </div>
                    
            <div class="row">
                <div class="col-sm-12 col-md-8">
                    <div class="row">
                        <div class="col-sm-6 col-md-6">
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="text" name="password" value="{$password}" class="form-control">
                                <span style="color: red;">*หากลืมรหัสผ่าน ต้องทำการเปลี่ยนรหัสผ่านใหม่อย่างเดียว</span>
                                {form_error('password')}
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6">
                            <div class="form-group">
                                <label style="margin-top:25px;">
                                    <input type="button" class="form-control" value="Generate" onClick="generate();">
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                    
            <div class="row">
                <div class="col-sm-12 col-md-3">
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="text" name="phone" value="{$phone}" class="form-control">
                        {form_error('phone')}
                    </div>
                </div>
            </div>

            <div class="row ">
                <div class="col-md-3" >
                    <div class="form-group">
                        <label for="is_active">Status</label>
                        {html_options name='agent_status' options=$agent_status_options selected=$agent_selected class='form-control'}
                    </div>
                </div>
            </div>
            <hr>
            <div class="row ">
                <div class="col-md-12">
                <button name="submit" type="submit" class="btn btn-primary">Create</button>
                &nbsp;<a href="{site_url url="admin/agents"}" class="btn btn-danger">Cancel</a></div>
            </div>
        </form>

        </div>
    </div>
</section>
<script type="text/javascript">                
function randomPassword(length) {
    var chars = "abcdefghijklmnopqrstuvwxyz!@#&*ABCDEFGHIJKLMNOP1234567890";
    var pass = "";
    for (var x = 0; x < length; x++) {
        var i = Math.floor(Math.random() * chars.length);
        pass += chars.charAt(i);
    }
    return pass;
}

function generate() {
    myform.password.value = randomPassword(10);
}
</script>
{include file="footer.tpl"}