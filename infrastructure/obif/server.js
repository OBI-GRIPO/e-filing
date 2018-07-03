var express = require('express');
var basicAuth = require('basic-auth-connect');
var bodyParser = require('body-parser');
var methodOverride = require('method-override');
var http = require('http');
var config = require('./config.json');
var querystring = require('querystring');


//TODO move as node module
function init(sid){
 
 function step1(){
  console.log("on step 1 "+sid);
  var bonita_post_data=querystring.stringify({
     username: config.bonita_user,
     password: config.bonita_password,
     redirect: false
   });
     
  var bonita_post_options = {
      host: config.bonita_host,
      port: config.bonita_port,
      path: '/bonita/loginservice',
      method: 'POST',
      headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
          'Content-Length': Buffer.byteLength(bonita_post_data)
      }
  };

  // Set up the request
  var post_req = http.request(bonita_post_options, function(res) {
	var setcookie = res.headers["set-cookie"];
    if ( setcookie ) {
				
      setcookie.forEach(
        function ( cookiestr ) {
			console.log(cookiestr);
			
		   
           if (cookiestr.startsWith('X-Bonita-API-Token'))  {
			   
			   
              const regex = /^X-Bonita-API-Token=(\S{8}-\S{4}-\S{4}-\S{4}-\S{12});\sPath=\/bonita$/gm;
               let m;
              while ((m = regex.exec(cookiestr)) !== null) {
                 // This is necessary to avoid infinite loops with zero-width matches
                if (m.index === regex.lastIndex) {
                    regex.lastIndex++;
                     }
                      
                      
                     console.log("go to step 2");                       
                     //call start process
                     step2(m[1],setcookie); //F@Bonita you need JSESSION id also....                     
                     
                     }
	       }
        }
      );
    }
  });

post_req.on('error', function(e) {
  console.log('problem with request: ' + e.message);
});


// post the data
post_req.write(bonita_post_data);
post_req.end();
}
 
 
//Start process with submition data
//TODO set submition id 
function step3(token,cookie,pid){
 	   console.log("on step 1 sid="+sid +" token="+token+" cookie="+cookie+" pid="+pid);
 
 
  var bonita_post_data= JSON.stringify({submition_id:sid});
  console.log(bonita_post_data);
  
  var bonita_post_options = {
      host: config.bonita_host,
      port: config.bonita_port,
      
      path: '/bonita/API/bpm/process/'+pid+'/instantiation',
      method: 'POST',
      headers: {

          'Cookie': cookie,
          'X-Bonita-API-Token': token,
          'Content-Type': 'application/json',
          'Content-Length': Buffer.byteLength(bonita_post_data,'utf8')
      }
  };

  // Set up the request
  var post_req = http.request(bonita_post_options, function(res) {
    res.setEncoding('utf8');
      res.on('data', function (chunk) {
          console.log('Response: ' + chunk);
      });
      res.on('end', function () {
         // console.log(res);
      });
      
   });
   
   post_req.on('error', function(e) {
         console.log('problem with request: ' + e.message);
   });
      
   // post the data
  post_req.write(bonita_post_data);
  post_req.end();
 	
} 
 
//Get Id of process By name
//TODO make sure you get the latest 
function step2(token,cookie){
	
	   console.log("on step 1 sid="+sid +" token="+token+" cookie="+cookie);
	
  var bonita_post_options = {
      host: config.bonita_host,
      port: config.bonita_port,
      
      path: '/bonita/API/bpm/process?s=GetSubmition',
      method: 'GET',
      headers: {

          'Cookie': cookie,
          'X-Bonita-API-Token': token,
      }
  };

 http.get(bonita_post_options,(res)=>{
	 
	  res.on('data', function (chunk) {
		  var jo = JSON.parse(chunk)
          //console.log('Response: ' + JSON.parse(chunk));
          step3(token,cookie,jo[0].id);
      });
 
 });

}


 step1();
}

// Create our application.
var app = express();

// Add Middleware necessary for REST API's
app.use(bodyParser.urlencoded({extended: true}));
app.use(bodyParser.json());
app.use(methodOverride('X-HTTP-Method-Override'));

// Add Basic authentication to our API.
app.use(basicAuth(config.username, config.password));

// Handle the requests.

app.post('/start/process', function(req, res, next) {

  // This shows all the available data for the POST operation.
  //console.log(req.body.submission._id);

   init(req.body.submission._id);

  next();
});

console.log('Listening to port ' + config.port);
app.listen(config.port);
