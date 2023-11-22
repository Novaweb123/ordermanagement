<?php

class Utilities{

    public $sucessful_stg = "sucessfully added data";
    public $sucess_edit_stg = "sucessfully updated";
    public $went_wrong_stg = "Something went wrong";
    public $data_already_stg = "Data already present";
    private $logic;

    public function __construct($logic) {
        $this->logic = $logic;
    }


    public function server_setting(){

        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS');

        if (isset($_SERVER['HTTP_ORIGIN']))
        {
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 86400'); // cache for 1 day

        }

        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS')
        {

            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
                    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
            }

            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])){
             header("Access-Control-Allow-Headers:{$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
            }

            exit(0);
        }

    }


    public function get_currenct_date(){
        return date('Y-m-d H:i:s');
    }

   

    public function date_converstion($input){

        return date_format(date_create($input),"Y-m-d H:i:s");

    }

    public function json_print($response){

        header("Content-Type:application/json");
        return json_encode($response);

    }

    public function unset_request($REQUEST){
        unset($REQUEST['token']);
        unset($REQUEST['type']);
        unset($REQUEST['submit']);
        unset($REQUEST['change_name']);
        unset($REQUEST['page_limit']);
        unset($REQUEST['current_page']);
        unset($REQUEST['page_num']);
        unset($REQUEST['uri']);
        unset($REQUEST['s']);
        unset($REQUEST['0']);
        unset($REQUEST['']);
        unset($REQUEST['-']);
        return $REQUEST;
    }

    public function get_page_custom_count($custom_query){
        $count_arr = $this->logic->fetch_data($custom_query);
        return $count_arr[0]['page_count'];
    }

    public function request_remodify($REQUEST){

        extract($REQUEST);
       if (!isset($page_limit))
       {
           $page_limit = 10;
       }

       $current_page = 1;
       if (isset($page_num))
       {
           $current_page = $page_num;
           $page_num = $page_num - 1;
           $page_num = $page_num * $page_limit;
       }
       else
       {
           $page_num = 0;

       }

       $REQUEST['page_limit'] = $page_limit;
       $REQUEST['current_page'] = $current_page;
       $REQUEST['page_num']  = $page_num;

       return $REQUEST;

   }

    public function Success_Msg($Msg){

        $response = array();
        $response['status'] = 200;
        $response['message'] = $Msg;
        echo $this->json_print($response);
    }

    public function Fail_Msg($Msg){
        $response = array();
        $response['status'] = 403;
        $response['message'] = $Msg;
        echo $this->json_print($response);
    }

    public function Add_Edit_MSG($response,$msg="",$data=[]){
        $status['result'] = $response['status'];
        $status['msg'] = $msg;
        if(isset($response['db_message'])){
            $status['errormsg'] = $response['db_message'];
        }
        if(isset($response['id'])){
            $status['id'] = $response['id'];
        }
        $row;
        $row['status'] = $status;
        $row['data'] = $data;
        $json_response = json_encode($row);
        echo $json_response;
    }

    public function Final_Output($response){

        $status = "";

        if(isset($response['status'])){
            $status = $response['status'];
        }
        if(isset($response['result'])){
            $status = $response['result'];
        }

        if ($status == 200 || $status == 200)
        {
            $response_arr = array();
            $response_arr['status'] = 200;
            $response_arr['message'] = 'sucess';
            $response_arr['results'] = $response[0];
            $json_response = json_encode($response_arr);
            echo $json_response;
        }
        else{
            $json_response = json_encode($response);
            echo $json_response;
        }

    }



}

?>