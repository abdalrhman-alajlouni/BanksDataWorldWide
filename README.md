
<br/>
<p align="center">
    <img src="https://banksdataworldwide.com/assets/images/linkLogo.png" alt="Logo" width="50%" height="auto">


  <p align="center">
    Open-source DB encompassing data for all banks across the world!
    <br/>
    <br/>

  </p>
</p>

<div align="center">

![Downloads](https://img.shields.io/github/downloads/abdalrhman-alajlouni/BanksDataWorldWide/total)
![Contributors](https://img.shields.io/github/contributors/abdalrhman-alajlouni/BanksDataWorldWide?color=dark-green)
![Issues](https://img.shields.io/github/issues/abdalrhman-alajlouni/BanksDataWorldWide)
![License](https://img.shields.io/github/license/abdalrhman-alajlouni/BanksDataWorldWide)

</div>

# About
BankDataWorldwide is an all-encompassing project that provides a centralized repository of information on banks from around the world. BanksDataWorldWide offer an extensive database containing logos, names, SWIFT codes, email addresses, websites, card colors, text colors, brand colors, countries, official names, and more for banks worldwide.

BanksDataWorldWide project comes with a variety of ready-to-use tools and packages. These tools allow you to effortlessly access and utilize bank data in your applications. Whether you need to design card user interfaces, or perform any other banking-related task, BanksDataWorldWide project has you covered.

# Table of Contents


- [Live Demo](#-demo)
- [Features](#-features)
- [ToDo List](#-todo-list)
- [Data Usage](#-data-usage)
- [How to Contribute](#-contribute)
- [Acknowledgments](#-acknowledgments)
- [Contact](#-contact)
- [Authors](#-authors)
- [License](#license)
- [Disclaimer and Limitations](#disclaimer-and-limitations)

# 🔥 Demo

### Check out the live demo at [Banks Data World Wide Website](https://banksdataworldwide.com/#demo).
<br>
<p align="center">
<img src="https://banksdataworldwide.com/assets/images/website.png" width="100%" title="logo">
</p>

# 🌠 Features

- Retrieve bank information by address, email, SWIFT code, or name.
- Access banks by country or globally.
- Generate bank cards with matching colors.
- Retrieve bank information by phone number.
- Utilize BIN codes to fetch bank details from customer card numbers.
- Access card scheme information (e.g., Visa, MasterCard).

# ⚡ ToDo List
- Collecting data on all the world's banks - (210/32000).
- Offer new technologies and packages to enhance access and use of data for all the world's banks.
- Create a bank branches system by providing information on all branches for each bank, including branch details, SWIFT Code, address, phone number, and more.
- After collecting information on all the world's banks and branches, establish an IBAN System for each of them, including an IBAN Calculator, IBAN Detector, and validator, as well as information on IBAN structure and more.
- Upgrade the data structure and quality and provide more data about the banks:
All the BINS Numbers for each bank.
The financial statements and insights.
Auditor.
Bank TOS.
Year founded.
Founders.


# ⚙ Data Usage

**You can use the data in both SQL and JSON as you like. However, we have created a ready-to-use PHP package with many methods for using the data, and there is a guide explaining how to use these methods:**
usage.php:
```php
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
```

# ❤ Contribute


### Contributions to the Banks Data World Wide project are welcome! Follow these steps to contribute:

1. Fork the repository.
2. Create a new branch for your feature or bug fix.
3. Make your changes and test thoroughly.
4. Create a pull request with a clear description of your changes.

### And you can use the project contribution tools:
- **[Easly Add a Bank](https://banksdataworldwide.com/contribute/add-bank)**
- **[Easly Edit a Bank](https://banksdataworldwide.com/contribute/edit-bank)**
  
 <br />
 
### Depending on your level of contribution, you'll be recognized as follows:

- 🟫 Bronze: Add 20 banks
- ⬜ Silver: Add 50 banks
- 🟨 Gold: Add 200 banks
- 🟪 Platinum: Add 500 banks
- 🟥 Platinum Plus: Add 1000 banks
  
## 🙌 Contributors

 🟥 Platinum Plus:
<hr>
 🟪 Platinum:
<hr>
 🟨 Gold:
<p align="left">
<a href="https://github.com/bashar444">
<img width="56px" src="https://banksdataworldwide.com/assets/images/BasharProfilePhoto.png">
</a>
<p/>
 
<hr>
 ⬜ Silver:
<hr>
 🟫 Bronze:
<hr>

## 💫 Acknowledgments

A big thank you to all the Stargazers who have shown their support for this project! 🌟

Your contributions help make Banks Data World Wide a valuable resource for everyone.

## 📞 Contact
- Open an Issue: If you encounter a technical issue or have a specific request related to the project.
- Compliance and Problem Reporting: For compliance-related matters or to report any problems contact us via **[the contact form](https://banksdataworldwide.com/#contact)**.
- Discord Community: Join **[BanksDataWorldWide vibrant Discord community](https://discord.gg/DZh44Smeuk)** for chatting with fellow contributors, planning and showcasing your achievements, and inquiring.

## 🕺 Authors

### Bashar Al-Thawabta:
 
- **[Github](https://github.com/bashar444)**
- **[LinkedIn Profile](https://www.linkedin.com/in/bashar-al-thawabta/)**

<br />

### Abdalrahman Alajlouni:
  
- **[Github](https://github.com/abdalrhman-alajlouni)**
- **[LinkedIn Profile](https://www.linkedin.com/in/abdalrahman-alajlouni-612511229/)**


## License

[MIT](https://choosealicense.com/licenses/mit/)

## Disclaimer and Limitations

- Accuracy of Information:
The information provided in this project, including BIN codes and related data, is intended for informational purposes only. We make no representations or warranties regarding the accuracy, completeness, or reliability of the information. Users are encouraged to independently verify all information.

- Not Responsible for Misuse:
We are not responsible for any misuse, misinterpretation, or illegal use of the information provided in this project. Users are expected to use this information responsibly and in compliance with all applicable laws and regulations.

- Data Sources:
This project may rely on data from various sources, including publicly available information. We do not own or control these data sources and are not responsible for their accuracy, availability, or content.

- Legal Compliance:
Users of this project are solely responsible for ensuring that their use of the information complies with all applicable laws, regulations, and contractual agreements. We do not provide legal advice.

- No Endorsement:
Any mention of specific financial institutions, brands, or organizations in this project does not constitute an endorsement or affiliation with them.

- Open Source Contributions:
Contributions from the open-source community are welcome but are subject to review and acceptance. Contributors are expected to adhere to project guidelines and standards.

- No Liability:
To the extent permitted by applicable law, we disclaim all liability for any direct, indirect, incidental, special, or consequential damages resulting from the use or inability to use this project or the information provided herein.

- Changes and Updates:
We reserve the right to modify, update, or remove information from this project at any time without prior notice. Users are encouraged to check for updates regularly.
<br />
<p align="center">
<img width="50%" src="https://banksdataworldwide.com/assets/images/linkLogo.png">
<p/>
