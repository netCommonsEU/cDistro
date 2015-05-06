#!/bin/bash
# Install, remove a HTTP SSL

CERTHOME=/etc/cloudy/cert
PORTSSL=7443
PORTCDISTRO=7000
APACHEHOME=/etc/apache2/sites-available/
APACHESITE="cdistro-ssl.conf"
CDISTROCONF=/etc/cloudy/cloudy.conf
CERTHOME=/etc/cloudy/cert


Help() {
	echo "Use $0 <install|remove>"
	echo " install: active cloudy web interface in $PORTSSL with https."
	echo " remove: desactive cloudy web interface, and remove "
}

Install() {
	# Load moduls
	a2enmod proxy proxy_http ssl

	# Make keys
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

Listen $PORTSSL
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
    ProxyPass / http://localhost:$PORTCDISTRO/
    ProxyPassReverse / http://localhost:$PORTCDISTRO/

</VirtualHost>

EOF
	}

	# change cloudy.conf
	sed -i -e 's/BINDIP="0.0.0.0"/BINDIP="127.0.0.1"/' $CDISTROCONF

	fgrep -q PORT_SSL $CDISTROCONF || {
		echo -e "\nPORT_SSL=$PORTSSL" >> $CDISTROCONF
	}

	# Restart cdistro
	/etc/init.d/cdistro stop
	/etc/init.d/cdistro start

	# Enable site
	a2ensite ${APACHESITE}

	# Reload apache2
	service apache2 stop
	service apache2 start

	# Execute /etc/rc.local to rebuild /etc/issue
	/etc/rc.local

}

Remove(){
	# Stop services
	service apache stop

	# Disable site
	a2dissite ${APACHESITE}

	# Change cdistro remove PORT_SSL variable
	sed -i -e 's/^PORT_SSL=.*$//' $CDISTROCONF
	sed -i -e 's/BINDIP="127.0.0.1"/BINDIP="0.0.0.0"/' $CDISTROCONF

	# Reload cdistro
	/etc/init.d/cdistro stop
	/etc/init.d/cdistro start

	# Remove config file
	rm -f ${APACHEHOME}${APACHESITE}

	# Remove keys
	rm -rf $CERTHOME

	# Disable moduls
	a2dismod proxy_http proxy ssl

	# Active apache
	service apache start

	# Execute /etc/rc.local to rebuild /etc/issue
	/etc/rc.local
}

case $1 in
	"install")
		Install
		;;
	"remove")
		Remove
		;;
	*)
		Help
		;;
esac
