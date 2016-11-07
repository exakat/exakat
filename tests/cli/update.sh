cd ../..

#empty project
php exakat init -p ut1 -v
php exakat status -p ut1 
php exakat remove -p ut1

#git project
php exakat init -p ut2 -R https://github.com/sculpin/sculpin.git -v
php exakat status -p ut2 
php exakat remove -p ut2

#git project with password
php exakat init -p ut3 -R https://brivezac:heureux@bitbucket.org/devteampmeti/sitewebpmeti.git 

cd -

