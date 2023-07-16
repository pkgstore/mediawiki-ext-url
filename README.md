# Information

Добавление различных типов ссылок в статью.

## Install

1. Загрузите папки и файлы в директорию `extensions/MW_EXT_URL`.
2. В самый низ файла `LocalSettings.php` добавьте строку:

```php
wfLoadExtension( 'MW_EXT_URL' );
```

## Syntax

```html
{{#url: [TYPE]|[CONTENT]|[TITLE]}}
```
