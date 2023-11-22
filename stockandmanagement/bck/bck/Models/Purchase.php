<?php
class Purchase{

      private $logic;


        public function __construct($logic) {
              $this->logic = $logic;
        }

        public function getPurchaseInvoiceHeader($REQUEST){
            extract($REQUEST);
            $statement = "Call GetPurchaseInvoiceHeader('$token','$billnumber','$ordernumber','$branch','$location','$fromDate','$toDate','$sortby','$sortkey','$page','$pagesize')";
           // echo $statement;
           
            $this->logic->p_list($REQUEST,$statement,'');
        }

        public function addPurchaseInvoiceHeader($REQUEST){
            extract($REQUEST);
            $tds_percentage = "0";
            $tds_amount = "0";
            $cgst_amount = "0";
            $sgst_amount = "0";
            $statement = "Call AddPurchaseInvoiceHeader('$token','$billnumber','$ordernumber','$billdate','$currency','$total_amount','$branch','$location','$payment_terms','$duedate','$vendor','$gst_treatment','$gst_number','$tds_percentage','$tds_amount','$cgst_amount','$sgst_amount','$source','$destination')";
            
            $this->logic->Add_query($statement);
        }

        public function createUser($REQUEST){
            extract($REQUEST);
            $statement = "Call create_user('$mobile','$email','$username','$password','$usertypeid','$name')";
           // echo $statement;
           // exit;
            $this->logic->Add_query($statement);
        }

        public function AddPurchaseItem($REQUEST){
            extract($REQUEST);
            //$statement = "Call AddPurchaseItem('$token','$Item_name','$MSKU','$Account_type','$Bill_number','$Quantity','$Price','$Discount','$GST_terms','$Amount','$id')";
            $statement = "Call AddPurchaseItem('$token','$itemid','$Item_name','$brandid','$product_categoryid','$Bill_number','$Account_type','$Quantity','$Price','$discount_pergentage','$Discount_amount','$net_price','$item_amount','$gst','$tax_amount','$Amount','$CGST','$SGST','$IGST','$id','$Manuf','$Expiry','$Batch')";
            //echo $statement;
           // exit;
            $status = array();
            $data = array();
            $stmt = $this->logic->dbh->prepare($statement);
            $stmt->execute();
            $status = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->nextRowset();
            $row['status'] = $status[0];
            $row['data'] = [];
            $this->logic->utilites->Final_Output($row);
        }

        public function GetHeaderItem($REQUEST){
            extract($REQUEST);
            $statement = "Call GetHeaderItem('$token','$bill_number')";
            $status = array();
            $data = array();
            $stmt = $this->logic->dbh->prepare($statement);
            $stmt->execute();
            $status = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->nextRowset();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->nextRowset();
            $row;
            $row['status'] = $status[0];
            $row['data'] = $data;
            $this->logic->utilites->Final_Output($row);
            
        }

        public function EditPurchaseInvoiceHeader($REQUEST){
            extract($REQUEST);
            $tds_percentage = "0";
            $tds_amount = "0";
            $cgst_amount = "0";
            $sgst_amount = "0";
            $statement = "Call EditPurchaseInvoiceHeader('$token','$billnumber','$ordernumber','$billdate','$currency','$total_amount','$branch','$location','$payment_terms','$duedate','$vendor','$gst_treatment','$gst_number','$tds_percentage','$tds_amount','$cgst_amount','$sgst_amount','$source','$destination','$id')";
           // echo $statement;
            
            $status = array();
            $data = array();
            $stmt = $this->logic->dbh->prepare($statement);
            $stmt->execute();
            $status = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->nextRowset();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
       //     print_r($status);
        //    print_r($data);
            
            $query = "";
            if($status[0]['taxtype'] == "IGST"){

                for($i=0;$i<count($data);$i++){

                 // $query .=  'UPDATE Purchase_invoice_line_items SET CGST = 0,SGST =0,IGST ='.$data[$i]['tax_amount'].' WHERE idno = '.$data[$i]['idno'].';';
                 $query .=  'UPDATE Purchase_invoice_line_items SET CGST = 0 ,SGST =0,IGST ='.$data[$i]['tax_amount'].' WHERE idno = '.$data[$i]['idno'].';';
                 
                }
              

            }else{

                for($i=0;$i<count($data);$i++){

                    $query .=  'UPDATE Purchase_invoice_line_items SET CGST = '.($data[$i]['tax_amount']/2).',SGST ='.($data[$i]['tax_amount']/2).',IGST =0 WHERE idno = '.$data[$i]['idno'].';';
  
                }
       
                
            }
            //echo $query;
            if($query != ""){
            $stmt = $this->logic->dbh->prepare($query);
            $stmt->execute();
            $stmt->closeCursor();
            }
            $row['status'] = $status[0];
            $this->logic->utilites->Final_Output($row);
        }

        public function SaveFinal($REQUEST){
            extract($REQUEST);
            $statement = "Call sp_PurchaseInvoice_save('$token','$id')";
            $this->logic->Add_query($statement);
        }

        public function AddPurchaseInvoiceHeadereEmpty($REQUEST){
            extract($REQUEST);
            $statement = "Call AddPurchaseInvoiceHeadereEmpty('$token')";
            $status = array();
            $data = array();
            $stmt = $this->logic->dbh->prepare($statement);
            $stmt->execute();
            $status = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->nextRowset();
            $row['status'] = $status[0];
            $row['data'] = [];
            $this->logic->utilites->Final_Output($row);
        }


        public function GetItemMasterDropDown($REQUEST){
            extract($REQUEST);
            $statement = "Call GetItemMasterDropDown('$brandid','$item')";
           // echo $statement;
            $status = array();
            $data = array();
            $stmt = $this->logic->dbh->prepare($statement);
            $stmt->execute();
            $status = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->nextRowset();
            $row['status'] = $status[0];
            
            $row['data'] = $stmt->fetchAll(PDO::FETCH_ASSOC);;
            $this->logic->utilites->Final_Output($row);
        }

        public function viewPurchaseDetails($REQUEST){
            extract($REQUEST);
            $statement = "Call GetPurchaseBillDetails('$token','$billnumber')";
            $status = array();
            $data = array();
            $stmt = $this->logic->dbh->prepare($statement);
            $stmt->execute();
            $status = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->nextRowset();
            if($status[0]['result'] == 200){
            $data['purchaseHeaderDetails'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->nextRowset();
            $data['purchaseItems'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->nextRowset();
            }

            $row;
            $row['status'] = $status[0];
            $row['data'] = $data;
            $this->logic->utilites->Final_Output($row);

        }


        public function getLocation($REQUEST){
            extract($REQUEST);
            $statement = "Call GetLocation('$token','$location','$address1','$address2','$state','$country','$sortby','$sortkey','$page','$pagesize')";
            $this->logic->p_list($REQUEST,$statement,'');
        }


        public function addLocation($REQUEST){
            extract($REQUEST);
            $statement = "Call AddLocation('$token','$locationname','$address1','$address2','$street','$city','$district','$state','$country')";
            $this->logic->Add_query($statement);
        }

        public function addCurrency($REQUEST){
            extract($REQUEST);
            $statement = "Call AddCurrency('$token','$Currency','$Country')";
            $this->logic->Add_query($statement);
        }

        public function getCurrency($REQUEST){
            extract($REQUEST);
            $statement = "Call GetCurrency('$token','$sortby','$sortkey','$page','$pagesize')";
            $this->logic->p_list($REQUEST,$statement,'');
        }
        

        public function getItemDropDown($REQUEST){

            extract($REQUEST);
          //  $statement = "Call getItemDropDown('$brand')";
          $statement = "Call fetchItemData()";
            $status = array();
            $data = array();
            $stmt = $this->logic->dbh->prepare($statement);
            $stmt->execute();
            $status = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->nextRowset();
            
            if($status[0]['result'] == 200){
            $data['items'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->nextRowset();
            }

            $row;
            $row['status'] = $status[0];
            $row['data'] = $data;
            $this->logic->utilites->Final_Output($row);

    }


    public function getDetails($REQUEST){
        extract($REQUEST);
        $statement = "call getDetails('$type','$id','')";
        //echo $statement;
        $data = $this->logic->fetch_data($statement);
        $response;
        if(count($data) >=1){
            $response['status'] = 200;
            $this->logic->utilites->Add_Edit_MSG($response,"Sucessfully",$data);
        
        }else{
            $response['status'] = 403;
            $this->logic->utilites->Add_Edit_MSG($response,"Some thing went wrong");
        }
        
        

    }


        public function getDropDowns(){

                  $statement = "Call getDropDown()";
                  $status = array();
			$data = array();
			$stmt = $this->logic->dbh->prepare($statement);
			$stmt->execute();
			$status = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$stmt->nextRowset();
			if($status[0]['result'] == 200){
			$data['locations'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$stmt->nextRowset();
            $data['suppliers'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$stmt->nextRowset();
                  $data['currency'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$stmt->nextRowset();
           
            $data['brands'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$stmt->nextRowset();
            $data['product_category'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$stmt->nextRowset();
            $data['state_codes'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$stmt->nextRowset();
            $data['account_types'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$stmt->nextRowset();

            $data['item_master'] = [];
			//$stmt->nextRowset();
                  }

			$row;
			$row['status'] = $status[0];
			$row['data'] = $data;
                  $this->logic->utilites->Final_Output($row);

        }



        public function GetVendorDiscount($REQUEST){
            extract($REQUEST);
            $statement = "Call GetVendorDiscount('$token','$vendorid')";
            $status = array();
      $data = array();
      $stmt = $this->logic->dbh->prepare($statement);
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
            $this->logic->utilites->Final_Output($row);

      }


      public function getUsers($REQUEST){
        extract($REQUEST);
        $query = "select * from  tblusers where utypeid != 100";
        $subquery = "";
        $page_query = "";

        if($username != ""){
            $subquery .= " and username like '%$username%'";
        }
        
      
        if($email != ""){
            $subquery .= " and email = $email";
        }

        if($fromdate != "" && $todate != ""){
            $subquery .= " and  createdon BETWEEN '".$fromdate."' and '".$todate."'";
        }

        

        $page_query .= " order by idno desc LIMIT " . $perpage . $this->logic->ofset_stg . ($page -1)* $perpage ;

        $query = $query.$subquery.$page_query;
       // echo $query;

        $this->logic->fetch_data($query);
        
        $page_query = "select count(idno) as page_count from tblusers where  utypeid != 100 ".$subquery;
       
        $this->logic->Output_page($this->logic->fetch_data($query) , $this->logic->utilites->get_page_custom_count($page_query) , $page, $perpage, []);

    }


  public function edits($REQUEST){

    extract($REQUEST);
 
    if($type == "editUser"){
       $idno = $REQUEST['id']; 
        unset($REQUEST['id']);

        $this->logic->app_edit_query_return_array("tblusers",$REQUEST," idno=$idno");
    }

 

}
        
      

        





}

?>