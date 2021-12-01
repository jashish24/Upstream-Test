INTRODUCTION
------------

This module creates a custom user info table
and allows user to import data from csv to the table.
There is a sample csv file located at "assets/user_info.csv"

REQUIREMENTS
------------

This module requires the following modules:

 * [Migrate Source CSV](https://www.drupal.org/project/migrate_source_csv)

INSTALLATION
------------

 * Install as you would normally install a contributed Drupal module. Visit
   https://www.drupal.org/node/1897420 for further information.

CONFIGURATION
-------------

The provided migration Import User Info (migrations/ge_user_info_import.yml)
required csv file to import data. It is currently using the default sample
file located at "assets/user_info.csv". You will have to change the csv path
as per you Drupal root setup under source/path in migration yml file
(currently set to: "modules/custom/ge_user_info_import/assets/user_info.csv")
