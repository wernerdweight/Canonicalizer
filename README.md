Canonicalizer
==

Simple PHP string canonicalizer

[![Build Status](https://travis-ci.org/wernerdweight/Canonicalizer.svg?branch=master)](https://travis-ci.org/wernerdweight/Canonicalizer)

Instalation
--

1) Download using composer

```bash
composer require wernerdweight/canonicalizer
```

2) Use in your project

```php
use WernerDweight\Canonicalizer\Canonicalizer;
 
$string = 'This is an interesteing string with some strange cháračtěřš in it. Хорошо?'
$maxLength = 255;   // maximal resulting canonical length (excessive chars will be trimmed)

$canonicalizer = new Canonicalizer($maxLength);
$canonical = $canonicalizer->canonicalize($string);

echo $canonical;    // this-is-an-interesting-string-with-some-strange-characters-in-it-choroso
```

API
--

TODO:
