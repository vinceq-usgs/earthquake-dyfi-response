earthquake-dyfi-response
==============

Subsystem of DYFI that handles collection of user entries from the
web interface.

See https://github.com/usgs/earthquake-eventpages/ for the web interface.


Location
--------

To be run on USGS EHP servers.

Components
----------

The `response.php` file handles the DYFI Questionnaire
whenever a user submits data.
It takes the form data and saves it as a unique
entry file (in raw text format). It also displays the closing
message for the user.

The `response.php` file makes one copy of this entry file for each of the backend servers. Each file is stored in a separate 
directory (one for each backend server). It is each backend server's
responsibility to periodically copy those files to its own server
and remove them from this directory.


INSTALLATION AND CONFIGURATION
------------------------------

This subsytem should be installed in each server that serves the DYFI Questionnaire. Setup requires the following locations:

- An 'apps' directory, [apps]/earthquake-dyfi-response. Executables go here. 
- A 'data' directory, [data]/earthquake-dyfi-response. Incoming entry files, replication directories, and logs go here. Ensure that it has sufficient file space.

1. On your local repository, run 'grunt dist' then rsync the 'dist' directory into the response server's app directory:
    - [apps]/earthquake-dyfi-response

2. Ensure the "action" element of the DYFI Questionnaire form points to the correct executable: 
    - [apps]/earthquake-dyfi-response/.build/src/htdocs/responses.php

3. cd to the repository root and run src/lib/pre-install to configure (this will create the target directories). It will ask for the following settings:
    - MOUNT PATH: URL for the response.php
    - SERVER SHORTNAME: Name of the server this is installed in
    - WRITE DIR: Data directory. For testing, you can use [THIS_REPOSITORY]/test/data/. For installation, you probably want [data]/earthquake-dyfi-response
    - BACKEND SERVERS: Comma-delimited list of DYFI servers that will be accessing this data. The incoming entries will be copied to separate directories each named 'incoming.[SERVER]'.
    - TEST RESPONSE URL: Used for local testing only.

5. On the DYFI backend servers, point the retrieval script to start receiving data from here: 
    - [fullservername]:[data]/earthquake-dyfi-response/incoming.[server]/

