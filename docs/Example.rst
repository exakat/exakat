.. _Usage:

Exakat usage
************

Initialization
--------------

A simple run for the report : 

::

    php exakat.phar init -p sculpin -R https://github.com/sculpin/sculpin

This will init the project in the 'projects' folder, with the name 'sculpin', then clone the code with the provided repository. 

Execution
---------

Now, run : 

:: 

    php exakat.phar project -p sculpin

This will run the whole analysis.

Once it is finished, the report are in the folder `projects/sculpin/report` (HTML version) or `projects/sculpin/faceted` (Faceted version). Simply open the 'index.html' file in a browser.
