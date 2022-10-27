<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Content-type: application/json');
header('Cache-Control: no-cache, must-revalidate');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
	exit;
} else
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

$ref = $_SERVER['HTTP_REFERER'];
$refData = parse_url($ref);

if($refData['host'] !== 'formio-api'&&$refData['host'] !== 'efiling.obi.gr') { //TODO allow internal obi network
	    $data['status']="error";
	    $data['error']="not in net";
		$data["Forbidden"] = 1;
		header("HTTP/1.1 403 Forbidden");
		die(json_encode($data));	
}
	
$countries=json_decode('
[
  {
    "iso": "EPO",
    "english": "EPO",
    "french": "EPO",
    "spanish": "EPO",
    "greek": "EPO"
  },
  {
    "iso": "WIPO",
    "english": "WIPO",
    "french": "WIPO",
    "spanish": "WIPO",
    "greek": "WIPO"
  },
  {
    "iso": "ARIPO",
    "english": "ARIPO",
    "french": "ARIPO",
    "spanish": "ARIPO",
    "greek": "ARIPO"
  },
  {
    "iso": "EAPO",
    "english": "EAPO",
    "french": "EAPO",
    "spanish": "EAPO",
    "greek": "EAPO"
  },
  {
    "iso": "OAPI",
    "english": "OAPI",
    "french": "OAPI",
    "spanish": "OAPI",
    "greek": "OAPI"
  },
  {
    "iso": "GR",
    "english": "Greece",
    "french": "Grèce",
    "spanish": "Grecia",
    "greek": "Ελλάδα"
  },
  {
    "iso": "SH",
    "english": "Saint Helena",
    "french": "Sainte-Hélène",
    "spanish": "Santa Elena",
    "greek": "Αγία Ελένη"
  },
  {
    "iso": "LC",
    "english": "Saint Lucia",
    "french": "Sainte-Lucie",
    "spanish": "Santa Lucía",
    "greek": "Αγία Λουκία"
  },
  {
    "iso": "BL",
    "english": "Saint-Barthelemy",
    "french": "Saint-Barthélemy",
    "spanish": "San Bartolomé",
    "greek": "Αγιος Βαρθολομαίος"
  },
  {
    "iso": "VC",
    "english": "Saint Vincent and the Grenadines",
    "french": "Saint-Vincent-et-les-Grenadines",
    "spanish": "San Vincente y Granadinas",
    "greek": "Άγιος Βικέντιος και Γρεναδίνες"
  },
  {
    "iso": "MF",
    "english": "Saint Martin",
    "french": "Saint-Martin",
    "spanish": "San Martín",
    "greek": "Αγιος Μαρτίνος (Γαλλία)"
  },
  {
    "iso": "SX",
    "english": "Sint Maarten",
    "french": "Saint-Martin",
    "spanish": "Sint Maarten",
    "greek": "Αγιος Μαρτίνος (Ολλανδία)"
  },
  {
    "iso": "KN",
    "english": "Saint Kitts and Nevis",
    "french": "Saint-Kitts-et-Nevis",
    "spanish": "San Cristobal y Nevis",
    "greek": "Άγιος Χριστόφορος και Νέβις"
  },
  {
    "iso": "EG",
    "english": "Egypt",
    "french": "Égypte",
    "spanish": "Egipto",
    "greek": "Αίγυπτος"
  },
  {
    "iso": "AZ",
    "english": "Azerbaijan",
    "french": "Azerbaïdjan",
    "spanish": "Azerbaiyán",
    "greek": "Αϊζερμπαϊτζαν"
  },
  {
    "iso": "ET",
    "english": "Ethiopia",
    "french": "Éthiopie",
    "spanish": "Etiopía",
    "greek": "Αιθιοπία"
  },
  {
    "iso": "HT",
    "english": "Haiti",
    "french": "Haïti",
    "spanish": "Haiti",
    "greek": "Αϊτή"
  },
  {
    "iso": "CI",
    "english": "Cote d\'Ivoire",
    "french": "Côte d\'Ivoire",
    "spanish": "Costa de Marfil",
    "greek": "Ακτή Ελεφαντοστού"
  },
  {
    "iso": "AL",
    "english": "Albania",
    "french": "Albanie",
    "spanish": "Albania",
    "greek": "Αλβανία"
  },
  {
    "iso": "DZ",
    "english": "Algeria",
    "french": "Algérie",
    "spanish": "Argelia",
    "greek": "Αλγερία"
  },
  {
    "iso": "VI",
    "english": "U.S. Virgin Islands",
    "french": "Îles Vierges des États-Unis",
    "spanish": "Islas Virgenes Americanas",
    "greek": "Αμερικανικές Παρθένοι Νήσοι"
  },
  {
    "iso": "AS",
    "english": "American Samoa",
    "french": "Samoa Américaines",
    "spanish": "Samoa Americana",
    "greek": "Αμερικανική Σαμόα"
  },
  {
    "iso": "TL",
    "english": "East Timor",
    "french": "Timor oriental",
    "spanish": "Timor Oriental",
    "greek": "Ανατολικό Τιμόρ"
  },
  {
    "iso": "AO",
    "english": "Angola",
    "french": "Angola",
    "spanish": "Angola",
    "greek": "Ανγκόλα"
  },
  {
    "iso": "AI",
    "english": "Anguilla",
    "french": "Anguilla",
    "spanish": "Anguilla",
    "greek": "Ανγκουϊλα"
  },
  {
    "iso": "AD",
    "english": "Andorra",
    "french": "Andorre",
    "spanish": "Andorra",
    "greek": "Ανδόρα"
  },
  {
    "iso": "AQ",
    "english": "Antarctica",
    "french": "Antarctique",
    "spanish": "Antártida",
    "greek": "Ανταρκτική"
  },
  {
    "iso": "AG",
    "english": "Antigua and Barbuda",
    "french": "Antigua et Barbuda",
    "spanish": "Antigua y Barbuda",
    "greek": "Αντίγκουα και Μπαρμπούδα"
  },
  {
    "iso": "UM",
    "english": "Minor Outlying Islands",
    "french": "Îles mineures éloignées",
    "spanish": "Islas Ultramarinas Menores",
    "greek": "Απομακρυσμένες Νησίδες των Ηνωμένων Πολιτειών"
  },
  {
    "iso": "AR",
    "english": "Argentina",
    "french": "Argentine",
    "spanish": "Argentina",
    "greek": "Αργεντινή"
  },
  {
    "iso": "AM",
    "english": "Armenia",
    "french": "Arménie",
    "spanish": "Armenia",
    "greek": "Αρμενία"
  },
  {
    "iso": "AW",
    "english": "Aruba",
    "french": "Aruba",
    "spanish": "Aruba",
    "greek": "Αρουμπα"
  },
  {
    "iso": "AU",
    "english": "Australia",
    "french": "Australie",
    "spanish": "Australia",
    "greek": "Αυστραλία"
  },
  {
    "iso": "AT",
    "english": "Austria",
    "french": "Autriche",
    "spanish": "Austria",
    "greek": "Αυστρία"
  },
  {
    "iso": "AF",
    "english": "Afghanistan",
    "french": "Afghanistan",
    "spanish": "Afganistán",
    "greek": "Αφγανιστάν"
  },
  {
    "iso": "WF",
    "english": "Wallis and Futuna",
    "french": "Wallis et Futuna",
    "spanish": "Wallis y Futuna",
    "greek": "Βαλίς και Φουτούνα"
  },
  {
    "iso": "VU",
    "english": "Vanuatu",
    "french": "Vanuatu",
    "spanish": "Vanuatu",
    "greek": "Βανουάτου"
  },
  {
    "iso": "VA",
    "english": "Vatican City",
    "french": "Vatican",
    "spanish": "Ciudad del Vaticano",
    "greek": "Βατικανό"
  },
  {
    "iso": "BE",
    "english": "Belgium",
    "french": "Belgique",
    "spanish": "Bélgica",
    "greek": "Βέλγιο"
  },
  {
    "iso": "VE",
    "english": "Venezuela",
    "french": "Venezuela",
    "spanish": "Venezuela",
    "greek": "Βενεζουέλα"
  },
  {
    "iso": "BM",
    "english": "Bermuda",
    "french": "Bermudes",
    "spanish": "Bermudas",
    "greek": "Βερμούδες"
  },
  {
    "iso": "VN",
    "english": "Vietnam",
    "french": "Vietnam",
    "spanish": "Vietnam",
    "greek": "Βιετνάμ"
  },
  {
    "iso": "BO",
    "english": "Bolivia",
    "french": "Bolivie",
    "spanish": "Bolivia",
    "greek": "Βολιβία"
  },
  {
    "iso": "KP",
    "english": "North Korea",
    "french": "Corée du Nord",
    "spanish": "Corea del Norte",
    "greek": "Βόρειος Κορέα"
  },
  {
    "iso": "MK",
    "english": "Macedonia",
    "french": "Macédoine",
    "spanish": "Macedonia",
    "greek": "Βόρειος Μακεδονία"
  },
  {
    "iso": "BA",
    "english": "Bosnia and Herzegovina",
    "french": "Bosnie-Herzégovine",
    "spanish": "Bosnia-Herzegovina",
    "greek": "Βοσνία - Ερζεγοβίνη"
  },
  {
    "iso": "BG",
    "english": "Bulgaria",
    "french": "Bulgarie",
    "spanish": "Bulgaria",
    "greek": "Βουλγαρία"
  },
  {
    "iso": "BR",
    "english": "Brazil",
    "french": "Brésil",
    "spanish": "Brasil",
    "greek": "Βραζιλία"
  },
  {
    "iso": "VG",
    "english": "British Virgin Islands",
    "french": "Îles Vierges Britanniques",
    "spanish": "Islas Virgenes Británicas",
    "greek": "Βρετανικές Παρθένοι Νήσοι"
  },
  {
    "iso": "IO",
    "english": "British Indian Ocean Territory",
    "french": "Territoire Britannique de l\'Océan Indien",
    "spanish": "Territorio Británico del Océano Indico",
    "greek": "Βρετανικό Έδαφος Ινδικού Ωκεανού"
  },
  {
    "iso": "FR",
    "english": "France",
    "french": "France",
    "spanish": "Francia",
    "greek": "Γαλλία"
  },
  {
    "iso": "TF",
    "english": "French Southern Territories",
    "french": "Terres Australes Françaises",
    "spanish": "Tierras Australes y Antárticas Francesas",
    "greek": "Γαλλικά Νότια και Ανταρκτικά Εδάφη"
  },
  {
    "iso": "GF",
    "english": "French Guiana",
    "french": "Guyane Française",
    "spanish": "Guayana Francesa",
    "greek": "Γαλλική Γουϊάνα"
  },
  {
    "iso": "PF",
    "english": "French Polynesia",
    "french": "Polynésie Française",
    "spanish": "Polinesia Francesa",
    "greek": "Γαλλικη Πολυνησία"
  },
  {
    "iso": "DE",
    "english": "Germany",
    "french": "Allemagne",
    "spanish": "Alemania",
    "greek": "Γερμανία"
  },
  {
    "iso": "GE",
    "english": "Georgia",
    "french": "Géorgie",
    "spanish": "Georgia",
    "greek": "Γεωργία"
  },
  {
    "iso": "GI",
    "english": "Gibraltar",
    "french": "Gibraltar",
    "spanish": "Gibraltar",
    "greek": "Γιβραλτάρ"
  },
  {
    "iso": "GM",
    "english": "Gambia",
    "french": "Gambie",
    "spanish": "Gambia",
    "greek": "Γκάμπια"
  },
  {
    "iso": "GA",
    "english": "Gabon",
    "french": "Gabon",
    "spanish": "Gabón",
    "greek": "Γκαμπον"
  },
  {
    "iso": "GH",
    "english": "Ghana",
    "french": "Ghana",
    "spanish": "Ghana",
    "greek": "Γκάνα"
  },
  {
    "iso": "GU",
    "english": "Guam",
    "french": "Guam",
    "spanish": "Guam",
    "greek": "Γκουάμ"
  },
  {
    "iso": "GG",
    "english": "Guernsey",
    "french": "Guernesey",
    "spanish": "Guernsey",
    "greek": "ΓκουέρνσεΪ"
  },
  {
    "iso": "GP",
    "english": "Guadeloupe",
    "french": "Guadeloupe",
    "spanish": "Guadalupe",
    "greek": "Γουαδελούπη"
  },
  {
    "iso": "GT",
    "english": "Guatemala",
    "french": "Guatemala",
    "spanish": "Guatemala",
    "greek": "Γουατεμάλα"
  },
  {
    "iso": "GY",
    "english": "Guyana",
    "french": "Guyana",
    "spanish": "Guyana",
    "greek": "Γουϊάνα"
  },
  {
    "iso": "GN",
    "english": "Guinea",
    "french": "Guinéee",
    "spanish": "República Guinea",
    "greek": "Γουϊνέα"
  },
  {
    "iso": "GW",
    "english": "Guinea-Bissau",
    "french": "Guinée-Bissau",
    "spanish": "Guinea Bissau",
    "greek": "Γουϊνέα Μπισάου"
  },
  {
    "iso": "GD",
    "english": "Grenada",
    "french": "Grenade",
    "spanish": "Granada",
    "greek": "Γρενάδα"
  },
  {
    "iso": "GL",
    "english": "Greenland",
    "french": "Groenland",
    "spanish": "Groenlandia",
    "greek": "Γρινλαδία"
  },
  {
    "iso": "DK",
    "english": "Denmark",
    "french": "Danemark",
    "spanish": "Dinamarca",
    "greek": "Δανία"
  },
  {
    "iso": "CG",
    "english": "Republic of Congo",
    "french": "Congo-Brazzaville",
    "spanish": "República del Congo",
    "greek": "Δημοκρατία του Κονγκό"
  },
  {
    "iso": "DM",
    "english": "Dominica",
    "french": "Dominique",
    "spanish": "Dominica",
    "greek": "Δομίνικα"
  },
  {
    "iso": "DO",
    "english": "Dominican Republic",
    "french": "République Dominicaine",
    "spanish": "Dominicana, República",
    "greek": "Δομινικανή Δημοκρατία"
  },
  {
    "iso": "EH",
    "english": "Western Sahara",
    "french": "Sahara Occidental",
    "spanish": "Sáhara Occidental",
    "greek": "Δυτική Σαχάρα"
  },
  {
    "iso": "CH",
    "english": "Switzerland",
    "french": "Suisse",
    "spanish": "Suiza",
    "greek": "Ελβετία"
  },
  {
    "iso": "ER",
    "english": "Eritrea",
    "french": "Érythrée",
    "spanish": "Eritrea",
    "greek": "Ερυθραία"
  },
  {
    "iso": "EE",
    "english": "Estonia",
    "french": "Estonie",
    "spanish": "Estonia",
    "greek": "Εσθονία"
  },
  {
    "iso": "ZM",
    "english": "Zambia",
    "french": "Zambie",
    "spanish": "Zambia",
    "greek": "Ζάμπια"
  },
  {
    "iso": "ZW",
    "english": "Zimbabwe",
    "french": "Zimbabwe",
    "spanish": "Zimbabwe",
    "greek": "Ζιμπάμπουε"
  },
  {
    "iso": "SZ",
    "english": "Swaziland",
    "french": "Swaziland",
    "spanish": "Swazilandia",
    "greek": "Ζουαζιλάνδη"
  },
  {
    "iso": "AE",
    "english": "United Arab Emirates",
    "french": "Émirats Arabes Unis",
    "spanish": "Emiratos Árabes Unidos",
    "greek": "Ηνωμένα Αραβικά Εμιράτα"
  },
  {
    "iso": "US",
    "english": "United States",
    "french": "États-Unis",
    "spanish": "Estados Unidos",
    "greek": "Ηνωμένες Πολιτείες Αμερικής"
  },
  {
    "iso": "GB",
    "english": "United Kingdom",
    "french": "Royaume-Uni",
    "spanish": "Reino Unido",
    "greek": "Ηνωμένο Βασίλειο"
  },
  {
    "iso": "JP",
    "english": "Japan",
    "french": "Japon",
    "spanish": "Japón",
    "greek": "Ιαπωνια"
  },
  {
    "iso": "IN",
    "english": "India",
    "french": "Inde",
    "spanish": "India",
    "greek": "Ινδία"
  },
  {
    "iso": "ID",
    "english": "Indonesia",
    "french": "Indonésie",
    "spanish": "Indonesia",
    "greek": "Ινδονησία"
  },
  {
    "iso": "JO",
    "english": "Jordan",
    "french": "Jordanie",
    "spanish": "Jordania",
    "greek": "Ιορδανία"
  },
  {
    "iso": "IQ",
    "english": "Iraq",
    "french": "Iraq",
    "spanish": "Iraq",
    "greek": "Ιράκ"
  },
  {
    "iso": "IR",
    "english": "Iran",
    "french": "Iran",
    "spanish": "Irán",
    "greek": "Ιράν"
  },
  {
    "iso": "IE",
    "english": "Ireland",
    "french": "Irlande",
    "spanish": "Irlanda",
    "greek": "Ιρλανδία"
  },
  {
    "iso": "GQ",
    "english": "Equatorial Guinea",
    "french": "Guinée Équatoriale",
    "spanish": "Guinea Ecuatorial",
    "greek": "Ισημερινή Γουϊνέα"
  },
  {
    "iso": "EC",
    "english": "Ecuador",
    "french": "Équateur",
    "spanish": "Ecuador",
    "greek": "Ισημερινός"
  },
  {
    "iso": "IS",
    "english": "Iceland",
    "french": "Islande",
    "spanish": "Islandia",
    "greek": "Ισλανδία"
  },
  {
    "iso": "ES",
    "english": "Spain",
    "french": "Espagne",
    "spanish": "España",
    "greek": "Ισπανία"
  },
  {
    "iso": "IL",
    "english": "Israel",
    "french": "Israël",
    "spanish": "Israel",
    "greek": "Ισραήλ"
  },
  {
    "iso": "IT",
    "english": "Italy",
    "french": "Italie",
    "spanish": "Italia",
    "greek": "Ιταλία"
  },
  {
    "iso": "KZ",
    "english": "Kazakhstan",
    "french": "Kazakstan",
    "spanish": "Kazajstán",
    "greek": "Καζακστάν"
  },
  {
    "iso": "CM",
    "english": "Cameroon",
    "french": "Cameroun",
    "spanish": "Camerún",
    "greek": "Καμερούν"
  },
  {
    "iso": "KH",
    "english": "Cambodia",
    "french": "Cambodge",
    "spanish": "Camboya",
    "greek": "Καμπότζη"
  },
  {
    "iso": "CA",
    "english": "Canada",
    "french": "Canada",
    "spanish": "Canadá",
    "greek": "Καναδάς"
  },
  {
    "iso": "QA",
    "english": "Qatar",
    "french": "Qatar",
    "spanish": "Qatar",
    "greek": "Κατάρ"
  },
  {
    "iso": "CF",
    "english": "Central African Republic",
    "french": "Rép. Centrafricaine",
    "spanish": "República Centroafricana",
    "greek": "Κεντροαφρικανική Δημοκρατία"
  },
  {
    "iso": "KE",
    "english": "Kenya",
    "french": "Kenya",
    "spanish": "Kenia",
    "greek": "Κένυα"
  },
  {
    "iso": "CN",
    "english": "China",
    "french": "Chine",
    "spanish": "China",
    "greek": "Κίνα"
  },
  {
    "iso": "KG",
    "english": "Kyrgyzstan",
    "french": "Kirghizistan",
    "spanish": "Kirguistán",
    "greek": "Κιργιστάν"
  },
  {
    "iso": "KI",
    "english": "Kiribati",
    "french": "Kiribati",
    "spanish": "Kiribati",
    "greek": "Κιριμπάτι"
  },
  {
    "iso": "CO",
    "english": "Colombia",
    "french": "Colombie",
    "spanish": "Colombia",
    "greek": "Κολομβία"
  },
  {
    "iso": "KM",
    "english": "Comoros",
    "french": "Comores",
    "spanish": "Comores",
    "greek": "Κομόρες"
  },
  {
    "iso": "CU",
    "english": "Cuba",
    "french": "Cuba",
    "spanish": "Cuba",
    "greek": "Κούβα"
  },
  {
    "iso": "KW",
    "english": "Kuwait",
    "french": "Koweït",
    "spanish": "Kuwait",
    "greek": "Κουβέϊτ"
  },
  {
    "iso": "CW",
    "english": "Curacao",
    "french": "Curaçao",
    "spanish": "Curaçao",
    "greek": "Κουρασάο"
  },
  {
    "iso": "HR",
    "english": "Croatia",
    "french": "Croatie",
    "spanish": "Croacia",
    "greek": "Κροατία"
  },
  {
    "iso": "CY",
    "english": "Cyprus",
    "french": "Chypre",
    "spanish": "Chipre",
    "greek": "Κύπρος"
  },
  {
    "iso": "CR",
    "english": "Costa Rica",
    "french": "Costa Rica",
    "spanish": "Costa Rica",
    "greek": "Κώστα Ρίκα"
  },
  {
    "iso": "CD",
    "english": "Congo D.R.",
    "french": "Congo-Kinshasa",
    "spanish": "República Democrática del Congo",
    "greek": "Λαϊκή Δημοκρατία του Κονγκό"
  },
  {
    "iso": "LA",
    "english": "Laos",
    "french": "Laos",
    "spanish": "Laos",
    "greek": "Λάος"
  },
  {
    "iso": "LR",
    "english": "Liberia",
    "french": "Libéria",
    "spanish": "Liberia",
    "greek": "Λεβερία"
  },
  {
    "iso": "LS",
    "english": "Lesotho",
    "french": "Lesotho",
    "spanish": "Lesotho",
    "greek": "Λεσότο"
  },
  {
    "iso": "LV",
    "english": "Latvia",
    "french": "Lettonie",
    "spanish": "Letonia",
    "greek": "Λεττονία"
  },
  {
    "iso": "BY",
    "english": "Belarus",
    "french": "Bélarus",
    "spanish": "Bielorrusia",
    "greek": "Λευκορωσσία"
  },
  {
    "iso": "LB",
    "english": "Lebanon",
    "french": "Liban",
    "spanish": "Líbano",
    "greek": "Λίβανος"
  },
  {
    "iso": "LT",
    "english": "Lithuania",
    "french": "Lituanie",
    "spanish": "Lituania",
    "greek": "Λιθουανία"
  },
  {
    "iso": "LI",
    "english": "Liechtenstein",
    "french": "Liechtenstein",
    "spanish": "Liechtenstein",
    "greek": "Λιχνενστάϊν"
  },
  {
    "iso": "LU",
    "english": "Luxembourg",
    "french": "Luxembourg",
    "spanish": "Luxemburgo",
    "greek": "Λουξεμβούργο"
  },
  {
    "iso": "LY",
    "english": "Libya",
    "french": "Libye",
    "spanish": "Libia",
    "greek": "Λυβίη"
  },
  {
    "iso": "YT",
    "english": "Mayotte",
    "french": "Mayotte",
    "spanish": "Mayotte",
    "greek": "Μαγιότ"
  },
  {
    "iso": "MG",
    "english": "Madagascar",
    "french": "Madagascar",
    "spanish": "Madagascar",
    "greek": "Μαδαγασκάρη"
  },
  {
    "iso": "MO",
    "english": "Macao",
    "french": "Macao",
    "spanish": "Macao",
    "greek": "Μακάο"
  },
  {
    "iso": "MY",
    "english": "Malaysia",
    "french": "Malaisie",
    "spanish": "Malasia",
    "greek": "Μαλαισία"
  },
  {
    "iso": "MW",
    "english": "Malawi",
    "french": "Malawi",
    "spanish": "Malawi",
    "greek": "Μαλάουϊ"
  },
  {
    "iso": "MV",
    "english": "Maldives",
    "french": "Maldives",
    "spanish": "Maldivas",
    "greek": "Μαλδίβες"
  },
  {
    "iso": "ML",
    "english": "Mali",
    "french": "Mali",
    "spanish": "Malí",
    "greek": "Μαλί"
  },
  {
    "iso": "MT",
    "english": "Malta",
    "french": "Malte",
    "spanish": "Malta",
    "greek": "Μάλτα"
  },
  {
    "iso": "MA",
    "english": "Morocco",
    "french": "Maroc",
    "spanish": "Marruecos",
    "greek": "Μαρόκο"
  },
  {
    "iso": "MQ",
    "english": "Martinique",
    "french": "Martinique",
    "spanish": "Martinica",
    "greek": "Μαρτινίκα"
  },
  {
    "iso": "MR",
    "english": "Mauritania",
    "french": "Mauritanie",
    "spanish": "Mauritania",
    "greek": "Μαυριτανία"
  },
  {
    "iso": "ME",
    "english": "Montenegro",
    "french": "Monténégro",
    "spanish": "Montenegro",
    "greek": "Μαυροβούνιο"
  },
  {
    "iso": "MX",
    "english": "Mexico",
    "french": "Mexique",
    "spanish": "México",
    "greek": "Μεξικό"
  },
  {
    "iso": "MM",
    "english": "Myanmar",
    "french": "Birmanie",
    "spanish": "Myanmar, Birmania",
    "greek": "Μιαμάρ"
  },
  {
    "iso": "MN",
    "english": "Mongolia",
    "french": "Mongolie",
    "spanish": "Mongolia",
    "greek": "Μογγολία"
  },
  {
    "iso": "MZ",
    "english": "Mozambique",
    "french": "Mozambique",
    "spanish": "Mozambique",
    "greek": "Μοζαμβίκη"
  },
  {
    "iso": "MD",
    "english": "Moldova",
    "french": "Moldavie",
    "spanish": "Moldavia",
    "greek": "Μολδαβία"
  },
  {
    "iso": "MC",
    "english": "Monaco",
    "french": "Monaco",
    "spanish": "Mónaco",
    "greek": "Μονακό"
  },
  {
    "iso": "MS",
    "english": "Montserrat",
    "french": "Montserrat",
    "spanish": "Montserrat",
    "greek": "Μοντσεράτ"
  },
  {
    "iso": "BD",
    "english": "Bangladesh",
    "french": "Bangladesh",
    "spanish": "Bangladesh",
    "greek": "Μπανγκλαντές"
  },
  {
    "iso": "BB",
    "english": "Barbados",
    "french": "Barbade",
    "spanish": "Barbados",
    "greek": "Μπαρμπάδος"
  },
  {
    "iso": "BS",
    "english": "Bahamas",
    "french": "Bahamas",
    "spanish": "Bahamas",
    "greek": "Μπαχάμες"
  },
  {
    "iso": "BH",
    "english": "Bahrain",
    "french": "Bahreïn",
    "spanish": "Bahrein",
    "greek": "Μπαχρέϊν"
  },
  {
    "iso": "BZ",
    "english": "Belize",
    "french": "Belize",
    "spanish": "Belice",
    "greek": "Μπελίζ"
  },
  {
    "iso": "BJ",
    "english": "Benin",
    "french": "Bénin",
    "spanish": "Benín",
    "greek": "Μπενίν"
  },
  {
    "iso": "BW",
    "english": "Botswana",
    "french": "Botswana",
    "spanish": "Botswana",
    "greek": "Μποτσουάνα"
  },
  {
    "iso": "BV",
    "english": "Bouvet Island",
    "french": "Île Bouvet",
    "spanish": "Isla Bouvet",
    "greek": "Μπουβέ"
  },
  {
    "iso": "BF",
    "english": "Burkina Faso",
    "french": "Burkina Faso",
    "spanish": "Burkina Faso",
    "greek": "Μπουρκίνα Φάσο"
  },
  {
    "iso": "BI",
    "english": "Burundi",
    "french": "Burundi",
    "spanish": "Burundi",
    "greek": "Μπουρούντι"
  },
  {
    "iso": "BT",
    "english": "Bhutan",
    "french": "Bhoutan",
    "spanish": "Bután",
    "greek": "Μπουτάν"
  },
  {
    "iso": "BN",
    "english": "Brunei",
    "french": "Brunei",
    "spanish": "Brunei",
    "greek": "Μπρουνέϊ"
  },
  {
    "iso": "NA",
    "english": "Namibia",
    "french": "Namibie",
    "spanish": "Namibia",
    "greek": "Ναμπίμβια"
  },
  {
    "iso": "NR",
    "english": "Nauru",
    "french": "Nauru",
    "spanish": "Nauru",
    "greek": "Ναούρου"
  },
  {
    "iso": "NZ",
    "english": "New Zealand",
    "french": "Nouvelle-Zélande",
    "spanish": "Nueva Zelanda",
    "greek": "Νέα Ζηλανδία"
  },
  {
    "iso": "NC",
    "english": "New Caledonia",
    "french": "Nouvelle-Calédonie",
    "spanish": "Nueva Caledonia",
    "greek": "Νέα Καληδονία"
  },
  {
    "iso": "NP",
    "english": "Nepal",
    "french": "Népal",
    "spanish": "Nepal",
    "greek": "Νεπάλ"
  },
  {
    "iso": "NF",
    "english": "Norfolk Island",
    "french": "Île Norfolk",
    "spanish": "Norfolk Island",
    "greek": "Νησί Νόρφολκ"
  },
  {
    "iso": "BQ",
    "english": "Caribbean Netherlands",
    "french": "Pays-Bas caribéens",
    "spanish": "Caribe Neerlandés",
    "greek": "Νήσοι BES"
  },
  {
    "iso": "MP",
    "english": "Northern Mariana Islands",
    "french": "Îles Mariannes du Nord",
    "spanish": "Marianas del Norte",
    "greek": "Νήσοι Βόρειες Μαριάνες"
  },
  {
    "iso": "KY",
    "english": "Cayman Islands",
    "french": "Îles Caïmans",
    "spanish": "Islas Caimán",
    "greek": "Νήσοι Καϊμάν"
  },
  {
    "iso": "CC",
    "english": "Cocos Islands",
    "french": "Îles Cocos",
    "spanish": "Islas Cocos",
    "greek": "Νήσοι Κόκος"
  },
  {
    "iso": "CK",
    "english": "Cook Islands",
    "french": "Îles Cook",
    "spanish": "Islas Cook",
    "greek": "Νήσοι Κουκ"
  },
  {
    "iso": "IM",
    "english": "Isle of Man",
    "french": "Île de Man",
    "spanish": "Isla Man",
    "greek": "Νήσοι Μαν"
  },
  {
    "iso": "MH",
    "english": "Marshall Islands",
    "french": "Îles Marshall",
    "spanish": "Islas Marshall",
    "greek": "Νήσοι Μάρσαλ"
  },
  {
    "iso": "MU",
    "english": "Mauritius",
    "french": "Île Maurice",
    "spanish": "Mauricio",
    "greek": "Νήσοι Μαυρίκιος"
  },
  {
    "iso": "GS",
    "english": "South Georgia and Sandwich Islands",
    "french": "Géorgie du Sud et îles Sandwich",
    "spanish": "Sudo Georgia y los Islas Sandwich del Sur",
    "greek": "Νήσοι Νότια Γεωργία και Νότιες Σάντουιτς"
  },
  {
    "iso": "PN",
    "english": "Pitcairn Islands",
    "french": "Pitcairn",
    "spanish": "Isla Pitcairn",
    "greek": "Νήσοι Πίτκαιρν"
  },
  {
    "iso": "SB",
    "english": "Solomon Islands",
    "french": "Îles Salomon",
    "spanish": "Islas Salomón",
    "greek": "Νήσοι Σολομόν"
  },
  {
    "iso": "TC",
    "english": "Turks and Caicos Islands",
    "french": "Îles Turques-et-Caïques",
    "spanish": "Islas Turcas y Caicos",
    "greek": "Νήσοι Τερκς και Κέικος"
  },
  {
    "iso": "FO",
    "english": "Faroe Islands",
    "french": "Îles Féroé",
    "spanish": "Islas Feroe",
    "greek": "Νήσοι Φερόες"
  },
  {
    "iso": "FK",
    "english": "Falkland Islands",
    "french": "Îles Falkland",
    "spanish": "Islas Malvinas",
    "greek": "Νήσοι Φωλκλαντ"
  },
  {
    "iso": "HM",
    "english": "Heard and McDonald Islands",
    "french": "Îles Heard et McDonald",
    "spanish": "Islas de Heard y McDonald",
    "greek": "Νήσοι Χερντ και Μακντόναλντ"
  },
  {
    "iso": "CX",
    "english": "Christmas Island",
    "french": "Île Christmas",
    "spanish": "Isla De Navidad, Isla Christmas",
    "greek": "Νήσος των Χριστουγέννων"
  },
  {
    "iso": "NE",
    "english": "Niger",
    "french": "Niger",
    "spanish": "Niger",
    "greek": "Νίγηρας"
  },
  {
    "iso": "NG",
    "english": "Nigeria",
    "french": "Nigéria",
    "spanish": "Nigeria",
    "greek": "Νιγηρία"
  },
  {
    "iso": "NI",
    "english": "Nicaragua",
    "french": "Nicaragua",
    "spanish": "Nicaragua",
    "greek": "Νικαράγουα"
  },
  {
    "iso": "NU",
    "english": "Niue",
    "french": "Nioué",
    "spanish": "Niue",
    "greek": "Νιούε"
  },
  {
    "iso": "NO",
    "english": "Norway",
    "french": "Norvège",
    "spanish": "Noruega",
    "greek": "Νορβηγία"
  },
  {
    "iso": "SS",
    "english": "South Sudan",
    "french": "Sud Soudan",
    "spanish": "Sudán del Sur",
    "greek": "Νότιο Σουδάν"
  },
  {
    "iso": "ZA",
    "english": "South Africa",
    "french": "Afrique du Sud",
    "spanish": "Sudáfrica",
    "greek": "Νότιος Αφρική"
  },
  {
    "iso": "KR",
    "english": "South Korea",
    "french": "Corée du Sud",
    "spanish": "Corea del Sur",
    "greek": "Νότιος Κορέα"
  },
  {
    "iso": "NL",
    "english": "Netherlands",
    "french": "Pays-Bas",
    "spanish": "Países Bajos, Holanda",
    "greek": "Ολλανδία"
  },
  {
    "iso": "OM",
    "english": "Oman",
    "french": "Oman",
    "spanish": "Omán",
    "greek": "Ομάν"
  },
  {
    "iso": "FM",
    "english": "Micronesia",
    "french": "Micronésie",
    "spanish": "Micronesia, Estados Federados de",
    "greek": "Ομόσπονδες Πολιτείες της Μικρονησίας"
  },
  {
    "iso": "HN",
    "english": "Honduras",
    "french": "Honduras",
    "spanish": "Honduras",
    "greek": "Ονδούρα"
  },
  {
    "iso": "HU",
    "english": "Hungary",
    "french": "Hongrie",
    "spanish": "Hungría",
    "greek": "Ουγγαρία"
  },
  {
    "iso": "UG",
    "english": "Uganda",
    "french": "Ouganda",
    "spanish": "Uganda",
    "greek": "Ουγκάντα"
  },
  {
    "iso": "UZ",
    "english": "Uzbekistan",
    "french": "Ouzbékistan",
    "spanish": "Uzbekistán",
    "greek": "Ουζμπεκιστάν"
  },
  {
    "iso": "UA",
    "english": "Ukraine",
    "french": "Ukraine",
    "spanish": "Ucrania",
    "greek": "Ουκρανία"
  },
  {
    "iso": "UY",
    "english": "Uruguay",
    "french": "Uruguay",
    "spanish": "Uruguay",
    "greek": "Ουρουγουάη"
  },
  {
    "iso": "PK",
    "english": "Pakistan",
    "french": "Pakistan",
    "spanish": "Pakistán",
    "greek": "Πακιστάν"
  },
  {
    "iso": "PS",
    "english": "Palestine",
    "french": "Palestine",
    "spanish": "Palestina",
    "greek": "Παλαιστίνη"
  },
  {
    "iso": "PW",
    "english": "Palau",
    "french": "Palaos",
    "spanish": "Palau",
    "greek": "Παλάου"
  },
  {
    "iso": "PA",
    "english": "Panama",
    "french": "Panama",
    "spanish": "Panamá",
    "greek": "Παναμάς"
  },
  {
    "iso": "PG",
    "english": "Papua New Guinea",
    "french": "Papouasie-Nv.-Guinée",
    "spanish": "Papúa-Nueva Guinea",
    "greek": "Παπούα - Νέα Γουϊνέα"
  },
  {
    "iso": "PY",
    "english": "Paraguay",
    "french": "Paraguay",
    "spanish": "Paraguay",
    "greek": "Παραγουάη"
  },
  {
    "iso": "PE",
    "english": "Peru",
    "french": "Pérou",
    "spanish": "Perú",
    "greek": "Περού"
  },
  {
    "iso": "PL",
    "english": "Poland",
    "french": "Pologne",
    "spanish": "Polonia",
    "greek": "Πολωνία"
  },
  {
    "iso": "PT",
    "english": "Portugal",
    "french": "Portugal",
    "spanish": "Portugal",
    "greek": "Πορτογαλία"
  },
  {
    "iso": "PR",
    "english": "Puerto Rico",
    "french": "Porto Rico",
    "spanish": "Puerto Rico",
    "greek": "Πουέρτο Ρίκο"
  },
  {
    "iso": "CV",
    "english": "Cape Verde",
    "french": "Cap-Vert",
    "spanish": "Cabo Verde",
    "greek": "Πράσινο Ακρωγτήριο"
  },
  {
    "iso": "RE",
    "english": "Reunion",
    "french": "La Réunion",
    "spanish": "Reunión",
    "greek": "Ρεϋνιόν"
  },
  {
    "iso": "RW",
    "english": "Rwanda",
    "french": "Rwanda",
    "spanish": "Ruanda",
    "greek": "Ρουάντα"
  },
  {
    "iso": "RO",
    "english": "Romania",
    "french": "Roumanie",
    "spanish": "Rumanía",
    "greek": "Ρουμανία"
  },
  {
    "iso": "RU",
    "english": "Russia",
    "french": "Russie",
    "spanish": "Federación Rusa",
    "greek": "Ρωσία"
  },
  {
    "iso": "PM",
    "english": "Saint Pierre and Miquelon",
    "french": "Saint-Pierre-et-Miquelon",
    "spanish": "San Pedro y Miquelón",
    "greek": "Σαιν-Πιερ και Μικελόν"
  },
  {
    "iso": "SV",
    "english": "El Salvador",
    "french": "El Salvador",
    "spanish": "El Salvador",
    "greek": "Σαλβαδόρ"
  },
  {
    "iso": "WS",
    "english": "Samoa",
    "french": "Samoa",
    "spanish": "Samoa",
    "greek": "Σαμόα"
  },
  {
    "iso": "SM",
    "english": "San Marino",
    "french": "Saint-Marin",
    "spanish": "San Marino",
    "greek": "Σαν Μαρίνο"
  },
  {
    "iso": "ST",
    "english": "Sao Tome and Principe",
    "french": "São Tomé & Príncipe",
    "spanish": "San Tomé y Príncipe",
    "greek": "Σάο Τομέ και Πρίνσιπε"
  },
  {
    "iso": "SA",
    "english": "Saudi Arabia",
    "french": "Arabie Saoudite",
    "spanish": "Arabia Saudita",
    "greek": "Σαουδική Αραβία"
  },
  {
    "iso": "SJ",
    "english": "Svalbard and Jan Mayen",
    "french": "Svalbard et Jan Mayen",
    "spanish": "Isla Jan Mayen y Archipiélago de Svalbard",
    "greek": "Σβάλμπαρντ και Γιαν Μάγεν"
  },
  {
    "iso": "SN",
    "english": "Senegal",
    "french": "Sénégal",
    "spanish": "Senegal",
    "greek": "Σενεγάλη"
  },
  {
    "iso": "RS",
    "english": "Serbia",
    "french": "Serbie",
    "spanish": "Serbia",
    "greek": "Σερβία"
  },
  {
    "iso": "SC",
    "english": "Seychelles",
    "french": "Seychelles",
    "spanish": "Seychelles",
    "greek": "Σεϋχέλλες"
  },
  {
    "iso": "SL",
    "english": "Sierra Leone",
    "french": "Sierra Leone",
    "spanish": "Sierra Leona",
    "greek": "Σιέρρα Λεόνε"
  },
  {
    "iso": "SG",
    "english": "Singapore",
    "french": "Singapour",
    "spanish": "Singapur",
    "greek": "Σιγκαπούρη"
  },
  {
    "iso": "SK",
    "english": "Slovakia",
    "french": "Slovaquie",
    "spanish": "Eslovaquia",
    "greek": "Σλοβακία"
  },
  {
    "iso": "SI",
    "english": "Slovenia",
    "french": "Slovénie",
    "spanish": "Eslovenia",
    "greek": "Σλοβενία"
  },
  {
    "iso": "SO",
    "english": "Somalia",
    "french": "Somalie",
    "spanish": "Somalia",
    "greek": "Σομαλία"
  },
  {
    "iso": "SD",
    "english": "Sudan",
    "french": "Soudan",
    "spanish": "Sudán",
    "greek": "Σουδάν"
  },
  {
    "iso": "SE",
    "english": "Sweden",
    "french": "Suède",
    "spanish": "Suecia",
    "greek": "Σουηδία"
  },
  {
    "iso": "SR",
    "english": "Suriname",
    "french": "Suriname",
    "spanish": "Surinam",
    "greek": "Σουρινάμ"
  },
  {
    "iso": "LK",
    "english": "Sri Lanka",
    "french": "Sri Lanka",
    "spanish": "Sri Lanka",
    "greek": "Σρι Λάνκα"
  },
  {
    "iso": "SY",
    "english": "Syria",
    "french": "Syrie",
    "spanish": "Siria",
    "greek": "Συρία"
  },
  {
    "iso": "TW",
    "english": "Taiwan",
    "french": "Taïwan",
    "spanish": "Taiwan",
    "greek": "Ταϊβάν"
  },
  {
    "iso": "TZ",
    "english": "Tanzania",
    "french": "Tanzanie",
    "spanish": "Tanzania",
    "greek": "Τανζανία"
  },
  {
    "iso": "TJ",
    "english": "Tajikistan",
    "french": "Tadjikistan",
    "spanish": "Tadjikistan",
    "greek": "Τατζικιστάν"
  },
  {
    "iso": "TH",
    "english": "Thailand",
    "french": "Thaïlande",
    "spanish": "Tailandia",
    "greek": "Ταϋλάνδη"
  },
  {
    "iso": "JM",
    "english": "Jamaica",
    "french": "Jamaïque",
    "spanish": "Jamaica",
    "greek": "Τζαμάϊκα"
  },
  {
    "iso": "DJ",
    "english": "Djibouti",
    "french": "Djibouti",
    "spanish": "Djibouti, Yibuti",
    "greek": "Τζιμπουτί"
  },
  {
    "iso": "TG",
    "english": "Togo",
    "french": "Togo",
    "spanish": "Togo",
    "greek": "Τόγκο"
  },
  {
    "iso": "TK",
    "english": "Tokelau",
    "french": "Tokelau",
    "spanish": "Tokelau",
    "greek": "Τοκελάου"
  },
  {
    "iso": "TO",
    "english": "Tonga",
    "french": "Tonga",
    "spanish": "Tonga",
    "greek": "Τόνγκα"
  },
  {
    "iso": "TV",
    "english": "Tuvalu",
    "french": "Tuvalu",
    "spanish": "Tuvalu",
    "greek": "Τουβαλού"
  },
  {
    "iso": "TR",
    "english": "Turkey",
    "french": "Turquie",
    "spanish": "Turquía",
    "greek": "Τουρκία"
  },
  {
    "iso": "TM",
    "english": "Turkmenistan",
    "french": "Turkménistan",
    "spanish": "Turkmenistan",
    "greek": "Τουρκμενιστάν"
  },
  {
    "iso": "TT",
    "english": "Trinidad and Tobago",
    "french": "Trinité-et-Tobago",
    "spanish": "Trinidad y Tobago",
    "greek": "Τρινιδάδ και Τομπάκο"
  },
  {
    "iso": "TD",
    "english": "Chad",
    "french": "Tchad",
    "spanish": "Chad",
    "greek": "Τσαντ"
  },
  {
    "iso": "CZ",
    "english": "Czech Republic",
    "french": "République Tchèque",
    "spanish": "República Checa",
    "greek": "Τσεχική Δημοκρατία"
  },
  {
    "iso": "TN",
    "english": "Tunisia",
    "french": "Tunisie",
    "spanish": "Túnez",
    "greek": "Τυνησία"
  },
  {
    "iso": "YE",
    "english": "Yemen",
    "french": "Yémen",
    "spanish": "Yemen",
    "greek": "Υεμένη"
  },
  {
    "iso": "JE",
    "english": "Jersey",
    "french": "Jersey",
    "spanish": "Jersey",
    "greek": "Υερσέη"
  },
  {
    "iso": "PH",
    "english": "Philippines",
    "french": "Philippines",
    "spanish": "Filipinas",
    "greek": "Φιλιππίνες"
  },
  {
    "iso": "FI",
    "english": "Finland",
    "french": "Finlande",
    "spanish": "Finlandia",
    "greek": "Φινλανδία"
  },
  {
    "iso": "FJ",
    "english": "Fiji",
    "french": "Fidji",
    "spanish": "Fiyi",
    "greek": "Φίτζι"
  },
  {
    "iso": "CL",
    "english": "Chile",
    "french": "Chili",
    "spanish": "Chile",
    "greek": "Χιλή"
  },
  {
    "iso": "HK",
    "english": "Hong Kong",
    "french": "Hong-Kong",
    "spanish": "Hong Kong",
    "greek": "Χόνγκ Κονγκ"
  },
  {
    "iso": "AX",
    "english": "Aland",
    "french": "Åland",
    "spanish": "Åland",
    "greek": "Ωλαντ"
  }
]');

function ucg($string, $enc = "utf-8") {
    return strtr(mb_strtoupper($string, $enc),
      array(
      'A' => 'A', 'A' => 'A', 'A' => 'A', 'A' => 'A', 'Ά' => 'Α', 
      'Έ' => 'Ε', 
      'Ί' => 'Ι','ΐ' => 'Ϊ', 
      'Ή' => 'Η', 
      'Ύ' => 'Υ','Y' => 'Y',
      'Ό' => 'Ο', 
      'Ώ' => 'Ω'
      ));
}

$limit = ($_REQUEST['limit']!=""?$_REQUEST['limit']:20);
$skip = ($_REQUEST['skip']!=""?$_REQUEST['skip']:0);
$country = ($_REQUEST['country']!=""?$_REQUEST['country']:"");

if ($country!=""){
	foreach($countries as $key => $co){
    if(strpos(ucg($co->greek) ,ucg($country))!== false){
         $selectedCountries[] = $co;
    }
}

if(isset($selectedCountries)){
print json_encode(array_slice ($selectedCountries,$skip,$limit));
}
}
else print json_encode(array_slice ($countries,$skip,$limit));
}
