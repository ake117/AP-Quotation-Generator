<section class="content-header">
    <h1>
        Photo albums
        <small>Manage your site's photo albums here.</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= site_url('admin/dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="<?= site_url('admin/albuns'); ?>"><i class="fa fa-camera"></i> Albums</a></li>
        <li>Alteração de álbum</li>
    </ol>
</section>

<section class="content">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Alteração de álbum</h3>
        </div>
        <div class="box-body">
            <?= form_open_multipart('admin/albuns/edit/'.$row->id, array('role'=>'form')); ?>
                <div class="form-group">
                    <label for="titulo">Title</label>
                    <input type="text" name="titulo" value="<?= $row->titulo; ?>" class="form-control" />
                    <?= form_error('titulo'); ?>
                </div>
                <div class="form-group">
                    <label for="descricao">Description</label>
                    <textarea name="descricao" rows="5" class="form-control"><?= $row->descricao; ?></textarea>
                    <?= form_error('descricao'); ?>
                </div>
                <div class="row">
                    <div class="col-sm-5 col-md-5">
                        <div class="form-group">
                            <label for="userfile">Image Cover</label>
                            <input type="file" name="userfile" class="form-control" />
                            <p style="margin-top:15px;"><b>Cover image preview:</b></p>
                            <?php
                            $data = array(
                                'src'=>'media/capas/'.$row->capa,
                                'class'=>'img-responsive img-thumbnail',
                                'style'=>'margin-top:5px;'
                            );
                            echo img($data);
                            ?>
                            <label>
                                <input type="checkbox" name="alterar_imagem" value="1" />
                                Alter image
                            </label>
                        </div>
                    </div>
                    <div class="col-sm-3 col-md-3">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" class="form-control">
                                <option value="0" <?php if($row->status == 0){ echo 'selected'; } ?>>Unavailable</option>
                                <option value="1" <?php if($row->status == 1){ echo 'selected'; } ?>>Published</option>
                            </select>
                        </div>
                    </div>
                </div>
                <hr/>
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <button type="submit" class="btn btn-primary">Save change</button>
                        &nbsp;<?= anchor('admin/albuns', 'Cancel', array('class' => 'btn btn-danger')); ?>
                    </div>
                </div>
            <?= form_close(); ?>
        </div>
    </div>
</section>