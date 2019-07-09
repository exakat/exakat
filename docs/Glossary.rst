.. Glossary:

Glossary
============
+ `$`
    + `$_ENV`

      + :ref:`Useless Global <useless-global>`

    + `$_GET`

      + :ref:`Don't Change Incomings <don't-change-incomings>`
      + :ref:`Indirect Injection <indirect-injection>`
      + :ref:`Useless Global <useless-global>`

    + `$_POST`

      + :ref:`Don't Change Incomings <don't-change-incomings>`
      + :ref:`Indirect Injection <indirect-injection>`
      + :ref:`Useless Global <useless-global>`

    + `$_REQUEST`

      + :ref:`Indirect Injection <indirect-injection>`
      + :ref:`Useless Global <useless-global>`

    + `$this`

      + :ref:`$this Belongs To Classes Or Traits <$this-belongs-to-classes-or-traits>`
      + :ref:`$this Is Not For Static Methods <$this-is-not-for-static-methods>`
      + :ref:`Closure May Use $this <closure-may-use-$this>`
      + :ref:`Complex Dynamic Names <complex-dynamic-names>`
      + :ref:`Method Could Be Static <method-could-be-static>`
      + :ref:`Non Static Methods Called In A Static <non-static-methods-called-in-a-static>`
      + :ref:`Static Methods Called From Object <static-methods-called-from-object>`
      + :ref:`Static Methods Can't Contain $this <static-methods-can't-contain-$this>`
      + :ref:`Used Once Variables <used-once-variables>`
      + :ref:`Using $this Outside A Class <using-$this-outside-a-class>`


+ `*`
    + `**`

      + :ref:`** For Exponent <**-for-exponent>`
      + :ref:`Exponent Usage <exponent-usage>`
      + :ref:`Mismatch Type And Default <mismatch-type-and-default>`
      + :ref:`Negative Power <negative-power>`


+ `.`
    + `...`

      + :ref:`Ellipsis Usage <ellipsis-usage>`
      + :ref:`Iffectations <iffectations>`
      + :ref:`Multiple Definition Of The Same Argument <multiple-definition-of-the-same-argument>`
      + :ref:`Reserved Keywords In PHP 7 <reserved-keywords-in-php-7>`
      + :ref:`Should Use Operator <should-use-operator>`


+ `@`
    + `@`

      + :ref:`@ Operator <@-operator>`


+ `A`
    + `Array_search()`

      + :ref:`Find Key Directly <find-key-directly>`

    + `abs()`

      + :ref:`Always Positive Comparison <always-positive-comparison>`
      + :ref:`No Real Comparison <no-real-comparison>`

    + `array()`

      + :ref:`Constant Scalar Expressions <constant-scalar-expressions>`
      + :ref:`Could Be Class Constant <could-be-class-constant>`
      + :ref:`Group Use Trailing Comma <group-use-trailing-comma>`
      + :ref:`Short Syntax For Arrays <short-syntax-for-arrays>`

    + `array_change_key_case()`

      + :ref:`Use Constant As Arguments <use-constant-as-arguments>`

    + `array_column()`

      + :ref:`Should Use array_column() <should-use-array\_column()>`

    + `array_count_values()`

      + :ref:`Avoid array_unique() <avoid-array\_unique()>`
      + :ref:`Slow Functions <slow-functions>`

    + `array_diff()`

      + :ref:`Slow Functions <slow-functions>`

    + `array_fill_keys()`

      + :ref:`Could Use array_fill_keys <could-use-array\_fill\_keys>`

    + `array_filter()`

      + :ref:`Should Use array_filter() <should-use-array\_filter()>`

    + `array_flip()`

      + :ref:`Avoid array_unique() <avoid-array\_unique()>`
      + :ref:`Double array_flip() <double-array\_flip()>`
      + :ref:`Slow Functions <slow-functions>`

    + `array_intersect()`

      + :ref:`Slow Functions <slow-functions>`

    + `array_key_exists()`

      + :ref:`Always Use Function With array_key_exists() <always-use-function-with-array\_key\_exists()>`
      + :ref:`Slow Functions <slow-functions>`
      + :ref:`array_key_exists() Speedup <array\_key\_exists()-speedup>`

    + `array_keys()`

      + :ref:`Avoid array_unique() <avoid-array\_unique()>`
      + :ref:`Find Key Directly <find-key-directly>`
      + :ref:`Slow Functions <slow-functions>`

    + `array_map()`

      + :ref:`Altering Foreach Without Reference <altering-foreach-without-reference>`
      + :ref:`Callback Needs Return <callback-needs-return>`
      + :ref:`Could Be Typehinted Callable <could-be-typehinted-callable>`
      + :ref:`Slow Functions <slow-functions>`

    + `array_merge()`

      + :ref:`No array_merge() In Loops <no-array\_merge()-in-loops>`
      + :ref:`Unpacking Inside Arrays <unpacking-inside-arrays>`

    + `array_merge_recursive()`

      + :ref:`No array_merge() In Loops <no-array\_merge()-in-loops>`

    + `array_multisort()`

      + :ref:`Use Constant As Arguments <use-constant-as-arguments>`

    + `array_push()`

      + :ref:`Avoid array_push() <avoid-array\_push()>`
      + :ref:`Should Use Operator <should-use-operator>`

    + `array_search()`

      + :ref:`Find Key Directly <find-key-directly>`
      + :ref:`Slow Functions <slow-functions>`
      + :ref:`Strpos()-like Comparison <strpos()-like-comparison>`

    + `array_sum()`

      + :ref:`Avoid Concat In Loop <avoid-concat-in-loop>`
      + :ref:`Static Loop <static-loop>`

    + `array_udiff()`

      + :ref:`Slow Functions <slow-functions>`

    + `array_uintersect()`

      + :ref:`Slow Functions <slow-functions>`

    + `array_unique()`

      + :ref:`Avoid array_unique() <avoid-array\_unique()>`
      + :ref:`Could Use array_unique <could-use-array\_unique>`
      + :ref:`Slow Functions <slow-functions>`
      + :ref:`Use Constant As Arguments <use-constant-as-arguments>`

    + `array_unshift()`

      + :ref:`Slow Functions <slow-functions>`

    + `array_walk()`

      + :ref:`Altering Foreach Without Reference <altering-foreach-without-reference>`
      + :ref:`Slow Functions <slow-functions>`

    + `arrayaccess`

      + :ref:`$this Is Not An Array <$this-is-not-an-array>`

    + `arrayobject`

      + :ref:`$this Is Not An Array <$this-is-not-an-array>`

    + `arsort()`

      + :ref:`Use Constant As Arguments <use-constant-as-arguments>`

    + `asort()`

      + :ref:`Use Constant As Arguments <use-constant-as-arguments>`

    + `assert()`

      + :ref:`Assert Function Is Reserved <assert-function-is-reserved>`
      + :ref:`PHP 7.2 Deprecations <php-7.2-deprecations>`


+ `B`
    + `Break`

      + :ref:`Break With 0 <break-with-0>`
      + :ref:`Switch Fallthrough <switch-fallthrough>`

    + `basename()`

      + :ref:`Use Basename Suffix <use-basename-suffix>`
      + :ref:`Use pathinfo() Arguments <use-pathinfo()-arguments>`

    + `break`

      + :ref:`Break Outside Loop <break-outside-loop>`
      + :ref:`Break With 0 <break-with-0>`
      + :ref:`Break With Non Integer <break-with-non-integer>`
      + :ref:`Continue Is For Loop <continue-is-for-loop>`
      + :ref:`Exit() Usage <exit()-usage>`
      + :ref:`Long Arguments <long-arguments>`
      + :ref:`No Need For Else <no-need-for-else>`
      + :ref:`No Return Or Throw In Finally <no-return-or-throw-in-finally>`
      + :ref:`Switch Fallthrough <switch-fallthrough>`
      + :ref:`Unconditional Break In Loop <unconditional-break-in-loop>`
      + :ref:`Unreachable Code <unreachable-code>`


+ `C`
    + `Closure`

      + :ref:`Argument Should Be Typehinted <argument-should-be-typehinted>`
      + :ref:`Closure Could Be A Callback <closure-could-be-a-callback>`
      + :ref:`Could Be Static Closure <could-be-static-closure>`
      + :ref:`Unused Inherited Variable In Closure <unused-inherited-variable-in-closure>`

    + `Compact()`

      + :ref:`Compact Inexistant Variable <compact-inexistant-variable>`
      + :ref:`Could Use Compact <could-use-compact>`

    + `Count()`

      + :ref:`Can't Count Non-Countable <can't-count-non-countable>`
      + :ref:`Uses Default Values <uses-default-values>`

    + `call_user_func()`

      + :ref:`Should Use Operator <should-use-operator>`

    + `call_user_method()`

      + :ref:`PHP 7.0 Removed Functions <php-7.0-removed-functions>`

    + `call_user_method_array()`

      + :ref:`PHP 7.0 Removed Functions <php-7.0-removed-functions>`

    + `chdir()`

      + :ref:`No Hardcoded Path <no-hardcoded-path>`

    + `chr()`

      + :ref:`Should Preprocess Chr <should-preprocess-chr>`
      + :ref:`Should Use Operator <should-use-operator>`

    + `chroot()`

      + :ref:`No Hardcoded Path <no-hardcoded-path>`

    + `class_exists()`

      + :ref:`Undefined ::class <undefined-\:\:class>`

    + `closure`

      + :ref:`Closure Could Be A Callback <closure-could-be-a-callback>`
      + :ref:`Closure May Use $this <closure-may-use-$this>`
      + :ref:`Could Be Typehinted Callable <could-be-typehinted-callable>`
      + :ref:`Parent, Static Or Self Outside Class <parent,-static-or-self-outside-class>`
      + :ref:`Should Use array_filter() <should-use-array\_filter()>`
      + :ref:`Using $this Outside A Class <using-$this-outside-a-class>`
      + :ref:`preg_replace With Option e <preg\_replace-with-option-e>`

    + `collator_compare()`

      + :ref:`Strpos()-like Comparison <strpos()-like-comparison>`

    + `collator_get_sort_key()`

      + :ref:`Strpos()-like Comparison <strpos()-like-comparison>`

    + `compact()`

      + :ref:`Compact Inexistant Variable <compact-inexistant-variable>`

    + `constant()`

      + :ref:`Fully Qualified Constants <fully-qualified-constants>`

    + `continue`

      + :ref:`Break Outside Loop <break-outside-loop>`
      + :ref:`Continue Is For Loop <continue-is-for-loop>`
      + :ref:`No Need For Else <no-need-for-else>`
      + :ref:`No Return Or Throw In Finally <no-return-or-throw-in-finally>`
      + :ref:`Unconditional Break In Loop <unconditional-break-in-loop>`
      + :ref:`Unreachable Code <unreachable-code>`

    + `count()`

      + :ref:`Always Positive Comparison <always-positive-comparison>`
      + :ref:`Cache Variable Outside Loop <cache-variable-outside-loop>`
      + :ref:`No Count With 0 <no-count-with-0>`
      + :ref:`Use Constant As Arguments <use-constant-as-arguments>`
      + :ref:`Use is_countable <use-is\_countable>`
      + :ref:`Uses Default Values <uses-default-values>`

    + `crc32()`

      + :ref:`Crc32() Might Be Negative <crc32()-might-be-negative>`

    + `create_function()`

      + :ref:`PHP 7.2 Deprecations <php-7.2-deprecations>`

    + `crypt()`

      + :ref:`Use password_hash() <use-password\_hash()>`
      + :ref:`crypt() Without Salt <crypt()-without-salt>`

    + `curl_exec()`

      + :ref:`Strpos()-like Comparison <strpos()-like-comparison>`

    + `curl_version()`

      + :ref:`curl_version() Has No Argument <curl\_version()-has-no-argument>`

    + `current()`

      + :ref:`Foreach Don't Change Pointer <foreach-don't-change-pointer>`
      + :ref:`Strpos()-like Comparison <strpos()-like-comparison>`


+ `D`
    + `DateTime`

      + :ref:`Timestamp Difference <timestamp-difference>`
      + :ref:`Use DateTimeImmutable Class <use-datetimeimmutable-class>`

    + `DateTimeImmutable`

      + :ref:`Use DateTimeImmutable Class <use-datetimeimmutable-class>`

    + `Datetime`

      + :ref:`Use DateTimeImmutable Class <use-datetimeimmutable-class>`

    + `Die()`

      + :ref:`Print And Die <print-and-die>`

    + `date_create()`

      + :ref:`PHP 7.1 Microseconds <php-7.1-microseconds>`

    + `datetime`

      + :ref:`Timestamp Difference <timestamp-difference>`
      + :ref:`Use DateTimeImmutable Class <use-datetimeimmutable-class>`

    + `datetimeimmutable`

      + :ref:`Use DateTimeImmutable Class <use-datetimeimmutable-class>`

    + `define()`

      + :ref:`Case Insensitive Constants <case-insensitive-constants>`
      + :ref:`Constants Created Outside Its Namespace <constants-created-outside-its-namespace>`
      + :ref:`Define With Array <define-with-array>`
      + :ref:`Fully Qualified Constants <fully-qualified-constants>`
      + :ref:`Invalid Constant Name <invalid-constant-name>`
      + :ref:`Non-constant Index In Array <non-constant-index-in-array>`
      + :ref:`Use const <use-const>`

    + `die`

      + :ref:`Exit() Usage <exit()-usage>`
      + :ref:`Print And Die <print-and-die>`
      + :ref:`Unreachable Code <unreachable-code>`

    + `die()`

      + :ref:`Exit() Usage <exit()-usage>`
      + :ref:`Print And Die <print-and-die>`
      + :ref:`Unreachable Code <unreachable-code>`

    + `dirname()`

      + :ref:`Could Use __DIR__ <could-use-\_\_dir\_\_>`
      + :ref:`PHP7 Dirname <php7-dirname>`
      + :ref:`Use pathinfo() Arguments <use-pathinfo()-arguments>`


+ `E`
    + `Each()`

      + :ref:`While(List() = Each()) <while(list()-=-each())>`

    + `each()`

      + :ref:`PHP 7.2 Deprecations <php-7.2-deprecations>`

    + `easter_days()`

      + :ref:`Use Constant As Arguments <use-constant-as-arguments>`

    + `empty()`

      + :ref:`Cant Use Return Value In Write Context <cant-use-return-value-in-write-context>`
      + :ref:`Empty With Expression <empty-with-expression>`
      + :ref:`Modernize Empty With Expression <modernize-empty-with-expression>`
      + :ref:`No Count With 0 <no-count-with-0>`
      + :ref:`No isset() With empty() <no-isset()-with-empty()>`
      + :ref:`Variable Is Not A Condition <variable-is-not-a-condition>`

    + `ereg()`

      + :ref:`PHP 7.0 Removed Functions <php-7.0-removed-functions>`

    + `ereg_replace()`

      + :ref:`PHP 7.0 Removed Functions <php-7.0-removed-functions>`

    + `eregi()`

      + :ref:`PHP 7.0 Removed Functions <php-7.0-removed-functions>`

    + `eregi_replace()`

      + :ref:`PHP 7.0 Removed Functions <php-7.0-removed-functions>`

    + `error_clear_last()`

      + :ref:`New Functions In PHP 7.0 <new-functions-in-php-7.0>`

    + `error_reporting()`

      + :ref:`Use Constant As Arguments <use-constant-as-arguments>`

    + `eval()`

      + :ref:`Eval() Usage <eval()-usage>`
      + :ref:`preg_replace With Option e <preg\_replace-with-option-e>`

    + `exit`

      + :ref:`Exit() Usage <exit()-usage>`
      + :ref:`Unreachable Code <unreachable-code>`

    + `exit()`

      + :ref:`Unreachable Code <unreachable-code>`

    + `explode()`

      + :ref:`Implode One Arg <implode-one-arg>`

    + `extract()`

      + :ref:`Configure Extract <configure-extract>`
      + :ref:`Foreach With list() <foreach-with-list()>`
      + :ref:`Register Globals <register-globals>`
      + :ref:`Use Constant As Arguments <use-constant-as-arguments>`


+ `F`
    + `Foreach()`

      + :ref:`Altering Foreach Without Reference <altering-foreach-without-reference>`
      + :ref:`Should Use Foreach <should-use-foreach>`
      + :ref:`Use List With Foreach <use-list-with-foreach>`
      + :ref:`Useless Check <useless-check>`

    + `feof()`

      + :ref:`Possible Infinite Loop <possible-infinite-loop>`

    + `fgetc()`

      + :ref:`Strpos()-like Comparison <strpos()-like-comparison>`

    + `fgetcsv()`

      + :ref:`Possible Infinite Loop <possible-infinite-loop>`

    + `fgets()`

      + :ref:`Possible Infinite Loop <possible-infinite-loop>`

    + `fgetss()`

      + :ref:`Possible Infinite Loop <possible-infinite-loop>`

    + `file()`

      + :ref:`Joining file() <joining-file()>`

    + `file_get_contents()`

      + :ref:`Joining file() <joining-file()>`
      + :ref:`Strpos()-like Comparison <strpos()-like-comparison>`

    + `file_put_contents()`

      + :ref:`No array_merge() In Loops <no-array\_merge()-in-loops>`
      + :ref:`Strpos()-like Comparison <strpos()-like-comparison>`

    + `filter_input()`

      + :ref:`Use Constant As Arguments <use-constant-as-arguments>`
      + :ref:`filter_input() As A Source <filter\_input()-as-a-source>`

    + `filter_input_array()`

      + :ref:`filter_input() As A Source <filter\_input()-as-a-source>`

    + `filter_var()`

      + :ref:`Use Constant As Arguments <use-constant-as-arguments>`

    + `fopen()`

      + :ref:`@ Operator <@-operator>`
      + :ref:`Wrong fopen() Mode <wrong-fopen()-mode>`

    + `for()`

      + :ref:`Bracketless Blocks <bracketless-blocks>`
      + :ref:`For Using Functioncall <for-using-functioncall>`

    + `foreach()`

      + :ref:`Avoid array_unique() <avoid-array\_unique()>`
      + :ref:`Bracketless Blocks <bracketless-blocks>`
      + :ref:`Break Outside Loop <break-outside-loop>`
      + :ref:`Dont Change The Blind Var <dont-change-the-blind-var>`
      + :ref:`Find Key Directly <find-key-directly>`
      + :ref:`Foreach Don't Change Pointer <foreach-don't-change-pointer>`
      + :ref:`Foreach With list() <foreach-with-list()>`
      + :ref:`No Direct Usage <no-direct-usage>`
      + :ref:`Should Use array_column() <should-use-array\_column()>`
      + :ref:`Should Use array_filter() <should-use-array\_filter()>`
      + :ref:`Should Yield With Key <should-yield-with-key>`
      + :ref:`Slow Functions <slow-functions>`
      + :ref:`Useless Referenced Argument <useless-referenced-argument>`
      + :ref:`preg_match_all() Flag <preg\_match\_all()-flag>`

    + `fputcsv()`

      + :ref:`fputcsv() In Loops <fputcsv()-in-loops>`

    + `fread()`

      + :ref:`Possible Infinite Loop <possible-infinite-loop>`
      + :ref:`Strpos()-like Comparison <strpos()-like-comparison>`

    + `fseek()`

      + :ref:`Use Constant As Arguments <use-constant-as-arguments>`

    + `func_get_arg()`

      + :ref:`func_get_arg() Modified <func\_get\_arg()-modified>`

    + `func_get_args()`

      + :ref:`Wrong Number Of Arguments <wrong-number-of-arguments>`
      + :ref:`func_get_arg() Modified <func\_get\_arg()-modified>`


+ `G`
    + `gc_mem_caches()`

      + :ref:`New Functions In PHP 7.0 <new-functions-in-php-7.0>`

    + `generator`

      + :ref:`Don't Loop On Yield <don't-loop-on-yield>`
      + :ref:`Generator Cannot Return <generator-cannot-return>`
      + :ref:`No Return For Generator <no-return-for-generator>`

    + `get_called_class()`

      + :ref:`Detect Current Class <detect-current-class>`

    + `get_class()`

      + :ref:`No Need For get_class() <no-need-for-get\_class()>`
      + :ref:`No get_class() With Null <no-get\_class()-with-null>`

    + `get_html_translation_table()`

      + :ref:`Use Constant As Arguments <use-constant-as-arguments>`

    + `glob()`

      + :ref:`Avoid glob() Usage <avoid-glob()-usage>`
      + :ref:`No Direct Usage <no-direct-usage>`

    + `gmp_div_q()`

      + :ref:`Use Constant As Arguments <use-constant-as-arguments>`

    + `gmp_div_qr()`

      + :ref:`Use Constant As Arguments <use-constant-as-arguments>`

    + `gmp_div_r()`

      + :ref:`Use Constant As Arguments <use-constant-as-arguments>`

    + `gmp_random()`

      + :ref:`PHP 7.2 Deprecations <php-7.2-deprecations>`


+ `H`
    + `hash()`

      + :ref:`Directly Use File <directly-use-file>`

    + `hash_file()`

      + :ref:`Directly Use File <directly-use-file>`

    + `hash_hmac()`

      + :ref:`Directly Use File <directly-use-file>`

    + `hash_update()`

      + :ref:`Directly Use File <directly-use-file>`

    + `hash_update_file()`

      + :ref:`Directly Use File <directly-use-file>`

    + `header()`

      + :ref:`Should Use SetCookie() <should-use-setcookie()>`

    + `highlight_file()`

      + :ref:`Directly Use File <directly-use-file>`

    + `highlight_string()`

      + :ref:`Directly Use File <directly-use-file>`

    + `html_entity_decode()`

      + :ref:`Use Constant As Arguments <use-constant-as-arguments>`

    + `htmlentities()`

      + :ref:`Htmlentities Calls <htmlentities-calls>`
      + :ref:`Use Constant As Arguments <use-constant-as-arguments>`
      + :ref:`Uses Default Values <uses-default-values>`

    + `htmlspecialchars()`

      + :ref:`Htmlentities Calls <htmlentities-calls>`
      + :ref:`Use Constant As Arguments <use-constant-as-arguments>`

    + `htmlspecialchars_decode()`

      + :ref:`Use Constant As Arguments <use-constant-as-arguments>`

    + `http_build_query()`

      + :ref:`Use Constant As Arguments <use-constant-as-arguments>`

    + `http_build_url()`

      + :ref:`Use Constant As Arguments <use-constant-as-arguments>`

    + `http_parse_cookie()`

      + :ref:`Use Constant As Arguments <use-constant-as-arguments>`

    + `http_parse_params()`

      + :ref:`Use Constant As Arguments <use-constant-as-arguments>`

    + `http_redirect()`

      + :ref:`Use Constant As Arguments <use-constant-as-arguments>`

    + `http_support()`

      + :ref:`Use Constant As Arguments <use-constant-as-arguments>`


+ `I`
    + `Isset`

      + :ref:`Isset() On The Whole Array <isset()-on-the-whole-array>`

    + `iconv()`

      + :ref:`Substring First <substring-first>`

    + `iconv_strpos()`

      + :ref:`Strpos()-like Comparison <strpos()-like-comparison>`

    + `iconv_strrpos()`

      + :ref:`Strpos()-like Comparison <strpos()-like-comparison>`

    + `idn_to_ascii()`

      + :ref:`idn_to_ascii() New Default <idn\_to\_ascii()-new-default>`

    + `idn_to_utf8()`

      + :ref:`idn_to_ascii() New Default <idn\_to\_ascii()-new-default>`

    + `image2wbmp()`

      + :ref:`PHP 7.3 Removed Functions <php-7.3-removed-functions>`
      + :ref:`PHP 8.0 Removed Functions <php-8.0-removed-functions>`

    + `imagecolorallocate()`

      + :ref:`Strpos()-like Comparison <strpos()-like-comparison>`

    + `imagecolorallocatealpha()`

      + :ref:`Strpos()-like Comparison <strpos()-like-comparison>`

    + `imagepsbbox()`

      + :ref:`PHP 7.0 Removed Functions <php-7.0-removed-functions>`

    + `imagepsencodefont()`

      + :ref:`PHP 7.0 Removed Functions <php-7.0-removed-functions>`

    + `imagepsextendfont()`

      + :ref:`PHP 7.0 Removed Functions <php-7.0-removed-functions>`

    + `imagepsfreefont()`

      + :ref:`PHP 7.0 Removed Functions <php-7.0-removed-functions>`

    + `imagepsloadfont()`

      + :ref:`PHP 7.0 Removed Functions <php-7.0-removed-functions>`

    + `imagepsslantfont()`

      + :ref:`PHP 7.0 Removed Functions <php-7.0-removed-functions>`

    + `imagepstext()`

      + :ref:`PHP 7.0 Removed Functions <php-7.0-removed-functions>`

    + `implode()`

      + :ref:`Avoid Concat In Loop <avoid-concat-in-loop>`
      + :ref:`Implode One Arg <implode-one-arg>`
      + :ref:`Joining file() <joining-file()>`

    + `import_request_variables()`

      + :ref:`Register Globals <register-globals>`

    + `in_array()`

      + :ref:`Logical To in_array <logical-to-in\_array>`
      + :ref:`Processing Collector <processing-collector>`
      + :ref:`Slow Functions <slow-functions>`
      + :ref:`Strict Comparison With Booleans <strict-comparison-with-booleans>`

    + `instanceof`

      + :ref:`Could Typehint <could-typehint>`
      + :ref:`Should Use Operator <should-use-operator>`
      + :ref:`Undefined ::class <undefined-\:\:class>`
      + :ref:`Unresolved Instanceof <unresolved-instanceof>`
      + :ref:`Use Instanceof <use-instanceof>`
      + :ref:`Useless Interfaces <useless-interfaces>`

    + `intdiv()`

      + :ref:`Could Use Try <could-use-try>`
      + :ref:`New Functions In PHP 7.0 <new-functions-in-php-7.0>`

    + `intval()`

      + :ref:`Should Typecast <should-typecast>`

    + `is_array()`

      + :ref:`Should Use Operator <should-use-operator>`

    + `is_callable()`

      + :ref:`Check All Types <check-all-types>`

    + `is_int()`

      + :ref:`Should Use Operator <should-use-operator>`

    + `is_integer()`

      + :ref:`Use Instanceof <use-instanceof>`

    + `is_iterable()`

      + :ref:`Check All Types <check-all-types>`
      + :ref:`New Functions In PHP 7.1 <new-functions-in-php-7.1>`

    + `is_null()`

      + :ref:`Should Use Operator <should-use-operator>`
      + :ref:`Use === null <use-===-null>`

    + `is_object()`

      + :ref:`Should Use Operator <should-use-operator>`
      + :ref:`Use Instanceof <use-instanceof>`

    + `is_real()`

      + :ref:`Avoid Real <avoid-real>`

    + `is_scalar()`

      + :ref:`Use Instanceof <use-instanceof>`

    + `is_string()`

      + :ref:`Check All Types <check-all-types>`
      + :ref:`Use Instanceof <use-instanceof>`

    + `isset`

      + :ref:`Isset Multiple Arguments <isset-multiple-arguments>`
      + :ref:`Isset() On The Whole Array <isset()-on-the-whole-array>`
      + :ref:`No isset() With empty() <no-isset()-with-empty()>`
      + :ref:`Should Use array_column() <should-use-array\_column()>`
      + :ref:`Should Use array_filter() <should-use-array\_filter()>`
      + :ref:`Slow Functions <slow-functions>`
      + :ref:`Use Instanceof <use-instanceof>`
      + :ref:`Variable Is Not A Condition <variable-is-not-a-condition>`
      + :ref:`array_key_exists() Speedup <array\_key\_exists()-speedup>`
      + :ref:`isset() With Constant <isset()-with-constant>`

    + `iterator_to_array()`

      + :ref:`Should Yield With Key <should-yield-with-key>`


+ `J`
    + `jdtojewish()`

      + :ref:`Use Constant As Arguments <use-constant-as-arguments>`

    + `jpeg2wbmp()`

      + :ref:`PHP 7.2 Removed Functions <php-7.2-removed-functions>`
      + :ref:`PHP 8.0 Removed Functions <php-8.0-removed-functions>`

    + `json_decode()`

      + :ref:`Use json_decode() Options <use-json\_decode()-options>`


+ `K`
    + `krsort()`

      + :ref:`Use Constant As Arguments <use-constant-as-arguments>`

    + `ksort()`

      + :ref:`Use Constant As Arguments <use-constant-as-arguments>`


+ `L`
    + `List()`

      + :ref:`List With Appends <list-with-appends>`

    + `ldap_sort()`

      + :ref:`PHP 8.0 Removed Functions <php-8.0-removed-functions>`

    + `list()`

      + :ref:`Empty List <empty-list>`
      + :ref:`Foreach With list() <foreach-with-list()>`
      + :ref:`List Short Syntax <list-short-syntax>`
      + :ref:`List With Keys <list-with-keys>`
      + :ref:`No List With String <no-list-with-string>`
      + :ref:`Use List With Foreach <use-list-with-foreach>`
      + :ref:`list() May Omit Variables <list()-may-omit-variables>`

    + `ltrim()`

      + :ref:`Substr To Trim <substr-to-trim>`


+ `M`
    + `magic_quotes_runtime()`

      + :ref:`PHP 7.0 Removed Functions <php-7.0-removed-functions>`

    + `mb_chr()`

      + :ref:`New Functions In PHP 7.1 <new-functions-in-php-7.1>`
      + :ref:`New Functions In PHP 7.2 <new-functions-in-php-7.2>`

    + `mb_ord()`

      + :ref:`New Functions In PHP 7.1 <new-functions-in-php-7.1>`
      + :ref:`New Functions In PHP 7.2 <new-functions-in-php-7.2>`

    + `mb_scrub()`

      + :ref:`New Functions In PHP 7.1 <new-functions-in-php-7.1>`
      + :ref:`New Functions In PHP 7.2 <new-functions-in-php-7.2>`

    + `mb_strlen()`

      + :ref:`No Count With 0 <no-count-with-0>`
      + :ref:`Strpos()-like Comparison <strpos()-like-comparison>`

    + `mb_substr()`

      + :ref:`No Substr() One <no-substr()-one>`
      + :ref:`Substr To Trim <substr-to-trim>`

    + `mcrypt_cbc()`

      + :ref:`Functions Removed In PHP 5.5 <functions-removed-in-php-5.5>`
      + :ref:`PHP 7.0 Removed Functions <php-7.0-removed-functions>`

    + `mcrypt_cfb()`

      + :ref:`Functions Removed In PHP 5.5 <functions-removed-in-php-5.5>`
      + :ref:`PHP 7.0 Removed Functions <php-7.0-removed-functions>`

    + `mcrypt_ecb()`

      + :ref:`Functions Removed In PHP 5.5 <functions-removed-in-php-5.5>`
      + :ref:`PHP 7.0 Removed Functions <php-7.0-removed-functions>`

    + `mcrypt_ofb()`

      + :ref:`Functions Removed In PHP 5.5 <functions-removed-in-php-5.5>`
      + :ref:`PHP 7.0 Removed Functions <php-7.0-removed-functions>`

    + `md5()`

      + :ref:`Directly Use File <directly-use-file>`

    + `md5_file()`

      + :ref:`Directly Use File <directly-use-file>`

    + `microtime()`

      + :ref:`Use random_int() <use-random\_int()>`

    + `mkdir()`

      + :ref:`Mkdir Default <mkdir-default>`

    + `move_uploaded_file()`

      + :ref:`move_uploaded_file Instead Of copy <move\_uploaded\_file-instead-of-copy>`

    + `mt_rand()`

      + :ref:`Use random_int() <use-random\_int()>`

    + `mt_srand()`

      + :ref:`Use random_int() <use-random\_int()>`


+ `N`
    + `NULL`

      + :ref:`$this Is Not For Static Methods <$this-is-not-for-static-methods>`
      + :ref:`Check JSON <check-json>`
      + :ref:`Static Methods Can't Contain $this <static-methods-can't-contain-$this>`
      + :ref:`Strpos()-like Comparison <strpos()-like-comparison>`
      + :ref:`array_key_exists() Speedup <array\_key\_exists()-speedup>`

    + `Null`

      + :ref:`Avoid Optional Properties <avoid-optional-properties>`
      + :ref:`Indices Are Int Or String <indices-are-int-or-string>`
      + :ref:`Null Or Boolean Arrays <null-or-boolean-arrays>`
      + :ref:`Scalar Or Object Property <scalar-or-object-property>`

    + `next()`

      + :ref:`Foreach Don't Change Pointer <foreach-don't-change-pointer>`
      + :ref:`Static Loop <static-loop>`
      + :ref:`Strpos()-like Comparison <strpos()-like-comparison>`

    + `nl2br()`

      + :ref:`Joining file() <joining-file()>`

    + `null`

      + :ref:`Always Positive Comparison <always-positive-comparison>`
      + :ref:`Avoid Optional Properties <avoid-optional-properties>`
      + :ref:`Break With Non Integer <break-with-non-integer>`
      + :ref:`Casting Ternary <casting-ternary>`
      + :ref:`Check All Types <check-all-types>`
      + :ref:`Don't Unset Properties <don't-unset-properties>`
      + :ref:`No Reference For Ternary <no-reference-for-ternary>`
      + :ref:`No get_class() With Null <no-get\_class()-with-null>`
      + :ref:`Null On New <null-on-new>`
      + :ref:`Reserved Keywords In PHP 7 <reserved-keywords-in-php-7>`
      + :ref:`Scalar Or Object Property <scalar-or-object-property>`
      + :ref:`Should Use Coalesce <should-use-coalesce>`
      + :ref:`Should Use Operator <should-use-operator>`
      + :ref:`Use === null <use-===-null>`
      + :ref:`Use Nullable Type <use-nullable-type>`
      + :ref:`Weak Typing <weak-typing>`
      + :ref:`isset() With Constant <isset()-with-constant>`


+ `O`
    + `opendir()`

      + :ref:`Avoid glob() Usage <avoid-glob()-usage>`

    + `openssl_random_pseudo_bytes()`

      + :ref:`Use random_int() <use-random\_int()>`


+ `P`
    + `pack()`

      + :ref:`Invalid Pack Format <invalid-pack-format>`

    + `parse_ini_file()`

      + :ref:`Directly Use File <directly-use-file>`
      + :ref:`Use Constant As Arguments <use-constant-as-arguments>`

    + `parse_ini_string()`

      + :ref:`Directly Use File <directly-use-file>`
      + :ref:`Use Constant As Arguments <use-constant-as-arguments>`

    + `parse_str()`

      + :ref:`PHP 7.2 Deprecations <php-7.2-deprecations>`
      + :ref:`Register Globals <register-globals>`
      + :ref:`parse_str() Warning <parse\_str()-warning>`

    + `parse_url()`

      + :ref:`Pathinfo() Returns May Vary <pathinfo()-returns-may-vary>`
      + :ref:`Use Constant As Arguments <use-constant-as-arguments>`

    + `parsekit_compile_file()`

      + :ref:`Directly Use File <directly-use-file>`

    + `parsekit_compile_string()`

      + :ref:`Directly Use File <directly-use-file>`

    + `password_hash()`

      + :ref:`Compare Hash <compare-hash>`
      + :ref:`Use password_hash() <use-password\_hash()>`

    + `password_verify()`

      + :ref:`Compare Hash <compare-hash>`

    + `pathinfo()`

      + :ref:`Pathinfo() Returns May Vary <pathinfo()-returns-may-vary>`
      + :ref:`Use Constant As Arguments <use-constant-as-arguments>`
      + :ref:`Use Pathinfo <use-pathinfo>`
      + :ref:`Use pathinfo() Arguments <use-pathinfo()-arguments>`

    + `pcntl_getpriority()`

      + :ref:`Strpos()-like Comparison <strpos()-like-comparison>`

    + `pg_result_status()`

      + :ref:`Use Constant As Arguments <use-constant-as-arguments>`

    + `pg_select()`

      + :ref:`Use Constant As Arguments <use-constant-as-arguments>`

    + `php_egg_logo_guid()`

      + :ref:`Functions Removed In PHP 5.5 <functions-removed-in-php-5.5>`

    + `php_logo_guid()`

      + :ref:`Functions Removed In PHP 5.5 <functions-removed-in-php-5.5>`

    + `php_real_logo_guid()`

      + :ref:`Functions Removed In PHP 5.5 <functions-removed-in-php-5.5>`

    + `php_sapi_name()`

      + :ref:`Use Constant <use-constant>`

    + `phpcredits()`

      + :ref:`Use Constant As Arguments <use-constant-as-arguments>`

    + `phpinfo()`

      + :ref:`Phpinfo <phpinfo>`
      + :ref:`Use Constant As Arguments <use-constant-as-arguments>`

    + `phpversion()`

      + :ref:`Use Constant <use-constant>`

    + `pi()`

      + :ref:`Use Constant <use-constant>`

    + `png2wbmp()`

      + :ref:`PHP 7.2 Removed Functions <php-7.2-removed-functions>`
      + :ref:`PHP 8.0 Removed Functions <php-8.0-removed-functions>`

    + `posix_access()`

      + :ref:`Use Constant As Arguments <use-constant-as-arguments>`

    + `pow()`

      + :ref:`** For Exponent <**-for-exponent>`
      + :ref:`Negative Power <negative-power>`

    + `preg_filter()`

      + :ref:`Regex On Arrays <regex-on-arrays>`

    + `preg_grep()`

      + :ref:`Regex On Arrays <regex-on-arrays>`
      + :ref:`Use Constant As Arguments <use-constant-as-arguments>`

    + `preg_match()`

      + :ref:`Results May Be Missing <results-may-be-missing>`
      + :ref:`Strpos()-like Comparison <strpos()-like-comparison>`
      + :ref:`Use Constant As Arguments <use-constant-as-arguments>`

    + `preg_match_all()`

      + :ref:`preg_match_all() Flag <preg\_match\_all()-flag>`

    + `preg_replace()`

      + :ref:`Make One Call With Array <make-one-call-with-array>`
      + :ref:`Possible Missing Subpattern <possible-missing-subpattern>`
      + :ref:`Processing Collector <processing-collector>`
      + :ref:`Slow Functions <slow-functions>`
      + :ref:`preg_replace With Option e <preg\_replace-with-option-e>`

    + `preg_replace_callback()`

      + :ref:`Make One Call With Array <make-one-call-with-array>`
      + :ref:`Regex On Arrays <regex-on-arrays>`
      + :ref:`preg_replace With Option e <preg\_replace-with-option-e>`

    + `preg_replace_callback_array()`

      + :ref:`Make One Call With Array <make-one-call-with-array>`
      + :ref:`New Functions In PHP 7.0 <new-functions-in-php-7.0>`
      + :ref:`Regex On Arrays <regex-on-arrays>`
      + :ref:`preg_replace With Option e <preg\_replace-with-option-e>`

    + `preg_split()`

      + :ref:`Use Constant As Arguments <use-constant-as-arguments>`

    + `prev()`

      + :ref:`Strpos()-like Comparison <strpos()-like-comparison>`

    + `printf()`

      + :ref:`Echo Or Print <echo-or-print>`
      + :ref:`Printf Number Of Arguments <printf-number-of-arguments>`

    + `proc_nice()`

      + :ref:`New Functions In PHP 7.2 <new-functions-in-php-7.2>`


+ `R`
    + `rand()`

      + :ref:`Use random_int() <use-random\_int()>`

    + `random_bytes()`

      + :ref:`New Functions In PHP 7.0 <new-functions-in-php-7.0>`
      + :ref:`Random Without Try <random-without-try>`
      + :ref:`Use random_int() <use-random\_int()>`

    + `random_int()`

      + :ref:`New Functions In PHP 7.0 <new-functions-in-php-7.0>`
      + :ref:`Random Without Try <random-without-try>`
      + :ref:`Use random_int() <use-random\_int()>`

    + `readdir()`

      + :ref:`Strpos()-like Comparison <strpos()-like-comparison>`

    + `readfile()`

      + :ref:`Joining file() <joining-file()>`

    + `recode()`

      + :ref:`Directly Use File <directly-use-file>`

    + `recode_file()`

      + :ref:`Directly Use File <directly-use-file>`

    + `recode_string()`

      + :ref:`Directly Use File <directly-use-file>`

    + `round()`

      + :ref:`Use Constant As Arguments <use-constant-as-arguments>`

    + `rsort()`

      + :ref:`Use Constant As Arguments <use-constant-as-arguments>`

    + `rtrim()`

      + :ref:`Substr To Trim <substr-to-trim>`

    + `runkit_import()`

      + :ref:`Use Constant As Arguments <use-constant-as-arguments>`


+ `S`
    + `Strtr()`

      + :ref:`Strtr Arguments <strtr-arguments>`

    + `Substr()`

      + :ref:`Drop Substr Last Arg <drop-substr-last-arg>`

    + `Switch()`

      + :ref:`Missing Cases In Switch <missing-cases-in-switch>`

    + `scandir()`

      + :ref:`Avoid glob() Usage <avoid-glob()-usage>`
      + :ref:`Use Constant As Arguments <use-constant-as-arguments>`

    + `session_start()`

      + :ref:`Use session_start() Options <use-session\_start()-options>`

    + `set_error_handler()`

      + :ref:`Avoid set_error_handler $context Argument <avoid-set\_error\_handler-$context-argument>`

    + `set_exception_handler()`

      + :ref:`set_exception_handler() Warning <set\_exception\_handler()-warning>`

    + `set_magic_quotes_runtime()`

      + :ref:`PHP 7.0 Removed Functions <php-7.0-removed-functions>`

    + `set_socket_blocking()`

      + :ref:`PHP 7.0 Removed Functions <php-7.0-removed-functions>`

    + `setcookie()`

      + :ref:`Set Cookie Safe Arguments <set-cookie-safe-arguments>`
      + :ref:`Should Use SetCookie() <should-use-setcookie()>`

    + `setlocale()`

      + :ref:`Setlocale() Uses Constants <setlocale()-uses-constants>`

    + `setrawcookie()`

      + :ref:`Set Cookie Safe Arguments <set-cookie-safe-arguments>`
      + :ref:`Should Use SetCookie() <should-use-setcookie()>`

    + `settype()`

      + :ref:`Should Typecast <should-typecast>`

    + `sha1()`

      + :ref:`Directly Use File <directly-use-file>`

    + `sha1_file()`

      + :ref:`Directly Use File <directly-use-file>`

    + `show_source()`

      + :ref:`Directly Use File <directly-use-file>`

    + `simplexml_load_file()`

      + :ref:`Directly Use File <directly-use-file>`

    + `simplexml_load_string()`

      + :ref:`Directly Use File <directly-use-file>`

    + `sleep()`

      + :ref:`Avoid sleep()/usleep() <avoid-sleep()/usleep()>`

    + `socket_read()`

      + :ref:`Use Constant As Arguments <use-constant-as-arguments>`

    + `sort()`

      + :ref:`Use Constant As Arguments <use-constant-as-arguments>`

    + `split()`

      + :ref:`PHP 7.0 Removed Functions <php-7.0-removed-functions>`

    + `spliti()`

      + :ref:`PHP 7.0 Removed Functions <php-7.0-removed-functions>`

    + `sql_regcase()`

      + :ref:`PHP 7.0 Removed Functions <php-7.0-removed-functions>`

    + `srand()`

      + :ref:`Use random_int() <use-random\_int()>`

    + `str_ireplace()`

      + :ref:`Make One Call With Array <make-one-call-with-array>`

    + `str_pad()`

      + :ref:`Could Use str_repeat() <could-use-str\_repeat()>`
      + :ref:`Use Constant As Arguments <use-constant-as-arguments>`

    + `str_repeat()`

      + :ref:`Could Use str_repeat() <could-use-str\_repeat()>`

    + `str_replace()`

      + :ref:`Joining file() <joining-file()>`
      + :ref:`Make One Call With Array <make-one-call-with-array>`

    + `stream_isatty()`

      + :ref:`New Functions In PHP 7.2 <new-functions-in-php-7.2>`

    + `stream_socket_client()`

      + :ref:`Use Constant As Arguments <use-constant-as-arguments>`

    + `stream_socket_server()`

      + :ref:`@ Operator <@-operator>`
      + :ref:`Use Constant As Arguments <use-constant-as-arguments>`

    + `stripos()`

      + :ref:`Simplify Regex <simplify-regex>`
      + :ref:`Strpos()-like Comparison <strpos()-like-comparison>`
      + :ref:`strpos() Too Much <strpos()-too-much>`

    + `strlen()`

      + :ref:`Always Positive Comparison <always-positive-comparison>`
      + :ref:`No Count With 0 <no-count-with-0>`

    + `strpos()`

      + :ref:`Simplify Regex <simplify-regex>`
      + :ref:`Slow Functions <slow-functions>`
      + :ref:`Strpos()-like Comparison <strpos()-like-comparison>`
      + :ref:`strpos() Too Much <strpos()-too-much>`

    + `strripos()`

      + :ref:`Strpos()-like Comparison <strpos()-like-comparison>`

    + `strrpos()`

      + :ref:`Strpos()-like Comparison <strpos()-like-comparison>`

    + `strstr()`

      + :ref:`Slow Functions <slow-functions>`

    + `strtok()`

      + :ref:`Strpos()-like Comparison <strpos()-like-comparison>`

    + `strtotime()`

      + :ref:`Next Month Trap <next-month-trap>`
      + :ref:`time() Vs strtotime() <time()-vs-strtotime()>`

    + `strtr()`

      + :ref:`Strtr Arguments <strtr-arguments>`

    + `strval()`

      + :ref:`Concat Empty String <concat-empty-string>`

    + `substr()`

      + :ref:`No List With String <no-list-with-string>`
      + :ref:`No Substr() One <no-substr()-one>`
      + :ref:`Substr To Trim <substr-to-trim>`
      + :ref:`Substring First <substring-first>`
      + :ref:`Use Basename Suffix <use-basename-suffix>`
      + :ref:`strpos() Too Much <strpos()-too-much>`

    + `substr_replace()`

      + :ref:`Make One Call With Array <make-one-call-with-array>`

    + `switch()`

      + :ref:`Bracketless Blocks <bracketless-blocks>`
      + :ref:`Break Outside Loop <break-outside-loop>`
      + :ref:`Missing Cases In Switch <missing-cases-in-switch>`
      + :ref:`Strict Comparison With Booleans <strict-comparison-with-booleans>`
      + :ref:`Switch To Switch <switch-to-switch>`
      + :ref:`Switch With Too Many Default <switch-with-too-many-default>`
      + :ref:`Switch Without Default <switch-without-default>`

    + `sys_get_temp_dir()`

      + :ref:`No Hardcoded Path <no-hardcoded-path>`
      + :ref:`Use System Tmp <use-system-tmp>`


+ `T`
    + `Throwable`

      + :ref:`Can't Throw Throwable <can't-throw-throwable>`
      + :ref:`Empty Try Catch <empty-try-catch>`

    + `throwable`

      + :ref:`Can't Throw Throwable <can't-throw-throwable>`

    + `time()`

      + :ref:`Use random_int() <use-random\_int()>`
      + :ref:`time() Vs strtotime() <time()-vs-strtotime()>`

    + `token_get_all()`

      + :ref:`@ Operator <@-operator>`

    + `trigger_error()`

      + :ref:`Use Constant As Arguments <use-constant-as-arguments>`

    + `trim()`

      + :ref:`Substr To Trim <substr-to-trim>`
      + :ref:`Substring First <substring-first>`


+ `U`
    + `Unset()`

      + :ref:`Multiple Unset() <multiple-unset()>`

    + `Usort()`

      + :ref:`Usort Sorting In PHP 7.0 <usort-sorting-in-php-7.0>`

    + `uasort()`

      + :ref:`Slow Functions <slow-functions>`
      + :ref:`Usort Sorting In PHP 7.0 <usort-sorting-in-php-7.0>`

    + `uksort()`

      + :ref:`Slow Functions <slow-functions>`
      + :ref:`Usort Sorting In PHP 7.0 <usort-sorting-in-php-7.0>`

    + `uniqid()`

      + :ref:`Use random_int() <use-random\_int()>`

    + `unpack()`

      + :ref:`Invalid Pack Format <invalid-pack-format>`

    + `unserialize()`

      + :ref:`Unserialize Second Arg <unserialize-second-arg>`

    + `unset()`

      + :ref:`Multiple Unset() <multiple-unset()>`

    + `usleep()`

      + :ref:`Avoid sleep()/usleep() <avoid-sleep()/usleep()>`

    + `usort()`

      + :ref:`Slow Functions <slow-functions>`


+ `V`
    + `var_dump()`

      + :ref:`var_dump()... Usage <var\_dump()...-usage>`

    + `var_export()`

      + :ref:`var_dump()... Usage <var\_dump()...-usage>`

    + `vprintf()`

      + :ref:`Printf Number Of Arguments <printf-number-of-arguments>`


+ `W`
    + `while()`

      + :ref:`Bracketless Blocks <bracketless-blocks>`
      + :ref:`Break Outside Loop <break-outside-loop>`
      + :ref:`Minus One On Error <minus-one-on-error>`


+ `Y`
    + `yaml_parse()`

      + :ref:`Directly Use File <directly-use-file>`

    + `yaml_parse_file()`

      + :ref:`Directly Use File <directly-use-file>`


+ `Z`
    + `zend_logo_guid()`

      + :ref:`Functions Removed In PHP 5.5 <functions-removed-in-php-5.5>`


+ `_`
    + `__CLASS__`

      + :ref:`::class <\:\:class>`
      + :ref:`Detect Current Class <detect-current-class>`

    + `__DIR__`

      + :ref:`Could Use __DIR__ <could-use-\_\_dir\_\_>`
      + :ref:`No Hardcoded Path <no-hardcoded-path>`
      + :ref:`__DIR__ Then Slash <\_\_dir\_\_-then-slash>`

    + `__FILE__`

      + :ref:`Could Use __DIR__ <could-use-\_\_dir\_\_>`
      + :ref:`No Hardcoded Path <no-hardcoded-path>`

    + `__call`

      + :ref:`Check On __Call Usage <check-on-\_\_call-usage>`
      + :ref:`Must Return Methods <must-return-methods>`
      + :ref:`No Direct Call To Magic Method <no-direct-call-to-magic-method>`

    + `__callStatic`

      + :ref:`Must Return Methods <must-return-methods>`

    + `__clone`

      + :ref:`Direct Call To __clone() <direct-call-to-\_\_clone()>`
      + :ref:`No Direct Call To Magic Method <no-direct-call-to-magic-method>`
      + :ref:`Should Deep Clone <should-deep-clone>`

    + `__construct`

      + :ref:`Cant Instantiate Class <cant-instantiate-class>`
      + :ref:`Don't Send $this In Constructor <don't-send-$this-in-constructor>`
      + :ref:`Must Call Parent Constructor <must-call-parent-constructor>`
      + :ref:`Old Style Constructor <old-style-constructor>`
      + :ref:`Should Chain Exception <should-chain-exception>`

    + `__debugInfo`

      + :ref:`Must Return Methods <must-return-methods>`
      + :ref:`__debugInfo() Usage <\_\_debuginfo()-usage>`

    + `__get`

      + :ref:`Must Return Methods <must-return-methods>`
      + :ref:`No Direct Call To Magic Method <no-direct-call-to-magic-method>`

    + `__invoke`

      + :ref:`Must Return Methods <must-return-methods>`

    + `__isset`

      + :ref:`Must Return Methods <must-return-methods>`

    + `__set_state`

      + :ref:`Must Return Methods <must-return-methods>`

    + `__sleep`

      + :ref:`Must Return Methods <must-return-methods>`

    + `__toString`

      + :ref:`Must Return Methods <must-return-methods>`
      + :ref:`No Direct Call To Magic Method <no-direct-call-to-magic-method>`
      + :ref:`__toString() Throws Exception <\_\_tostring()-throws-exception>`



