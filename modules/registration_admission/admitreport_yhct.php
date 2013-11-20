<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('./roots.php');
require($root_path.'include/core/inc_environment_global.php');
$local_user='aufnahme_user';
require($root_path.'include/core/inc_front_chain_lang.php');

 require_once($root_path.'gui/smarty_template/smarty_care.class.php');
 $smarty = new smarty_care('common');

 $smarty->assign('sToolbarTitle',"Thống kê khoa Y Học Cổ Truyền");

 $smarty->assign('breakfile',$breakfile);

 //echo $datefrom;
 $str = "<center>BẢNG THEO DÕI THỦ THUẬT BỆNH NỘI TRÚ- NGOẠI TRÚ<br>
 		Ngày ".date("d/m/Y",strtotime($datefrom)).(($datefrom != $dateto)?" tới ngày ".date("d/m/Y",strtotime($dateto))."":"")."
 </center>";
 $sqlngt = "SELECT distinct(t1.encounter_nr),
t1.fname, t1.yearbirth, t1.address,t1.insurance_nr,t1.insurance_exp,
(select t2.datein from dfck_admit_inout_dept t2 where t2.dept_to = (select nr from care_department where id = 11) and t2.encounter_nr = t1.encounter_nr order by t2.nr  limit 0,1) datein,
t1.referrer_diagnosis,
(SELECT t2.datein from dfck_admit_inout_dept t2 where t2.dept_to<0 and t2.dept_from = t1.dept_to and t2.encounter_nr = t1.encounter_nr and t2.datein >= t1.datein) dateout,
(select sum(bill_item_units) FROM care_billing_bill_item where bill_item_encounter_nr=t1.encounter_nr and bill_item_code like '%YHCTDC%' and DATE_FORMAT(bill_item_date,'%Y-%m-%d') >= '$datefrom' AND DATE_FORMAT(bill_item_date,'%Y-%m-%d') <= '$dateto' ) sumdc,
(select sum(bill_item_units) FROM care_billing_bill_item where bill_item_encounter_nr=t1.encounter_nr and bill_item_code like '%YHCTCD%'  and DATE_FORMAT(bill_item_date,'%Y-%m-%d') >= '$datefrom' AND DATE_FORMAT(bill_item_date,'%Y-%m-%d') <= '$dateto'  ) sumcd,
(select sum(bill_item_units) FROM care_billing_bill_item where bill_item_encounter_nr=t1.encounter_nr and bill_item_code like '%YHCTQC%' and DATE_FORMAT(bill_item_date,'%Y-%m-%d') >= '$datefrom' AND DATE_FORMAT(bill_item_date,'%Y-%m-%d') <= '$dateto'  ) sumqc,
(select sum(bill_item_units) FROM care_billing_bill_item where bill_item_encounter_nr=t1.encounter_nr and bill_item_code like '%YHCTVLTL%'  and DATE_FORMAT(bill_item_date,'%Y-%m-%d') >= '$datefrom' AND DATE_FORMAT(bill_item_date,'%Y-%m-%d') <= '$dateto' ) sumvltl,
(select sum(bill_item_units) FROM care_billing_bill_item where bill_item_encounter_nr=t1.encounter_nr and bill_item_code like '%YHCTPA%' and DATE_FORMAT(bill_item_date,'%Y-%m-%d') >= '$datefrom' AND DATE_FORMAT(bill_item_date,'%Y-%m-%d') <= '$dateto'  ) sumpa
 from dfck_admit_inout_dept t1,care_billing_bill_item b WHERE 
 t1.type_encounter = 2 
					and ( (t1.dept_to= (select nr from care_department where id = 11) 
					AND ( DATE_FORMAT(t1.datein,'%Y-%m-%d') <= '$dateto' 
					and ( 
							(
								t1.status=1
								and ( select DATE_FORMAT(t3.datein,'%Y-%m-%d') 
								from dfck_admit_inout_dept t3 
								where dept_from=(select nr from care_department where id = 11)  
								and t3.encounter_nr=t1.encounter_nr and t3.nr>t1.nr ORDER BY t3.nr LIMIT 0,1
								)>='$datefrom' 
								and ( select DATE_FORMAT(t3.datein,'%Y-%m-%d') 
								from dfck_admit_inout_dept t3 
								where dept_from=(select nr from care_department where id = 11)  
								and t3.encounter_nr=t1.encounter_nr and t3.nr>t1.nr ORDER BY t3.nr LIMIT 0,1
								)<='$dateto' 
								
							) OR 
							(t1.status=0) 
						)
					) 
				)
				OR (DATE_FORMAT(t1.datein,'%Y-%m-%d') >= '$datefrom'  
					AND DATE_FORMAT(t1.datein,'%Y-%m-%d') <= '$dateto' 
					and t1.dept_from =  (select nr from care_department where id = 11) 
				)
				)
and t1.encounter_nr = b.bill_item_encounter_nr AND  b.bill_item_code like 'YHCT%'
and DATE_FORMAT(b.bill_item_date,'%Y-%m-%d') >= '$datefrom' AND DATE_FORMAT(b.bill_item_date,'%Y-%m-%d') <= '$dateto'
group by t1.encounter_nr
";//ma noi bo cua YHCT = 11
//echo $sqlngt;
/*

			
*/
$sqlsumngt = "SELECT 
			(SELECT COUNT(a.encounter_nr) FROM (
					select distinct t1.encounter_nr from dfck_admit_inout_dept t1 , care_billing_bill_item b 
					where b.bill_item_encounter_nr=t1.encounter_nr and b.bill_item_code like '%YHCT%'
					and DATE_FORMAT(b.bill_item_date,'%Y-%m-%d') >= '$datefrom' AND DATE_FORMAT(b.bill_item_date,'%Y-%m-%d') <= '$dateto'
					and t1.type_encounter = 2 
					and ( (t1.dept_to= (select nr from care_department where id = 11) 
					AND ( DATE_FORMAT(t1.datein,'%Y-%m-%d') <= '$dateto' 
					and ( 
							(
								t1.status=1
								and ( select DATE_FORMAT(t3.datein,'%Y-%m-%d') 
								from dfck_admit_inout_dept t3 
								where dept_from=(select nr from care_department where id = 11)  
								and t3.encounter_nr=t1.encounter_nr and t3.nr>t1.nr ORDER BY t3.nr LIMIT 0,1
								)>='$datefrom' 
								and ( select DATE_FORMAT(t3.datein,'%Y-%m-%d') 
								from dfck_admit_inout_dept t3 
								where dept_from=(select nr from care_department where id = 11)  
								and t3.encounter_nr=t1.encounter_nr and t3.nr>t1.nr ORDER BY t3.nr LIMIT 0,1
								)<='$dateto' 
								
							) OR 
							(t1.status=0) 
						)
					) 
				)
				OR (DATE_FORMAT(t1.datein,'%Y-%m-%d') >= '$datefrom'  
					AND DATE_FORMAT(t1.datein,'%Y-%m-%d') <= '$dateto' 
					and t1.dept_from =  (select nr from care_department where id = 11) 
				)
				)
			group by t1.encounter_nr) a 
			) sumtnt,
			(select sum(b.bill_item_units) from (select distinct t1.encounter_nr from dfck_admit_inout_dept t1 where  
				t1.type_encounter = 2 
					and ( (t1.dept_to= (select nr from care_department where id = 11) 
					AND ( DATE_FORMAT(t1.datein,'%Y-%m-%d') <= '$dateto' 
					and ( 
							(
								t1.status=1
								and ( select DATE_FORMAT(t3.datein,'%Y-%m-%d') 
								from dfck_admit_inout_dept t3 
								where dept_from=(select nr from care_department where id = 11)  
								and t3.encounter_nr=t1.encounter_nr and t3.nr>t1.nr ORDER BY t3.nr LIMIT 0,1
								)>='$datefrom' 
								and ( select DATE_FORMAT(t3.datein,'%Y-%m-%d') 
								from dfck_admit_inout_dept t3 
								where dept_from=(select nr from care_department where id = 11)  
								and t3.encounter_nr=t1.encounter_nr and t3.nr>t1.nr ORDER BY t3.nr LIMIT 0,1
								)<='$dateto' 
								
							) OR 
							(t1.status=0) 
						)
					) 
				)
				OR (DATE_FORMAT(t1.datein,'%Y-%m-%d') >= '$datefrom'  
					AND DATE_FORMAT(t1.datein,'%Y-%m-%d') <= '$dateto' 
					and t1.dept_from =  (select nr from care_department where id = 11) 
				)
				)
			group by t1.encounter_nr

				)a, care_billing_bill_item b where b.bill_item_encounter_nr=a.encounter_nr and b.bill_item_code like '%YHCTDC%' and DATE_FORMAT(b.bill_item_date,'%Y-%m-%d') >= '$datefrom' AND DATE_FORMAT(b.bill_item_date,'%Y-%m-%d') <= '$dateto') sumdc,
			(select sum(b.bill_item_units) from (select distinct t1.encounter_nr from dfck_admit_inout_dept t1 where  
				t1.type_encounter = 2 
					and ( (t1.dept_to= (select nr from care_department where id = 11) 
					AND ( DATE_FORMAT(t1.datein,'%Y-%m-%d') <= '$dateto' 
					and ( 
							(
								t1.status=1
								and ( select DATE_FORMAT(t3.datein,'%Y-%m-%d') 
								from dfck_admit_inout_dept t3 
								where dept_from=(select nr from care_department where id = 11)  
								and t3.encounter_nr=t1.encounter_nr and t3.nr>t1.nr ORDER BY t3.nr LIMIT 0,1
								)>='$datefrom' 
								and ( select DATE_FORMAT(t3.datein,'%Y-%m-%d') 
								from dfck_admit_inout_dept t3 
								where dept_from=(select nr from care_department where id = 11)  
								and t3.encounter_nr=t1.encounter_nr and t3.nr>t1.nr ORDER BY t3.nr LIMIT 0,1
								)<='$dateto' 
								
							) OR 
							(t1.status=0) 
						)
					) 
				)
				OR (DATE_FORMAT(t1.datein,'%Y-%m-%d') >= '$datefrom'  
					AND DATE_FORMAT(t1.datein,'%Y-%m-%d') <= '$dateto' 
					and t1.dept_from =  (select nr from care_department where id = 11) 
				)
				)
			group by t1.encounter_nr

				)a, care_billing_bill_item b where b.bill_item_encounter_nr=a.encounter_nr and b.bill_item_code like '%YHCTCD%' and DATE_FORMAT(b.bill_item_date,'%Y-%m-%d') >= '$datefrom' AND DATE_FORMAT(b.bill_item_date,'%Y-%m-%d') <= '$dateto' ) sumcd,
(select sum(b.bill_item_units) from (select distinct t1.encounter_nr from dfck_admit_inout_dept t1 where  
				t1.type_encounter = 2 
					and ( (t1.dept_to= (select nr from care_department where id = 11) 
					AND ( DATE_FORMAT(t1.datein,'%Y-%m-%d') <= '$dateto' 
					and ( 
							(
								t1.status=1
								and ( select DATE_FORMAT(t3.datein,'%Y-%m-%d') 
								from dfck_admit_inout_dept t3 
								where dept_from=(select nr from care_department where id = 11)  
								and t3.encounter_nr=t1.encounter_nr and t3.nr>t1.nr ORDER BY t3.nr LIMIT 0,1
								)>='$datefrom' 
								and ( select DATE_FORMAT(t3.datein,'%Y-%m-%d') 
								from dfck_admit_inout_dept t3 
								where dept_from=(select nr from care_department where id = 11)  
								and t3.encounter_nr=t1.encounter_nr and t3.nr>t1.nr ORDER BY t3.nr LIMIT 0,1
								)<='$dateto' 
								
							) OR 
							(t1.status=0) 
						)
					) 
				)
				OR (DATE_FORMAT(t1.datein,'%Y-%m-%d') >= '$datefrom'  
					AND DATE_FORMAT(t1.datein,'%Y-%m-%d') <= '$dateto' 
					and t1.dept_from =  (select nr from care_department where id = 11) 
				)
				)
			group by t1.encounter_nr

				)a, care_billing_bill_item b where b.bill_item_encounter_nr=a.encounter_nr and b.bill_item_code like '%YHCTQC%' and DATE_FORMAT(b.bill_item_date,'%Y-%m-%d') >= '$datefrom' AND DATE_FORMAT(b.bill_item_date,'%Y-%m-%d') <= '$dateto' ) sumqc,
(select sum(b.bill_item_units) from (select distinct t1.encounter_nr from dfck_admit_inout_dept t1 where  
				t1.type_encounter = 2 
					and ( (t1.dept_to= (select nr from care_department where id = 11) 
					AND ( DATE_FORMAT(t1.datein,'%Y-%m-%d') <= '$dateto' 
					and ( 
							(
								t1.status=1
								and ( select DATE_FORMAT(t3.datein,'%Y-%m-%d') 
								from dfck_admit_inout_dept t3 
								where dept_from=(select nr from care_department where id = 11)  
								and t3.encounter_nr=t1.encounter_nr and t3.nr>t1.nr ORDER BY t3.nr LIMIT 0,1
								)>='$datefrom' 
								and ( select DATE_FORMAT(t3.datein,'%Y-%m-%d') 
								from dfck_admit_inout_dept t3 
								where dept_from=(select nr from care_department where id = 11)  
								and t3.encounter_nr=t1.encounter_nr and t3.nr>t1.nr ORDER BY t3.nr LIMIT 0,1
								)<='$dateto' 
								
							) OR 
							(t1.status=0) 
						)
					) 
				)
				OR (DATE_FORMAT(t1.datein,'%Y-%m-%d') >= '$datefrom'  
					AND DATE_FORMAT(t1.datein,'%Y-%m-%d') <= '$dateto' 
					and t1.dept_from =  (select nr from care_department where id = 11) 
				)
				)
			group by t1.encounter_nr

				)a, care_billing_bill_item b where b.bill_item_encounter_nr=a.encounter_nr and b.bill_item_code like '%YHCTVLTL%' and DATE_FORMAT(b.bill_item_date,'%Y-%m-%d') >= '$datefrom' AND DATE_FORMAT(b.bill_item_date,'%Y-%m-%d') <= '$dateto') sumvltl,
(select sum(b.bill_item_units) from (select distinct t1.encounter_nr from dfck_admit_inout_dept t1 where  
				t1.type_encounter = 2 
					and ( (t1.dept_to= (select nr from care_department where id = 11) 
					AND ( DATE_FORMAT(t1.datein,'%Y-%m-%d') <= '$dateto' 
					and ( 
							(
								t1.status=1
								and ( select DATE_FORMAT(t3.datein,'%Y-%m-%d') 
								from dfck_admit_inout_dept t3 
								where dept_from=(select nr from care_department where id = 11)  
								and t3.encounter_nr=t1.encounter_nr and t3.nr>t1.nr ORDER BY t3.nr LIMIT 0,1
								)>='$datefrom' 
								and ( select DATE_FORMAT(t3.datein,'%Y-%m-%d') 
								from dfck_admit_inout_dept t3 
								where dept_from=(select nr from care_department where id = 11)  
								and t3.encounter_nr=t1.encounter_nr and t3.nr>t1.nr ORDER BY t3.nr LIMIT 0,1
								)<='$dateto' 
								
							) OR 
							(t1.status=0) 
						)
					) 
				)
				OR (DATE_FORMAT(t1.datein,'%Y-%m-%d') >= '$datefrom'  
					AND DATE_FORMAT(t1.datein,'%Y-%m-%d') <= '$dateto' 
					and t1.dept_from =  (select nr from care_department where id = 11) 
				)
				)
			group by t1.encounter_nr

				)a, care_billing_bill_item b where b.bill_item_encounter_nr=a.encounter_nr and b.bill_item_code like '%YHCTPA%'
				and DATE_FORMAT(b.bill_item_date,'%Y-%m-%d') >= '$datefrom' AND DATE_FORMAT(b.bill_item_date,'%Y-%m-%d') <= '$dateto'
				 and DATE_FORMAT(b.bill_item_date,'%Y-%m-%d') >= '$datefrom' AND DATE_FORMAT(b.bill_item_date,'%Y-%m-%d') <= '$dateto') sumpa,
			(SELECT COUNT(a.encounter_nr) FROM (select distinct t1.encounter_nr from dfck_admit_inout_dept t1 , care_billing_bill_item b 
				where b.bill_item_encounter_nr=t1.encounter_nr and b.bill_item_code like '%YHCT%' 
				and DATE_FORMAT(b.bill_item_date,'%Y-%m-%d') >= '$datefrom' AND DATE_FORMAT(b.bill_item_date,'%Y-%m-%d') <= '$dateto'
			  and t1.type_encounter = 2 
					and (
							t1.dept_to= (select nr from care_department where id = 11) 
							AND ( 	DATE_FORMAT(t1.datein,'%Y-%m-%d') <= '$dateto' 
									and ( 
											(
												t1.status=1
												and ( select DATE_FORMAT(t3.datein,'%Y-%m-%d') 
												from dfck_admit_inout_dept t3 
												where dept_from=(select nr from care_department where id = 11)  
												and t3.encounter_nr=t1.encounter_nr and t3.nr>t1.nr order by t3.nr limit 0,1
												)> '$dateto' 								
											) OR 
											(t1.status=0) 
										)
								)
						) 
												
			group by t1.encounter_nr)
				a  ) sumchuaravien
			FROM DUAL 
			UNION ALL
			SELECT 
			(SELECT COUNT(a.encounter_nr) FROM (select distinct t1.encounter_nr from dfck_admit_inout_dept t1 , care_billing_bill_item b 
				where b.bill_item_encounter_nr=t1.encounter_nr and b.bill_item_code like '%YHCT%'
				and DATE_FORMAT(b.bill_item_date,'%Y-%m-%d') >= '$datefrom' AND DATE_FORMAT(b.bill_item_date,'%Y-%m-%d') <= '$dateto'
				and t1.insurance_nr!='' and DATE_FORMAT(t1.insurance_exp,'%Y-%m-%d') >= '$dateto' 
				and  t1.type_encounter = 2 
					and ( 
						(t1.dept_to= (select nr from care_department where id = 11) 
						AND ( DATE_FORMAT(t1.datein,'%Y-%m-%d') <= '$dateto' 
						and ( 
								(
									t1.status=1
									and ( select DATE_FORMAT(t3.datein,'%Y-%m-%d') 
									from dfck_admit_inout_dept t3 
									where dept_from=(select nr from care_department where id = 11)  
									and t3.encounter_nr=t1.encounter_nr and t3.nr>t1.nr ORDER BY t3.nr LIMIT 0,1
									)>='$datefrom' 
									and ( select DATE_FORMAT(t3.datein,'%Y-%m-%d') 
									from dfck_admit_inout_dept t3 
									where dept_from=(select nr from care_department where id = 11)  
									and t3.encounter_nr=t1.encounter_nr and t3.nr>t1.nr ORDER BY t3.nr LIMIT 0,1
									)<='$dateto' 
									
								) OR 
								(t1.status=0) 
							)
						) 
					)
				OR (DATE_FORMAT(t1.datein,'%Y-%m-%d') >= '$datefrom'  
					AND DATE_FORMAT(t1.datein,'%Y-%m-%d') <= '$dateto' 
					and t1.dept_from =  (select nr from care_department where id = 11) 
				)
				)
			group by t1.encounter_nr) a  
			) sumtnt,
			(select sum(b.bill_item_units) from (select distinct t1.encounter_nr from dfck_admit_inout_dept t1 where t1.insurance_nr!='' and DATE_FORMAT(t1.insurance_exp,'%Y-%m-%d') >= '$dateto' and  t1.type_encounter = 2 
					and ( 
						(t1.dept_to= (select nr from care_department where id = 11) 
						AND ( DATE_FORMAT(t1.datein,'%Y-%m-%d') <= '$dateto' 
						and ( 
								(
									t1.status=1
									and ( select DATE_FORMAT(t3.datein,'%Y-%m-%d') 
									from dfck_admit_inout_dept t3 
									where dept_from=(select nr from care_department where id = 11)  
									and t3.encounter_nr=t1.encounter_nr and t3.nr>t1.nr ORDER BY t3.nr LIMIT 0,1
									)>='$datefrom' 
									and ( select DATE_FORMAT(t3.datein,'%Y-%m-%d') 
									from dfck_admit_inout_dept t3 
									where dept_from=(select nr from care_department where id = 11)  
									and t3.encounter_nr=t1.encounter_nr and t3.nr>t1.nr ORDER BY t3.nr LIMIT 0,1
									)<='$dateto' 
									
								) OR 
								(t1.status=0) 
							)
						) 
					)
				OR (DATE_FORMAT(t1.datein,'%Y-%m-%d') >= '$datefrom'  
					AND DATE_FORMAT(t1.datein,'%Y-%m-%d') <= '$dateto' 
					and t1.dept_from =  (select nr from care_department where id = 11) 
				)
				)
			group by t1.encounter_nr) a , care_billing_bill_item b where b.bill_item_encounter_nr=a.encounter_nr and b.bill_item_code like '%YHCTDC%' and DATE_FORMAT(b.bill_item_date,'%Y-%m-%d') >= '$datefrom' AND DATE_FORMAT(b.bill_item_date,'%Y-%m-%d') <= '$dateto' ) sumdc,
			(select sum(b.bill_item_units) from (select distinct t1.encounter_nr from dfck_admit_inout_dept t1 where t1.insurance_nr!='' and DATE_FORMAT(t1.insurance_exp,'%Y-%m-%d') >= '$dateto' and  t1.type_encounter = 2 
					and ( 
						(t1.dept_to= (select nr from care_department where id = 11) 
						AND ( DATE_FORMAT(t1.datein,'%Y-%m-%d') <= '$dateto' 
						and ( 
								(
									t1.status=1
									and ( select DATE_FORMAT(t3.datein,'%Y-%m-%d') 
									from dfck_admit_inout_dept t3 
									where dept_from=(select nr from care_department where id = 11)  
									and t3.encounter_nr=t1.encounter_nr and t3.nr>t1.nr ORDER BY t3.nr LIMIT 0,1
									)>='$datefrom' 
									and ( select DATE_FORMAT(t3.datein,'%Y-%m-%d') 
									from dfck_admit_inout_dept t3 
									where dept_from=(select nr from care_department where id = 11)  
									and t3.encounter_nr=t1.encounter_nr and t3.nr>t1.nr ORDER BY t3.nr LIMIT 0,1
									)<='$dateto' 
									
								) OR 
								(t1.status=0) 
							)
						) 
					)
				OR (DATE_FORMAT(t1.datein,'%Y-%m-%d') >= '$datefrom'  
					AND DATE_FORMAT(t1.datein,'%Y-%m-%d') <= '$dateto' 
					and t1.dept_from =  (select nr from care_department where id = 11) 
				)
				)
			group by t1.encounter_nr) a , care_billing_bill_item b where b.bill_item_encounter_nr=a.encounter_nr and b.bill_item_code like '%YHCTCD%' and DATE_FORMAT(b.bill_item_date,'%Y-%m-%d') >= '$datefrom' AND DATE_FORMAT(b.bill_item_date,'%Y-%m-%d') <= '$dateto') sumcd,
(select sum(b.bill_item_units) from (select distinct t1.encounter_nr from dfck_admit_inout_dept t1 where t1.insurance_nr!='' and DATE_FORMAT(t1.insurance_exp,'%Y-%m-%d') >= '$dateto' and  t1.type_encounter = 2 
					and ( 
						(t1.dept_to= (select nr from care_department where id = 11) 
						AND ( DATE_FORMAT(t1.datein,'%Y-%m-%d') <= '$dateto' 
						and ( 
								(
									t1.status=1
									and ( select DATE_FORMAT(t3.datein,'%Y-%m-%d') 
									from dfck_admit_inout_dept t3 
									where dept_from=(select nr from care_department where id = 11)  
									and t3.encounter_nr=t1.encounter_nr and t3.nr>t1.nr ORDER BY t3.nr LIMIT 0,1
									)>='$datefrom' 
									and ( select DATE_FORMAT(t3.datein,'%Y-%m-%d') 
									from dfck_admit_inout_dept t3 
									where dept_from=(select nr from care_department where id = 11)  
									and t3.encounter_nr=t1.encounter_nr and t3.nr>t1.nr ORDER BY t3.nr LIMIT 0,1
									)<='$dateto' 
									
								) OR 
								(t1.status=0) 
							)
						) 
					)
				OR (DATE_FORMAT(t1.datein,'%Y-%m-%d') >= '$datefrom'  
					AND DATE_FORMAT(t1.datein,'%Y-%m-%d') <= '$dateto' 
					and t1.dept_from =  (select nr from care_department where id = 11) 
				)
				)
			group by t1.encounter_nr) a , care_billing_bill_item b where b.bill_item_encounter_nr=a.encounter_nr and b.bill_item_code like '%YHCTQC%' and DATE_FORMAT(b.bill_item_date,'%Y-%m-%d') >= '$datefrom' AND DATE_FORMAT(b.bill_item_date,'%Y-%m-%d') <= '$dateto') sumqc,
(select sum(b.bill_item_units) from (select distinct t1.encounter_nr from dfck_admit_inout_dept t1 where t1.insurance_nr!='' and DATE_FORMAT(t1.insurance_exp,'%Y-%m-%d') >= '$dateto' and  t1.type_encounter = 2 
					and ( 
						(t1.dept_to= (select nr from care_department where id = 11) 
						AND ( DATE_FORMAT(t1.datein,'%Y-%m-%d') <= '$dateto' 
						and ( 
								(
									t1.status=1
									and ( select DATE_FORMAT(t3.datein,'%Y-%m-%d') 
									from dfck_admit_inout_dept t3 
									where dept_from=(select nr from care_department where id = 11)  
									and t3.encounter_nr=t1.encounter_nr and t3.nr>t1.nr ORDER BY t3.nr LIMIT 0,1
									)>='$datefrom' 
									and ( select DATE_FORMAT(t3.datein,'%Y-%m-%d') 
									from dfck_admit_inout_dept t3 
									where dept_from=(select nr from care_department where id = 11)  
									and t3.encounter_nr=t1.encounter_nr and t3.nr>t1.nr ORDER BY t3.nr LIMIT 0,1
									)<='$dateto' 
									
								) OR 
								(t1.status=0) 
							)
						) 
					)
				OR (DATE_FORMAT(t1.datein,'%Y-%m-%d') >= '$datefrom'  
					AND DATE_FORMAT(t1.datein,'%Y-%m-%d') <= '$dateto' 
					and t1.dept_from =  (select nr from care_department where id = 11) 
				)
				)
			group by t1.encounter_nr) a , care_billing_bill_item b where b.bill_item_encounter_nr=a.encounter_nr and b.bill_item_code like '%YHCTVLTL%' and DATE_FORMAT(b.bill_item_date,'%Y-%m-%d') >= '$datefrom' AND DATE_FORMAT(b.bill_item_date,'%Y-%m-%d') <= '$dateto') sumvltl,
(select sum(b.bill_item_units) from (select distinct t1.encounter_nr from dfck_admit_inout_dept t1 where t1.insurance_nr!='' and DATE_FORMAT(t1.insurance_exp,'%Y-%m-%d') >= '$dateto' and  t1.type_encounter = 2
					and ( 
						(t1.dept_to= (select nr from care_department where id = 11) 
						AND ( DATE_FORMAT(t1.datein,'%Y-%m-%d') <= '$dateto' 
						and ( 
								(
									t1.status=1
									and ( select DATE_FORMAT(t3.datein,'%Y-%m-%d') 
									from dfck_admit_inout_dept t3 
									where dept_from=(select nr from care_department where id = 11)  
									and t3.encounter_nr=t1.encounter_nr and t3.nr>t1.nr ORDER BY t3.nr LIMIT 0,1
									)>='$datefrom' 
									and ( select DATE_FORMAT(t3.datein,'%Y-%m-%d') 
									from dfck_admit_inout_dept t3 
									where dept_from=(select nr from care_department where id = 11)  
									and t3.encounter_nr=t1.encounter_nr and t3.nr>t1.nr ORDER BY t3.nr LIMIT 0,1
									)<='$dateto' 
									
								) OR 
								(t1.status=0) 
							)
						) 
					)
				OR (DATE_FORMAT(t1.datein,'%Y-%m-%d') >= '$datefrom'  
					AND DATE_FORMAT(t1.datein,'%Y-%m-%d') <= '$dateto' 
					and t1.dept_from =  (select nr from care_department where id = 11) 
				)
				)
			group by t1.encounter_nr) a , care_billing_bill_item b where b.bill_item_encounter_nr=a.encounter_nr and b.bill_item_code like '%YHCTPA%' and DATE_FORMAT(b.bill_item_date,'%Y-%m-%d') >= '$datefrom' AND DATE_FORMAT(b.bill_item_date,'%Y-%m-%d') <= '$dateto') sumpa,
			(SELECT COUNT(a.encounter_nr) FROM (select distinct t1.encounter_nr from dfck_admit_inout_dept t1, care_billing_bill_item b 
				where b.bill_item_encounter_nr=t1.encounter_nr and b.bill_item_code like '%YHCT%'
				and DATE_FORMAT(b.bill_item_date,'%Y-%m-%d') >= '$datefrom' AND DATE_FORMAT(b.bill_item_date,'%Y-%m-%d') <= '$dateto'
			 and t1.insurance_nr!='' and DATE_FORMAT(t1.insurance_exp,'%Y-%m-%d') >= '$dateto' 
			 and   
				 t1.type_encounter = 2 
					and (
						t1.dept_to= (select nr from care_department where id = 11) 
						AND ( DATE_FORMAT(t1.datein,'%Y-%m-%d') <= '$dateto' 
						and ( 
								(
									t1.status=1
									and ( select DATE_FORMAT(t3.datein,'%Y-%m-%d') 
									from dfck_admit_inout_dept t3 
									where dept_from=(select nr from care_department where id = 11)  
									and t3.encounter_nr=t1.encounter_nr and t3.nr>t1.nr order by t3.nr limit 0,1
									)> '$dateto' 								
								) OR 
								(t1.status=0) 
							)
						) 
								
					)
			group by t1.encounter_nr) a 
				) sumchuaravien
			FROM DUAL 
				";
				/*

			
				*/
			//	echo $sqlsumngt;
global $db;
$strngoaitru = "<br><br>BỆNH NHÂN NGOẠI TRÚ<br><table width='100%'>";
$strngoaitru .= '<thead><tr><td>STT</td><td>Họ tên</td><td>Năm sinh</td><td>Địa chỉ</td><td>Mã số BH</td><td>Hạn dùng</td><td>NGÀY VV	</td><td>Chẩn đoán	</td><td>ĐC	</td><td>CĐ	</td><td>QC	</td><td>VLTL	</td><td>Parafin	</td><td>NGÀY RV</td></tr></thead>
';
if($rs = $db->Execute($sqlngt)){
	$i=1;
	while($row = $rs->FetchRow()){
		$strngoaitru .= '<tr><td>'.$i.'</td><td>'.$row['fname'].'</td><td>'.$row['yearbirth'].'</td><td>'.$row['address'].'</td><td>'.$row['insurance_nr'].'</td><td>'.(($row['insurance_exp']!='0000-00-00')?date('d/m/Y',strtotime($row['insurance_exp'])):'').'</td><td>'.date('d/m/Y',strtotime($row['datein'])).'</td><td>'.$row['referrer_diagnosis'].'</td><td>'.$row['sumdc'].'</td><td>'.$row['sumcd'].'</td><td>'.$row['sumqc'].'</td><td>'.$row['sumvltl'].'</td><td>'.$row['sumpa'].'</td><td>'.(($row['dateout']!='0000-00-00' && $row['dateout']!=null)?date('d/m/Y',strtotime($row['dateout'])):'').'</td></tr>';
	$i++;
	}
}
$strngoaitru .= '</table>';
$strsumngtru = '<br><table><thead><tr><td></td><td>TNT</td><td>ĐC</td><td>CĐ</td><td>QC</td><td>VLTL</td><td>PA</td><td>Hiện còn</td></tr></thead>';
if($rs = $db->Execute($sqlsumngt)){
	$row1 = $rs->FetchRow();
//	$rowbh = $rs->FetchRow();
	//var_dump($row1);
	$strsumngtru .= '<tr><td>CHUNG</td><td>'.$row1['sumtnt'].'</td><td>'.$row1['sumdc'].'</td><td>'.$row1['sumcd'].'</td><td>'.$row1['sumqc'].'</td><td>'.$row1['sumvltl'].'</td><td>'.$row1['sumpa'].'</td><td>'.$row1['sumchuaravien'].'</td></tr>';
	$row2 = $rs->FetchRow();
	$strsumngtru .= '<tr><td>BHYT</td><td>'.$row2['sumtnt'].'</td><td>'.$row2['sumdc'].'</td><td>'.$row2['sumcd'].'</td><td>'.$row2['sumqc'].'</td><td>'.$row2['sumvltl'].'</td><td>'.$row2['sumpa'].'</td><td>'.$row2['sumchuaravien'].'</td></tr>';
}
$strsumngtru .= '</table>';
 $sqlnt = "SELECT distinct(t1.encounter_nr),
t1.fname, t1.yearbirth, t1.address,t1.insurance_nr,t1.insurance_exp,t1.referrer_diagnosis,
(select t2.datein from dfck_admit_inout_dept t2 where t2.dept_to = (select nr from care_department where id = 11) and t2.encounter_nr = t1.encounter_nr order by t2.nr  limit 0,1 ) datein2,
(select sum(bill_item_units) FROM care_billing_bill_item where bill_item_encounter_nr=t1.encounter_nr and bill_item_code like '%YHCTDC%' and DATE_FORMAT(bill_item_date,'%Y-%m-%d') >= '$datefrom' AND DATE_FORMAT(bill_item_date,'%Y-%m-%d') <= '$dateto') sumdc,
(select sum(bill_item_units) FROM care_billing_bill_item where bill_item_encounter_nr=t1.encounter_nr and bill_item_code like '%YHCTCD%' and DATE_FORMAT(bill_item_date,'%Y-%m-%d') >= '$datefrom' AND DATE_FORMAT(bill_item_date,'%Y-%m-%d') <= '$dateto') sumcd,
(select sum(bill_item_units) FROM care_billing_bill_item where bill_item_encounter_nr=t1.encounter_nr and bill_item_code like '%YHCTQC%' and DATE_FORMAT(bill_item_date,'%Y-%m-%d') >= '$datefrom' AND DATE_FORMAT(bill_item_date,'%Y-%m-%d') <= '$dateto') sumqc,
(select sum(bill_item_units) FROM care_billing_bill_item where bill_item_encounter_nr=t1.encounter_nr and bill_item_code like '%YHCTVLTL%' and DATE_FORMAT(bill_item_date,'%Y-%m-%d') >= '$datefrom' AND DATE_FORMAT(bill_item_date,'%Y-%m-%d') <= '$dateto') sumvltl,
(select sum(bill_item_units) FROM care_billing_bill_item where bill_item_encounter_nr=t1.encounter_nr and bill_item_code like '%YHCTPA%' and DATE_FORMAT(bill_item_date,'%Y-%m-%d') >= '$datefrom' AND DATE_FORMAT(bill_item_date,'%Y-%m-%d') <= '$dateto' ) sumpa
 from dfck_admit_inout_dept t1 WHERE 
  
t1.type_encounter = 1 
and ( (t1.dept_to= (select nr from care_department where id = 11) 
		AND ( DATE_FORMAT(t1.datein,'%Y-%m-%d') <= '$dateto' 
				and ( 
						(
							t1.status=1
							and ( select DATE_FORMAT(t3.datein,'%Y-%m-%d') 
							from dfck_admit_inout_dept t3 
							where dept_from=(select nr from care_department where id = 11)  
							and t3.encounter_nr=t1.encounter_nr and t3.nr>t1.nr ORDER BY t3.nr LIMIT 0,1
							)>='$datefrom' 
							and ( select DATE_FORMAT(t3.datein,'%Y-%m-%d') 
							from dfck_admit_inout_dept t3 
							where dept_from=(select nr from care_department where id = 11)  
							and t3.encounter_nr=t1.encounter_nr and t3.nr>t1.nr ORDER BY t3.nr LIMIT 0,1
							)<='$dateto' 
							
						) OR 
						(t1.status=0) 
					)
			) 
		)
			OR (DATE_FORMAT(t1.datein,'%Y-%m-%d') >= '$datefrom'  
				AND DATE_FORMAT(t1.datein,'%Y-%m-%d') <= '$dateto' 
				and t1.dept_from =  (select nr from care_department where id = 11) 
			)
	)
group by t1.encounter_nr";//ma noi bo cua YHCT = 11
//echo $sqlnt;
$strnoitru = "<br><br>BỆNH NHÂN NỘI TRÚ<br><table width='100%'>";
$strnoitru .= '<thead><tr><td>STT</td><td>Họ tên</td><td>Năm sinh</td>	<td>Địa chỉ</td>	<td>Mã số BH</td>	<td>Hạn dùng</td>	<td>Chẩn đoán</td>	<td>ĐC</td>	<td>CĐ</td>	<td>QC</td>	<td>VLTL</td>	<td>Parafin</td>	<td>NVV</td></tr></thead>
';
if($rs = $db->Execute($sqlnt)){
	$i=1;
	while($row = $rs->FetchRow()){
		$strnoitru .= '<tr><td>'.$i.'</td><td>'.$row['fname'].'</td><td>'.$row['yearbirth'].'</td><td>'.$row['address'].'</td><td>'.$row['insurance_nr'].'</td><td>'.(($row['insurance_exp']!='0000-00-00')?date('d/m/Y',strtotime($row['insurance_exp'])):'').'</td><td>'.$row['referrer_diagnosis'].'</td><td>'.$row['sumdc'].'</td><td>'.$row['sumcd'].'</td><td>'.$row['sumqc'].'</td><td>'.$row['sumvltl'].'</td><td>'.$row['sumpa'].'</td><td>'.date('d/m/Y',strtotime($row['datein2'])).'</td></tr>';
		$i++;
	}
}
$strnoitru .='</table>';
$sqlsumnoit = "SELECT 
			(SELECT COUNT(a.encounter_nr) FROM (
					select distinct t1.encounter_nr from dfck_admit_inout_dept t1 WHERE 
					t1.type_encounter = 1 
					and ( (t1.dept_to= (select nr from care_department where id = 11) 
					AND ( DATE_FORMAT(t1.datein,'%Y-%m-%d') <= '$dateto' 
					and ( 
							(
								t1.status=1
								and ( select DATE_FORMAT(t3.datein,'%Y-%m-%d') 
								from dfck_admit_inout_dept t3 
								where dept_from=(select nr from care_department where id = 11)  
								and t3.encounter_nr=t1.encounter_nr and t3.nr>t1.nr ORDER BY t3.nr LIMIT 0,1
								)>='$datefrom' 
								and ( select DATE_FORMAT(t3.datein,'%Y-%m-%d') 
								from dfck_admit_inout_dept t3 
								where dept_from=(select nr from care_department where id = 11)  
								and t3.encounter_nr=t1.encounter_nr and t3.nr>t1.nr ORDER BY t3.nr LIMIT 0,1
								)<='$dateto' 
								
							) OR 
							(t1.status=0) 
						)
					) 
				)
				OR (DATE_FORMAT(t1.datein,'%Y-%m-%d') >= '$datefrom'  
					AND DATE_FORMAT(t1.datein,'%Y-%m-%d') <= '$dateto' 
					and t1.dept_from =  (select nr from care_department where id = 11) 
				)
				)
			group by t1.encounter_nr) a 
			) sumtnt,
			(select sum(b.bill_item_units) from (select distinct t1.encounter_nr from dfck_admit_inout_dept t1 where  
				t1.type_encounter = 1 
					and ( (t1.dept_to= (select nr from care_department where id = 11) 
					AND ( DATE_FORMAT(t1.datein,'%Y-%m-%d') <= '$dateto' 
					and ( 
							(
								t1.status=1
								and ( select DATE_FORMAT(t3.datein,'%Y-%m-%d') 
								from dfck_admit_inout_dept t3 
								where dept_from=(select nr from care_department where id = 11)  
								and t3.encounter_nr=t1.encounter_nr and t3.nr>t1.nr ORDER BY t3.nr LIMIT 0,1
								)>='$datefrom' 
								and ( select DATE_FORMAT(t3.datein,'%Y-%m-%d') 
								from dfck_admit_inout_dept t3 
								where dept_from=(select nr from care_department where id = 11)  
								and t3.encounter_nr=t1.encounter_nr and t3.nr>t1.nr ORDER BY t3.nr LIMIT 0,1
								)<='$dateto' 
								
							) OR 
							(t1.status=0) 
						)
					) 
				)
				OR (DATE_FORMAT(t1.datein,'%Y-%m-%d') >= '$datefrom'  
					AND DATE_FORMAT(t1.datein,'%Y-%m-%d') <= '$dateto' 
					and t1.dept_from =  (select nr from care_department where id = 11) 
				)
				)
			group by t1.encounter_nr

				)a, care_billing_bill_item b where b.bill_item_encounter_nr=a.encounter_nr and b.bill_item_code like '%YHCTDC%' and DATE_FORMAT(b.bill_item_date,'%Y-%m-%d') >= '$datefrom' AND DATE_FORMAT(b.bill_item_date,'%Y-%m-%d') <= '$dateto') sumdc,
			(select sum(b.bill_item_units) from (select distinct t1.encounter_nr from dfck_admit_inout_dept t1 where  
				t1.type_encounter = 1 
					and ( (t1.dept_to= (select nr from care_department where id = 11) 
					AND ( DATE_FORMAT(t1.datein,'%Y-%m-%d') <= '$dateto' 
					and ( 
							(
								t1.status=1
								and ( select DATE_FORMAT(t3.datein,'%Y-%m-%d') 
								from dfck_admit_inout_dept t3 
								where dept_from=(select nr from care_department where id = 11)  
								and t3.encounter_nr=t1.encounter_nr and t3.nr>t1.nr ORDER BY t3.nr LIMIT 0,1
								)>='$datefrom' 
								and ( select DATE_FORMAT(t3.datein,'%Y-%m-%d') 
								from dfck_admit_inout_dept t3 
								where dept_from=(select nr from care_department where id = 11)  
								and t3.encounter_nr=t1.encounter_nr and t3.nr>t1.nr ORDER BY t3.nr LIMIT 0,1
								)<='$dateto' 
								
							) OR 
							(t1.status=0) 
						)
					) 
				)
				OR (DATE_FORMAT(t1.datein,'%Y-%m-%d') >= '$datefrom'  
					AND DATE_FORMAT(t1.datein,'%Y-%m-%d') <= '$dateto' 
					and t1.dept_from =  (select nr from care_department where id = 11) 
				)
				)
			group by t1.encounter_nr

				)a, care_billing_bill_item b where b.bill_item_encounter_nr=a.encounter_nr and b.bill_item_code like '%YHCTCD%' and DATE_FORMAT(b.bill_item_date,'%Y-%m-%d') >= '$datefrom' AND DATE_FORMAT(b.bill_item_date,'%Y-%m-%d') <= '$dateto' ) sumcd,
(select sum(b.bill_item_units) from (select distinct t1.encounter_nr from dfck_admit_inout_dept t1 where  
				t1.type_encounter = 1 
					and ( (t1.dept_to= (select nr from care_department where id = 11) 
					AND ( DATE_FORMAT(t1.datein,'%Y-%m-%d') <= '$dateto' 
					and ( 
							(
								t1.status=1
								and ( select DATE_FORMAT(t3.datein,'%Y-%m-%d') 
								from dfck_admit_inout_dept t3 
								where dept_from=(select nr from care_department where id = 11)  
								and t3.encounter_nr=t1.encounter_nr and t3.nr>t1.nr ORDER BY t3.nr LIMIT 0,1
								)>='$datefrom' 
								and ( select DATE_FORMAT(t3.datein,'%Y-%m-%d') 
								from dfck_admit_inout_dept t3 
								where dept_from=(select nr from care_department where id = 11)  
								and t3.encounter_nr=t1.encounter_nr and t3.nr>t1.nr ORDER BY t3.nr LIMIT 0,1
								)<='$dateto' 
								
							) OR 
							(t1.status=0) 
						)
					) 
				)
				OR (DATE_FORMAT(t1.datein,'%Y-%m-%d') >= '$datefrom'  
					AND DATE_FORMAT(t1.datein,'%Y-%m-%d') <= '$dateto' 
					and t1.dept_from =  (select nr from care_department where id = 11) 
				)
				)
			group by t1.encounter_nr

				)a, care_billing_bill_item b where b.bill_item_encounter_nr=a.encounter_nr and b.bill_item_code like '%YHCTQC%' and DATE_FORMAT(b.bill_item_date,'%Y-%m-%d') >= '$datefrom' AND DATE_FORMAT(b.bill_item_date,'%Y-%m-%d') <= '$dateto') sumqc,
(select sum(b.bill_item_units) from (select distinct t1.encounter_nr from dfck_admit_inout_dept t1 where  
				t1.type_encounter = 1 
					and ( (t1.dept_to= (select nr from care_department where id = 11) 
					AND ( DATE_FORMAT(t1.datein,'%Y-%m-%d') <= '$dateto' 
					and ( 
							(
								t1.status=1
								and ( select DATE_FORMAT(t3.datein,'%Y-%m-%d') 
								from dfck_admit_inout_dept t3 
								where dept_from=(select nr from care_department where id = 11)  
								and t3.encounter_nr=t1.encounter_nr and t3.nr>t1.nr ORDER BY t3.nr LIMIT 0,1
								)>='$datefrom' 
								and ( select DATE_FORMAT(t3.datein,'%Y-%m-%d') 
								from dfck_admit_inout_dept t3 
								where dept_from=(select nr from care_department where id = 11)  
								and t3.encounter_nr=t1.encounter_nr and t3.nr>t1.nr ORDER BY t3.nr LIMIT 0,1
								)<='$dateto' 
								
							) OR 
							(t1.status=0) 
						)
					) 
				)
				OR (DATE_FORMAT(t1.datein,'%Y-%m-%d') >= '$datefrom'  
					AND DATE_FORMAT(t1.datein,'%Y-%m-%d') <= '$dateto' 
					and t1.dept_from =  (select nr from care_department where id = 11) 
				)
				)
			group by t1.encounter_nr

				)a, care_billing_bill_item b where b.bill_item_encounter_nr=a.encounter_nr and b.bill_item_code like '%YHCTVLTL%' and DATE_FORMAT(b.bill_item_date,'%Y-%m-%d') >= '$datefrom' AND DATE_FORMAT(b.bill_item_date,'%Y-%m-%d') <= '$dateto') sumvltl,
(select sum(b.bill_item_units) from (select distinct t1.encounter_nr from dfck_admit_inout_dept t1 where  
				t1.type_encounter = 1 
					and ( (t1.dept_to= (select nr from care_department where id = 11) 
					AND ( DATE_FORMAT(t1.datein,'%Y-%m-%d') <= '$dateto' 
					and ( 
							(
								t1.status=1
								and ( select DATE_FORMAT(t3.datein,'%Y-%m-%d') 
								from dfck_admit_inout_dept t3 
								where dept_from=(select nr from care_department where id = 11)  
								and t3.encounter_nr=t1.encounter_nr and t3.nr>t1.nr ORDER BY t3.nr LIMIT 0,1
								)>='$datefrom' 
								and ( select DATE_FORMAT(t3.datein,'%Y-%m-%d') 
								from dfck_admit_inout_dept t3 
								where dept_from=(select nr from care_department where id = 11)  
								and t3.encounter_nr=t1.encounter_nr and t3.nr>t1.nr ORDER BY t3.nr LIMIT 0,1
								)<='$dateto' 
								
							) OR 
							(t1.status=0) 
						)
					) 
				)
				OR (DATE_FORMAT(t1.datein,'%Y-%m-%d') >= '$datefrom'  
					AND DATE_FORMAT(t1.datein,'%Y-%m-%d') <= '$dateto' 
					and t1.dept_from =  (select nr from care_department where id = 11) 
				)
				)
			group by t1.encounter_nr

				)a, care_billing_bill_item b where b.bill_item_encounter_nr=a.encounter_nr and b.bill_item_code like '%YHCTPA%' and DATE_FORMAT(b.bill_item_date,'%Y-%m-%d') >= '$datefrom' AND DATE_FORMAT(b.bill_item_date,'%Y-%m-%d') <= '$dateto') sumpa,
			(SELECT COUNT(a.encounter_nr) FROM (select distinct t1.encounter_nr from dfck_admit_inout_dept t1 where 
			 t1.type_encounter = 1 
					and (
							t1.dept_to= (select nr from care_department where id = 11) 
							AND ( 	DATE_FORMAT(t1.datein,'%Y-%m-%d') <= '$dateto' 
									and ( 
											(
												t1.status=1
												and ( select DATE_FORMAT(t3.datein,'%Y-%m-%d') 
												from dfck_admit_inout_dept t3 
												where dept_from=(select nr from care_department where id = 11)  
												and t3.encounter_nr=t1.encounter_nr and t3.nr>t1.nr order by t3.nr limit 0,1
												)> '$dateto' 								
											) OR 
											(t1.status=0) 
										)
								)
						) 
												
			group by t1.encounter_nr)
				a ) sumchuaravien
			FROM DUAL 
			UNION ALL
			SELECT 
			(SELECT COUNT(a.encounter_nr) FROM (select distinct t1.encounter_nr from dfck_admit_inout_dept t1 where t1.insurance_nr!='' and DATE_FORMAT(t1.insurance_exp,'%Y-%m-%d') >= '$dateto' 
				and  t1.type_encounter = 1 
					and ( 
						(t1.dept_to= (select nr from care_department where id = 11) 
						AND ( DATE_FORMAT(t1.datein,'%Y-%m-%d') <= '$dateto' 
						and ( 
								(
									t1.status=1
									and ( select DATE_FORMAT(t3.datein,'%Y-%m-%d') 
									from dfck_admit_inout_dept t3 
									where dept_from=(select nr from care_department where id = 11)  
									and t3.encounter_nr=t1.encounter_nr and t3.nr>t1.nr ORDER BY t3.nr LIMIT 0,1
									)>='$datefrom' 
									and ( select DATE_FORMAT(t3.datein,'%Y-%m-%d') 
									from dfck_admit_inout_dept t3 
									where dept_from=(select nr from care_department where id = 11)  
									and t3.encounter_nr=t1.encounter_nr and t3.nr>t1.nr ORDER BY t3.nr LIMIT 0,1
									)<='$dateto' 
									
								) OR 
								(t1.status=0) 
							)
						) 
					)
				OR (DATE_FORMAT(t1.datein,'%Y-%m-%d') >= '$datefrom'  
					AND DATE_FORMAT(t1.datein,'%Y-%m-%d') <= '$dateto' 
					and t1.dept_from =  (select nr from care_department where id = 11) 
				)
				)
			group by t1.encounter_nr) a 
			) sumtnt,
			(select sum(b.bill_item_units) from (select distinct t1.encounter_nr from dfck_admit_inout_dept t1 where t1.insurance_nr!='' and DATE_FORMAT(t1.insurance_exp,'%Y-%m-%d') >= '$dateto' and  t1.type_encounter = 1 
					and ( 
						(t1.dept_to= (select nr from care_department where id = 11) 
						AND ( DATE_FORMAT(t1.datein,'%Y-%m-%d') <= '$dateto' 
						and ( 
								(
									t1.status=1
									and ( select DATE_FORMAT(t3.datein,'%Y-%m-%d') 
									from dfck_admit_inout_dept t3 
									where dept_from=(select nr from care_department where id = 11)  
									and t3.encounter_nr=t1.encounter_nr and t3.nr>t1.nr ORDER BY t3.nr LIMIT 0,1
									)>='$datefrom' 
									and ( select DATE_FORMAT(t3.datein,'%Y-%m-%d') 
									from dfck_admit_inout_dept t3 
									where dept_from=(select nr from care_department where id = 11)  
									and t3.encounter_nr=t1.encounter_nr and t3.nr>t1.nr ORDER BY t3.nr LIMIT 0,1
									)<='$dateto' 
									
								) OR 
								(t1.status=0) 
							)
						) 
					)
				OR (DATE_FORMAT(t1.datein,'%Y-%m-%d') >= '$datefrom'  
					AND DATE_FORMAT(t1.datein,'%Y-%m-%d') <= '$dateto' 
					and t1.dept_from =  (select nr from care_department where id = 11) 
				)
				)
			group by t1.encounter_nr) a , care_billing_bill_item b where b.bill_item_encounter_nr=a.encounter_nr and b.bill_item_code like '%YHCTDC%' and DATE_FORMAT(b.bill_item_date,'%Y-%m-%d') >= '$datefrom' AND DATE_FORMAT(b.bill_item_date,'%Y-%m-%d') <= '$dateto') sumdc,
			(select sum(b.bill_item_units) from (select distinct t1.encounter_nr from dfck_admit_inout_dept t1 where t1.insurance_nr!='' and DATE_FORMAT(t1.insurance_exp,'%Y-%m-%d') >= '$dateto' and  t1.type_encounter = 1 
					and ( 
						(t1.dept_to= (select nr from care_department where id = 11) 
						AND ( DATE_FORMAT(t1.datein,'%Y-%m-%d') <= '$dateto' 
						and ( 
								(
									t1.status=1
									and ( select DATE_FORMAT(t3.datein,'%Y-%m-%d') 
									from dfck_admit_inout_dept t3 
									where dept_from=(select nr from care_department where id = 11)  
									and t3.encounter_nr=t1.encounter_nr and t3.nr>t1.nr ORDER BY t3.nr LIMIT 0,1
									)>='$datefrom' 
									and ( select DATE_FORMAT(t3.datein,'%Y-%m-%d') 
									from dfck_admit_inout_dept t3 
									where dept_from=(select nr from care_department where id = 11)  
									and t3.encounter_nr=t1.encounter_nr and t3.nr>t1.nr ORDER BY t3.nr LIMIT 0,1
									)<='$dateto' 
									
								) OR 
								(t1.status=0) 
							)
						) 
					)
				OR (DATE_FORMAT(t1.datein,'%Y-%m-%d') >= '$datefrom'  
					AND DATE_FORMAT(t1.datein,'%Y-%m-%d') <= '$dateto' 
					and t1.dept_from =  (select nr from care_department where id = 11) 
				)
				)
			group by t1.encounter_nr) a , care_billing_bill_item b where b.bill_item_encounter_nr=a.encounter_nr and b.bill_item_code like '%YHCTCD%' and DATE_FORMAT(b.bill_item_date,'%Y-%m-%d') >= '$datefrom' AND DATE_FORMAT(b.bill_item_date,'%Y-%m-%d') <= '$dateto' ) sumcd,
(select sum(b.bill_item_units) from (select distinct t1.encounter_nr from dfck_admit_inout_dept t1 where t1.insurance_nr!='' and DATE_FORMAT(t1.insurance_exp,'%Y-%m-%d') >= '$dateto' and  t1.type_encounter = 1 
					and ( 
						(t1.dept_to= (select nr from care_department where id = 11) 
						AND ( DATE_FORMAT(t1.datein,'%Y-%m-%d') <= '$dateto' 
						and ( 
								(
									t1.status=1
									and ( select DATE_FORMAT(t3.datein,'%Y-%m-%d') 
									from dfck_admit_inout_dept t3 
									where dept_from=(select nr from care_department where id = 11)  
									and t3.encounter_nr=t1.encounter_nr and t3.nr>t1.nr ORDER BY t3.nr LIMIT 0,1
									)>='$datefrom' 
									and ( select DATE_FORMAT(t3.datein,'%Y-%m-%d') 
									from dfck_admit_inout_dept t3 
									where dept_from=(select nr from care_department where id = 11)  
									and t3.encounter_nr=t1.encounter_nr and t3.nr>t1.nr ORDER BY t3.nr LIMIT 0,1
									)<='$dateto' 
									
								) OR 
								(t1.status=0) 
							)
						) 
					)
				OR (DATE_FORMAT(t1.datein,'%Y-%m-%d') >= '$datefrom'  
					AND DATE_FORMAT(t1.datein,'%Y-%m-%d') <= '$dateto' 
					and t1.dept_from =  (select nr from care_department where id = 11) 
				)
				)
			group by t1.encounter_nr) a , care_billing_bill_item b where b.bill_item_encounter_nr=a.encounter_nr and b.bill_item_code like '%YHCTQC%' and DATE_FORMAT(b.bill_item_date,'%Y-%m-%d') >= '$datefrom' AND DATE_FORMAT(b.bill_item_date,'%Y-%m-%d') <= '$dateto') sumqc,
(select sum(b.bill_item_units) from (select distinct t1.encounter_nr from dfck_admit_inout_dept t1 where t1.insurance_nr!='' and DATE_FORMAT(t1.insurance_exp,'%Y-%m-%d') >= '$dateto' and  t1.type_encounter = 1 
					and ( 
						(t1.dept_to= (select nr from care_department where id = 11) 
						AND ( DATE_FORMAT(t1.datein,'%Y-%m-%d') <= '$dateto' 
						and ( 
								(
									t1.status=1
									and ( select DATE_FORMAT(t3.datein,'%Y-%m-%d') 
									from dfck_admit_inout_dept t3 
									where dept_from=(select nr from care_department where id = 11)  
									and t3.encounter_nr=t1.encounter_nr and t3.nr>t1.nr ORDER BY t3.nr LIMIT 0,1
									)>='$datefrom' 
									and ( select DATE_FORMAT(t3.datein,'%Y-%m-%d') 
									from dfck_admit_inout_dept t3 
									where dept_from=(select nr from care_department where id = 11)  
									and t3.encounter_nr=t1.encounter_nr and t3.nr>t1.nr ORDER BY t3.nr LIMIT 0,1
									)<='$dateto' 
									
								) OR 
								(t1.status=0) 
							)
						) 
					)
				OR (DATE_FORMAT(t1.datein,'%Y-%m-%d') >= '$datefrom'  
					AND DATE_FORMAT(t1.datein,'%Y-%m-%d') <= '$dateto' 
					and t1.dept_from =  (select nr from care_department where id = 11) 
				)
				)
			group by t1.encounter_nr) a , care_billing_bill_item b where b.bill_item_encounter_nr=a.encounter_nr and b.bill_item_code like '%YHCTVLTL%' and DATE_FORMAT(b.bill_item_date,'%Y-%m-%d') >= '$datefrom' AND DATE_FORMAT(b.bill_item_date,'%Y-%m-%d') <= '$dateto' ) sumvltl,
(select sum(b.bill_item_units) from (select distinct t1.encounter_nr from dfck_admit_inout_dept t1 where t1.insurance_nr!='' and DATE_FORMAT(t1.insurance_exp,'%Y-%m-%d') >= '$dateto' and  t1.type_encounter = 1 
					and ( 
						(t1.dept_to= (select nr from care_department where id = 11) 
						AND ( DATE_FORMAT(t1.datein,'%Y-%m-%d') <= '$dateto' 
						and ( 
								(
									t1.status=1
									and ( select DATE_FORMAT(t3.datein,'%Y-%m-%d') 
									from dfck_admit_inout_dept t3 
									where dept_from=(select nr from care_department where id = 11)  
									and t3.encounter_nr=t1.encounter_nr and t3.nr>t1.nr ORDER BY t3.nr LIMIT 0,1
									)>='$datefrom' 
									and ( select DATE_FORMAT(t3.datein,'%Y-%m-%d') 
									from dfck_admit_inout_dept t3 
									where dept_from=(select nr from care_department where id = 11)  
									and t3.encounter_nr=t1.encounter_nr and t3.nr>t1.nr ORDER BY t3.nr LIMIT 0,1
									)<='$dateto' 
									
								) OR 
								(t1.status=0) 
							)
						) 
					)
				OR (DATE_FORMAT(t1.datein,'%Y-%m-%d') >= '$datefrom'  
					AND DATE_FORMAT(t1.datein,'%Y-%m-%d') <= '$dateto' 
					and t1.dept_from =  (select nr from care_department where id = 11) 
				)
				)
			group by t1.encounter_nr) a , care_billing_bill_item b where b.bill_item_encounter_nr=a.encounter_nr and b.bill_item_code like '%YHCTPA%' and DATE_FORMAT(b.bill_item_date,'%Y-%m-%d') >= '$datefrom' AND DATE_FORMAT(b.bill_item_date,'%Y-%m-%d') <= '$dateto') sumpa,
			(SELECT COUNT(a.encounter_nr) FROM (select distinct t1.encounter_nr from dfck_admit_inout_dept t1 where t1.insurance_nr!='' and DATE_FORMAT(t1.insurance_exp,'%Y-%m-%d') >= '$dateto' and   
				 t1.type_encounter = 1 
					and (
						t1.dept_to= (select nr from care_department where id = 11) 
						AND ( DATE_FORMAT(t1.datein,'%Y-%m-%d') <= '$dateto' 
						and ( 
								(
									t1.status=1
									and ( select DATE_FORMAT(t3.datein,'%Y-%m-%d') 
									from dfck_admit_inout_dept t3 
									where dept_from=(select nr from care_department where id = 11)  
									and t3.encounter_nr=t1.encounter_nr and t3.nr>t1.nr order by t3.nr limit 0,1
									)> '$dateto' 								
								) OR 
								(t1.status=0) 
							)
						) 
								
					)
			group by t1.encounter_nr)
				a ) sumchuaravien
			FROM DUAL 
				";
	//echo $sqlsumnoit;			
$strsumnoitru = '<br><table><thead><tr><td></td><td>TNT</td><td>ĐC</td><td>CĐ</td><td>QC</td><td>VLTL</td><td>PA</td><td>Hiện còn</td></tr></thead>';
if($rs = $db->Execute($sqlsumnoit)){
	$row3 = $rs->FetchRow();
//	$rowbh = $rs->FetchRow();
	//var_dump($row1);
	$strsumnoitru .= '<tr><td>CHUNG</td><td>'.$row3['sumtnt'].'</td><td>'.$row3['sumdc'].'</td><td>'.$row3['sumcd'].'</td><td>'.$row3['sumqc'].'</td><td>'.$row3['sumvltl'].'</td><td>'.$row3['sumpa'].'</td><td>'.$row3['sumchuaravien'].'</td></tr>';
	$row4 = $rs->FetchRow();
	$strsumnoitru .= '<tr><td>BHYT</td><td>'.$row4['sumtnt'].'</td><td>'.$row4['sumdc'].'</td><td>'.$row4['sumcd'].'</td><td>'.$row4['sumqc'].'</td><td>'.$row4['sumvltl'].'</td><td>'.$row4['sumpa'].'</td><td>'.$row4['sumchuaravien'].'</td></tr>';
	$TCNOITRU = $row3['sumtnt'];
	$TCNOITRUBHYT = $row4['sumtnt'];
	$TCNOITRUCONLAI = $row3['sumchuaravien'];
	$TCNOITRUCONLAIBHYT = $row4['sumchuaravien'];
}
$strsumnoitru .= '</table>';
$strsumall = '<br><table><thead><tr><td>TỔNG CỘNG</td><td>TNT</td><td>ĐC</td><td>CĐ</td><td>QC</td><td>VLTL</td><td>PA</td><td>Hiện còn</td></tr></thead>';
if($rs = $db->Execute($sqlsumnoit)){
	$row3 = $rs->FetchRow();
//	$rowbh = $rs->FetchRow();
	//var_dump($row1);
	$strsumall .= '<tr><td>CHUNG</td><td>'.($row3['sumtnt']+$row1['sumtnt']).'</td><td>'.($row3['sumdc']+$row1['sumdc']).'</td><td>'.($row3['sumcd']+$row1['sumcd']).'</td><td>'.($row3['sumqc']+$row1['sumqc']).'</td><td>'.($row3['sumvltl']+$row1['sumvltl']).'</td><td>'.($row3['sumpa']+$row1['sumpa']).'</td><td>'.($row3['sumchuaravien']+$row1['sumchuaravien']).'</td></tr>';
	$row4 = $rs->FetchRow();
	$TCDC = $row3['sumdc']+$row1['sumdc'];
	$TCCD = $row3['sumcd']+$row1['sumcd'];
	$TCQC = $row3['sumqc']+$row1['sumqc'];
	$TCVLTL = $row3['sumvltl']+$row1['sumvltl'];
	$TCPA = $row3['sumpa']+$row1['sumpa'];
	$strsumall .= '<tr><td>BHYT</td><td>'.($row4['sumtnt']+$row2['sumtnt']).'</td><td>'.($row4['sumdc']+$row2['sumdc']).'</td><td>'.($row4['sumcd']+$row2['sumcd']).'</td><td>'.($row4['sumqc']+$row2['sumqc']).'</td><td>'.($row4['sumvltl']+$row2['sumvltl']).'</td><td>'.($row4['sumpa']+$row2['sumpa']).'</td><td>'.($row4['sumchuaravien']+$row2['sumchuaravien']).'</td></tr>';

	$TCDCBH = $row4['sumdc']+$row2['sumdc'];
	$TCCDBH = $row4['sumcd']+$row2['sumcd'];
	$TCQCBH = $row4['sumqc']+$row2['sumqc'];
	$TCVLTLBH = $row4['sumvltl']+$row2['sumvltl'];
	$TCPABH = $row4['sumpa']+$row2['sumpa'];
}
$strsumall .= '</table>';

$sqlraviennoitru = "SELECT distinct t.fname,t.datein from dfck_admit_inout_dept t where t.dept_to < 0 and t.type_encounter = 1 and DATE_FORMAT(t.datein,'%Y-%m-%d') >= '$datefrom' AND DATE_FORMAT(t.datein,'%Y-%m-%d') <= '$dateto' and t.dept_from = (select nr from care_department where id = 11) "; 
//echo $sqlraviennoitru;
$sqlvaoviennoitru = "SELECT distinct t.fname from dfck_admit_inout_dept t where t.type_encounter = 1 and DATE_FORMAT(t.datein,'%Y-%m-%d') >= '$datefrom' AND DATE_FORMAT(t.datein,'%Y-%m-%d') <= '$dateto'  and t.dept_to = (select nr from care_department where id = 11)"; 
$sqlravienngtru = "SELECT distinct t.fname from dfck_admit_inout_dept t,care_billing_bill_item b where t.dept_to < 0 and t.type_encounter = 2 and DATE_FORMAT(t.datein,'%Y-%m-%d') >= '$datefrom' AND DATE_FORMAT(t.datein,'%Y-%m-%d') <= '$dateto' and t.dept_from = (select nr from care_department where id = 11) and t.encounter_nr = b.bill_item_encounter_nr AND  b.bill_item_code like 'YHCT%'"; 
$sqlvaovienngtru = "SELECT distinct t.fname from dfck_admit_inout_dept t,care_billing_bill_item b where t.type_encounter = 2 and DATE_FORMAT(t.datein,'%Y-%m-%d') >= '$datefrom' AND DATE_FORMAT(t.datein,'%Y-%m-%d') <= '$dateto'  and t.dept_to = (select nr from care_department where id = 11) and t.encounter_nr = b.bill_item_encounter_nr AND  b.bill_item_code like 'YHCT%'";
$sqltknoitru="SELECT 
		(SELECT COUNT(DISTINCT f12.encounter_nr) 
    FROM dfck_admit_inout_dept f12
    WHERE ( ( DATE_FORMAT(f12.datein,'%Y-%m-%d') > '".date("Y-m-d",strtotime($datefrom))."' AND f12.dept_from = d.nr 
        AND ( SELECT DATE_FORMAT(f13.datein,'%Y-%m-%d') 
        from dfck_admit_inout_dept f13 
        where f13.encounter_nr = f12.encounter_nr 
        and f13.dept_to = f12.dept_from   and f13.status=1
        order by f13.datein DESC limit 0,1 ) < '".date("Y-m-d",strtotime($datefrom))."'
        ) 
    OR ( f12.status=0 AND f12.dept_to = d.nr and DATE_FORMAT(f12.datein,'%Y-%m-%d') < '".date("Y-m-d",strtotime($datefrom))."' ) ) AND f12.type_encounter = 1
        ) numdauky,
	(SELECT COUNT(DISTINCT f1.encounter_nr) 
    FROM dfck_admit_inout_dept f1 
    WHERE
    f1.dept_to = d.nr and f1.dept_to = f1.dept_from AND f1.type_encounter = 1
    AND DATE_FORMAT(f1.datein,'%Y-%m-%d') >= '".date("Y-m-d",strtotime($datefrom))."' 
    AND DATE_FORMAT(f1.datein,'%Y-%m-%d') <= '".date("Y-m-d",strtotime($dateto))."') numvaovien,
(SELECT COUNT(f2.nr) 
    FROM dfck_admit_inout_dept f2 
    WHERE f2.dept_from != f2.dept_to 
    AND f2.dept_to = d.nr AND f2.type_encounter = 1
    AND DATE_FORMAT(f2.datein,'%Y-%m-%d') >= '".date("Y-m-d",strtotime($datefrom))."' 
    AND DATE_FORMAT(f2.datein,'%Y-%m-%d') <= '".date("Y-m-d",strtotime($dateto))."') numkhoakhacden,
(SELECT COUNT(DISTINCT f3.encounter_nr) 
    FROM dfck_admit_inout_dept f3 
    WHERE f3.dept_to < 0
    AND f3.dept_from = d.nr AND f3.type_encounter = 1
    AND DATE_FORMAT(f3.datein,'%Y-%m-%d') >= '".date("Y-m-d",strtotime($datefrom))."' 
    AND DATE_FORMAT(f3.datein,'%Y-%m-%d') <= '".date("Y-m-d",strtotime($dateto))."') numravien,
(SELECT COUNT(DISTINCT f3.encounter_nr) 
    FROM dfck_admit_inout_dept f3 
    WHERE f3.dept_to != f3.dept_from and f3.dept_to > 0
    AND f3.dept_from = d.nr AND f3.type_encounter = 1
    AND DATE_FORMAT(f3.datein,'%Y-%m-%d') >= '".date("Y-m-d",strtotime($datefrom))."' 
    AND DATE_FORMAT(f3.datein,'%Y-%m-%d') <= '".date("Y-m-d",strtotime($dateto))."') numrakhoa
	 from care_department d where d.id = 11"; 
$strdsravaovien = '<br><table><thead><tr><td>Nội trú ra viện</td><td>Nội trú vào viện</td><td>Ngoại trú ra viện</td><td>Ngoại trú vào viện</td></tr></thead>';
$max = 0;
$rs1 = $db->Execute($sqlraviennoitru);
if ($rs1->RecordCount() > $max) $max = $rs1->RecordCount(); 
$rs2 = $db->Execute($sqlvaoviennoitru);
if ($rs2->RecordCount() > $max) $max = $rs2->RecordCount(); 
$rs3 = $db->Execute($sqlravienngtru);
if ($rs3->RecordCount() > $max) $max = $rs3->RecordCount(); 
$rs4 = $db->Execute($sqlvaovienngtru);
if ($rs4->RecordCount() > $max) $max = $rs4->RecordCount(); 
//echo $max;
if($max>0){
	for($i=0;$i<$max;$i++){
		$strdsravaovien .= '<tr>';
	if($row1 = $rs1->FetchRow())
		$strdsravaovien .= '<td>'.$row1['fname'].'</td>';
	else 
		$strdsravaovien .= '<td></td>';
	if($row2 = $rs2->FetchRow())
		$strdsravaovien .= '<td>'.$row2['fname'].'</td>';
	else 
		$strdsravaovien .= '<td></td>';
	if($row3 = $rs3->FetchRow())
		$strdsravaovien .= '<td>'.$row3['fname'].'</td>';
	else 
		$strdsravaovien .= '<td></td>';
	if($row4 = $rs4->FetchRow())
		$strdsravaovien .= '<td>'.$row4['fname'].'</td>';
	else 
		$strdsravaovien .= '<td></td>';
	$strdsravaovien .= '</tr>';
	}
}
$strdsravaovien .= '</table>';
$sqlsumkham = "SELECT 
			(select COUNT(distinct t.encounter_nr) from dfck_admit_inout_dept t 
				where  t.dept_to= (select nr from care_department where id = 11)
					and t.type_encounter = 2 
					and DATE_FORMAT(t.datein,'%Y-%m-%d') >= '$datefrom' 
					AND DATE_FORMAT(t.datein,'%Y-%m-%d') <= '$dateto'
				) sumtnt ,
		
			
			(SELECT COUNT(distinct t.encounter_nr) from dfck_admit_inout_dept t 
				where t.insurance_nr!='' and DATE_FORMAT(t.insurance_exp,'%Y-%m-%d') >= '$dateto' 
				and  t.dept_to= (select nr from care_department where id = 11)
				and t.type_encounter = 2 
				and DATE_FORMAT(t.datein,'%Y-%m-%d') >= '$datefrom' 
				AND DATE_FORMAT(t.datein,'%Y-%m-%d') <= '$dateto' ) sumtntbh from dual
			";
		//echo $sqlsumkham;
$strsumkham = '<br>KHÁM<table><thead><tr><td>TỔNG SỐ</td><td>BHYT</td></tr></thead><tr>';
if($rssumkham = $db->Execute($sqlsumkham)){
if($rsumkham = $rssumkham->FetchRow()){
	$strsumkham.= '<td>'.$rsumkham['sumtnt'].'</td><td>'.$rsumkham['sumtntbh'].'</td>';
}
else $strsumkham.= '<td></td><td></td>';

}
$strsumkham.='</tr></table>';
$strsumthuthuat = '<br>THỦ THUẬT<table><thead><tr><td>TỔNG SỐ</td><td>ĐIỆN CHÂM</td><td>CHIẾU ĐÈN</td><td>QUANG CHÂM</td><td>VẬT LÝ TRỊ LIỆU</td><td>PARAFIN</td></tr></thead><tr>';

	$strsumthuthuat.= '<tr><td>'.($TCDC+$TCCD+$TCQC+$TCVLTL+$TCPA).'</td><td>'.$TCDC.'</td><td>'.$TCCD.'</td><td>'.$TCQC.'</td><td>'.$TCVLTL.'</td><td>'.$TCPA.'</td></tr>';
	$strsumthuthuat.= '<tr><td>KHÔNG BHYT</td><td>'.($TCDC - $TCDCBH).'</td><td>'.($TCCD-$TCCDBH).'</td><td>'.($TCQC-$TCQCBH).'</td><td>'.($TCVLTL-$TCVLTLBH).'</td><td>'.($TCPA - $TCPABH).'</td></tr>';
$strsumthuthuat.='</tr></table>';

$strtknoitru = '<br>NỘI TRÚ<table><thead><tr><td>TỔNG SỐ</td><td>BHYT</td><td>BỆNH CŨ</td><td>BỆNH MỚI</td><td>CHUYỂN ĐẾN</td><td>CHUYỂN ĐI</td><td>RA VIỆN</td><td>HIỆN CÒN</td><td>BHYT</td></tr></thead><tr>';
if($rs = $db->Execute($sqltknoitru)){
	while($row = $rs->FetchRow()){
		$strtknoitru .='<tr><td>'.$TCNOITRU.'</td><td>'.$TCNOITRUBHYT.'</td><td>'.$row['numdauky'].'</td><td>'.$row['numvaovien'].'</td><td>'.$row['numkhoakhacden'].'</td><td>'.$row['numrakhoa'].'</td><td>'.$row['numravien'].'</td><td>'.$TCNOITRUCONLAI.'</td><td>'.$TCNOITRUCONLAIBHYT.'</td></tr>';
	}

}
$strtknoitru .= '</table>';
?>
<meta charset="utf-8">
<style>
table{
	border:1px solid;
	border-collapse: collapse;
}
td{
	border:1px solid;
	text-align: center;
}
thead tr{
	background: #dadada;
}
</style>

<?
echo  $str.$strngoaitru.$strsumngtru.$strnoitru.$strsumnoitru.$strsumall.$strdsravaovien.$strsumkham.$strsumthuthuat.$strtknoitru;
?>
