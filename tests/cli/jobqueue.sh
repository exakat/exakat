cd ../..
php exakat jobqueue -v & 
sleep 1
php exakat queue -ping -v
php exakat queue -stop -v
cd -

