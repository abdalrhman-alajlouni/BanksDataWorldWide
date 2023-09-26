<?php
	
	
	namespace banksSQL;
	
	class banksSQLSystem {
		
		private $sqli;
		private $AssetsPath;
		
		function __construct($sqli, $AssetsPath) {
			$this->sqli = $sqli;
			$this->AssetsPath = $AssetsPath;
		}
		
		public function GetCardSchema($CardNo) {
			
			$CardNo = str_replace('#', '1', $CardNo);
			$CardNo = str_replace(' ·', '1', $CardNo);
			
			$cardTypes = [
			[
			'mask' => '0000 000000 00000',
			'regex' => '^3[47]\\d{0,13}',
			'cardtype' => 'american express',
			'icon' => 'fontisto:american-express',
			],
			[
			'mask' => '0000 0000 0000 0000',
			'regex' => '^(?:6011|65\\d{0,2}|64[4-9]\\d?)\\d{0,12}',
			'cardtype' => 'discover',
			'icon' => 'brandico:discover',
			],
			[
			'mask' => '0000 000000 0000',
			'regex' => '^3(?:0([0-5]|9)|[689]\\d?)\\d{0,11}',
			'cardtype' => 'diners',
			'icon' => 'la:cc-diners-club',
			],
			[
			'mask' => '0000 0000 0000 0000',
			'regex' => '^(5[1-5]\\d{0,2}|22[2-9]\\d{0,1}|2[3-7]\\d{0,2})\\d{0,12}',
			'cardtype' => 'mastercard',
			'icon' => 'logos:mastercard',
			],
			[
			'mask' => '0000 000000 00000',
			'regex' => '^(?:2131|1800)\\d{0,11}',
			'cardtype' => 'jcb15',
			'icon' => 'logos:jcb',
			],
			[
			'mask' => '0000 0000 0000 0000',
			'regex' => '^(?:35\\d{0,2})\\d{0,12}',
			'cardtype' => 'jcb',
			'icon' => 'logos:jcb',
			],
			[
			'mask' => '0000 0000 0000 0000',
			'regex' => '^(?:5[0678]\\d{0,2}|6304|67\\d{0,2})\\d{0,12}',
			'cardtype' => 'maestro',
			'icon' => 'logos:maestro',
			],
			[
			'mask' => '0000 0000 0000 0000',
			'regex' => '^4\\d{0,15}',
			'cardtype' => 'visa',
			'icon' => 'ri:visa-line',
			],
			[
			'mask' => '0000 0000 0000 0000',
			'regex' => '^62\\d{0,14}',
			'cardtype' => 'unionpay',
			'icon' => 'logos:unionpay',
			],
			[
			'mask' => '0000 0000 0000 0000',
			'cardtype' => 'Unknown',
			'icon' => '',
			],
			];
			
			foreach ($cardTypes as $type) {
				if (!empty($type['regex']) && preg_match('/' . $type['regex'] . '/', str_replace(' ', '', $CardNo))) {
					return $type['icon'];
				}
			}
			
			return ''; // Default if no match is found	
		}
		
		public function GetBankFromBIN($CardNoOrBIN) {
			$sqli = $this->sqli;
			
			$CardNoOrBIN = str_replace(' ', '', $CardNoOrBIN);
			
			if (strlen($CardNoOrBIN) < 6 || strlen($CardNoOrBIN) > 30) {
				$ResArray = [];
				$ResArray['status'] = "error";
				$ResArray['message'] = 'Invalid Card Or BIN Number.';
				$ResArrayJSON = json_encode($ResArray, true);
				return $ResArrayJSON;
			}
			
			$BIN = substr($CardNoOrBIN, 0, 6);
			
			
			$query01fBIN = mysqli_prepare($sqli, "SELECT * FROM bins WHERE bin=?");
			mysqli_stmt_bind_param($query01fBIN, "s", $BIN);
			mysqli_stmt_execute($query01fBIN);
			$result01fBIN = $query01fBIN->get_result();
			$rownnumber01fBIN = mysqli_num_rows($result01fBIN);
			if ($rownnumber01fBIN > 0) {
				$BINData = $result01fBIN->fetch_assoc();
				
				
				
				$query01f = mysqli_prepare($sqli, "SELECT * FROM banks WHERE id=?");
				mysqli_stmt_bind_param($query01f, "i", $BINData['bank_id']);
				mysqli_stmt_execute($query01f);
				$result01f = $query01f->get_result();
				$rownnumber01f = mysqli_num_rows($result01f);
				if ($rownnumber01f > 0) {
					$BankData = $result01f->fetch_assoc();
					
					$ResArray = [];
					$ResArray['status'] = "success";
					$ResArray['message'] = $BankData;
					$ResArrayJSON = json_encode($ResArray, true);
					return $ResArrayJSON;	
					
					} else {
					$ResArray = [];
					$ResArray['status'] = "error";
					$ResArray['message'] = 'No data found in the database.';
					$ResArrayJSON = json_encode($ResArray, true);
					return $ResArrayJSON;
				}
				
				
				} else {
				$ResArray = [];
				$ResArray['status'] = "error";
				$ResArray['message'] = 'No data found in the database.';
				$ResArrayJSON = json_encode($ResArray, true);
				return $ResArrayJSON;
			}

		}
		
		public function GetBankFromNameAndCountry($BankName, $Country) {
			
			$sqli = $this->sqli;
			
			$Country = strtolower($Country);
			$Country = str_replace(" ", "", $Country);
			$Country = trim($Country);
			
			
			$query01f = mysqli_prepare($sqli, "SELECT * FROM banks WHERE LOWER(country)=? AND official_name=?");
			mysqli_stmt_bind_param($query01f, "ss", $Country, $BankName);
			mysqli_stmt_execute($query01f);
			$result01f = $query01f->get_result();
			$rownnumber01f = mysqli_num_rows($result01f);
			if ($rownnumber01f > 0) {
				$BankData = $result01f->fetch_assoc();
				}else{
				
				$BankNameNew = strtolower($BankName);
				$BankNameNew = preg_replace('/\([^)]*\)/', '', $BankNameNew);
				$BankNameNew = str_replace(",", "", $BankNameNew);
				$BankNameNew = str_replace(".", "", $BankNameNew);
				$BankNameNew = str_replace("-", "", $BankNameNew);
				$BankNameNew = trim($BankNameNew);
				
				
				
				
				$SQLReplcaeS = "REPLACE(REPLACE(REPLACE(REPLACE(LOWER(official_name), ' ', ''), ',', ''), '.',''), '-', '')";
				$SQLReplcaeS = "REPLACE(".$SQLReplcaeS.", ')', '')";
				$SQLReplcaeS = "REPLACE(".$SQLReplcaeS.", '(', '')";
				
				$query22 = mysqli_prepare($sqli, "SELECT * FROM banks WHERE LOWER(country) = ? AND ".$SQLReplcaeS." LIKE ?");
				
				$BankNameNewPercent = str_replace("  ", " ", $BankNameNew);
				$BankNameNewPercent = str_replace(" ", "%", $BankNameNewPercent);
				$partialBankName = '%' . $BankNameNewPercent . '%';
				
				
				mysqli_stmt_bind_param($query22, "ss", $Country, $partialBankName);
				
				mysqli_stmt_execute($query22);
				$result22 = $query22->get_result();
				$rownnumberuser = mysqli_num_rows($result22);
				
				if ($rownnumberuser == 0) {
					$Bankcharacters = str_split($BankName);
					$SearchBank = "";
					$MatchesTime = 0;
					foreach ($Bankcharacters as $char) {
						
						$SearchBank .= $char;
						
						$SearchTerm = '%' . $SearchBank . '%';
						
						$query01 = mysqli_prepare($sqli, "SELECT * FROM banks WHERE LOWER(country)=? AND official_name LIKE ?");
						mysqli_stmt_bind_param($query01, "ss", $Country, $SearchTerm);
						mysqli_stmt_execute($query01);
						$result01 = $query01->get_result();
						$rownnumber01 = mysqli_num_rows($result01);
						if ($rownnumber01 > 0) {
							$MatchesTime++;
							$BankData = $result01->fetch_assoc();
							}else{
							
							if ($MatchesTime > 10) {
								break;
								}else{
								$BankData = null;
							}
							
						}
					}
					}else{
					$BankData = $result22->fetch_assoc();
				}
				
				if ($BankData == null) {
					$reversedBankName = strrev(strtolower($BankName));
					$BankCharactersRev = str_split($reversedBankName);
					$SearchBankR = "";
					$MatchesTimeR = 0;
					foreach ($BankCharactersRev as $charR) {
						
						
						$SearchBankR .= $charR;
						
						$SearchTermR = '%' . $SearchBankR . '%';
						
						$query01R = mysqli_prepare($sqli, "SELECT * FROM banks WHERE LOWER(country)=? AND LOWER(REVERSE(official_name)) LIKE ?");
						mysqli_stmt_bind_param($query01R, "ss", $Country, $SearchTermR);
						mysqli_stmt_execute($query01R);
						$result01R = $query01R->get_result();
						$rownnumber01R = mysqli_num_rows($result01R);
						if ($rownnumber01R > 0) {
							$MatchesTimeR++;
							$BankData = $result01R->fetch_assoc();
							}else{
							
							if ($MatchesTimeR > 10) {
								break;
								}else{
								$BankData = null;
							}
							
						}
						
						
					}
					
				}
			}
			
			if ($BankData == null) {
				$ResArray = [];
				$ResArray['status'] = "error";
				$ResArray['message'] = "Unable to find the bank.";
				$ResArrayJSON = json_encode($ResArray, true);
				return $ResArrayJSON;	
				}else{
				$ResArray = [];
				$ResArray['status'] = "success";
				$ResArray['message'] = $BankData;
				$ResArrayJSON = json_encode($ResArray, true);
				return $ResArrayJSON;	
			}
			
			
		}
		
		public function GetAllCountryBank($Country) {
			$sqli = $this->sqli;	
			$Banks = [];
			$query01f = mysqli_prepare($sqli, "SELECT * FROM banks where country=?");
			mysqli_stmt_bind_param($query01f, "s", $Country);
			mysqli_stmt_execute($query01f);
			$result01f = $query01f->get_result();
			$rownnumber01f = mysqli_num_rows($result01f);
			
			if ($rownnumber01f == 0) {
				$ResArray = [];
				$ResArray['status'] = "error";
				$ResArray['message'] = "Unable to find any bank.";
				$ResArrayJSON = json_encode($ResArray, true);
				return $ResArrayJSON;	
			}
			
			while($BankData = $result01f->fetch_assoc()) {
				$Banks[] = $BankData;
			}
			$ResArray = [];
			$ResArray['status'] = "success";
			$ResArray['message'] = $Banks;
			$ResArrayJSON = json_encode($ResArray, true);
			return $ResArrayJSON;
			
			
		}
		
		public function GetAllWorldBanks() {
			$sqli = $this->sqli;
			$Banks = [];
			$query01f = mysqli_prepare($sqli, "SELECT * FROM banks");
			mysqli_stmt_execute($query01f);
			$result01f = $query01f->get_result();
			$rownnumber01f = mysqli_num_rows($result01f);
			
			if ($rownnumber01f == 0) {
				$ResArray = [];
				$ResArray['status'] = "error";
				$ResArray['message'] = "Unable to find any bank.";
				$ResArrayJSON = json_encode($ResArray, true);
				return $ResArrayJSON;	
			}
			
			while($BankData = $result01f->fetch_assoc()) {
				$Banks[] = $BankData;
			}
			$ResArray = [];
			$ResArray['status'] = "success";
			$ResArray['message'] = $Banks;
			$ResArrayJSON = json_encode($ResArray, true);
			return $ResArrayJSON;
		}
		
		
		public function GetBankFromSQLId($SQLBankID) {
			$sqli = $this->sqli;
			
			$BankData = null;
			
			$query01f = mysqli_prepare($sqli, "SELECT * FROM banks WHERE id=?");
			mysqli_stmt_bind_param($query01f, "i", $SQLBankID);
			mysqli_stmt_execute($query01f);
			$result01f = $query01f->get_result();
			$rownnumber01f = mysqli_num_rows($result01f);
			if ($rownnumber01f > 0) {
				$BankData = $result01f->fetch_assoc();
			}
			
			if ($BankData == null) {
				$ResArray = [];
				$ResArray['status'] = "error";
				$ResArray['message'] = "Unable to find the bank.";
				$ResArrayJSON = json_encode($ResArray, true);
				return $ResArrayJSON;	
				}else{
				$ResArray = [];
				$ResArray['status'] = "success";
				$ResArray['message'] = $BankData;
				$ResArrayJSON = json_encode($ResArray, true);
				return $ResArrayJSON;	
			}
			
		}
		
		public function GetCardHTMLCode($SQLBankID = null, $NameOnCard = '-', $ExpiryDate = 'MM/YY', $CardNo = '···· ···· ···· ····', $CardType = 'Card', $CSV = '***') {
			$AssetsPath = $this->AssetsPath;	
			$sqli = $this->sqli;
			$CardNo = str_replace('#', ' ·', $CardNo);
			
			$CardSchemaIcon = $this->GetCardSchema($CardNo);
			
			$query01f = mysqli_prepare($sqli, "SELECT * FROM banks WHERE id=?");
			mysqli_stmt_bind_param($query01f, "i", $SQLBankID);
			mysqli_stmt_execute($query01f);
			$result01f = $query01f->get_result();
			$rownnumber01f = mysqli_num_rows($result01f);
			if ($rownnumber01f > 0) {
				$BankData = $result01f->fetch_assoc();
				
				$HTMLCode = '
				
				<div class="MainCardBackFront">
				<div class="flip-card-inner">
				
				<div class="MainCardCont MainCardFront" dir="ltr" style="background: linear-gradient(37deg, '.$BankData['card_color'].', '.$BankData['card_color_gradient'].');color: '.$BankData['card_text_color'].';">
				
				<div class="MainCardHeader">
				<p>'.$CardType.'</p>
				<img src="'.$AssetsPath.'/'.$BankData['icon'].'" class="MainCardBankLogo">
				</div>
				
				<div class="MainCardChipCont">
				<img src="'.$AssetsPath.'/card-chip-800x450-by-usa.visa.com.png" class="MainCardChipImg">
				<iconify-icon icon="ion:wifi" class="MainCardChipWifiIcon"></iconify-icon>
				</div>
				
				<p class="MainCardCardNo">'.$CardNo.'</p>
				
				<div class="MainCardFooterCont">
				
				<div class="MainCardFooterNameDataCont">
				
				<div>
				<p class="MainCardFooterNameDataTitle" style="color: '.$BankData['card_text_color'].'a3;">Name</p>
				<p class="MainCardFooterNameDataContent">'.$NameOnCard.'</p>
				</div>
				
				<div>
				<p class="MainCardFooterNameDataTitle" style="color: '.$BankData['card_text_color'].'a3;">Expires End</p>
				<p class="MainCardFooterNameDataContent">'.$ExpiryDate.'</p>
				</div>
				
				</div>
				
				<iconify-icon icon="'.$CardSchemaIcon.'" class="MainCardFooterRightCardSchema"></iconify-icon>
				
				</div>
				
				</div>
				';
				
				if ($BankData['card_text_color'] != '#ffffff') {
				$HTMLCode .= '
				<div class="MainCardCont MainCardBack" dir="ltr" style="background: linear-gradient(37deg, '.$BankData['card_color'].', '.$BankData['card_color_gradient'].');color: '.$BankData['card_text_color'].';gap: 0px">
				<div class="MainCardBackTopInfo" style="padding-bottom: 15px;">
					<p>'.strtoupper($BankData['country']).' - '.strtoupper($BankData['official_name']).'</p>
				</div>
				';
				}else{
				$HTMLCode .= '
				<div class="MainCardCont MainCardBack" dir="ltr" style="background: linear-gradient(37deg, '.$BankData['card_color_gradient'].', '.$BankData['card_color'].');color: '.$BankData['card_text_color'].';gap: 0px">
				<div class="MainCardBackTopInfo">
					<p>'.strtoupper($BankData['country']).' - '.strtoupper($BankData['official_name']).'</p>
				</div>
				';
				}
				
				$HTMLCode .= '
				
				
				<div class="MainCardBackBlackLine"></div>
				<div class="MainCardBackUnderLineCont">
					<div class="MainCardBackSignCont">
						<div class="MainCardBackSignMain">
							<div class="MainCardBackSignLine"></div>
							<div class="MainCardBackSignLine"></div>
							<div class="MainCardBackSignLine"></div>
							<div class="MainCardBackSignLine"></div>
							<div class="MainCardBackSignLine"></div>
						</div>
						<p class="MainCardBackSignCSVno">***</p>
					</div>
					
					<div class="MainCardBackMiddleCont">
						';
						
						if ($BankData['card_text_color'] != '#ffffff') {
							$HTMLCode .= '
						<img src="'.$AssetsPath.'/'.$BankData['icon'].'" class="MainCardBackMiddleBankLogo" style="max-height: 45px;background: #d5d5d5;padding: 5px;border-radius: 5px;">
						';
						}else{
						$HTMLCode .= '
						<img src="'.$AssetsPath.'/'.$BankData['icon'].'" class="MainCardBackMiddleBankLogo">
						';
						}
						
						$HTMLCode .= '
						<div>
							<p>AUTHORIZED SIGNATURE</p>
							<p>This card may only be used by authorized signatory and subject to the current terms and conditions of use.</p>
						</div>
						
					</div>
					
					
					<div class="MainCardBackFooterCont">
						<div>
							<p>Contact '.$BankData['ToShowName'].':</p>
							';
							
							$OneAdded = false;
							
							if ($BankData['email'] != "" && $BankData['email'] != null && $BankData['email'] != 'null' && isset($BankData['email'])) {
							$HTMLCode .= '
							<p class="MainCardBackFooterContactItem">Email: <a href="mailto:'.strtolower($BankData['email']).'">'.strtolower($BankData['email']).'</a></p>
							';
							$OneAdded = true;
							}
							
							if ($BankData['customer_service'] != "" && $BankData['customer_service'] != null && $BankData['customer_service'] != 'null' && isset($BankData['customer_service'])) {
							$HTMLCode .= '
							<p class="MainCardBackFooterContactItem">Phone Number: <a href="tel:'.strtolower($BankData['customer_service']).'">'.strtolower($BankData['customer_service']).'</a></p>
							';
							$OneAdded = true;
							}
							
							if ($BankData['website'] != "" && $BankData['website'] != null && $BankData['website'] != 'null' && isset($BankData['website'])) {
							$HTMLCode .= '
							<p class="MainCardBackFooterContactItem">Website: <a href="'.strtolower($BankData['website']).'" target="_blank">'.strtolower($BankData['website']).'</a></p>
							';
							$OneAdded = true;
							}
							
							if ($OneAdded == false) {
							$HTMLCode .= '
							<p class="MainCardBackFooterContactItem">Unable to find any contact info for the bank.</p>
							';
							}
							
							$HTMLCode .= '
						</div>
						<div class="MainCardBackFooterContactItemCardSchema">
						<iconify-icon icon="'.$CardSchemaIcon.'" class="MainCardBackFooterContactItemCardSchema2"></iconify-icon>
					    </div>
					</div>
					
				</div>
				
				
				</div>
				
				
				</div>
				</div>
				';	
				
				$ResArray = [];
				$ResArray['status'] = "success";
				$ResArray['found'] = true;
				$ResArray['message'] = $HTMLCode;
				$ResArrayJSON = json_encode($ResArray, true);
				return $ResArrayJSON;	
				
				}else{
				
				$HTMLCode = '
				
				<div class="MainCardBackFront">
				<div class="flip-card-inner">
				
				<div class="MainCardCont MainCardFront" dir="ltr">
				
				<div class="MainCardHeader">
				<p>'.$CardType.'</p>
				<iconify-icon icon="mdi:bank-outline" class="MainCardBankLogoDefaultIcon"></iconify-icon>
				</div>
				
				<div class="MainCardChipCont">
				<img src="'.$AssetsPath.'/card-chip-800x450-by-usa.visa.com.png" class="MainCardChipImg">
				<iconify-icon icon="ion:wifi" class="MainCardChipWifiIcon"></iconify-icon>
				</div>
				
				<p class="MainCardCardNo">'.$CardNo.'</p>
				
				<div class="MainCardFooterCont">
				
				<div class="MainCardFooterNameDataCont">
				
				<div>
				<p class="MainCardFooterNameDataTitle">Name</p>
				<p class="MainCardFooterNameDataContent">'.$NameOnCard.'</p>
				</div>
				
				<div>
				<p class="MainCardFooterNameDataTitle">Expires End</p>
				<p class="MainCardFooterNameDataContent">'.$ExpiryDate.'</p>
				</div>
				
				</div>
				
				<iconify-icon icon="'.$CardSchemaIcon.'" class="MainCardFooterRightCardSchema"></iconify-icon>
				
				</div>
				
				</div>
				
				
				<div class="MainCardCont MainCardBack" dir="ltr">
				
				<div class="MainCardBackBlackLine"></div>
				
				</div>
				
				
				</div>
				</div>
				';	
				
				$ResArray = [];
				$ResArray['status'] = "success";
				$ResArray['found'] = false;
				$ResArray['message'] = $HTMLCode;
				$ResArrayJSON = json_encode($ResArray, true);
				return $ResArrayJSON;	
			}
			
		}
		
		private function TempRemoveCountryCodeFromPhone($Phone) {	
			$countries = [
			93,355,213,1684,376,244,1264,0,1268,54,374,297,61,43,994,1242,973,880,1246,375,32,501,229,1441,975,591,387,267,0,55,246,673,359,226,257,855,237,1,238,1345,236,235,56,86,61,672,57,269,242,242,682,506,225,385,53,357,420,45,
			253,1767,1809,670,593,20,503,240,291,372,251,61,500,298,679,358,33,
			594,689,0,241,220,995,49,233,350,30,299,1473,590,1671,502,44,224,245,592,509,0,504,852,36,354,91,62,98,964,353,972,39,1876,81,44,962,7,254,686,850,82,965,996,856,371,961,266,231,218,423,370,352,853,389,261,265,60,960,
			223,356,44,692,596,222,230,269,52,691,373,377,976,1664,212,258,95,264,674,977,599,31,687,64,505,227,234,683,672,1670,47,968,92,680,
			970,507,675,595,51,63,0,48,351,1787,974,262,40,70,250,290,1869,1758,508,1784,684,378,239,966,221,381,248,232,65,421,386,44,677,252,27,0,211,34,94,249,597,47,268,46,41,963,886,992,255,66,228,690,676,1868,216,90,7370,
			1649,688,256,380,971,44,1,1,598,998,678,39,58,84,1284,1340,681,212,967,38,260,263
			];
			foreach($countries as $key => $value) {
				if (str_starts_with($Phone, "+".$value)) {
					$Phone = str_replace("+".$value, "", $Phone);	
					}elseif (str_starts_with($Phone, "00".$value)) {
					$Phone = str_replace("00".$value, "", $Phone);	
				}
			}
			return $Phone;
		}
		
		private function TempRemoveChar($String) {
			$String = trim($String);
			$String = strtolower($String);
			$String = str_replace(" ", "", $String);
			$String = str_replace(",", "", $String);
			$String = str_replace(".", "", $String);
			$String = str_replace("-", "", $String);
			$String = str_replace(")", "", $String);
			$String = str_replace("(", "", $String);
			return $String;
		}
		
		private function TempRemoveCharSQL($VarName) {
			$SQLReplcaeS = "LOWER(".$VarName.")";
			$SQLReplcaeS = "REPLACE(".$SQLReplcaeS.", ' ', '')";
			$SQLReplcaeS = "REPLACE(".$SQLReplcaeS.", ',', '')";
			$SQLReplcaeS = "REPLACE(".$SQLReplcaeS.", '.','')";
			$SQLReplcaeS = "REPLACE(".$SQLReplcaeS.", '-', '')";
			$SQLReplcaeS = "REPLACE(".$SQLReplcaeS.", ')', '')";
			$SQLReplcaeS = "REPLACE(".$SQLReplcaeS.", '(', '')";
			$SQLReplcaeS = "REPLACE(".$SQLReplcaeS.", '+', '')";
			return $SQLReplcaeS;
		}
		
		public function GetBankFromPhoneNumber($PhoneNumber, $OneTime = null) {
			$sqli = $this->sqli;
			$BankData = null;
			
			$PhoneNumber = $this->TempRemoveChar($PhoneNumber);
			$PhoneNumber = $this->TempRemoveCountryCodeFromPhone($PhoneNumber);
			$SQLReplcaeS = $this->TempRemoveCharSQL('customer_service');
			
			$query01f = mysqli_prepare($sqli, "SELECT * FROM banks WHERE ".$SQLReplcaeS." LIKE ? or ".$SQLReplcaeS." LIKE ?");
			$PhoneNumberSQL = "%".$PhoneNumber."%";
			$RemovedFirstPhoneNumberSQL = "%".substr($PhoneNumber,1)."%";
			mysqli_stmt_bind_param($query01f, "ss", $PhoneNumberSQL, $RemovedFirstPhoneNumberSQL);
			mysqli_stmt_execute($query01f);
			$result01f = $query01f->get_result();
			$rownnumber01f = mysqli_num_rows($result01f);
			if ($rownnumber01f > 0) {
				$BankData = $result01f->fetch_assoc();
				}else{
				
				$query01f2 = mysqli_prepare($sqli, "SELECT * FROM banks");
				mysqli_stmt_execute($query01f2);
				$result01f2 = $query01f2->get_result();
				$rownnumber01f2 = mysqli_num_rows($result01f2);
				
				if ($rownnumber01f2 > 0) {
					while($BankDataTemp = $result01f2->fetch_assoc()) {
						$ReadySQLPhone = $this->TempRemoveChar($BankDataTemp['customer_service']);
						if (str_contains($ReadySQLPhone, $PhoneNumber) || str_contains($ReadySQLPhone, substr($PhoneNumber, 1))) {
							$BankData = $BankDataTemp;
						}
					}
				}
			}
			
			if ($BankData == null) {
				$ResArray = [];
				$ResArray['status'] = "error";
				$ResArray['message'] = "Unable to find the bank.";
				$ResArrayJSON = json_encode($ResArray, true);
				return $ResArrayJSON;	
				}else{
				$ResArray = [];
				$ResArray['status'] = "success";
				$ResArray['message'] = $BankData;
				$ResArrayJSON = json_encode($ResArray, true);
				return $ResArrayJSON;	
			}
			
			
		}
		
		public function GetBankFromEmail($Email) {
			$sqli = $this->sqli;	
			
			if (!filter_var($Email, FILTER_VALIDATE_EMAIL)) {
				$ResArray = [];
				$ResArray['status'] = "error";
				$ResArray['message'] = "Invalid email address.";
				$ResArrayJSON = json_encode($ResArray, true);
				return $ResArrayJSON;
			}
			
			$BankData = null;
			
			$Email = $this->TempRemoveChar($Email);
			$SQLReplcaeS = $this->TempRemoveCharSQL('email');
			
			$Emaildomain = substr($Email, strpos($Email, '@') + 1);
			
			$query01f = mysqli_prepare($sqli, "SELECT * FROM banks WHERE ".$SQLReplcaeS." LIKE ?");
			$EmailSQL = "%".$Email."%";
			mysqli_stmt_bind_param($query01f, "s", $EmailSQL);
			mysqli_stmt_execute($query01f);
			$result01f = $query01f->get_result();
			$rownnumber01f = mysqli_num_rows($result01f);
			if ($rownnumber01f > 0) {
				$BankData = $result01f->fetch_assoc();
				}else{
				
				$query01f2 = mysqli_prepare($sqli, "SELECT * FROM banks");
				mysqli_stmt_execute($query01f2);
				$result01f2 = $query01f2->get_result();
				$rownnumber01f2 = mysqli_num_rows($result01f2);
				
				if ($rownnumber01f2 > 0) {
					while($BankDataTemp = $result01f2->fetch_assoc()) {
						
						
						
						if ($BankDataTemp['website'] != "" && $BankDataTemp['website'] != null && $BankDataTemp['website'] != 'null') {
							
							$WebsiteC = parse_url($BankDataTemp['website']);
							$WebsiteDomain = $this->TempRemoveChar($WebsiteC['host']);
							
							if (str_contains($WebsiteDomain, $Emaildomain)) {
								$BankData = $BankDataTemp;
							}
						}
						
					}
				}
				
			}
			
			if ($BankData == null) {
				$ResArray = [];
				$ResArray['status'] = "error";
				$ResArray['message'] = "Unable to find the bank.";
				$ResArrayJSON = json_encode($ResArray, true);
				return $ResArrayJSON;	
				}else{
				$ResArray = [];
				$ResArray['status'] = "success";
				$ResArray['message'] = $BankData;
				$ResArrayJSON = json_encode($ResArray, true);
				return $ResArrayJSON;	
			}
			
			
		}
		public function GetBankFromSwiftCode($SwiftCode) {
			$sqli = $this->sqli;
			
			$BankData = null;
			
			$SwiftCode = $this->TempRemoveChar($SwiftCode);
			$SQLReplcaeS = $this->TempRemoveCharSQL('swift_code');
			
			$query01f = mysqli_prepare($sqli, "SELECT * FROM banks WHERE ".$SQLReplcaeS." LIKE ?");
			$SwiftCodeSQL = "%".$SwiftCode."%";
			mysqli_stmt_bind_param($query01f, "s", $SwiftCodeSQL);
			mysqli_stmt_execute($query01f);
			$result01f = $query01f->get_result();
			$rownnumber01f = mysqli_num_rows($result01f);
			if ($rownnumber01f > 0) {
				$BankData = $result01f->fetch_assoc();
			}
			
			if ($BankData == null) {
				$ResArray = [];
				$ResArray['status'] = "error";
				$ResArray['message'] = "Unable to find the bank.";
				$ResArrayJSON = json_encode($ResArray, true);
				return $ResArrayJSON;	
				}else{
				$ResArray = [];
				$ResArray['status'] = "success";
				$ResArray['message'] = $BankData;
				$ResArrayJSON = json_encode($ResArray, true);
				return $ResArrayJSON;	
			}
			
			
		}
		
		public function GetBankFromWebsiteOrDomain($WebsiteOrDomain) {
			$sqli = $this->sqli;	
			$BankData = null;
			
			
			
			if (filter_var($WebsiteOrDomain, FILTER_VALIDATE_URL) === FALSE) {
				$Domain = $WebsiteOrDomain;
				}else{
				$WebsiteC = parse_url($WebsiteOrDomain);
				$Domain = $WebsiteC['host'];
			}
			
			$Domain = $this->TempRemoveChar($Domain);
			$SQLReplcaeS = $this->TempRemoveCharSQL('website');
			
			
			$query01f = mysqli_prepare($sqli, "SELECT * FROM banks WHERE ".$SQLReplcaeS." LIKE ?");
			$DomainSQL = "%".$Domain."%";
			mysqli_stmt_bind_param($query01f, "s", $DomainSQL);
			mysqli_stmt_execute($query01f);
			$result01f = $query01f->get_result();
			$rownnumber01f = mysqli_num_rows($result01f);
			if ($rownnumber01f > 0) {
				$BankData = $result01f->fetch_assoc();
				}else{
				
				$query01f2 = mysqli_prepare($sqli, "SELECT * FROM banks");
				mysqli_stmt_execute($query01f2);
				$result01f2 = $query01f2->get_result();
				$rownnumber01f2 = mysqli_num_rows($result01f2);
				
				if ($rownnumber01f2 > 0) {
					while($BankDataTemp = $result01f2->fetch_assoc()) {
						
						if ($BankDataTemp['website'] != "" && $BankDataTemp['website'] != null && $BankDataTemp['website'] != 'null') {
							
							$WebsiteC = parse_url($BankDataTemp['website']);
							$WebsiteDomain = $this->TempRemoveChar($WebsiteC['host']);
							
							if (str_contains($WebsiteDomain, $Domain)) {
								$BankData = $BankDataTemp;
							}
						}
						
					}
				}
				
			}
			
			if ($BankData == null) {
				$ResArray = [];
				$ResArray['status'] = "error";
				$ResArray['message'] = "Unable to find the bank.";
				$ResArrayJSON = json_encode($ResArray, true);
				return $ResArrayJSON;	
				}else{
				$ResArray = [];
				$ResArray['status'] = "success";
				$ResArray['message'] = $BankData;
				$ResArrayJSON = json_encode($ResArray, true);
				return $ResArrayJSON;	
			}	
			
			
		}
		
		
	}
?>			
