<?php
class PDF extends FPDI_with_annots
		{

			public $offset = 0;

			//Page header		
			function Header()			
			{					
			
			}
			//Page footer		
			function Footer()
			{	
				//Position at 1.5 cm from bottom	
				$this->SetY(-15);		
				$this->SetFont('Arial','',8);	
				$page_number = $this->PageNo() + $this->offset;
				//Page number	
				$this->Cell(0,10,'page '.$page_number.'',0,0,'C');	
			}			
		}

