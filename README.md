# BeaucalQuickUnion

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

$union->union(new Strategy\Directed('AAA', 'BBB'));
echo $union->query('AAA'); // 'BBB'
echo $union->query('AAA') == $union->query('BBB'); // TRUE
echo $union->query('BBB') == $union->query('ZZZ'); // FALSE

$union->union(new Strategy\Random('AAA', 'ZZZ'));
echo $union->query('BBB') == $union->query('ZZZ'); // TRUE
```


If you need separate union structures, you can configure a separate
union + adapter instance and change its database table.
```PHP
$adapterOptions = $serviceLocator->get('BeaucalQuickUnion\Options\DbAdapter');
adapterOptions->setDbTable('beaucal_union_separate');
$gateway = new TableGateway(
$adapterOptions->getDbTable(), $serviceLocator->get($adapterOptions->getDbAdapterClass())
);
$adapter = new DbAdapter($gateway, $adapterOptions);

$union = new Union($adapter, $unionOptions);
```
