.. Contribute:

Summary
=======

* Supported PHP Extensions
* Supported Frameworks
* Recognized Libraries

Supported PHP Extensions
========================

PHP extensions are used to check for defined structures (classes, interfaces, etc.), identify dependencies and directives. 

PHP extensions should be provided with the list of structures they define (functions, class, constants, traits, variables, interfaces, namespaces), and directives. 

* ext/amqp
* ext/apache
* ext/apc
* ext/apcu
* ext/array
* ext/php-ast
* ext/bcmath
* ext/bzip2
* ext/cairo
* ext/calendar
* ext/com
* Crypto (pecl)
* ext/ctype
* ext/curl
* ext/cyrus
* ext/date
* ext/dba
* ext/dio
* ext/dom
* ext/eaccelerator
* ext/enchant
* ext/ereg
* ext/ev
* ext/event
* ext/exif
* ext/expect
* ext/fann
* ext/fdf
* ext/ffmpeg
* ext/file
* ext/fileinfo
* ext/filter
* ext/fpm
* ext/ftp
* ext/gd
* ext/gearman
* Ext/geoip
* ext/gettext
* ext/gmagick
* ext/gmp
* ext/gnupgp
* ext/hash
* ext/pecl_http
* ext/ibase
* ext/iconv
* ext/iis
* ext/imagick
* ext/imap
* ext/info
* ext/inotify
* ext/intl
* ext/json
* ext/kdm5
* ext/ldap
* ext/libevent
* ext/libxml
* ext/lua
* ext/mail
* ext/mailparse
* ext/math
* ext/mbstring
* ext/mcrypt
* ext/memcache
* ext/memcached
* ext/ming
* ext/mongo
* ext/mssql
* ext/mysql
* ext/mysqli
* ext/ob
* ext/oci8
* ext/odbc
* ext/opcache
* ext/openssl
* ext/parsekit
* ext/pcntl
* ext/pcre
* ext/pdo
* ext/pgsql
* ext/phalcon
* ext/phar
* ext/posix
* ext/proctitle
* ext/pspell
* ext/rar
* ext/readline
* ext/recode
* ext/redis
* ext/reflexion
* ext/runkit
* ext/sem
* ext/sockets
* ext/shmop
* ext/simplexml
* ext/snmp
* ext/soap
* ext/sockets
* ext/spl
* ext/sqlite
* ext/sqlite3
* ext/sqlsrv
* ext/ssh2
* ext/standard
* ext/suhosin
* ext/tidy
* ext/tokenizer
* ext/tokyotyrant
* ext/trader
* ext/v8js
* ext/wddx
* ext/wikidiff2
* ext/wincache
* ext/xcache
* ext/xdebug
* ext/xdiff
* ext/xhprof
* ext/xml
* ext/xmlreader
* ext/xmlrpc
* ext/xmlwriter
* ext/xsl
* ext/yaml
* ext/yis
* ext/zip
* ext/zlib
* ext/0mq

Supported Frameworks
====================

Frameworks are supported when they is an analysis related to them. Then, a selection of analysis may be dedicated to them. 

::
   php exakat.phar analysis -p <project> -T <Framework> 
   

* Cakephp
* Wordpress
* ZendFramework

Recognized Libraries
====================

Libraries that are popular, large and often included in repositories are identified early in the analysis process, and ignored. This prevents Exakat to analysis some code foreign to the current repository : it prevents false positives from this code, and make the analysis much lighter. The whole process is entirely automatic. 

Those libraries, or even some of the, may be included again in the analysis by commenting the ignored_dir[] line, in the projects/<project>/config.ini file. 

* [BBQ](https://github.com/eventio/bbq)
* [DomPDF](https://github.com/dompdf/dompdf)
* [CPDF](https://pear.php.net/reference/PhpDocumentor-latest/li_Cpdf.html)
* [FPDF](http://www.fpdf.org/)
* [jpGraph](http://jpgraph.net/)
* [HTML2PDF](http://sourceforge.net/projects/phphtml2pdf/)
* [HTMLPurifier](http://htmlpurifier.org/)
* [http_class]()
* [IDNA convert](https://github.com/phpWhois/idna-convert)
* [lessc](http://leafo.net/lessphp/)
* [magpieRSS](http://magpierss.sourceforge.net/)
* [MarkDown Parser](http://processwire.com/apigen/class-Markdown_Parser.html)
* [Markdown](https://github.com/michelf/php-markdown)
* [mpdf](http://www.mpdf1.com/mpdf/index.php)
* [oauthToken]()
* [passwordHash]()
* [pChart](http://www.pchart.net/)
* [pclZip](http://www.phpconcept.net/pclzip/)
* [Propel](http://propelorm.org/)
* [gettext Reader](http://pivotx.net/dev/docs/trunk/External/PHP-gettext/gettext_reader.html)
* [phpExecl](https://phpexcel.codeplex.com/)
* [phpMailer](https://github.com/PHPMailer/PHPMailer)
* [qrCode](http://phpqrcode.sourceforge.net/)
* [Services_JSON](https://pear.php.net/package/Services_JSON)
* [sfYaml](https://github.com/fabpot-graveyard/yaml/blob/master/lib/sfYaml.php)
* [swift](http://swiftmailer.org/)
* [Smarty](http://www.smarty.net/)
* [tcpdf](http://www.tcpdf.org/)
* [text_diff](https://pear.php.net/package/Text_Diff)
* [text highlighter](https://pear.php.net/package/Text_Highlighter/)
* [tfpdf](http://www.fpdf.org/en/script/script92.php)
* [UTF8]()
* [CI xmlRPC](http://apigen.juzna.cz/doc/ci-bonfire/Bonfire/class-CI_Xmlrpc.html)
* [Yii](http://www.yiiframework.com/)
* [Zend Framework](http://framework.zend.com/)

