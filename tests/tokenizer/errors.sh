find exp -type f | xargs grep -l NEXT | awk '{print "rm "$1}' > rebuildNext.sh
