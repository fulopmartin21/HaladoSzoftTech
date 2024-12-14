CREATE TABLE IF NOT EXISTS Opinions (
    ID integer NOT NULL PRIMARY KEY AUTO_INCREMENT,        
    Opinion varchar(255) NOT NULL,                           
    UserID INTEGER NOT NULL,                                 
    CoffeID INTEGER NOT NULL,                                
    CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,           
    FOREIGN KEY (UserID) REFERENCES Users(ID),              
    FOREIGN KEY (CoffeID) REFERENCES Coffees(ID)          
);
