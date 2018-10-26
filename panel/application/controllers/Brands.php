<?php

class Brands extends CI_Controller
{
  public $viewFolder="";
  public function __construct()
  {
    parent::__construct();
    $this->viewFolder="Brands_view";
    $this->load->model("Brand_model");

  }
  public function index()
  {
    $viewData = new stdClass();
    //Tablodan veri getirilmesi
    $items=$this->Brand_model->get_all(array(),"rank ASC");

    //View'e yollanacak veriler
    $viewData->viewFolder = $this->viewFolder;
    $viewData->subViewFolder="list";
    $viewData->items=$items;
    $this->load->view("{$this->viewFolder}/{$viewData->subViewFolder}/index",$viewData);
  }
  public function add_Brands()
  {
    $viewData = new stdClass();
    $viewData->viewFolder=$this->viewFolder;
    $viewData->subViewFolder="add";
    $this->load->view("{$this->viewFolder}/{$viewData->subViewFolder}/index",$viewData);
  }
  public function saved()
  {
    $this->load->library("form_validation");
    if($_FILES["img_url"]["name"] == ""){

      $alert = array(
        "title" => "İşlem Başarısız",
        "text" => "Lütfen bir görsel seçiniz",
        "type"  => "error"
      );
      $this->session->set_flashdata("alert", $alert);
      redirect(base_url("Brands/add_Brands"));
    }

    $this->form_validation->set_rules("title","Başlık","required|trim");
    $validation=$this->form_validation->run();

    if($validation)
    {
      $file_name=convertToSEO(pathinfo($_FILES["img_url"]["name"],PATHINFO_FILENAME)). "." .pathinfo($_FILES["img_url"]["name"],PATHINFO_EXTENSION);
      $config["allowed_types"]="jpg||jpeg||png";
      $config["upload_path"]="uploads/$this->viewFolder/";
      $config["file_name"]= $file_name;

      $this->load->library("upload",$config);
      $upload=$this->upload->do_upload("img_url");
      if($upload)
      {
        $uploaded_file = $this->upload->data("file_name");

        $data=array(
          "title"       =>$this->input->post("title"),
          "img_url"     =>$uploaded_file,
          "rank"        =>0,
          "isActive"    =>1,
          "createdAt"   =>date("Y-m-d H:i:s")
        );
      }else{
        $alert =array(
          "title" => "İşlem Başarısız..",
          "text"  => "Görsel Yüklenirken Bir Problem Oluştu...",
          "type"  => "error"
        );
        $this->session->set_flashdata("alert",$alert);
        redirect(base_url("Brands/add_Brands"));
      }

      $insert=$this->Brand_model->add($data);
      if($insert)
      {
        $alert =array(
          "title" => "İşlem Başarılı..",
          "text" => "İşlem Başarılı Bir Şekilde Gerçekleşti..",
          "type" => "success"
        );

      }
      else
      {
        $alert =array(
          "title" => "İşlem Başarısız..",
          "text" => "İşlem Sırasında Bir Hata Oluştu...",
          "type" => "error"
        );
      }
      $this->session->set_flashdata("alert",$alert);
      redirect(base_url("Brands"));

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
  public function update_Brands($id)
  {
    $viewData = new stdClass();
    $item=$this->Brand_model->
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
  public function update($id)
  {
    $this->load->library("form_validation");
    $this->form_validation->set_rules("title", "Başlık", "required|trim");
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
              "img_url"     => $uploaded_file,
            );
          } else {
            $alert = array(
              "title" => "İşlem Başarısız",
              "text" => "Görsel yüklenirken bir problem oluştu",
              "type" => "error"
            );

            $this->session->set_flashdata("alert", $alert);
            redirect(base_url("Brands/update_Brands/$id"));
          }
        } else {

          $data = array(
            "title" => $this->input->post("title"),
          );
        }

      $update = $this->Brand_model->update(array("id" => $id), $data);
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

      redirect(base_url("Brands"));

    } else {

      $viewData = new stdClass();

      /** View'e gönderilecek Değişkenlerin Set Edilmesi.. */
      $viewData->viewFolder = $this->viewFolder;
      $viewData->subViewFolder = "update";
      $viewData->form_error = true;

      $viewData->item = $this->Brand_model->get(
        array(
          "id"    => $id,
        )
      );
      $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
    }
  }
  public function delete_Brands($id)
  {
    $delete=$this->Brand_model->delete(
      array(
        "id" => $id
      ));

      if($delete)
      {
        $alert =array(
          "title" => "İşlem Başarılı..",
          "text" => "Kayıt Başarılı Bir Şekilde Silindi..",
          "type" => "success"
        );

      }
      else
      {
        $alert =array(
          "title" => "İşlem Başarısız..",
          "text" => "Kayıt Silme Sırasında Bir Hata Oluştu...",
          "type" => "error"
        );
      }
      $this->session->set_flashdata("alert",$alert);
      redirect(base_url("Brands"));
  }
  public function isActiveSet($id)
  {
      if($id){
        $isActive=($this->input->post("data")==="true") ? 1 : 0;

        $this->Brand_model->update(
          array(
            "id" => $id
          ),
          array(
            "isActive" =>$isActive
          )
        );
      }
    }
  public function rankSet()
  {
      $data=$this->input->post("data");
      parse_str($data,$order);
      $items=$order["tr"];
      foreach ($items as $rank => $id) {
        $this->Brand_model->update(
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