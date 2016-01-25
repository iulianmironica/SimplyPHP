# SimplyPHP Framework
### A simple and lightweight PHP framework. MVC, Object Oriented and really easy to use.

SimplyPHP is a skeleton Framework and provides basic functionality to start an app. I wanted a simple architecture and  
a light framework and also because I wanted to learn more I wrote SimplyPHP.

* MVC
* Fully namespaced
* Uses loading on demand
* It Requires 5.5 or higher
* Dynamic (db) and static routing
* Basic input filtering

## It works with:
* [Twig templating engine](http://twig.sensiolabs.org/documentation)
* [Doctrine DBAL a Database Abstraction Layer over PHP's PDO](http://docs.doctrine-project.org/projects/doctrine-dbal/en/latest/)
* [KLogger A simple and improved version of Klogger, a PSR-3 compliant logging class](https://github.com/iulianmironica/KLogger) **required**

## Let's make it better!
* If you have improvement ideas or time to develop I'm open to feedback and wiling to accept your pull requests.
* [PSR](http://www.php-fig.org/psr/psr-1/) is the direction I want to follow.

## Documentation (detailed documentation is yet to come)

## 1. Download [SimplyPHP Framework](https://github.com/iulianmironica/SimplyPHP)
* Unzip the archive
* run: <code>composer update</code> (or install [Composer](https://getcomposer.org/)) to fetch the dependencies

## 2. Create your <code>.htaccess</code> <small>(Apache Environment)</small>
```apache  
   
    # Set dev environment
    SetEnv ENVIRONMENT development
    RewriteEngine on
    
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    
    # Hide index.php
    RewriteRule ^(.*)$ index.php [L,QSA]
```

## 3. Configure the app <small>(Linux environment)</small>
 Assuming SimplyPHP was extracted in <code>/var/www/simplyphp.local</code> directory,  
 in Ubuntu an <code>/etc/apache2/sites-available/simplyphp.local.conf</code> file might look like this:
 
 ```apache
 
    <VirtualHost *:80>
            ServerName simplyphp.local
            DocumentRoot /var/www/simplyphp.local/public
            ErrorLog /var/log/apache2/error_simplyphp.local.log
    </VirtualHost>
 ```
 
## Go to <code>http://simplyphp.local/</code> in your browser
  That's it!  
  If all was ok you should be able to see SimplyPHP Framework Demo page.

## Fine tuning

### Structure review <small>(and optionally change)</small>
* Application
    - Cache  
    <small>(Cache should be pointed to this folder)</small>
    
    - Controller  
    <small>(Controller classes go inside)</small>
    
    - Library  
    <small>(Library files go here: e.g: Constants, Bridge classes ..)</small>
    
    - Model  
    <small>(Model communicates with the database, each class should represent an entity)</small>
    
    - Settings  
    <small>(Config files/classes)</small>
    
    - View  
    <small>(Layout and View specific files)</small>
* Framework <small>(Core classes)</small>
* public <small>(Your custom app public resources)</small>
    - css 
    - javascript
    - image
    - .htaccess
    - index.php <small>(Change the name/location of the /Application directory)</small> 

### Configure your app <code>/Application/Settings/Config.php</code>

By default SimplyPHP will require TWIG, Doctrine DBAL and KLogger as seen in the <code>composer.json</code>

```json

    {
    "require": {
        "php": ">=5.4",
        "twig/twig": "1.*",
        "doctrine/dbal": ">=2.4.0",
        "iulianmironica/klogger": "dev-master"
      }
    }
```

They can be disabled/enabled in <code>/Application/Settings/Config.php</code>

```php

    // Doctrine settings
    public static $doctrine = [
        'enable' => true,
        // Relative path e.g: '/vendor/doctrine/dbal/lib/'
        'pathToLib' => '/vendor/doctrine/dbal/lib/',
        // Relative path e.g: 'vendor/doctrine/common/lib/Doctrine/Common'
        'pathToCommon' => 'vendor/doctrine/common/lib/Doctrine/Common',
    ];
        
    // KLogger settings
    public static $logger = [
        'level' => 'debug', // emergency, alert, critical, error, warning, notice, info, debug
        'file' => 'logger.txt',
        'timestamp' => 'm-d-Y G:i:s', // leave blank for none
        'format' => '%timestamp% %level% %class% %function% %message%', // output format - leave blank for none
        // 'directory' => APPLICATION_LOG, // path to the log directory - default is APPLICATION_LOG
        /* %timestamp%      - the timestamp declared above
         * %level%          - level declared above
         * %class%          - class name
         * %function%       - method/function name
         * %message%        - the message passed as param
         * %line%, %file%   - point to the parent file that triggered method/function
         */
    ];
    
    // Twig settings
     public static $twig = [
            'enable' => true,
            'cache' => 'Twig', // Cache directory
            'template' => 'Application/View', // Views/templates directory
    ];
    
    
```

 Next set the router and database settings

```php
    
    const DEFAULT_CONTROLLER = 'main';
    const DEFAULT_ACTION = 'index';
    const ROUTER_SCHEME = 'http://';

    public static $routes = [
            // Not found page
            '404' => [
                // Full name space path and class name
                'controller' => '\Application\Controller\MainController',
                'action' => 'notfound'
            ],
            // App pages
            'list' => [
                // slug => [controller, action]
                'how-it-works' => ['Main', 'howitworks'],
            ],
        ];

    // Database
    const DATABASE_TYPE = 'mysql';
    const DATABASE_DRIVER = 'pdo_mysql';
    const DATABASE_CHARSET = 'utf8';
    const DATABASE_HOST = 'localhost';
    const DATABASE_USER = 'root';
    const DATABASE_PASSWORD = '';
    const DATABASE_NAME = '';
```


### Create your first controller <code>MainController.php</code> class inside <code>/Application/Controller</code>

```php  

    <?php
    namespace Application\Controller;
    
    use Application\Model\ProductModel;
    
    # Notice the use of the framework base controller
    use Framework\Controller;
    
    class MainController extends Controller
    {
    
        private static $logger;
    
        public function init()
        {
            // Get the logger from the session
            // $si = Controller::getInstance();
            self::$logger = $this->session->logger;
    
            // A view file can also be set in the constructor
            // $this->view->setLayoutFile('Layout');
        }
    
        public function index()
        {
            $productModel = new ProductModel();
            $categoriesAndProducts = $productModel->getProducts();
            
            $this->view->render(array(
                'content' => 'Main/Main',
                'categoriesAndProducts' => $categoriesAndProducts,
            ));
        }
    }
```

### Create your Model class in <code>/Application/Model</code>

```php

    <?php
    namespace Application\Model;
    
    use Framework\Model;
  
    class ProductModel extends Model {
   
        public function getProducts($value, $showDeleted) {
    
            // MySQL Query example
            $sql = " SELECT *
                     FROM products ";
            
            if ($showDeleted) {
                $sql .= " WHERE actionType != :deleted ";
                $params['deleted'] = Constants::actionTypeDelete;
            }
            
            return DatabaseModel::query($sql, $params, DatabaseModel::FETCH_COLUMN);
        }
    
    }
```

### Create your View <small>(with TWIG)</small>

Make sure [Twig](http://twig.sensiolabs.org/documentation) is enabled in 
<code>/Application/Settings/Config.php</code> first!  
Create a <code>Layout.html.twig</code> file in <code>/Application/View</code>:    

```twig

    <h1> That's a SimplyPHP with TWIG test! </h1>
    
    {% block someBlockName %}
    
        That's my name: {{ name }}
        
    {% endblock %}
```

Then create a <code>twig.html.twig</code> in <code>/Application/View/Main/</code>

```twig
    
    {% extends 'Layout.html.twig' %}
    
    {% block someBlockName %}
    
        Hello from {{ name }}
    
        {{ baseUrl('/ok') }}
        {{ session.ok }}
    
    {% endblock %}
```

Now from your <code>MainController.php</code>

```php
    
     public function twig()
    {
        $this->twig->display('Main/twig.html.twig', ['name' => 'SimplyPHP']);
    }
```

Navigating to <code>http://simplyphp.local/main/twig</code> should display your result.


### Create your View <small>(with default SimplyPHP View)</small>

<code>/Application/View/LayoutView.php</code>:

```php
    
    <html>
    <body>
        <?= $this->render(null, $this->content) ?>
        
        <p>   
            <?= $this->controller ?>
            <?= $this->action ?>
            <?= $this->baseUrl() ?>
            
            <!-- Access a variable from session -->
            <?= $this->session->basket ?>
        </p>
        
    </body>
    </html>
    
```

<code>/Application/View/Main/LayoutView.php</code>:

```php
   
    <p> <?= $this->someVarName ?> </p>
```    

<code>/Application/Controller/MainController.php</code>:

```php

    public function about()
    {
        $this->view->render([
            'content' => 'Main/About',
            'someVarName' => 'SimplyPHP',
        ]);
    }
```

Navigating to <code>http://simplyphp.local/main/about</code> should display your result.

### Input
To access GET OR POST variable <code>?yo=123</code>  
Inside your controller just do:  

```php   

    // 123
    $this->input->yo
    
    // Or statically
    use Framework\Input;
    
    // Fetch GET variable 'yo' statically
    Input::get('yo');
    
    // Fetch POST variable 'yo' statically
    Input::post('yo');
```
 
### Request
Get the HTTP request verb  

```php   

    // Get the HTTP request verb
    $this->router->getRequest(); // 'POST'
```

Get the second segment **index** from url <code>http://simplyphp.local/service/index/yo</code>  

```php  

    // http://simplyphp.local/service/index/yo
    $this->router->getUriSegment(2) // 'index'
```
 
### Session
Get the contents of a session variable
  
```php   

    // Set content of basket variable on session
    $this->session->basket = ['product1', 'product2']
     
    // Get content of basket variable from session
    $this->session->basket; 
```

### Detailed documentation is yet to come...

### SimplyPHP is distributed with [CC BY-SA](http://creativecommons.org/licenses/by-sa/4.0/)
**Share** — copy and redistribute the material in any medium or format  
**Adapt** — remix, transform, and build upon the material

**Attribution** — You must give appropriate credit, provide a link to the license, and indicate if changes were made.  
You may do so in any reasonable manner, but not in any way that suggests the licensor endorses you or your use.    
**ShareAlike** — If you remix, transform, or build upon the material, you must distribute your contributions under the same license as the original.

#### [Markdown Cheatsheet](https://github.com/adam-p/markdown-here/wiki/Markdown-Cheatsheet)
