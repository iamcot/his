<?php
/**
 * @package care_api
 */
/**
 */
require_once($root_path . 'include/care_api_classes/class_core.php');
/**
 *  Product methods.
 *
 * Note this class should be instantiated only after a "$db" adodb  connector object  has been established by an adodb instance
 * @author Elpidio Latorilla
 * @version beta 2.0.1
 * @copyright 2002,2003,2004,2005,2005 Elpidio Latorilla
 * @package care_api
 */
class Product extends Core
{
    /**#@+
     * @access private
     * @var string
     */
    /**
     * Table name for pharmay order lists
     */
    var $tb_polist = 'care_pharma_orderlist';
    /**
     * Table name for pharmay order lists sub table
     */
    var $tb_polist_sub = 'care_pharma_orderlist_sub';
    /**
     * Table name for pharmacy order catalog
     */
    var $tb_pocat = 'care_pharma_ordercatalog';
    /**
     * Table name for pharmacy main products
     */
    var $tb_pmain = 'care_pharma_products_main';
    /**
     * Table name for medical depot order lists
     */
    var $tb_molist = 'care_med_orderlist';
    /**
     * Table name for medical depot order catalog
     */
    var $tb_mocat = 'care_med_ordercatalog';
    /**
     * Table name for medical depot main products
     */
    var $tb_mmain = 'care_med_products_main';
    /**
     * Table name for medical depot main products administration
     */
    var $tb_mmain_sub = 'care_med_products_main_sub';
    /**
     * Table name form pharmacy product administration
     */
    var $tb_pmain_sub = 'care_pharma_products_main_sub';
    /**
     * Table name of the encounter prescription
     */
    var $tb_prescription = 'care_encounter_prescription';
    /**
     * Table name of the encounter prescription sub
     */
    var $tb_prescription_sub = 'care_encounter_prescription_sub';
    /**#@-*/

    /**
     * Field names of care_pharma_ordercatalog or care_med_ordercatalog tables
     * @var array
     */
    var $fld_ocat = array('item_no',
        'dept_nr',
        'hit',
        'artikelname',
        'bestellnum',
        'minorder',
        'maxorder',
        'proorder',
        'supplier_nr',
        'sasi',
        'price',
        'vlere',
        'expiry_date',
        'dose',
        'packing');
    /**
     * Field names of care_pharma_products_main or care_med_products_main tables
     * @var array
     */
    var $fld_prodmain = array('bestellnum',
        'description',
        'dose',
        'history',
        'modify_id',
        'modify_time',
        'create_id',
        'create_time',
        'product_name',
        'pharma_generic_drug_id',
        'content',
        'component',
        'using_type',
        'type_of_medicine',
        'unit_of_medicine',
        'caution',
        'care_supplier',
        'product_encoder',
        'note',
        'in_use',
        'price',
        'unit_of_price',
        'available_number',
        'pharma_type',
        'effects',
        'allergy',
        'hangsx',
        'nuocsx',
        'allocation_temp');
    /**
     * Field names of care_med_products_main_sub
     * @var array
     */
    var $fld_prodmain_sub = array('id',
        'pcs',
        'expiry_date',
        'price',
        'bestellnum',
        'idcare_supply',
        'create_time');

    /**
     * Field names of care_pharma_products_main_sub
     * @var array
     */
    var $fld_pharmamain_sub = array('id',
        'pcs',
        'expiry_date',
        'price',
        'bestellnum',
        'idcare_pharma',
        'create_time');

    /**
     * Field names of care_encounter_prescription table
     * @var int
     */
    var $fld_presc_sub = array('nr',
        'prescription_nr',
        'prescription_type_nr',
        'bestellnum',
        'article',
        'drug_class',
        'dosage',
        'admin_time',
        'quantity',
        'application_type_nr',
        'sub_speed',
        'notes_sub',
        'color_marker',
        'is_stopped',
        'stop_date',
        'status',
        'companion');

    /**
     * Constructor
     */
    function Product()
    {
    }

    /**
     * Sets the core object to point  to either care_pharma_orderlist or care_med_orderlist table and field names.
     *
     * The table is determined by the parameter content.
     * @access public
     * @param string Determines the final table name
     * @return boolean.
     */
    function useOrderList($type)
    {
        if ($type == 'pharma') {
            $this->coretable = $this->tb_polist;
        } elseif ($type == 'medlager') {
            $this->coretable = $this->tb_molist;
        } else {
            return false;
        }
    }

    /**
     * Sets the core object to point  to either care_pharma_ordercatalog or care_med_ordercatalog table and field names.
     *
     * The table is determined by the parameter content.
     * @access public
     * @param string Determines the final table name
     * @return boolean.
     */
    function useOrderCatalog($type)
    {
        if ($type == 'pharma') {
            $this->coretable = $this->tb_pocat;
            $this->ref_array = $this->fld_ocat;
        } elseif ($type == 'medlager' or $type == 'supply') {
            $this->coretable = $this->tb_mocat;
            $this->ref_array = $this->fld_ocat;
        } else {
            return false;
        }
    }

    /**
     * Sets the core object to point  to either care_pharma_products_main or care_med_products_main table and field names.
     *
     * The table is determined by the parameter content.
     * @access public
     * @param string Determines the final table name
     * @return boolean.
     */
    function useProduct($type)
    {
        if ($type == 'pharma') {
            $this->coretable = $this->tb_pmain;
            $this->ref_array = $this->fld_prodmain;
        } elseif ($type == 'medlager' or $type = 'supply') {
            $this->coretable = $this->tb_mmain;
            $this->ref_array = $this->fld_prodmain;
        } else {
            return false;
        }
    }

    /**
     * Deletes an order.
     * @access public
     * @param int Order number
     * @param string Determines the final table name
     * @return boolean.
     */
    function DeleteOrder($order_nr, $type)
    {
        $this->useOrderList($type);
        $this->sql = "DELETE  FROM $this->coretable WHERE order_nr='$order_nr'";
        return $this->Transact();
    }

    /**
     * Deletes an order by a supplier.
     * @access public
     * @param int Order number
     * @param string Determines the final table name
     * @return boolean.
     */
    function DeleteOrderSupplier($idcare_supply, $type)
    {
        //$this->useOrderList($type);
        $this->sql = "DELETE FROM care_supply WHERE idcare_supply='$idcare_supply'";
        return $this->Transact();
    }

    /**
     * Returns the actual order catalog of a department.
     *
     * The returned adodb record object contains rows of arrays.
     * Each array contains catalog  data with  index keys as outlined in the <var>$fld_ocat</var> array.
     * @access public
     * @param int Department number
     * @param string Determines the final table name
     * @return mixed adodb record object or boolean
     */
    function ActualOrderCatalog($dept_nr, $type = '')
    {
        global $db;
        if (empty($type) || empty($dept_nr)) return false;
        $this->useOrderCatalog($type);
        $this->sql = "SELECT * FROM $this->coretable WHERE dept_nr='$dept_nr' ORDER BY hit DESC";
        if ($this->res['aoc'] = $db->Execute($this->sql)) {
            if ($this->rec_count = $this->res['aoc']->RecordCount()) {
                return $this->res['aoc'];
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Returns the actual order catalog of a pharmacy.
     *
     * The returned adodb record object contains rows of arrays.
     * Each array contains catalog  data with  index keys as outlined in the <var>$fld_ocat</var> array.
     * @access public
     * @param int Department number
     * @param string Determines the final table name
     * @return mixed adodb record object or boolean
     */
    function ActualOrderCatalogPharma($type = '', $bestellnum, $pharma)
    {
        global $db;
        if (empty($type) || empty($bestellnum)) return false;
        $this->useOrderCatalog($type);
        $this->sql = "SELECT * FROM care_pharma_products_main_sub WHERE bestellnum='$bestellnum' AND pcs > 0  AND idcare_pharma = $pharma ORDER BY expiry_date ASC";
        if ($this->res['aoc'] = $db->Execute($this->sql)) {
            if ($this->rec_count = $this->res['aoc']->RecordCount()) {
                return $this->res['aoc'];
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Returns the actual pcs for the selected product.
     *
     * The returned adodb record object contains rows of arrays.
     * Each array contains catalog  data with  index keys as outlined in the <var>$fld_ocat</var> array.
     * @access public
     * @param int Department number
     * @param string Determines the final table name
     * @return mixed adodb record object or boolean
     */
    function ActualOrderCatalogProducts($type = '', $bestellnum)
    {
        global $db;
        if (empty($type) || empty($bestellnum)) return false;
        $this->useOrderCatalog($type);
        $this->sql = "SELECT * FROM care_med_products_main_sub WHERE bestellnum='$bestellnum' AND pcs > 0 ORDER BY  expiry_date ASC";
        if ($this->res['aoc'] = $db->Execute($this->sql)) {
            if ($this->rec_count = $this->res['aoc']->RecordCount()) {
                return $this->res['aoc'];
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Returns the actual order catalog of supplier.
     *
     * The returned adodb record object contains rows of arrays.
     * Each array contains catalog  data with  index keys as outlined in the <var>$fld_ocat</var> array.
     * @access public
     * @param int Department number
     * @param string Determines the final table name
     * @return mixed adodb record object or boolean
     */
    function ActualOrderCatalogSupply($supplier_nr, $type = '')
    {
        global $db;
        $this->sql = "SELECT DISTINCT * FROM care_med_ordercatalog WHERE supplier_nr='$supplier_nr' ORDER BY hit DESC";
        if ($this->res['aoc'] = $db->Execute($this->sql)) {
            if ($this->rec_count = $this->res['aoc']->RecordCount()) {
                return $this->res['aoc'];
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Saves (inserts)  an item in the order catalog.
     *
     * The data must be passed by reference with associative array.
     * Data must have the index keys as outlined in the <var>$fld_ocat</var> array.
     * @access public
     * @param array Data to save
     * @param string Determines the final table name
     * @return boolean
     */
    function SaveCatalogItem(&$data, $type)
    {
        if (empty($type)) return false;
        $this->useOrderCatalog($type);
        $this->data_array =& $data;
        return $this->insertDataFromInternalArray();
    }

    /**
     * Saves (inserts)  an item in the order catalog of the suplier.
     *
     * The data must be passed by reference with associative array.
     * Data must have the index keys as outlined in the <var>$fld_ocat</var> array.
     * @access public
     * @param array Data to save
     * @param string Determines the final table name
     * @return boolean
     */
    function SaveCatalogItemSupply(&$data, $type)
    {
        if (empty($type)) return false;
        $this->useOrderCatalog($type);
        $this->data_array =& $data;
        return $this->insertDataFromInternalArray();
    }

    /**
     * Deletes a catalog item based on its item number key.
     * @access public
     * @param int Item number
     * @param string Determines the final table name
     * @return boolean
     */
    function DeleteCatalogItem($item_nr, $type)
    {
        if (!$item_nr || !$type) return false;
        $this->useOrderCatalog($type);
        $this->sql = "DELETE FROM $this->coretable WHERE item_no='$item_nr'";
        return $this->Transact();
    }

    /**
     * Returns all orders of a department marked as draft or are still unsent.
     *
     * The returned adodb record object contains rows of arrays.
     * Each array contains order  data with the following index keys:
     * - order_nr = order's primary key number
     * - dept_nr = department number
     * - order_date = date of ordering
     * - order_time = time of ordering
     * - articles = ordered articles
     * - extra1 = extra notes
     * - extra2 = extra notes
     * - validator = validator's name
     * - ip_addr = IP address of the workstation that send the order
     * - priority = priority level
     * - status = record's status
     * - history = record's history
     * - modify_id = name of user
     * - modify_time = modify time stamp in yyyymmddhhMMss format
     * - create_id = name of use
     * - create_time = creation time stamp in yyyymmddhhMMss format
     * - sent_datetime = date and time sent in yyyy-mm-dd hh:MM:ss format
     * - process_datetime = date and time processed in yyyy-mm-dd hh:MM:ss format
     * @access public
     * @param int Department number
     * @param string Determines the final table name
     * @return mixed adodb record object or boolean
     */
    function OrderDrafts($dept_nr, $type)
    {
        global $db;
        if (empty($type) || empty($dept_nr)) return false;
        $this->useOrderList($type);
        $this->sql = "SELECT * FROM $this->coretable
						WHERE sent_datetime = '" . DBF_NODATETIME . "'
						AND validator=''
						AND (status='draft' OR status='')
						AND dept_nr=$dept_nr
						ORDER BY order_date";

        if ($this->res['od'] = $db->Execute($this->sql)) {
            if ($this->rec_count = $this->res['od']->RecordCount()) {
                return $this->res['od'];
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Returns all orders of a suplier marked as draft or are still unsent.
     *
     * The returned adodb record object contains rows of arrays.
     * Each array contains order  data with the following index keys:
     * - order_nr = order's primary key number
     * - supplier_nr = suplier number
     * - order_date = date of ordering
     * - order_time = time of ordering
     * - articles = ordered articles
     * - extra1 = extra notes
     * - extra2 = extra notes
     * - validator = validator's name
     * - ip_addr = IP address of the workstation that send the order
     * - priority = priority level
     * - status = record's status
     * - history = record's history
     * - modify_id = name of user
     * - modify_time = modify time stamp in yyyymmddhhMMss format
     * - create_id = name of use
     * - create_time = creation time stamp in yyyymmddhhMMss format
     * - sent_datetime = date and time sent in yyyy-mm-dd hh:MM:ss format
     * - process_datetime = date and time processed in yyyy-mm-dd hh:MM:ss format
     * @access public
     * @param int Suplier number
     * @param string Determines the final table name
     * @return mixed adodb record object or boolean
     */
    function OrderDraftsSupplier($supplier_nr, $type)
    {
        global $db;
        if (empty($type) || empty($supplier_nr)) return false;
        //$this->useOrderList($type);
        $this->sql = "SELECT * FROM care_supply
						WHERE sent_datetime = '" . DBF_NODATETIME . "'
						AND (status='draft' OR status='')
						AND idcare_supplier=$supplier_nr
						ORDER BY order_date";

        if ($this->res['od'] = $db->Execute($this->sql)) {
            if ($this->rec_count = $this->res['od']->RecordCount()) {
                return $this->res['od'];
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Returns all pending orders or orders with  "acknowledge and print" status.
     *
     * These orders are marked in the table as "pending" or "ack_print".
     * For detailed structure of the returned data, see <var>OrderDrafts()</var> method.
     * @access public
     * @param string Determines the final table name
     * @return mixed adodb record object or boolean
     */
    function PendingOrders($type)
    {
        global $db;
        if (empty($type)) return false;
        $this->useOrderList($type);
        $this->sql = "SELECT * FROM $this->coretable WHERE status='pending' OR status='ack_print' ORDER BY sent_datetime DESC";

        if ($this->res['po'] = $db->Execute($this->sql)) {
            if ($this->rec_count = $this->res['po']->RecordCount()) {
                return $this->res['po'];
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Checks if the product exists based on its primary key number.
     * @access public
     * @param int Item number
     * @param string Determines the final table name
     * @return boolean
     */
    function ProductExists($nr = 0, $type = '')
    {
        global $db;
        if (empty($type) || !$nr) return false;
        $this->useProduct($type);
        $this->sql = "SELECT bestellnum FROM $this->coretable WHERE bestellnum='$nr'";

        if ($buf = $db->Execute($this->sql)) {
            if ($buf->RecordCount()) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Checks if the product exists based on its name.
     * @access public
     * @param int Item number
     * @param string Determines the final table name
     * @return boolean
     */
    function ProductNameExists($artikelname = '', $table = '')
    {
        global $db;
        if (empty($type) || !$nr) return false;
        $this->useProduct($type);
        $this->sql = "SELECT artikelname FROM care_med_pharma_main WHERE artikelname='$artikelname'";
        if ($buf = $db->Execute($this->sql)) {
            if ($buf->RecordCount()) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Checks if the product exists based on its name.
     * @access public
     * @param int Item number
     * @param string Determines the final table name
     * @return boolean
     */
    function ProductInformation($idsub = '', $type)
    {
        global $db;
        if (!$idsub) return false;
        if (!$type) return false;
        if ($type == 'pharma') {
            $tbmain = 'care_pharma_products_main';
            $tbsub = 'care_pharma_products_main_sub';
        } else {
            $tbmain = 'care_med_products_main';
            $tbsub = 'care_med_products_main_sub';
        }
        $this->sql = "SELECT *
                    FROM $tbmain
                         INNER JOIN $tbsub ON (
                         $tbmain.bestellnum =
                          $tbsub.bestellnum)
                    WHERE $tbsub.id = '$idsub'";
        if ($buf = $db->Execute($this->sql)) {
            if ($buf->RecordCount()) {
                return $buf->fields;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Return the orders made by the given department
     *
     * @param int $dept_nr Department number
     */
    function getWaitingDeptOrders($dept_nr)
    {
        global $db;
        if (empty($dept_nr) || !$dept_nr) return false;
        //cleanup things a bit
        $this->sql = "DELETE FROM $this->tb_pocat WHERE $this->tb_pocat.dept_nr = $dept_nr";
        $db->Execute($this->sql);
        $this->coretable = $this->tb_prescription_sub;
        $this->ref_array = $this->fld_presc_sub;
        $this->sql = "INSERT INTO $this->tb_pocat(bestellnum, quantity, artikelname,
                     minorder, maxorder, proorder, dose, packing, dept_nr)
                    SELECT $this->tb_prescription_sub.bestellnum,
                           SUM($this->tb_prescription_sub.quantity) AS quantity,
                           $this->tb_pmain.artikelname,
                           $this->tb_pmain.minorder,
                           $this->tb_pmain.maxorder,
                           $this->tb_pmain.proorder,
                           $this->tb_pmain.dose,
                           $this->tb_pmain.packing,
                           care_encounter_prescription.dept_nr
                    FROM $this->tb_pmain
                         INNER JOIN $this->tb_prescription_sub ON (
                         $this->tb_pmain.bestellnum = $this->tb_prescription_sub.bestellnum)
                         INNER JOIN care_encounter_prescription ON (
                         $this->tb_prescription_sub.prescription_nr = care_encounter_prescription.nr)
                    WHERE care_encounter_prescription.dept_nr = $dept_nr AND $this->tb_prescription_sub.status = 'printed'
                    GROUP BY $this->tb_prescription_sub.bestellnum,
                             $this->tb_pmain.artikelname,
                             $this->tb_pmain.minorder,
                             $this->tb_pmain.maxorder,
                             $this->tb_pmain.proorder,
                             $this->tb_pmain.dose,
                             $this->tb_pmain.packing,
                             care_encounter_prescription.dept_nr
                    ORDER BY $this->tb_prescription_sub.companion DESC";
        if ($this->res['od'] = $db->Execute($this->sql)) {
            if ($this->rec_count = $this->res['od']->RecordCount()) {
                return $this->res['od'];
            } else {
                return false;
            }
        } else {
            return false;
        }

    }

    /**
     * function to update the prices of the medicaments on the prescription table
     * since i'm not so good with sql i've done a 3 step process
     * 1. get the actual bestellnum & the correspondin price
     * 2. update prescription sub with the prices
     * 3. update the status to 'done' in prescription
     *
     * @param int $dept_nr Department number
     * @return bool true / false
     */
    function updatePrescriptionPrices($dept_nr)
    {
        global $db;
        $doneUpdate = false;
        if (empty($dept_nr) || !$dept_nr) return false;

        //get the actual prices of the mediaments
        $this->sql = "SELECT $this->tb_polist.dept_nr,
                           $this->tb_polist.status,
                           AVG ($this->tb_polist_sub.price) AS price,
                           $this->tb_polist_sub.bestellnum
                    FROM $this->tb_polist
                         INNER JOIN $this->tb_polist_sub ON ($this->tb_polist.order_nr =
                          $this->tb_polist_sub.order_nr_sub)
                    WHERE $this->tb_polist.dept_nr = $dept_nr AND
                          $this->tb_polist.status = 'pending'
                    GROUP BY $this->tb_polist_sub.bestellnum";
        if ($buf = $db->Execute($this->sql)) {
            if ($this->rec_count = $buf->RecordCount()) {
                $actualPrices = $buf;
            } else {
                return false;
            }
        } else {
            return false;
        }

        //update the prices for the prescriptions
        while ($actualProduct = $actualPrices->fetchRow()) {
            $price = $actualProduct['price'];
            $bnum = $actualProduct['bestellnum'];
            $this->sql = "UPDATE $this->tb_prescription,
                               $this->tb_prescription_sub
                        SET $this->tb_prescription_sub.price = $price, 
                        	$this->tb_prescription_sub.status = 'done'
                        WHERE $this->tb_prescription.nr =
                         $this->tb_prescription_sub.prescription_nr AND
                              $this->tb_prescription_sub.bestellnum = $bnum AND
                              $this->tb_prescription_sub.status = 'printed' AND
                              $this->tb_prescription.dept_nr = $dept_nr AND
                              $this->tb_prescription.status = 'printed'";
            if ($db->Execute($this->sql))
                $doneUpdate = true;
        }
        //finaly i update the status of the actual prescriptions
        $db->Execute("UPDATE $this->tb_prescription SET $this->tb_prescription.status = 'done' WHERE $this->tb_prescription.dept_nr = $dept_nr");
        if ($doneUpdate == true)
            return true;
        else
            return false;
    }

//**********************************************************************************************************************************

    //made by d_s (Bình Minh)
    //-------------GENERIC DRUG-------------------------------------------------------------------------------------------
    //hàm lấy danh mục theo điều kiện
    function GetAllGenericDrugInfo($quick, $begin, $total_records, $records_in_page, $group_key, $attribute)
    {
        global $db;
        if ($quick != '') {
            $cond = "(generic_drug LIKE '" . $quick . "%' or generic_drug LIKE '% " . $quick . "%' or generic_drug LIKE '%(" . $quick . "%') ";
        }
        if ($group_key == '') {
            if ($cond != '')
                $records = $db->Execute("SELECT COUNT(*) AS records FROM care_pharma_generic_drug WHERE " . $cond)->FetchRow();
            else $records = $db->Execute("SELECT COUNT(*) AS records FROM care_pharma_generic_drug")->FetchRow();
            $total_records = $records["records"];
            if ($cond == '')
                $this->sql = "SELECT pharma_generic_drug_id, generic_drug FROM care_pharma_generic_drug ORDER BY generic_drug LIMIT $begin, $records_in_page";
            else
                $this->sql = "SELECT pharma_generic_drug_id, generic_drug FROM care_pharma_generic_drug WHERE " . $cond . " ORDER BY generic_drug LIMIT $begin, $records_in_page";

        } else {
            if ($cond != '')
                $records = $db->Execute("SELECT COUNT(*) AS records FROM care_pharma_generic_drug WHERE " . $cond . " AND pharma_group_id=$group_key")->FetchRow();
            else $records = $db->Execute("SELECT COUNT(*) AS records FROM care_pharma_generic_drug WHERE pharma_group_id=$group_key")->FetchRow();
            $total_records = $records["records"];
            if ($cond == '')
                $this->sql = "SELECT pharma_generic_drug_id, generic_drug FROM care_pharma_generic_drug WHERE pharma_group_id=$group_key ORDER BY generic_drug LIMIT $begin, $records_in_page";
            else
                $this->sql = "SELECT pharma_generic_drug_id, generic_drug FROM care_pharma_generic_drug WHERE " . $cond . " AND pharma_group_id=$group_key ORDER BY generic_drug LIMIT $begin, $records_in_page";

        }
        //echo($this->sql);
        return ($db->Execute($this->sql));
    }

    //hàm lấy thông tin theo 1 đối tượng
    function GetGenericDrugInfo($pharma_generic_drug_id, $user)
    {
        global $db;
        $this->sql = "SELECT pharma_generic_drug_id, generic_drug, 
					care_pharma_generic_drug.pharma_group_id, care_pharma_generic_drug.pharma_group_id_sub, 
					care_pharma_group.pharma_group_name, care_pharma_group_sub.pharma_group_name_sub, generic_drug_id, drug_id, using_type, 
					hospital_5th,hospital_6th,hospital_7th,hospital_8th, care_pharma_generic_drug.note,in_use,description,effects
					FROM care_pharma_group, care_pharma_generic_drug
					LEFT JOIN care_pharma_group_sub
					ON care_pharma_generic_drug.pharma_group_id_sub = care_pharma_group_sub.pharma_group_id_sub  
					WHERE pharma_generic_drug_id='" . $pharma_generic_drug_id . "' 
					AND care_pharma_generic_drug.pharma_group_id=care_pharma_group.pharma_group_id";

        return ($db->Execute($this->sql));
    }

    //new
    function AddGenericDrug($generic_drug, $pharma_group_id, $pharma_group_id_sub, $drug_id, $generic_drug_id, $using_type, $hospital_5th, $hospital_6th, $hospital_7th, $hospital_8th, $description, $effects, $note, $in_use, $user)
    {
        global $db;
        if ($pharma_group_id == '') $pharma_group_id = '1';
        if ($drug_id == '') $drug_id = 0;
        if ($generic_drug_id == '') $generic_drug_id = 0;

        if ($hospital_5th == '') $hospital_5th = 0;
        else $hospital_5th = 1;
        if ($hospital_6th == '') $hospital_6th = 0;
        else $hospital_6th = 1;
        if ($hospital_7th == '') $hospital_7th = 0;
        else $hospital_7th = 1;
        if ($hospital_8th == '') $hospital_8th = 0;
        else $hospital_8th = 1;

        $in_use = 1;

        $this->sql = "INSERT INTO care_pharma_generic_drug (generic_drug, pharma_group_id, pharma_group_id_sub, generic_drug_id, drug_id, using_type,hospital_5th,hospital_6th,hospital_7th,hospital_8th,description,effects,note,in_use)
                            VALUES ('" . $generic_drug . "','" . $pharma_group_id . "','$pharma_group_id_sub','$generic_drug_id','" . $drug_id . "','" . $using_type . "','" . $hospital_5th . "','" . $hospital_6th . "','" . $hospital_7th . "','" . $hospital_8th . "','" . $description . "','" . $effects . "','" . $note . "', '$in_use')";
        //echo($this->sql);
        $db->Execute($this->sql);
        $this->sql = "SELECT pharma_generic_drug_id
                            FROM care_pharma_generic_drug
                            WHERE generic_drug='$generic_drug'";
//                echo($this->sql);
        $result = $db->Execute($this->sql);
        $result = $result->FetchRow();
        return ($result["pharma_generic_drug_id"]);
    }

    //edit
    function EditGenericDrug($pharma_generic_drug_id, $generic_drug, $pharma_group_id, $pharma_group_id_sub, $generic_drug_id, $drug_id, $using_type, $hospital_5th, $hospital_6th, $hospital_7th, $hospital_8th, $description, $effects, $note, $user)
    {
        global $db;
        $this->sql = "UPDATE care_pharma_generic_drug
                            SET generic_drug = '" . $generic_drug . "', generic_drug_id='$generic_drug_id', pharma_group_id = '" . $pharma_group_id . "',  pharma_group_id_sub = '" . $pharma_group_id_sub . "', drug_id = " . $drug_id . ", using_type = '" . $using_type . "',
                                hospital_5th = " . $hospital_5th . ", hospital_6th = " . $hospital_6th . ", hospital_7th = " . $hospital_7th . ", hospital_8th = " . $hospital_8th . ",
                                description = '" . $description . "', effects = '" . $effects . "', note ='" . $note . "'
                            WHERE pharma_generic_drug_id = " . $pharma_generic_drug_id;
        //echo($this->sql);
        return ($db->Execute($this->sql));
    }

    //delete
    function DeleteGenericDrug($pharma_generic_drug_id)
    {
        global $db;
        $this->sql = "DELETE FROM care_pharma_generic_drug
                            WHERE pharma_generic_drug_id = '" . $pharma_generic_drug_id . "'";
        return ($db->Execute($this->sql));
    }

    //hàm lấy select box nhóm thuốc
    function GetPharmaGroupName($group_key = '')
    {
        global $db;
        if ($group_key == '')
            $this->sql = "SELECT pharma_group_id, pharma_group_name
								FROM care_pharma_group";
        else $this->sql = "SELECT pharma_group_id, pharma_group_name 
                    FROM care_pharma_group
                    WHERE pharma_group_id='$group_key'";
        //echo($this->sql);
        return ($db->Execute($this->sql));
    }

    function GetPharmaGroupNameVn($group_key)
    {
        global $db;
        if ($group_key == '')
            $this->sql = "SELECT pharma_group_id, pharma_group_name
								FROM care_pharma_group_vn";
        else $this->sql = "SELECT pharma_group_id, pharma_group_name 
                    FROM care_pharma_group
                    WHERE pharma_group_id='$group_key'";
        //echo($this->sql);
        return ($db->Execute($this->sql));
    }

    function ListPharmaGroupNameSub($group)
    {
        global $db;
        if ($group == '') {
            $this->sql = "SELECT pharma_group_id_sub, pharma_group_name_sub 
								FROM care_pharma_group_sub
								WHERE pharma_group_id='1'";
        } else $this->sql = "SELECT pharma_group_id_sub, pharma_group_name_sub 
								FROM care_pharma_group_sub
								WHERE pharma_group_id='$group'";
        return ($db->Execute($this->sql));
    }

    function GetPharmaGroupNameSub($group_key)
    { //id_sub
        global $db;
        if ($group_key == '')
            return false;
        $this->sql = "SELECT pharma_group_id, pharma_group_id_sub, pharma_group_name 
                    FROM care_pharma_group
                    WHERE pharma_group_id_sub='$group_key' ";
        //echo($this->sql);
        return ($db->Execute($this->sql));
    }

    function GetPharmaGroupNameByGeneric($generic_name)
    {
        global $db;
        if ($generic_name == '')
            return;
        $this->sql = "SELECT care_pharma_group.pharma_group_id, care_pharma_group.pharma_group_name, care_pharma_generic_drug.pharma_generic_drug_id 
							FROM care_pharma_group, care_pharma_generic_drug 
							WHERE care_pharma_group.pharma_group_id=care_pharma_generic_drug.pharma_group_id
							AND care_pharma_generic_drug.generic_drug='$generic_name'";

        //echo($this->sql);
        return ($db->Execute($this->sql));
    }

    //hàm lấy 1 trang của danh mục theo điều kiện
    function GetGenericDrugCatalogue($quick, $current_page, $total_records, $records_in_page, $before, $font_before, $font_after, $after, $before2, $after2, $general_info, $catalogue, $group_key, $attribute)
    {
        global $db;
        $begin = ($current_page - 1) * $records_in_page; //khởi tạo kết quả đầu tiên của trang
        $catalogue_info = $this->GetAllGenericDrugInfo($quick, $begin, &$total_records, $records_in_page, $group_key, $attribute);
        if (is_object($catalogue_info)) {
            $i = 0;
            $sCatalogueInfo = "";
            while ($object = $catalogue_info->FetchRow()) {
                $i++;
                if ($i % 2 != 0)
                    $sCatalogueInfo = $sCatalogueInfo . $before . "'$catalogue'" . ",'" . $object["pharma_generic_drug_id"] . "','" . $current_page . "')" . '"' . $font_before . $object['generic_drug'] . $font_after . $after;
                else
                    $sCatalogueInfo = $sCatalogueInfo . $before2 . "'$catalogue'" . ",'" . $object["pharma_generic_drug_id"] . "','" . $current_page . "')" . '"' . $font_before . $object['generic_drug'] . $font_after . $after2;
            }
        }
        return $sCatalogueInfo;
    }

    //lấy thông tin của kết quả tìm kiếm đầu tiên
    function GetFirstGenericDrugCatalogue($quick, $group_key, $attribute)
    {
        global $db;
        $catalogue_info = $this->GetAllGenericDrugInfo($quick, 0, $total_records, 1, $group_key, $attribute);
        if (is_object($catalogue_info)) {
            $object = $catalogue_info->FetchRow();
            $generic_drug = $object["pharma_generic_drug_id"];
        }
        return $this->GetGenericDrugInfo($generic_drug, '');
    }

    //--------------------MEDICINE------------------------------------------------------------------------------------------
    //hàm lấy toàn bộ danh mục theo điều kiện
    function GetAllMedicineInfo($quick, $begin, $total_records, $records_in_page, $group_key, $attribute)
    {
        global $db;
        if ($quick != '') {
            $cond = "(product_name LIKE '" . $quick . "%' or product_name LIKE '% " . $quick . "%') and ";
        }
        if ($group_key == '') {
            $records = $db->Execute("SELECT COUNT(*) AS records FROM care_pharma_products_main WHERE $cond (pharma_type=3 or pharma_type=2 or pharma_type=1)")->FetchRow();
            $total_records = $records["records"];
            $this->sql = "SELECT product_encoder, product_name FROM care_pharma_products_main WHERE " . $cond . " (pharma_type=3 or pharma_type=2 or pharma_type=1) ORDER BY product_name LIMIT $begin, $records_in_page";

        } else {
            $count_sql = "SELECT COUNT(*) AS records FROM care_pharma_products_main, care_pharma_generic_drug, care_pharma_group WHERE $cond
                            (pharma_type=3 or pharma_type=2 or pharma_type=1)
                            AND care_pharma_generic_drug.pharma_generic_drug_id=care_pharma_products_main.pharma_generic_drug_id
                            AND care_pharma_generic_drug.pharma_group_id=care_pharma_group.pharma_group_id AND care_pharma_group.pharma_group_id=$group_key";
            $records = $db->Execute($count_sql)->FetchRow();
            $total_records = $records["records"];
            $this->sql = "SELECT product_encoder, product_name FROM care_pharma_products_main, care_pharma_generic_drug, care_pharma_group WHERE " . $cond . "
                            (pharma_type=3 or pharma_type=2 or pharma_type=1)
                            AND care_pharma_generic_drug.pharma_generic_drug_id=care_pharma_products_main.pharma_generic_drug_id
                            AND care_pharma_generic_drug.pharma_group_id=care_pharma_group.pharma_group_id AND care_pharma_group.pharma_group_id=$group_key
                            ORDER BY product_name LIMIT $begin, $records_in_page";

        }
        //echo($count_sql);
        //echo($this->sql);
        return ($db->Execute($this->sql));
    }

    //hàm lấy 1 số thông tin cơ bản của nhiều đối tượng
    function GetAllMedicineInfos($quick, $begin, $total_records, $records_in_page, $group_key, $attribute)
    {
        global $db;
        if ($quick != '') {
            $cond = "(product_name LIKE '" . $quick . "%' or product_name LIKE '% " . $quick . "%') and ";
        }
        if ($group_key == '') {
            $records = $db->Execute("SELECT COUNT(*) AS records FROM care_pharma_products_main WHERE $cond (pharma_type=3 or pharma_type=2 or pharma_type=1)")->FetchRow();
            $total_records = $records["records"];
//                    $this->sql="SELECT product_encoder, product_name, price, care_pharma_unit_of_medicine.unit_name_of_medicine
//                                FROM care_pharma_products_main, care_pharma_unit_of_medicine
//                                WHERE ".$cond." (pharma_type=3 or pharma_type=2 or pharma_type=1)
//                                AND care_pharma_unit_of_medicine.unit_of_medicine=care_pharma_products_main.unit_of_medicine
//                                ORDER BY product_name LIMIT $begin, $records_in_page";
            $this->sql = "SELECT care_pharma_products_main.product_encoder, product_name, price, care_pharma_unit_of_medicine.unit_name_of_medicine, product_lot_id, DAY(exp_date), MONTH(exp_date), YEAR(exp_date)
                                FROM care_pharma_products_main, care_pharma_unit_of_medicine, care_pharma_available_product, care_pharma_available_department
                                WHERE " . $cond . " (pharma_type=3 OR pharma_type=2 OR pharma_type=1)
                                AND care_pharma_unit_of_medicine.unit_of_medicine=care_pharma_products_main.unit_of_medicine
                                AND care_pharma_products_main.product_encoder=care_pharma_available_product.product_encoder
                                AND care_pharma_available_product.available_product_id=care_pharma_available_department.available_product_id
                                AND care_pharma_available_department.department=38
                                AND care_pharma_available_product.exp_date IN
                                (
                                        SELECT MAX(exp_date)
                                        FROM care_pharma_available_product
                                        WHERE care_pharma_products_main.product_encoder=product_encoder
                                )
                                ORDER BY product_name LIMIT $begin, $records_in_page";

        }
        //echo($count_sql);
//               echo($this->sql);
        return ($db->Execute($this->sql));
    }

    function GetAllMedicineInfos2($quick, $begin, $total_records, $records_in_page, $group_key, $attribute)
    {
        global $db;
        if ($quick != '') {
            $cond = "(product_name LIKE '" . $quick . "%' or product_name LIKE '% " . $quick . "%') and ";
        }
        if ($group_key == '') {
            $records = $db->Execute("SELECT COUNT(*) AS records FROM care_pharma_products_main WHERE $cond (pharma_type=3 or pharma_type=2 or pharma_type=1)")->FetchRow();
            $total_records = $records["records"]; //                    
            $this->sql = "SELECT care_pharma_products_main.product_encoder, product_name, price, care_pharma_unit_of_medicine.unit_name_of_medicine
                                FROM care_pharma_products_main, care_pharma_unit_of_medicine
                                WHERE " . $cond . " (pharma_type=3 OR pharma_type=2 OR pharma_type=1)
                                AND care_pharma_unit_of_medicine.unit_of_medicine=care_pharma_products_main.unit_of_medicine                                
                                ORDER BY product_name LIMIT $begin, $records_in_page";

        }
        //echo($count_sql);
//               echo($this->sql);
        return ($db->Execute($this->sql));
    }

    //hàm lấy thông tin của 1 đối tượng
    function GetMedicineInfo($product_encoder, $user)
    {
        global $db;
        $this->sql = "SELECT product_encoder, product_name, care_pharma_products_main.pharma_generic_drug_id, generic_drug,content,component,care_pharma_products_main.using_type, sodangky, hangsx, nuocsx, 
                    care_pharma_products_main.type_of_medicine, type_name_of_medicine, care_pharma_products_main.unit_of_medicine, caution, care_pharma_products_main.care_supplier, supplier_name,
                    care_pharma_products_main.note, price, unit_of_price, available_number, unit_name_of_medicine, short_name, care_pharma_products_main.effects, care_pharma_products_main.in_use,
                    care_pharma_products_main.description, care_pharma_group.pharma_group_id, care_pharma_group.pharma_group_name 
                    FROM care_pharma_products_main, care_currency, care_pharma_generic_drug, care_supplier, care_pharma_type_of_medicine,care_pharma_unit_of_medicine, care_pharma_group 
                    WHERE product_encoder='$product_encoder' AND
                    care_pharma_products_main.unit_of_price=care_currency.item_no AND care_pharma_products_main.pharma_generic_drug_id=care_pharma_generic_drug.pharma_generic_drug_id AND
                    care_pharma_products_main.type_of_medicine=care_pharma_type_of_medicine.type_of_medicine AND care_pharma_products_main.unit_of_medicine= care_pharma_unit_of_medicine.unit_of_medicine AND
					care_pharma_group.pharma_group_id=care_pharma_generic_drug.pharma_group_id 
                    AND care_pharma_products_main.care_supplier=care_supplier.supplier";
        //echo($this->sql);
        return ($db->Execute($this->sql));
    }

    function AddMedicine($product_encoder, $product_name, $pharma_generic_drug_id, $content, $component, $using_type, $type_of_medicine, $unit_of_medicine, $caution, $care_supplier, $note, $price, $unit_of_price, $effects, $in_use, $description, $user, $sodangky, $hangsx, $nuocsx)
    {
        global $db;
        $this->sql = "SELECT pharma_generic_drug_id
                            FROM care_pharma_generic_drug
                            WHERE generic_drug='$pharma_generic_drug_id'";
//                echo($this->sql);
        $result = $db->Execute($this->sql)->FetchRow();
        $pharma_generic_drug_id = $result["pharma_generic_drug_id"];
        $this->sql = "INSERT INTO care_pharma_products_main (product_encoder, product_name, pharma_generic_drug_id, content,component,using_type,type_of_medicine,
                            unit_of_medicine, caution, care_supplier,note, price, unit_of_price,effects, in_use, description, pharma_type, sodangky, hangsx, nuocsx)
                            VALUES ('', '$product_name', $pharma_generic_drug_id, '$content','$component','$using_type','$type_of_medicine', '$unit_of_medicine', '$caution', '$care_supplier','$note', '$price', '$unit_of_price','$effects', 1, '$description', 3, '$sodangky', '$hangsx', '$nuocsx')";
//                echo($this->sql);
        return ($db->Execute($this->sql));
    }

    function AddVnMedicine($product_encoder, $product_name, $pharma_generic_drug_id, $content, $component, $using_type, $type_of_medicine, $unit_of_medicine, $caution, $care_supplier, $note, $price, $unit_of_price, $effects, $in_use, $description, $user, $sodangky, $hangsx, $nuocsx)
    {
        global $db;
        $this->sql = "INSERT INTO care_pharma_products_main (product_encoder, product_name, pharma_generic_drug_id, content,component,using_type,type_of_medicine,
                            unit_of_medicine, caution, care_supplier,note, price, unit_of_price,effects, in_use, description, pharma_type, sodangky, hangsx, nuocsx)
                            VALUES ('', '$product_name', 1, '$content','$component','$using_type','$type_of_medicine', '$unit_of_medicine', '$caution', '$care_supplier','$note', '$price', $unit_of_price,'$effects', 1, '$description', 4, '$sodangky', '$hangsx', '$nuocsx')";
        //echo($this->sql);
        return ($db->Execute($this->sql));
    }

    function EditMedicine($product_encoder, $product_name, $pharma_generic_drug_id, $content, $component, $using_type, $type_of_medicine, $unit_of_medicine, $caution, $care_supplier, $note, $price, $unit_of_price, $effects, $in_use, $description, $history, $sodangky, $hangsx, $nuocsx)
    {
        global $db;
        $this->sql = "UPDATE care_pharma_products_main
                    SET product_name='$product_name', pharma_generic_drug_id='$pharma_generic_drug_id', content='$content',
                        component='$component', using_type='$using_type', type_of_medicine='$type_of_medicine', unit_of_medicine='$unit_of_medicine', caution='$caution',care_supplier='$care_supplier',note='$note',price='$price', unit_of_price='$unit_of_price', effects='$effects',
                                in_use='$in_use', description='$description', history='$history', sodangky='$sodangky', hangsx='$hangsx', nuocsx='$nuocsx' 
                            WHERE product_encoder='$product_encoder'";
        //echo($this->sql);
        return $this->Transact($this->sql);
    }

    function DeleteMedicine($product_encoder)
    {
        global $db;
        $this->sql = "DELETE FROM care_pharma_products_main
						WHERE product_encoder='$product_encoder'";
        //echo($this->sql);
        return $this->Transact($this->sql);
    }

    //cập nhật số lượng tồn kho
    function UpdateAvailableMedicine($product_encoder, $available_number, $user)
    {
        global $db;
        $this->sql = "UPDATE care_pharma_products_main
                            SET available_number=$available_number
                            WHERE product_encoder='$product_encoder'";
        //echo($this->sql);
        return ($db->Execute($this->sql));
    }

    //hàm lấy select box dạng thuốc
    function GetMedicineType()
    {
        global $db;
        $this->sql = "SELECT type_of_medicine, type_name_of_medicine
                    FROM care_pharma_type_of_medicine";
        //echo($this->sql);
        return ($db->Execute($this->sql));
    }

    //đơn vị tiền tệ
    function GetCurrency()
    {
        global $db;
        $this->sql = "SELECT item_no, short_name
                    FROM care_currency";
        //echo($this->sql);
        return ($db->Execute($this->sql));
    }

    //đơn vị tính (thuốc)
    function GetMedicineUnit()
    {
        global $db;
        $this->sql = "SELECT unit_of_medicine, unit_name_of_medicine
                    FROM care_pharma_unit_of_medicine";
        //echo($this->sql);
        return ($db->Execute($this->sql));
    }

    //hàm lấy 1 trang của danh mục theo điều kiện
    function GetMedicineCatalogue($quick, $current_page, $total_records, $records_in_page, $before, $font_before, $font_after, $after, $before2, $after2, $general_info, $catalogue, $group_key, $attribute)
    {
        global $db;
        $records = 0;
        $begin = ($current_page - 1) * $records_in_page; //khởi tạo kết quả đầu tiên của trang
        $end = $current_page * $records_in_page;
        $catalogue_info = $this->GetAllMedicineInfo($quick, $begin, &$total_records, $records_in_page, $group_key, $attribute);
        if (is_object($catalogue_info)) {
            $i = 0;
            $sCatalogueInfo = "";
            while ($object = $catalogue_info->FetchRow()) {
                $i++;
                if ($i % 2 != 0)
                    $sCatalogueInfo = $sCatalogueInfo . $before . "'$catalogue'" . ",'" . $object["product_encoder"] . "','" . $current_page . "')" . '"' . $font_before . $object['product_name'] . $font_after . $after;
                else
                    $sCatalogueInfo = $sCatalogueInfo . $before2 . "'$catalogue'" . ",'" . $object["product_encoder"] . "','" . $current_page . "')" . '"' . $font_before . $object['product_name'] . $font_after . $after2;
            }
        }
        return $sCatalogueInfo;
    }

    //lấy thông tin của kết quả tìm kiếm đầu tiên
    function GetFirstMedicineCatalogue($quick, $group_key, $attribute)
    {
        global $db;
        $catalogue_info = $this->GetAllMedicineInfo($quick, 0, $total_records, 1, $group_key, $attribute);
        if (is_object($catalogue_info)) {
            $object = $catalogue_info->FetchRow();
            $obj = $object["product_encoder"];
        }
        return $this->GetMedicineInfo($obj, '');
    }

    //------------------------VNMEDICINE-----------------------------------------------------------------------------------
    //hàm lấy danh mục theo điều kiện
    function GetAllVnMedicineInfo($quick, $begin, $total_records, $records_in_page, $group_key, $attribute)
    {
        global $db;
        if ($quick != '') {
            $cond = "(product_name LIKE '" . $quick . "%' or product_name LIKE '% " . $quick . "%') and ";
        }
        if ($group_key == '') {
            $sql = "SELECT COUNT(*) AS records FROM care_pharma_products_main WHERE $cond (pharma_type=4 or pharma_type=8 or pharma_type=9 or pharma_type=10)";
            if ($temp = $db->Execute($sql)) {
                $records = $temp->FetchRow();
                $total_records = $records["records"];
            } else $total_records = 0;

            $this->sql = "SELECT product_encoder, product_name 
							FROM care_pharma_products_main 
							WHERE " . $cond . " (pharma_type=4 or pharma_type=8 or pharma_type=9 or pharma_type=10) ORDER BY product_name LIMIT $begin, $records_in_page";
            //echo($this->sql);
            return ($db->Execute($this->sql));
        } else {
            $sql = "SELECT COUNT(*) AS records 
						FROM care_pharma_products_main, care_pharma_generic_vn, care_pharma_group_vn   
						WHERE $cond (pharma_type=4 or pharma_type=8 or pharma_type=9 or pharma_type=10)
						AND care_pharma_products_main.pharma_generic_drug_id=care_pharma_generic_vn.pharma_generic_drug_id
						AND care_pharma_generic_vn.pharma_group_id=care_pharma_group_vn.pharma_group_id
						AND care_pharma_group_vn.pharma_group_id='$group_key'";
            if ($temp = $db->Execute($sql)) {
                $records = $temp->FetchRow();
                $total_records = $records["records"];
            } else $total_records = 0;

            $this->sql = "SELECT product_encoder, product_name 
							FROM care_pharma_products_main, care_pharma_generic_vn, care_pharma_group_vn  
							WHERE " . $cond . " (pharma_type=4 or pharma_type=8 or pharma_type=9 or pharma_type=10) 
							AND care_pharma_products_main.pharma_generic_drug_id=care_pharma_generic_vn.pharma_generic_drug_id
							AND care_pharma_generic_vn.pharma_group_id=care_pharma_group_vn.pharma_group_id
							AND care_pharma_group_vn.pharma_group_id='$group_key' 
							ORDER BY product_name LIMIT $begin, $records_in_page";
            //echo($this->sql);
            return ($db->Execute($this->sql));
        }
    }

    //hàm lấy 1 đối tượng trong danh mục
    function GetVnMedicineInfo($product_encoder, $user)
    {
        global $db;
        $this->sql = "SELECT product_encoder, product_name, content,component,care_pharma_products_main.using_type,
                    care_pharma_products_main.type_of_medicine, type_name_of_medicine, care_pharma_products_main.unit_of_medicine, caution, care_pharma_products_main.care_supplier, supplier_name, sodangky, hangsx, nuocsx,
                    care_pharma_products_main.note, price, unit_of_price, available_number, unit_name_of_medicine, short_name, care_pharma_products_main.effects, care_pharma_products_main.in_use,
                    care_pharma_products_main.description, care_pharma_group_vn.pharma_group_name, care_pharma_generic_vn.generic_drug 
                    FROM care_pharma_products_main, care_currency, care_supplier, care_pharma_type_of_medicine,care_pharma_unit_of_medicine,
					care_pharma_group_vn, care_pharma_generic_vn 
                    WHERE product_encoder='$product_encoder' AND
					care_pharma_group_vn.pharma_group_id=care_pharma_generic_vn.pharma_group_id AND
					care_pharma_generic_vn.pharma_generic_drug_id=care_pharma_products_main.pharma_generic_drug_id AND
                    care_pharma_products_main.unit_of_price=care_currency.item_no AND care_pharma_products_main.type_of_medicine=care_pharma_type_of_medicine.type_of_medicine
                    AND care_pharma_products_main.unit_of_medicine= care_pharma_unit_of_medicine.unit_of_medicine AND care_pharma_products_main.care_supplier=care_supplier.supplier";
        //echo($this->sql);
        return ($db->Execute($this->sql));
    }

    //hàm lấy 1 trang của danh mục theo điều kiện
    function GetVnMedicineCatalogue($quick, $current_page, $total_records, $records_in_page, $before, $font_before, $font_after, $after, $before2, $after2, $general_info, $catalogue, $group_key, $attribute)
    {
        global $db;
        $begin = ($current_page - 1) * $records_in_page; //khởi tạo kết quả đầu tiên của trang
        $catalogue_info = $this->GetAllVnMedicineInfo($quick, $begin, &$total_records, $records_in_page, $group_key, $attribute);
        if (is_object($catalogue_info)) {
            $i = 0;
            while ($object = $catalogue_info->FetchRow()) {
                $i++;
                if ($i % 2 != 0)
                    $sCatalogueInfo = $sCatalogueInfo . $before . "'$catalogue'" . ",'" . $object["product_encoder"] . "','" . $current_page . "')" . '"' . $font_before . $object['product_name'] . $font_after . $after;
                else
                    $sCatalogueInfo = $sCatalogueInfo . $before2 . "'$catalogue'" . ",'" . $object["product_encoder"] . "','" . $current_page . "')" . '"' . $font_before . $object['product_name'] . $font_after . $after2;
            }
        }
        return $sCatalogueInfo;
    }

    //lấy thông tin của kết quả tìm kiếm đầu tiên
    function GetFirstVnMedicineCatalogue($quick, $group_key, $attribute)
    {
        global $db;
        $catalogue_info = $this->GetAllVnMedicineInfo($quick, 0, $total_records, 1, $group_key, $attribute);
        if (is_object($catalogue_info)) {
            $object = $catalogue_info->FetchRow();
            $obj = $object["product_encoder"];
        }
        return $this->GetVnMedicineInfo($obj, '');
    }

    //-----------------------MEDIPOT------------------------------------------------------------------------------------------
    //hàm lấy danh mục theo điều kiện
    function GetAllMedipotInfo($quick, $begin, $total_records, $records_in_page, $group_key, $attribute)
    {
        global $db;
        if ($quick != '') {
            $cond = "(product_name LIKE '" . $quick . "%' or product_name LIKE '% " . $quick . "%') and ";
        }
        $records = $db->Execute("SELECT COUNT(*) AS records FROM care_med_products_main WHERE " . $cond . " (pharma_type=5 or pharma_type=6 or pharma_type=7)")->FetchRow();

        $total_records = $records["records"];

        $this->sql = "SELECT product_encoder, product_name FROM care_med_products_main WHERE " . $cond . " (pharma_type=5 or pharma_type=6 or pharma_type=7 ) ORDER BY product_name LIMIT $begin, $records_in_page";
        //echo($this->sql);
        return ($db->Execute($this->sql));
    }

    //hàm lấy 1 đối tượng trong danh mục 
    function GetMedipotInfo($product_encoder, $user)
    {
        global $db;
        $this->sql = "SELECT product_encoder, product_name, care_med_products_main.using_type,care_med_products_main.unit_of_medicine, caution, care_med_products_main.care_supplier, supplier_name,
                    care_med_products_main.note, price, unit_of_price, available_number, unit_name_of_medicine, short_name, care_med_products_main.in_use, sodangky, hangsx, nuocsx,
                    care_med_products_main.description
                    FROM care_med_products_main, care_currency, care_supplier, care_med_unit_of_medipot
                    WHERE product_encoder='$product_encoder' AND
                    care_med_products_main.unit_of_price=care_currency.item_no AND care_med_products_main.unit_of_medicine= care_med_unit_of_medipot.unit_of_medicine
                    AND care_med_products_main.care_supplier=care_supplier.supplier";
        //echo($this->sql);
        return ($db->Execute($this->sql));
    }

    //hàm lấy 1 trang của danh mục theo điều kiện
    function GetMedipotCatalogue($quick, $current_page, $total_records, $records_in_page, $before, $font_before, $font_after, $after, $before2, $after2, $general_info, $catalogue, $group_key, $attribute)
    {
        global $db;
        $begin = ($current_page - 1) * $records_in_page; //khởi tạo kết quả đầu tiên của trang
        $catalogue_info = $this->GetAllMedipotInfo($quick, $begin, &$total_records, $records_in_page, $group_key, $attribute);
        if (is_object($catalogue_info)) {
            $i = 0;
            $sCatalogueInfo = "";
            while ($object = $catalogue_info->FetchRow()) {
                $i++;
                if ($i % 2 != 0)
                    $sCatalogueInfo = $sCatalogueInfo . $before . "'$catalogue'" . ",'" . $object["product_encoder"] . "','" . $current_page . "')" . '"' . $font_before . $object['product_name'] . $font_after . $after;
                else
                    $sCatalogueInfo = $sCatalogueInfo . $before2 . "'$catalogue'" . ",'" . $object["product_encoder"] . "','" . $current_page . "')" . '"' . $font_before . $object['product_name'] . $font_after . $after2;
            }
        }
        return $sCatalogueInfo;
    }

    //lấy thông tin của kết quả tìm kiếm đầu tiên
    function GetFirstMedipotCatalogue($quick, $group_key, $attribute)
    {
        global $db;
        $catalogue_info = $this->GetAllMedipotInfo($quick, 0, $total_records, 1, $group_key, $attribute);
        if (is_object($catalogue_info)) {
            $object = $catalogue_info->FetchRow();
            $obj = $object["product_encoder"];
        }
        return $this->GetMedipotInfo($obj, '');
    }

    //đơn vị tính (vật tư y tế)
    function GetMedipotUnit()
    {
        global $db;
        $this->sql = "SELECT unit_of_medicine, unit_name_of_medicine
                    FROM care_med_unit_of_medipot";
        //echo($this->sql);
        return ($db->Execute($this->sql));
    }

    function GetMedipotGroup($group_key = '')
    {
        global $db;
        $this->sql = "SELECT * 
                    FROM care_med_products_main_sub";
        //echo($this->sql);
        return ($db->Execute($this->sql));
    }

    function EditMedipot($product_encoder, $product_name, $using_type, $unit_of_medicine, $caution, $care_supplier, $note, $price, $unit_of_price, $description, $history, $sodangky, $hangsx, $nuocsx)
    {
        global $db;
        $this->sql = "UPDATE care_med_products_main
                    SET product_name='$product_name', using_type='$using_type', unit_of_medicine='$unit_of_medicine', caution='$caution',care_supplier='$care_supplier',note='$note',price='$price', unit_of_price='$unit_of_price', description='$description', history='$history', sodangky='$sodangky', hangsx='$hangsx', nuocsx='$nuocsx' 
                    WHERE product_encoder='$product_encoder'";
        return $this->Transact($this->sql);
    }

    function AddMedipot($product_encoder, $product_name, $group_of_medipot_input, $unit_name_of_medicine_input, $caution, $supplier, $note, $price, $unit_of_price, $in_use, $description, $user, $sodangky, $hangsx, $nuocsx)
    {
        global $db;
        $this->sql = "INSERT INTO care_med_products_main (product_encoder, product_name, id_sub, unit_of_medicine, caution, care_supplier, note, price, unit_of_price, in_use, description, pharma_type, sodangky, hangsx, nuocsx)
                        VALUES ('', '$product_name', $group_of_medipot_input, '$unit_name_of_medicine_input', '$caution', '$supplier','$note', '$price', '$unit_of_price', 1, '$description', 5, '$sodangky', '$hangsx', '$nuocsx')";
        //echo($this->sql);
        return ($db->Execute($this->sql));
    }

    function DeleteMedipot($product_encoder)
    {
        global $db;
        $this->sql = "DELETE FROM care_med_products_main
						WHERE product_encoder='$product_encoder'";
        return $this->Transact($this->sql);
    } //***********************************************************************************************************************
    //-----------------QUẢN LÝ TỒN KHO----------------------------
    //Thuốc
    function ShowExp()
    {
        global $db;
        $this->sql = "SELECT product_name, unit_name_of_medicine, care_pharma_products_main.product_encoder, sodangky, product_lot_id, exp_date, DAY(exp_date),MONTH(exp_date),YEAR(exp_date), care_pharma_available_department.available_number, care_department.name_short, care_department.name_formal, LD_var AS \"LD_var\" 
                            FROM care_pharma_available_department, care_pharma_available_product, care_pharma_products_main, care_pharma_unit_of_medicine, care_department
                            WHERE care_pharma_available_department.available_product_id=care_pharma_available_product.available_product_id
                            AND care_pharma_products_main.product_encoder=care_pharma_available_product.product_encoder
                            AND care_pharma_unit_of_medicine.unit_of_medicine=care_pharma_products_main.unit_of_medicine
                            AND care_department.nr=department
                            AND care_pharma_available_department.available_number>0
                            AND ((YEAR(exp_date) - YEAR(CURRENT_TIMESTAMP) <1) OR ((YEAR(exp_date) - YEAR(CURRENT_TIMESTAMP) <1) AND (MONTH(exp_date) < MONTH(CURRENT_TIMESTAMP))))
                            ORDER BY exp_date";
        if ($this->result = $db->Execute($this->sql)) {
            if ($this->result->RecordCount()) {
                return $this->result;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function ShowExpCabinet($dept_nr, $ward_nr, $current_page, $number_items_per_page)
    {
        global $db;
        $dept_ward = '';
        if ($dept_nr != '')
            $dept_ward = " AND taikhoa.department='" . $dept_nr . "' ";
        if ($ward_nr != '')
            $dept_ward .= " AND taikhoa.ward_nr='" . $ward_nr . "' ";
        if ($current_page != '' && $number_items_per_page != '') {
            $start_from = ($current_page - 1) * $number_items_per_page;
            $limit_number = 'LIMIT ' . $start_from . ', ' . $number_items_per_page;
        }

        $this->sql = "SELECT DISTINCT khochan.product_name, donvi.unit_name_of_medicine, khochan.product_encoder, khochan.sodangky, tatcakhoa.product_lot_id,  DAY(tatcakhoa.exp_date) AS dayexp, MONTH(tatcakhoa.exp_date) AS monthexp, YEAR(tatcakhoa.exp_date) AS yearexp, taikhoa.available_number   
                FROM care_pharma_available_department AS taikhoa, care_pharma_available_product AS tatcakhoa, care_pharma_products_main AS  khochan, care_pharma_unit_of_medicine AS donvi  
                WHERE taikhoa.available_product_id=tatcakhoa.available_product_id 
					" . $dept_ward . " 
                    AND khochan.product_encoder=tatcakhoa.product_encoder 
                    AND donvi.unit_of_medicine=khochan.unit_of_medicine 
                    AND taikhoa.available_number>0 	
                ORDER BY exp_date 
				" . $limit_number;
        if ($this->result = $db->Execute($this->sql)) {
            if ($this->result->RecordCount()) {
                return $this->result;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function SearchExpCabinet($dept_nr, $ward_nr, $condition)
    {
        global $db;
        $dept_ward = '';
        if ($dept_nr != '')
            $dept_ward = " AND taikhoa.department='" . $dept_nr . "' ";
        if ($ward_nr != '')
            $dept_ward .= " AND taikhoa.ward_nr='" . $ward_nr . "' ";

        $this->sql = "SELECT DISTINCT khochan.product_name, donvi.unit_name_of_medicine, khochan.product_encoder, khochan.sodangky, khochan.hangsx, khochan.nuocsx, khochan.price AS cost, tatcakhoa.product_lot_id, tatcakhoa.exp_date, DAY(tatcakhoa.exp_date) AS dayexp, MONTH(tatcakhoa.exp_date) AS monthexp, YEAR(tatcakhoa.exp_date) AS yearexp, taikhoa.*, tatcakhoa.available_number AS number     
                FROM care_pharma_available_department AS taikhoa, care_pharma_available_product AS tatcakhoa, care_pharma_products_main AS  khochan, care_pharma_unit_of_medicine AS donvi 
                WHERE taikhoa.available_product_id=tatcakhoa.available_product_id 
					" . $dept_ward . " 
                    AND khochan.product_encoder=tatcakhoa.product_encoder 
                    AND donvi.unit_of_medicine=khochan.unit_of_medicine 
					" . $condition . "
                ORDER BY exp_date";
        //echo $this->sql;	
        if ($this->result = $db->Execute($this->sql)) {
            if ($this->result->RecordCount()) {
                return $this->result;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function countExpCabinet($dept_nr, $ward_nr, $condition)
    {
        global $db;
        $dept_ward = '';
        if ($dept_nr != '')
            $dept_ward = " AND taikhoa.department='" . $dept_nr . "' ";
        if ($ward_nr != '')
            $dept_ward .= " AND taikhoa.ward_nr='" . $ward_nr . "' ";

        $this->sql = "SELECT count(DISTINCT khochan.product_name) numthuoc
                FROM care_pharma_available_department AS taikhoa, care_pharma_available_product AS tatcakhoa, care_pharma_products_main AS  khochan, care_pharma_unit_of_medicine AS donvi
                WHERE taikhoa.available_product_id=tatcakhoa.available_product_id
					" . $dept_ward . "
                    AND khochan.product_encoder=tatcakhoa.product_encoder
                    AND donvi.unit_of_medicine=khochan.unit_of_medicine
					" . $condition . "
                ORDER BY exp_date";
        echo $this->sql;
        if ($this->result = $db->Execute($this->sql)) {
            $rowItem = $this->result->FetchRow();
            return $rowItem['numthuoc'];
        } else {
            return 0;
        }
    }

    function ShowCatalogCabinet($dept_nr, $ward_nr, $condition, $current_page, $number_items_per_page, $updown,$typeMedicine)
    {
        global $db;
        $dept_ward = '';
        if ($dept_nr != '')
            $dept_ward .= " AND taikhoa.department='" . $dept_nr . "' ";
        if ($ward_nr != '')
            $dept_ward .= " AND taikhoa.ward_nr='" . $ward_nr . "' ";
//        if ($current_page != '' && $number_items_per_page != '') {
//            $start_from = ($current_page - 1) * $number_items_per_page;
//            $limit_number = 'LIMIT ' . $start_from . ', ' . $number_items_per_page;
//        }
        //tong hop thuoc de xem tong so luong nen group theo ten thuoc
        if ($typeMedicine == 'tamthan')
            $typeMedicine = " AND khochan.product_name LIKE 'Diazepam%' ";
        else
            $typeMedicine = " AND khochan.product_name NOT LIKE 'Diazepam%' ";

        $this->sql = "SELECT
		    khochan.product_name,
              donvi.unit_name_of_medicine,
              khochan.product_encoder,
              khochan.sodangky,
              tatcakhoa.product_lot_id,
              tatcakhoa.exp_date,
              taikhoa.department,
            taikhoa.ward_nr,
            sum(taikhoa.available_number) tonkho,
            taikhoa.init_number,
            taikhoa.typeput,
            (SELECT SUM(x1.number_receive)
	FROM care_pharma_issue_paper x1,care_pharma_issue_paper_info x1info
	WHERE x1.issue_paper_id = x1info.issue_paper_id
	AND DATE_FORMAT(x1info.date_time_create,'%Y-%m-%d') ='".date('Y-m-d')."'
	AND x1.product_encoder = khochan.product_encoder ) nhanvekhoa
                FROM care_pharma_available_department AS taikhoa, care_pharma_available_product AS tatcakhoa, care_pharma_products_main AS  khochan, care_pharma_unit_of_medicine AS donvi
                WHERE taikhoa.available_product_id=tatcakhoa.available_product_id 
					" . $dept_ward . " 
                    AND khochan.product_encoder=tatcakhoa.product_encoder 
                    AND donvi.unit_of_medicine=khochan.unit_of_medicine
                      	" . $condition . "

                        $typeMedicine
                      	GROUP BY khochan.product_encoder, taikhoa.ward_nr,taikhoa.typeput
                ORDER BY ward_nr, khochan.product_name, taikhoa.init_number " . $updown . "
				";
        //echo $this->sql;
        if ($this->result = $db->Execute($this->sql)) {
            if ($this->result->RecordCount()) {
                return $this->result;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function SearchCatalogCabinet($dept_nr, $ward_nr, $condition, $updown)
    {
        global $db;
        $dept_ward = '';
        if ($dept_nr != '')
            $dept_ward = " AND taikhoa.department='" . $dept_nr . "' ";
        if ($ward_nr != '')
            $dept_ward .= " AND taikhoa.ward_nr='" . $ward_nr . "' ";

        $this->sql = "SELECT DISTINCT khochan.product_name, donvi.unit_name_of_medicine, khochan.product_encoder, khochan.sodangky, tatcakhoa.product_lot_id, tatcakhoa.exp_date , taikhoa.*   
                FROM care_pharma_available_department AS taikhoa, care_pharma_available_product AS tatcakhoa, care_pharma_products_main AS  khochan, care_pharma_unit_of_medicine AS donvi 
                WHERE taikhoa.available_product_id=tatcakhoa.available_product_id 
					" . $dept_ward . " 
                    AND khochan.product_encoder=tatcakhoa.product_encoder 
                    AND donvi.unit_of_medicine=khochan.unit_of_medicine 
					" . $condition . "
                ORDER BY khochan.product_name, taikhoa.available_number " . $updown;
        echo $this->sql;
        if ($this->result = $db->Execute($this->sql)) {
            if ($this->result->RecordCount()) {
                return $this->result;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function ShowCatalogKhoLe($current_page, $number_items_per_page, $condition = '')
    {
        global $db;
        if ($current_page != '' && $number_items_per_page != '') {
            $start_from = ($current_page - 1) * $number_items_per_page;
            $limit_number = 'LIMIT ' . $start_from . ', ' . $number_items_per_page;
        }
        $this->sql = "SELECT DISTINCT khochan.product_name, khochan.sodangky, khochan.nuocsx, donvi.unit_name_of_medicine, khochan.product_encoder, khochan.sodangky, khochan.hangsx, khochan.nuocsx, khochan.care_supplier, khole.* 
					FROM care_pharma_available_product AS khole, care_pharma_products_main AS khochan, care_pharma_unit_of_medicine AS donvi
					WHERE khochan.product_encoder=khole.product_encoder 
						AND donvi.unit_of_medicine=khochan.unit_of_medicine 
						AND khole.available_number>0 	
						" . $condition . " 	
					ORDER BY khochan.product_name, khole.exp_date
					" . $limit_number;

        if ($this->result = $db->Execute($this->sql)) {
            if ($this->result->RecordCount()) {
                return $this->result;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function SearchCatalogKhoLe($condition)
    {
        global $db;

        $this->sql = "SELECT DISTINCT khochan.product_name, khochan.sodangky, khochan.nuocsx, donvi.unit_name_of_medicine, khochan.product_encoder, khochan.care_supplier, khole.* 
					FROM care_pharma_available_product AS khole, care_pharma_products_main AS khochan, care_pharma_unit_of_medicine AS donvi
					WHERE khochan.product_encoder=khole.product_encoder 
						AND donvi.unit_of_medicine=khochan.unit_of_medicine 
						AND khole.available_number>0 	
						" . $condition . " 
					ORDER BY khochan.product_name";
        //echo $this->sql;	
        if ($this->result = $db->Execute($this->sql)) {
            if ($this->result->RecordCount()) {
                return $this->result;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function ShowCatalogKhoChan($typedongtayy, $current_page, $number_items_per_page, $condition = '')
    {
        global $db;
        if ($current_page != '' && $number_items_per_page != '') {
            $start_from = ($current_page - 1) * $number_items_per_page;
            $limit_number = 'LIMIT ' . $start_from . ', ' . $number_items_per_page;
        }
        $this->sql = "SELECT DISTINCT khochan.product_name, khochan.sodangky, khochan.nuocsx, donvi.unit_name_of_medicine, khochan.product_encoder, khochan.hangsx, khochan.nuocsx, khochan.care_supplier, sub.* 
					FROM care_pharma_products_main_sub AS sub, care_pharma_products_main AS khochan, care_pharma_unit_of_medicine AS donvi
					WHERE khochan.product_encoder=sub.product_encoder 
						AND donvi.unit_of_medicine=khochan.unit_of_medicine " . $typedongtayy . "
						AND sub.number>0 
						 " . $condition . " 	
					ORDER BY khochan.product_name 
					" . $limit_number;

        if ($this->result = $db->Execute($this->sql)) {
            if ($this->result->RecordCount()) {
                return $this->result;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function SearchCatalogKhoChan($condition)
    {
        global $db;

        $this->sql = "SELECT DISTINCT khochan.product_name, khochan.sodangky, khochan.nuocsx, donvi.unit_name_of_medicine, khochan.product_encoder, khochan.care_supplier, sub.* 
					FROM care_pharma_products_main_sub AS sub, care_pharma_products_main AS khochan, care_pharma_unit_of_medicine AS donvi
					WHERE khochan.product_encoder=sub.product_encoder 
						AND donvi.unit_of_medicine=khochan.unit_of_medicine 
						AND sub.number>0 	
						" . $condition . " 
					ORDER BY khochan.product_name";
        //echo $this->sql;	
        if ($this->result = $db->Execute($this->sql)) {
            if ($this->result->RecordCount()) {
                return $this->result;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function ShowKhoChanThuoc_Ton($typedongtayy, $current_page, $number_items_per_page, $condition = '', $todate)
    {
        global $db;
        if ($current_page != '' && $number_items_per_page != '') {
            $start_from = ($current_page - 1) * $number_items_per_page;
            $limit_number = 'LIMIT ' . $start_from . ', ' . $number_items_per_page;
        }
        switch ($typedongtayy) {
            case 'tayy':
                $tbl_ton = ' care_pharma_khochan_ton ';
                $tbl_ton_info = ' care_pharma_khochan_ton_info ';
                break;
            case 'dongy':
                $tbl_ton = ' care_pharma_khochan_dongy_ton ';
                $tbl_ton_info = ' care_pharma_khochan_dongy_ton_info ';
                break;
        }
        $this->sql = "SELECT khochan.product_name, khochan.sodangky, khochan.nuocsx, donvi.unit_name_of_medicine, khochan.product_encoder, khochan.hangsx, khochan.nuocsx, khochan.care_supplier, source.* 
					FROM " . $tbl_ton . " AS source, " . $tbl_ton_info . " AS sourceinfo,
						care_pharma_products_main AS khochan, care_pharma_unit_of_medicine AS donvi
					WHERE khochan.product_encoder=source.product_encoder 
						AND source.ton_id=sourceinfo.id
						AND sourceinfo.todate='$todate'
						AND donvi.unit_of_medicine=khochan.unit_of_medicine 
						AND source.number>0 
						 " . $condition . " 	
					ORDER BY khochan.product_name 
					" . $limit_number;
        //echo $this->sql;	
        if ($this->result = $db->Execute($this->sql)) {
            if ($this->result->RecordCount()) {
                return $this->result;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function SearchKhoChanThuoc_Ton($typedongtayy, $condition, $todate)
    {
        global $db;
        switch ($typedongtayy) {
            case 'tayy':
                $tbl_ton = ' care_pharma_khochan_ton ';
                $tbl_ton_info = ' care_pharma_khochan_ton_info ';
                break;
            case 'dongy':
                $tbl_ton = ' care_pharma_khochan_dongy_ton ';
                $tbl_ton_info = ' care_pharma_khochan_dongy_ton_info ';
                break;
        }
        $this->sql = "SELECT khochan.product_name, khochan.sodangky, khochan.nuocsx, donvi.unit_name_of_medicine, khochan.product_encoder, khochan.care_supplier, source.* 
					FROM " . $tbl_ton . " AS source, " . $tbl_ton_info . " AS sourceinfo, 
						care_pharma_products_main AS khochan, care_pharma_unit_of_medicine AS donvi
					WHERE khochan.product_encoder=source.product_encoder 
						AND source.ton_id=subinfo.id
						AND subinfo.todate='$todate' 
						AND donvi.unit_of_medicine=khochan.unit_of_medicine 
						AND source.number>0 	
						" . $condition . " 
					ORDER BY khochan.product_name";
        //echo $this->sql;	
        if ($this->result = $db->Execute($this->sql)) {
            if ($this->result->RecordCount()) {
                return $this->result;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function ShowNumberCatalogKhoChan($dongtayy, $current_page, $number_items_per_page, $updown)
    {
        global $db;
        if ($current_page != '' && $number_items_per_page != '') {
            $start_from = ($current_page - 1) * $number_items_per_page;
            $limit_number = 'LIMIT ' . $start_from . ', ' . $number_items_per_page;
        }

        $this->sql = "SELECT khochan.product_name, khochan.sodangky, donvi.unit_name_of_medicine, khochan.product_encoder, khochan.care_supplier, source.*, SUM(source.number) AS numbersum
					FROM care_pharma_products_main_sub AS source, care_pharma_products_main AS khochan, care_pharma_unit_of_medicine AS donvi
					WHERE khochan.product_encoder=source.product_encoder 
						AND donvi.unit_of_medicine=khochan.unit_of_medicine " . $dongtayy . "	
					GROUP BY source.product_encoder, source.price, source.exp_date
					ORDER BY numbersum " . $updown . "
					" . $limit_number;

        if ($this->result = $db->Execute($this->sql)) {
            if ($this->result->RecordCount()) {
                return $this->result;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function ShowNumberCatalogKhoChan_OrderByName($dongtayy, $current_page, $number_items_per_page)
    {
        global $db;
        if ($current_page != '' && $number_items_per_page != '') {
            $start_from = ($current_page - 1) * $number_items_per_page;
            $limit_number = 'LIMIT ' . $start_from . ', ' . $number_items_per_page;
        }

        $this->sql = "SELECT khochan.product_name, khochan.sodangky, donvi.unit_name_of_medicine, khochan.product_encoder, khochan.care_supplier, source.*, SUM(source.number) AS numbersum
					FROM care_pharma_products_main_sub AS source, care_pharma_products_main AS khochan, care_pharma_unit_of_medicine AS donvi
					WHERE khochan.product_encoder=source.product_encoder
						AND donvi.unit_of_medicine=khochan.unit_of_medicine " . $dongtayy . "
					GROUP BY source.product_encoder, source.price, source.exp_date
					ORDER BY khochan.product_name
					" . $limit_number;
        //echo $this->sql;
        if ($this->result = $db->Execute($this->sql)) {
            if ($this->result->RecordCount()) {
                return $this->result;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function SearchNumberCatalogKhoChan($condition, $updown)
    {
        global $db;

        $this->sql = "SELECT khochan.product_name, khochan.sodangky, donvi.unit_name_of_medicine, khochan.product_encoder, khochan.care_supplier, sub.*, SUM(sub.number) AS numbersum
					FROM care_pharma_products_main_sub AS sub, care_pharma_products_main AS khochan, care_pharma_unit_of_medicine AS donvi
					WHERE khochan.product_encoder=sub.product_encoder 
						AND donvi.unit_of_medicine=khochan.unit_of_medicine 	
						" . $condition . " 
					GROUP BY sub.product_encoder, sub.price, sub.exp_date
					ORDER BY numbersum " . $updown;
        //echo $this->sql;
        if ($this->result = $db->Execute($this->sql)) {
            if ($this->result->RecordCount()) {
                return $this->result;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function SearchNumberCatalogKhoChan_OrderByName($condition)
    {
        global $db;

        $this->sql = "SELECT khochan.product_name, khochan.sodangky, donvi.unit_name_of_medicine, khochan.product_encoder, khochan.care_supplier, sub.*, SUM(sub.number) AS numbersum
					FROM care_pharma_products_main_sub AS sub, care_pharma_products_main AS khochan, care_pharma_unit_of_medicine AS donvi
					WHERE khochan.product_encoder=sub.product_encoder
						AND donvi.unit_of_medicine=khochan.unit_of_medicine
						" . $condition . "
					GROUP BY sub.product_encoder, sub.price, sub.exp_date
					ORDER BY khochan.product_name ";
        //echo $this->sql;
        if ($this->result = $db->Execute($this->sql)) {
            if ($this->result->RecordCount()) {
                return $this->result;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    //VTYT
    function ShowExpMedipotCabinet($dept_nr, $ward_nr, $current_page, $number_items_per_page)
    {
        global $db;
        $dept_ward = '';
        if ($dept_nr != '')
            $dept_ward = " AND taikhoa.department='" . $dept_nr . "' ";
        if ($ward_nr != '')
            $dept_ward .= " AND taikhoa.ward_nr='" . $ward_nr . "' ";
        if ($current_page != '' && $number_items_per_page != '') {
            $start_from = ($current_page - 1) * $number_items_per_page;
            $limit_number = 'LIMIT ' . $start_from . ', ' . $number_items_per_page;
        }

        $this->sql = "SELECT DISTINCT khochan.product_name, khochan.sodangky, donvi.unit_name_of_medicine, khochan.product_encoder, tatcakhoa.product_lot_id,  DAY(tatcakhoa.exp_date) AS dayexp, MONTH(tatcakhoa.exp_date) AS monthexp, YEAR(tatcakhoa.exp_date) AS yearexp, taikhoa.available_number   
                FROM care_med_available_department AS taikhoa, care_med_available_product AS tatcakhoa, care_med_products_main AS  khochan, care_med_unit_of_medipot AS donvi, care_ward 
                WHERE taikhoa.available_product_id=tatcakhoa.available_product_id 
					" . $dept_ward . " 
                    AND khochan.product_encoder=tatcakhoa.product_encoder 
                    AND donvi.unit_of_medicine=khochan.unit_of_medicine 
                    AND taikhoa.available_number>0 	
                ORDER BY exp_date 
				" . $limit_number;
        if ($this->result = $db->Execute($this->sql)) {
            if ($this->result->RecordCount()) {
                return $this->result;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function SearchKhoChanVTYT_Ton($condition, $todate)
    {
        global $db;

        $this->sql = "SELECT khochan.product_name, khochan.sodangky, khochan.nuocsx, donvi.unit_name_of_medicine, khochan.product_encoder, khochan.care_supplier, sub.* 
					FROM care_med_khochan_ton AS sub, care_med_khochan_ton_info AS subinfo, 
						care_med_products_main AS khochan, care_med_unit_of_medipot AS donvi
					WHERE khochan.product_encoder=sub.product_encoder 
						AND sub.ton_id=subinfo.id
						AND subinfo.todate='$todate' 
						AND donvi.unit_of_medicine=khochan.unit_of_medicine 
						AND sub.number>0 	
						" . $condition . " 
					ORDER BY khochan.product_name";
        //echo $this->sql;
        if ($this->result = $db->Execute($this->sql)) {
            if ($this->result->RecordCount()) {
                return $this->result;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function ShowKhoChanVTYT_Ton($current_page, $number_items_per_page, $condition = '', $todate)
    {
        global $db;
        if ($current_page != '' && $number_items_per_page != '') {
            $start_from = ($current_page - 1) * $number_items_per_page;
            $limit_number = 'LIMIT ' . $start_from . ', ' . $number_items_per_page;
        }
        $this->sql = "SELECT khochan.product_name, khochan.sodangky, khochan.nuocsx, donvi.unit_name_of_medicine, khochan.product_encoder, khochan.hangsx, khochan.nuocsx, khochan.care_supplier, sub.* 
					FROM care_med_khochan_ton AS sub, care_med_khochan_ton_info AS subinfo, 
						care_med_products_main AS khochan, care_med_unit_of_medipot AS donvi
					WHERE khochan.product_encoder=sub.product_encoder 
						AND sub.ton_id=subinfo.id
						AND subinfo.todate='$todate'
						AND donvi.unit_of_medicine=khochan.unit_of_medicine 
						AND sub.number>0 
						 " . $condition . " 	
					ORDER BY khochan.product_name 
					" . $limit_number;

        if ($this->result = $db->Execute($this->sql)) {
            if ($this->result->RecordCount()) {
                return $this->result;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function SearchExpMedipotCabinet($dept_nr, $ward_nr, $condition)
    {
        global $db;
        $dept_ward = '';
        if ($dept_nr != '')
            $dept_ward = " AND taikhoa.department='" . $dept_nr . "' ";
        if ($ward_nr != '')
            $dept_ward .= " AND taikhoa.ward_nr='" . $ward_nr . "' ";

        $this->sql = "SELECT DISTINCT khochan.product_name, khochan.sodangky, donvi.unit_name_of_medicine, khochan.product_encoder, khochan.price AS cost, tatcakhoa.product_lot_id, tatcakhoa.exp_date, DAY(tatcakhoa.exp_date) AS dayexp, MONTH(tatcakhoa.exp_date) AS monthexp, YEAR(tatcakhoa.exp_date) AS yearexp, taikhoa.*,taikhoa.available_number AS number     
                FROM care_med_available_department AS taikhoa, care_med_available_product AS tatcakhoa, care_med_products_main AS  khochan, care_med_unit_of_medipot AS donvi, care_ward 
                WHERE taikhoa.available_product_id=tatcakhoa.available_product_id 
					" . $dept_ward . " 
                    AND khochan.product_encoder=tatcakhoa.product_encoder 
                    AND donvi.unit_of_medicine=khochan.unit_of_medicine 
					" . $condition . "
                ORDER BY exp_date";
        //echo $this->sql;	
        if ($this->result = $db->Execute($this->sql)) {
            if ($this->result->RecordCount()) {
                return $this->result;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function ShowCatalogMedipotCabinet($dept_nr, $ward_nr,$condition, $current_page, $number_items_per_page, $updown)
    {
        global $db;
        $dept_ward = '';
        if ($dept_nr != '')
            $dept_ward = " AND taikhoa.department='" . $dept_nr . "' ";
        if ($ward_nr != '')
            $dept_ward .= " AND taikhoa.ward_nr='" . $ward_nr . "' ";
//        if ($current_page != '' && $number_items_per_page != '') {
//            $start_from = ($current_page - 1) * $number_items_per_page;
//            $limit_number = 'LIMIT ' . $start_from . ', ' . $number_items_per_page;
//        }

//        $this->sql = "SELECT DISTINCT khochan.product_name, khochan.sodangky, donvi.unit_name_of_medicine, khochan.product_encoder, tatcakhoa.product_lot_id, tatcakhoa.exp_date, taikhoa.*
//                FROM care_med_available_department AS taikhoa, care_med_available_product AS tatcakhoa, care_med_products_main AS  khochan, care_med_unit_of_medipot AS donvi, care_ward
//                WHERE taikhoa.available_product_id=tatcakhoa.available_product_id
//					" . $dept_ward . "
//                    AND khochan.product_encoder=tatcakhoa.product_encoder
//                    AND donvi.unit_of_medicine=khochan.unit_of_medicine
//
//                ORDER BY taikhoa.init_number  " . $updown . "
//				" . $limit_number;
        $this->sql = "SELECT
		    khochan.product_name,
              donvi.unit_name_of_medicine,
              khochan.product_encoder,
              khochan.sodangky,
              tatcakhoa.lotid,
              tatcakhoa.exp_date,
              taikhoa.department,
            taikhoa.ward_nr,
            sum(taikhoa.available_number) tonkho,
            taikhoa.init_number,
            taikhoa.typeput,
            (SELECT SUM(x1.number_receive)
	FROM care_med_issue_paper x1,care_med_issue_paper_info x1info
	WHERE x1.issue_paper_id = x1info.issue_paper_id
	AND DATE_FORMAT(x1info.date_time_create,'%Y-%m-%d') ='".date('Y-m-d')."'
	AND x1.product_encoder = khochan.product_encoder ) nhanvekhoa
                FROM care_med_available_department AS taikhoa, care_med_products_main_sub1 AS tatcakhoa, care_med_products_main AS  khochan, care_med_unit_of_medipot AS donvi
                WHERE taikhoa.available_product_id=tatcakhoa.id
					" . $dept_ward . "
                    AND khochan.product_encoder=tatcakhoa.product_encoder
                    AND donvi.unit_of_medicine=khochan.unit_of_medicine
                      	" . $condition . "
                      	GROUP BY khochan.product_encoder, taikhoa.ward_nr,taikhoa.typeput
                ORDER BY ward_nr, khochan.product_name, taikhoa.init_number " . $updown . "";
        if ($this->result = $db->Execute($this->sql)) {
            if ($this->result->RecordCount()) {
                return $this->result;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function SearchCatalogMedipotCabinet($dept_nr, $ward_nr, $condition, $updown)
    {
        global $db;
        $dept_ward = '';
        if ($dept_nr != '')
            $dept_ward = " AND taikhoa.department='" . $dept_nr . "' ";
        if ($ward_nr != '')
            $dept_ward .= " AND taikhoa.ward_nr='" . $ward_nr . "' ";

        $this->sql = "SELECT DISTINCT khochan.product_name, khochan.sodangky, donvi.unit_name_of_medicine, khochan.product_encoder, tatcakhoa.product_lot_id, tatcakhoa.exp_date , taikhoa.*   
                FROM care_med_available_department AS taikhoa, care_med_available_product AS tatcakhoa, care_med_products_main AS khochan, care_med_unit_of_medipot AS donvi, care_ward 
                WHERE taikhoa.available_product_id=tatcakhoa.available_product_id 
					" . $dept_ward . " 
                    AND khochan.product_encoder=tatcakhoa.product_encoder 
                    AND donvi.unit_of_medicine=khochan.unit_of_medicine 
					" . $condition . "
                ORDER BY taikhoa.available_number " . $updown;
        //echo $this->sql;	
        if ($this->result = $db->Execute($this->sql)) {
            if ($this->result->RecordCount()) {
                return $this->result;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function ShowCatalogMedipotKhoLe($current_page, $number_items_per_page, $condition = '')
    {
        global $db;
        if ($current_page != '' && $number_items_per_page != '') {
            $start_from = ($current_page - 1) * $number_items_per_page;
            $limit_number = 'LIMIT ' . $start_from . ', ' . $number_items_per_page;
        }
        $this->sql = "SELECT DISTINCT khochan.product_name, khochan.sodangky, khochan.nuocsx, donvi.unit_name_of_medicine, khochan.product_encoder, khochan.care_supplier, khole.* 
					FROM care_med_available_product AS khole, care_med_products_main AS khochan, care_med_unit_of_medipot AS donvi
					WHERE khochan.product_encoder=khole.product_encoder 
						AND donvi.unit_of_medicine=khochan.unit_of_medicine 
						AND khole.available_number>0 	
					" . $condition . " 			
					ORDER BY khochan.product_name 
					" . $limit_number;

        if ($this->result = $db->Execute($this->sql)) {
            if ($this->result->RecordCount()) {
                return $this->result;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function SearchCatalogMedipotKhoLe($condition)
    {
        global $db;

        $this->sql = "SELECT DISTINCT khochan.product_name, khochan.sodangky, khochan.nuocsx, donvi.unit_name_of_medicine, khochan.product_encoder, khochan.care_supplier, khole.* 
					FROM care_med_available_product AS khole, care_med_products_main AS khochan, care_med_unit_of_medipot AS donvi
					WHERE khochan.product_encoder=khole.product_encoder 
						AND donvi.unit_of_medicine=khochan.unit_of_medicine 
						AND khole.available_number>0 	
						" . $condition . " 
					ORDER BY khochan.product_name";
        //echo $this->sql;	
        if ($this->result = $db->Execute($this->sql)) {
            if ($this->result->RecordCount()) {
                return $this->result;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function ShowCatalogMedipotKhoChan($current_page, $number_items_per_page, $condition)
    {
        global $db;
        if ($current_page != '' && $number_items_per_page != '') {
            $start_from = ($current_page - 1) * $number_items_per_page;
            $limit_number = 'LIMIT ' . $start_from . ', ' . $number_items_per_page;
        }
        $this->sql = "SELECT DISTINCT khochan.product_name, khochan.sodangky, khochan.nuocsx, donvi.unit_name_of_medicine, khochan.product_encoder, khochan.care_supplier, sub.* 
					FROM care_med_products_main_sub1 AS sub, care_med_products_main AS khochan, care_med_unit_of_medipot AS donvi
					WHERE khochan.product_encoder=sub.product_encoder 
						AND donvi.unit_of_medicine=khochan.unit_of_medicine 
						AND sub.number>0 	
						" . $condition . " 	
					ORDER BY khochan.product_name 
					" . $limit_number;

        if ($this->result = $db->Execute($this->sql)) {
            if ($this->result->RecordCount()) {
                return $this->result;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function SearchCatalogMedipotKhoChan($condition)
    {
        global $db;

        $this->sql = "SELECT DISTINCT khochan.product_name, khochan.sodangky, khochan.nuocsx, donvi.unit_name_of_medicine, khochan.product_encoder, khochan.care_supplier, sub.* 
					FROM care_med_products_main_sub1 AS sub, care_med_products_main AS khochan, care_med_unit_of_medipot AS donvi
					WHERE khochan.product_encoder=sub.product_encoder 
						AND donvi.unit_of_medicine=khochan.unit_of_medicine 
						AND sub.number>0 	
						" . $condition . " 
					ORDER BY khochan.product_name";
        //echo $this->sql;	
        if ($this->result = $db->Execute($this->sql)) {
            if ($this->result->RecordCount()) {
                return $this->result;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function ShowNumberCatalogMedipotKhoChan($current_page, $number_items_per_page, $updown)
    {
        global $db;
        if ($current_page != '' && $number_items_per_page != '') {
            $start_from = ($current_page - 1) * $number_items_per_page;
            $limit_number = 'LIMIT ' . $start_from . ', ' . $number_items_per_page;
        }
        $this->sql = "SELECT DISTINCT khochan.product_name, khochan.sodangky, donvi.unit_name_of_medicine, khochan.product_encoder, khochan.care_supplier, sub.* 
					FROM care_med_products_main_sub1 AS sub, care_med_products_main AS khochan, care_med_unit_of_medipot AS donvi
					WHERE khochan.product_encoder=sub.product_encoder 
						AND donvi.unit_of_medicine=khochan.unit_of_medicine 
					ORDER BY khochan.product_name, sub.number " . $updown . "
					" . $limit_number;

        if ($this->result = $db->Execute($this->sql)) {
            if ($this->result->RecordCount()) {
                return $this->result;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function SearchNumberCatalogMedipotKhoChan($condition, $updown)
    {
        global $db;

        $this->sql = "SELECT DISTINCT khochan.product_name, khochan.sodangky, donvi.unit_name_of_medicine, khochan.product_encoder, khochan.care_supplier, sub.* 
					FROM care_med_products_main_sub1 AS sub, care_med_products_main AS khochan, care_med_unit_of_medipot AS donvi
					WHERE khochan.product_encoder=sub.product_encoder 
						AND donvi.unit_of_medicine=khochan.unit_of_medicine 	
						" . $condition . " 
					ORDER BY sub.number " . $updown;
        //echo $this->sql;	
        if ($this->result = $db->Execute($this->sql)) {
            if ($this->result->RecordCount()) {
                return $this->result;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function PharmaAvailProductId($product_encoder, $product_lot_id)
    {
        global $db;
        $this->sql = "SELECT COUNT(available_product_id) AS records
                            FROM care_pharma_available_product
                            WHERE product_encoder='$product_encoder' AND product_lot_id='$product_lot_id'";
        $records = $db->Execute($this->sql)->FetchRow();
        $number = $records["records"];
        if ($number > 0) {
            $this->sql = "SELECT available_product_id
                                FROM care_pharma_available_product
                                WHERE product_encoder='$product_encoder' AND product_lot_id='$product_lot_id'";
            $records = $db->Execute($this->sql)->FetchRow();
            $availprodut_id = $records["available_product_id"];
            //echo($this->sql);
        }
        if ($number > 0) {
            $this->sql = "SELECT available_product_id
                                FROM care_pharma_available_product
                                WHERE product_encoder='$product_encoder' AND product_lot_id='$product_lot_id'";
            $records = $db->Execute($this->sql)->FetchRow();
            $availprodut_id = $records["available_product_id"];
            //echo($this->sql);
        } else {
            $this->sql = "SELECT available_product_id
                                FROM care_pharma_available_product
                                WHERE product_encoder='$product_encoder' AND product_lot_id='$product_lot_id';
                                SELECT MAX(available_product_id) AS available_product_id
                                FROM care_pharma_available_product;";
            $records = $db->Execute($this->sql)->FetchRow();
            $availprodut_id = $records["available_product_id"];
        }
        return ($availprodut_id);
    }

    function MedAvailProductId($product_encoder, $product_lot_id)
    {
        global $db;
        $this->sql = "SELECT COUNT(available_product_id) AS records
                            FROM care_med_available_product
                            WHERE product_encoder='$product_encoder' AND product_lot_id='$product_lot_id'";
        $records = $db->Execute($this->sql)->FetchRow();
        $number = $records["records"];
        if ($number > 0) {
            $this->sql = "SELECT available_product_id
                                FROM care_med_available_product
                                WHERE product_encoder='$product_encoder' AND product_lot_id='$product_lot_id'";
            $records = $db->Execute($this->sql)->FetchRow();
            $availprodut_id = $records["available_product_id"];
            //echo($this->sql);
        }
        if ($number > 0) {
            $this->sql = "SELECT available_product_id
                                FROM care_med_available_product
                                WHERE product_encoder='$product_encoder' AND product_lot_id='$product_lot_id'";
            $records = $db->Execute($this->sql)->FetchRow();
            $availprodut_id = $records["available_product_id"];
            //echo($this->sql);
        } else {
            $this->sql = "SELECT available_product_id
                                FROM care_med_available_product
                                WHERE product_encoder='$product_encoder' AND product_lot_id='$product_lot_id';
                                SELECT MAX(available_product_id) AS available_product_id
                                FROM care_med_available_product;";
            $records = $db->Execute($this->sql)->FetchRow();
            $availprodut_id = $records["available_product_id"];
        }
        return ($availprodut_id);
    }

    function Issue_Paper_Info()
    {
        global $db;
        $this->sql = "SELECT DAY(date_time_create), MONTH(date_time_create), YEAR(date_time_create), issue_paper_id, care_ward.name
                            FROM care_pharma_issue_paper_info, care_ward
                            WHERE care_pharma_issue_paper_info.ward_nr=care_ward.nr
                            AND status_finish=0;";
        //echo($this->sql);
        return ($db->Execute($this->sql));
    }

    function Pres_Info()
    {
        global $db;
        $this->sql = "SELECT DAY(date_time_create), MONTH(date_time_create), YEAR(date_time_create), prescription_id, name_first, name_last
                            FROM care_pharma_prescription_info, care_encounter, care_person
                            WHERE care_pharma_prescription_info.encounter_nr=care_encounter.encounter_nr
                            AND care_encounter.pid=care_person.pid
                            AND status_finish=0;";
        //echo($this->sql);
        return ($db->Execute($this->sql));
    }

    /*
	 * Thang - 2014/06/07
	 * Lay 1 lot_id tuong ung voi product_encoder, so luong yeu cau va loai thuoc
	 * Tra ve lot_id, ton kho cua lot_id va gia tien
	 */
    function getLastLotID($encoder, $typeput)
    {
        global $db;
        $this->sql = "SELECT product_encoder, product_lot_id, available_number, price,available_product_id
					FROM care_pharma_available_product
					WHERE product_encoder='$encoder'
					AND available_number > 0 AND typeput='" . $typeput . "'
					AND product_lot_id IS NOT NULL
					ORDER BY exp_date,available_product_id
					LIMIT 1";
//        echo $this->sql;
        if ($this->result = $db->Execute($this->sql)) {
            $n = $this->result->RecordCount();
            if ($n) {
                return $lotid = $this->result->FetchRow();
            }
        }
        return null;
    }
    function getMedLastLotID($encoder, $typeput)
    {
        global $db;
        $this->sql = "SELECT care_med_products_main.product_encoder, lotid, number, care_med_products_main_sub1.price, care_med_products_main_sub1.id
					FROM care_med_products_main,care_med_products_main_sub1
					WHERE care_med_products_main.product_encoder='$encoder' AND care_med_products_main.product_encoder= care_med_products_main_sub1.product_encoder
					AND number > 0 AND typeput='" . $typeput . "'
					AND lotid IS NOT NULL
					ORDER BY exp_date
					LIMIT 1";
//        echo $this->sql;
        if ($this->result = $db->Execute($this->sql)) {
            $n = $this->result->RecordCount();
            if ($n) {
                return $lotid = $this->result->FetchRow();
            }
        }
        return null;
    }
    /*------------------------------------------ Tuyen ---------------------------------------------*/
    /*	cung 1 loai thuoc, co nhieu lo (lot_id), uu tien lay lo nao nhap truoc
		input: encode, number of medicine 
		output:  list lot_id with number, priority to use first_lotid
		pharma_avai_product
	*/
    function getListLotID($encoder, $number, $typeput)
    {
        global $db;
        $this->sql = "SELECT product_encoder, product_lot_id, available_number 
					FROM care_pharma_available_product 
					WHERE product_encoder='$encoder'
					AND available_number>0 AND typeput='" . $typeput . "'
					ORDER BY exp_date";
        if ($this->result = $db->Execute($this->sql)) {
            $n = $this->result->RecordCount();
            if ($n) {
                $list_lotid = array();
                for ($i = 0; $i < $n; $i++) {
                    $lotid = $this->result->FetchRow();
                    if ($lotid['available_number'] < $number) {
                        $list_lotid = array($lotid['product_lot_id'] => $lotid['available_number']);
                        $number = $number - $lotid['available_number'];
                    } else {
//						$list_lotid =array($lotid['product_lot_id'] =>$number);
                        $list_lotid[($lotid['product_lot_id'])] = $number;
                        break;

                    }
                }
                return $list_lotid;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }


    function getListLotIDMedipot($encoder, $number, $typeput)
    {
        global $db;
        $this->sql = "SELECT product_encoder, product_lot_id, available_number 
					FROM care_med_available_product 
					WHERE product_encoder='$encoder'
					AND available_number>0 AND typeput='" . $typeput . "' 
					ORDER BY available_product_id";
        if ($this->result = $db->Execute($this->sql)) {
            $n = $this->result->RecordCount();
            if ($n) {
                $list_lotid = array();
                for ($i = 0; $i < $n; $i++) {
                    $lotid = $this->result->FetchRow();
                    if ($lotid['available_number'] < $number) {
                        $list_lotid = array($lotid['product_lot_id'] => $lotid['available_number']);
                        $number = $number - $lotid['available_number'];
                    } else {
//						$list_lotid = array($lotid['product_lot_id'] => $number);
                        $list_lotid[($lotid['product_lot_id'])] = $number;
                        break;
                    }
                }
                return $list_lotid;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function getListLotIDMedipot_KhoChan($encoder, $number, $typeput)
    {
        global $db;
        $this->sql = "SELECT product_encoder, lotid, number 
					FROM care_med_products_main_sub1 
					WHERE product_encoder='$encoder' 
					AND number>0 AND typeput='" . $typeput . "' 
					ORDER BY id";
        if ($this->result = $db->Execute($this->sql)) {
            $n = $this->result->RecordCount();
            if ($n) {
                for ($i = 0; $i < $n; $i++) {
                    $lotid = $this->result->FetchRow();
                    if ($lotid['number'] < $number) {
                        $list_lotid = array($lotid['lotid'] => $lotid['number']);
                        $number = $number - $lotid['number'];
                    } else {
                        $list_lotid = array($lotid['lotid'] => $number);
                        break;
                    }
                }
                return $list_lotid;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function updateMedicineProductMain($encoder, $number, $cal)
    {
        global $db;
        if ($encoder == '') return FALSE;
        $this->sql = "UPDATE care_pharma_products_main
						SET available_number=available_number" . $cal . "'$number' 
						WHERE product_encoder='$encoder'";
        //echo $this->sql;				
        return $this->Transact($this->sql);
    }

    function updateMedipotProductMain($encoder, $number, $cal)
    {
        global $db;
        if ($encoder == '') return FALSE;
        $this->sql = "UPDATE care_med_products_main
						SET available_number=available_number" . $cal . "'$number' 
						WHERE product_encoder='$encoder'";
        //echo $this->sql;				
        return $this->Transact($this->sql);
    }

    //Avai_Product
    function checkExistMedicineInAvaiProduct($encoder, $lotid, $typeput)
    {
        global $db;
        $this->sql = "SELECT *
                    FROM care_pharma_available_product 
                    WHERE product_encoder='$encoder' AND product_lot_id='$lotid' AND typeput='$typeput'";
        if ($this->result = $db->Execute($this->sql)) {
            if ($this->result->RecordCount()) {
                return $this->result->FetchRow();
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    function updateMedicineAvaiProductByAvailID( $available_product_id, $number, $cal)
    {
        global $db;
        if ($available_product_id == '') return FALSE;
        $this->sql = "UPDATE care_pharma_available_product
					SET available_number=available_number" . $cal . "'$number'
					WHERE available_product_id='$available_product_id'";
        return $this->Transact($this->sql);
    }

    function updateMedipotAvaiProductByAvailID( $available_product_id, $number, $cal)
    {
        global $db;
        if ($available_product_id == '') return FALSE;
        $this->sql = "UPDATE care_med_available_product
					SET available_number=available_number" . $cal . "'$number'
					WHERE available_product_id='$available_product_id'";
        return $this->Transact($this->sql);
    }
    function updateMedicineAvaiProduct($encoder, $lotid, $number, $cal, $typeput)
    {
        global $db;
        if ($encoder == '') return FALSE;
        $this->sql = "UPDATE care_pharma_available_product
					SET available_number=available_number" . $cal . "'$number' 
					WHERE product_encoder='$encoder' AND product_lot_id='$lotid' AND typeput='" . $typeput . "'";
        return $this->Transact($this->sql);
    }

    function updateMedicineAvaiProduct_ToaThuoc($encoder, $price, $number, $cal, $typeput)
    {
        global $db;
        if ($encoder == '') return FALSE;
        $this->sql = "UPDATE care_pharma_available_product
					SET available_number=available_number" . $cal . "'$number' 
					WHERE product_encoder='$encoder' AND price='$price' AND typeput='" . $typeput . "'";
        //echo $this->sql;
        return $this->Transact($this->sql);
    }

    function InsertMedicineInAvaiProduct($product_encoder, $lotid, $product_date, $exp_date, $number, $price, $typeput)
    {
        global $db;
        $this->sql = "INSERT INTO care_pharma_available_product(product_encoder, product_lot_id, typeput, product_date, exp_date, available_number, price)		
		VALUES ('$product_encoder', '$lotid', '$typeput', '$product_date', '$exp_date', '$number', '$price')";
        return $this->Transact($this->sql);
    }

    function checkExistMedipotInAvaiProduct($encoder, $lotid, $typeput)
    {
        global $db;
        $this->sql = "SELECT *
                    FROM care_med_available_product 
                    WHERE product_encoder='$encoder' AND product_lot_id='$lotid' AND typeput='$typeput'";
        if ($this->result = $db->Execute($this->sql)) {
            if ($this->result->RecordCount()) {
                return $this->result->FetchRow();
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function updateMedipotAvaiProduct($encoder, $lotid, $number, $cal, $typeput)
    {
        global $db;
        if ($encoder == '') return FALSE;
        $this->sql = "UPDATE care_med_available_product
					SET available_number=available_number" . $cal . "'$number' 
					WHERE product_encoder='$encoder' AND product_lot_id='$lotid' AND typeput='" . $typeput . "'";
        return $this->Transact($this->sql);
    }

    function InsertMedipotInAvaiProduct($product_encoder, $lotid, $product_date, $exp_date, $number, $price, $typeput)
    {
        global $db;
        $this->sql = "INSERT INTO care_med_available_product(product_encoder, product_lot_id, typeput, product_date, exp_date, available_number, price)		
		VALUES ('$product_encoder', '$lotid', '$typeput', '$product_date', '$exp_date', '$number', '$price')";
        return $this->Transact($this->sql);
    }

    //Main_Sub
    function checkExistMedicineInMainSub($encoder, $lotid, $typeput)
    {
        global $db;
        $this->sql = "SELECT *
                    FROM care_pharma_products_main_sub
                    WHERE product_encoder='$encoder' AND lotid='$lotid' AND typeput='$typeput'";
        if ($this->result = $db->Execute($this->sql)) {
            if ($this->result->RecordCount()) {
                return $this->result->FetchRow();
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function UpdateMedicineInMainSub($encoder, $lotid, $number, $cost, $cal, $typeput)
    {
        global $db;
        if ($encoder == '') return FALSE;
        if ($cost == 0 || $cost == '')
            $condition_cost = '';
        else $condition_cost = " , price='$cost' ";

        $this->sql = "UPDATE care_pharma_products_main_sub
					SET number=number" . $cal . "'$number' " . $condition_cost . "  
					WHERE product_encoder='$encoder' AND lotid='$lotid' AND typeput='$typeput'";
        return $this->Transact($this->sql);
    }

    function InsertMedicineInMainSub($product_encoder, $lotid, $product_date, $exp_date, $number, $price, $typeput)
    {
        global $db;
        $this->sql = "INSERT INTO care_pharma_products_main_sub (product_encoder, lotid, typeput, product_date, exp_date, number, price)		
		VALUES ('$product_encoder', '$lotid', '$typeput', '$product_date', '$exp_date', '$number', '$price')";
        //echo($this->sql);
        return $this->Transact($this->sql);
    }

    function checkExistMedipotInMainSub($encoder, $lotid, $typeput)
    {
        global $db;
        $this->sql = "SELECT *
                    FROM care_med_products_main_sub1
                    WHERE product_encoder='$encoder' AND lotid='$lotid'  AND typeput='$typeput'";
        if ($this->result = $db->Execute($this->sql)) {
            if ($this->result->RecordCount()) {
                return $this->result->FetchRow();
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function UpdateMedipotInMainSub($available_product_id, $number, $cal)
    {
        global $db;
        if ($available_product_id == '') return FALSE;
//        if ($cost == 0 || $cost == '')
//            $condition_cost = '';
//        else $condition_cost = " , price='$cost' ";
//        if ($lotid == '') return FALSE;
        $this->sql = "UPDATE care_med_products_main_sub1
					SET number=number" . $cal . "'$number'
					WHERE id='$available_product_id'";
        return $this->Transact($this->sql);
    }

    function InsertMedipotInMainSub($product_encoder, $lotid, $product_date, $exp_date, $number, $price, $typeput)
    {
        global $db;
        $this->sql = "INSERT INTO care_med_products_main_sub1 (product_encoder, lotid, typeput, product_date, exp_date, number, price)		
		VALUES ('$product_encoder', '$lotid', '$typeput', '$product_date', '$exp_date', '$number', '$price')";
        //echo($this->sql);
        return $this->Transact($this->sql);
    }

    //**********************************************************************************************
    //**********************************************************************************************
    //Hoa chat-Huynh
    //**********************************************************************************************
    //**********************************************************************************************
    function GetChemicalCatalogue($quick, $current_page, $total_records, $records_in_page, $before, $font_before, $font_after, $after, $before2, $after2, $general_info, $catalogue, $group_key, $attribute)
    {
        global $db;
        $begin = ($current_page - 1) * $records_in_page; //khởi tạo kết quả đầu tiên của trang ban đầu =0
        $catalogue_info = $this->GetAllChemicalInfo($quick, $begin, &$total_records, $records_in_page, $group_key, $attribute);
        if (is_object($catalogue_info)) {
            $i = 0;
            $sCatalogueInfo = "";
            while ($object = $catalogue_info->FetchRow()) {
                $i++;
                if ($i % 2 != 0)
                    $sCatalogueInfo = $sCatalogueInfo . $before . "'$catalogue'" . ",'" . $object["product_encoder"] . "','" . $current_page . "')" . $font_before . $object['product_name'] . $font_after . $after;
                else
                    $sCatalogueInfo = $sCatalogueInfo . $before2 . "'$catalogue'" . ",'" . $object["product_encoder"] . "','" . $current_page . "')" . $font_before . $object['product_name'] . $font_after . $after2;
            }
        }
        //var_dump($sCatalogueInfo);
        return $sCatalogueInfo;
    }

    function GetAllChemicalInfo($quick, $begin, $total_records, $records_in_page, $group_key, $attribute)
    {
        global $db;
        if ($group_key != '') {
            $cond = " , care_chemical_group  
                WHERE care_chemical_products_main.chemical_generic_drug_id=care_chemical_group.chemical_group_id AND care_chemical_products_main.chemical_generic_drug_id='$group_key'";
            if ($quick != '') {
                $cond .= " AND (product_name LIKE '" . $quick . "%' or product_name LIKE '% " . $quick . "%')";
            }
        }
        if ($quick != '' && $group_key == '') {
            $cond = " WHERE (product_name LIKE '" . $quick . "%' or product_name LIKE '% " . $quick . "%')";
        }
        $records = $db->Execute("SELECT COUNT(*) AS records FROM care_chemical_products_main $cond ")->FetchRow();
        $total_records = $records["records"];
        $this->sql = "SELECT product_encoder, product_name FROM care_chemical_products_main " . $cond . " ORDER BY product_name LIMIT $begin, $records_in_page";

        return ($db->Execute($this->sql));
    }

    //lấy thông tin của kết quả tìm kiếm đầu tiên
    function GetFirstChemicalCatalogue($quick, $group_key, $attribute)
    {
        global $db;
        $catalogue_info = $this->GetAllChemicalInfo($quick, 0, $total_records, 1, $group_key, $attribute);
        if (is_object($catalogue_info)) {
            $object = $catalogue_info->FetchRow();
            $obj = $object["product_encoder"];
        }
        return $this->GetChemicalInfo($obj, '');
    }

    //hàm lấy 1 đối tượng trong danh mục 
    function GetChemicalInfo($product_encoder, $user)
    {
        global $db;
        $this->sql = "SELECT product_encoder, product_name, 
                                care_chemical_products_main.using_type,care_chemical_products_main.unit_of_chemical, sodangky, hangsx, nuocsx,
                                caution, care_chemical_group.chemical_group_name,care_chemical_products_main.chemical_generic_drug_id,
                                care_chemical_products_main.care_supplier, 
                                supplier_name,care_currency.short_name,
                                care_chemical_products_main.note, 
                                price, unit_of_price, available_number, unit_name_of_chemical, 
                                care_chemical_products_main.in_use,
                                care_chemical_products_main.description
                        FROM care_chemical_products_main, care_currency, care_supplier, care_chemical_unit_of_medicine, care_chemical_group
                        WHERE product_encoder='$product_encoder' 
                        AND care_chemical_products_main.unit_of_price=care_currency.item_no 
                        AND care_chemical_products_main.unit_of_chemical= care_chemical_unit_of_medicine.unit_of_chemical
                        AND care_chemical_products_main.care_supplier=care_supplier.supplier
                        AND care_chemical_products_main.chemical_generic_drug_id=care_chemical_group.chemical_group_id";
        //short_name,
        return ($db->Execute($this->sql));
    }

    //hàm lấy select box nhóm thuốc
    function GetChemicalGroupName($group_key)
    {
        global $db;
        if ($group_key == '')
            $this->sql = "SELECT chemical_group_id, chemical_group_name
                                FROM care_chemical_group";
        else
            $this->sql = "SELECT chemical_group_id, chemical_group_name
                            FROM care_chemical_group
                            WHERE chemical_group_id=$group_key";
        return ($db->Execute($this->sql));
    }

    function GetChemicalGroup()
    {
        global $db;
        $this->sql = "SELECT chemical_group_id, chemical_group_name
                FROM care_chemical_group";

        return ($db->Execute($this->sql));
    }

    function GetChemicalNameGroup($id = '')
    {
        global $db;
        $this->sql = "SELECT chemical_group_name
                        FROM care_chemical_group WHERE chemical_group_id=$id";
//            echo $this->sql;
        if ($result = $db->Execute($this->sql)) {
            if ($query = $result->RecordCount()) {
                return $result->FetchRow();
            }
            return FALSE;
        }
        return FALSE;
    }

    function EditChemical($product_encoder, $product_encoder_before, $product_name, $using_type, $unit_of_chemical, $caution, $care_supplier, $note, $price, $unit_of_price, $description, $history, $id_group, $sodangky, $hangsx, $nuocsx)
    {
        global $db;
        $this->sql = "UPDATE care_chemical_products_main
                    SET product_encoder='$product_encoder',
                    product_name='$product_name', unit_of_chemical='$unit_of_chemical', caution='$caution',
                    chemical_generic_drug_id='$id_group',
                    care_supplier='$care_supplier',note='$note',price='$price', 
                    unit_of_price='$unit_of_price', description='$description', history='$history', sodangky='$sodangky', hangsx='$hangsx', nuocsx='$nuocsx'  
                    WHERE product_encoder='$product_encoder_before'";
        return $this->Transact($this->sql);
    }

    function DeleteChemical($product_encoder)
    {
        global $db;
        $this->sql = "DELETE FROM care_chemical_products_main
					WHERE product_encoder='$product_encoder'";
        return $this->Transact($this->sql);
    }

    //hàm lấy 1 số thông tin cơ bản của nhiều đối tượng
    function GetAllChemicalInfos($quick, $begin, $total_records, $records_in_page, $group_key, $attribute, $dept_nr)
    {
        global $db;
        if ($quick != '') {
            $cond = "WHERE (product_name LIKE '" . $quick . "%' or product_name LIKE '% " . $quick . "%') and ";
        }
        if ($group_key == '') {
            $records = $db->Execute("SELECT COUNT(*) AS records FROM care_pharma_products_main $cond ")->FetchRow();
            $total_records = $records["records"];
            $this->sql = "SELECT care_chemical_products_main.product_encoder, product_name, price, care_chemical_unit_of_medicine.unit_name_of_chemical, product_lot_id, DAY(exp_date), MONTH(exp_date), YEAR(exp_date)
                            FROM care_chemical_products_main, care_chemical_unit_of_medicine, care_chemical_available_product, care_chemical_available_department
                            WHERE " . $cond . "
                            AND care_chemical_unit_of_medicine.unit_of_chemical=care_chemical_products_main.unit_of_chemical
                            AND care_chemical_products_main.product_encoder=care_chemical_available_product.product_encoder
                            AND care_chemical_available_product.available_product_id=care_chemical_available_department.available_product_id
                            AND care_chemical_available_department.department=$dept_nr
                            AND care_chemical_available_product.exp_date IN
                            (
                                    SELECT MAX(exp_date)
                                    FROM care_chemical_available_product
                                    WHERE care_chemical_products_main.product_encoder=product_encoder
                            )
                            ORDER BY product_name LIMIT $begin, $records_in_page";

        }

        return ($db->Execute($this->sql));
    }

    function AddChemical($product_encoder, $product_name, $group_of_medipot_input, $unit_name_of_chemical_input, $caution, $supplier, $note, $price, $unit_of_price, $in_use, $description, $user, $sodangky, $hangsx, $nuocsx)
    {
        global $db;
        $this->sql = "INSERT INTO care_chemical_products_main (
                        product_encoder, 
                        product_name, 
                        chemical_generic_drug_id, 
                        unit_of_chemical, 
                        caution, 
                        care_supplier, 
                        note, price, 
                        unit_of_price, 
                        in_use, 
                        description, 
                        chemical_type,
						sodangky, hangsx, nuocsx)
                    VALUES ('', 
                            '$product_name', 
                            '$group_of_medipot_input', 
                            '$unit_name_of_chemical_input', 
                            '$caution', 
                            '$supplier', 
                            '$note', 
                            '$price', 
                            '$unit_of_price', 
                            1, 
                            '$description', 
                            5, '$sodangky', '$hangsx', '$nuocsx')";
        return ($db->Execute($this->sql));
    }

    //hàm lấy select box dạng thuốc
    function GetChemicalType()
    {
        global $db;
        $this->sql = "SELECT type_of_chemical, type_name_of_chemical
                    FROM care_chemical_type_of_medicine";

        return ($db->Execute($this->sql));
    }

    //đơn vị tính (thuốc)
    function GetChemicalUnit()
    {
        global $db;
        $this->sql = "SELECT unit_of_chemical, unit_name_of_chemical
                    FROM care_chemical_unit_of_medicine";

        return ($db->Execute($this->sql));
    }

    //search kho lẻ
    function SearchChemicalCatalogKhoLe($condition)
    {
        global $db;

        $this->sql = "SELECT DISTINCT khochan.product_name, khochan.sodangky, donvi.unit_name_of_chemical, khochan.product_encoder, khochan.care_supplier, khole.* 
                        FROM care_chemical_available_product AS khole, care_chemical_products_main AS khochan, care_chemical_unit_of_medicine AS donvi
                        WHERE khochan.product_encoder=khole.product_encoder 
                                AND donvi.unit_of_chemical=khochan.unit_of_chemical
                                AND khole.available_number>0 	
                                " . $condition . " 
                        ORDER BY khochan.product_name";

        if ($this->result = $db->Execute($this->sql)) {
            if ($this->result->RecordCount()) {
                return $this->result;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function updateChemicalProductMain($encoder, $number, $cal)
    {
        global $db;
        if ($encoder == '') return FALSE;
        $this->sql = "UPDATE care_chemical_products_main
                        SET available_number=available_number" . $cal . "'$number' 
                        WHERE product_encoder='$encoder'";
//	echo $this->sql;		
        return $this->Transact($this->sql);
    }

    //Main_Sub
    function checkExistChemicalInLotid($encoder, $lotid, $typeput)
    {
        global $db;
        $this->sql = "SELECT *
                    FROM care_chemical_products_main_sub
                    WHERE product_encoder='$encoder' AND lotid='$lotid' AND typeput='$typeput'";

        if ($this->result = $db->Execute($this->sql)) {
            if ($this->result->RecordCount()) {
                return $this->result->FetchRow();
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function UpdateChemicalInPutIn($encoder, $typeput, $vat, $lotid, $number, $cost, $cal)
    {
        global $db;
        if ($encoder == '') return FALSE;
        if ($cost == 0 || $cost == '')
            $condition_cost = '';
        else $condition_cost = " , price='$cost' ";

        $this->sql = "UPDATE care_chemical_products_main_sub
                    SET number=number" . $cal . "'$number' " . $condition_cost . "                        
                    WHERE product_encoder='$encoder' AND lotid='$lotid'";

        return $this->Transact($this->sql);
    }

    function InsertChemicalInPutIn($product_encoder, $typeput, $vat, $lotid, $product_date, $exp_date, $number, $price)
    {
        global $db;
        $this->sql = "INSERT INTO care_chemical_products_main_sub (product_encoder, typeput, vat, lotid, product_date, exp_date, number, price)		
		VALUES ('$product_encoder', '$typeput', '$vat', '$lotid', '$product_date', '$exp_date', '$number', '$price')";

        return $this->Transact($this->sql);
    }

    function UpdateChemicalInMainSub($encoder, $lotid, $number, $cost, $cal, $typeput)
    {
        global $db;
        if ($encoder == '') return FALSE;
        if ($cost == 0 || $cost == '')
            $condition_cost = '';
        else $condition_cost = " , price='$cost' ";

        $this->sql = "UPDATE care_chemical_products_main_sub
                    SET number=number" . $cal . "'$number' " . $condition_cost . "  
                    WHERE product_encoder='$encoder' AND lotid='$lotid' AND typeput='$typeput'";

        return $this->Transact($this->sql);
    }

    function InsertChemicalInMainSub($product_encoder, $lotid, $product_date, $exp_date, $number, $price, $typeput)
    {
        global $db;
        $this->sql = "INSERT INTO care_chemical_products_main_sub (product_encoder, lotid, typeput, product_date, exp_date, number, price)		
		VALUES ('$product_encoder', '$lotid', '$typeput', '$product_date', '$exp_date', '$number', '$price')";
        return $this->Transact($this->sql);
    }

    //Avai_Product
    function checkExistChemicalInAvaiProduct($encoder, $lotid, $typeput)
    {
        global $db;
        $this->sql = "SELECT *
                        FROM care_chemical_available_product 
                        WHERE product_encoder='$encoder' AND product_lot_id='$lotid' AND typeput='$typeput'";
//            echo $this->sql;
        if ($this->result = $db->Execute($this->sql)) {
            if ($this->result->RecordCount()) {
                return $this->result->FetchRow();
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function updateChemicalAvaiProduct($encoder, $lotid, $number, $cal, $typeput)
    {
        global $db;
        if ($encoder == '') return FALSE;
        $this->sql = "UPDATE care_chemical_available_product
                    SET available_number=available_number" . $cal . "'$number' 
                    WHERE product_encoder='$encoder' AND product_lot_id='$lotid' AND typeput='$typeput' ";
//        echo $this->sql;
        return $this->Transact($this->sql);
    }

    function InsertChemicalInAvaiProduct($product_encoder, $lotid, $product_date, $exp_date, $number, $price, $typeput)
    {
        global $db;
        $this->sql = "INSERT INTO care_chemical_available_product(product_encoder, product_lot_id, typeput, product_date, exp_date, available_number, price)		
            VALUES ('$product_encoder', '$lotid', '$typeput', '$product_date', '$exp_date', '$number', '$price')";
        return $this->Transact($this->sql);
    }

    function getListChemicalLotID($encoder, $number, $typeput)
    {
        global $db;
        $this->sql = "SELECT product_encoder, product_lot_id, available_number 
                        FROM care_chemical_available_product 
                        WHERE product_encoder='$encoder'
                        AND available_number>0 AND typeput='" . $typeput . "' 
                        ORDER BY available_product_id";
        if ($this->result = $db->Execute($this->sql)) {
            $n = $this->result->RecordCount();
            if ($n) {
                for ($i = 0; $i < $n; $i++) {
                    $lotid = $this->result->FetchRow();
                    if ($lotid['available_number'] < $number) {
                        $list_lotid = array($lotid['product_lot_id'] => $lotid['available_number']);
                        $number = $number - $lotid['available_number'];
                    } else {
                        $list_lotid = array($lotid['product_lot_id'] => $number);
                        break;
                    }
                }
                return $list_lotid;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function SearchNumberChemicalCatalogKhoChan($condition, $updown)
    {
        global $db;
        $this->sql = "SELECT DISTINCT khochan.product_name, khochan.sodangky, donvi.unit_name_of_chemical, khochan.product_encoder, khochan.care_supplier, sub.*, 
                            grp.chemical_group_name AS group_name
                        FROM care_chemical_products_main_sub AS sub, care_chemical_products_main AS khochan, care_chemical_unit_of_medicine AS donvi, care_chemical_group AS grp
                        WHERE khochan.product_encoder=sub.product_encoder 
                        AND donvi.unit_of_chemical=khochan.unit_of_chemical 
                        AND grp.chemical_group_id=khochan.chemical_generic_drug_id
                        " . $condition . " 
                        ORDER BY sub.number " . $updown;
//        echo $this->sql;//currency.short_name,
        if ($this->result = $db->Execute($this->sql)) {
            if ($this->result->RecordCount()) {
                return $this->result;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function ShowNumberChemicalCatalogKhoChan($current_page, $number_items_per_page, $updown)
    {
        global $db;
        if ($current_page != '' && $number_items_per_page != '') {
            $start_from = ($current_page - 1) * $number_items_per_page;
            $limit_number = 'LIMIT ' . $start_from . ', ' . $number_items_per_page;
        }
        $this->sql = "SELECT DISTINCT khochan.product_name, khochan.sodangky, donvi.unit_name_of_chemical, khochan.product_encoder, khochan.care_supplier, sub.* 
                    FROM care_chemical_products_main_sub AS sub, care_chemical_products_main AS khochan, care_chemical_unit_of_medicine AS donvi
                    WHERE khochan.product_encoder=sub.product_encoder 
                            AND donvi.unit_of_chemical=khochan.unit_of_chemical 	
                    ORDER BY sub.number " . $updown . " 
                    " . $limit_number;

        if ($this->result = $db->Execute($this->sql)) {
            if ($this->result->RecordCount()) {
                return $this->result;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function SearchCatalogChemicalKhoChan($condition)
    {
        global $db;

        $this->sql = "SELECT DISTINCT khochan.product_name, khochan.sodangky, khochan.nuocsx, donvi.unit_name_of_chemical, khochan.product_encoder, khochan.care_supplier, sub.*
                        FROM care_chemical_products_main_sub AS sub, care_chemical_products_main AS khochan, care_chemical_unit_of_medicine AS donvi
                        WHERE khochan.product_encoder=sub.product_encoder 
                        AND donvi.unit_of_chemical=khochan.unit_of_chemical
                        AND sub.number>0 	
                        " . $condition . " 
                        ORDER BY khochan.product_name";
//        echo $this->sql;//, currency.short_name
        if ($this->result = $db->Execute($this->sql)) {
            if ($this->result->RecordCount()) {
                return $this->result;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function ShowCatalogChemicalKhoChan($current_page, $number_items_per_page, $condition = '')
    {
        global $db;
        if ($current_page != '' && $number_items_per_page != '') {
            $start_from = ($current_page - 1) * $number_items_per_page;
            $limit_number = 'LIMIT ' . $start_from . ', ' . $number_items_per_page;
        }
        $this->sql = "SELECT DISTINCT khochan.product_name, khochan.sodangky, khochan.nuocsx, donvi.unit_name_of_chemical, khochan.product_encoder, khochan.care_supplier, sub.*
                        FROM care_chemical_products_main_sub AS sub, care_chemical_products_main AS khochan, care_chemical_unit_of_medicine AS donvi
                        WHERE khochan.product_encoder=sub.product_encoder 
                        AND donvi.unit_of_chemical=khochan.unit_of_chemical 
						 " . $condition . " 
                        AND sub.number>0 	
                        ORDER BY khochan.product_name 
                        " . $limit_number;
//        echo $this->sql;//, currency.short_name 
        if ($this->result = $db->Execute($this->sql)) {
            if ($this->result->RecordCount()) {
                return $this->result;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function SearchExpCabinetChemical($dept_nr, $ward_nr, $condition)
    {
        global $db;
        $dept_ward = '';
        if ($dept_nr != '')
            $dept_ward = " AND taikhoa.department='" . $dept_nr . "' ";
        if ($ward_nr != '')
            $dept_ward .= " AND taikhoa.ward_nr='" . $ward_nr . "' ";

        $this->sql = "SELECT DISTINCT khochan.product_name, khochan.sodangky, donvi.unit_name_of_chemical, khochan.product_encoder,
                    khochan.price AS cost, tatcakhoa.product_lot_id, tatcakhoa.exp_date, DAY(tatcakhoa.exp_date) AS dayexp, 
                    MONTH(tatcakhoa.exp_date) AS monthexp, YEAR(tatcakhoa.exp_date) AS yearexp, taikhoa.*, taikhoa.available_number AS number     
                    FROM care_chemical_available_department AS taikhoa, care_chemical_available_product AS tatcakhoa, care_chemical_products_main AS  khochan, care_chemical_unit_of_medicine AS donvi 
                    WHERE taikhoa.available_product_id=tatcakhoa.available_product_id " . $dept_ward . " 
                    AND khochan.product_encoder=tatcakhoa.product_encoder 
                    AND donvi.unit_of_chemical=khochan.unit_of_chemical 
                    " . $condition . "
                    ORDER BY exp_date";
//        echo $this->sql;
        if ($this->result = $db->Execute($this->sql)) {
            if ($this->result->RecordCount()) {
                return $this->result;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function SearchCatalogChemicalCabinet($dept_nr, $ward_nr, $condition, $updown)
    {
        global $db;
        $dept_ward = '';
        if ($dept_nr != '')
            $dept_ward = " AND taikhoa.department='" . $dept_nr . "' ";
        if ($ward_nr != '')
            $dept_ward .= " AND taikhoa.ward_nr='" . $ward_nr . "' ";

        $this->sql = "SELECT DISTINCT khochan.product_name, khochan.sodangky, donvi.unit_name_of_chemical, khochan.product_encoder,
                        tatcakhoa.product_lot_id, tatcakhoa.exp_date , taikhoa.*   
                    FROM care_chemical_available_department AS taikhoa, care_chemical_available_product AS tatcakhoa, 
                        care_chemical_products_main AS  khochan, care_chemical_unit_of_medicine AS donvi 
                    WHERE taikhoa.available_product_id=tatcakhoa.available_product_id 
                    " . $dept_ward . " 
                    AND khochan.product_encoder=tatcakhoa.product_encoder 
                    AND donvi.unit_of_chemical=khochan.unit_of_chemical 
                    " . $condition . "
                    ORDER BY taikhoa.available_number " . $updown;

        if ($this->result = $db->Execute($this->sql)) {
            if ($this->result->RecordCount()) {
                return $this->result;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function ShowCatalogChemicalCabinet($dept_nr, $ward_nr, $current_page, $number_items_per_page, $updown)
    {
        global $db;
        $dept_ward = '';
        if ($dept_nr != '')
            $dept_ward = " AND taikhoa.department='" . $dept_nr . "' ";
        if ($ward_nr != '')
            $dept_ward .= " AND taikhoa.ward_nr='" . $ward_nr . "' ";
        if ($current_page != '' && $number_items_per_page != '') {
            $start_from = ($current_page - 1) * $number_items_per_page;
            $limit_number = 'LIMIT ' . $start_from . ', ' . $number_items_per_page;
        }

        $this->sql = "SELECT DISTINCT khochan.product_name, khochan.sodangky, donvi.unit_name_of_chemical, khochan.product_encoder, 
                        tatcakhoa.product_lot_id, tatcakhoa.exp_date, taikhoa.*    
                    FROM care_chemical_available_department AS taikhoa, care_chemical_available_product AS tatcakhoa, 
                        care_chemical_products_main AS  khochan, care_chemical_unit_of_medicine AS donvi 
                    WHERE taikhoa.available_product_id=tatcakhoa.available_product_id 
                    " . $dept_ward . " 
                    AND khochan.product_encoder=tatcakhoa.product_encoder 
                    AND donvi.unit_of_chemical=khochan.unit_of_chemical  	
                    ORDER BY taikhoa.init_number  " . $updown . " 
                    " . $limit_number;
        if ($this->result = $db->Execute($this->sql)) {
            if ($this->result->RecordCount()) {
                return $this->result;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function ShowExpCabinetChemical($dept_nr, $ward_nr, $current_page, $number_items_per_page)
    {
        global $db;
        $dept_ward = '';
        if ($dept_nr != '')
            $dept_ward = " AND taikhoa.department='" . $dept_nr . "' ";
        if ($ward_nr != '')
            $dept_ward .= " AND taikhoa.ward_nr='" . $ward_nr . "' ";
        if ($current_page != '' && $number_items_per_page != '') {
            $start_from = ($current_page - 1) * $number_items_per_page;
            $limit_number = 'LIMIT ' . $start_from . ', ' . $number_items_per_page;
        }

        $this->sql = "SELECT DISTINCT khochan.product_name, khochan.sodangky, donvi.unit_name_of_chemical, khochan.product_encoder, 
                        tatcakhoa.product_lot_id,  DAY(tatcakhoa.exp_date) AS dayexp, MONTH(tatcakhoa.exp_date) AS monthexp, 
                        YEAR(tatcakhoa.exp_date) AS yearexp, taikhoa.available_number   
                    FROM care_chemical_available_department AS taikhoa, care_chemical_available_product AS tatcakhoa, 
                        care_chemical_products_main AS  khochan, care_chemical_unit_of_medicine AS donvi  
                    WHERE taikhoa.available_product_id=tatcakhoa.available_product_id 
                    " . $dept_ward . " 
                    AND khochan.product_encoder=tatcakhoa.product_encoder 
                    AND donvi.unit_of_chemical=khochan.unit_of_chemical 
                    AND taikhoa.available_number>0 	
                    ORDER BY exp_date 
                    " . $limit_number;
        if ($this->result = $db->Execute($this->sql)) {
            if ($this->result->RecordCount()) {
                return $this->result;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function SearchCatalogChemicalKhoLe($condition)
    {
        global $db;

        $this->sql = "SELECT DISTINCT khochan.product_name, khochan.sodangky, khochan.nuocsx, donvi.unit_name_of_chemical, khochan.product_encoder, khochan.care_supplier, khole.* 
                            FROM care_chemical_available_product AS khole, care_chemical_products_main AS khochan, care_chemical_unit_of_medicine AS donvi
                            WHERE khochan.product_encoder=khole.product_encoder 
                                    AND donvi.unit_of_chemical=khochan.unit_of_chemical 
                                    AND khole.available_number>0 	
                                    " . $condition . " 
                            ORDER BY khochan.product_name";

        if ($this->result = $db->Execute($this->sql)) {
            if ($this->result->RecordCount()) {
                return $this->result;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function ShowCatalogChemicalKhoLe($current_page, $number_items_per_page, $condition = '')
    {
        global $db;
        if ($current_page != '' && $number_items_per_page != '') {
            $start_from = ($current_page - 1) * $number_items_per_page;
            $limit_number = 'LIMIT ' . $start_from . ', ' . $number_items_per_page;
        }
        $this->sql = "SELECT DISTINCT khochan.product_name, khochan.sodangky, khochan.nuocsx, donvi.unit_name_of_chemical, khochan.product_encoder, khochan.care_supplier, khole.* 
                        FROM care_chemical_available_product AS khole, care_chemical_products_main AS khochan, care_chemical_unit_of_medicine AS donvi
                        WHERE khochan.product_encoder=khole.product_encoder 
                                AND donvi.unit_of_chemical=khochan.unit_of_chemical 
                                AND khole.available_number>0 	
						 " . $condition . " 		
                        ORDER BY khochan.product_name 
                        " . $limit_number;

        if ($this->result = $db->Execute($this->sql)) {
            if ($this->result->RecordCount()) {
                return $this->result;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function SearchKhoChanHC_Ton($condition, $todate)
    {
        global $db;

        $this->sql = "SELECT khochan.product_name, khochan.sodangky, khochan.nuocsx, donvi.unit_name_of_chemical, khochan.product_encoder, khochan.care_supplier, sub.* 
					FROM care_chemical_khochan_ton AS sub, care_chemical_khochan_ton_info AS subinfo, 
						care_chemical_products_main AS khochan, care_chemical_unit_of_medicine AS donvi
					WHERE khochan.product_encoder=sub.product_encoder 
						AND sub.ton_id=subinfo.id
						AND subinfo.todate='$todate' 
						AND donvi.unit_of_chemical=khochan.unit_of_chemical 
						AND sub.number>0 	
						" . $condition . " 
					ORDER BY khochan.product_name";
        //echo $this->sql;
        if ($this->result = $db->Execute($this->sql)) {
            if ($this->result->RecordCount()) {
                return $this->result;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function ShowKhoChanHC_Ton($current_page, $number_items_per_page, $condition = '', $todate)
    {
        global $db;
        if ($current_page != '' && $number_items_per_page != '') {
            $start_from = ($current_page - 1) * $number_items_per_page;
            $limit_number = 'LIMIT ' . $start_from . ', ' . $number_items_per_page;
        }
        $this->sql = "SELECT khochan.product_name, khochan.sodangky, khochan.nuocsx, donvi.unit_name_of_chemical, khochan.product_encoder, khochan.hangsx, khochan.nuocsx, khochan.care_supplier, sub.* 
					FROM care_chemical_khochan_ton AS sub, care_chemical_khochan_ton_info AS subinfo, 
						care_chemical_products_main AS khochan, care_chemical_unit_of_medicine AS donvi
					WHERE khochan.product_encoder=sub.product_encoder 
						AND sub.ton_id=subinfo.id
						AND subinfo.todate='$todate'
						AND donvi.unit_of_chemical=khochan.unit_of_chemical 
						AND sub.number>0 
						 " . $condition . " 	
					ORDER BY khochan.product_name 
					" . $limit_number;

        if ($this->result = $db->Execute($this->sql)) {
            if ($this->result->RecordCount()) {
                return $this->result;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }


}

?>
