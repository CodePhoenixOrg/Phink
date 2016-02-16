
use information_schema;
show tables; 

select * from tables where table_schema = 'phoenix';
SELECT 
    `table_schema`, `table_name`, `column_name`
FROM
    `columns`
WHERE
    table_schema = 'phoenix';