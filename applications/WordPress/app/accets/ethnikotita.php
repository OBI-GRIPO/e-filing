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

$eths=json_decode('
[
{"etnikotita":"Ανδόρα"},
{"etnikotita":"Ηνωμένα Αραβικά Ερμιράτα"},
{"etnikotita":"Αφγανός"},
{"etnikotita":"Αντίγκουα και Μπαρμπούδα"},
{"etnikotita":"Ανγκουϊλα"},
{"etnikotita":"Αλβανός"},
{"etnikotita":"Αρμένιος"},
{"etnikotita":"Ανγκόλα"},
{"etnikotita":"Ανταρκτική"},
{"etnikotita":"Αργεντίνος"},
{"etnikotita":"Αμερικανική Σαμόα"},
{"etnikotita":"Αυστριακός"},
{"etnikotita":"Αυστραλός"},
{"etnikotita":"Αρούμπα"},
{"etnikotita":"Ώλαντ"},
{"etnikotita":"Αζερμπαιτζάν"},
{"etnikotita":"Βοσνία - Ερζεγοβίνη"},
{"etnikotita":"Μπαρμπάδος"},
{"etnikotita":"Μπαγκλαντές"},
{"etnikotita":"Βέλγος"},
{"etnikotita":"Μπουρκίνα Φάσο"},
{"etnikotita":"Βούλγαρος"},
{"etnikotita":"Μπαχρεϊν"},
{"etnikotita":"Μπουρουντί"},
{"etnikotita":"Μπενίν"},
{"etnikotita":"Αγιος Βαρθολομαίος"},
{"etnikotita":"Βερμούδες"},
{"etnikotita":"ΜπρούνεΪ"},
{"etnikotita":"Βολιβιανός"},
{"etnikotita":"Νήσοι BES"},
{"etnikotita":"Βραζιλιάνος"},
{"etnikotita":"Μπαχάμες"},
{"etnikotita":"Μπουτάν"},
{"etnikotita":"Μπουβέ"},
{"etnikotita":"Μποτσουάνα"},
{"etnikotita":"Λευκορώσσος"},
{"etnikotita":"Μπελίζ"},
{"etnikotita":"Καναδός"},
{"etnikotita":"Νήσοι Κόκος"},
{"etnikotita":"Λαϊκή Δημοκρατία του Κονγκο"},
{"etnikotita":"Κεντροαφρικανική Δημοκρατία"},
{"etnikotita":"Δημοκρατία του Κονγκό"},
{"etnikotita":"Ελβετός"},
{"etnikotita":"Ιβοριανός"},
{"etnikotita":"Νήσοι Κουκ"},
{"etnikotita":"Χιλιανός"},
{"etnikotita":"Καμαρούν"},
{"etnikotita":"Κινέζος"},
{"etnikotita":"Κολομβιανός"},
{"etnikotita":"Κοστα Ρίκα"},
{"etnikotita":"Κουβανός"},
{"etnikotita":"Πράσινο Ακρωτήριο"},
{"etnikotita":"Κουρασάο"},
{"etnikotita":"Νήσος των Χριστουγέννων"},
{"etnikotita":"Κύπριος"},
{"etnikotita":"Τσέχος"},
{"etnikotita":"Γερμανός"},
{"etnikotita":"Τζιμπουτί"},
{"etnikotita":"Δανός"},
{"etnikotita":"Δομινίκα"},
{"etnikotita":"Δομινικανή Δημοκρατία"},
{"etnikotita":"Αλγερινός"},
{"etnikotita":"Ισημερινός"},
{"etnikotita":"Εσθονός"},
{"etnikotita":"Αιγύπτιος"},
{"etnikotita":"Δυτική Σαχάρα"},
{"etnikotita":"Ερυθραία"},
{"etnikotita":"Ισπανός"},
{"etnikotita":"Αιθίοπας"},
{"etnikotita":"Φινλανδός"},
{"etnikotita":"Φίτζι"},
{"etnikotita":"Νήσοι Φώκλαντ"},
{"etnikotita":"Ομόσπονδες Πολιτείες της Μικρονησίας"},
{"etnikotita":"Νήσοι Φερόες"},
{"etnikotita":"Γάλλος"},
{"etnikotita":"Γκαμπόν"},
{"etnikotita":"Ηνωμένο Βασίλειο"},
{"etnikotita":"Γρενάδα"},
{"etnikotita":"Γεωργιανός"},
{"etnikotita":"Γαλλική Γουϊάνα"},
{"etnikotita":"ΓκουέρνσεΪ"},
{"etnikotita":"Γκάνέζος"},
{"etnikotita":"Γιβραλτάρ"},
{"etnikotita":"Γροιλανδία"},
{"etnikotita":"Γκάμπια"},
{"etnikotita":"Γουϊνέα "},
{"etnikotita":"Γουαδελούπη"},
{"etnikotita":"Ισημερινή Γουϊνέα"},
{"etnikotita":"Έλληνας"},
{"etnikotita":"Νήσοι Νότια Γεωργία και Νότιες Σάντουιτς"},
{"etnikotita":"Γουατεμάλα"},
{"etnikotita":"Γκουάμ"},
{"etnikotita":"Γουϊνέα Μπισάου"},
{"etnikotita":"Γουϊάνα"},
{"etnikotita":"Χονγκ Κονγκ"},
{"etnikotita":"Νήσοι Χερντ και Μακντόναλτ"},
{"etnikotita":"Ονδούρα"},
{"etnikotita":"Κροάτης"},
{"etnikotita":"Αϊτή"},
{"etnikotita":"Ούγγρος"},
{"etnikotita":"Ινδονησία"},
{"etnikotita":"Ιρλανδός"},
{"etnikotita":"Ισραηλινός"},
{"etnikotita":"Νήσοι Μαν"},
{"etnikotita":"Ινδός"},
{"etnikotita":"Βρετανικό Έδαφος Ινδικού Ωκεανόυ"},
{"etnikotita":"Ιρακινός"},
{"etnikotita":"Ιρανός"},
{"etnikotita":"Ισλανδός"},
{"etnikotita":"Ιταλός"},
{"etnikotita":"Υερσέη"},
{"etnikotita":"Τζαμαϊκανός"},
{"etnikotita":"Ιορδανός"},
{"etnikotita":"Ιάπωνας"},
{"etnikotita":"Κενυάτης"},
{"etnikotita":"Κιργιστάν"},
{"etnikotita":"Καμποτζιανός"},
{"etnikotita":"Κιριμπάτι"},
{"etnikotita":"Κομόρες"},
{"etnikotita":"Άγιος Χριστόφορος και Νέβις"},
{"etnikotita":"Βορειοκορεάτης"},
{"etnikotita":"Νοτιοκορεάτης"},
{"etnikotita":"Κουβέϊτ"},
{"etnikotita":"Νήσοι Καϊμάν"},
{"etnikotita":"Καζάκος"},
{"etnikotita":"Λάος"},
{"etnikotita":"Λιβανέζος"},
{"etnikotita":"Αγία Λουκία"},
{"etnikotita":"Λιχτενστάϊν"},
{"etnikotita":"Σριλανκέζος"},
{"etnikotita":"Λιβεριανός"},
{"etnikotita":"Λεσότο"},
{"etnikotita":"Λιθουανός"},
{"etnikotita":"Λουξεμβουργιανός"},
{"etnikotita":"Λεττονός"},
{"etnikotita":"Λίβυος"},
{"etnikotita":"Μαροκινός"},
{"etnikotita":"Μονακό"},
{"etnikotita":"Μολδαβός"},
{"etnikotita":"Μαυροβούνιος"},
{"etnikotita":"Άγιος Μαρτίνος"},
{"etnikotita":"Μαδαγασκάρη"},
{"etnikotita":"Νήσοι Μάρσαλ"},
{"etnikotita":"Βόρειος Μακεδονία"},
{"etnikotita":"Μαλινέζος"},
{"etnikotita":"Μιαμάρ"},
{"etnikotita":"Μογγόλος"},
{"etnikotita":"Μακάο"},
{"etnikotita":"Νήσοι Βόρειες Μαριάνες"},
{"etnikotita":"Μαρτινίκα"},
{"etnikotita":"Μαυριτανός"},
{"etnikotita":"Μοντσεράτ"},
{"etnikotita":"Μαλτέζος"},
{"etnikotita":"Νήσοι Μαυρίκιος"},
{"etnikotita":"Μαλδίβες"},
{"etnikotita":"Μαλάουϊ"},
{"etnikotita":"Μεξικανός"},
{"etnikotita":"Μαλαισιανός"},
{"etnikotita":"Μοζαμβικανός"},
{"etnikotita":"Ναμπίμβια"},
{"etnikotita":"Νέα Καληδονία"},
{"etnikotita":"Νίγηρας"},
{"etnikotita":"Νησί Νόρφολκ"},
{"etnikotita":"Νιγηριανός"},
{"etnikotita":"Νικαράγουα"},
{"etnikotita":"Ολλανδός"},
{"etnikotita":"Νορβηγός"},
{"etnikotita":"Νεπάλέζος"},
{"etnikotita":"Ναουρού"},
{"etnikotita":"Νιούε"},
{"etnikotita":"Νέο"},
{"etnikotita":"Ομαν"},
{"etnikotita":"Παναμέζος"},
{"etnikotita":"Περουβιανος"},
{"etnikotita":"Γαλλική Πολυνησία"},
{"etnikotita":"Παπούα -Νέα Γουϊνέα"},
{"etnikotita":"Φιλλιπινέζος"},
{"etnikotita":"Πακιστανός"},
{"etnikotita":"Πολωνός"},
{"etnikotita":"Σαιν- Πιέρ και Μικελόν"},
{"etnikotita":"Νήσοι Πίτκαιρν"},
{"etnikotita":"Πορτορικανός"},
{"etnikotita":"Παλαιστίνιος"},
{"etnikotita":"Πορτογάλος"},
{"etnikotita":"Παλάου"},
{"etnikotita":"Παραγουανός"},
{"etnikotita":"Κατάρ"},
{"etnikotita":"Ρεϋνιόν"},
{"etnikotita":"Ρουμάνος"},
{"etnikotita":"Σέρβος"},
{"etnikotita":"Ρώσσος"},
{"etnikotita":"Ρουάντα"},
{"etnikotita":"Σαουδική Αραβία"},
{"etnikotita":"Νήσοι Σολομόν"},
{"etnikotita":"Σεϋχέλλες"},
{"etnikotita":"Σουδανός"},
{"etnikotita":"Σουηδός"},
{"etnikotita":"Σιγκαπούρη"},
{"etnikotita":"Αγία Ελένη"},
{"etnikotita":"Σλοβένος"},
{"etnikotita":"Σβάλμπαρντ και Γιαν Μάγεν"},
{"etnikotita":"Σλοβάκος"},
{"etnikotita":"Σιλερρα Λεόνε"},
{"etnikotita":"Σαν Μαρίνο"},
{"etnikotita":"Σενεγαλέζος"},
{"etnikotita":"Σομαλός"},
{"etnikotita":"Σουρινάμ"},
{"etnikotita":"Νοτιοσουδανός"},
{"etnikotita":"Σάο Τομέ λαο Πρινσίπε"},
{"etnikotita":"Σαλβαδόρ"},
{"etnikotita":"Άγιος Μαρτίνος"},
{"etnikotita":"Σύριος"},
{"etnikotita":"Ζουαζιλάνδη"},
{"etnikotita":"Νήσοι Τερκς και Κέικος"},
{"etnikotita":"Τσαντ"},
{"etnikotita":"Γαλλικά Νότια και Ανταρκτικά Εδάφη"},
{"etnikotita":"Τόγκο"},
{"etnikotita":"Ταϋλανδός"},
{"etnikotita":"Τατζικιστάν"},
{"etnikotita":"Τοκελάου"},
{"etnikotita":"Ανατολικό Τιμόρ"},
{"etnikotita":"Τουρκμεκισταν"},
{"etnikotita":"Τυνήσιος"},
{"etnikotita":"Τόνγκα"},
{"etnikotita":"Τούρκος"},
{"etnikotita":"Τρινιδάδ και Τομπάκο"},
{"etnikotita":"Τουβαλού"},
{"etnikotita":"ΤαιΪβανέζος"},
{"etnikotita":"Τανζανός"},
{"etnikotita":"Ουκρανός"},
{"etnikotita":"Ουγκάντα"},
{"etnikotita":"Απομακρυσμένες Νησίδες των Ηνωμένω Πολιτειών"},
{"etnikotita":"Αμερικανός"},
{"etnikotita":"Ουρουγουανός"},
{"etnikotita":"Ουζμπέκος"},
{"etnikotita":"Βατικανό"},
{"etnikotita":"Άγιος Βικέντιος και Γρεναδίνες"},
{"etnikotita":"Βενεζουέλα"},
{"etnikotita":"Βρετανικες Παρθένες Νήσοι"},
{"etnikotita":"Αμερικανικες Παρθένες Νήσοι"},
{"etnikotita":"Βιετναμέζος"},
{"etnikotita":"Βανουάτου"},
{"etnikotita":"Βαλίς και Φουτούνα"},
{"etnikotita":"Σαμόα"},
{"etnikotita":"Υεμένη"},
{"etnikotita":"Μαγιότ"},
{"etnikotita":"Νοτιοαφρικανός"},
{"etnikotita":"Ζάμπια"},
{"etnikotita":"Ζιμπάμπουε"}
]');


function rs($string){
return str_replace(' ', '', $string.'');
}

$limit = ($_REQUEST['limit']!=""?$_REQUEST['limit']:20);
$skip = ($_REQUEST['skip']!=""?$_REQUEST['skip']:0);

$ethq = ($_REQUEST['eth']!=""?$_REQUEST['eth']:"");


if ($ethq!=""){
    foreach($eths as $eth=>$ethv){
    if(strpos(rs($ethv->etnikotita) ,rs($ethq))!== false){
         $selectedEth[] = $ethv;
    }
}
}

if(isset($selectedEth)){
print json_encode(array_slice($selectedEth,$skip,$limit));
}
else print json_encode(array_slice($eths,$skip,$limit));


}
//print '[{"ydrima":"aaa"}]';

