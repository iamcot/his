SELECT * FROM care_pharma_khole_ton_info
SELECT * FROM care_pharma_khole_ton WHERE id='1'
SELECT * FROM care_pharma_products_main
SELECT * FROM care_pharma_unit_of_medicine

SELECT Pr.product_name,Unit.unit_name_of_medicine,KLT.product_encoder,KLT.lotid,KLT.number,KLT.price,KLTI.monthreport,KLTI.yearreport
FROM   	   care_pharma_unit_of_medicine AS Unit
LEFT JOIN  care_pharma_products_main  AS Pr   ON Unit.unit_of_medicine=Pr.unit_of_medicine
LEFT JOIN  care_pharma_khole_ton      AS KLT  ON Pr.product_encoder=KLT.product_encoder
RIGHT JOIN care_pharma_khole_ton_info AS KLTI ON KLTI.id=KLT.ton_id
WHERE KLTI.monthreport='' AND KLTI.yearreport=''


SELECT * FROM care_pharma_pay_out
SELECT * FROM care_pharma_pay_out_info
SELECT * FROM care_pharma_dept_returnmed
SELECT * FROM care_pharma_dept_returnmed_info

SELECT  Nhap.product_encoder AS Encoder_Of_Payout,TraVe.product_encoder AS Encoder_Of_Return,
        Nhap.lotid,Nhap.exp_date,Nhap.number,Nhap.price,
	NhapInfo.create_time,
	TraVe.product_lot_id,TraVe.cost,TraVe.number,
	TraVeInfo.date_time_create,
	(SUM(Nhap.number)+SUM(TraVe.number)) AS TONG
FROM 	   care_pharma_pay_out_info AS NhapInfo
LEFT JOIN  care_pharma_pay_out AS Nhap ON NhapInfo.pay_out_id=Nhap.pay_out_id
RIGHT JOIN care_pharma_products_main AS Pr ON Pr.product_encoder=Nhap.product_encoder
LEFT JOIN  care_pharma_dept_returnmed AS TraVe ON Pr.product_encoder=TraVe.product_encoder
RIGHT JOIN care_pharma_dept_returnmed_info AS TraVeInfo ON TraVeInfo.return_id=TraVe.return_id
WHERE      TraVeInfo.status_finish='1' AND NhapInfo.health_station='0' AND NhapInfo.status_finish='1'

/*sum*/
SELECT SUM(Nhap.number) FROM care_pharma_pay_out AS Nhap
SELECT SUM(TraVe.number)FROM care_pharma_dept_returnmed AS TraVe
SELECT (SUM(Nhap.number)+SUM(TraVe.number)) AS TONG
FROM care_pharma_pay_out AS Nhap


SELECT * FROM care_pharma_prescription
SELECT * FROM care_pharma_prescription_info
SELECT * FROM care_pharma_issue_paper
SELECT * FROM care_pharma_issue_paper_info

SELECT  Nhap.product_encoder AS Encoder_Of_Payout,TraVe.product_encoder AS Encoder_Of_Return,
        Nhap.lotid,Nhap.exp_date,Nhap.number,Nhap.price,
	NhapInfo.create_time,
	TraVe.product_lot_id,TraVe.cost,TraVe.number,
	TraVeInfo.date_time_create
FROM 	   care_pharma_pay_out_info AS NhapInfo
LEFT JOIN  care_pharma_pay_out AS Nhap ON NhapInfo.pay_out_id=Nhap.pay_out_id
RIGHT JOIN care_pharma_products_main AS Pr ON Pr.product_encoder=Nhap.product_encoder
LEFT JOIN  care_pharma_dept_returnmed AS TraVe ON Pr.product_encoder=TraVe.product_encoder
RIGHT JOIN care_pharma_dept_returnmed_info AS TraVeInfo ON TraVeInfo.return_id=TraVe.return_id
WHERE      TraVeInfo.status_finish='1' AND NhapInfo.health_station='0' AND NhapInfo.status_finish='1'

