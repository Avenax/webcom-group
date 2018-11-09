CREATE TABLE users (
  id SERIAL PRIMARY KEY,
  login CHAR(50) NOT NULL,
  password CHAR(128) NOT NULL
);

CREATE TABLE quest_book( 
  id SERIAL PRIMARY KEY, 
  id_user INTEGER,
  message TEXT, 
  rating INTEGER,
  time_message INTEGER,
  path_foto char(50)
);
