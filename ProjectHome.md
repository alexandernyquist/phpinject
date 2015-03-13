This is a simple ioc framework for php. It's using a fluent interface for binding, for example:

```
$ioc = new phpinject;
$ioc->bind('IDatabaseProvider')->to('MysqlProvider');
$ioc->bind('IMessagePrinter')->to('HtmlMessagePrinter');
```