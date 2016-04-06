cd ../..
php exakat init -p test-cli -R https://github.com/lkorth/php-gcm.git -v
php exakat project -v -p test-cli
php exakat report -v -p test-cli -format Xml -file xml 
php exakat report -v -p test-cli -format Text -file txt 
php exakat report -v -p test-cli -format Uml -file uml 
php exakat report -v -p test-cli -format Json -file json
php exakat report -v -p test-cli -format Devoops -file report
php exakat report -v -p test-cli -format Faceted -file faceted
php exakat report -v -p test-cli -format Faceted2 -file faceted2
php exakat report -v -p test-cli -format FacetedJson -file facetedJson
php exakat report -v -p test-cli -format OnepageJson -file onepagejson
php exakat remove -v -p test-cli 
cd -

