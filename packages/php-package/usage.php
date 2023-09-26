<?php
	// Get Specific Bank with All Their Stored Data and Use It
	//--------------------------------------------------------------
	
	//-- 1.1 - Get the Bank Data and Use It (MySQL Data) -----------------
	
	// Establish a connection to the MySQL database.
	$sqli = mysqli_connect("localhost", "root", "", "DATABASE_NAME");
	
	if (!$sqli) {
		echo "Connection aborted";
	}
	
	// Require the 'banks-system-MySQL.php' file and use the 'banksSQLSystem' namespace.
	require 'banks-system-MySQL.php';
	use banksSQL\banksSQLSystem;
	
	// Create the 'banksSQLSystem' object and assign the MySQL database variable and the path to the images.
	// Ensure that you have imported the required data tables, such as the 'banks' table or any other available data tables.
	$banksSQLSystem = new banksSQLSystem($sqli, "banks-data/banks-images");
	
	//-- First - Get the Data ------------------------
	
	$SQLBankData = null;
	
	//- 1.1.1 - You can get the bank if you have the bank SQL ID.
	$SQLBankData = json_decode($banksSQLSystem->GetBankFromSQLId(1), true);
	
	if ($SQLBankData['status'] == 'success') {
		// The bank was found.
		$SQLBankData = $SQLBankData['message']; // The bank data
		} else {
		// The bank was not found.
		$SQLBankData = null;
	}
	
	//- 1.1.2 - We can get the bank from the bank name and country.
	$BankName = "Union";
	$Country = 'JO';
	
	// Now use the bank name and country.
	$SQLBankData = json_decode($banksSQLSystem->GetBankFromNameAndCountry($BankName, $Country), true);
	
	if ($SQLBankData['status'] == 'success') {
		// The bank was found.
		$SQLBankData = $SQLBankData['message']; // The bank data
		} else {
		// The bank was not found.
		$SQLBankData = null;
	}
	
	
	//- 1.1.3 - We can get the bank from any of the bank's cards BIN codes.
	
	// Card Provided Data (from your system, user, or any third party such as the payment service provider).
	$CardNo = '4299 20## #### 6161'; // You can provide the full card number or just the bin code (at least 6 digits)
		
	$BankDataFromBIN = json_decode($banksSQLSystem->GetBankFromBIN($CardNo), true);
		
	if ($BankDataFromBIN['status'] == 'success') {
	    // The bank was found.
	    $SQLBankData = $BankDataFromBIN['message']; // The bank data
		} else {
		// The bank was not found.
		$SQLBankData = null;
	}
		
	//- 1.1.4 - We can get the bank from the bank swift code.
	$SwiftCode = 'BNPAKWKW'; // For example
	$SQLBankData = json_decode($banksSQLSystem->GetBankFromSwiftCode($SwiftCode), true);
	
	if ($SQLBankData['status'] == 'success') {
		// The bank was found.
		$SQLBankData = $SQLBankData['message']; // The bank data
		} else {
		// The bank was not found.
		$SQLBankData = null;
	}
	
	//- 1.1.5 - We can get the bank from any of the bank's email.
	$Email = 'info@uab.ae'; // For example
	$SQLBankData = json_decode($banksSQLSystem->GetBankFromEmail($Email), true);
	
	if ($SQLBankData['status'] == 'success') {
		// The bank was found.
		$SQLBankData = $SQLBankData['message']; // The bank data
		} else {
		// The bank was not found.
		$SQLBankData = null;
	}
	
	//- 1.1.6 - We can get the bank from their phone number.
	$PhoneNumber = '(+973) 17 51 51 51'; // For example
	$SQLBankData = json_decode($banksSQLSystem->GetBankFromPhoneNumber($PhoneNumber), true);
	
	if ($SQLBankData['status'] == 'success') {
		// The bank was found.
		$SQLBankData = $SQLBankData['message']; // The bank data
		} else {
		// The bank was not found.
		$SQLBankData = null;
	}
	
	//- 1.1.7 - We can get the bank from their website or domain.
	$DomainOrWebsiteURL = 'arabbank.jo'; // For example
	$SQLBankData = json_decode($banksSQLSystem->GetBankFromWebsiteOrDomain($DomainOrWebsiteURL), true);
	
	if ($SQLBankData['status'] == 'success') {
		// The bank was found.
		$SQLBankData = $SQLBankData['message']; // The bank data
		} else {
		// The bank was not found.
		$SQLBankData = null;
	}
	
	//- 1.1.8 - We can get all the world banks.
	$AllWorldBanks = json_decode($banksSQLSystem->GetAllWorldBanks(), true);
	
	$AllWorldBanksCardsCODE = '';
	
	if ($AllWorldBanks['status'] == 'success') {
		$AllWorldBanksArray = $AllWorldBanks['message'];
		
		foreach ($AllWorldBanksArray as $key => $value) {
			// Process each bank in the list if needed.
		}
	}
	
	//- 1.1.9 - We can get all country banks.
	$AllCountryBanks = json_decode($banksSQLSystem->GetAllCountryBank('jo'), true);
	
	$AllCountryBanksCardsCODE = '';
	
	if ($AllCountryBanks['status'] == 'success') {
		$AllCountryBanksArray = $AllCountryBanks['message'];
		
		foreach ($AllCountryBanksArray as $key => $value) {
			// Process each bank in the list if needed.
		}
	}
	
	//-- Second - Use the Data -------------------
	// We can now use the bank data as we want, such as:
	
	//- 1 - We can use the bank data to generate bank card UI.
	
	$BankID = $SQLBankData['id']; // This is the SQL Bank ID obtained from any of the previous methods. If it's null, the default card will be returned.
	$CardNo = '4644 52## #### 9571'; // Optional
	$NameOnCard = 'Abdalrahman Alajlouni'; // Optional
	$ExpiryDate = '04/25'; // Optional
	$CardType = 'Debit Card'; // Optional
	
	$HTMLCode = json_decode($banksSQLSystem->GetCardHTMLCode($BankID, $NameOnCard, $ExpiryDate, $CardNo, $CardType, '***'), true);
	
	$CardCode = '';
	
	if ($HTMLCode['status'] == 'success') {
		$CardCode = $HTMLCode['message'];
		} else {
		$CardCode = '';
	}
	
	// Now, we will display the generated UI Bank card:	
?>
<script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
<link href="https://fonts.googleapis.com/css?family=Alexandria" rel="stylesheet">
<link href="style.css" rel="stylesheet">
<div class="MainContCardSystem">
	
	
	<?php echo $CardCode;?>
	
	
</div>
