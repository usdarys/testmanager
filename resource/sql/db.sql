
-- Structure
CREATE TABLE user_account (
    id serial PRIMARY KEY,
    login TEXT NOT NULL UNIQUE,
    first_name TEXT NOT NULL,
    last_name TEXT NOT NULL,
    password TEXT NOT NULL
);

CREATE TABLE role (
    id serial PRIMARY KEY,
    name TEXT NOT NULL UNIQUE
);

CREATE TABLE user_account_role (
    user_account_id INT,
    role_id INT,
    FOREIGN KEY (user_account_id) REFERENCES user_account(id),
    FOREIGN KEY (role_id) REFERENCES role(id),
    PRIMARY KEY (user_account_id, role_id)
);

CREATE TABLE test_case (
    id serial PRIMARY KEY,
    name TEXT,
    preconditions TEXT,
    steps TEXT,
    expected_result TEXT
);

CREATE TABLE test_run (
    id serial PRIMARY KEY,
    name TEXT,
    description TEXT,
    date_created TIMESTAMP
);

CREATE TABLE test_result (
    test_run_id INT,
    test_case_id INT,
    user_account_id INT,
    date_run TIMESTAMP,
    status TEXT,
    comment TEXT,
    FOREIGN KEY (test_run_id) REFERENCES test_run(id),
    FOREIGN KEY (test_case_id) REFERENCES test_case(id),
    FOREIGN KEY (user_account_id) REFERENCES user_account(id),
    PRIMARY KEY (test_run_id, test_case_id)
);


-- Data
INSERT INTO user_account(login, first_name, last_name, password)
VALUES ('superadmin', 'SuperAdmin', 'SuperAdmin', 'test1');

INSERT INTO role(name)
VALUES ('Admin'),
('Test Leader')
('Tester');

INSERT INTO user_account_role(user_account_id, role_id)
SELECT (SELECT id FROM user_account WHERE login = 'superadmin'), id
FROM role;