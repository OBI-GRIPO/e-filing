 #!/bin/bash
 # This script generates NTLM compatible hash for password
 printf "%s" $1 | iconv -t utf16le | openssl md4
