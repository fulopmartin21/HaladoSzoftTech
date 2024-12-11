
USE CofeBeanWebPage;

CREATE TABLE IF NOT EXISTS Coffees (
    ID integer NOT NULL PRIMARY KEY AUTO_INCREMENT,        
    Manufacturer varchar(255) NOT NULL,                     
    CoffeeName varchar(255) NOT NULL,                       
    Region varchar(255) NOT NULL,                           
    Roasting varchar(255) NOT NULL,                         
    FlavorNotes varchar(255) NOT NULL,                      
    Counter integer NOT NULL DEFAULT 0                      
);

INSERT INTO Coffees (Manufacturer, CoffeeName, Region, Roasting, FlavorNotes, Counter)
VALUES ("Tchibo", "Silk Road", "Nigeria", "Dark", "Intense,Fruity", 0);