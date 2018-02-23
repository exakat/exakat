cd ../..
php exakat init -p phrozn -R https://github.com/atrilla/nlptools.git -v
php exakat project -p phrozn -v

# testing results
php exakat report -p phrozn -format Text -file report -v
php exakat report -p phrozn -format Text -file stdout -v
php exakat report -p phrozn -format Text -v

php exakat report -p phrozn -format Codesniffer -file report -v
php exakat report -p phrozn -format Codesniffer -file stdout -v
php exakat report -p phrozn -format Codesniffer -v

php exakat report -p phrozn -format Uml -file uml -v
php exakat report -p phrozn -format Uml -file stdout -v
php exakat report -p phrozn -format Uml -v

php exakat report -p phrozn -format Diplomat -file uml -v
php exakat report -p phrozn -format Diplomat -file stdout -v
php exakat report -p phrozn -format Diplomat -v

php exakat report -p phrozn -format Dependencywheel -file uml -v
php exakat report -p phrozn -format Dependencywheel -file stdout -v
php exakat report -p phrozn -format Dependencywheel -v

php exakat report -p phrozn -format Ambassador -file aaaa -v
php exakat report -p phrozn -format Ambassador -file stdout -v
php exakat report -p phrozn -format Ambassador -v

php exakat report -p phrozn -format Text -file aaaa -v
php exakat report -p phrozn -format Text -file stdout -v
php exakat report -p phrozn -format Text -v

php exakat report -p phrozn -format XML -file aaaa -v
php exakat report -p phrozn -format XML -file stdout -v
php exakat report -p phrozn -format XML -v

php exakat report -p phrozn -format Clustergrammer -file aaaa -v
php exakat report -p phrozn -format Clustergrammer -file stdout -v
php exakat report -p phrozn -format Clustergrammer -v

php exakat report -p phrozn -format RadwellCode -file aaaa -v
php exakat report -p phrozn -format RadwellCode -file stdout -v
php exakat report -p phrozn -format RadwellCode -v

php exakat report -p phrozn -format PhpCompilation -file aaaa -v
php exakat report -p phrozn -format PhpCompilation -file stdout -v
php exakat report -p phrozn -format PhpCompilation -v

php exakat report -p phrozn -format PhpConfiguration -file aaaa -v
php exakat report -p phrozn -format PhpConfiguration -file stdout -v
php exakat report -p phrozn -format PhpConfiguration -v

php exakat report -p phrozn -format Inventories -file aaaa -v
php exakat report -p phrozn -format Inventories -file stdout -v
php exakat report -p phrozn -format Inventories -v

php exakat report -p phrozn -format Plantuml -file aaaa -v
php exakat report -p phrozn -format Plantuml -file stdout -v
php exakat report -p monolog -format Plantuml -v

php exakat report -p phrozn -format SimpleHtml -file aaaa -v
php exakat report -p phrozn -format SimpleHtml -file stdout -v
php exakat report -p phrozn -format SimpleHtml -v

php exakat report -p phrozn -format CodeFlower -file aaaa -v
php exakat report -p phrozn -format CodeFlower -file stdout -v
php exakat report -p phrozn -format CodeFlower -v

php exakat report -p phrozn -format Owasp -file aaaa -v
php exakat report -p phrozn -format Owasp -file stdout -v
php exakat report -p phrozn -format Owasp -v

php exakat report -p phrozn -format Marmelab -file aaaa -v
php exakat report -p phrozn -format Marmelab -file stdout -v
php exakat report -p phrozn -format Marmelab -v

php exakat report -p phrozn -format Drillinstructor -file aaaa -v
php exakat report -p phrozn -format Drillinstructor -file stdout -v
php exakat report -p phrozn -format Drillinstructor -v

php exakat report -p phrozn -format Composer -file aaaa -v
php exakat report -p phrozn -format Composer -file stdout -v
php exakat report -p phrozn -format Composer -v

cd -


