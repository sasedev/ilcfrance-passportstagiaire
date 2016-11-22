INSERT INTO "ilcfrance_locales" ("id", "status", "direction", "created_at", "updated_at") VALUES
('fr', 1, 'rtl', '2016-11-18 08:00:00+01', '2016-11-18 08:00:00+01'),
('en', 1, 'rtl', '2016-11-18 08:00:00+01', '2016-11-18 08:00:00+01');

INSERT INTO "ilcfrance_roles" ("id", "description", "created_at", "updated_at") VALUES
('ROLE_USER', 'ROLE_USER', '2016-11-18 08:00:00+01', '2016-11-18 08:00:00+01'),
('ROLE_ADMIN', 'ROLE_ADMIN', '2016-11-18 08:00:00+01', '2016-11-18 08:00:00+01'),
('ROLE_TEACHER', 'ROLE_TEACHER', '2016-11-18 08:00:00+01', '2016-11-18 08:00:00+01'),
('ROLE_SUPERADMIN', 'ROLE_SUPERADMIN', '2016-11-18 08:00:00+01', '2016-11-18 08:00:00+01');

INSERT INTO "ilcfrance_role_parents" ("child_id", "parent_id") VALUES
('ROLE_ADMIN', 'ROLE_USER'),
('ROLE_TEACHER', 'ROLE_USER'),
('ROLE_SUPERADMIN', 'ROLE_ADMIN'),
('ROLE_SUPERADMIN', 'ROLE_TEACHER');

INSERT INTO "ilcfrance_users" ("id", "email", "clearpassword", "passwd", "salt", "lockout", "logins", "lastname", "firstname", "sexe", "locale_id", "created_at", "updated_at") VALUES
('sasedev', 'seif.salah@gmail.com', 'alphatester', 'nRlxfjNPJNwmLJ50/TmoZLBNQtSkgJfALg9KvVy3sSyd27gI46PLjw==', 'd373ec2ae8890256bb2471580087d373', 1, 0, 'Salah', 'Abdelkader Seifeddine', 3, 'fr', '2016-11-18 08:00:00+01', '2016-11-18 08:00:00+01');


INSERT INTO "ilcfrance_users_roles" ("user_id", "role_id") VALUES
('sasedev', 'ROLE_SUPERADMIN');
