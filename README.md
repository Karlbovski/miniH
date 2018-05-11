# miniH Framework 
#### v1.0.3.1
## Light-weight MVC and VC frameworks in PHP

>Temporary Readme file. Full documentation is under construction...

### Full Version ( MVC )

- Full MVC
- PHP ^7.0
- Complete Auth System
- Twig templates
- PHPMailer

### Lite Version ( VC )

- VC only
- PHP ^7.0
- Twig templates
- PHPMailer

## Setup and Dev-Environment

- Download [Composer](https://getcomposer.org/download/)
- Install Composer ( locally or globally ) - [Get Started with Composer](https://getcomposer.org/doc/00-intro.md)
- Note that each version has its own `composer.json` and `composer.lock` files.
- Enter the correct folder and **run** `composer.phar install` to install the relative dependencies packages.

Since the project includes the `composer.lock` files, consider to run `composer.phar update` to update to the latest packages's versions.

# The MVC Pattern
The model-view-controller design pattern separates the code that does stuff ( application logics ) from the code that shows stuff ( the presentation ).

## Controllers
- Controllers are what the user **interacts** with.
- They receive requests from the user, decide what to do, and send a response back.
- It's the only component that interacts with the models.
## Models
- Models are where an application's **data** are stored.
- Responsible for storing and receiving data ( usually a database ).
- Know nothing about the user interface ( the html ).
## Views
- Views are what the user sees on screen ( the HTML ).
- They present data to the user.
- Know nothing about the models ( no business logics, databases access, etc..).

# Folders structure
> _We are not mapping files and folders to URLs directly._

**App** folder contains application code:
- Controllers
- Models
- Views

**Core** folder contains the framework code.

**public** folder contains publicly accessible files.

**vendor** folder contains 3rd-party library code.


# The front controller
The entry point to the framework.

Every URL will point to the same file ( index.php ). Instead of mapping URLs to individual scripts, URLs point to "actions" inside controllers.

The request is the query string.
The query string is the part of the URL that comes after the first question mark.
```
myWebsite.com/index.php?/home
```
We can use this to decide where to route the request ( aKa to which Controller )
```
myWebsite.com/index.php?/show_post/123
myWebsite.com/index.php?/posts?page=2
```
The entire query string will be the **request URL** or **route**.

The framework use **prettyURLs** so that we can remove the default page **index.php** and the question mark. This is done through changing the web-server configuration using **.htaccess** files.

# The Router class ( Core )
The router class takes the **request URL** and decides what to do with it.

### **require Vs include** 
    Is a good habit to have classes in separate files.
    To use those classes we have to load the files into the script where we want to use them. If the file is not found:
    require will l stop the script and produce an error
    include will just carry on.

## Routing Table
The Router contains a table that matches incoming **routes** to **controllers** and **actions**.
```
.::TODO::.
```

# Controllers
Controllers are classes that contain **methods** that are the **actions**.

## Create objects and run methods dynamically in PHP
Create an object :
```PHP
$post = new Post();
```
or we can create an object using a variable like this:
```PHP
$class_name = "Post";
$post = new $class_name();
```
We can do the same with methods
```PHP
$post = new Post();
$post->save();
```
or based on a variable
```PHP
$method = "save";
$post->$method();
```
To call a method and pass parameters to it we use the function called **call_user_func_array()**.
Let's say we have a save method in our class that accept 2 parameters.
```PHP
class Post{
    public function save($arg1, $arg2){
        ...
    }
}
```
We can pass parameters by passing 2 arrays, the first array contains the object and the name of the method, the second array contains the parameters.
```PHP
$post = new Post();
call_user_func_array([$post,"save"],[123,"abc"]);
```

### Error Handling
Check if a class exists before we create an object.
```PHP
$class_name = "Post";
if(class_exists($class_name)){
    $post = new $class_name();
}
```
We can also check if a **method exists** and if is **public**, before we call it.
```PHP
$post = new Post();
$method = "save";
if(is_callable([$post, $method])){
    $post->$method();
}
```
## Dispatching
**Routing** is like _asking for directions_, **dispatching** is like _following those directions_.
The dispatching step in this framework is going to create a **controller object** and run its **action methods**.

### Get the Controller class an the method from the route
- Router will provide a controller _parameter_, taken from the URL.
- Words are separated in the URL by _hyphens_.
- Controller classes are named using **StudlyCaps** method, therefore their names are capitalized.
- Action methods are named using **camelCase** syntax.


### Example | The routing block inside the index.php
<b>*Index.php*</b> <small>is our Front controller / Landing page </small>

```PHP
...
/**
 * Routing
*/
$router = new Core\Router();

//Add routes to the routing table
$router->add('',['controller'=>'Home', 'action'=>'index']);
$router->add('{controller}/{action}'); //Base scheme
$router->add('login',['controller'=>'Login', 'action' => 'new']);
$router->add('logout',['controller'=>'Login', 'action' => 'destroy']);
$router->add('password/reset/{token:[\da-f]+}',['controller' => 'Password', 'action' => 'reset']);
$router->add('signup/activate/{token:[\da-f]+}',['controller' => 'Signup', 'action' => 'activate']);
$router->add('{controller}/{id:\d+}/{action}');
$router->add('admin/{controller}/{action}', ['namespace' => 'Admin']);

// Dispatch Route
$router->dispatch($_SERVER['QUERY_STRING']);
...
```




