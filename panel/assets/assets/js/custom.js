$(document).ready(function(){

  iziToast.show({
    title: 'Hey',
    message: 'What would you like to add?',
    position:'TopCenter'
  });
  <!--  !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!   -->
  $(".sortable").sortable();
  <!--  !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!   -->
    $(".content-container,.image-list-container").on('click','.remove-btn',function(){
    var $data_url= $(this).data("url");
    swal({
      title: 'Emin misiniz?',
      text: "Bu işlemi geri alamazsınız!",
      type: 'Dikkat',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Evet Sil!',
      cancelButtonText:"Hayır"
    }).then((result) => {
      if (result.value) {

        window.location.href=$data_url;
      }
    })
  });
  <!--  !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!   -->
  $(".content-container,.image-list-container").on('change','.isActive',function(){
    var $data=$(this).prop("checked");
    var $data_url=$(this).data("url");
    if(typeof $data !== "undefined" && typeof $data_url !=="undefined"){
      $.post($data_url,{data:$data},function(response){
      });
    }
  });
  <!--  !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!   -->
  $(".image-list-container").on('change','.isCover',function(){
    var $data=$(this).prop("checked");
    var $data_url=$(this).data("url");
    if(typeof $data !== "undefined" && typeof $data_url !=="undefined"){
      $.post($data_url,{data:$data},function(response){
        $(".image-list-container").html(response)

        $('[data-switchery]').each(function(){
          var $this = $(this),
          color = $this.attr('data-color') || '#188ae2',
          jackColor = $this.attr('data-jackColor') || '#ffffff',
          size = $this.attr('data-size') || 'default'

          new Switchery(this, {
            color: color,
            size: size,
            jackColor: jackColor
          });
        });
        $(".sortable").sortable();
      });

    }
  });
  <!--  !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!   -->
  $(".content-container,.image-list-container").on('sortupdate','.sortable',function(event,ui){

    var $data= $(this).sortable("serialize");
    var $data_url =$(this).data("url");
    $.post($data_url,{data:$data},function(response){});
  });
  <!--  !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!   -->
  var uploadSection = Dropzone.forElement("#dropzone");

  uploadSection.on("complete", function(file){

    var $data_url =$("#dropzone").data("url");
    $.post($data_url,{},function(response){
      $(".image-list-container").html(response)
      $('[data-switchery]').each(function(){
        var $this = $(this),
        color = $this.attr('data-color') || '#188ae2',
        jackColor = $this.attr('data-jackColor') || '#ffffff',
        size = $this.attr('data-size') || 'default'

        new Switchery(this, {
          color: color,
          size: size,
          jackColor: jackColor
        });
      });
      $(".sortable").sortable();
    })
  })
})