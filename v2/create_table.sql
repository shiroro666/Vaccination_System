DROP TABLE IF EXISTS offer;
DROP TABLE IF EXISTS appointment;
DROP TABLE IF EXISTS preference;
DROP TABLE IF EXISTS provider;
DROP TABLE IF EXISTS patient;
DROP TABLE IF EXISTS grouptype;


CREATE TABLE grouptype(
  prionum INTEGER primary key,
  startdate DATE,
  description VARCHAR(50)
);

CREATE TABLE patient(
	patientId VARCHAR(20) primary key,
	name VARCHAR(20),
	SSN VARCHAR(9),
	birthday DATE,
	address VARCHAR(50),
	latitude FLOAT,
	longitude FLOAT,
	phone VARCHAR(11),
	email varchar(30),
	prionum INTEGER,
	password VARCHAR(20),
	FOREIGN KEY (prionum) REFERENCES grouptype(prionum)
);

CREATE TABLE provider(
	providerId VARCHAR(20) primary key,
	providertype VARCHAR(10),
	name VARCHAR(50),
	phone VARCHAR(11),
	address VARCHAR(50),
	latitude FLOAT,
	longitude FLOAT,
	password VARCHAR(20)
);

CREATE TABLE preference(
	patientId VARCHAR(20),
	weekday INTEGER,
	slot TIME,
	distance INTEGER,
	PRIMARY KEY (PatientId, weekday, slot),
	FOREIGN KEY (PatientId) REFERENCES patient(PatientId)
);

CREATE TABLE appointment(
	aid INTEGER NOT NULL AUTO_INCREMENT,
	adate DATE,
	atime TIME,
	providerId VARCHAR(20),
	PRIMARY KEY (aid),
	FOREIGN KEY (providerId) REFERENCES provider(providerId)
);

CREATE TABLE offer(
	aid INTEGER,
	patientId VARCHAR(20),
	offerdate DATE,
	deadline DATE,
	replydate DATE,
	status VARCHAR(10),
	PRIMARY KEY (aid, patientId),
	FOREIGN KEY (aid) REFERENCES appointment(aid),
	FOREIGN KEY (patientId) REFERENCES patient(patientId)
);
