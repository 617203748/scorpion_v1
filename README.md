# scorpion_v1
scorpion_v1 php 框架


<VirtualHost *:1235>
    DocumentRoot        "I:/web_www/scorpion_v1/scorpion"

    ErrorLog            "logs/www_1235_error.log"
    CustomLog           "logs/www_1235_access.log" common

    Alias /upload       "I:/web_www/scorpion_v1/scorpion/upload/"
    Alias /             "I:/web_www/scorpion_v1/scorpion/moudle/"

    DirectoryIndex index.php index.html index.htm 
    <Directory />
       Options Indexes FollowSymLinks
	AllowOverride all
	Require all granted
    </Directory>
</VirtualHost>
