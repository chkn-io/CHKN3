# CHKN3
PHP Framework for developing lightweight server-side application. 


## Installation
1. Download and copy the CHKN3 Files on your local server.
2. Open the CHKN console by navigating to http://localhost/chkn/console
   * Enter the command chkn -help for the list of commands.
   * You need to include the word chkn before the command.
3. Change the rootfolder of the app. It must be the name of your main folder.
    * Use `chkn rootfolder` command
4. Application Key Installation. 
    * Use `chkn install key` command
5. Database Configuration
    * Use `chkn db -help` command for the syntax.


## Package
Default classes on CHKN Framework are grouped and compiled inside Drive://xampp/htdocs/rootfolder/config/Package/package.conf  


* **Query Building Class** - *Set value to 0 to disable*
```
QUERY_BUILDER=1
```

* **Encryption and Decryption Class** - *Set value to 0 to disable*
```
ENCRYPTION=1
```

* **Download Class** - *Set value to 0 to disable*
```
DOWNLOAD=1
```

* **DEFAULT Class** - *For displaying the status of the site using `$this->chkn_default`. Set value to 0 to disable*
```
DEFAULTS=1
```

* **Upload Class** - *Set value to 0 to disable*
```
UPLOAD=1
```

* **Session Class** - *Set value to 0 to disable*
```
SESSION=1
```

* **Maintenance Class** - *Set value to 0 to disable*
```
MAINTENANCE_CLASS=1
```

* **Page Not Found Class** - *Display a Page Not Found Page if class or method was not found. Set value to 0 to disable*
```
PAGE_NOT_FOUND=1
```
* **CSRF Token Class** - *Set value to 0 to disable*
```
CSRF=1
```




## Vendor Styles and Scripts

Include a global styles and scripts on the application. 

* **Include Global CSS** - *Go to `config/vendors/stylesheet.conf`*
```
/**Set your App's Global Stylesheet/Libraries
/**Include every file after a new line
/**Save the stylesheet inside public/vendor/css/
/**Don't include the file extension

/**Start
bootstrap.min
fontawesome.min
fontawesome-all
/**End
```

* **Include Global Scripts** - *Go to `config/vendors/scripts.conf`*
```
/**Set your App's Global JavaScript/Libraries
/**Include every file after a new line
/**Save the scripts inside public/vendor/js/
/**Don't include the file extension

/**Start
jquery
bootstrap.min
fontawesome.min
/**End
```
