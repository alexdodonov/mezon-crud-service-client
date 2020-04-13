# CRUD service client [![Build Status](https://travis-ci.com/alexdodonov/mezon-crud-service-client.svg?branch=master)](https://travis-ci.com/alexdodonov/mezon-mezon-crud-service-client) [![codecov](https://codecov.io/gh/alexdodonov/mezon-mezon-crud-service-client/branch/master/graph/badge.svg)](https://codecov.io/gh/alexdodonov/mezon-mezon-crud-service-client)
## Intro

Mezon provides simple client for CRUD service.

## Installation

Just print in console

```
composer require mezon/crud-service-client
```

And that's all )

## Reference

This class provides CRUD-like all-purpose methods:

```PHP
create($data)
update(int $id, array $data, int $crossDomain = 0)
newRecordsSince($date)
recordsCount()
lastRecords($count, $filter)
delete(int $id, int $crossDomain = 0): string
recordsCountByField(string $field, $filter = false): array
deleteFiltered($crossDomain = 0, $filter = false)
getRecordsBy($filter, $crossDomain = 0)
getById($id, $crossDomain = 0)
getByIdsArray($ids, $crossDomain = 0)
getList(int $from = 0, int $limit = 1000000000, $crossDomain = 0, $filter = false, $order = false): array

// and some utilities
static function instance(string $service, string $token): \Mezon\CrudService\CrudServiceClient
getFields(): array
```
