#!/bin/sh
#file: /root/tools/sphinx.sh
#author: jianzi
#===========> sphinx manager tool 2015.10.10 <=======


#. /etc/rc.d/init.d/functions

appName="Sphinx"

stop()
{
	/usr/local/sphinx/bin/searchd -c /usr/local/sphinx/etc/sphinx.conf --stop > /dev/null 2>&1 
        ret=$?
        if [ $ret -eq 0 ]; then
            echo "Stoping $appName: [TRUE]"
        else
            echo "Stoping $appName: [FALSE]"
        fi
	return $ret
}

start()
{
	/usr/local/sphinx/bin/searchd -c /usr/local/sphinx/etc/sphinx.conf > /dev/null 2>&1 
        ret=$?
        if [ $ret -eq 0 ]; then
            echo "Starting $appName: [TRUE]"
        else
            echo "Starting $appName: [FALSE]"
        fi
	return $ret
}

indexer()
{
        /usr/local/sphinx/bin/indexer -c /usr/local/sphinx/etc/sphinx.conf --all > /dev/null 2>&1
	ret=$?
        if [ $ret -eq 0 ]; then
            echo "$appName making index: [TRUE]"
        else
            echo "$appName making index: [FALSE]" 
        fi
        return $ret
}


case $1 in
	restart)
		stop
		indexer
		start
		;;

	stop)
		stop
		;;
	
	start)
		start
		;;
	*)
		echo "Usage:$0 {start|stop|restart}" >&2
		exit 1
		;;
esac
exit 0
