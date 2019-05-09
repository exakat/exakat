cd ../..

#empty project
php exakat init -p ut1 -v
php exakat status -p ut1 
php exakat remove -p ut1

#empty project
php exakat init -p ut1 -none -v
php exakat status -p ut1 
php exakat remove -p ut1

#git project
php exakat init -p ut2 -R https://github.com/sculpin/sculpin.git -v
php exakat status -p ut2 
php exakat remove -p ut2

#git project with password
php exakat init -p ut3 -R https://daseguy:Yt4IqRcXVu@github.com/daseguy/DepotConfidentiel.git -v
php exakat remove -p ut3

#tgz project
php exakat init -p ut4 -tgz -R https://files.phpmyadmin.net/phpMyAdmin/4.6.4/phpMyAdmin-4.6.4-all-languages.tar.gz
php exakat remove -p ut4

#zip project
php exakat init -p ut5 -zip -R tests/cli/sculpin.zip -v
php exakat remove -p ut5

php exakat init -p ut5 -rar -R tests/cli/sculpin.rar -v
php exakat remove -p ut5

php exakat init -p ut5 -sevenz -R  tests/cli/sculpin.7z -v
php exakat remove -p ut5

#cvs
#svn


#copy project
php exakat init -p ut6 -copy -R projects/ut5/code 
php exakat remove -p ut6

php exakat init -p ut7 -symlink -R projects/ut5/code 
php exakat files -p ut7
php exakat remove -p ut7

php exakat init -p ut8人 -v
php exakat init -p ut8人 -v -D # testing for delete
php exakat remove -p ut8人

#Error generating 
php exakat init -p test -v
php exakat init -p onepage -v
php exakat init -p test/test -v
php exakat init -p '!)*@(*!#' -v

# clean previous

cd -

