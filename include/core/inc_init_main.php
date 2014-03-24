<?php
# This is the database name
$dbname='histudb';

# Database user name, default is root or httpd for mysql, or postgres for postgresql
$dbusername='root';

# Database user password, default is empty char
$dbpassword='';

# Database host name, default = localhost
$dbhost='localhost';

# First key used for simple chaining protection of scripts
$key='212551131623133';

# Second key used for accessing modules
$key_2level='32722630722432';

# 3rd key for encrypting cookie information
$key_login='16217182067167';

# Main host address or domain
$main_domain='mywww.his';

# Host address for images
$fotoserver_ip='mywww.his';

# Transfer protocol. Use https if this runs on SSL server
$httprotocol='http';

# Set this to your database type. For details refer to ADODB manual or goto http://php.weblogs.com/ADODB/
$dbtype='mysql';

# Set this to your timezone.
$timezone = 'Asia/Ho_Chi_Minh';
date_default_timezone_set($timezone);