(function(jsGrid, $, undefined) {

    var Field = jsGrid.Field;

    function PayumField(config) {
        Field.call(this, config);
        this.includeInDataExport = false;
        this._configInitialized = false;
    }

    PayumField.prototype = new Field({
        css: "jsgrid-payum-field",
        align: "center",
        width: 50,
        filtering: false,
        inserting: false,
        editing: false,
        sorting: false,

        buttonClass: "pay-button btn btn-primary",
        payumButtonClass: "jsgrid-payum-button",

        payumButtonTooltip: "Πληρωμή",
        payumButton: true,
        payId: "",
        payValue: 0,
        
        _initConfig: function() {
            this._configInitialized = true;
        },
        itemTemplate: function(value, item) {
			
		var $result = $([]);
                
              if(item.data.case_id!=undefined){ //Bonita start succesfully
	    	    var isPayed=true; 
			if (item.data.first_payment_id==undefined ||item.data.first_payment_id=="" ){
                            console.log(item);
			    isPayed=false;
                            //Free form execption
                            if (item.form=="5ba13d345fd2c6002df023c3") {
                             $result = $result.add(this._createFreePayumButton(item));
                            }
                           if (item.form=="5ba67a345fd2c6002df023cd") {
                             //do nothing
                            }

                            else
			    $result = $result.add(this._createFirstPayumButton(item));
		    }

		    if (item.data.last_total!=undefined&&item.data.final_payment_id==undefined){
			    isPayed=false;
			    $result = $result.add(this._createFinalPayumButton(item));
		    }

		    if (isPayed){
		      $result.add(this._isPayedNote(item))
		   }
                 }
		    return $result;
		},
		_getItemFieldValue: function(item, field) {
            
            var props = field.name.split('.');
            var result = item[props.shift()];

            while(result && props.length) {
                result = result[props.shift()];
            }

            return result;
        },

        _pay: function(item,e,type,fee,gateway_name){
	console.log(item);					    
	        const payumServerUrl = "https://efiling.obi.gr/payum";
                const payum = new Payum(payumServerUrl);
                const payment = {
					       totalAmountInput: fee, 
					       currencyCode: 'EUR', 
					       gatewayName: gateway_name, 
                           description: ("Πληρωμή γιά : ΑΥ-"+item.data.case_id+" Τύπος -"+item.data.application_type).substring(0,125),
                           payer:{
                            email: item.data.applicant_Email_m,
                            id: item.owner,
                            firstName: item.data.applicant_name_m
                           },
                           clientId: item.owner,
                           clientEmail: item.data.applicant_Email_m, //TODO This may be owner email
                           
                           details:{
                            //sharedSecretKey: "Cardlink1", // do not actual put this here! use backend configuration 
                            //mid: "someothermid",
                            
                            // Required for AlphaBank's Test environment (API version 2)
                            billCountry: 'GR',
                            billState: 'Αθήνα',
                            billZip: '15125',
                            billCity: 'Παράδεισος Αμαρουσίου',
                            billAddress: 'Γιάννη Σταυρουλάκη 5',
                            custom3:type,
                            custom4:item.form,
                            custom5:item._id 
                          }
                       };
	     
                 payum.payment.create(payment, (payment) => {
					    console.log("payment",payment);
                        const token = {
                            type: 'capture',
                            payerEmail: payment.email,
                            paymentId: payment.id,
                            afterUrl: "https://efiling.obi.gr/wp-content/app/payum"
                        };

                        payum.token.create(token, (responseToken) => {
							console.log("token",responseToken);
                           payum.execute(responseToken.targetUrl, '#payum-container');
                        });
                    });	     
		},
		
		_isPayedNote : function(item) {
			return $("<label>").html("ok");
		},

        _createFreePayumButton: function(item) {
                var pay = this._pay;
                var fee = item.data.total_application_fees;
                var dialog = this._gatewayDialog;
                      
               $button = $("<input>").addClass(this.buttonClass)
                                 .addClass(this.payumButtonClass)
                                 .attr({type: "button",
                                                        title: this.payumButtonTooltip
                                   })
                                 .val(fee+"€")
                                 .on("click", function(e) {
                                                                             e.preventDefault();
                                                                             e.stopPropagation();
                                         dialog(item, e,pay,"free_fee",fee);
                                         //pay(item, e,"submission_fee",item.data.total_application_fees,'AlphaBank');
                                    });
            return $button;
        },

        _createFirstPayumButton: function(item) {
		var pay = this._pay; 
                var fee = item.data.total_application_fees;
		var dialog = this._gatewayDialog;                
                      
               $button = $("<input>").addClass(this.buttonClass)
                                 .addClass(this.payumButtonClass)
                                 .attr({type: "button",
					                title: this.payumButtonTooltip
                                   })
                                 .val(fee+"€")
                                 .on("click", function(e) {
									     e.preventDefault();
									     e.stopPropagation();
                                         dialog(item, e,pay,"submission_fee",fee);
                                         //pay(item, e,"submission_fee",item.data.total_application_fees,'AlphaBank');
                                    
                                    });
            return $button;
        },
        
        _createFinalPayumButton: function(item) {
		var pay = this._pay;
                var fee = item.data.last_total;
        var dialog = this._gatewayDialog;     
        $button = $("<input>").addClass(this.buttonClass)
                                 .addClass(this.payumButtonClass)
                                 .attr({type: "button",
					                title: this.payumButtonTooltip
                                   })
                                 .val(fee+"€")
                                 .on("click", function(e) {
									     e.preventDefault();
									     e.stopPropagation();
									      dialog(item, e,pay,"final_fee",fee);
                                         //pay(item, e,"final_fee",item.data.last_total,'AlphaBank');
                                    
                                    });
            return $button;
        },




   _gatewayDialog(item,e,payfunction,paytype,fee) {
   
   

function pad(n){
          var n = n + '';
          
   	      //strip in case    
          var reg = /^.*-(\d*)|(\d*)/
          var groups=n.match(reg)
          if (typeof groups[1]!=="undefined") n = groups[1]; 
    
 	  var width=14;
          var z = '0';
          
        return n.length >= width ? n : new Array(width - n.length + 1).join(z) + n;
   }

function parseLocalNum(num) {
    return +(num.replace('.','').replace(",", "."));
}

function feetocents(fee){
   var fstr= ''+fee;
   fstr = ''+Number(+parseLocalNum(fstr)).toFixed(2);
   fstr = fstr.replace(/[\.,]/g,'');

   while (fstr.lengh<3) fstr=fstr+"0";
   return fstr;
}

function reverseString(str) {
    return str.split("").reverse().join("");
}


function ct(fee){
   var multipliers=[1,7,3];
   var rfee = ''+reverseString(feetocents(fee));
   var ct=0;

   for (var i=0; i<rfee.length;i++) {
    var cm=multipliers[i%3];
    var digit= parseInt(rfee[i]);  
    var product=digit*cm;
    ct=ct+product;
   }
   ct =ct%8;

   return ct;

}

function modulo (divident, divisor) {
    var cDivident = '';
    var cRest = '';

    for (var i in divident ) {
        var cChar = divident[i];
        var cOperator = cRest + '' + cDivident + '' + cChar;

        if ( cOperator < parseInt(divisor) ) {
                cDivident += '' + cChar;
        } else {
                cRest = cOperator % divisor;
                if ( cRest == 0 ) {
                    cRest = '';
                }
                cDivident = '';
        }

    }
    cRest += '' + cDivident;
    if (cRest == '') {
        cRest = 0;
    }
    return cRest;
}

    var PT ="0";
    if (paytype=="submission_fee") PT="1";
    if (paytype=="final_fee") PT="2";
    if (paytype=="free_fee") PT="3";
 
    var OC="90772"; //gia to paragogiko RF
    var CT=ct(fee);

    var CID=PT+pad(item.data.case_id);    
    var RF=OC+CT+CID;

    var MC=RF+"271500"
    var Y = modulo(MC,97);
    var CD=98-Y;
    if (CD<10) CD="0"+CD;
    RF="RF"+CD+RF;

    console.log(RF); 


     $('<div align="center"></div>').appendTo('body')
      .html('<div><h6>Παρακαλώ επιλέξτε τρόπο πληρωμής</h6></div>\
      <div style="display:block;"><hr />Μέσω web banking σε οποιαδήποτε τράπεζα με τη χρήση του κωδικού πληρωμής: <br /><br />\
        <center><strong>'+RF+'</strong></center><br />\
       <span style="text-align: left;display: block;font-size: smaller;">Στο περιβάλλον της τράπεζας, επιλέξτε στη Ενότητα \'Πληρωμές\', από τη λίστα των διαθέσιμων <br />πληρωμών, την κατηγορία \'Οργανισμός Βιομηχανικής Ιδιοκτησίας (ΟΒΙ)\' και εισάγετε τον <br />\ παραπάνω κωδικό RF, στο αντίστοιχο πεδίο \'Κωδικός Πληρωμής\'.</span>\
          <hr />\
        <span style="text-align: left;display: block;">Μέσω πιστωτικών, χρεωστικών και προπληρωμένων καρτών των Visa,Mastercard,Maestro,<br />American Express,Diners,Discover.</span><br />\
        <img src="/wp-content/app/efiling/assets/Horizontal_Banner_trans.png" /><br />\
     </div>\
      <span style="text-align: left;display: block;"><label><a href="/?page_id=559" target="_blank">Συμφωνώ με τους όρους χρήσης</a> <input id="confirm" type="checkbox" unchecked /></label></span>')
     .dialog({
        modal: true, title: 'Επιλογή τρόπου πληρωμής', zIndex: 10000, autoOpen: true,
        width: 'auto', resizable: false,
        buttons: {
            "Πληρωμή με κάρτα": function () {
                // $(obj).removeAttr('onclick');                                
                // $(obj).parents('.Parent').remove();
                payfunction(item, e,paytype,fee,'AlphaBank');//AlphaBank
                $(this).dialog("close");
            },
            "Πληρωμή με Masterpass": function () {                                                                 
                payfunction(item, e,paytype,fee,'AlphaBankMasterpass');//AlphaBankMasterpass
                $(this).dialog("close");
            }
        },
        close: function (event, ui) {
            $(this).remove();
        },


       open: function(){
        
        $(".ui-dialog-buttonset button").prop('disabled', true);
       }

       });


      $("#confirm").change(function() {
          $(".ui-dialog-buttonset button").prop('disabled', this.checked ?  false:true );
      });
      

    },
        editValue: function() {
            return "";
        }

    });

    jsGrid.fields.payum = jsGrid.PayumField = PayumField;

}(jsGrid, jQuery));
