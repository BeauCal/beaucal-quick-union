# BeaucalQuickUnion

[![Build Status](https://travis-ci.org/BeauCal/beaucal-quick-union.svg?branch=master)](https://travis-ci.org/BeauCal/beaucal-quick-union)

**Now with 100% code coverage.**

Provides union-find functionality, whether directed or shortest.

### Installation
1. In `application.config.php`, add as follows:

```PHP
'modules' => [..., 'BeaucalQuickUnion', ...];
```

2. Import into your database `data/beaucal_union.sql`:
```SQL
CREATE TABLE IF NOT EXISTS `beaucal_union` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `item` varchar(255) NOT NULL UNIQUE KEY,
  `set` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `beaucal_union` ADD INDEX(`set`);
ALTER TABLE `beaucal_union` ADD FOREIGN KEY (`set`)
  REFERENCES `beaucal_union`(`item`) ON DELETE RESTRICT ON UPDATE CASCADE;
```


### To Use

```PHP
// in controller
$union = $this->getServiceLocator()->get('BeaucalQuickUnion');

$union->union('AAA', 'BBB');
echo $union->query('AAA'); // 'AAA' or 'BBB', random/set behaviour
echo $union->query('AAA') == $union->query('BBB'); // TRUE
echo $union->query('BBB') == $union->query('ZZZ'); // FALSE

$union->union(new Order\Directed('AAA', 'ZZZ'));
echo $union->query('AAA'); // 'ZZZ'
echo $union->query('BBB') == $union->query('ZZZ'); // TRUE

// change from random/set behaviour to known/directed
// or change via beaucalquickunion.global.php: option order_class
$union->getOptions()->setOrderClass('BeaucalQuickUnion\Order\Directed');
$union->union('PPP', 'QQQ');
echo $union->query('PPP'); // 'QQQ', no longer random
```


### Separate Structures

If you need a separate union space, simply preface each item with a namespace, e.g. `union('JobID::123', 'JobID::456')`.

Or for complete separation, configure another union + adapter instance and change its database table.
```PHP
$adapterOptions = $serviceLocator->get('BeaucalQuickUnion\Options\DbAdapter');
$adapterOptions->setDbTable('beaucal_union_separate');
$gateway = new TableGateway(
$adapterOptions->getDbTable(), $serviceLocator->get($adapterOptions->getDbAdapterClass())
);
$adapter = new DbAdapter($gateway, $adapterOptions);

$union = new Union($adapter, $unionOptions);
```

### Memory Adapter

If you just need a short-lived instance for a single request, use the Memory adapter, as follows:
```PHP
// in beaucalquickunion.global.php
$union = [
    'adapter_class' => 'BeaucalQuickUnion\Adapter\Memory',
// ...
]
// in controller
$union = $this->getServiceLocator()->get('BeaucalQuickUnion');


// alternatively, a shortcut factory that doesn't require config
$throttle = $this->getServiceLocator()->get('BeaucalQuickUnion_Memory');

```
