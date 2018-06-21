# To generate self signed for testing

### Generate the key store 

`keytool -genkey -alias wso2carbon -keyalg RSA -keystore wso2carbon.jks -keysize 2048`

* set wso2carbon as password

## Generate self signed certificate
`openssl genrsa -des3 -out obiCA.key 4096`

`openssl req -x509 -new -nodes -key obiCA.key -sha256 -days 1024 -out obiCA.crt`

`openssl genrsa -out ids.obi.gr 2048`

`openssl x509 -req -in ids.obi.gr.csr -CA obiCA.crt -CAkey obiCA.key -CAcreateserial -out ids.obi.gr.crt -days 500 -sha256`

## add to key store

`keytool -import -alias root -keystore wso2carbon.jks -trustcacerts -file ids.obi.gr.crt -storepass wso2carbon`
`keytool -import -alias ids.obi.gr -keystore wso2carbon.jks -trustcacerts -file ids.obi.gr.crt -storepass wso2carbon`

##### Get stored cert 
`keytool -certreq -alias wso2carbon -keystore wso2carbon.jks -storepass wso2carbon`
`keytool -certreq -alias ids.obi.gr -keystore wso2carbon.jks -storepass wso2carbon`
