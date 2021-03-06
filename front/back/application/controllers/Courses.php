<?php

class Courses extends CI_Controller
{
  public $viewFolder="";
  public function __construct(){
    parent::__construct();
    $this->viewFolder="Courses_view";
    $this->load->model("Course_model");
    if(!get_active_user()){
        redirect(base_url("login"));
    }

  }
  public function index(){
    $viewData = new stdClass();
    //Tablodan veri getirilmesi
    $items=$this->Course_model->get_all(array(),"rank ASC");

    //View'e yollanacak veriler
    $viewData->viewFolder = $this->viewFolder;
    $viewData->subViewFolder="list";
    $viewData->items=$items;
    $this->load->view("{$this->viewFolder}/{$viewData->subViewFolder}/index",$viewData);
  }
  public function add_Courses(){
    $viewData = new stdClass();
    $viewData->viewFolder=$this->viewFolder;
    $viewData->subViewFolder="add";
    $this->load->view("{$this->viewFolder}/{$viewData->subViewFolder}/index",$viewData);
  }
  public function saved(){
    $this->load->library("form_validation");
    if($_FILES["img_url"]["name"] == ""){

      $alert = array(
        "title" => "İşlem Başarısız",
        "text" => "Lütfen bir görsel seçiniz",
        "type"  => "error"
      );
      $this->session->set_flashdata("alert", $alert);
      redirect(base_url("Courses/add_Courses"));
    }

    $this->form_validation->set_rules("title","Başlık","required|trim");
    $this->form_validation->set_rules("event_date","Eğitim Tarihi","required|trim");
    $validation=$this->form_validation->run();

    if($validation)
    {

        $file_name = convertToSEO(pathinfo($_FILES["img_url"]["name"], PATHINFO_FILENAME)) . "." . pathinfo($_FILES["img_url"]["name"], PATHINFO_EXTENSION);
        $image_255x157 = upload_picture($_FILES["img_url"]["tmp_name"], "uploads/$this->viewFolder",255,157, $file_name);
        $image_1140x705 = upload_picture($_FILES["img_url"]["tmp_name"], "uploads/$this->viewFolder",1140,705, $file_name);
        if($image_255x157 && $image_1140x705){
          $insert = $this->Course_model->add(
            array(
              "title"         => $this->input->post("title"),
              "description"   => $this->input->post("description"),
              "url"           => convertToSEO($this->input->post("title")),
              "img_url"       => $file_name,
              "event_date"    => $this->input->post("event_date"),
              "rank"          => 0,
              "isActive"      => 1,
              "createdAt"     => date("Y-m-d H:i:s")
            )
          );

          // TODO Alert sistemi eklenecek...
          if($insert){

            $alert = array(
              "title" => "İşlem Başarılı",
              "text" => "Kayıt başarılı bir şekilde eklendi",
              "type"  => "success"
            );

          } else {

            $alert = array(
              "title" => "İşlem Başarısız",
              "text" => "Kayıt Ekleme sırasında bir problem oluştu",
              "type"  => "error"
            );
          }

        } else {

          $alert = array(
            "title" => "İşlem Başarısız",
            "text" => "Görsel yüklenirken bir problem oluştu",
            "type"  => "error"
          );

          $this->session->set_flashdata("alert", $alert);

          redirect(base_url("courses/add_Courses"));

          die();

        }

        // İşlemin Sonucunu Session'a yazma işlemi...
        $this->session->set_flashdata("alert", $alert);

        redirect(base_url("courses"));

      }
    else
    {
      $viewData= new stdClass();
      $viewData->viewFolder=$this->viewFolder;
      $viewData->subViewFolder="add";
      $viewData->form_error= true;
      $this->load->view("{$this->viewFolder}/{$viewData->subViewFolder}/index",$viewData);
    }
  }
  public function update_Courses($id){
    $viewData = new stdClass();
    $item=$this->Course_model->
    get(
      array(
        "id"  =>  $id
      )
    );
    $viewData->viewFolder=$this->viewFolder;
    $viewData->subViewFolder="update";
    $viewData->item=$item;
    $this->load->view("{$this->viewFolder}/{$viewData->subViewFolder}/index",$viewData);
  }
  public function update($id){
    $this->load->library("form_validation");
    $this->form_validation->set_rules("title", "Başlık", "required|trim");
    $this->form_validation->set_rules("event_date", "Eğitim Tarihi", "required|trim");
    $this->form_validation->set_message(
      array(
        "required"  => "<b>{field}</b> alanı doldurulmalıdır"
      )
    );
    $validate = $this->form_validation->run();
    if($validate){

      if($_FILES["img_url"]["name"] !== "") {

          $file_name = convertToSEO(pathinfo($_FILES["img_url"]["name"], PATHINFO_FILENAME)) . "." . pathinfo($_FILES["img_url"]["name"], PATHINFO_EXTENSION);
          $config["allowed_types"] = "jpg|jpeg|png";
          $config["upload_path"] = "uploads/$this->viewFolder/";
          $config["file_name"] = $file_name;
          $this->load->library("upload", $config);
          $upload = $this->upload->do_upload("img_url");
          if ($upload) {
            $uploaded_file = $this->upload->data("file_name");
            $data = array(
              "title"       => $this->input->post("title"),
              "description" => $this->input->post("description"),
              "url"         => convertToSEO($this->input->post("title")),
              "img_url"     => $uploaded_file,
              "event_date"  =>$this->input->post("event_date"),
            );
          } else {
            $alert = array(
              "title" => "İşlem Başarısız",
              "text" => "Görsel yüklenirken bir problem oluştu",
              "type" => "error"
            );

            $this->session->set_flashdata("alert", $alert);
            redirect(base_url("Courses/update_Courses/$id"));
          }
        } else {

          $data = array(
            "title" => $this->input->post("title"),
            "description" => $this->input->post("description"),
            "url" => convertToSEO($this->input->post("title")),
            "event_date"  =>$this->input->post("event_date"),
          );
        }

      $update = $this->Course_model->update(array("id" => $id), $data);
      if($update){

        $alert = array(
          "title" => "İşlem Başarılı",
          "text" => "Kayıt başarılı bir şekilde güncellendi",
          "type"  => "success"
        );
      } else {
        $alert = array(
          "title" => "İşlem Başarısız",
          "text" => "Kayıt Güncelleme sırasında bir problem oluştu",
          "type"  => "error"
        );
      }

      // İşlemin Sonucunu Session'a yazma işlemi...
      $this->session->set_flashdata("alert", $alert);

      redirect(base_url("Courses"));

    } else {

      $viewData = new stdClass();

      /** View'e gönderilecek Değişkenlerin Set Edilmesi.. */
      $viewData->viewFolder = $this->viewFolder;
      $viewData->subViewFolder = "update";
      $viewData->form_error = true;

      $viewData->item = $this->Course_model->get(
        array(
          "id"    => $id,
        )
      );
      $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
    }
  }
  public function delete_Courses($id){
    $fileName=$this->Course_model->get(
      array(
        "id" => $id
      ));
    $delete=$this->Course_model->delete(
      array(
        "id" => $id
      ));
      if($delete)
      {
        $img1="uploads/{$this->viewFolder}/255x157/$fileName->img_url";
        $img2="uploads/{$this->viewFolder}/1140x705/$fileName->img_url";
        if(unlink($img1) && unlink($img2)){
          $alert =array(
            "title" => "İşlem Başarılı..",
            "text"  => "Fotoğraf Silme Başarılı Bir Şekilde Gerçekleşti..",
            "type"  => "success"
          );
        }
        else
        {
          $alert =array(
            "title" => "İşlem Başarısız..",
            "text"  => "Fotoğraf Silinirken Bir Hata Oluştu...",
            "type"  => "error"
          );
        }
      }
      else
      {
        $alert =array(
          "title" => "İşlem Başarısız..",
          "text"  => "Kayıt Silme Sırasında Bir Hata Oluştu...",
          "type"  => "error"
        );
      }
      $this->session->set_flashdata("alert",$alert);
      redirect(base_url("Courses"));
  }
  public function isActiveSet($id){
      if($id){
        $isActive=($this->input->post("data")==="true") ? 1 : 0;

        $this->Course_model->update(
          array(
            "id" => $id
          ),
          array(
            "isActive" =>$isActive
          )
        );
      }
    }
  public function rankSet(){
      $data=$this->input->post("data");
      parse_str($data,$order);
      $items=$order["tr"];
      foreach ($items as $rank => $id) {
        $this->Course_model->update(
          array(
            "id"      =>$id,
            "rank !=" =>$rank
          ),
          array(
            "rank"    =>$rank
          )
        );
      }

  }

  }?>
