{
    "name": "anouarcharif/CakePHP3-Media",
    "description": "Media plugin for CakePHP",
    "type": "cakephp-plugin",
    "require": {
        "php": ">=5.4",
        "cakephp/cakephp": "3.0.x-dev"
    },
    "require-dev": {
        "phpunit/phpunit": "*"
    },
    "autoload": {
        "psr-4": {
            "Media\\": "src"
        }
    },
    "autoload-dev": {
        "psr-4": {
            "Media\\Test\\": "tests",
            "Cake\\Test\\": "./vendor/cakephp/cakephp/tests"
        }
    }
}