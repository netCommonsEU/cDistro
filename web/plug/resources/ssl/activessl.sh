#!/bin/sh

CERTHOME=/etc/cloudy/cert
PORTSSL=7443
APACHEHOME=/etc/apache2/sites-available/
APACHESITE=cdistro-ssl
CDISTROCONF=/etc/cdistro.conf

# Load moduls
a2enmod proxy proxy_http ssl

# Make keys
CERTHOME=/etc/cloudy/cert

mkdir -p $CERTHOME
[ ! -f $CERTHOME/cdistro.key ] && {
cat << EOF |openssl req -x509 -nodes -days 3650 -newkey rsa:2048 -keyout $CERTHOME/cdistro.key -out $CERTHOME/cdistro.crt                       
CN
Community Network
Cloudy
Clommunity
.
.
.
EOF
}
# Declare web site

[ ! -f ${APACHEHOME}${APACHESITE} ] && {
cat > ${APACHEHOME}${APACHESITE} << EOF 

Listen 7443
<VirtualHost *:$PORTSSL>

    <Proxy *>
        Order deny,allow
        Allow from all
    </Proxy>

    SSLEngine on
    SSLProxyEngine On
    SSLCertificateFile $CERTHOME/cdistro.crt
    SSLCertificateKeyFile $CERTHOME/cdistro.key

    ProxyRequests Off
    ProxyPreserveHost On
    ProxyPass / http://localhost:7000/
    ProxyPassReverse / http://localhost:7000/
		
</VirtualHost>

EOF
}

# change cdistro.conf
# sed -i -e 's/BINDIP="0.0.0.0"/BINDIP="127.0.0.1"/' /etc/cdistro.conf

fgrep -q PORT_SSL $CDISTROCONF || {
	echo -e "\nPORT_SSL=$PORTSSL" >> $CDISTROCONF
}

# Restart cdistro
/etc/init.d/cdistro stop
/etc/init.d/cdistro start


# Enable site
a2ensite $APACHESITE

# Reload apache2
service apache2 stop
service apache2 start


