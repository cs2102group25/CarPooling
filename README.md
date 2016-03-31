# CarPooling

Currently, the user and password for PostgresSQL are both assumed to be 'postgres'.

![](erdplus-diagram-3.png)

The relations are:
```
Car(car_plate VARCHAR(10) PRIMARY KEY, model VARCHAR(32));

User(email VARCHAR(32) PRIMARY KEY, username VARCHAR(32), password VARCHAR(32), created_date DATE, admin BOOLEAN);

Make_Transaction(email VARCHAR(32), time DATETIME, amount DECIMAL(10,2));

Provides_Trip(seat_no INTEGER, price DECIMAL(10, 2), start_time DATETIME, end_time DATETIME, start_loc VARCHAR(32), end_loc VARCHAR(32), posted BOOLEAN, car_plate VARCHAR(10), PRIMARY KEY(seat_no, car_plate), FOREIGN KEY(car_plate) REFERENCES Car(car_plate));

Booking(seat_no INTEGER, email VARCHAR(32), time DATETIME, cancelled BOOLEAN, PRIMARY KEY(seat_no, email), FOREIGN KEY (seat_no) REFERENCES Provides_Trip(seat_no), FOREIGN KEY(email) REFERENCES User(email), FOREIGN KEY(time) REFERENCES Make_Transaction(time));

Ownership(email VARCHAR(32), car_plate VARCHAR(10), expiration DATE, PRIMARY KEY(email, car_plate), FOREIGN KEY(email) REFERENCES User(email), FOREIGN KEY(car_plate REFERENCES Car(car_plate));
```

SQL DDL:
```
CREATE TABLE Car(
  car_plate VARCHAR(10) PRIMARY KEY,
  model VARCHAR(32)
);

CREATE TABLE "user"(
  email VARCHAR(32) PRIMARY KEY,
  username VARCHAR(32),
  password VARCHAR(32),
  created_date DATE,
  admin BOOLEAN
);

CREATE TABLE Make_Transaction(
  email VARCHAR(32),
  time TIMESTAMP,
  amount DECIMAL(10,2),
  PRIMARY KEY(email, time),
  FOREIGN KEY(email) REFERENCES "user"(email)
);

CREATE TABLE Provides_Trip(
  seat_no INTEGER,
  price DECIMAL(10, 2),
  start_time TIMESTAMP,
  end_time TIMESTAMP,
  start_loc VARCHAR(32),
  end_loc VARCHAR(32),
  posted BOOLEAN,
  car_plate VARCHAR(10),
  PRIMARY KEY(seat_no, car_plate),
  FOREIGN KEY(car_plate) REFERENCES Car(car_plate)
);

CREATE TABLE Booking(
  seat_no INTEGER,
  car_plate VARCHAR(10),
  email VARCHAR(32),
  time TIMESTAMP,
  cancelled BOOLEAN,
  PRIMARY KEY(seat_no, email, time),
  FOREIGN KEY (seat_no, car_plate) REFERENCES Provides_Trip(seat_no, car_plate),
  FOREIGN KEY(email, time) REFERENCES Make_Transaction(email, time)
);

CREATE TABLE Ownership(
  email VARCHAR(32),
  car_plate VARCHAR(10),
  expiration DATE,
  PRIMARY KEY(email, car_plate),
  FOREIGN KEY(email) REFERENCES "user"(email),
  FOREIGN KEY(car_plate) REFERENCES Car(car_plate)
);
```
