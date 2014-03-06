echo "deb http://ftp.es.debian.org/debian/ wheezy main non-free contrib" > /etc/apt/sources.list
echo "deb http://security.debian.org/ wheezy/updates main contrib non-free" >> /etc/apt/sources.list
echo "deb http://ftp.es.debian.org/debian/ wheezy-updates main contrib non-free" >> /etc/apt/sources.list

aptitude update
aptitude install -y vim
vim /etc/hostname
vim /etc/hosts

apt-key adv --keyserver pgp.mit.edu --recv-keys 8AE35B96C3FD5CD9
echo "deb http://repo.clommunity-project.eu/debian unstable/" | tee /etc/apt/sources.list.d/repo.clommunity.list

apt-key adv --keyserver pgp.mit.edu --recv-keys 2E484DAB
echo "deb http://serveis.guifi.net/debian guifi/" | tee /etc/apt/sources.list.d/serveis.guifi.net.list
aptitude update


aptitude install -y screen ssh less telnet avahi-daemon bash-completion curl ddclient
#reboot
