.. Reports:

Reports
=======

There are several reports that may be extracted from Exakat : 

REPORT_LIST

   `php exakat.phar report -p <project> -format <format> -file <filename>`

Here is an explanation of all the different steps involved in running exakat on your code.

Common behavior
---------------

Default format is Text. Each report has a default filename, that may be configured with the -file option. Each report adds a file extension to the provided filename. 

A special value for -file is 'stdout'. Some formats may be output to stdout, such as Text or Json. Not all format are accepting that value : some format, like Ambassador or Sqlite, may only be written to directories. 

Each report is stored in its <project> folder, under the requested name.

Reports may be generated at any time, during execution of the analysis (partial results) or later, even if another audit is running. 

REPORT_DETAILS

