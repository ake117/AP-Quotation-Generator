<section class="content-header">
    <h1>
        Category
        <small>Gerencie as category de postagens.</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= site_url('admin/dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="<?= site_url('admin/categories'); ?>"><i class="fa fa-tag"></i> Category</a></li>
        <li>Alteração de category</li>
    </ol>
</section>

<section class="content">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Alteração de category</h3>
        </div>
        <div class="box-body">
            <?php 
            echo form_open('admin/categories/edit/'.$row->id, array('role'=>'form'));

            echo div(array('class'=>'form-group'));
            echo form_label('Title', 'title');
            echo form_input(array('name'=>'title', 'value'=> $row->title, 'class'=>'form-control'));
            echo form_error('title');
            echo div(null, true);

            echo div(array('class'=>'form-group'));
            echo form_label('Description', 'description');
            echo form_textarea(array('name'=>'description', 'value'=> $row->description,'class'=>'form-control'));
            echo div(null, true);

            echo row();
            echo col(6);

            echo div(array('class'=>'form-group'));
            echo form_label('Category-pai', 'category_id');
            echo form_dropdown('category_id', $options, array($row->category_id), array('class'=>'form-control'));

            echo close_div(2);
            echo col(6);

            $options = config_item('posts_views');

            echo div(array('class'=>'form-group'));
            echo form_label('Type de visualização', 'view');
            echo form_dropdown('view', $options, array($row->view), array('class'=>'form-control'));

            echo close_div(3);
            //000
            // echo div(array('class'=>'form-group'));
            // echo form_label('Category-pai', 'category_id');
            // echo form_dropdown('category_id', $options, array($row->category_id), null, 'form-control');
            // echo div(null, true);
            //000
            echo form_button(array('type'=>'submit', 'name'=>'submit', 'content'=>'Save change', 'class'=>'btn btn-primary'));
            echo '&nbsp;';
            echo anchor('admin/categories', 'Cancel', array('class'=>'btn btn-danger'));

            echo form_close();
            ?>
        </div>
    </div>
</section>