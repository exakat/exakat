cd ../..
php exakat init -p ut3 -R https://github.com/sculpin/sculpin.git -v
php exakat project -p ut3 -v

# testing results
php exakat analyze -p ut3 -P Variables/Variablenames -v
php exakat results -p ut3 -P Variables/Variablenames -o

php exakat remove -p ut3
cd -

