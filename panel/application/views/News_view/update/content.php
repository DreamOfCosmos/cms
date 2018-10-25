<div class="row">
  <div class="col-md-12">
    <h4><?php echo "<b> $item->title </b> Ürününü düzenliyorsunuz." ?> </h4>
  </div>
  <div class="col-md-12">
    <div class="widget">
      <hr class="widget-separator">
      <div class="widget-body">
        <form action="<?php echo base_url("news/update/$item->id") ?>" method="POST" enctype="multipart/form-data">
          <div class="form-group">
            <label>Başlık</label>
            <input class="form-control" placeholder="Başlık" name="title" value="<?php echo $item->title;?>">
            <?php if(isset($form_error)){ ?>
              <small class="pull-right input-form-error"> <?php echo form_error("title"); ?></small>
            <?php } ?>
          </div>

          <div class="form-group">
            <label>Açıklama</label>
            <textarea  name="description" class="m-0"
            data-plugin="summernote"
            data-options="{height: 250}"
            style="display: none;">
            <?php echo $item->description ?>
          </textarea>
        </div>

        <div class="form-group">

          <label for="control-demo-6" >Haberin Türü</label>
          <div id="control-demo-6" >
            <?php if(isset($form_error)) { ?>
              <select class="form-control news_type_select" name="news_type">
                <option <?php echo ($news_type=="image") ? "selected":"" ; ?> value="image">Resim</option>
                <option <?php echo ($news_type=="video") ? "selected":"" ; ?> value="video">video</option>
              </select>
            <?php }else{ ?>
              <select class="form-control news_type_select" name="news_type">
                <option <?php echo ($item->news_type=="image") ? "selected":"" ; ?> value="image">Resim</option>
                <option <?php echo ($item->news_type=="video") ? "selected":"" ; ?> value="video">video</option>
              </select>
            <?php } ?>
          </div>
        </div>

        <?php if(isset($form_error)){ ?>
          <div class="form-group image_upload_con" style="display:<?php echo($item->news_type =="image") ? "block":"none"; ?>">
            <label>Görsel Seçiniz</label>
            <input type="file" name="img_url" class="form-control">
          </div>
          <div class="form-group video_url_con"style="display:<?php echo($item->news_type =="video") ? "block":"none"; ?>">
            <label>Video URL</label>
            <input class="form-control" placeholder="Video bağlantısını buraya yapıştırınız" name="video_url">
            <?php if(isset($form_error)){ ?>
              <small class="pull-right input-form-error"> <?php echo form_error("video_url"); ?></small>
            <?php } ?>
          </div>
        <?php }else{ ?>
          <div class="row">
            <div class="col-md-1 image_upload_con">
              <img src="<?php echo base_url("uploads/$viewFolder/$item->img_url") ?>" alt="" class="img-responsive">
            </div>
            <div class="col-md-9">
              <div class="form-group image_upload_con"style="display:<?php echo($item->news_type =="image") ? "block":"none"; ?>">
                <label>Görsel Seçiniz</label>
                <input type="file" name="img_url" class="form-control">
              </div>
            </div>
          </div>
          <div class="form-group video_url_con" style="display:<?php echo($item->news_type =="video") ? "block":"none"; ?>">
            <label>Video URL</label>
            <input class="form-control" placeholder="Video bağlantısını buraya yapıştırınız"
            name="video_url" value="<?php echo $item->video_url ?>">
          </div>
        <?php } ?>

        <button type="submit" class="btn btn-primary btn-md">Güncelle</button>
        <a href="<?php echo base_url("news") ?>" class="btn btn-danger">İptal</a>
      </form>
    </div><!-- .widget-body -->
  </div><!-- .widget -->
</div>
</div>
