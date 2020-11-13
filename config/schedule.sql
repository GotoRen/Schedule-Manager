DROP TABLE IF EXISTS schedule;

CREATE TABLE schedule (
    id       int(11)       NOT NULL AUTO_INCREMENT,
    user_id  int(11)       NOT NULL,
    begin    datetime      NOT NULL,
    end      datetime      NOT NULL,
    place    varchar(255)  NOT NULL,
    content  text          NOT NULL,
    PRIMARY KEY (id)
);