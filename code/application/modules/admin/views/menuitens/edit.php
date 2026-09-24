<section class="content-header">
    <h1>
        Menus
        <small>Manage your site navigation menus and options here.</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= site_url('admin/dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="<?= site_url('admin/menus'); ?>"><i class="fa fa-reorder"></i> Menus</a></li>
        <li>Changing menu items</li>
    </ol>
</section>

<section class="content">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Changing menu items</h3>
        </div>
        <div class="box-body">
            <?= form_open('admin/menuitens/edit/' . $row->id, array('role'=>'form')); ?>
                <input type="hidden" name="menu_id" value="<?= $row->menu_id; ?>" />
                <div class="form-group">
                    <label for="label">Link Label</label>
                    <input type="text" name="label" id="label" value="<?= $row->label; ?>" class="form-control" />
                    <?= form_error('label'); ?>
                </div>
                <div class="form-group">
                    <p><label for="type">Link Type</label></p>
                    <div class="radio">
                        <label id="tipo_link">
                            <input type="radio" name="type" id="tipo_link" value="link" <?php if($row->type == 'link'){ echo 'checked="checked"'; } ?> />
                            Link external
                        </label>
                        <label>
                            <input type="radio" name="type" id="tipo_post" value="post" <?php if($row->type == 'post'){ echo 'checked="checked"'; } ?> />
                            Page or post
                        </label>
                        <label>
                            <input type="radio" name="type" id="tipo_posts" value="posts" <?php if($row->type == 'posts'){ echo 'checked="checked"'; } ?> />
                            List of post
                        </label>
                        <label>
                            <input type="radio" name="type" id="tipo_funcional" value="funcional" <?php if($row->type == 'funcional'){ echo 'checked="checked"'; } ?> />
                            Function page
                        </label>
                        <label>
                            <input type="radio" name="type" id="tipo_submenu" value="submenu" <?php if($row->type == 'submenu'){ echo 'checked="checked"'; } ?> />
                            Sub-Menu
                        </label>
                    </div>
                    <?= form_error('type'); ?>
                </div>   
                <div class="form-group" id="form_link"<?php if($row->type == 'link'){ echo 'style="display:block;"'; } else {echo 'style="display:none;"';} ?>>
                    <label for="link">Link external</label>
                    <input type="text" name="link" id="link" value="<?= $row->href; ?>" class="form-control" />
                    <?= form_error('link'); ?>
                </div>
                <div class="form-group" id="form_post" <?php if($row->type == 'post'){ echo 'style="display:block;"'; } else {echo 'style="display:none;"';} ?>>
                    <label for="post_id">Página ou Postagem</label>
                    <select class="form-control" name="post_id" id="post_id">
                        <?php
                        foreach ($posts as $row_post)
                        {
                            if($row->href == $row_post->id){ $select = 'selected="selected"';}
                            echo '<option value="' . $row_post->link . '" '.$select . '>' . $row_post->title . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group" id="form_posts" <?php if($row->type == 'posts'){ echo 'style="display:block;"'; } else {echo 'style="display:none;"';} ?>>
                    <label for="category_id">Listing de category</label>
                    <select class="form-control" name="category_id" id="category_id">
                        <?php
                        foreach ($category as $row_cat)
                        {
                            if($row->href == $row_cat->id){ $select = 'selected="selected"';}
                            echo '<option value="' . $row_cat->id . '" '.$select . '>' . $row_cat->title . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group" id="form_funcional" <?php if($row->type == 'funcional'){ echo 'style="display:block;"'; } else {echo 'style="display:none;"';} ?>>
                    <label for="funcional">Function page</label>
                    <select class="form-control" name="funcional" id="funcional">
                        <?php foreach(config_item('funcional_links') as $key => $value){ ?>
                            <option value="<?= $key; ?>" <?php if($row->href == $key){ echo 'selected="selected"';} ?>><?= $value ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group" id="form_submenu" <?php if($row->type == 'submenu'){ echo 'style="display:block;"'; } else {echo 'style="display:none;"';} ?>>
                    <label for="funcional">Sub-menu</label>
                    <select class="form-control" name="submenu" id="funcional">
                        <option value="">[Selecione um menu]</option>
                        <?php foreach($menus as $row_menu){ ?>
                        <option value="<?= $row_menu->id; ?>" <?php if($row->href == $row_menu->id){ echo 'selected="selected"'; } ?>><?= $row_menu->nome; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="ordem">Display order</label>
                            <input type="text" name="ordem" id="ordem" value="<?= $row->ordem; ?>" class="form-control" />
                            <?= form_error('ordem'); ?>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Save change</button>
                    <?= anchor('admin/menus', 'Cancel', array('class' => 'btn btn-danger')); ?>
                </div>
            </form>
        </div>
    </div>
</section>