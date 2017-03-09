CREATE TABLE "ilcfrance_locales" (
	"id"                                                                TEXT     NOT NULL,
	"status"                                                            INT4            NOT NULL DEFAULT 0,
	"direction"                                                         TEXT            NOT NULL DEFAULT 'ltr',
	"created_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"updated_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	CONSTRAINT "pk_ilcfrance_locales" PRIMARY KEY ("id")
);

CREATE TABLE "ilcfrance_roles" (
	"id"                                                              TEXT NOT NULL,
	"description"                                                       TEXT NULL,
	"created_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"updated_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	CONSTRAINT "pk_ilcfrance_roles" PRIMARY KEY ("id")
);

CREATE TABLE "ilcfrance_role_parents" (
	"child_id"                                                             TEXT NOT NULL,
	"parent_id"                                                            TEXT NOT NULL,
	CONSTRAINT "pk_ilcfrance_role_parents" PRIMARY KEY ("child_id", "parent_id"),
	CONSTRAINT "fk_ilcfrance_role_parents_child" FOREIGN KEY ("child_id") REFERENCES "ilcfrance_roles" ("id") ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT "fk_ilcfrance_role_parents_parend" FOREIGN KEY ("parent_id") REFERENCES "ilcfrance_roles" ("id") ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE "ilcfrance_users" (
	"id"                                                                TEXT NOT NULL,
	"email"                                                             TEXT NULL,
	"clearpassword"                                                     TEXT NULL,
	"passwd"                                                            TEXT NULL,
	"salt"                                                              TEXT NULL,
	"recoverycode"                                                      TEXT NULL,
	"recoveryexpiration"                                                TIMESTAMP WITH TIME ZONE NULL,
	"lockout"                                                           INT8 NOT NULL DEFAULT 1,
	"logins"                                                            INT8 NOT NULL DEFAULT 0,
	"lastlogin"                                                         TIMESTAMP WITH TIME ZONE NULL,
	"lastactivity"                                                      TIMESTAMP WITH TIME ZONE NULL,
	"lastname"                                                          TEXT NULL,
	"firstname"                                                         TEXT NULL,
	"sexe"                                                              INT8 NULL,
	"locale_id"                                                         TEXT NULL,
	"created_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"updated_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	CONSTRAINT "pk_ilcfrance_users" PRIMARY KEY ("id"),
	CONSTRAINT "fk_ilcfrance_users_locale" FOREIGN KEY ("locale_id") REFERENCES "ilcfrance_locales" ("id") ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE "ilcfrance_users_roles" (
	"user_id"                                                           TEXT NOT NULL,
	"role_id"                                                           TEXT NOT NULL,
	CONSTRAINT "pk_ilcfrance_users_roles" PRIMARY KEY ("user_id", "role_id"),
	CONSTRAINT "fk_ilcfrance_users_roles_user" FOREIGN KEY ("user_id") REFERENCES "ilcfrance_users" ("id") ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT "fk_ilcfrance_users_roles_role" FOREIGN KEY ("role_id") REFERENCES "ilcfrance_roles" ("id") ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE "ilcfrance_users_pictures" (
	"user_id"                                                           TEXT NOT NULL,
	"pic_url"                                                           TEXT NULL,
	"created_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"updated_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	CONSTRAINT "pk_ilcfrance_users_pictures" PRIMARY KEY ("user_id"),
	CONSTRAINT "fk_ilcfrance_users_pictures_user" FOREIGN KEY ("user_id") REFERENCES "ilcfrance_users" ("id") ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE "ilcfrance_stagaires" (
	"id"                                                                UUID NOT NULL DEFAULT uuid_generate_v4(),
	"fname"                                                             TEXT NULL,
	"lname"                                                             TEXT NULL,
	"address"                                                           TEXT NULL,
	"town"                                                              TEXT NULL,
	"phone"                                                             TEXT NULL,
	"mobile"                                                            TEXT NULL,
	"job"                                                               TEXT NULL,
	"initlevel"                                                         TEXT NULL,
	"level"                                                             TEXT NULL,
	"needs"                                                             TEXT NULL,
	"courses"                                                           TEXT NULL,
	"created_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"updated_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	CONSTRAINT "pk_ilcfrance_stagaires" PRIMARY KEY ("id")
);

CREATE TABLE "ilcfrance_stagaire_records" (
	"id"                                                                UUID NOT NULL DEFAULT uuid_generate_v4(),
	"stagiaire_id"                                                      UUID NOT NULL,
	"teacher_id"                                                        TEXT NULL,
	"teacher_name"                                                      TEXT NULL,
	"record_date"                                                       TIMESTAMP WITH TIME ZONE NULL,
	"works_covered"                                                     TEXT NULL,
	"comments"                                                          TEXT NULL,
	"homeworks"                                                         TEXT NULL,
	"created_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"updated_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	CONSTRAINT "pk_ilcfrance_stagaire_records" PRIMARY KEY ("id"),
	CONSTRAINT "fk_ilcfrance_stagaire_records_stagaire" FOREIGN KEY ("stagiaire_id") REFERENCES "ilcfrance_stagaires" ("id") ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT "fk_ilcfrance_stagaire_records_teacher" FOREIGN KEY ("teacher_id") REFERENCES "ilcfrance_users" ("id") ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE "ilcfrance_documents" (
	"id"                                                                UUID NOT NULL DEFAULT uuid_generate_v4(),
	"filename"                                                          TEXT NOT NULL,
	"filesize"                                                          INT8 NOT NULL DEFAULT 0,
	"filemimetype"                                                      TEXT NOT NULL,
	"fileoname"                                                         TEXT NOT NULL,
	"filemd5"                                                           TEXT NOT NULL,
	"filedesc"                                                          TEXT NULL,
	"filedls"                                                           INT8 NOT NULL DEFAULT 0,
	"created_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	"updated_at"                                                        TIMESTAMP WITH TIME ZONE NULL,
	CONSTRAINT "pk_ilcfrance_documents" PRIMARY KEY ("id")
);


