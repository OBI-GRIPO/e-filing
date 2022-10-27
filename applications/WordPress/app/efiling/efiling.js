var tr_i18n = {
  en: {
    'Submit': 'Complete'
  },
  el: {
    'Submit': 'Αποστολή',
    'Please correct all errors before submitting.': 'Διορθώστε όλα τα σφάλματα πριν από την υποβολή.',
    'Please save all rows before proceeding.': 'Παρακαλώ αποθηκεύστε όλες τις σειρές πριν προχωρήσετε.',
    'File Name': 'Όνομα',
    'Size': 'μέγεθος',
    ' Drop files to attach, or ': 'Αποθέστε αρχείο ή ',
    ' Are you sure you want to cancel? ': 'Είστε βέβαιοι ότι θέλετε να ακυρώσετε;',
    browse: 'επιλέξτε',
    error: "Επιδιορθώστε τα ακόλουθα σφάλματα πριν από την υποβολή..",
    invalid_date: "{{field}} δεν είναι έγκυρη ημερομηνία.",
    invalid_email: "{{field}} πρέπει να είναι μια έγκυρη διεύθυνση ηλεκτρονικού ταχυδρομείου.",
    invalid_regex: "{{field}} δεν αντιστοιχεί στο μοτίβο {{regex}}.",
    mask: "{{field}} δεν ταιριάζει με τη μάσκα.",
    max: "{{field}} δεν μπορεί να είναι μεγαλύτερη από {{max}}.",
    maxLength: "{{field}} πρέπει να είναι μικρότερος από  {{length}} χαρακτήρες.",
    min: "{{field}} δεν μπορεί να είναι μικρότερη από {{min}}.",
    minLength: "{{field}} πρέπει να είναι μεγαλύτερος από {{length}} χαρακτήρες.",
    next: "Επόμενο",
    pattern: "{{field}} δεν ταιριάζει με το πρότυπο  {{pattern}}",
    previous: "Προηγούμενο",
    required: "{{field}} απαιτείται",
    submit: "Αποστολή",
    cancel: "Καθαρισμός",
    stripe: '{{stripe}}',
    month: 'Μήνας',
    day: 'Ημέρα',
    year: 'Χρόνος',
    january: 'Ιανουάριος',
    february: 'Φεβρουάριος',
    march: 'Μάρτιος',
    april: 'Απρίλιος',
    may: 'Μάιος',
    june: 'Ιούνιος',
    july: 'Ιούλιος',
    august: 'Άυγουστος',
    september: 'Σεπτέμβριος',
    october: 'Οκτώβριος',
    november: 'Νοέμβριος',
    december: 'Δεκέμβριος'
  }
}
/**********************************************************************
* 
*    UTILS
*/
function parseJwt(token) {
  var base64Url = token.split('.')[1];
  var base64 = base64Url.replace('-', '+').replace('_', '/');
  return JSON.parse(window.atob(base64));
};

function checkExp(token) {
  var liveToken = parseJwt(token);
  var current_time = Date.now() / 1000;
  if (liveToken.exp < current_time) {
    window.location = '/εφαρμογή/είσοδος/?r=' + window.location.href
  }
}

function getExp(token) {
  var liveToken = parseJwt(token);
  return liveToken.exp;
}

function getParameterByName(name, url) {
  if (!url) url = window.location.href;
  name = name.replace(/[\[\]]/g, '\\$&');
  var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
    results = regex.exec(url);
  if (!results) return null;
  if (!results[2]) return '';
  return decodeURIComponent(results[2].replace(/\+/g, ' '));
}

function setCookie(name, value, exp) {
  var expires = "";
  if (exp) {
    var now = new Date(parseInt(exp) * 1000);
    expires = "; expires=" + now.toUTCString();
  }
  document.cookie = name + "=" + (value || "") + expires + "; path=/";
}

function getCookie(name) {
  var nameEQ = name + "=";
  var ca = document.cookie.split(';');
  for (var i = 0; i < ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == ' ') c = c.substring(1, c.length);
    if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
  }
  return null;
}

function eraseCookie(name) {
  setCookie(name, '', 0);
}

function formatDate(ds) {

  var date = new Date(ds);

  var year = date.getFullYear();
  var month = date.getMonth() + 1;
  var day = date.getDate();
  var hour = date.getHours();
  var min = date.getMinutes();

  if (day < 10) {
    day = '0' + day;
  }
  if (month < 10) {
    month = '0' + month;
  }
  if (hour < 10) {
    hour = '0' + hour;
  }

  if (min < 10) {
    min = '0' + min;
  }
  var formattedDate = day + '/' + month + '/' + year + ' ' + hour + ':' + min;


  return formattedDate;
}

function getStatus(sn) {
  if (sn == 0) return "Προσωρινή";
  if (sn == 1) return "Οριστική (Υποβολή για έλεγχο,αναμονή πληρωμής τέλους κατάθεσης)";
  if (sn == 2) return "Οριστική (Πληρωμένο τέλος κατάθεσης)";
  if (sn == 3) return "Οριστική (Αναμονή αποδοχής λοιπών καταθετών)";
  if (sn == 4) return "Οριστική (Αποδοχή από καταθέτες)";
  if (sn == 5) return "Οριστική (Για επανέλεγχο-συμπλήρωση)";
  if (sn == 6) return "Οριστική (Αναμονή πληρωμής δεύτερου τέλους)";
  if (sn == 7) return "Οριστική (Πληρωμή δεύτερου τέλους)";
  if (sn == 8) return "Οριστική (Τέλος διαδικασιών)";
  if (sn == 9) return "Ακυρωμένη (λόγο παρέλευσης τετραμήνου)";
  if (sn == 10) return "Ακυρωμένη (λόγο παρέλευσης δύο χρόνων)";
  if (sn == 11) return "Ακυρωμένη (λόγο λαθών στην ανασκόπηση)";
  if (sn == 12) return " Ακυρωμένη (λόγο μη αποδοχής των καταθετών)";
  return "";
}

function fio2ReportPDF(formid, submissionid) {

  var template = `
              <div style="width:80%; margin: 0 auto;background-color:white;">
              <center><img src="https://efiling.obi.gr/wp-content/uploads/2019/06/OBIfull-TR_used.gif" /></center>
              <br />
              <center><h4 style="text-decoration: underline;"><strong><u>ΑΠΟΔΕΙΚΤΙΚΟ ΚΑΤΑΘΕΣΗΣ ΑΙΤΗΣΗΣ</u></strong></h4></center>
              <br />
              Κατατέθηκε από: {useremail}
              <br />
              <div style="height:50px;background-color:lightgray;"><center><strong style="top: 20px;position: relative;">Στοιχεία Αίτησης</strong></center></div>
              <div class="row" style="padding:20px">
              <div class="left-side" style="float: left;width: 50%; padding: 20px;">
                 <p>Αριθμός Υπόθεσης: {case_id}</p>
                 <p>Ημερομηνία και ώρα αρχικής δημιουργίας: {start_date}</p>
                 <p>Ημερομηνία και ώρα πληρωμής τέλους κατάθεσης: {payment_submission_date}</p>
                 <p>Αριθμός Πρωτοκόλλου: {protocol_number}</p>
              </div>
              <div class="right-side" style="padding: 20px;">
                 <p>Αριθμός Κατάθεσης (Χορηγείται από τον ΟΒΙ μετά τον έλεγχο): {deposit_number}</p>
                 <p>Κατάσταση αίτησης: {submission_status}</p>
                 <p>Αριθμός αρχικής πληρωμής: {first_payment_id}</p>
                 <p>Αριθμός Δημοσίευσης Ευρωπαϊκού Διπλώματος: {euro_patent_no}</p>
              </div>
              </div>

               <div style="height:50px;background-color:lightgray;"><center><strong style="top: 20px;position: relative;">Στοιχεία Καταθετών</strong></center></div>
               <br />
                1. ({applicant_surname_m}, {applicant_name_m}, Διεύθυνση: {applicant_address_m3},{applicant_city_m},Τ.Κ. {applicant_zip_m},{applicant_country_m},Email  {applicant_email_m})
               <br />
               {loipoi_katathetes}
               <br />

                <div style="height:50px;background-color:lightgray;"><center><strong style="top: 20px;position: relative;">Τίτλος Αίτησης</strong></center></div>
                <br />
                {application_type}
                </div>
              `
    ;

  document.getElementById("pdfsubmission").style.display = "none";
  document.getElementById("pdfsubmissionwait").style.display = "initial";

  Formio.clearCache();
  Formio.setBaseUrl("https://efiling.obi.gr/formio");
  Formio.setProjectUrl("https://efiling.obi.gr/formio");
  var panelForm = new Formio("/form/" + formid + "/submission/" + submissionid);
  //  panelForm.loadForm().then(function(rform) {
  panelForm.loadSubmission().then(function (submission) {

    var formioUser = localStorage.getItem('formioUser');
    var useremail = "";

    try {
      var o = JSON.parse(formioUser);
      useremail = o.data.email;
    } catch (e) {
      console.log(e);
    }


    //console.log(submission,formioUser);
    template = template.replace("{useremail}", (typeof useremail != "undefined") ? useremail : "-"); //useremail

    template = template.replace("{case_id}", (typeof submission.data.case_id != "undefined") ? submission.data.case_id : "-"); //Αριθμός Υπόθεσης: case_id
    template = template.replace("{deposit_number}", (typeof submission.data.deposit_number != "undefined") ? submission.data.deposit_number : "-"); //Αριθμός Κατάθεσης (Χορηγείται από τον ΟΒΙ μετά τον έλεγχο): deposit_number
    template = template.replace("{start_date}", (typeof submission.data.start_date != "undefined") ? formatDate(submission.data.start_date) : "-");//Ημερομηνία και ώρα αρχικής δημιουργίας start_date
    template = template.replace("{payment_submission_date}", (typeof submission.data.payment_submission_date != "undefined") ? formatDate(submission.data.payment_submission_date) : "-"); //Ημερομηνία και ώρα πληρωμής τέλους κατάθεσης payment_submission_date
    template = template.replace("{first_payment_id}", (typeof submission.data.first_payment_id != "undefined") ? submission.data.first_payment_id : "-"); //Αριθμός αρχικής πληρωμής first_payment_id
    template = template.replace("{protocol_number}", (typeof submission.data.protocol_number != "undefined") ? submission.data.protocol_number : "-"); //Αριθμός Πρωτοκόλλου protocol_number
    template = template.replace("{euro_patent_no}", (typeof submission.data.euro_patent_no != "undefined") ? submission.data.euro_patent_no : "-"); //Αριθμός Δημοσίευσης Ευρωπαϊκού Διπλώματος euro_patent_no                       

    template = template.replace("{submission_status}", (typeof submission.data.submission_status != "undefined") ? getStatus(submission.data.submission_status) : "-");   //Κατάσταση αίτησης submission_status


    //katathetis
    template = template.replace("{applicant_surname_m}", (typeof submission.data.applicant_surname_m != "undefined") ? submission.data.applicant_surname_m : "-");  //Επώνυμο ή επωνυμία applicant_surname_m
    template = template.replace("{applicant_name_m}", (typeof submission.data.applicant_name_m != "undefined") ? submission.data.applicant_name_m : "-"); //Όνομα applicant_name_m
    template = template.replace("{applicant_address_m3}", (typeof submission.data.applicant_address_m3 != "undefined") ? submission.data.applicant_address_m3 : "-"); //Διεύθυνση applicant_address_m3
    template = template.replace("{applicant_city_m}", (typeof submission.data.applicant_city_m != "undefined") ? submission.data.applicant_city_m : "-");//Πόλη applicant_city_m
    template = template.replace("{applicant_zip_m}", (typeof submission.data.applicant_zip_m != "undefined") ? submission.data.applicant_zip_m : "-"); //Τ.Κ. applicant_zip_m

    template = template.replace("{applicant_country_m}", (typeof submission.data.applicant_country_m != "undefined") ? submission.data.applicant_country_m : "-"); //Χώρα applicant_country_m
    template = template.replace("{applicant_email_m}", (typeof submission.data.applicant_email_m != "undefined") ? submission.data.applicant_email_m : "-"); //Email  applicant_email_m
    
    //Diaforetikos xeirismos logo lathous sthn forma  
    var applicantsArray = (typeof submission.data.applicants !== "undefined") ? submission.data.applicants : [];

    if (applicantsArray.length == 0) 
      applicantsArray = submission.data.applicant_dataApplicants !== "undefined" ? submission.data.applicant_dataApplicants : [];

    if (typeof applicantsArray !== "undefined" && applicantsArray.length > 0) {

      var counter = 1;
      var applicants = "";
      for (var katathetis of applicantsArray) {

        console.log(katathetis);
        counter = counter + 1;
        var surname = (typeof katathetis.applicant_surname != "undefined") ? katathetis.applicant_surname : "";
        var name = (typeof katathetis.applicant_name != "undefined") ? katathetis.applicant_name : "";
        var address = (typeof katathetis.applicant_address != "undefined") ? katathetis.applicant_address : "";
        var city = (typeof katathetis.applicant_city != "undefined") ? katathetis.applicant_city : "";
        var tk = (typeof katathetis.applicant_zip != "undefined") ? katathetis.applicant_zip : "";
        var country = (typeof katathetis.applicant_country != "undefined") ? katathetis.applicant_country : "";
        var email = (typeof katathetis.applicant_email != "undefined") ? katathetis.applicant_email : "";
        applicants += counter + ". (" + surname + "," + name + ", Διεύθυνση: " + address + "," + city + " Τ.Κ. " + tk + "," + country 
          + ",Email " + email + ") <br />";

      }
      template = template.replace("{loipoi_katathetes}", applicants);

    } else
      template = template.replace("{loipoi_katathetes}", "");

    template = template.replace("{application_type}", (typeof submission.data.application_type != "undefined") ? submission.data.application_type : "-"); //Τίτλος Αίτησης application_type

    //
    const filename = 'Απόδειξη.pdf';
    const element = document.querySelector('#pdfrender');
    element.style.width = "21cm";
    element.style.height = "29.7cm";
    element.innerHTML = template;


    window.scroll(0, 0);
    html2canvas(element, { scale: 0.8, windowWidth: element.scrollWidth, windowHeight: element.scrollHeight }).then(canvas => {
      document.body.appendChild(canvas);
      let pdf = new jsPDF('p', 'mm', 'a4');
      //pdf.text(20,20,"Αριθμός Υπόθεσης:"+submission.data.case_id);
      pdf.addImage(canvas.toDataURL('image/png'), 'PNG', 0, 0, 211, 298);
      pdf.save(filename);

      const ce = document.querySelector('#pdfrender');
      ce.innerHTML = "";
      document.getElementById("pdfsubmission").style.display = "initial";
      window.location.reload(false);

    });
  });
}

function report2PDF(formid, submissionid, rcaseId) {
  var link = document.createElement('a');
  link.href = "https://efiling.obi.gr/wp-content/reports/?formid=" + formid + "&submissionid=" + submissionid;
  link.download = rcaseId + '.pdf';
  link.dispatchEvent(new MouseEvent('click'));
}

function fioPanel2PDF(formid, submissionid) {
  fio2ReportPDF(formid, submissionid);
  return;

  document.getElementById("pdfsubmission").style.display = "none";
  document.getElementById("pdfsubmissionwait").style.display = "initial";

  Formio.clearCache();
  Formio.setBaseUrl("https://efiling.obi.gr/formio");
  Formio.setProjectUrl("https://efiling.obi.gr/formio");
  var panelForm = new Formio("/form/" + formid + "/submission/" + submissionid);
  panelForm.loadForm().then(function (rform) {
    rform.display = "form";

    for (key in rform.components) {
      if (rform.components[key].type == "panel" && rform.components[key].title != "Οριστικοποιήσεις") {
        rform.components[key].customConditional = "show = false;"
      }
    }


    Formio.createForm(document.getElementById("pdfrender"), rform,
      { readOnly: true, viewAsHtml: true }).then(function (form) {
        panelForm.loadSubmission().then(function (submission) {
          form.submission = submission;
          //need to wait
          setTimeout(function () {
            const filename = 'Απόδειξη.pdf';
            const element = document.querySelector('#pdfrender');
            //const element = document.querySelector('div.apodixi_panel');
            window.scroll(0, 0);
            html2canvas(element, { scale: 0.8, windowWidth: element.scrollWidth, windowHeight: element.scrollHeight }).then(canvas => {
              // document.body.appendChild(canvas); 
              const ce = document.querySelector('#pdfrender');
              let pdf = new jsPDF('p', 'mm', 'a4');
              pdf.addImage(canvas.toDataURL('image/png'), 'PNG', 0, 0, 211, 298);
              pdf.save(filename);
              ce.innerHTML = "";
              document.getElementById("pdfsubmission").style.display = "initial";
              window.location.reload(false);
            });
          }, 6000);


        });

      });
  });
}


function fioPrinter(formid, submissionid) {
  var printwindow = window.open('', 'PRINT', 'height=400,width=600');

  printwindow.document.write('<html><head><title>' + document.title + '</title>');
  printwindow.document.write('<link rel="stylesheet" id="bootstrap-css" href="https://efiling.obi.gr/wp-content/themes/OBI/vendors/bootstrap3/css/bootstrap.min.css?ver=4.9.8" type="text/css" media="all">');
  printwindow.document.write('<link type="text/css" rel="stylesheet" href="/wp-content/app/efiling/vendors/formio/formio.full.min.css">');
  printwindow.document.write('<script src="/wp-content/app/efiling/vendors/formio/formio.full.min.js"></script>');
  printwindow.document.write('<style>\
@media print{		\
	@page {\
         size: A4;\
       }		\
\
  a[href]:after { content: none !important; }\
  img[src]:after { content: none !important; }\
   .btn {\
                display:none!important;\
        }\
   \
    #pformio .no-header ul.list-group-striped {\
        display:none;\
    }\
   \
   #pformio .formio-component-datetime .flatpickr-input {\
       direction:rtl!important;\
   }\
\
 #pformio > div >div.card {\
	      margin-top:10px;\
          page-break-before: avoid;\
          page-break-after: always;\
  }\
}\
#preloader { position: fixed; left: 0; top: 0; z-index: 999; width: 100%; height: 100%; overflow: visible; background: #333 url("https://efiling.obi.gr/wp-content/themes/OBI/images/loader.gif") no-repeat center center; }\
    </style>');

  printwindow.document.write('</head><body >');

  printwindow.document.write('<div id="preloader"></div>');
  printwindow.document.write('<div id="pformio"></div>');
  printwindow.document.write('<script>\
    (function(){\
                Formio.clearCache();\
                Formio.setBaseUrl("https://efiling.obi.gr/formio");\
                Formio.setProjectUrl("https://efiling.obi.gr/formio");\
                var printForm= new Formio("/form/'+ formid + '/submission/' + submissionid + '");\
				printForm.loadForm().then(function(rform) {\
                 rform.display="form";\
                 Formio.createForm(document.getElementById("pformio"),rform,\
                 { readOnly: true, viewAsHtml: true }).then(function(form) {\
                         printForm.loadSubmission().then(function(submission) {\
                           form.submission=submission;\
                           });\
                           });\
                });\
               })();\
                </script>');

  printwindow.document.write('</body></html>');

  setTimeout(function () {
    printwindow.document.close(); // necessary for IE >= 10
    printwindow.focus(); // necessary for IE >= 10*/

    printwindow.print();
    printwindow.close();
  }, 4000);

}

/************************************************************************
*
* Proxy resister
*
*
*/
function proxyRegister() {
  var formid = "5d39997b730744002dce5ded";

  var base = location.protocol + '//' + location.hostname + (location.port ? ':' + location.port : '');
  var token = localStorage.getItem('formioToken');
  var base = location.protocol + '//' + location.hostname + (location.port ? ':' + location.port : '');

  if (token == null) { window.location = '/εφαρμογή/είσοδος/?r=' + window.location.href; return; }
  else {

    checkExp(token);

    Formio.clearCache();
    Formio.setBaseUrl(base + '/formio/');
    Formio.setProjectUrl(base + '/formio/');
    Formio.setToken(token);

    var formio = new Formio('/form/' + formid);

    formio.canSubmit().then(function (canSubmit) {
      if (canSubmit) {

        var params = { skip: 0 };
        formio.loadSubmissions({ params: params }).then(function (submissions) {
          var submission = ''
          console.log(submissions);
          if (submissions.serverCount >= 1 && typeof submissions[0] != "undefined") {
            submission = '/submission/' + submissions[0]._id;
          }

          Formio.createForm(document.getElementById('formio'), '/form/' + formid + submission, {
            noAlerts: true,
            language: 'el',
            i18n: tr_i18n
          })
            .then(function (form) {

              form.on('submit', (submission) => {
                //do nothing.
              });
              form.on('submitDone', (submission) => {

                if (getParameterByName("r") == null) { location.replace("/εφαρμογή/εγγραφή/εγγραφη-πληρεξουσιου/εγγραφή-πληρεξουσίου-ενημέρωση/"); return; }
                else {
                  location.replace(getParameterByName("r"));
                }
              });
              form.on('error', (errors) => {
                if (errors != false) {
                  console.log('We have errors!', errors);
                  var ErrorsMsg = "";
                  for (var error in errors) {
                    ErrorsMsg = ErrorsMsg + " - " + errors[error].message;
                    console.log(errors[error]);
                  }
                  alert(ErrorsMsg);
                }
              })
            });
        });
      }
    })
  }
}

/***********************************************************************
 *
 *  Register
 * 
 */
function fioRegister() {
  var formid = "5b35eac4045b880424dd5c7d";
  var base = location.protocol + '//' + location.hostname + (location.port ? ':' + location.port : '');
  localStorage.removeItem('formioAppUser');
  localStorage.removeItem('formioUser');
  localStorage.removeItem('formioToken');
  eraseCookie('wp_jwtToken');
  Formio.clearCache();
  Formio.setBaseUrl(base + '/formio/');
  Formio.setProjectUrl(base + '/formio/');

  Formio.createForm(document.getElementById('formio'), '/form/' + formid, {
    noAlerts: true,
    language: 'el',
    i18n: tr_i18n
  })
    .then(function (form) {

      form.on('submit', (submission) => {
        //do nothing.
      });
      form.on('submitDone', (submission) => {

        if (getParameterByName("r") == null) { location.replace("/εφαρμογή/εγγραφή/ενημέρωση"); return; }
        else {
          location.replace(getParameterByName("r"));
        }

      });
      form.on('error', (errors) => {
        if (errors != false) {
          console.log('We have errors!', errors);
          var ErrorsMsg = "";
          for (var error in errors) {
            ErrorsMsg = ErrorsMsg + " - " + errors[error].message;
            console.log(errors[error]);//TODO na leo oti to email hdh xrisimopoite
          }
          alert(ErrorsMsg);

        }

      })

    });
}
/***********************************************************************
 *
 *  Reset Password
 * 
 */
function resetPassword() {
  var formid = "5b6c33a1989f090035088845";
  var base = location.protocol + '//' + location.hostname + (location.port ? ':' + location.port : '');
  localStorage.removeItem('formioAppUser');
  localStorage.removeItem('formioUser');
  localStorage.removeItem('formioToken');
  eraseCookie('wp_jwtToken');
  Formio.setBaseUrl(base + '/formio/');
  Formio.setProjectUrl(base + '/formio');
  Formio.setToken(null, {});
  Formio.setUser(null, {});
  Formio.clearCache();

  var jwtToken = getParameterByName("x-jwt-token");
  if (jwtToken != null && jwtToken != "") {
    Formio.setToken(jwtToken);
  }

  Formio.createForm(document.getElementById('formio'), '/form/' + formid + '?live=1', {
    noAlerts: true,
    language: 'el',
    i18n: tr_i18n
  })
    .then(function (form) {
      form.on('submit', (submission) => {
        //do nothing.
      });
      form.on('submitDone', (submission) => {
        fioLogout();
      });
      form.on('error', (errors) => {
        if (errors != false)
          console.log('We have errors!', errors);
      })
    });



}
/***********************************************************************
 *
 *  Logout
 * 
 */
function fioLogout() {
  localStorage.removeItem('formioAppUser');
  localStorage.removeItem('formioUser');
  localStorage.removeItem('formioToken');
  eraseCookie('wp_jwtToken');
  Formio.setToken(null, {});
  Formio.setUser(null, {});
  Formio.clearCache();
  window.location = '/';
}
/***********************************************************************
 *
 *  Login
 * 
 */
function fioLogin() {
  var formid = "5b35eac4045b880424dd5c7c";
  var base = location.protocol + '//' + location.hostname + (location.port ? ':' + location.port : '');
  localStorage.removeItem('formioAppUser');
  localStorage.removeItem('formioUser');
  localStorage.removeItem('formioToken');
  eraseCookie('wp_jwtToken');
  Formio.clearCache();
  Formio.setBaseUrl(base + '/formio/');
  Formio.setProjectUrl(base + '/formio/');
  Formio.createForm(document.getElementById('formio'), '/form/' + formid, {
    noAlerts: true,
    language: 'el',
    i18n: tr_i18n
  })
    .then(function (form) {
      form.on('submit', (submission) => {
        //do nothing.
      });
      form.on('submitDone', (submission) => {
        var jwtToken = localStorage.getItem('formioToken');
        setCookie("wp_jwtToken", jwtToken, getExp(jwtToken));
        if (getParameterByName("r") == null) { location.replace("/εφαρμογή/αιτήσεις/"); return; }
        else {
          location.replace(getParameterByName("r"));
        }


      });
      form.on('error', (errors) => {
        if (errors != false)
          console.log('We have errors!', errors);
      });

      var emailField = $("#formio input[type=email].form-control");
      var passwordField = $("#formio input[type=password].form-control");
      var submitButton = $("#formio button[type=submit]");

      emailField.on('keyup', (event) => {
        if(event.which == 13) {
          passwordField.focus();
        }
      });
      passwordField.on('keyup', (event) => {
        if(event.which == 13) {
          submitButton.click();
        }
      });
    });
}

/***********************************************************************
 * 
 *  Aplicant approve
 * 
 */

function aprove() {
  var formid = "5b37565f076e80002c4969f6";
  var base = location.protocol + '//' + location.hostname + (location.port ? ':' + location.port : '');
  var token = localStorage.getItem('formioToken');
  if (token == null) { window.location = '/εφαρμογή/είσοδος/?r=' + window.location.href }
  else {
    checkExp(token);
    Formio.clearCache();
    Formio.setBaseUrl(base + '/formio/');
    Formio.setProjectUrl(base + '/formio/');

    var formio = new Formio('/form/' + formid);
    formio.canSubmit().then(function (canSubmit) {
      if (canSubmit) {
        var agreeVal = getParameterByName("c");
        $("#agree").val(agreeVal);
        $("#agreeBtn").show();
      }
      else {
        //back to login
        window.location = '/εφαρμογή/είσοδος/?r=' + window.location.href;
      }
    });
  }
}

/***********************************************************************
 *
 * Validate email 
 */

function validate() {
  var tokenVal = getParameterByName("r");//location.search.substring(3);
  if (tokenVal == "") {
    window.location = '/';
    return;
  }
  $.post("/wp-content/app/user/register.php", { 'token': tokenVal }, function (data) {
    window.location.replace(data.location);
  });

}

/***********************************************************************
 *
 * Render read olny
 */
function renderSubmissions(formid) {

  var token = localStorage.getItem('formioToken');
  var base = location.protocol + '//' + location.hostname + (location.port ? ':' + location.port : '');

  if (token == null) { window.location = '/εφαρμογή/είσοδος/?r=' + window.location.href; return; }
  else {
    checkExp(token);
    Formio.clearCache();
    Formio.setBaseUrl(base + '/formio/');
    Formio.setProjectUrl(base + '/formio/');
    Formio.setToken(token);

    var formio = new Formio('/form/' + formid);

    formio.canSubmit().then(function (canSubmit) {
      if (canSubmit) {

        jsGrid.locale("el");

        $("#" + formid).jsGrid({
          width: "100%",
          height: "350px",

          inserting: false,
          editing: false,
          sorting: false,
          paging: true,
          pageSize: 10,
          pageLoading: true,
          autoload: true,

          controller: {

            loadData: function (filter) {

              var startIndex = (filter.pageIndex - 1) * filter.pageSize;
              var d = $.Deferred();
              owner = JSON.parse(localStorage.getItem("formioUser"));

              var caseId = getParameterByName("caseId");
              var headers = {
                'Accept': 'application/json',
                'Cache-control': 'no-cache',
                'Content-type': 'application/json'
              };
              var params = { skip: startIndex, owner: owner._id };
              if (caseId != null) {
                params = { skip: startIndex, owner: owner._id, "data.case_id": caseId }
              }

              formio.loadSubmissions({ params: params }, { headers: headers }).then(function (submissions) {
                if (submissions.serverCount >= 1) $("." + formid).removeClass("d-none");
                var retobject = {
                  data: submissions,
                  itemsCount: submissions.serverCount
                }
                d.resolve(retobject);
              });
              return d.promise();
            },
          },

          rowClick: function (args) {
            //$('#read_submission > .modal-dialog > .modal-content').html();
            var formwrapper = document.getElementById('rformio');
            var submissionid = args.item._id;
            var formid = args.item.form;
            var rcaseId = args.item.data.case_id;
            var realsrc = "/formio/form/" + formid + "/submission/" + submissionid;
            Formio.createForm(formwrapper, realsrc, { readOnly: true, viewAsHtml: true, noAlerts: true, language: 'el', i18n: tr_i18n }).then(function (instance) {
              $('#read_submission').modal("show");
              //CyberStream 2020-07: Keep the Jasper report as the only print option of the submission
              //$('#printsubmission').click(function(event){event.preventDefault(); fioPrinter(formid,submissionid);});
              //$('#pdfsubmission').click(function(event){event.preventDefault(); fioPanel2PDF(formid,submissionid);});
              $('#reportsubmission').off();
              $('#reportsubmission').click(function (event) { event.preventDefault(); report2PDF(formid, submissionid, rcaseId); });
            });
          },
          fields: [
            { name: "data.case_id", title: "A/Y", type: "text" },
            { name: "data.application_type", title: "Τύπος", type: "text" },
            { name: "data.applicant_surname_m", title: "Επώνυμο/ια", type: "text" },
            {
              name: "data.patent_title", title: "Τίτλος", type: "text", cellRenderer: function (value, item) {
                return "<td>" + ("" + value).substring(0, 140) + "...</td>";
              }
            },
            { title: "Πληρωμές", type: "payum", payId: "data.first_payment_id", payValue: "data.total_application_fees" },
          ]

        });

      } else {
        //back to login
        window.location = '/εφαρμογή/είσοδος/?r=' + window.location.href;
      }
    });
  };
}

function proxyData() {
  var d = $.Deferred();
  var formid = "5d39997b730744002dce5ded";
  var token = localStorage.getItem('formioToken');
  var base = location.protocol + '//' + location.hostname + (location.port ? ':' + location.port : '');
  var submission = ''
  if (token == null) { window.location = '/εφαρμογή/είσοδος/?r=' + window.location.href; return; } else {
    checkExp(token);
    Formio.clearCache();
    Formio.setBaseUrl(base + '/formio/');
    Formio.setProjectUrl(base + '/formio/');
    Formio.setToken(token);
    var formio = new Formio('/form/' + formid);
    formio.canSubmit().then(function (canSubmit) {
      if (canSubmit) {
        var params = { skip: 0 };
        formio.loadSubmissions({ params: params }).then(function (submissions) {
          if (submissions.serverCount >= 1 && typeof submissions[0] != "undefined") {
            submission = submissions[0].data;
            delete submission.submit;
            submission.proxy_declaration = true;

            d.resolve(submission);
          } else d.resolve("");
        })
      } else d.reject();
    })
  }
  return d.promise();
}

/**********************************************************************
*
*  Render for edit
*
*/

function renderForm(formid) {

  var token = localStorage.getItem('formioToken');
  var base = location.protocol + '//' + location.hostname + (location.port ? ':' + location.port : '');

  if (token == null) { window.location = '/εφαρμογή/είσοδος/?r=' + window.location.href; return; } else {
    checkExp(token);

    Formio.clearCache();
    Formio.setBaseUrl(base + '/formio/');
    Formio.setProjectUrl(base + '/formio/');
    Formio.setToken(token);

    var formio = new Formio('/form/' + formid);

    formio.canSubmit().then(function (canSubmit) {
      if (canSubmit) {

        proxyData().then(function (submission) {

          Formio.createForm(document.getElementById('formio'), '/form/' + formid, {
            noAlerts: true,
            language: 'el',
            i18n: tr_i18n
          }).then(function (form) {

            //To allow extrenal populate for data
            window.getTab = function () {
              if (form.pages[form.page].title == "Πληρεξούσιος") {
                if (submission) {
                  //Delay needed list not renered at first   
                  setTimeout(function () {
                    if (!$('#updateProxyData').length) {
                      $("#formio ul:first-of-type.list-inline").append('<li class="list-inline-item"><button id="updateProxyData" class="btn btn-danger">Ενημέρωση στοιχείων πληρεξουσίου</button></li>')
                      $("#updateProxyData").click(function () {
                        var iform = form;
                        var sData = submission;
                        for (var key in sData) {
                          // skip loop if the property is from prototype
                          if (!sData.hasOwnProperty(key)) continue;
                          var val = sData[key];
                          iform.submission.data[key] = val;
                        }

                        //to save localy for reload
                        localStorage.setItem(form.schema.machineName, JSON.stringify(iform.submission.data));
                        iform.render();
                      })
                    }
                  }, 200);
                }
              }
            };

            var populateData = localStorage.getItem(form.schema.machineName);
            if (populateData !== null) {
              form.submission = { data: JSON.parse(populateData) };
            }


            form.on("change", (changes) => {
              if (changes.changed != "undefined") {
                localStorage.setItem(form.schema.machineName, JSON.stringify(changes.data));
              }
              getTab(); //To show proxy data populator
            });



            form.on('submit', (submission) => {
              //temp disable body    
              $('body').append('<div id="overlay"><div class="inner"><img src="/wp-content/themes/OBI/images/loader.gif" /></div></div>');
              $('#formio').css('pointer-events', 'none');
            });


            form.on('submitDone', (submission) => {
              //TODO isos na elegxo an exxei parei case_id prin kano redirect
              setTimeout(function () {
                localStorage.removeItem(form.schema.machineName);//Clean form after submit
                if (getParameterByName("r") === null) { location.replace("/εφαρμογή/οι-αιτήσεις-μου/"); return; }
                else {
                  location.replace(getParameterByName("r"));
                }
              }, 2200);


            });

            form.on('error', (errors) => {
              if (errors !== false) {
                console.log('We have errors!', errors);
                var ErrorsMsg = "";
                for (var error in errors) {
                  ErrorsMsg = ErrorsMsg + " - " + errors[error].message;
                }
                alert(ErrorsMsg);
                //form.setAlert(false);
                //form.showErrors();         
                //form.cancel();
                //form.reset();
                form.render();// alert show errors

                return false;
              }
            })
          });

        })//To load proxy data if exist

      } else {
        //back to login
        window.location = '/εφαρμογή/είσοδος/?r=' + window.location.href;
      }
    });
  };
}

