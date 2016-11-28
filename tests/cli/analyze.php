cd ../..
php exakat init -p ut_nlptools -R https://github.com/atrilla/nlptools.git -v
php exakat project -p ut_nlptools -v

php exakat analyze -p ut_nlptools -T Analyze
php exakat analyze -p ut_nlptools -T Appcontent
php exakat analyze -p ut_nlptools -T Appinfo
php exakat analyze -p ut_nlptools -T Cakephp
php exakat analyze -p ut_nlptools -T Calisthenics
php exakat analyze -p ut_nlptools -T ClearPHP
php exakat analyze -p ut_nlptools -T CompatibilityPHP53
php exakat analyze -p ut_nlptools -T CompatibilityPHP54
php exakat analyze -p ut_nlptools -T CompatibilityPHP55
php exakat analyze -p ut_nlptools -T CompatibilityPHP56
php exakat analyze -p ut_nlptools -T CompatibilityPHP70
php exakat analyze -p ut_nlptools -T CompatibilityPHP71
php exakat analyze -p ut_nlptools -T CompatibilityPHP72
php exakat analyze -p ut_nlptools -T "Dead code"
php exakat analyze -p ut_nlptools -T OneFile
php exakat analyze -p ut_nlptools -T Performances
php exakat analyze -p ut_nlptools -T Portability
php exakat analyze -p ut_nlptools -T Preferences
php exakat analyze -p ut_nlptools -T RadwellCodes
php exakat analyze -p ut_nlptools -T Security
php exakat analyze -p ut_nlptools -T Wordpress
php exakat analyze -p ut_nlptools -T ZendFramework
php exakat analyze -p ut_nlptools -T All

php exakat dump -p ut_nlptools -u -v
php exakat dump -p ut_nlptools -v
 
php exakat remove -p ut_nlptools
cd -

