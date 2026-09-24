<section class="content-header">
    <h1>
        Category
        <small>Gerencie as category de postagens.</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= site_url('admin/dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="<?= site_url('admin/categories'); ?>"><i class="fa fa-tag"></i> Category</a></li>
        <li>Cadastro de category</li>
    </ol>
</section>

<section class="content">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Cadastro de category</h3>
        </div>
        <div class="box-body">
            <?php
            echo form_open('admin/categories/add', array('role'=>'form'));

            echo div(array('class'=>'form-group'));
            echo form_label('Title', 'title');
            echo form_input(array('name'=>'title', 'value'=> set_value('name'), 'class'=>'form-control'));
            echo form_error('title');
            echo div(null, true);

            echo div(array('class'=>'form-group'));
            echo form_label('Description', 'description');
            echo form_textarea(array('name'=>'description', 'class'=>'form-control'));
            echo div(null, true);

            echo row();
            echo col(6);

            echo div(array('class'=>'form-group'));
            echo form_label('Category-pai', 'category_id');
            echo form_dropdown('category_id', $options, '', array('class'=>'form-control'));

            echo close_div(2);
            echo col(6);
            
            $options = config_item('posts_views');

            echo div(array('class'=>'form-group'));
            echo form_label('Type de visualização', 'view');
            echo form_dropdown('view', $options, '', array('class'=>'form-control'));

            echo close_div(3);


            echo form_button(array('type'=>'submit', 'name'=>'submit', 'content'=>'Create', 'class'=>'btn btn-primary'));
            echo '&nbsp;';
            echo anchor('admin/categories', 'Cancel', array('class'=>'btn btn-danger'));

            echo form_close();
            ?>
        </div>
    </div>
</section>