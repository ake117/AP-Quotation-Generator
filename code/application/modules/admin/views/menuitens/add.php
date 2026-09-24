<section class="content-header">
    <h1>
        Menus
        <small>Manage your site navigation menus and options here.</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= site_url('admin/dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="<?= site_url('admin/menus'); ?>"><i class="fa fa-reorder"></i> Menus</a></li>
        <li>Cadastro de ítem de menu</li>
    </ol>
</section>

<section class="content">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Cadastro de ítem de menu</h3>
        </div>
        <div class="box-body">
            <?= form_open('admin/menuitens/add/' . $menu_id, array('role'=>'form')); ?>
                <div class="form-group">
                    <label for="label">Link Label</label>
                    <input type="text" name="label" id="label" class="form-control" />
                    <?= form_error('label'); ?>
                </div>
                <div class="form-group">
                    <p><label for="type">Link Type</label></p>
                    <div class="radio">
                        <label id="tipo_link">
                            <input type="radio" name="type" id="tipo_link" value="link" checked="checked" />
                            Link external
                        </label>
                        <label>
                            <input type="radio" name="type" id="tipo_post" value="post" />
                            Page or post
                        </label>
                        <label>
                            <input type="radio" name="type" id="tipo_posts" value="posts" />
                            List of post
                        </label>
                        <label>
                            <input type="radio" name="type" id="tipo_funcional" value="funcional" />
                            Function page
                        </label>
                        <label>
                            <input type="radio" name="type" id="tipo_submenu" value="submenu" />
                            Sub-Menu
                        </label>
                    </div>
                    <?= form_error('type'); ?>
                </div>
                <div class="form-group" id="form_link">
                    <label for="link">Link external</label>
                    <input type="text" name="link" id="link" class="form-control" />
                    <?= form_error('link'); ?>
                </div>
                <div class="form-group" id="form_post" style="display: none;">
                    <label for="post_id">Página ou Postagem</label>
                    <select class="form-control" name="post_id" id="post_id">
                        <?php
                        foreach ($posts as $row_post)
                        {
                            echo '<option value="' . $row_post->link . '">' . $row_post->title . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group" id="form_posts" style="display: none;">
                    <label for="category_id">Listing de category</label>
                    <select class="form-control" name="category_id" id="category_id">
                        <?php
                        foreach ($category as $row_cat)
                        {
                            echo '<option value="' . $row_cat->id . '">' . $row_cat->title . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group" id="form_funcional" style="display: none;">
                    <label for="funcional">Function page</label>
                    <select class="form-control" name="funcional" id="funcional">
                        <?php foreach(config_item('funcional_links') as $key => $value){ ?>
                            <option value="<?= $key; ?>"><?= $value ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group" id="form_submenu" style="display: none;">
                    <label for="funcional">Sub-menu</label>
                    <select class="form-control" name="submenu" id="funcional">
                        <option value="">[Selecione um menu]</option>
                        <?php foreach($menus as $row_menu){ ?>
                        <option value="<?= $row_menu->id; ?>"><?= $row_menu->nome; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="ordem">Display order</label>
                            <input type="text" name="ordem" id="ordem" class="form-control" />
                            <?= form_error('ordem'); ?>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Create</button>
                    <?= anchor('admin/menus', 'Cancel', array('class' => 'btn btn-danger')); ?>
                </div>
            </form>
        </div>
    </div>
</section>