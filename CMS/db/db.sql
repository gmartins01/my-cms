create table if not exists me
(
    id                  Int auto_increment
        primary key,
    name                varchar(25) not null,
    image               varchar(500) not null,
    profession          varchar(75) not null
);

create table if not exists socials
(
    id          Int auto_increment primary key,
    name        varchar(35)         not null,
    url         varchar(1000)       not null,
    icon        varchar(50)         not null,
    status      int default 1       not null,
    id_me       Int,
    constraint fk_socials_me
        foreign key (id_me) references me (id)
);

create table if not exists about
(
    id          Int auto_increment primary key,
    description varchar(1000)     not null,
    status      int default 1       not null,
    id_me       Int,
    constraint fk_about_me
        foreign key (id_me) references me (id)
);

create table if not exists education
(
    id              Int auto_increment primary key,
    image           varchar(500)     not null,
    institution     varchar(100)      not null,
    year_start      varchar(50)       not null,
    year_end        varchar(50)       not null,
    course          varchar(100)      not null,
    status          int default 1     not null,
    id_me           Int               not null,
    constraint fk_education_me
        foreign key (id_me) references me (id)
);

create table if not exists languages
(
    id              Int auto_increment primary key,
    description     varchar(1000)      not null,
    status          int default 1      not null,
    id_me       Int                    not null,
    constraint fk_languages_me
        foreign key (id_me) references me (id)
);

create table if not exists skill_type
(
    id Int auto_increment
        primary key,
    name varchar(50) not null
);

create table if not exists skills
(
    id              Int auto_increment primary key,
    name       varchar(50)        not null,
    image      varchar(500)       not null,
    status     int default 1     not null,
    id_me       Int               not null,
    id_skill_type   Int           not null,
    constraint fk_skills_me
        foreign key (id_me) references me (id),
     constraint fk_skills_skill_type
        foreign key (id_skill_type) references skill_type (id)
);

create table if not exists roles
(
    id         Int auto_increment
        primary key,
    name       varchar(25)          not null
);

create table if not exists users
(
    id         Int auto_increment primary key,
    name       varchar(25)          not null,
    username   varchar(25)          not null,
    password   varchar(100)         not null,
    id_me      Int                  not null,
    id_role    Int                   not null,
    constraint unique_users_username
        unique (username),
    constraint fk_users_me
        foreign key (id_me) references me (id),
    constraint fk_users_roles
        foreign key (id_role) references roles (id)
);

create table if not exists contact_requests
(
    id         Int auto_increment primary key,
    email       varchar(40)         not null,
    subject     varchar(100)        not null,
    message     varchar(1000)       not null,
    is_read     int default 0       not null,
    id_me       Int                 not null,
    constraint fk_contacts_me
        foreign key (id_me) references me (id)
);

INSERT INTO me (name, image, profession) VALUES ("Gonçalo Martins", "../../uploaded_files/me.jpg", "Computer Science Student");
INSERT INTO roles (name) VALUES ("Admin");
INSERT INTO roles (name) VALUES ("Manager");
INSERT INTO roles (name) VALUES ("Unauthorized");
INSERT INTO skill_type (name) VALUES ("Programming Languages & Tools");
INSERT INTO skill_type (name) VALUES ("Frameworks");
INSERT INTO users (name,username,password,id_me, id_role) VALUES ("admin","admin1","$2y$10$vF.IGWCpzvCip9/rSFy18.8cxKYWa63wv1rrymNhPOHcrQMTHno4W",1,1);
