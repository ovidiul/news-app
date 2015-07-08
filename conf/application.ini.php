;<?php
;die(); // For further security
;/*
[main]
;APPLICATION_PATH is the constant defined in index.php
application.directory=APPLICATION_PATH "/application/" 

;product section inherit from yaf section
[database]
username=root
password=root
db=news
server=127.0.0.1
port=8889

[route]
/=indexAction
/add=addNewsAction
/edit=updateNewsAction
/delete=deleteNewsAction
/search=searchNewsAction
/view=viewNewsAction
*/