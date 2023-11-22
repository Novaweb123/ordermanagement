<?php
 $data = json_decode(file_get_contents('php://input'), true);
 //print_r($data);
 $headers = apache_request_headers();
include ('common/common.php');
$logic->utilites->server_setting();

//print_r($data);

    $data['token'] =  $headers['Authorization'];

    if($data['type'] == "get_token"){
         $logic->tokens->get_token();

    }else{
        validations($logic,$data,$headers);
    }





function validations($logic,$data,$headers)
{

  
         $thisType = $data['type'];
         $REQUEST = $data;

         extract($REQUEST);

       // print_r($REQUEST);
     
          
       

        if ($thisType == "login")
        {
           $logic->App_Login($REQUEST);
        }
        else if ($thisType == "getDropDowns")
        {
           $logic->purchase->getDropDowns();
        }
        else if($thisType == "getItemDropDown")
        {
            $logic->purchase->getItemDropDown($REQUEST);
        }
        else if ($thisType == "getPurchaseInvoiceHeader")
        {
           $logic->purchase->getPurchaseInvoiceHeader($REQUEST);
        }

        else if ($thisType == "addPurchaseInvoiceHeader")
        {
           $logic->purchase->addPurchaseInvoiceHeader($REQUEST);
        }
        else if ($thisType == "EditPurchaseInvoiceHeader")
        {
           $logic->purchase->EditPurchaseInvoiceHeader($REQUEST);
        }

        else if ($thisType == "GetHeaderItem")
        {
           $logic->purchase->GetHeaderItem($REQUEST);
        }

        else if ($thisType == "AddPurchaseItem")
        {
           $logic->purchase->AddPurchaseItem($REQUEST);
        }

        else if ($thisType == "AddPurchaseInvoiceHeadereEmpty")
        {
           $logic->purchase->AddPurchaseInvoiceHeadereEmpty($REQUEST);
        }
        else if ($thisType == "viewPurchaseDetails")
        {
           $logic->purchase->viewPurchaseDetails($REQUEST);
        }

        else if ($thisType == "GetItemMasterDropDown")
        {
           $logic->purchase->GetItemMasterDropDown($REQUEST);
        }

        

        else if ($thisType == "getLocation")
        {
           $logic->purchase->getLocation($REQUEST);
        }

        else if ($thisType == "addLocation")
        {
           $logic->purchase->addLocation($REQUEST);
        }

        else if ($thisType == "getCurrency")
        {
           $logic->purchase->getCurrency($REQUEST);
        }

        else if ($thisType == "addCurrency")
        {
           $logic->purchase->addCurrency($REQUEST);
        }
        else if($thisType == "GetVendorDiscount"){
           //print_r($REQUEST);
         $logic->purchase->GetVendorDiscount($REQUEST);
        }
        else if($thisType == "SaveFinal"){
           $logic->purchase->SaveFinal($REQUEST);
        }

        else if($thisType == "SaveHeaderAsDraft"){
         $logic->app_edit_query_return_array("tbl_members",$REQUEST," idno=$id");  
        }

        else if($thisType == "getUsers"){
         $logic->purchase->getUsers($REQUEST);  
        }

        else if ($thisType == "createUser")
        {
            $logic->purchase->createUser($REQUEST);
        }
        else{
            $logic->Error_Msg('No Valid Type');
        }


        if(isset($uri)){

         if ($uri == "getDetails"){
      
            $logic->purchase->getDetails($REQUEST);
         }

         if($uri =="edit"){
            $logic->purchase->edits($REQUEST);
         }

         }

      

   

}


    function gentoken($logic,$token)
    {
        return $logic->tokens->valided_token($token);
    }

?>
