CREATE TABLE IF NOT EXISTS "migrations"(
  "id" integer primary key autoincrement not null,
  "migration" varchar not null,
  "batch" integer not null
);
CREATE TABLE IF NOT EXISTS "users"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "email" varchar not null,
  "bio" text,
  "profile_image" varchar,
  "is_super_admin" boolean not null DEFAULT 0,
  "email_verified_at" datetime,
  "password" varchar not null,
  "remember_token" varchar,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE UNIQUE INDEX "users_email_unique" on "users"("email");
CREATE TABLE IF NOT EXISTS "password_reset_tokens"(
  "email" varchar not null,
  "token" varchar not null,
  "created_at" datetime,
  primary key("email")
);
CREATE TABLE IF NOT EXISTS "sessions"(
  "id" varchar not null,
  "user_id" integer,
  "ip_address" varchar,
  "user_agent" text,
  "payload" text not null,
  "last_activity" integer not null,
  primary key("id")
);
CREATE INDEX "sessions_user_id_index" on "sessions"("user_id");
CREATE INDEX "sessions_last_activity_index" on "sessions"("last_activity");
CREATE TABLE IF NOT EXISTS "cache"(
  "key" varchar not null,
  "value" text not null,
  "expiration" integer not null,
  primary key("key")
);
CREATE TABLE IF NOT EXISTS "cache_locks"(
  "key" varchar not null,
  "owner" varchar not null,
  "expiration" integer not null,
  primary key("key")
);
CREATE TABLE IF NOT EXISTS "jobs"(
  "id" integer primary key autoincrement not null,
  "queue" varchar not null,
  "payload" text not null,
  "attempts" integer not null,
  "reserved_at" integer,
  "available_at" integer not null,
  "created_at" integer not null
);
CREATE INDEX "jobs_queue_index" on "jobs"("queue");
CREATE TABLE IF NOT EXISTS "job_batches"(
  "id" varchar not null,
  "name" varchar not null,
  "total_jobs" integer not null,
  "pending_jobs" integer not null,
  "failed_jobs" integer not null,
  "failed_job_ids" text not null,
  "options" text,
  "cancelled_at" integer,
  "created_at" integer not null,
  "finished_at" integer,
  primary key("id")
);
CREATE TABLE IF NOT EXISTS "failed_jobs"(
  "id" integer primary key autoincrement not null,
  "uuid" varchar not null,
  "connection" text not null,
  "queue" text not null,
  "payload" text not null,
  "exception" text not null,
  "failed_at" datetime not null default CURRENT_TIMESTAMP
);
CREATE UNIQUE INDEX "failed_jobs_uuid_unique" on "failed_jobs"("uuid");
CREATE TABLE IF NOT EXISTS "cars"(
  "id" integer primary key autoincrement not null,
  "user_id" integer not null,
  "manufacture" varchar not null,
  "model" varchar not null,
  "displacement" numeric not null,
  "engine_code" varchar not null,
  "whp" integer not null,
  "color" varchar not null,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("user_id") references "users"("id") on delete cascade
);
CREATE TABLE IF NOT EXISTS "modifications"(
  "id" integer primary key autoincrement not null,
  "user_id" integer not null,
  "car_id" integer not null,
  "name" varchar not null,
  "description" varchar,
  "reason" varchar not null,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("user_id") references "users"("id") on delete cascade,
  foreign key("car_id") references "cars"("id") on delete cascade
);
CREATE TABLE IF NOT EXISTS "types"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE TABLE IF NOT EXISTS "tags"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE TABLE IF NOT EXISTS "stories"(
  "id" integer primary key autoincrement not null,
  "user_id" integer not null,
  "car_id" integer not null,
  "body_text" text,
  "body_html" text not null,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("user_id") references "users"("id") on delete cascade,
  foreign key("car_id") references "cars"("id") on delete cascade
);
CREATE TABLE IF NOT EXISTS "car_type"(
  "car_id" integer not null,
  "type_id" integer not null,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("car_id") references "cars"("id") on delete cascade,
  foreign key("type_id") references "types"("id") on delete cascade
);
CREATE TABLE IF NOT EXISTS "car_tag"(
  "car_id" integer not null,
  "tag_id" integer not null,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("car_id") references "cars"("id") on delete cascade,
  foreign key("tag_id") references "tags"("id") on delete cascade
);
CREATE TABLE IF NOT EXISTS "media"(
  "id" integer primary key autoincrement not null,
  "model_type" varchar not null,
  "model_id" integer not null,
  "uuid" varchar,
  "collection_name" varchar not null,
  "name" varchar not null,
  "file_name" varchar not null,
  "mime_type" varchar,
  "disk" varchar not null,
  "conversions_disk" varchar,
  "size" integer not null,
  "manipulations" text not null,
  "custom_properties" text not null,
  "generated_conversions" text not null,
  "responsive_images" text not null,
  "order_column" integer,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE INDEX "media_model_type_model_id_index" on "media"(
  "model_type",
  "model_id"
);
CREATE UNIQUE INDEX "media_uuid_unique" on "media"("uuid");
CREATE INDEX "media_order_column_index" on "media"("order_column");
CREATE TABLE IF NOT EXISTS "permissions"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "description" varchar not null,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE TABLE IF NOT EXISTS "user_permission" (
  "id" integer PRIMARY KEY AUTOINCREMENT NOT NULL,
  "user_id" integer NOT NULL,
  "permission_id" integer NOT NULL,
  "created_at" datetime,
  "updated_at" datetime,
  FOREIGN KEY ("user_id") REFERENCES "users"("id") ON DELETE CASCADE,
  foreign key("permission_id") references "permissions"("id") on delete cascade
);
CREATE TABLE IF NOT EXISTS "groups"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE TABLE IF NOT EXISTS "group_permission"(
  "id" integer primary key autoincrement not null,
  "group_id" integer not null,
  "permission_id" integer not null,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("group_id") references "groups"("id") on delete cascade,
  foreign key("permission_id") references "permissions"("id") on delete cascade
);
CREATE TABLE IF NOT EXISTS "group_user"(
  "id" integer primary key autoincrement not null,
  "group_id" integer not null,
  "user_id" integer not null,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("group_id") references "groups"("id") on delete cascade,
  foreign key("user_id") references "users"("id") on delete cascade
);
CREATE TABLE IF NOT EXISTS "follow_user"(
  "id" integer primary key autoincrement not null,
  "user_id" integer not null,
  "following_id" integer not null,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("user_id") references "users"("id") on delete cascade,
  foreign key("following_id") references "users"("id") on delete cascade
);
CREATE UNIQUE INDEX "follow_user_user_id_following_id_unique" on "follow_user"(
  "user_id",
  "following_id"
);
CREATE TABLE IF NOT EXISTS "replies"(
  "id" integer primary key autoincrement not null,
  "content" text not null,
  "user_id" integer not null,
  "repliable_type" varchar not null,
  "repliable_id" integer not null,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("user_id") references "users"("id") on delete cascade
);
CREATE INDEX "replies_repliable_type_repliable_id_index" on "replies"(
  "repliable_type",
  "repliable_id"
);
CREATE TABLE IF NOT EXISTS "likes"(
  "id" integer primary key autoincrement not null,
  "user_id" integer not null,
  "likeable_type" varchar not null,
  "likeable_id" integer not null,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("user_id") references "users"("id") on delete cascade
);
CREATE INDEX "likes_likeable_type_likeable_id_index" on "likes"(
  "likeable_type",
  "likeable_id"
);
CREATE TABLE IF NOT EXISTS "notifications"(
  "id" varchar not null,
  "type" varchar not null,
  "notifiable_type" varchar not null,
  "notifiable_id" integer not null,
  "data" text not null,
  "read_at" datetime,
  "created_at" datetime,
  "updated_at" datetime,
  primary key("id")
);
CREATE INDEX "notifications_notifiable_type_notifiable_id_index" on "notifications"(
  "notifiable_type",
  "notifiable_id"
);

INSERT INTO migrations VALUES(1,'0001_01_01_000000_create_users_table',1);
INSERT INTO migrations VALUES(2,'0001_01_01_000001_create_cache_table',1);
INSERT INTO migrations VALUES(3,'0001_01_01_000002_create_jobs_table',1);
INSERT INTO migrations VALUES(4,'2025_11_30_130945_create_cars_table',1);
INSERT INTO migrations VALUES(5,'2025_11_30_131045_create_modifications_table',1);
INSERT INTO migrations VALUES(6,'2025_11_30_131101_create_types_table',1);
INSERT INTO migrations VALUES(7,'2025_11_30_131121_create_tags_table',1);
INSERT INTO migrations VALUES(8,'2025_11_30_131140_create_stories_table',1);
INSERT INTO migrations VALUES(9,'2025_11_30_210946_create_car_type',1);
INSERT INTO migrations VALUES(10,'2025_11_30_211201_create_car_tag',1);
INSERT INTO migrations VALUES(11,'2025_12_06_193142_create_media_table',1);
INSERT INTO migrations VALUES(12,'2025_12_13_202252_create_permissions_table',1);
INSERT INTO migrations VALUES(13,'2025_12_13_202300_create_groups_table',1);
INSERT INTO migrations VALUES(14,'2025_12_14_212946_create_group_permission_table',1);
INSERT INTO migrations VALUES(15,'2025_12_14_212953_create_group_user_table',1);
INSERT INTO migrations VALUES(16,'2026_03_25_094738_create_follow_user_table',1);
INSERT INTO migrations VALUES(17,'2026_03_31_115727_create_replies_table',1);
INSERT INTO migrations VALUES(18,'2026_04_06_121844_create_likes_table',1);
INSERT INTO migrations VALUES(19,'2026_04_07_195910_create_notifications_table',1);
