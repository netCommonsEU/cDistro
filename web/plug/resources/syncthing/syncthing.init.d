#!/bin/sh
#
### BEGIN INIT INFO
# Provides:          syncthing
# Required-Start:    $local_fs $remote_fs $network $syslog $named
# Required-Stop:     $local_fs $remote_fs $network $syslog $named
# Default-Start:     2 3 4 5
# Default-Stop:      0 1 6
# X-Interactive:     true
# Short-Description: Start/Stop Syncthing
### END INIT INFO

DIRPATH="/opt/syncthing"
BINPATH="$DIRPATH/syncthing"
CFGPATH="$DIRPATH/config"
CFGPATH_XML="$CFGPATH/config.xml";
REPOSPATH="$DIRPATH/repos"
USER="www-data"
test -x $BINPATH || exit 1
test -e $CFGPATH_XML || exit 1

start() {
	killall $BINPATH
	su $USER -s "/bin/sh" -c \
		"HOME=$REPOSPATH; $BINPATH -no-browser -home=$CFGPATH" &>/dev/null &
	sleep 1
}

stop() {
	killall $BINPATH
	sleep 1
}

case "$1" in
	start)
		start
		;;
	stop)
		stop
		;;
	restart)
		stop
		start
		;;
	*)
		echo "Usage: $0 {start|stop|restart}" >&2
		exit 1
		;;
esac

exit 0
