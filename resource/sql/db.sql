
-- Structure
CREATE TABLE user_account (
    id serial PRIMARY KEY,
    login VARCHAR(255) NOT NULL UNIQUE,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE role (
    id serial PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE
);

CREATE TABLE user_account_role (
    user_account_id INT,
    role_id INT,
    FOREIGN KEY (user_account_id) REFERENCES user_account(id),
    FOREIGN KEY (role_id) REFERENCES role(id),
    PRIMARY KEY (user_account_id, role_id)
);


-- Data
INSERT INTO user_account(login, first_name, last_name, password)
VALUES ('superadmin', 'SuperAdmin', 'SuperAdmin', 'test1');

INSERT INTO role(name)
VALUES ('Admin'),
('Tester');

INSERT INTO user_account_role(user_account_id, role_id)
SELECT (SELECT id FROM user_account WHERE login = 'superadmin'), id
FROM role;