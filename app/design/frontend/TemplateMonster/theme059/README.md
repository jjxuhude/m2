## Magento 2 Frontend Framework

### Установка через Composer

В файл composer.json в корне Magento добавляете:

В раздел "repositories":
{
    "type": "vcs",
    "url": "http://products.git.devoffice.com/magento/magento2_framework.git"
}

В раздел "require":
"templatemonster/theme-frontend-framework": "dev-master"

Где :
* "dev-master" - название сетки с префиксом dev-
* "templatemonster/framework" - название пакета в файле composer.json в корне темы