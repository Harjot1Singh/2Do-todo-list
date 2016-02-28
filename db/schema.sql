CREATE TABLE users (
	id integer PRIMARY KEY,
	name text,
	email text,
	password text,
	phone text,
	verifyCode text,
	background text
);

CREATE TABLE lists (
	id integer PRIMARY KEY,
	name text
);

CREATE TABLE accounts (
	id integer PRIMARY KEY,
	userID integer,
	service text,
	serviceID text,
	token text
);

CREATE TABLE items (
	id integer PRIMARY KEY,
	listID integer,
	name text,
	due datetime,
	urgent integer,
	longitude real,
	latitude real,
	completed integer
);

CREATE TABLE userlists (
	userID integer,
	listID integer
);

CREATE TABLE files (
	id integer PRIMARY KEY,
	filename text,
	itemID integer
);