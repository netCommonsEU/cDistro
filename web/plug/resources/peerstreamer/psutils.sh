#!/bin/bash
VLCUSER=${VLCUSER:-"nobody"}
PSUSER=${PSUSER:-$VLCUSER}
VLCPATH="/usr/bin/vlc"
PSPATH="/opt/peerstreamer/streamer-udp-grapes-static"
LOGFILE="/dev/null"
FILEPEERS="/var/run/pspeers.conf"
LSOF="/usr/bin/lsof"
DEBUG=0
S='|' # Char Separator


doHelp(){
	echo "Use $0 <publish|unpublish|connectrtsp|connectudp|disconnect|info>:"
	echo "	publish <urlstream> <port> [Device] [Description]"
	echo "	unpublish <port>"
	echo "	Use connectrtsp <ip> <port> [Port RTSP Server] [IP RTSP Server] [Device]"
	echo "	connectudp <ip> <port> [IP UDP Server] [Port UDP Server] [Device]"
	echo "	disconnect <port>"
	echo "	info"
	return
}

doDebug(){
	[ $DEBUG -eq 1 ] && echo "[Info]:$@"
}

checkPrograms() {
	[ ! -f $LSOF ] && 
		{
			echo "Need lsof in '"$LSOF"'"
			echo "Maybe you need execute 'apt-get install lsof'"
			exit
		}
	[ ! -f $VLCPATH ] && 
		{
			echo "Need vlc in '"$VLCPATH"'"
			echo "Maybe you need execute 'apt-get install vlc'"
			exit
		}

	[ ! -f $PSPATH ] &&
		{
			echo "Need PeerStreamer in '"$PSPATH"'"
			echo "Install a PeerStreamer compatible version with your hardware"
			exit
		}

}

saveInfoPeer(){
	local pidvlc=${1:-""}
	local pidps=${2:-""}
	local port=${3:-""}
	local udpport=${4:-""}
	local kind=${5:-""}
	local others=${6:-""}

	[ -z "$pidvlc" -o -z "$pidps" -o -z "$port" -o -z "$udpport" -o -z "$kind" ] && return
	doDebug "saveInfoPeer: $@"
	[ ! -f $FILEPEERS ] && {
		doDebug "$FILEPEERS don't exist, now is creating."
		touch $FILEPEERS
	}
	echo "$port$S$pidvlc$S$pidps$S$udpport$S$kind$S$others" >> $FILEPEERS

}

removeInfoPeer(){
	local port=${1:-""}

	[ -z "$port" ] && return

	cat $FILEPEERS | grep -v ^${port}$S > ${FILEPEERS}.temp
	rm $FILEPEERS
	mv ${FILEPEERS}.temp $FILEPEERS

}

getInfoPeer(){
	local port=${1:-""}

	[ -z "$port" ] && return

	cat $FILEPEERS | grep ^${port}$S

}

doInfo(){
	local procesos=($(cat $FILEPEERS))
	local ofs
	local auxline

	for auxline in ${procesos[@]};
	do  
		ofs=$IFS
		IFS=$S read -a data <<< "$auxline"
		IFS=$ofs
		case ${data[4]} in
			"Source")
				echo "Source from '"${data[5]}"'"
				echo "	In port ${data[0]}."
				echo "	Internal publish UDP 127.0.0.1:${data[3]}."
				echo "	VLC pid ${data[1]}."
				echo "	PeerStreamer pid ${data[2]}."
				;;
			"PeerUDP")
				echo "Peer in port ${data[0]}"
				echo "	To UDP Server in ${data[5]}."
				echo "	PeerStreamer pid ${data[2]}."
				;;
			"PeerRTSPServer")
				echo "Peer in port ${data[0]}"
				echo "	To RTSP Server in ${data[5]}."
				echo "	Internal UDP Port ${data[3]}."
				echo "	VLC pid ${data[1]}."
				echo "	PeerStreamer pid ${data[2]}."
				;;			
		esac


	done

}

# Function peer-soucer
doPeerSource(){
	# Source  
	local urlstream=${1:-""}
	local port=${2:-""}
	local device=${3:-"eth0"}
	local description=${4:-"NoDescription"}

	[ -z "$urlstream" -o -z "$port" ] && {
		echo "Use publish <urlstream> <port> [Device] [Description]"
		return
	} 
	udpport=$(findRandFreePort)
	doDebug "Port to upd local: $udpport"

	cmd='su '$VLCUSER' -c "{ '$VLCPATH' -I dummy \"'$urlstream'\" --sout=\"#standard{access=udp,mux=ts,dst=127.0.0.1:'$udpport'}\" > '$LOGFILE' 2>&1 & }; echo \$!"'
	doDebug "Execute: $cmd"
	pidvlc=$(eval $cmd)
	doDebug "PID from VLC is $pidvlc."

	cmd='su '$PSUSER' -c "{ '$PSPATH' -f null,chunkiser=udp,port0='$udpport',addr=127.0.0.1 -P '$port' -I '$device' > '$LOGFILE' 2>&1 & }; echo \$!"'
	doDebug "Execute: $cmd"
	pidps=$(eval $cmd)
	doDebug "PID from PeerStreamer is $pidps."

	saveInfoPeer $pidvlc $pidps $port $udpport "Source" "$urlstream"

	return
}

# Stop peers
doStopPeerSource(){
	local port=${1:-""}

	[ -z "$port" ] && {
		echo "Use unpublish <port>"
		return
	} 

	datosPeer=$(getInfoPeer $port)
	doDebug "Get information: '"$datosPeer"'"
	[ -z "$datosPeer" ] && {
		echo "This port ($port) doesn't exist in $FILEPEERS."
		return
	} 
	pidvlc=$(echo $datosPeer|cut -d "$S" -f 2)
	[ $pidvlc -ne 0 ] && {
		doDebug "Kill VLC: $pidvlc"
		kill -9 $pidvlc
	}
	pidps=$(echo $datosPeer|cut -d "$S" -f 3)
	[ $pidvlc -ne 0 ] && {
		doDebug "Kill PeerStreamer: $pidps"
		kill -9 $pidps
	}
	removeInfoPeer $port
	return

}

# Function peer-with-udp-client
doPeer2UDPClient(){
	# Server RTSP
	local ippeersource=${1:-""}
	local port=${2:-""}
	local ipclient=${3:-"127.0.0.1"}
	local udpport=${4:-""}	
	local device=${5:-"eth0"}


	[ -z "$ippeersource" -o -z "$port" ] && {
		echo "Use connectudp <ip> <port> [IP UDP Server] [Port UDP Server] [Device]"
		return
	} 
	[ -z "$udpport" ] && udpport=$(findRandFreePort)
	doDebug "Port to upd local: $udpport"

	cmd='su '$PSUSER' -c "{ '$PSPATH' -i '$ippeersource' -p '$port' -P '$port' -F null,dechunkiser=udp,port0='$udpport',addr='$ipclient' -I '$device' > '$LOGFILE' 2>&1 & }; echo \$!"'
	doDebug "Execute: $cmd"
	pidps=$(eval $cmd)
	doDebug "PID from PeerStreamer is $pidps."

	doDebug "VLC is not necessary."

	saveInfoPeer "0" $pidps $port $udpport "PeerUDP" "udp://@$ipclient:$udpport"

	return

}
# Funciton peer-with-rtsp-server
doPeer2RTSPServer(){
	# Server RTSP
	local ippeersource=${1:-""}
	local port=${2:-""}
	local udpport=${3:-""}	
	local ipserver=${4:-""}
	local device=${5:-"eth0"}


	[ -z "$ippeersource" -o -z "$port" ] && {
		echo "Use connectrtsp <ip> <port> [Port RTSP Server] [IP RTSP Server] [Device]"
		return
	} 
	[ -z "$udpport" ] && udpport=$(findRandFreePort)
	doDebug "Port to upd local: $udpport"

	cmd='su '$PSUSER' -c "{ '$PSPATH' -i '$ippeersource' -p '$port' -P '$port' -F null,dechunkiser=udp,port0='$udpport',addr=127.0.0.1 -I '$device' > '$LOGFILE' 2>&1 & }; echo \$!"'
	doDebug "Execute: $cmd"
	pidps=$(eval $cmd)
	doDebug "PID from PeerStreamer is $pidps."

	cmd='su '$VLCUSER' -c "{ '$VLCPATH' -I dummy udp://@127.0.0.1:'$udpport' --sout=\"#rtp{sdp=rtsp://'$ipserver':'$udpport'/} --sout-keep\" > '$LOGFILE' 2>&1 & }; echo \$!"'
	doDebug "Execute: $cmd"
	pidvlc=$(eval $cmd)
	doDebug "PID from VLC is $pidvlc."


	saveInfoPeer $pidvlc $pidps $port $udpport "PeerRTSPServer" "rtsp://$ipserver:$udpport/"

	return

}


findRandFreePort() {
	read lowerPort upperPort < /proc/sys/net/ipv4/ip_local_port_range
	rannum=$(( RANDOM % $(( $upperPort - $lowerPort))  ))  
	for (( contador = lowerPort ; contador <= upperPort ; contador++ )); do
	      port=$(( $contador + $rannum ))
	      [ $port -gt $upperPort ] && port=$(( $port - $upperPort + $lowerPort )) 	
	      $LSOF -i -n|awk '{print $9}'|cut -d "-" -f 1|grep -q ":${port}"
	      [ $? -eq 1 ] && { lp=$port; break; }
	done
	[ $lp = 0 ] && { echo "no free local ports available"; return 2; }
	echo $lp
}

if [ $# -lt 1 ]
then
	doHelp
fi

checkPrograms

case $1 in
	"publish")
		shift
		doPeerSource $@
		;;
	"unpublish")
		shift
		doStopPeerSource $@
		;;
	"connectrtsp")
		shift
		doPeer2RTSPServer $@
		;;
	"connectudp")
		shift
		doPeer2UDPClient $@
		;;
	"disconnect")
		shift
		doStopPeerSource $@
		;;
	"info")
		shift
		doInfo $@
		;;
esac