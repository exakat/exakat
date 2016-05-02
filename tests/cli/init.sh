cd ../..

#empty project
php exakat init -p ut1 -v
php exakat status -p ut1 
php exakat remove -p ut1

#git project
php exakat init -p ut2 -R https://github.com/sculpin/sculpin.git -v
php exakat status -p ut2 
php exakat remove -p ut2

#svn project
php exakat init -p ut3 -R svn://svn.code.sf.net/p/proc-php/code/trunk -svn -v
php exakat status -p ut3 
php exakat remove -p ut3

#bazaar project
#hg project
#tar.gz project
#zip project


cd -

