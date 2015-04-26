<?php

$document_root = getcwd();
$local_host_postfix = '.loc';
$host_name = basename(__DIR__) . $local_host_postfix;
$ip = '127.0.0.100';
$path_to_apache_conf = '/etc/apache2/sites-available/';
$path_to_hosts_conf = '/etc/hosts';
$host_config_filename = $path_to_apache_conf . $host_name . '.conf';
$enable_host_cmd = 'sudo a2ensite ';
$reload_server_cmd = 'sudo service apache2 reload';

if (!isset($_POST['ip'])) {

    echo '<script>
    var ip = prompt("Please enter an ip address for new virtual host: (default: 127.0.0.127) ");
    var root = prompt("Please enter admin password to add config to \\n ' . $path_to_apache_conf . ' \\n and to ' . $path_to_hosts_conf . '");
    var form = document.createElement("form");
    var element = document.createElement("input");
    var element1 = document.createElement("input");
    form.method = "POST";
    form.action = "";
    element.name="ip";
    element1.name="root"
    element.value=ip;
    element1.value=root;
    form.appendChild(element); form.appendChild(element1); form.submit();
    </script>';

}

else {

$template = <<<T
<VirtualHost *:80>
        # The ServerName directive sets the request scheme, hostname and port that
        # the server uses to identify itself. This is used when creating
        # redirection URLs. In the context of virtual hosts, the ServerName
        # specifies what hostname must appear in the request's Host: header to
        # match this virtual host. For the default virtual host (this file) this
        # value is not decisive as it is used as a last resort host regardless.
        # However, you must set it for any further virtual host explicitly.

        ServerAdmin admin@%host_name%
        DocumentRoot %document_root%
        ServerName %host_name%

        # Available loglevels: trace8, ..., trace1, debug, info, notice, warn,
        # error, crit, alert, emerg.
        # It is also possible to configure the loglevel for particular
        # modules, e.g.
        #LogLevel info ssl:warn

        ErrorLog \${APACHE_LOG_DIR}/%host_name%-error.log
        CustomLog \${APACHE_LOG_DIR}/%host_name%-access.log combined
<Directory %document_root% >
        AllowOverride All
        Require all granted
</Directory>

# For most configuration files from conf-available/, which are
# enabled or disabled at a global level, it is possible to
# include a line for only one particular virtual host. For example the
# following line enables the CGI configuration for this host only
# after it has been globally disabled with "a2disconf".
#Include conf-available/serve-cgi-bin.conf

php_value error_log /var/log/apache2/%host_name%_error.log
</VirtualHost>
T;

$parsed_template = str_replace(['%document_root%', '%host_name%'], [$document_root, $host_name], $template);

file_put_contents('tmp.tmp', $parsed_template);
exec("echo $root_pwd | sudo -S touch $host_config_filename ");
exec("echo $root_pwd | sudo -S cp tmp.tmp $host_config_filename");
unlink('tmp.tmp');

echo exec("echo $root_pwd| sudo -S -u root cat $path_to_hosts_conf > file.tmp");
echo exec("echo $ip '\t' $host_name >> file.tmp");
echo exec("echo $root_pwd| sudo -S -u root cp file.tmp  $path_to_hosts_conf");
unlink('file.tmp');

file_put_contents("$document_root/index.html", "<h1>Apache virtual host <b>$host_name</b> with document root <b>$document_root</b> was set up successfully!</h1>");
exec("echo $root_pwd | sudo -S a2ensite $host_name.conf");
exec("echo $root_pwd | sudo -S service apache2 reload");

    echo $_POST['ip'];
    $root_pwd = $_POST['root'];
    $ip = $_POST['ip'];
    $_POST['ip'] = $_POST['root'] = null;
    echo "check your new host: <a href=\"http://$host_name\">$host_name</a>";
}


