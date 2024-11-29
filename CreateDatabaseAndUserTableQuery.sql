CREATE DATABASE IF NOT EXISTS CofeBeanWebPage;
USE CofeBeanWebPage;
CREATE TABLE IF NOT EXISTS Users (
ID integer  PRIMARY KEY NOT NULL auto_increment,
Name varchar(255) NOT NULL,
BirthPlace varchar(255),
DateOfBirth varchar(255),
Password varchar(255) NOT NULL,
Username varchar(255) NOT NULL
);