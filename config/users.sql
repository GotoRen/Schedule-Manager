DROP TABLE IF EXISTS users;

CREATE TABLE users (
    user_id    int(11)       NOT NULL AUTO_INCREMENT,
    user_name  varchar(255)  NOT NULL,
    user_pass  varchar(255)  NOT NULL,
    PRIMARY KEY (user_id)
);