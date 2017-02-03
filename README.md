dyfi-responses
==============

The DYFI responses subsytem. This manages the transfer of
DYFI user entries from the web interface to the DYFI backend
servers through the hazdev acquisition servers.

Location
--------

To be run on the HazDev acquisitions servers.

Components
----------

This subsytem has two components:

1. response.php 

This program (found in the htdocs directory of this repo)
is run by the DYFI Questionnaire once the
user submits the form. It takes the form data and saves it as a unique
entry file (raw text format). It also displays the closing
message for the user.


2. replicate_incoming

This program takes each entry file and replicates it to one directory
for each of the backend servers. The entry files will be stored there
until the backend server periodically transfers the files to the backend
system. This program should be run in the background from crontab
(see the top of the file for instructions).


INSTALLATION AND CONFIGURATION
------------------------------

This subsytem should be installed in each "target" or response server for the DYFI Questionnaire. Setup requires the following locations:

- [apps]/earthquake-dyfi-response. Executables go here. 
- [data]/earthquake-dyfi-response. Incoming entry files and replicated data go here. Ensure that it has sufficient file space.

1. Copy the following repository subdirectories into the apps directory:

    - [apps]/earthquake-dyfi-response/htdocs
    - [apps]/earthquake-dyfi-response/conf
    - [apps]/earthquake-dyfi-response/log
    - [apps]/earthquake-dyfi-response/etc
    - [apps]/earthquake-dyfi-response/replicate

2. Ensure the DYFI Questionnaire form points to the correct executable: [apps]/earthquake-dyfi-response/htdocs/responses.php

4. Create the incoming directory and ensure that it is writable by the web user:
[data]/earthquake-dyfi-response/incoming/ 

2. Obtain an ArcGISOnline account (https://www.arcgis.com) and note your ID and Secret Key.

3. Enter the ID and Secret Key and edit the directory entries in the configuration file:
[apps]/earthquake-dyfi-response/conf/response.ini.

6. Copy the replicate directory contents into the apps directory:
[apps]/earthquake-dyfi-response/replicate/

7. Edit the configurable section of the 'replicate_incoming' script to
match the installation. In particular, make sure $home_dir, $NODE, 
$data_dir, $input_dir, $output_dir, and the OS command paths are correct.

8. Add the replicate.sh script in the crontab (preferably running once per minute).

9. The replicate_incoming script will attempt to make the replicated
directories (incoming.[server]) in $output_dir. Make sure these were
created and writeable.

TODO
----
- Ensure response.php messages are HTTPS compliant
- Set up Docker/NPM/Travis??
- Set up configure scripts?

(From web team comments)

- The new web architecture does not have a .../template/static/functions.inc.php file.
- $STYLES and $ENCODING are not supported by the new template.
- Probably want to be more robust in determining cp, mv, etc... locations in replicate_incoming script
