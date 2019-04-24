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


## CONTROLLERS AND PAGES
As what we know about Controllers, they are the one actually responsible on what must be displayed inside a View and what must be requested inside the Model. In CHKN Framework , Controllers are the most important file on your system. Controller is the place where you have to declare what will be your template to be use, the page that will display inside your template, the page title, the page stylesheet and scripts and the place where you will becreating a request in the Model.

### Creating a Controller
* You have to create a PHP file inside the folder Http/Controller/. The File name must be the name of the Controller you want, followed by the word Controller. Example: homeController.php
* On the CHKN Console. Simply enter `chkn create controller ___name of controller___`.

Every Controller will contain the following codes.
```php
<?php
use App\App\Request;
class sampleController extends Controller{
	public function sample_page(Request $r){
		//Call index template
		$this->template('index');
		//set default title
		$this->title('CHKN Framework');
		//set css
		$this->css(array(
		));
		//set js
		$this->js(array(
		));

		$this->body('homepage/index');
		$this->show();
	}
}


```

* **$this->template('index')** - *Calling the the page template. 'index' is pertaining the the template file located at view/template/index.tpl*
* **$this->title();** - *Set the title of the page.*
* **$this->css(array());** - *Including stylesheets inside the page. CSS Files are stored at public/css. In addition, CSS Files are called without the .css file extension*
* **$this->js(array());** - *Including scripts inside the page. Script Files are stored at public/js. In addition, Script Files are called without the .js file extension*
* **$this->body('homepage/index')** - *Including the page content inside the template. This method will only going to fetch .cvf files*
* **$this->show()** - *Compile the settings above and display the page.*
