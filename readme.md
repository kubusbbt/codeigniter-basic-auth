# Codeigniter basic auth #

Copy all filed to Your project directory

Add this to `app/Config/Autoload.php`

```php
public $psr4 = [
	APP_NAMESPACE => APPPATH, // For custom app namespace
	'Config'      => APPPATH . 'Config',
	'Libraries' => APPPATH. 'Libraries',
];
```

Add Users to `app/Config/Auth.php`

Extends Auth class on `JwtAuth` or `SessionAuth`;


In controller use
```php 
protected $auth_protected = ['methods_to_protect'];
```
