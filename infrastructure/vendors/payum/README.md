# Payum Latest server prepared for greek bank AlphaBank
injects the payment gateway from [dnna/payum-alphabank](https://github.com/dnna/payum-alphabank)

and modify example demo.html to show complete usage.

also some bug fixes for PayumServer.


original readme folowing:


# PayumServer.
[![Join the chat at https://gitter.im/Payum/Payum](https://badges.gitter.im/Payum/Payum.svg)](https://gitter.im/Payum/Payum?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)
[![Build Status](https://travis-ci.org/Payum/PayumServer.png?branch=master)](https://travis-ci.org/Payum/PayumServer)
[![Total Downloads](https://poser.pugx.org/payum/payum-server/d/total.png)](https://packagist.org/packages/payum/payum-server)
[![Latest Stable Version](https://poser.pugx.org/payum/payum-server/version.png)](https://packagist.org/packages/payum/payum-server)

PHP 7.1+ Payment processing server. Setup once and rule them all. [Here](https://medium.com/@maksim_ka2/your-personal-payment-processing-server-abcc8ed76804#.23mlps63n) you can find a good introduction to what it does and what problems it solves.

## Try it online:

* Demo: https://server.payum.forma-pro.com/demo.html
* Backend: [https://server-ui.payum.forma-pro.com](https://server-ui.payum.forma-pro.com/#!/app/settings?api=https:%2F%2Fserver.payum.forma-pro.com)
* Server: https://server.payum.forma-pro.com

## Run local server

Create docker-compose.yml file:

```yaml
version: '2'
services:
  payum-server:
    image: payum/server
    environment:
      - PAYUM_MONGO_URI=mongodb://mongo:27017/payum_server
      - PAYUM_DEBUG=1
    links:
      - mongo
    ports:
      - "8080:80"

  mongo:
    image: mongo
```

and run `docker-compose up`. You server will be at `localhost:8080` port.

## Test local server
1. Copy `.test.env.dist` to `.test.env`
2. Run `bin/phpunit`

## Docker registry

The [payum/server](https://hub.docker.com/r/payum/server/) image and [payum/server-ui](https://hub.docker.com/r/payum/server-ui/) are built automatically on success push to the master branch.  

## Setup & Run

```bash
$ php composer.phar create-project payum/payum-server --stability=dev
$ cd payum-server
$ php -S 127.0.0.1:8000 web/app.php
```

An example on javascript:

```javascript
  // do new payment
  var payum = new Payum('http://localhost:8000');
    
  var payment = {totalAmount: 100, currencyCode: 'USD'};

  payum.payment.create(payment, function(payment) {
    var token = {
        type: 'capture',
        paymentId: payment.id,
        afterUrl: 'http://afterPaymentIsDoneUrl'
    };

    payum.token.create(token, function(token) {
      // do redirect to token.targetUrl or process at the same page like this:
      payum.execute(token.targetUrl, '#payum-container');
    });
  });
```

_**Note**: You might need a [web client](https://github.com/Payum/PayumServerUI) to manage payments gateways or you can use REST API._

[Site](https://payum.forma-pro.com/)

## Developed by Forma-Pro

Forma-Pro is a full stack development company which interests also spread to open source development. 
Being a team of strong professionals we have an aim an ability to help community by developing cutting edge solutions in the areas of e-commerce, docker & microservice oriented architecture where we have accumulated a huge many-years experience. 
Our main specialization is Symfony framework based solution, but we are always looking to the technologies that allow us to do our job the best way. We are committed to creating solutions that revolutionize the way how things are developed in aspects of architecture & scalability.

If you have any questions and inquires about our open source development, this product particularly or any other matter feel free to contact at opensource@forma-pro.com
## License

Code MIT [licensed](LICENSE.md).
