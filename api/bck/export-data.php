<?php
include ('common/common.php');
            $conn = new mysqli($hostname, $username,$password);
           mysqli_select_db($conn, $db_name);
           $query = "select i.*,p.*,(select statecode from tbl_state_codes where idno=p.Source_of_supply) as Source_of_supply,
           (select sum(item_amount) from Purchase_invoice_line_items where Bill_number = i.Bill_number) as sub_total, 
           (select sum(tax_amount) from Purchase_invoice_line_items where Bill_number = i.Bill_number) as total_tax_amount,
           (select statecode from tbl_state_codes where idno=p.Source_of_destination) as Source_of_destination,v.Supplier_Name,c.Currency,it.Seller_SKU,l.LocationName from Purchase_invoice_line_items i left join  Purchase_invoice_header p on p.idno = i.Bill_number left join suppliers v on v.idno = p.Vendor_name left join tbl_currency c on c.CurrencyId = p.Currency left join Item_master_Sheet it on it.idno = i.itemid left JOIN tbl_location l on l.LocationId = p.`Location` where p.Statusbit = 2 and i.itemid != ''";
           if($_GET["type"] == "single"){
            $query .= " and p.idno = ".$_GET['id'];
           }

           if($_GET["type"] == "date"){
            $query .= " and  p.Bill_date BETWEEN '".$_GET['fromdate']."' and '".$_GET['todate']."'";
           }
           $setRec = mysqli_query($conn, $query);
           $columnHeader = "Bill Date" . "\t" .
           "Bill Number" . "\t" .
           "PurchaseOrder" . "\t" . 
           "Bill Status" . "\t" . 
           "Source of Supply" . "\t" . 
           "Destination of Supply". "\t" . 
           "GST Treatment" ."\t". 
           "GST Identification Number (GSTIN)"."\t". 
           "Is Inclusive Tax" ."\t". 
           "TDS Percentage"."\t". 
           "TDS Amount"."\t". 
           "TDS Section Code"."\t". 
           "TDS Name"."\t". 
           "Vendor Name" ."\t". 
           "Due Date"  ."\t". 
           "Currency Code"."\t". 
           "Exchange Rate" ."\t". 
           "Attachment ID"."\t". 
           "Attachment Preview ID"."\t".
           "Attachment Name"."\t".
           "Attachment Type"."\t".
           "Attachment Size"."\t".
           "Item Name"."\t".
           "SKU"."\t".
           "Item Description"."\t".
           "Account"."\t".
           "Usage unit"."\t".
           "Quantity"."\t".
           "Rate"."\t".
           "Item Total"."\t".
           "Tax Name"."\t".
           "Tax Type"."\t".
           "Tax Percentage"."\t".
           "Tax Amount"."\t".
           "Item Exemption Code"."\t".
           "Reverse Charge Tax Name"."\t".
           "Reverse Charge Tax Rate"."\t".
           "Reverse Charge Tax Type"."\t".
           "SubTotal"."\t".
           "Total"."\t".
           "Balance"."\t".
           "Vendor Notes"."\t".
           "Terms & Conditions"."\t".
           "Payment Terms"."\t".
           "Payment Terms Label"."\t".
           "Is Billable"."\t".
           "Customer Name"."\t".
           "Branch" ;
           $setData = '';
           $row = $logic->fetch_data($query);

            $rowData = '';
            $tab =  "\t";
            
            for($i=0;$i<count($row);$i++){

              

                extract($row[$i]);
                $total_amount = (float)$sub_total + (float) $total_tax_amount;      
                $setData .= trim(
                $Bill_date . "\t" .
                $Bill_number . "\t" .
                "" . "\t" . 
                "Overdue" . "\t" . 
                $Source_of_supply . "\t" . 
                $Source_of_destination. "\t" . 
                $GST_treatment ."\t". 
                $GST_number."\t". 
                "FALSE" ."\t". //Is Inclusive Tax
                $TDS_percentage."\t". //TDS Percentage
                $TDS_amount."\t".  //TDS Amount
                ""."\t".  //TDS Section Code
                ""."\t". //TDS Name
                $Supplier_Name ."\t". //Vendor Name
                $Due_date  ."\t". 
                $Currency."\t". 
                "0" ."\t". //Exchange Rate
                ""."\t". //Attachment ID"
                ""."\t".//"Attachment Preview ID"
                ""."\t".//"Attachment Name"
                ""."\t".//Attachment Type
                ""."\t".//Attachment Size
                $Item_name."\t".
                $Seller_SKU."\t".
                ""."\t".//"Item Description"
                "Inventory Asset"."\t".//"Account"
                ""."\t".//"Usage unit"
                $Quantity."\t".
                $net_price."\t".//Rate
                $item_amount."\t".
                "GST".$gst."\t".
                "Tax Group"."\t".
                $gst."\t".
                $tax_amount."\t".
                ""."\t".//"Item Exemption Code"
                ""."\t".//"Reverse Charge Tax Name"
                ""."\t".//
                ""."\t".//"Reverse Charge Tax Type"
                $sub_total."\t".//"SubTotal"
                $total_amount."\t".// Total Amount
                ""."\t".//"Balance"
                ""."\t".//"Vendor Notes"
                ""."\t".//"Terms & Conditions"
                ""."\t".//"Payment Terms"
                "Due on Receipt"."\t".//"Payment Terms Label"
                "False"."\t".//"Is Billable"
                ""."\t".//"Customer Name"
                $LocationName ) . "\n";
           }
           



           header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
           header("Content-type: text/x-csv");
           header("Content-type: text/csv");
           header("Content-type: application/csv");
           header("Content-type: application/octet-stream");
           header("Content-Disposition: attachment; filename=Transaction.xls");
           header("Pragma: no-cache");
           header("Expires: 0");



             echo ucwords($columnHeader) . "\n" . $setData . "\n";

?>


