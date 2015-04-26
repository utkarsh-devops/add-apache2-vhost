# add-apache2-vhost
PHP script that automatically creates Apache2 virtual host for Linux
how to use:
1) Create a new folder that would be a root folder of your new host
2) Place script into the folder
3) [optional] start a built-in php server in this folder with: php -S 127.0.0.1:8000
4) Run script from any browser (localhost:8000/add.php)

Script will ask you to input new ip address for your host and root password to edit necessary config files.

In the end a link must appear leading to a newly generated test page on a virtual host.
