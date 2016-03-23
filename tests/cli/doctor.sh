cd ../..

# Normal test
php exakat doctor

# No config folder
mv config config_init
php exakat doctor
rm -rf config

# config folder, no config.ini file
mkdir config
php exakat doctor
rm -rf config

# restore
mv config_init config

cd -

