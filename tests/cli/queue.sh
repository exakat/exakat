cd ../..
php exakat jobqueue -v & 
php exakat queue -v -p phpinfo 
sleep 40
php exakat queue -stop -v
cd -