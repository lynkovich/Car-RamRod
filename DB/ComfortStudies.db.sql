BEGIN TRANSACTION;
CREATE TABLE IF NOT EXISTS "Menu_Cater" (
	"Item_ID"	INTEGER UNIQUE,
	"Name"	TEXT,
	"Price"	INTEGER,
	"Order_ID"	INTEGER,
	"Room_ID"	INTEGER,
	PRIMARY KEY("Item_ID")
);
CREATE TABLE IF NOT EXISTS "Orders" (
	"Order_ID"	INTEGER UNIQUE,
	"Total"	INTEGER,
	"Del_Room"	INTEGER,
	PRIMARY KEY("Order_ID")
);
CREATE TABLE IF NOT EXISTS "Menu_pickup" (
	"Item_ID"	INTEGER UNIQUE,
	"Name"	TEXT,
	"Price"	INTEGER,
	"Category"	TEXT,
	"Order_ID"	INTEGER UNIQUE,
	FOREIGN KEY("Order_ID") REFERENCES "Orders"("Order_ID"),
	PRIMARY KEY("Item_ID")
);
CREATE TABLE IF NOT EXISTS "Tutor" (
	"StaffID"	INTEGER UNIQUE,
	"Name"	TEXT,
	"Subject"	TEXT,
	"Email"	TEXT,
	"Res_ID"	INTEGER UNIQUE,
	PRIMARY KEY("StaffID"),
	FOREIGN KEY("Res_ID") REFERENCES "Room_Schedule"("Reserv_ID")
);
CREATE TABLE IF NOT EXISTS "Room_Schedule" (
	"Room_ID"	INTEGER,
	"Res_Date"	INTEGER,
	"StudentID"	INTEGER,
	"Res_Time"	INTEGER,
	"Reserv_ID"	INTEGER UNIQUE,
	FOREIGN KEY("Room_ID") REFERENCES "Study_Room"("Room_ID")
);
CREATE TABLE IF NOT EXISTS "Study_Room" (
	"Room_ID"	INTEGER,
	"Building"	INTEGER
);
CREATE TABLE IF NOT EXISTS "Student" (
	"StudentID"	INTEGER UNIQUE,
	"Username"	TEXT,
	"Password"	TEXT,
	"Order_ID"	INTEGER UNIQUE,
	PRIMARY KEY("StudentID")
);
COMMIT;
