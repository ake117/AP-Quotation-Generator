<?=$editor; ?>
<section class="content-header">
    <h1>
        Configuration
        <small>Manage general site configuration in this area.</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= site_url('admin/dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><i class="fa fa-cog"></i> Configuration</li>
    </ol>
</section>
<section class="content">
    <div class="box">
        <div class="box-body">
            <?= form_open_multipart('admin/configuracoes/index', array('role' => 'form')); ?>
            <ul class="nav nav-pills" role="tablist" style="margin-bottom:20px;">
                <li class="active"><a href="#geral" role="tab" data-toggle="tab">Configuration general</a></li>
                <li><a href="#home" role="tab" data-toggle="tab">Home page</a></li>
                <li><a href="#layout" role="tab" data-toggle="tab">Layout</a></li>
                <li><a href="#contato" role="tab" data-toggle="tab">Contacts</a></li>
                <li><a href="#social" role="tab" data-toggle="tab">Social and sharing</a></li>
                <li><a href="#backup" role="tab" data-toggle="tab">Backup of data</a></li>
            </ul>
            <div class="tab-content">
                <!--Painel de configuração geral-->
                <div class="tab-pane active  panel panel-default" id="geral">
                    <div class="panel-heading">
                        Configuration general
                    </div>
                    <div class="panel-body">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="site_title">Title site</label>
                                <input type="text" name="site_title" id="site_title" value="<?= $row->site_title; ?>" class="form-control" placeholder="Digite um título para o seu site." />
                            </div>
                            <div class="form-group">
                                <label for="site_desc">Description site</label>
                                <textarea name="site_desc" id="site_desc" class="form-control" placeholder="Digite uma descrição para melhorar a visibilidade do site nos mecanismos de busca."><?= $row->site_desc; ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="site_tags">Keyword site</label>
                                <input type="text" name="site_tags" id="site_tags" value="<?= $row->site_tags ?>" class="form-control" placeholder="Digite as palavras-chave para seu site." />
                            </div>
                            <div class="form-group">
                                <label for="copyright">Footnote (Copyright)</label>
                                <input type="text" name="copyright" id="copyright" value="<?= $row->copyright ?>" class="form-control" placeholder="Digite uma nota para o rodapé do seu site." />
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="home_category">Language</label>
                                <select name="language" class="form-control">
                                    <?php foreach (config_item('available_languages') as $key => $item) { ?>
                                        <option value="<?= $key; ?>" <?php
                                        if ($row->language == $key) {
                                            echo 'selected';
                                        }
                                        ?> ><?= $item['label']; ?></option>
                                            <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="home_category">Text editor</label>
                                <?php
                                $available_editors = config_item('available_editors');
                                echo form_dropdown('text_editor', $available_editors, array($row->text_editor), array('class' => 'form-control'));
                                ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="home_category">Author or responsible</label>
                                <input type="text" name="author" id="author" value="<?= $row->author ?>" class="form-control" placeholder="Ex: Nome do autor <email@site.com>" />
                            </div>
                        </div>
                    </div>
                </div>

                <!--Painel de configuração do layout-->
                <div class="tab-pane panel panel-default" id="layout">
                    <div class="panel-heading">
                        Layout
                    </div>
                    <div class="panel-body">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-4">
                                    <h4>Website Logo</h4>
                                    <hr/>
                                    <div class="form-group">
                                        <?php
                                        if ($row->logomarca) {
                                            echo img(array('src' => 'media/' . $row->logomarca, 'class' => 'img-responsive img-thumbnail', 'style' => 'margin-top:5px;'));
                                        }
                                        ?>
                                    </div>
                                    <div class="form-group">
                                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target=".modal-logomarca">Alterar</button>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <h4>Image background</h4>
                                    <hr/>
                                    <div class="form-group">
                                        <?php
                                        if ($row->background) {
                                            echo img(array('src' => 'media/' . $row->background, 'class' => 'img-responsive img-thumbnail', 'style' => 'margin-top:5px;'));
                                        }
                                        ?>
                                    </div>
                                    <div class="form-group">
                                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target=".modal-background">Alterar</button>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <h4>Background color</h4>
                                    <hr/>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="col-md-12" style="height: 50px; background-color: <?= $row->bgcolor ?>;"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="form-group">
                                                <input type="text" name="bgcolor" id="bgcolor" value="<?= $row->bgcolor ?>" class="form-control select_color" placeholder="Ex: #FF99CC" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--Painel de configuration de contato-->
                <div class="tab-pane panel panel-default" id="contato">
                    <div class="panel-heading">
                        Contacts
                    </div>
                    <div class="panel-body">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="site_contact">Recipient of the contact form</label>
                                <input type="text" name="site_contact" id="site_contact" value="<?= $row->site_contact ?>" class="form-control" placeholder="Enter the email that will receive the messages from the contact form." />
                            </div>
                            <div class="form-group">
                                <label for="site_telefone">Phone contact</label>
                                <input type="text" name="site_telefone" id="site_telefone" value="<?= $row->site_telefone ?>" class="form-control" placeholder="Enter the contact phone that will appear on the website." />
                            </div>
                            <div class="form-group">
                                <label for="texto_contato">Contact screen text</label>
                                <textarea name="texto_contato" id="editor" class="form-control ckeditor"><?= $row->texto_contato; ?></textarea>
                            </div>
                            <h4 style="margin-top:20px;">SMTP configuration</h4>
                            <hr/>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="use_smtp" value="1" <?= $smtp_checked; ?> class="checkbox" />
                                    Use your own SMTP to send the contact messages.
                                </label>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="smtp_host">Host SMTP</label>
                                        <input type="text" name="smtp_host" id="smtp_host" value="<?= $row->smtp_host ?>" class="form-control" placeholder="Inform your SMTP server." />
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="smtp_port">SMTP port</label>
                                        <input type="text" name="smtp_port" id="smtp_port" value="<?= $row->smtp_port ?>" class="form-control" placeholder="Enter the port of your SMTP server." />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="smtp_user">User</label>
                                        <input type="text" name="smtp_user" id="smtp_user" value="<?= $row->smtp_user ?>" class="form-control" placeholder="Inform your user connection" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="smtp_password">Password (visible)</label>
                                        <input type="text" name="smtp_password" id="smtp_password" value="<?= $row->smtp_password ?>" class="form-control" placeholder="Inform your password connection" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="usa_ssl" value="ssl" <?= $ssl_checked; ?> class="checkbox" />
                                            Use TLS / SSL authentication.
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--Painel de configuração social-->
                <div class="tab-pane panel panel-default" id="social">
                    <div class="panel-heading">
                        Social and sharing
                    </div>
                    <div class="panel-body">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="google_analytics">Code of <a href="http://analytics.google.com" target="_blank">Google Analytics</a></label>
                                <input type="text" name="google_analytics" id="google_analytics" value="<?= $row->google_analytics ?>" class="form-control" placeholder="Digite o código de rastreio do Googel Analytics." />
                            </div>
                            <div class="form-group">
                                <label for="addthis_uid">Code "Profile ID" do <a href="https://www.addthis.com" target="_blank">AddThis</a></label>
                                <input type="text" name="addthis_uid" id="addthis_uid" value="<?= $row->addthis_uid ?>" class="form-control" placeholder="Digite seu código de usuário do AddThis.com" />
                            </div>
                            <div class="form-group">
                                <label for="link_instagram">Link to the Instagram account</label>
                                <input type="text" name="link_instagram" id="link_instagram" value="<?= $row->link_instagram ?>" class="form-control" placeholder="Informe o linkn da sua conta no Instagram." />
                            </div>
                            <div class="form-group">
                                <label for="link_twitter">Link to the Twitter account</label>
                                <input type="text" name="link_twitter" id="link_twitter" value="<?= $row->link_twitter ?>" class="form-control" placeholder="Informe o link da sua conta no Twitter." />
                            </div>
                            <div class="form-group">
                                <label for="link_facebook">Link to the Facebook account</label>
                                <input type="text" name="link_facebook" id="link_facebook" value="<?= $row->link_facebook ?>" class="form-control" placeholder="Informe o link do seu perfil no Facebook." />
                            </div>
                            <div class="form-group">
                                <label for="link_likebox">Link da Fan-Page do Facebook para o likebox</label>
                                <input type="text" name="link_likebox" id="link_likebox" value="<?= $row->link_likebox ?>" class="form-control" placeholder="Informe o link da sua Fan-Page no Facebook." />
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Painel de backup dos dados -->
                <div class="tab-pane  panel panel-default" id="backup">
                    <div class="panel-heading">
                        Backup of data
                    </div>



                    <div class="panel-body">
                        <div class="col-md-12">
                            <p>Click the button below to perform a database backup. All information about posts, banners, configuration will be saved. </p>
                            <p>To back up files, such as images, do so using the ISP control panel. If you do not have access, contact your webmaster.</p>
                            <p class="text-danger">
                                <strong>Note:</strong> This feature is only available for MySQL databases.
                            </p>
                            <p><?= anchor('admin/backups/execute', '<span class="glyphicon glyphicon-circle-arrow-down"></span>Back up', array('class' => 'btn btn-success')); ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bs-confirma-modal-sm"><span class="glyphicon glyphicon-floppy-disk"></span> Save change</button>
            </div>
            <div class="modal fade bs-confirma-modal-sm" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header"><b>Confirmation</b></div>
                        <div class="modal-body">
                            <p>Are you sure you want to save these changes ?</p>
                            <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-ok"></span>Yes, I'm sure!</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</section>

<?= form_open_multipart('admin/configuracoes/altlogo'); ?>
<div class="modal fade modal-logomarca" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="gridSystemModalLabel">Alteração de logomarca</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="logomarca">Selecione uma nova imagem</label>
                    <input type="file" name="logomarca" id="logomarca" class="form-control" />
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-success">Save</button>
            </div>
        </div>
    </div>
</div>
<?= form_close(); ?>

<?= form_open_multipart('admin/configuracoes/altback'); ?>
<div class="modal fade modal-background" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="gridSystemModalLabel">Alteração de imagem de topo</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="background">Selecione uma nova imagem</label>
                    <input type="file" name="background" id="background" class="form-control" />
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-success">Save</button>
            </div>
        </div>
    </div>
</div>
<?= form_close(); ?>


<script>
var roxyFileman = '<?= base_url() ?>fileman/index.html';
CKEDITOR.replace( 'editor', {
    filebrowserBrowseUrl:roxyFileman,
    filebrowserImageBrowseUrl:roxyFileman+'?type=image',
    removeDialogTabs: 'link:upload;image:upload'
});
</script>
