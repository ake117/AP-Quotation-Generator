<?php
echo $this->wpanel->load_editor();
?>

<section class="content-header">
    <h1>
        Pages
        <small>Manage the fixed pages of the site here.</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= site_url('admin/dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="<?= site_url('admin/pages'); ?>"><i class="fa fa-files-o"></i> Pages</a></li>
        <li>New page</li>
    </ol>
</section>

<section class="content">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">New page</h3>
        </div>
        <div class="box-body">
            <?php
            echo form_open_multipart('admin/pages/add', array('role'=>'form'));

            echo div(array('class'=>'form-group'));
            echo form_label('Page title', 'title');
            echo form_input(array('name'=>'title', 'value'=> set_value('title'), 'class'=>'form-control'));
            echo form_error('title');
            echo close_div();

            echo div(array('class'=>'form-group'));
            echo form_label('Description for search mechanisms (No maximum 160 characters.)', 'description');
            echo form_textarea(array('name'=>'description', 'value'=> set_value('description'), 'class'=>'form-control', 'rows'=>'3'));
            echo form_error('description');
            echo close_div();

            echo div(array('class'=>'form-group'));
            echo form_label('Content', 'content');
            echo form_textarea(array('name'=>'content', 'value'=> set_value('content'), 'class'=>'form-control ckeditor', 'id'=>'editor'));
            echo form_error('content');
            echo close_div();

            echo row();
            echo col(5);
            echo div(array('class'=>'form-group'));
            echo form_label('Image Cover', 'userfile');
            echo form_input(array('name'=>'userfile', 'type'=>'file', 'class'=>'form-control'));
            echo close_div(2);

            echo col(4);
            echo div(array('class'=>'form-group'));
            echo form_label('Keywords (Separate multiple commas)', 'tags');
            echo form_textarea(array('name'=>'tags', 'value'=> set_value('tags'), 'class'=>'form-control', 'rows'=>'5'));
            echo close_div(2);

            // Opções de status
            $options = array(
                              '0'  => 'Rascunho',
                              '1'  => 'Published'
                            );
            echo col(3);
            echo div(array('class'=>'form-group'));
            echo form_label('Status', 'status');
            echo form_dropdown('status', $options, null, array('class'=>'form-control'));
            echo close_div(3);

            echo hr();

            echo row();
            echo col();
            echo form_button(
                    array(
                      'type'=>'submit', 
                      'name'=>'submit', 
                      'content'=>'Create', 
                      'class'=>'btn btn-primary'
                      )
                    );
            echo nbs(); // &nbsp;
            echo anchor('admin/pages', 'Cancel', array('class'=>'btn btn-danger'));
            echo close_div(2);

            echo form_close();
            ?>
        </div>
    </div>
</section>