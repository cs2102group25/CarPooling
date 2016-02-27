# CarPooling

Currently, the user and password for PostgresSQL are both assumed to be 'postgres'.

These SQL commands have been executed (to create the schema):
```
CREATE TABLE trip(src VARCHAR(32), dest VARCHAR(32), startTime TIME, estDuration INTEGER,
noOfSeats INTEGER, vehicle VARCHAR(32), cost DECIMAL(6,2), notes VARCHAR(128));
```
