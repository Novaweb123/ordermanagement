<?php


include('Purchase.php');
include('Utilities.php');


class UserLogic {


    public $dbh;
    public $enc;
    public $purchase;
    public $tokens;
    public $utilites;
    public $id_stg = "idno = ";
    public $duplicatie_stg = "Duplicate entry";
    public $mobile_stg = "This mobile number is already in use";
    public $ofset_stg = " OFFSET ";

    public function __construct($dhh,$enc) {
        $this->dbh = $dhh;
        $this->enc = $enc;
        $this->purchase = new Purchase($this);
        $this->utilites = new Utilities($this);
        
    }

    public function App_Login($REQUEST){

        extract($REQUEST);
        

        $statement = "Call user_loginvalidation('$uname','$password')";
        //$this->E_query($statement);

        $status = array();
        $data = array();
        $options = array();
        $stmt = $this->dbh->prepare($statement);
        $stmt->execute();
        $status = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->nextRowset();
        if($status[0]['result'] == 200){
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->nextRowset();
       // $options = $stmt->fetchAll(PDO::FETCH_ASSOC);
        //$stmt->nextRowset();
        
        }
        
        $row;
        $row['status'] = $status[0];
        $row['data'] = $data;
        
        $this->utilites->Final_Output($row);

    }


    public function app_insert_query($table_name,$REQUEST){



            $REQUEST = $this->utilites->unset_request($REQUEST);
            $keys = array();
            $values = array();
            foreach ($REQUEST as $key => $value)
            {
                $value = trim($value);
                $keys[] = "`{$key}`";
                $values[] = "'{$value}'";
            }

            $statement = "INSERT INTO ".$table_name." (" . implode(",", $keys) . ") VALUES (" . implode(",", $values) . ");";
            $stmt = $this->dbh->prepare($statement);
            $response = array();
            try {

            $stmt->execute();
            $last_inserted = $this->dbh->lastInsertId();

            $response['status'] = 200;
            $response['message'] = $this->utilites->sucessful_stg;
            $response['id'] =  $last_inserted;


            }
            catch(PDOException $e)
            {
                $response['status'] = 403;
                $response['message'] = $this->utilites->data_already_stg;
                $response['id'] =  0;


            }

            echo $this->utilites->json_print($response);

    }

    public function app_insert_query_return_array($table_name,$REQUEST){

            $REQUEST = $this->utilites->unset_request($REQUEST);
            $keys = array();
            $values = array();
            $response = array();
            foreach ($REQUEST as $key => $value)
            {
                $value = trim($value);
                $keys[] = "`{$key}`";
                $values[] = "'{$value}'";
            }

            $statement = "INSERT INTO ".$table_name." (" . implode(",", $keys) . ") VALUES (" . implode(",", $values) . ");";
            $stmt = $this->dbh->prepare($statement);
            try {
            $stmt->execute();
            $last_inserted = $this->dbh->lastInsertId();
            $response['status'] = 200;
            $response['message'] = $this->utilites->sucessful_stg;
            $response['id'] =  $last_inserted;
            return $response;
            }
            catch(PDOException $e)
            {
                $response['status'] = 403;
                $response['db_message'] =$e->getMessage();
                $response['message'] = $this->utilites->data_already_stg;
                $response['id'] =  0;
                return $response;

            }

    }

    public function app_edit_query_return_array($table_name,$REQUEST,$edit_query,$msg = "Sucessfully Updated"){

        $REQUEST = $this->utilites->unset_request($REQUEST);


         $edit_val = "";
         foreach ($REQUEST as $key => $value)
         {
             $value = trim($value);

             $edit_val = $edit_val .$key. '='."'".$value."',";


         }
         $edit_val = rtrim($edit_val, ',');

         $statement = "UPDATE ".$table_name." SET " . $edit_val . " WHERE ".$edit_query;
       //  echo $statement;
         $stmt = $this->dbh->prepare($statement);
         try {
             $stmt->execute();
             $response['status'] = 200;
             $this->utilites->Add_Edit_MSG($response,$msg);
             }
             catch(PDOException $e)
             {
                 $response['status'] = 403;
                 $response['db_message'] =$e->getMessage();
                 $this->utilites->Add_Edit_MSG($response,$this->utilites->data_already_stg);
             }

 }



    public function app_edit_query($table_name,$REQUEST,$edit_query){

           $REQUEST = $this->utilites->unset_request($REQUEST);

           $edit_val = "";
            foreach ($REQUEST as $key => $value)
            {
                $value = trim($value);

                $edit_val = $edit_val .$key. '='."'".$value."',";


            }
            $edit_val = rtrim($edit_val, ',');

            $statement = "UPDATE ".$table_name." SET " . $edit_val . " WHERE ".$edit_query;

            $stmt = $this->dbh->prepare($statement);
            try{
            $stmt->execute();
            $response = array();
            $response['status'] = 200;
            $response['message'] = $this->utilites->sucess_edit_stg;
            echo $this->utilites->json_print($response);
            }
            catch(PDOException $e)
            {
                $this->Error_Msg("Forbid");
            }


    }

    public function app_delete_query_array($table_name,$edit_query){

        $statement = "DELETE from ".$table_name." WHERE ".$edit_query;
        $stmt = $this->dbh->prepare($statement);
        try{
        $stmt->execute();
        $response = array();
        $response['status'] = 200;
        $response['message'] = $this->utilites->sucess_edit_stg;
        return $response;
        }
        catch(PDOException $e)
        {

        $response['status'] = 403;
        $response['db_message'] =$e->getMessage();
        $response['message'] = $this->utilites->went_wrong_stg;
        return $response;
        }

    }

    public function app_delete_query($table_name,$edit_query,$REQUEST){

            unset($REQUEST['token']);
            unset($REQUEST['type']);
            $statement = "DELETE from ".$table_name." WHERE ".$edit_query;
            $stmt = $this->dbh->prepare($statement);
            $stmt->execute();
            $response = array();
            $response['status'] = 200;
            $response['message'] = "deleted updated";
            echo $this->utilites->json_print($response);

    }

    public function custom_query($statement){


            $stmt = $this->dbh->prepare($statement);
                try{
                $stmt->execute();
                $response = array();
                $response['status'] = 200;
                $response['message'] =  $this->utilites->sucess_edit_stg;
                return $response;
            }
            catch(PDOException $e){
                $response['status'] = 403;
                $response['message'] = $this->utilites->went_wrong_stg;
                return $response;
            }

    }

    public function insert_query($statement){

        $stmt = $this->dbh->prepare($statement);
        try {
        $stmt->execute();
        $last_inserted = $this->dbh->lastInsertId();
        $response['status'] = 200;
        $response['message'] = $this->utilites->sucessful_stg;
        $response['id'] =  $last_inserted;
        return $response;
        }
        catch(PDOException $e)
        {
        $response['status'] = 403;
        $response['message'] = $this->utilites->data_already_stg;
        $response['id'] =  0;
        return $response;
        }

    }

    public function fetch_data($statement){
            $stmt = $this->dbh->prepare($statement);
            $stmt->execute();
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            return $row;
    }

    public function Error_Msg($error){
        $response = array();
        $response['status'] = 403;
        $response['message'] = $error;
        $response['error'] = "forbidden";
        echo $this->utilites->json_print($response);
    }

    public function Error_Msg_Status($error,$status){
        $response = array();
        $response['status'] = $status;
        $response['message'] = $error;
        $response['error'] = "forbidden";
        echo $this->utilites->json_print($response);
    }

    public function Error_Msg_Status_arr($error,$status){
        $response = array();
        $response['status'] = $status;
        $response['message'] = $error;
        $response['error'] = "forbidden";
        return $response;
    }

    public function Sucess_Msg($msg){
        $response = array();
        $response['status'] = 200;
        $response['message'] = $msg;
        $response['error'] = "forbidden";
        echo $this->utilites->json_print($response);
    }


    public function fetch_Final_Output($response){

            if (!empty($response))
            {

                $response_arr = array();
                $response_arr['status'] = 200;
                $response_arr['message'] = 'sucess';
                $response_arr['results'] = $response;
                $json_response = json_encode($response_arr);
                echo $json_response;

            }
            else
            {
                $response = array();
                $response['status'] = 403;
                $response['message'] = 'sorry no data found';
                $response['error'] = "forbidden";

                echo $this->utilites->json_print($response);
            }
    }

    public function Output_page($response, $count, $current_page, $page_limit, $custom_array){

             if (!empty($response)) // cheking data is ther or not
            {

                $response_arr = array();
                $response_arr['status'] = 200;
                $response_arr['message'] = 'sucess';
                if (round($count / $page_limit) == 0) // if count and page limit divided = 0 then keep totalpage = 1
                {
                    $response_arr['total_pages'] = 1;
                }
                else  // else show total page count
                {
                    $response_arr['total_pages'] = ceil($count / $page_limit);
                }
                $response_arr['current_page'] = $current_page;
                $response_arr['total_results'] = $count;
                $response_arr['results'] = $response;
                if (isset($custom_array['type'])) // check type its customer or rfids or status
                {

                    if ($custom_array['type'] == "customers") // if type = customer then show customer count
                    {
                        $response_arr['total_customers'] = $custom_array['customer_count'];
                    }
                    if ($custom_array['type'] == "get_rfids") // if type = rfids then show rfid count
                    {
                                    $response_arr['rfid_counts'] = $custom_array['rfid_counts'];
                    }
                    if ($custom_array['type'] == "stats_counts") // if type = stats_counts then show stats
                    {
                        $response_arr['top_stats'] = $custom_array['top_stats'];
                        $response_arr['reset_time'] = $custom_array['reset_time'];
                    }

                }

                $json_response = json_encode($response_arr);
                echo $json_response;
            }
            else
            {
                $response = array();
                $response['status'] = 403;
                $response['message'] = 'sorry no data found';
                $response['error'] = "forbidden";
                $response['results'] = [];
                $response['total_pages'] = 0;
                $response['current_page'] = 1;
                $response['total_results'] = 0;
                if (isset($custom_array['type']))
                {

                    if ($custom_array['type'] == "customers") // if customer show customer counts
                    {
                        $response_arr['total_customers'] = $custom_array['customer_count'];
                    }
                    if ($custom_array['type'] == "stats_counts") // if stats_counts show stats counts
                    {
                        $response['top_stats'] = $custom_array['top_stats'];
                        $response['reset_time'] = $custom_array['reset_time'];
                    }

                }
                echo $this->utilites->json_print($response);
            }
    }

    public function Add_query($statement){
        //echo $statement;
           $status = array();
           $data = array();
           $stmt = $this->dbh->prepare($statement);
           $stmt->execute();
           $status = $stmt->fetchAll(PDO::FETCH_ASSOC);
           $stmt->nextRowset();
           $row;
           $row['status'] = $status[0];
           
       

       $this->utilites->Final_Output($row);
   }

    public function E_query($statement){
         //echo $statement;
			$status = array();
			$data = array();
			$stmt = $this->dbh->prepare($statement);
			$stmt->execute();
			$status = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$stmt->nextRowset();
			if($status[0]['result'] == 200){
			$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$stmt->nextRowset();


            }
			
			$row;
			$row['status'] = $status[0];
			$row['data'] = $data;
		

        $this->utilites->Final_Output($row);
    }

    public function p_list($params,$statement,$custom){



            $row = array();
			$listData = array();
            $custom_obj = array();
			$stmt = $this->dbh->prepare($statement);
			$stmt->execute();
			$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$stmt->nextRowset();
			$listData = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$stmt->nextRowset();
            if($custom != ''){
                $custom_obj = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $stmt->nextRowset();
            }
            
			

        $totalcount = $row[0]['count'];
        $response = array();
        $status = array();
        $final_results = array();
        if($totalcount == -1 || $totalcount == -2 || $totalcount == 0){
         $status['result'] = 200;
         $status['msg'] = "No Data Found";
         $final_results["totalRecords"] = 0;
         $final_results["totalPages"] = 0;
         $final_results["currentPage"] = 0;
         $final_results["listData"] = [];
         $final_results["customdata"]= $custom_obj;
         $response['status'] = $status;
         $response['data'] = $final_results;
         
        }


        if($totalcount > 0){
          $status['result'] = 200;
          $status['msg'] = "sucess";
          $final_results["totalRecords"] = 10;
          $final_results["totalPages"] = ceil($totalcount/$params['perpage']);
          $final_results["currentPage"] = $params['page'];
          $final_results["listData"] = $listData;
          $final_results["customdata"]= $custom_obj;
          $response['status'] = $status;
          $response['data'] = $final_results;
        }

        echo $this->utilites->json_print($response);

    }





}

?>
