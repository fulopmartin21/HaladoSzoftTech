USE CofeBeanWebPage;
CREATE TABLE IF NOT EXISTS Coffees(
ID integer NOT NULL PRIMARY KEY,
Manufacturer varchar(255) NOT NULL,
CoffeeName varchar(255) NOT NULL,
Region varchar(255) NOT NULL,
Roasting varchar(255) NOT NULL,
FlavorNotes varchar(255) NOT NULL
);
INSERT INTO Coffees(ID,Manufacturer,CoffeeName,Region,Roasting,FlavorNotes)
VALUES (1,"Tchibo","Silk Road","Nigeria","Dark","Intense,Fruity");