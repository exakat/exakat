cd ../..
php exakat init -p ut_nlptools -R https://github.com/atrilla/nlptools.git -v
php exakat project -p ut_nlptools -v

# testing results
php exakat report -p ut_nlptools -format Ambassador -file ambassador -v
php exakat report -p ut_nlptools -format Devoops -file ambassador -v
php exakat report -p ut_nlptools -format Text -file report -v
php exakat report -p ut_nlptools -format Uml -file uml -v
php exakat report -p ut_nlptools -format XML -file ambassador -v
php exakat report -p ut_nlptools -format Clustergrammer -file clustergrammer -v
php exakat report -p ut_nlptools -format RadwellCodes -file radwell -v
php exakat report -p ut_nlptools -format PhpConfiguration -file phpconfiguration -v

php exakat report -p ut_nlptools -format UndefinedReport -v

php exakat remove -p ut_nlptools
cd -

