/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "anios_academicos" (
  "id_anio" bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  "anio" int(11) NOT NULL,
  "fecha_inicio" date NOT NULL,
  "fecha_fin" date NOT NULL,
  "estado" enum('Planificado','En curso','Finalizado') NOT NULL,
  "descripcion" text DEFAULT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  PRIMARY KEY ("id_anio"),
  UNIQUE KEY "anios_academicos_anio_unique" ("anio")
);
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO "anios_academicos" VALUES (1,2024,'2024-03-15','2024-12-20','Finalizado','“Año del Bicentenario, de la consolidación de nuestra Independencia, y de la conmemoración de las heroicas batallas de Junín y Ayacucho”','2025-03-29 23:12:25','2025-03-29 23:12:25');
INSERT INTO "anios_academicos" VALUES (2,2025,'0205-03-15','2025-12-20','En curso','“Año de la recuperación y consolidación de la economía peruana”','2025-03-29 23:15:15','2025-03-29 23:15:15');
INSERT INTO "anios_academicos" VALUES (3,1995,'1995-03-13','1995-12-22','Finalizado',NULL,'2025-05-06 22:51:39','2025-05-06 22:51:39');
INSERT INTO "anios_academicos" VALUES (4,1999,'1999-03-13','1999-12-22','Finalizado',NULL,'2025-05-08 19:09:05','2025-05-08 19:09:05');
INSERT INTO "anios_academicos" VALUES (5,2000,'2000-03-15','2000-12-20','Finalizado',NULL,'2025-05-19 21:51:40','2025-05-19 21:51:40');
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "apoderados" (
  "id_apoderado" bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  "nombre" varchar(50) NOT NULL,
  "apellido" varchar(50) NOT NULL,
  "dni" varchar(20) DEFAULT NULL,
  "relacion" varchar(50) DEFAULT NULL,
  "telefono" varchar(20) DEFAULT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  PRIMARY KEY ("id_apoderado"),
  UNIQUE KEY "apoderados_dni_unique" ("dni")
);
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO "apoderados" VALUES (2,'Wilson Smith','Calle Baca','76241568','Padre','953671053','2025-04-02 06:18:11','2025-04-02 06:18:11');
INSERT INTO "apoderados" VALUES (3,'Daniel','Ramirez Rodriguez',NULL,'Madre',NULL,'2025-04-06 19:10:27','2025-04-06 19:10:27');
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "asignaciones" (
  "id_asignacion" bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  "id_docente" bigint(20) unsigned NOT NULL,
  "id_materia" bigint(20) unsigned NOT NULL,
  "id_aula" bigint(20) unsigned NOT NULL,
  "id_anio" bigint(20) unsigned NOT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  PRIMARY KEY ("id_asignacion"),
  UNIQUE KEY "asignaciones_id_docente_id_materia_id_aula_id_anio_unique" ("id_docente","id_materia","id_aula","id_anio"),
  KEY "asignaciones_id_materia_foreign" ("id_materia"),
  KEY "asignaciones_id_aula_foreign" ("id_aula"),
  KEY "asignaciones_id_anio_foreign" ("id_anio"),
  CONSTRAINT "asignaciones_id_anio_foreign" FOREIGN KEY ("id_anio") REFERENCES "anios_academicos" ("id_anio"),
  CONSTRAINT "asignaciones_id_aula_foreign" FOREIGN KEY ("id_aula") REFERENCES "aulas" ("id_aula"),
  CONSTRAINT "asignaciones_id_docente_foreign" FOREIGN KEY ("id_docente") REFERENCES "docentes" ("id_docente") ON DELETE CASCADE,
  CONSTRAINT "asignaciones_id_materia_foreign" FOREIGN KEY ("id_materia") REFERENCES "materias" ("id_materia")
);
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO "asignaciones" VALUES (32,33,40,18,4,'2025-05-06 23:14:54','2025-05-08 19:11:37');
INSERT INTO "asignaciones" VALUES (33,33,41,18,4,'2025-05-06 23:15:10','2025-05-08 19:11:23');
INSERT INTO "asignaciones" VALUES (34,34,42,18,4,'2025-05-06 23:15:41','2025-05-08 19:10:18');
INSERT INTO "asignaciones" VALUES (35,35,43,18,4,'2025-05-06 23:16:01','2025-05-08 19:10:38');
INSERT INTO "asignaciones" VALUES (36,34,44,18,4,'2025-05-06 23:16:24','2025-05-08 19:10:07');
INSERT INTO "asignaciones" VALUES (37,36,16,18,4,'2025-05-06 23:16:45','2025-05-08 19:11:17');
INSERT INTO "asignaciones" VALUES (38,34,45,18,4,'2025-05-06 23:16:58','2025-05-08 19:10:24');
INSERT INTO "asignaciones" VALUES (39,37,46,18,4,'2025-05-06 23:17:14','2025-05-08 19:11:08');
INSERT INTO "asignaciones" VALUES (40,38,47,18,4,'2025-05-06 23:39:16','2025-05-08 19:11:40');
INSERT INTO "asignaciones" VALUES (41,34,48,18,4,'2025-05-06 23:39:34','2025-05-08 19:10:31');
INSERT INTO "asignaciones" VALUES (42,33,40,19,4,'2025-05-08 20:11:04','2025-05-08 20:11:04');
INSERT INTO "asignaciones" VALUES (43,33,41,19,4,'2025-05-08 20:14:53','2025-05-08 20:14:53');
INSERT INTO "asignaciones" VALUES (44,34,42,19,4,'2025-05-08 20:31:53','2025-05-08 20:31:53');
INSERT INTO "asignaciones" VALUES (45,35,43,19,4,'2025-05-08 20:33:59','2025-05-08 20:33:59');
INSERT INTO "asignaciones" VALUES (46,34,44,19,4,'2025-05-08 20:34:18','2025-05-08 20:34:18');
INSERT INTO "asignaciones" VALUES (47,36,16,19,4,'2025-05-08 20:35:34','2025-05-08 20:35:34');
INSERT INTO "asignaciones" VALUES (48,34,45,19,4,'2025-05-08 20:35:47','2025-05-08 20:35:47');
INSERT INTO "asignaciones" VALUES (49,37,46,19,4,'2025-05-08 20:36:20','2025-05-08 20:36:20');
INSERT INTO "asignaciones" VALUES (50,38,47,19,4,'2025-05-08 20:36:44','2025-05-08 20:36:44');
INSERT INTO "asignaciones" VALUES (51,34,48,19,4,'2025-05-08 20:41:19','2025-05-08 20:41:19');
INSERT INTO "asignaciones" VALUES (53,33,40,20,5,'2025-05-21 20:43:51','2025-05-21 20:43:51');
INSERT INTO "asignaciones" VALUES (54,40,27,20,5,'2025-05-21 20:44:17','2025-05-21 20:44:17');
INSERT INTO "asignaciones" VALUES (55,34,53,20,5,'2025-05-21 21:28:40','2025-05-21 21:28:40');
INSERT INTO "asignaciones" VALUES (56,35,43,20,5,'2025-05-21 21:32:48','2025-05-21 21:32:48');
INSERT INTO "asignaciones" VALUES (57,41,55,20,5,'2025-05-21 21:34:19','2025-05-21 21:34:19');
INSERT INTO "asignaciones" VALUES (58,41,56,20,5,'2025-05-21 21:34:19','2025-05-21 21:34:19');
INSERT INTO "asignaciones" VALUES (59,41,57,20,5,'2025-05-21 21:36:16','2025-05-21 21:36:16');
INSERT INTO "asignaciones" VALUES (60,42,16,20,5,'2025-05-21 21:36:48','2025-05-21 21:36:48');
INSERT INTO "asignaciones" VALUES (61,37,46,20,5,'2025-05-21 21:38:03','2025-05-21 21:38:03');
INSERT INTO "asignaciones" VALUES (62,38,47,20,5,'2025-05-21 21:38:33','2025-05-21 21:38:33');
INSERT INTO "asignaciones" VALUES (63,43,48,20,5,'2025-05-21 21:40:20','2025-05-21 21:40:20');
INSERT INTO "asignaciones" VALUES (64,34,54,20,5,'2025-05-21 21:44:11','2025-05-21 21:44:11');
INSERT INTO "asignaciones" VALUES (65,33,40,18,5,'2025-05-22 20:30:28','2025-05-22 20:30:28');
INSERT INTO "asignaciones" VALUES (66,40,41,18,5,'2025-05-22 20:30:53','2025-05-22 20:30:53');
INSERT INTO "asignaciones" VALUES (67,34,42,18,5,'2025-05-22 20:31:29','2025-05-22 20:31:29');
INSERT INTO "asignaciones" VALUES (68,34,44,18,5,'2025-05-22 20:31:29','2025-05-22 20:38:30');
INSERT INTO "asignaciones" VALUES (69,35,43,18,5,'2025-05-22 20:34:33','2025-05-22 20:34:33');
INSERT INTO "asignaciones" VALUES (70,42,16,18,5,'2025-05-22 20:35:18','2025-05-22 20:35:18');
INSERT INTO "asignaciones" VALUES (71,41,45,18,5,'2025-05-22 20:35:46','2025-05-22 20:35:46');
INSERT INTO "asignaciones" VALUES (72,41,48,18,5,'2025-05-22 20:35:46','2025-05-22 20:35:46');
INSERT INTO "asignaciones" VALUES (73,37,46,18,5,'2025-05-22 20:36:09','2025-05-22 20:36:09');
INSERT INTO "asignaciones" VALUES (74,38,47,18,5,'2025-05-22 20:36:27','2025-05-22 20:36:27');
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "asistencia" (
  "id_asistencia" bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  "id_estudiante" bigint(20) unsigned NOT NULL,
  "fecha" date NOT NULL,
  "estado" enum('Presente','Ausente','Tardanza','Justificado') NOT NULL,
  "observacion" varchar(200) DEFAULT NULL,
  "id_asignacion" bigint(20) unsigned DEFAULT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  PRIMARY KEY ("id_asistencia"),
  UNIQUE KEY "asistencia_id_estudiante_fecha_id_asignacion_unique" ("id_estudiante","fecha","id_asignacion"),
  KEY "asistencia_id_asignacion_foreign" ("id_asignacion"),
  CONSTRAINT "asistencia_id_asignacion_foreign" FOREIGN KEY ("id_asignacion") REFERENCES "asignaciones" ("id_asignacion") ON DELETE SET NULL,
  CONSTRAINT "asistencia_id_estudiante_foreign" FOREIGN KEY ("id_estudiante") REFERENCES "estudiantes" ("id_estudiante") ON DELETE CASCADE
);
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO "asistencia" VALUES (1,23,'2025-03-31','Tardanza',NULL,NULL,'2025-03-31 20:52:45','2025-03-31 20:52:45');
INSERT INTO "asistencia" VALUES (2,26,'2025-03-31','Tardanza',NULL,NULL,'2025-03-31 20:52:45','2025-03-31 20:52:45');
INSERT INTO "asistencia" VALUES (3,21,'2025-03-31','Presente',NULL,NULL,'2025-03-31 20:52:45','2025-03-31 20:52:45');
INSERT INTO "asistencia" VALUES (4,28,'2025-03-31','Presente',NULL,NULL,'2025-03-31 20:52:45','2025-03-31 20:52:45');
INSERT INTO "asistencia" VALUES (5,24,'2025-03-31','Presente',NULL,NULL,'2025-03-31 20:52:45','2025-03-31 20:52:45');
INSERT INTO "asistencia" VALUES (6,27,'2025-03-31','Ausente',NULL,NULL,'2025-03-31 20:52:45','2025-03-31 20:52:45');
INSERT INTO "asistencia" VALUES (7,19,'2025-03-31','Tardanza',NULL,NULL,'2025-03-31 20:52:45','2025-03-31 20:54:08');
INSERT INTO "asistencia" VALUES (8,22,'2025-03-31','Presente',NULL,NULL,'2025-03-31 20:52:45','2025-03-31 20:52:45');
INSERT INTO "asistencia" VALUES (9,25,'2025-03-31','Presente',NULL,NULL,'2025-03-31 20:52:45','2025-03-31 20:52:45');
INSERT INTO "asistencia" VALUES (10,20,'2025-03-31','Presente',NULL,NULL,'2025-03-31 20:52:45','2025-03-31 20:52:45');
INSERT INTO "asistencia" VALUES (11,17,'2025-03-31','Presente',NULL,NULL,'2025-03-31 20:58:24','2025-03-31 20:58:24');
INSERT INTO "asistencia" VALUES (12,9,'2025-03-31','Presente',NULL,NULL,'2025-03-31 20:58:24','2025-03-31 20:58:24');
INSERT INTO "asistencia" VALUES (13,12,'2025-03-31','Presente',NULL,NULL,'2025-03-31 20:58:24','2025-03-31 20:58:24');
INSERT INTO "asistencia" VALUES (14,11,'2025-03-31','Presente',NULL,NULL,'2025-03-31 20:58:24','2025-03-31 20:58:24');
INSERT INTO "asistencia" VALUES (15,10,'2025-03-31','Tardanza',NULL,NULL,'2025-03-31 20:58:24','2025-03-31 20:58:24');
INSERT INTO "asistencia" VALUES (16,16,'2025-03-31','Presente',NULL,NULL,'2025-03-31 20:58:24','2025-03-31 20:58:24');
INSERT INTO "asistencia" VALUES (17,13,'2025-03-31','Justificado',NULL,NULL,'2025-03-31 20:58:24','2025-03-31 20:58:24');
INSERT INTO "asistencia" VALUES (18,18,'2025-03-31','Presente',NULL,NULL,'2025-03-31 20:58:24','2025-03-31 20:58:24');
INSERT INTO "asistencia" VALUES (19,14,'2025-03-31','Presente',NULL,NULL,'2025-03-31 20:58:24','2025-03-31 20:58:24');
INSERT INTO "asistencia" VALUES (20,15,'2025-03-31','Presente',NULL,NULL,'2025-03-31 20:58:24','2025-03-31 20:58:24');
INSERT INTO "asistencia" VALUES (21,29,'2025-03-31','Presente',NULL,NULL,'2025-03-31 20:59:37','2025-03-31 20:59:37');
INSERT INTO "asistencia" VALUES (22,33,'2025-03-31','Tardanza',NULL,NULL,'2025-03-31 20:59:37','2025-03-31 20:59:37');
INSERT INTO "asistencia" VALUES (23,31,'2025-03-31','Presente',NULL,NULL,'2025-03-31 20:59:37','2025-03-31 20:59:37');
INSERT INTO "asistencia" VALUES (24,37,'2025-03-31','Presente',NULL,NULL,'2025-03-31 20:59:37','2025-03-31 20:59:37');
INSERT INTO "asistencia" VALUES (25,30,'2025-03-31','Presente',NULL,NULL,'2025-03-31 20:59:37','2025-03-31 20:59:37');
INSERT INTO "asistencia" VALUES (26,34,'2025-03-31','Justificado',NULL,NULL,'2025-03-31 20:59:37','2025-03-31 20:59:37');
INSERT INTO "asistencia" VALUES (27,32,'2025-03-31','Presente',NULL,NULL,'2025-03-31 20:59:37','2025-03-31 20:59:37');
INSERT INTO "asistencia" VALUES (28,38,'2025-03-31','Presente',NULL,NULL,'2025-03-31 20:59:37','2025-03-31 20:59:37');
INSERT INTO "asistencia" VALUES (29,35,'2025-03-31','Presente',NULL,NULL,'2025-03-31 20:59:37','2025-03-31 20:59:37');
INSERT INTO "asistencia" VALUES (30,36,'2025-03-31','Presente',NULL,NULL,'2025-03-31 20:59:37','2025-03-31 20:59:37');
INSERT INTO "asistencia" VALUES (31,48,'2025-03-31','Presente',NULL,NULL,'2025-03-31 21:00:33','2025-03-31 21:00:33');
INSERT INTO "asistencia" VALUES (32,39,'2025-03-31','Ausente',NULL,NULL,'2025-03-31 21:00:33','2025-03-31 21:00:33');
INSERT INTO "asistencia" VALUES (33,43,'2025-03-31','Presente',NULL,NULL,'2025-03-31 21:00:33','2025-03-31 21:00:33');
INSERT INTO "asistencia" VALUES (34,42,'2025-03-31','Ausente',NULL,NULL,'2025-03-31 21:00:33','2025-03-31 21:00:33');
INSERT INTO "asistencia" VALUES (35,40,'2025-03-31','Presente',NULL,NULL,'2025-03-31 21:00:33','2025-03-31 21:00:33');
INSERT INTO "asistencia" VALUES (36,41,'2025-03-31','Ausente',NULL,NULL,'2025-03-31 21:00:33','2025-03-31 21:00:33');
INSERT INTO "asistencia" VALUES (37,46,'2025-03-31','Presente',NULL,NULL,'2025-03-31 21:00:33','2025-03-31 21:00:33');
INSERT INTO "asistencia" VALUES (38,45,'2025-03-31','Presente',NULL,NULL,'2025-03-31 21:00:33','2025-03-31 21:00:33');
INSERT INTO "asistencia" VALUES (39,44,'2025-03-31','Presente',NULL,NULL,'2025-03-31 21:00:33','2025-03-31 21:00:33');
INSERT INTO "asistencia" VALUES (40,47,'2025-03-31','Presente',NULL,NULL,'2025-03-31 21:00:33','2025-03-31 21:00:33');
INSERT INTO "asistencia" VALUES (41,61,'2025-03-31','Justificado',NULL,NULL,'2025-03-31 21:02:04','2025-03-31 21:02:04');
INSERT INTO "asistencia" VALUES (42,64,'2025-03-31','Presente',NULL,NULL,'2025-03-31 21:02:04','2025-03-31 21:02:04');
INSERT INTO "asistencia" VALUES (43,67,'2025-03-31','Presente',NULL,NULL,'2025-03-31 21:02:04','2025-03-31 21:02:04');
INSERT INTO "asistencia" VALUES (44,59,'2025-03-31','Presente',NULL,NULL,'2025-03-31 21:02:04','2025-03-31 21:02:04');
INSERT INTO "asistencia" VALUES (45,68,'2025-03-31','Presente',NULL,NULL,'2025-03-31 21:02:04','2025-03-31 21:02:04');
INSERT INTO "asistencia" VALUES (46,60,'2025-03-31','Presente',NULL,NULL,'2025-03-31 21:02:04','2025-03-31 21:02:04');
INSERT INTO "asistencia" VALUES (47,62,'2025-03-31','Justificado',NULL,NULL,'2025-03-31 21:02:04','2025-03-31 21:02:04');
INSERT INTO "asistencia" VALUES (48,63,'2025-03-31','Presente',NULL,NULL,'2025-03-31 21:02:04','2025-03-31 21:02:04');
INSERT INTO "asistencia" VALUES (49,66,'2025-03-31','Presente',NULL,NULL,'2025-03-31 21:02:04','2025-03-31 21:02:04');
INSERT INTO "asistencia" VALUES (50,65,'2025-03-31','Presente',NULL,NULL,'2025-03-31 21:02:04','2025-03-31 21:02:04');
INSERT INTO "asistencia" VALUES (51,97,'2025-03-31','Tardanza',NULL,NULL,'2025-03-31 21:04:38','2025-03-31 21:04:38');
INSERT INTO "asistencia" VALUES (52,93,'2025-03-31','Presente',NULL,NULL,'2025-03-31 21:04:38','2025-03-31 21:04:38');
INSERT INTO "asistencia" VALUES (53,89,'2025-03-31','Presente',NULL,NULL,'2025-03-31 21:04:38','2025-03-31 21:04:38');
INSERT INTO "asistencia" VALUES (54,90,'2025-03-31','Ausente',NULL,NULL,'2025-03-31 21:04:38','2025-03-31 21:04:38');
INSERT INTO "asistencia" VALUES (55,91,'2025-03-31','Presente',NULL,NULL,'2025-03-31 21:04:38','2025-03-31 21:04:38');
INSERT INTO "asistencia" VALUES (56,98,'2025-03-31','Presente',NULL,NULL,'2025-03-31 21:04:38','2025-03-31 21:04:38');
INSERT INTO "asistencia" VALUES (57,96,'2025-03-31','Tardanza',NULL,NULL,'2025-03-31 21:04:38','2025-03-31 21:04:38');
INSERT INTO "asistencia" VALUES (58,94,'2025-03-31','Presente',NULL,NULL,'2025-03-31 21:04:38','2025-03-31 21:04:38');
INSERT INTO "asistencia" VALUES (59,92,'2025-03-31','Presente',NULL,NULL,'2025-03-31 21:04:38','2025-03-31 21:04:38');
INSERT INTO "asistencia" VALUES (60,95,'2025-03-31','Presente',NULL,NULL,'2025-03-31 21:04:38','2025-03-31 21:04:38');
INSERT INTO "asistencia" VALUES (61,80,'2025-03-31','Tardanza',NULL,NULL,'2025-03-31 21:06:05','2025-03-31 21:06:05');
INSERT INTO "asistencia" VALUES (62,86,'2025-03-31','Ausente',NULL,NULL,'2025-03-31 21:06:05','2025-03-31 21:06:05');
INSERT INTO "asistencia" VALUES (63,84,'2025-03-31','Presente',NULL,NULL,'2025-03-31 21:06:05','2025-03-31 21:06:05');
INSERT INTO "asistencia" VALUES (64,81,'2025-03-31','Presente',NULL,NULL,'2025-03-31 21:06:05','2025-03-31 21:06:05');
INSERT INTO "asistencia" VALUES (65,88,'2025-03-31','Tardanza',NULL,NULL,'2025-03-31 21:06:05','2025-03-31 21:06:05');
INSERT INTO "asistencia" VALUES (66,85,'2025-03-31','Presente',NULL,NULL,'2025-03-31 21:06:05','2025-03-31 21:06:05');
INSERT INTO "asistencia" VALUES (67,79,'2025-03-31','Presente',NULL,NULL,'2025-03-31 21:06:05','2025-03-31 21:06:05');
INSERT INTO "asistencia" VALUES (68,82,'2025-03-31','Presente',NULL,NULL,'2025-03-31 21:06:05','2025-03-31 21:06:05');
INSERT INTO "asistencia" VALUES (69,83,'2025-03-31','Presente',NULL,NULL,'2025-03-31 21:06:05','2025-03-31 21:06:05');
INSERT INTO "asistencia" VALUES (70,87,'2025-03-31','Presente',NULL,NULL,'2025-03-31 21:06:05','2025-03-31 21:06:05');
INSERT INTO "asistencia" VALUES (71,117,'2025-03-31','Ausente',NULL,NULL,'2025-03-31 21:06:51','2025-03-31 21:06:51');
INSERT INTO "asistencia" VALUES (72,5,'2025-03-31','Presente',NULL,NULL,'2025-03-31 21:06:51','2025-03-31 21:06:51');
INSERT INTO "asistencia" VALUES (73,115,'2025-03-31','Presente',NULL,NULL,'2025-03-31 21:06:51','2025-03-31 21:06:51');
INSERT INTO "asistencia" VALUES (74,109,'2025-03-31','Presente',NULL,NULL,'2025-03-31 21:06:51','2025-03-31 21:06:51');
INSERT INTO "asistencia" VALUES (75,118,'2025-03-31','Presente',NULL,NULL,'2025-03-31 21:06:51','2025-03-31 21:06:51');
INSERT INTO "asistencia" VALUES (76,112,'2025-03-31','Justificado',NULL,NULL,'2025-03-31 21:06:51','2025-03-31 21:06:51');
INSERT INTO "asistencia" VALUES (77,116,'2025-03-31','Presente',NULL,NULL,'2025-03-31 21:06:51','2025-03-31 21:06:51');
INSERT INTO "asistencia" VALUES (78,111,'2025-03-31','Tardanza',NULL,NULL,'2025-03-31 21:06:51','2025-03-31 21:06:51');
INSERT INTO "asistencia" VALUES (79,110,'2025-03-31','Presente',NULL,NULL,'2025-03-31 21:06:51','2025-03-31 21:06:51');
INSERT INTO "asistencia" VALUES (80,114,'2025-03-31','Presente',NULL,NULL,'2025-03-31 21:06:51','2025-03-31 21:06:51');
INSERT INTO "asistencia" VALUES (81,113,'2025-03-31','Presente',NULL,NULL,'2025-03-31 21:06:51','2025-03-31 21:06:51');
INSERT INTO "asistencia" VALUES (82,23,'2025-04-01','Presente',NULL,NULL,'2025-04-01 21:01:49','2025-04-01 21:01:49');
INSERT INTO "asistencia" VALUES (83,26,'2025-04-01','Presente',NULL,NULL,'2025-04-01 21:01:49','2025-04-01 21:01:49');
INSERT INTO "asistencia" VALUES (84,21,'2025-04-01','Presente',NULL,NULL,'2025-04-01 21:01:49','2025-04-01 21:01:49');
INSERT INTO "asistencia" VALUES (85,28,'2025-04-01','Presente',NULL,NULL,'2025-04-01 21:01:49','2025-04-01 21:01:49');
INSERT INTO "asistencia" VALUES (86,24,'2025-04-01','Presente',NULL,NULL,'2025-04-01 21:01:49','2025-04-01 21:01:49');
INSERT INTO "asistencia" VALUES (87,27,'2025-04-01','Presente',NULL,NULL,'2025-04-01 21:01:49','2025-04-01 21:01:49');
INSERT INTO "asistencia" VALUES (88,19,'2025-04-01','Presente',NULL,NULL,'2025-04-01 21:01:49','2025-04-01 21:01:49');
INSERT INTO "asistencia" VALUES (89,22,'2025-04-01','Presente',NULL,NULL,'2025-04-01 21:01:49','2025-04-01 21:01:49');
INSERT INTO "asistencia" VALUES (90,25,'2025-04-01','Presente',NULL,NULL,'2025-04-01 21:01:49','2025-04-01 21:01:49');
INSERT INTO "asistencia" VALUES (91,20,'2025-04-01','Presente',NULL,NULL,'2025-04-01 21:01:49','2025-04-01 21:01:49');
INSERT INTO "asistencia" VALUES (92,23,'2025-04-02','Presente',NULL,NULL,'2025-04-02 00:07:02','2025-04-02 00:07:02');
INSERT INTO "asistencia" VALUES (93,26,'2025-04-02','Ausente',NULL,NULL,'2025-04-02 00:07:02','2025-04-02 00:07:02');
INSERT INTO "asistencia" VALUES (94,21,'2025-04-02','Tardanza',NULL,NULL,'2025-04-02 00:07:02','2025-04-02 00:07:02');
INSERT INTO "asistencia" VALUES (95,28,'2025-04-02','Tardanza',NULL,NULL,'2025-04-02 00:07:02','2025-04-02 00:07:02');
INSERT INTO "asistencia" VALUES (96,24,'2025-04-02','Tardanza',NULL,NULL,'2025-04-02 00:07:02','2025-04-02 00:07:02');
INSERT INTO "asistencia" VALUES (97,27,'2025-04-02','Tardanza',NULL,NULL,'2025-04-02 00:07:02','2025-04-02 00:07:02');
INSERT INTO "asistencia" VALUES (98,19,'2025-04-02','Tardanza',NULL,NULL,'2025-04-02 00:07:02','2025-04-02 00:07:02');
INSERT INTO "asistencia" VALUES (99,22,'2025-04-02','Tardanza',NULL,NULL,'2025-04-02 00:07:02','2025-04-02 00:07:02');
INSERT INTO "asistencia" VALUES (100,25,'2025-04-02','Tardanza',NULL,NULL,'2025-04-02 00:07:02','2025-04-02 00:07:02');
INSERT INTO "asistencia" VALUES (101,20,'2025-04-02','Tardanza',NULL,NULL,'2025-04-02 00:07:02','2025-04-02 00:07:02');
INSERT INTO "asistencia" VALUES (102,23,'2025-04-03','Tardanza',NULL,NULL,'2025-04-02 00:07:19','2025-04-02 00:07:19');
INSERT INTO "asistencia" VALUES (103,26,'2025-04-03','Presente',NULL,NULL,'2025-04-02 00:07:19','2025-04-02 00:07:19');
INSERT INTO "asistencia" VALUES (104,21,'2025-04-03','Ausente',NULL,NULL,'2025-04-02 00:07:19','2025-04-02 00:07:19');
INSERT INTO "asistencia" VALUES (105,28,'2025-04-03','Presente',NULL,NULL,'2025-04-02 00:07:19','2025-04-02 00:07:19');
INSERT INTO "asistencia" VALUES (106,24,'2025-04-03','Presente',NULL,NULL,'2025-04-02 00:07:19','2025-04-02 00:07:19');
INSERT INTO "asistencia" VALUES (107,27,'2025-04-03','Justificado',NULL,NULL,'2025-04-02 00:07:19','2025-04-11 21:51:47');
INSERT INTO "asistencia" VALUES (108,19,'2025-04-03','Presente',NULL,NULL,'2025-04-02 00:07:19','2025-04-02 00:07:19');
INSERT INTO "asistencia" VALUES (109,22,'2025-04-03','Ausente',NULL,NULL,'2025-04-02 00:07:19','2025-04-02 00:07:19');
INSERT INTO "asistencia" VALUES (110,25,'2025-04-03','Presente',NULL,NULL,'2025-04-02 00:07:19','2025-04-02 00:07:19');
INSERT INTO "asistencia" VALUES (111,20,'2025-04-03','Presente',NULL,NULL,'2025-04-02 00:07:19','2025-04-02 00:07:19');
INSERT INTO "asistencia" VALUES (112,23,'2025-04-04','Tardanza',NULL,NULL,'2025-04-02 00:10:33','2025-04-22 23:28:19');
INSERT INTO "asistencia" VALUES (113,26,'2025-04-04','Presente',NULL,NULL,'2025-04-02 00:10:33','2025-04-02 00:10:33');
INSERT INTO "asistencia" VALUES (114,21,'2025-04-04','Justificado',NULL,NULL,'2025-04-02 00:10:33','2025-04-02 00:10:33');
INSERT INTO "asistencia" VALUES (115,28,'2025-04-04','Presente',NULL,NULL,'2025-04-02 00:10:33','2025-04-02 00:10:33');
INSERT INTO "asistencia" VALUES (116,24,'2025-04-04','Justificado',NULL,NULL,'2025-04-02 00:10:33','2025-04-02 00:17:37');
INSERT INTO "asistencia" VALUES (117,27,'2025-04-04','Tardanza',NULL,NULL,'2025-04-02 00:10:33','2025-05-12 01:34:43');
INSERT INTO "asistencia" VALUES (118,19,'2025-04-04','Presente',NULL,NULL,'2025-04-02 00:10:33','2025-04-02 00:10:33');
INSERT INTO "asistencia" VALUES (119,22,'2025-04-04','Presente',NULL,NULL,'2025-04-02 00:10:33','2025-04-02 00:10:33');
INSERT INTO "asistencia" VALUES (120,25,'2025-04-04','Tardanza',NULL,NULL,'2025-04-02 00:10:33','2025-04-02 00:17:37');
INSERT INTO "asistencia" VALUES (121,20,'2025-04-04','Presente',NULL,NULL,'2025-04-02 00:10:33','2025-04-02 00:10:33');
INSERT INTO "asistencia" VALUES (122,23,'2025-04-05','Presente',NULL,NULL,'2025-04-02 00:10:45','2025-04-02 00:10:45');
INSERT INTO "asistencia" VALUES (123,26,'2025-04-05','Ausente',NULL,NULL,'2025-04-02 00:10:45','2025-04-02 00:10:45');
INSERT INTO "asistencia" VALUES (124,21,'2025-04-05','Presente',NULL,NULL,'2025-04-02 00:10:45','2025-04-02 00:10:45');
INSERT INTO "asistencia" VALUES (125,28,'2025-04-05','Ausente',NULL,NULL,'2025-04-02 00:10:45','2025-04-02 00:10:45');
INSERT INTO "asistencia" VALUES (126,24,'2025-04-05','Tardanza',NULL,NULL,'2025-04-02 00:10:45','2025-04-02 00:10:45');
INSERT INTO "asistencia" VALUES (127,27,'2025-04-05','Presente',NULL,NULL,'2025-04-02 00:10:45','2025-04-02 00:10:45');
INSERT INTO "asistencia" VALUES (128,19,'2025-04-05','Presente',NULL,NULL,'2025-04-02 00:10:45','2025-04-02 00:10:45');
INSERT INTO "asistencia" VALUES (129,22,'2025-04-05','Presente',NULL,NULL,'2025-04-02 00:10:45','2025-04-02 00:10:45');
INSERT INTO "asistencia" VALUES (130,25,'2025-04-05','Ausente',NULL,NULL,'2025-04-02 00:10:45','2025-04-02 00:10:45');
INSERT INTO "asistencia" VALUES (131,20,'2025-04-05','Presente',NULL,NULL,'2025-04-02 00:10:45','2025-04-02 00:10:45');
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "aula_docente" (
  "id_aula" bigint(20) unsigned NOT NULL,
  "id_docente" bigint(20) unsigned NOT NULL,
  "id_materia" bigint(20) unsigned NOT NULL,
  "id_anio" bigint(20) unsigned NOT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  PRIMARY KEY ("id_aula","id_docente","id_materia","id_anio"),
  KEY "aula_docente_id_docente_foreign" ("id_docente"),
  KEY "aula_docente_id_materia_foreign" ("id_materia"),
  KEY "aula_docente_id_anio_foreign" ("id_anio"),
  CONSTRAINT "aula_docente_id_anio_foreign" FOREIGN KEY ("id_anio") REFERENCES "anios_academicos" ("id_anio") ON DELETE CASCADE,
  CONSTRAINT "aula_docente_id_aula_foreign" FOREIGN KEY ("id_aula") REFERENCES "aulas" ("id_aula") ON DELETE CASCADE,
  CONSTRAINT "aula_docente_id_docente_foreign" FOREIGN KEY ("id_docente") REFERENCES "docentes" ("id_docente") ON DELETE CASCADE,
  CONSTRAINT "aula_docente_id_materia_foreign" FOREIGN KEY ("id_materia") REFERENCES "materias" ("id_materia") ON DELETE CASCADE
);
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "aula_estudiante" (
  "id_aula" bigint(20) unsigned NOT NULL,
  "id_estudiante" bigint(20) unsigned NOT NULL,
  "id_anio" bigint(20) unsigned NOT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  PRIMARY KEY ("id_aula","id_estudiante","id_anio"),
  KEY "aula_estudiante_id_estudiante_foreign" ("id_estudiante"),
  KEY "aula_estudiante_id_anio_foreign" ("id_anio"),
  CONSTRAINT "aula_estudiante_id_anio_foreign" FOREIGN KEY ("id_anio") REFERENCES "anios_academicos" ("id_anio") ON DELETE CASCADE,
  CONSTRAINT "aula_estudiante_id_aula_foreign" FOREIGN KEY ("id_aula") REFERENCES "aulas" ("id_aula") ON DELETE CASCADE,
  CONSTRAINT "aula_estudiante_id_estudiante_foreign" FOREIGN KEY ("id_estudiante") REFERENCES "estudiantes" ("id_estudiante") ON DELETE CASCADE
);
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "aulas" (
  "id_aula" bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  "id_nivel" bigint(20) unsigned NOT NULL,
  "id_grado" bigint(20) unsigned NOT NULL,
  "id_seccion" bigint(20) unsigned DEFAULT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  PRIMARY KEY ("id_aula"),
  UNIQUE KEY "aulas_id_nivel_id_grado_id_seccion_unique" ("id_nivel","id_grado","id_seccion"),
  KEY "aulas_id_grado_foreign" ("id_grado"),
  KEY "aulas_id_seccion_foreign" ("id_seccion"),
  CONSTRAINT "aulas_id_grado_foreign" FOREIGN KEY ("id_grado") REFERENCES "grados" ("id_grado") ON DELETE CASCADE,
  CONSTRAINT "aulas_id_nivel_foreign" FOREIGN KEY ("id_nivel") REFERENCES "niveles" ("id_nivel") ON DELETE CASCADE,
  CONSTRAINT "aulas_id_seccion_foreign" FOREIGN KEY ("id_seccion") REFERENCES "secciones" ("id_seccion") ON DELETE CASCADE
);
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO "aulas" VALUES (4,1,14,3,'2025-03-28 22:43:35','2025-03-28 22:43:35');
INSERT INTO "aulas" VALUES (5,1,15,5,'2025-03-28 22:43:49','2025-03-28 22:43:49');
INSERT INTO "aulas" VALUES (6,1,16,6,'2025-03-28 22:44:32','2025-03-28 22:44:32');
INSERT INTO "aulas" VALUES (8,2,2,21,'2025-03-28 22:54:26','2025-03-28 22:54:26');
INSERT INTO "aulas" VALUES (9,2,2,22,'2025-03-28 22:54:35','2025-03-28 22:54:35');
INSERT INTO "aulas" VALUES (10,2,3,7,'2025-03-28 22:54:43','2025-03-28 22:54:43');
INSERT INTO "aulas" VALUES (11,2,3,23,'2025-03-28 22:55:01','2025-03-28 22:55:01');
INSERT INTO "aulas" VALUES (12,2,4,8,'2025-03-28 22:55:09','2025-03-28 22:55:09');
INSERT INTO "aulas" VALUES (13,2,4,24,'2025-03-28 22:55:18','2025-03-28 22:55:18');
INSERT INTO "aulas" VALUES (14,2,5,9,'2025-03-28 22:55:27','2025-03-28 22:55:27');
INSERT INTO "aulas" VALUES (15,2,5,25,'2025-03-28 22:55:38','2025-03-28 22:55:38');
INSERT INTO "aulas" VALUES (16,2,6,10,'2025-03-28 22:55:49','2025-03-28 22:55:49');
INSERT INTO "aulas" VALUES (17,2,6,26,'2025-03-28 22:55:59','2025-03-28 22:55:59');
INSERT INTO "aulas" VALUES (18,3,7,16,'2025-03-28 22:56:10','2025-03-28 22:56:10');
INSERT INTO "aulas" VALUES (19,3,7,11,'2025-03-28 22:56:19','2025-03-28 22:56:19');
INSERT INTO "aulas" VALUES (20,3,9,17,'2025-03-28 23:00:22','2025-03-28 23:00:22');
INSERT INTO "aulas" VALUES (22,3,9,12,'2025-03-28 23:05:23','2025-03-28 23:05:23');
INSERT INTO "aulas" VALUES (23,3,11,18,'2025-03-28 23:05:33','2025-03-28 23:05:33');
INSERT INTO "aulas" VALUES (24,3,11,13,'2025-03-28 23:05:42','2025-03-28 23:05:42');
INSERT INTO "aulas" VALUES (25,3,12,19,'2025-03-28 23:05:51','2025-03-28 23:05:51');
INSERT INTO "aulas" VALUES (26,3,12,14,'2025-03-28 23:06:00','2025-03-28 23:06:00');
INSERT INTO "aulas" VALUES (28,2,1,28,'2025-03-28 23:20:28','2025-03-28 23:20:28');
INSERT INTO "aulas" VALUES (29,2,1,29,'2025-03-28 23:26:32','2025-03-28 23:26:32');
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "cache" (
  "key" varchar(255) NOT NULL,
  "value" mediumtext NOT NULL,
  "expiration" int(11) NOT NULL,
  PRIMARY KEY ("key")
);
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "cache_locks" (
  "key" varchar(255) NOT NULL,
  "owner" varchar(255) NOT NULL,
  "expiration" int(11) NOT NULL,
  PRIMARY KEY ("key")
);
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "calificaciones" (
  "id_calificacion" bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  "id_estudiante" bigint(20) unsigned NOT NULL,
  "id_asignacion" bigint(20) unsigned NOT NULL,
  "id_trimestre" bigint(20) unsigned NOT NULL,
  "grado" varchar(50) NOT NULL,
  "nota" decimal(4,2) NOT NULL,
  "fecha" date NOT NULL,
  "observacion" varchar(200) DEFAULT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  PRIMARY KEY ("id_calificacion"),
  KEY "calificaciones_id_estudiante_foreign" ("id_estudiante"),
  KEY "calificaciones_id_asignacion_foreign" ("id_asignacion"),
  KEY "calificaciones_id_trimestre_foreign" ("id_trimestre"),
  CONSTRAINT "calificaciones_id_asignacion_foreign" FOREIGN KEY ("id_asignacion") REFERENCES "asignaciones" ("id_asignacion") ON DELETE CASCADE,
  CONSTRAINT "calificaciones_id_estudiante_foreign" FOREIGN KEY ("id_estudiante") REFERENCES "estudiantes" ("id_estudiante") ON DELETE CASCADE,
  CONSTRAINT "calificaciones_id_trimestre_foreign" FOREIGN KEY ("id_trimestre") REFERENCES "trimestres" ("id_trimestre") ON DELETE CASCADE
);
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "calificacionesold" (
  "id_calificacion" bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  "id_estudiante" bigint(20) unsigned NOT NULL,
  "id_asignacion" bigint(20) unsigned NOT NULL,
  "id_trimestre" bigint(20) unsigned NOT NULL,
  "comportamiento" tinyint(3) unsigned NOT NULL COMMENT 'Comportamiento de 0 a 20',
  "asignaturas_reprobadas" smallint(5) unsigned NOT NULL COMMENT 'Cantidad de asignaturas reprobadas',
  "conclusion" varchar(50) NOT NULL COMMENT 'ConclusiÃ³n derivada de la nota',
  "grado" varchar(50) NOT NULL,
  "nota" decimal(4,2) NOT NULL,
  "fecha" date NOT NULL,
  "observacion" varchar(200) DEFAULT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  PRIMARY KEY ("id_calificacion"),
  KEY "calificacionesold_id_estudiante_foreign" ("id_estudiante"),
  KEY "calificacionesold_id_asignacion_foreign" ("id_asignacion"),
  KEY "calificacionesold_id_trimestre_foreign" ("id_trimestre"),
  CONSTRAINT "calificacionesold_id_asignacion_foreign" FOREIGN KEY ("id_asignacion") REFERENCES "asignaciones" ("id_asignacion") ON DELETE CASCADE,
  CONSTRAINT "calificacionesold_id_estudiante_foreign" FOREIGN KEY ("id_estudiante") REFERENCES "estudiantes" ("id_estudiante") ON DELETE CASCADE,
  CONSTRAINT "calificacionesold_id_trimestre_foreign" FOREIGN KEY ("id_trimestre") REFERENCES "trimestres" ("id_trimestre") ON DELETE CASCADE
);
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO "calificacionesold" VALUES (3,240,32,1,15,1,'Aplazado','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-06 23:44:06','2025-05-16 19:36:40');
INSERT INTO "calificacionesold" VALUES (4,240,33,1,15,1,'Aplazado','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-06 23:44:06','2025-05-16 19:36:40');
INSERT INTO "calificacionesold" VALUES (5,240,34,1,15,1,'Aplazado','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-06 23:44:06','2025-05-16 19:36:40');
INSERT INTO "calificacionesold" VALUES (6,240,35,1,15,1,'Aplazado','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-06 23:44:06','2025-05-16 19:36:40');
INSERT INTO "calificacionesold" VALUES (7,240,36,1,15,1,'Aplazado','1er Grado \'A\'',12.00,'2025-05-16',NULL,'2025-05-06 23:44:06','2025-05-16 19:36:40');
INSERT INTO "calificacionesold" VALUES (8,240,37,1,15,1,'Aplazado','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-06 23:44:06','2025-05-16 19:36:40');
INSERT INTO "calificacionesold" VALUES (9,240,38,1,15,1,'Aplazado','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-06 23:44:06','2025-05-16 19:36:40');
INSERT INTO "calificacionesold" VALUES (10,240,39,1,15,1,'Aplazado','1er Grado \'A\'',13.00,'2025-05-16',NULL,'2025-05-06 23:44:06','2025-05-16 19:36:40');
INSERT INTO "calificacionesold" VALUES (11,240,40,1,15,1,'Aplazado','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-06 23:44:06','2025-05-16 19:36:40');
INSERT INTO "calificacionesold" VALUES (12,240,41,1,15,1,'Aplazado','1er Grado \'A\'',10.00,'2025-05-16',NULL,'2025-05-06 23:44:06','2025-05-16 19:36:40');
INSERT INTO "calificacionesold" VALUES (13,241,32,1,14,3,'Aplazado','1er Grado \'A\'',10.00,'2025-05-16',NULL,'2025-05-06 23:59:09','2025-05-16 19:36:40');
INSERT INTO "calificacionesold" VALUES (14,241,33,1,14,3,'Aplazado','1er Grado \'A\'',12.00,'2025-05-16',NULL,'2025-05-06 23:59:09','2025-05-16 19:36:40');
INSERT INTO "calificacionesold" VALUES (15,241,34,1,14,3,'Aplazado','1er Grado \'A\'',9.00,'2025-05-16',NULL,'2025-05-06 23:59:09','2025-05-16 19:36:40');
INSERT INTO "calificacionesold" VALUES (16,241,35,1,14,3,'Aplazado','1er Grado \'A\'',13.00,'2025-05-16',NULL,'2025-05-06 23:59:09','2025-05-16 19:36:40');
INSERT INTO "calificacionesold" VALUES (17,241,36,1,14,3,'Aplazado','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-06 23:59:09','2025-05-16 19:36:40');
INSERT INTO "calificacionesold" VALUES (18,241,37,1,14,3,'Aplazado','1er Grado \'A\'',10.00,'2025-05-16',NULL,'2025-05-06 23:59:09','2025-05-16 19:36:40');
INSERT INTO "calificacionesold" VALUES (19,241,38,1,14,3,'Aplazado','1er Grado \'A\'',12.00,'2025-05-16',NULL,'2025-05-06 23:59:09','2025-05-16 19:36:40');
INSERT INTO "calificacionesold" VALUES (20,241,39,1,14,3,'Aplazado','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-06 23:59:09','2025-05-16 19:36:40');
INSERT INTO "calificacionesold" VALUES (21,241,40,1,14,3,'Aplazado','1er Grado \'A\'',15.00,'2025-05-16',NULL,'2025-05-06 23:59:09','2025-05-16 19:36:40');
INSERT INTO "calificacionesold" VALUES (22,241,41,1,14,3,'Aplazado','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-06 23:59:09','2025-05-16 19:36:40');
INSERT INTO "calificacionesold" VALUES (23,242,32,1,14,4,'Reprobado','1er Grado \'A\'',12.00,'2025-05-16',NULL,'2025-05-06 23:59:09','2025-05-16 19:36:40');
INSERT INTO "calificacionesold" VALUES (24,242,33,1,14,4,'Reprobado','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-06 23:59:09','2025-05-16 19:36:40');
INSERT INTO "calificacionesold" VALUES (25,242,34,1,14,4,'Reprobado','1er Grado \'A\'',8.00,'2025-05-16',NULL,'2025-05-06 23:59:09','2025-05-16 19:36:40');
INSERT INTO "calificacionesold" VALUES (26,242,35,1,14,4,'Reprobado','1er Grado \'A\'',12.00,'2025-05-16',NULL,'2025-05-06 23:59:09','2025-05-16 19:36:40');
INSERT INTO "calificacionesold" VALUES (27,242,36,1,14,4,'Reprobado','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-06 23:59:09','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (28,242,37,1,14,4,'Reprobado','1er Grado \'A\'',10.00,'2025-05-16',NULL,'2025-05-06 23:59:09','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (29,242,38,1,14,4,'Reprobado','1er Grado \'A\'',10.00,'2025-05-16',NULL,'2025-05-06 23:59:09','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (30,242,39,1,14,4,'Reprobado','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-06 23:59:09','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (31,242,40,1,14,4,'Reprobado','1er Grado \'A\'',13.00,'2025-05-16',NULL,'2025-05-06 23:59:09','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (32,242,41,1,14,4,'Reprobado','1er Grado \'A\'',10.00,'2025-05-16',NULL,'2025-05-06 23:59:09','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (33,243,32,1,13,2,'Aplazado','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (34,243,33,1,13,2,'Aplazado','1er Grado \'A\'',10.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (35,243,34,1,13,2,'Aplazado','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (36,243,35,1,13,2,'Aplazado','1er Grado \'A\'',13.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (37,243,36,1,13,2,'Aplazado','1er Grado \'A\'',10.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (38,243,37,1,13,2,'Aplazado','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (39,243,38,1,13,2,'Aplazado','1er Grado \'A\'',12.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (40,243,39,1,13,2,'Aplazado','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (41,243,40,1,13,2,'Aplazado','1er Grado \'A\'',12.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (42,243,41,1,13,2,'Aplazado','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (43,244,32,1,16,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (44,244,33,1,16,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (45,244,34,1,16,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (46,244,35,1,16,0,'Promovido','1er Grado \'A\'',16.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (47,244,36,1,16,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (48,244,37,1,16,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (49,244,38,1,16,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (50,244,39,1,16,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (51,244,40,1,16,0,'Promovido','1er Grado \'A\'',16.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (52,244,41,1,16,0,'Promovido','1er Grado \'A\'',17.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (53,245,32,1,14,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (54,245,33,1,14,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (55,245,34,1,14,0,'Promovido','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (56,245,35,1,14,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (57,245,36,1,14,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (58,245,37,1,14,0,'Promovido','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (59,245,38,1,14,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (60,245,39,1,14,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (61,245,40,1,14,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (62,245,41,1,14,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (63,246,32,1,15,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (64,246,33,1,15,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (65,246,34,1,15,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (66,246,35,1,15,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (67,246,36,1,15,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (68,246,37,1,15,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (69,246,38,1,15,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (70,246,39,1,15,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (71,246,40,1,15,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (72,246,41,1,15,0,'Promovido','1er Grado \'A\'',16.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (73,248,32,1,17,0,'Promovido','1er Grado \'A\'',17.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (74,248,33,1,17,0,'Promovido','1er Grado \'A\'',17.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (75,248,34,1,17,0,'Promovido','1er Grado \'A\'',18.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (76,248,35,1,17,0,'Promovido','1er Grado \'A\'',18.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (77,248,36,1,17,0,'Promovido','1er Grado \'A\'',16.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (78,248,37,1,17,0,'Promovido','1er Grado \'A\'',16.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (79,248,38,1,17,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (80,248,39,1,17,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (81,248,40,1,17,0,'Promovido','1er Grado \'A\'',19.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (82,248,41,1,17,0,'Promovido','1er Grado \'A\'',16.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (83,249,32,1,16,0,'Promovido','1er Grado \'A\'',16.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (84,249,33,1,16,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (85,249,34,1,16,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (86,249,35,1,16,0,'Promovido','1er Grado \'A\'',16.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (87,249,36,1,16,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (88,249,37,1,16,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (89,249,38,1,16,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (90,249,39,1,16,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (91,249,40,1,16,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (92,249,41,1,16,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (93,250,32,1,17,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (94,250,33,1,17,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (95,250,34,1,17,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (96,250,35,1,17,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (97,250,36,1,17,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (98,250,37,1,17,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (99,250,38,1,17,0,'Promovido','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (100,250,39,1,17,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (101,250,40,1,17,0,'Promovido','1er Grado \'A\'',17.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (102,250,41,1,17,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (103,251,32,1,13,0,'Promovido','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (104,251,33,1,13,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (105,251,34,1,13,0,'Promovido','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (106,251,35,1,13,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (107,251,36,1,13,0,'Promovido','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (108,251,37,1,13,0,'Promovido','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (109,251,38,1,13,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (110,251,39,1,13,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (111,251,40,1,13,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (112,251,41,1,13,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (113,252,32,1,15,1,'Aplazado','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (114,252,33,1,15,1,'Aplazado','1er Grado \'A\'',12.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (115,252,34,1,15,1,'Aplazado','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (116,252,35,1,15,1,'Aplazado','1er Grado \'A\'',13.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (117,252,36,1,15,1,'Aplazado','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (118,252,37,1,15,1,'Aplazado','1er Grado \'A\'',10.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (119,252,38,1,15,1,'Aplazado','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (120,252,39,1,15,1,'Aplazado','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (121,252,40,1,15,1,'Aplazado','1er Grado \'A\'',15.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (122,252,41,1,15,1,'Aplazado','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-08 19:44:35','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (123,253,32,1,16,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (124,253,33,1,16,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (125,253,34,1,16,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (126,253,36,1,16,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (127,253,37,1,16,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (128,253,38,1,16,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (129,253,39,1,16,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (130,253,40,1,16,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (131,253,41,1,16,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (132,254,32,1,14,1,'Aplazado','1er Grado \'A\'',12.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (133,254,33,1,14,1,'Aplazado','1er Grado \'A\'',12.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (134,254,34,1,14,1,'Aplazado','1er Grado \'A\'',9.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (135,254,35,1,14,1,'Aplazado','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (136,254,36,1,14,1,'Aplazado','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (137,254,37,1,14,1,'Aplazado','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (138,254,38,1,14,1,'Aplazado','1er Grado \'A\'',12.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (139,254,39,1,14,1,'Aplazado','1er Grado \'A\'',13.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (140,254,40,1,14,1,'Aplazado','1er Grado \'A\'',15.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (141,254,41,1,14,1,'Aplazado','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (142,255,32,1,14,0,'Promovido','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (143,255,33,1,14,0,'Promovido','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (144,255,34,1,14,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (145,255,35,1,14,0,'Promovido','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (146,255,36,1,14,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (147,255,37,1,14,0,'Promovido','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (148,255,38,1,14,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (149,255,39,1,14,0,'Promovido','1er Grado \'A\'',16.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (150,255,40,1,14,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (151,255,41,1,14,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (152,256,32,1,15,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (153,256,33,1,15,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (154,256,34,1,15,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (155,256,35,1,15,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (156,256,36,1,15,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (157,256,37,1,15,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (158,256,38,1,15,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (159,256,39,1,15,0,'Promovido','1er Grado \'A\'',16.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (160,256,40,1,15,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (161,256,41,1,15,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (162,257,32,1,14,3,'Aplazado','1er Grado \'A\'',10.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (163,257,33,1,14,3,'Aplazado','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (164,257,34,1,14,3,'Aplazado','1er Grado \'A\'',8.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (165,257,35,1,14,3,'Aplazado','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (166,257,36,1,14,3,'Aplazado','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (167,257,37,1,14,3,'Aplazado','1er Grado \'A\'',9.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (168,257,38,1,14,3,'Aplazado','1er Grado \'A\'',13.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (169,257,39,1,14,3,'Aplazado','1er Grado \'A\'',13.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (170,257,40,1,14,3,'Aplazado','1er Grado \'A\'',13.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (171,257,41,1,14,3,'Aplazado','1er Grado \'A\'',12.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (172,258,32,1,16,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (173,258,33,1,16,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (174,258,34,1,16,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (175,258,35,1,16,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (176,258,36,1,16,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (177,258,37,1,16,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (178,258,38,1,16,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (179,258,39,1,16,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (180,258,40,1,16,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (181,258,41,1,16,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (182,259,32,1,15,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (183,259,33,1,15,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (184,259,34,1,15,0,'Promovido','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (185,259,35,1,15,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (186,259,36,1,15,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (187,259,37,1,15,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (188,259,38,1,15,0,'Promovido','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (189,259,39,1,15,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (190,259,40,1,15,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (191,259,41,1,15,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (192,260,32,1,13,2,'Aplazado','1er Grado \'A\'',9.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (193,260,33,1,13,2,'Aplazado','1er Grado \'A\'',12.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (194,260,34,1,13,2,'Aplazado','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (195,260,35,1,13,2,'Aplazado','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (196,260,36,1,13,2,'Aplazado','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (197,260,37,1,13,2,'Aplazado','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (198,260,38,1,13,2,'Aplazado','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (199,260,39,1,13,2,'Aplazado','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (200,260,40,1,13,2,'Aplazado','1er Grado \'A\'',12.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (201,260,41,1,13,2,'Aplazado','1er Grado \'A\'',10.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (202,261,32,1,16,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (203,261,33,1,16,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (204,261,34,1,16,0,'Promovido','1er Grado \'A\'',16.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (205,261,35,1,16,0,'Promovido','1er Grado \'A\'',18.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (206,261,36,1,16,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (207,261,37,1,16,0,'Promovido','1er Grado \'A\'',16.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (208,261,38,1,16,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (209,261,39,1,16,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (210,261,40,1,16,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (211,261,41,1,16,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (212,262,32,1,16,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (213,262,33,1,16,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (214,262,34,1,16,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (215,262,35,1,16,0,'Promovido','1er Grado \'A\'',16.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (216,262,36,1,16,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (217,262,37,1,16,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (218,262,38,1,16,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (219,262,39,1,16,0,'Promovido','1er Grado \'A\'',16.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (220,262,40,1,16,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (221,262,41,1,16,0,'Promovido','1er Grado \'A\'',16.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (222,263,32,1,14,1,'Aplazado','1er Grado \'A\'',12.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (223,263,33,1,14,1,'Aplazado','1er Grado \'A\'',12.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (224,263,34,1,14,1,'Aplazado','1er Grado \'A\'',9.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (225,263,35,1,14,1,'Aplazado','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (226,263,36,1,14,1,'Aplazado','1er Grado \'A\'',12.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (227,263,37,1,14,1,'Aplazado','1er Grado \'A\'',12.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (228,263,38,1,14,1,'Aplazado','1er Grado \'A\'',12.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (229,263,39,1,14,1,'Aplazado','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (230,263,40,1,14,1,'Aplazado','1er Grado \'A\'',13.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (231,263,41,1,14,1,'Aplazado','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (232,264,32,1,15,0,'Promovido','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (233,264,33,1,15,0,'Promovido','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (234,264,34,1,15,0,'Promovido','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (235,264,35,1,15,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (236,264,36,1,15,0,'Promovido','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (237,264,37,1,15,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (238,264,38,1,15,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (239,264,39,1,15,0,'Promovido','1er Grado \'A\'',16.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (240,264,40,1,15,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (241,264,41,1,15,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (242,265,32,1,15,2,'Aplazado','1er Grado \'A\'',13.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (243,265,33,1,15,2,'Aplazado','1er Grado \'A\'',12.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (244,265,34,1,15,2,'Aplazado','1er Grado \'A\'',10.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (245,265,35,1,15,2,'Aplazado','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (246,265,36,1,15,2,'Aplazado','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (247,265,37,1,15,2,'Aplazado','1er Grado \'A\'',12.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (248,265,38,1,15,2,'Aplazado','1er Grado \'A\'',10.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (249,265,39,1,15,2,'Aplazado','1er Grado \'A\'',13.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (250,265,40,1,15,2,'Aplazado','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (251,265,41,1,15,2,'Aplazado','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (252,266,32,1,16,2,'Aplazado','1er Grado \'A\'',12.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (253,266,33,1,16,2,'Aplazado','1er Grado \'A\'',12.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (254,266,34,1,16,2,'Aplazado','1er Grado \'A\'',10.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (255,266,35,1,16,2,'Aplazado','1er Grado \'A\'',13.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (256,266,36,1,16,2,'Aplazado','1er Grado \'A\'',10.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (257,266,37,1,16,2,'Aplazado','1er Grado \'A\'',12.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (258,266,38,1,16,2,'Aplazado','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (259,266,39,1,16,2,'Aplazado','1er Grado \'A\'',15.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (260,266,40,1,16,2,'Aplazado','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (261,266,41,1,16,2,'Aplazado','1er Grado \'A\'',12.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (262,267,32,1,14,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (263,267,33,1,14,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (264,267,34,1,14,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (265,267,35,1,14,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (266,267,36,1,14,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (267,267,37,1,14,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (268,267,38,1,14,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (269,267,39,1,14,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (270,267,40,1,14,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (271,267,41,1,14,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (272,268,32,1,14,0,'Promovido','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (273,268,33,1,14,0,'Promovido','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (274,268,34,1,14,0,'Promovido','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (275,268,35,1,14,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (276,268,36,1,14,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (277,268,37,1,14,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (278,268,38,1,14,0,'Promovido','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (279,268,39,1,14,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (280,268,40,1,14,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (281,268,41,1,14,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (282,269,32,1,16,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (283,269,33,1,16,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (284,269,34,1,16,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (285,269,36,1,16,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (286,269,37,1,16,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (287,269,38,1,16,0,'Promovido','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (288,269,39,1,16,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (289,269,40,1,16,0,'Promovido','1er Grado \'A\'',16.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (290,269,41,1,16,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (291,270,32,1,16,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (292,270,33,1,16,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (293,270,34,1,16,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (294,270,35,1,16,0,'Promovido','1er Grado \'A\'',16.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (295,270,36,1,16,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (296,270,37,1,16,0,'Promovido','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (297,270,38,1,16,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (298,270,39,1,16,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (299,270,40,1,16,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (300,270,41,1,16,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (301,271,32,1,15,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (302,271,33,1,15,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (303,271,34,1,15,0,'Promovido','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (304,271,35,1,15,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (305,271,36,1,15,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (306,271,37,1,15,0,'Promovido','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (307,271,38,1,15,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (308,271,39,1,15,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (309,271,40,1,15,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (310,271,41,1,15,0,'Promovido','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (311,272,32,1,16,1,'Aplazado','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (312,272,33,1,16,1,'Aplazado','1er Grado \'A\'',12.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (313,272,34,1,16,1,'Aplazado','1er Grado \'A\'',10.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (314,272,35,1,16,1,'Aplazado','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (315,272,36,1,16,1,'Aplazado','1er Grado \'A\'',12.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (316,272,37,1,16,1,'Aplazado','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (317,272,38,1,16,1,'Aplazado','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (318,272,39,1,16,1,'Aplazado','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (319,272,40,1,16,1,'Aplazado','1er Grado \'A\'',15.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (320,272,41,1,16,1,'Aplazado','1er Grado \'A\'',13.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (321,273,32,1,13,4,'Reprobado','1er Grado \'A\'',10.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (322,273,33,1,13,4,'Reprobado','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (323,273,34,1,13,4,'Reprobado','1er Grado \'A\'',8.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (324,273,35,1,13,4,'Reprobado','1er Grado \'A\'',10.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (325,273,36,1,13,4,'Reprobado','1er Grado \'A\'',8.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (326,273,37,1,13,4,'Reprobado','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (327,273,38,1,13,4,'Reprobado','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (328,273,39,1,13,4,'Reprobado','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (329,273,40,1,13,4,'Reprobado','1er Grado \'A\'',13.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (330,273,41,1,13,4,'Reprobado','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (331,274,32,1,14,0,'Promovido','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (332,274,33,1,14,0,'Promovido','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (333,274,34,1,14,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (334,274,35,1,14,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (335,274,36,1,14,0,'Promovido','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (336,274,37,1,14,0,'Promovido','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (337,274,38,1,14,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (338,274,39,1,14,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (339,274,40,1,14,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (340,274,41,1,14,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (341,275,32,1,14,5,'Reprobado','1er Grado \'A\'',10.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (342,275,33,1,14,5,'Reprobado','1er Grado \'A\'',12.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (343,275,34,1,14,5,'Reprobado','1er Grado \'A\'',10.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (344,275,35,1,14,5,'Reprobado','1er Grado \'A\'',13.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (345,275,36,1,14,5,'Reprobado','1er Grado \'A\'',10.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (346,275,37,1,14,5,'Reprobado','1er Grado \'A\'',10.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (347,275,38,1,14,5,'Reprobado','1er Grado \'A\'',9.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (348,275,39,1,14,5,'Reprobado','1er Grado \'A\'',13.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (349,275,40,1,14,5,'Reprobado','1er Grado \'A\'',13.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (350,275,41,1,14,5,'Reprobado','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (351,276,32,1,13,5,'Reprobado','1er Grado \'A\'',10.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (352,276,33,1,13,5,'Reprobado','1er Grado \'A\'',12.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (353,276,34,1,13,5,'Reprobado','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (354,276,35,1,13,5,'Reprobado','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (355,276,36,1,13,5,'Reprobado','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (356,276,37,1,13,5,'Reprobado','1er Grado \'A\'',10.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (357,276,38,1,13,5,'Reprobado','1er Grado \'A\'',9.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (358,276,39,1,13,5,'Reprobado','1er Grado \'A\'',13.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (359,276,40,1,13,5,'Reprobado','1er Grado \'A\'',10.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (360,276,41,1,13,5,'Reprobado','1er Grado \'A\'',10.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (361,277,32,1,14,2,'Aplazado','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (362,277,33,1,14,2,'Aplazado','1er Grado \'A\'',10.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (363,277,34,1,14,2,'Aplazado','1er Grado \'A\'',9.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (364,277,35,1,14,2,'Aplazado','1er Grado \'A\'',12.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (365,277,36,1,14,2,'Aplazado','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (366,277,37,1,14,2,'Aplazado','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (367,277,38,1,14,2,'Aplazado','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (368,277,39,1,14,2,'Aplazado','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (369,277,40,1,14,2,'Aplazado','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (370,277,41,1,14,2,'Aplazado','1er Grado \'A\'',12.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (371,278,32,1,16,1,'Aplazado','1er Grado \'A\'',13.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (372,278,33,1,16,1,'Aplazado','1er Grado \'A\'',13.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (373,278,34,1,16,1,'Aplazado','1er Grado \'A\'',10.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (374,278,35,1,16,1,'Aplazado','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (375,278,36,1,16,1,'Aplazado','1er Grado \'A\'',13.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (376,278,37,1,16,1,'Aplazado','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (377,278,38,1,16,1,'Aplazado','1er Grado \'A\'',13.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (378,278,39,1,16,1,'Aplazado','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (379,278,40,1,16,1,'Aplazado','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (380,278,41,1,16,1,'Aplazado','1er Grado \'A\'',15.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (381,279,32,1,14,2,'Aplazado','1er Grado \'A\'',12.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (382,279,33,1,14,2,'Aplazado','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (383,279,34,1,14,2,'Aplazado','1er Grado \'A\'',9.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (384,279,35,1,14,2,'Aplazado','1er Grado \'A\'',13.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (385,279,36,1,14,2,'Aplazado','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (386,279,37,1,14,2,'Aplazado','1er Grado \'A\'',10.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (387,279,38,1,14,2,'Aplazado','1er Grado \'A\'',13.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (388,279,39,1,14,2,'Aplazado','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (389,279,40,1,14,2,'Aplazado','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (390,279,41,1,14,2,'Aplazado','1er Grado \'A\'',12.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (391,280,32,1,15,1,'Aplazado','1er Grado \'A\'',12.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (392,280,33,1,15,1,'Aplazado','1er Grado \'A\'',12.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (393,280,34,1,15,1,'Aplazado','1er Grado \'A\'',9.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (394,280,35,1,15,1,'Aplazado','1er Grado \'A\'',12.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (395,280,36,1,15,1,'Aplazado','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (396,280,37,1,15,1,'Aplazado','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (397,280,38,1,15,1,'Aplazado','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (398,280,39,1,15,1,'Aplazado','1er Grado \'A\'',13.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (399,280,40,1,15,1,'Aplazado','1er Grado \'A\'',12.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (400,280,41,1,15,1,'Aplazado','1er Grado \'A\'',12.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (401,281,32,1,17,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (402,281,33,1,17,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (403,281,34,1,17,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (404,281,35,1,17,0,'Promovido','1er Grado \'A\'',17.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (405,281,36,1,17,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (406,281,37,1,17,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (407,281,38,1,17,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (408,281,39,1,17,0,'Promovido','1er Grado \'A\'',16.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (409,281,40,1,17,0,'Promovido','1er Grado \'A\'',16.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (410,281,41,1,17,0,'Promovido','1er Grado \'A\'',17.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (411,282,32,1,15,1,'Aplazado','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (412,282,33,1,15,1,'Aplazado','1er Grado \'A\'',13.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (413,282,34,1,15,1,'Aplazado','1er Grado \'A\'',11.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (414,282,35,1,15,1,'Aplazado','1er Grado \'A\'',15.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (415,282,36,1,15,1,'Aplazado','1er Grado \'A\'',12.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (416,282,37,1,15,1,'Aplazado','1er Grado \'A\'',10.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (417,282,38,1,15,1,'Aplazado','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (418,282,39,1,15,1,'Aplazado','1er Grado \'A\'',15.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (419,282,40,1,15,1,'Aplazado','1er Grado \'A\'',13.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (420,282,41,1,15,1,'Aplazado','1er Grado \'A\'',12.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (421,283,32,1,17,0,'Promovido','1er Grado \'A\'',16.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (422,283,33,1,17,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (423,283,34,1,17,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (424,283,35,1,17,0,'Promovido','1er Grado \'A\'',18.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (425,283,36,1,17,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (426,283,37,1,17,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (427,283,38,1,17,0,'Promovido','1er Grado \'A\'',16.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (428,283,39,1,17,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (429,283,40,1,17,0,'Promovido','1er Grado \'A\'',17.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (430,283,41,1,17,0,'Promovido','1er Grado \'A\'',17.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (431,284,32,1,16,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (432,284,33,1,16,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (433,284,34,1,16,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (434,284,35,1,16,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (435,284,36,1,16,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (436,284,37,1,16,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (437,284,38,1,16,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (438,284,39,1,16,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (439,284,40,1,16,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (440,284,41,1,16,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (441,285,32,1,16,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (442,285,33,1,16,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (443,285,34,1,16,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (444,285,35,1,16,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (445,285,36,1,16,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (446,285,37,1,16,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (447,285,38,1,16,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (448,285,39,1,16,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (449,285,40,1,16,0,'Promovido','1er Grado \'A\'',16.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (450,285,41,1,16,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-16',NULL,'2025-05-08 20:06:39','2025-05-16 19:36:42');
INSERT INTO "calificacionesold" VALUES (451,247,32,1,0,1,'Trasladado','1er Grado \'A\'',0.00,'2025-05-16','Trasladada al C.E \"Contralmirante Villar\" Tumbes 14-06-99','2025-05-15 22:19:44','2025-05-16 19:36:41');
INSERT INTO "calificacionesold" VALUES (452,286,42,1,15,1,'Aplazado','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (453,286,43,1,15,1,'Aplazado','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (454,286,44,1,15,1,'Aplazado','1er Grado \'B\'',10.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (455,286,45,1,15,1,'Aplazado','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (456,286,46,1,15,1,'Aplazado','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (457,286,47,1,15,1,'Aplazado','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (458,286,48,1,15,1,'Aplazado','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (459,286,49,1,15,1,'Aplazado','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (460,286,50,1,15,1,'Aplazado','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (461,286,51,1,15,1,'Aplazado','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (462,287,42,1,15,1,'Aplazado','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (463,287,43,1,15,1,'Aplazado','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (464,287,44,1,15,1,'Aplazado','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (465,287,45,1,15,1,'Aplazado','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (466,287,46,1,15,1,'Aplazado','1er Grado \'B\'',10.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (467,287,47,1,15,1,'Aplazado','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (468,287,48,1,15,1,'Aplazado','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (469,287,49,1,15,1,'Aplazado','1er Grado \'B\'',15.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (470,287,50,1,15,1,'Aplazado','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (471,287,51,1,15,1,'Aplazado','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (472,288,42,1,15,1,'Aplazado','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (473,288,43,1,15,1,'Aplazado','1er Grado \'B\'',9.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (474,288,44,1,15,1,'Aplazado','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (475,288,45,1,15,1,'Aplazado','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (476,288,46,1,15,1,'Aplazado','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (477,288,47,1,15,1,'Aplazado','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (478,288,48,1,15,1,'Aplazado','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (479,288,49,1,15,1,'Aplazado','1er Grado \'B\'',14.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (480,288,50,1,15,1,'Aplazado','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (481,288,51,1,15,1,'Aplazado','1er Grado \'B\'',14.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (482,289,42,1,18,0,'Promovido','1er Grado \'B\'',14.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (483,289,43,1,18,0,'Promovido','1er Grado \'B\'',14.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (484,289,44,1,18,0,'Promovido','1er Grado \'B\'',14.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (485,289,45,1,18,0,'Promovido','1er Grado \'B\'',16.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (486,289,46,1,18,0,'Promovido','1er Grado \'B\'',15.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (487,289,47,1,18,0,'Promovido','1er Grado \'B\'',15.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (488,289,48,1,18,0,'Promovido','1er Grado \'B\'',14.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (489,289,49,1,18,0,'Promovido','1er Grado \'B\'',15.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (490,289,50,1,18,0,'Promovido','1er Grado \'B\'',16.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (491,289,51,1,18,0,'Promovido','1er Grado \'B\'',17.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (492,290,42,1,16,0,'Promovido','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (493,290,43,1,16,0,'Promovido','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (494,290,44,1,16,0,'Promovido','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (495,290,45,1,16,0,'Promovido','1er Grado \'B\'',16.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (496,290,46,1,16,0,'Promovido','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (497,290,47,1,16,0,'Promovido','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (498,290,48,1,16,0,'Promovido','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (499,290,49,1,16,0,'Promovido','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (500,290,50,1,16,0,'Promovido','1er Grado \'B\'',14.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (501,290,51,1,16,0,'Promovido','1er Grado \'B\'',14.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (502,291,42,1,15,0,'Promovido','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (503,291,43,1,15,0,'Promovido','1er Grado \'B\'',14.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (504,291,44,1,15,0,'Promovido','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (505,291,45,1,15,0,'Promovido','1er Grado \'B\'',16.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (506,291,46,1,15,0,'Promovido','1er Grado \'B\'',14.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (507,291,47,1,15,0,'Promovido','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (508,291,48,1,15,0,'Promovido','1er Grado \'B\'',14.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (509,291,49,1,15,0,'Promovido','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (510,291,50,1,15,0,'Promovido','1er Grado \'B\'',15.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (511,291,51,1,15,0,'Promovido','1er Grado \'B\'',14.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (512,292,42,1,16,0,'Promovido','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (513,292,43,1,16,0,'Promovido','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (514,292,44,1,16,0,'Promovido','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (515,292,45,1,16,0,'Promovido','1er Grado \'B\'',16.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (516,292,46,1,16,0,'Promovido','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (517,292,47,1,16,0,'Promovido','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (518,292,48,1,16,0,'Promovido','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (519,292,49,1,16,0,'Promovido','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (520,292,50,1,16,0,'Promovido','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (521,292,51,1,16,0,'Promovido','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (522,293,42,1,17,0,'Promovido','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (523,293,43,1,17,0,'Promovido','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (524,293,44,1,17,0,'Promovido','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (525,293,45,1,17,0,'Promovido','1er Grado \'B\'',16.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (526,293,46,1,17,0,'Promovido','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (527,293,47,1,17,0,'Promovido','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (528,293,48,1,17,0,'Promovido','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (529,293,49,1,17,0,'Promovido','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (530,293,50,1,17,0,'Promovido','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (531,293,51,1,17,0,'Promovido','1er Grado \'B\'',15.00,'2025-05-21',NULL,'2025-05-21 18:59:39','2025-05-21 18:59:39');
INSERT INTO "calificacionesold" VALUES (532,294,42,1,0,2,'Retirado','1er Grado \'B\'',0.00,'2025-05-21','Retirado por 30% de inasistencias 14/07/1999 D.D. N.º 16','2025-05-21 19:01:11','2025-05-21 19:21:53');
INSERT INTO "calificacionesold" VALUES (533,296,42,1,0,2,'Retirado','1er Grado \'B\'',0.00,'2025-05-21','Retirado por 30% de inasistencias 14/07/1999 D.D. N.º 17','2025-05-21 19:02:36','2025-05-21 19:21:53');
INSERT INTO "calificacionesold" VALUES (534,295,42,1,15,0,'Promovido','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 19:21:13','2025-05-21 19:21:13');
INSERT INTO "calificacionesold" VALUES (535,295,43,1,15,0,'Promovido','1er Grado \'B\'',14.00,'2025-05-21',NULL,'2025-05-21 19:21:13','2025-05-21 19:21:13');
INSERT INTO "calificacionesold" VALUES (536,295,44,1,15,0,'Promovido','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 19:21:13','2025-05-21 19:21:13');
INSERT INTO "calificacionesold" VALUES (537,295,45,1,15,0,'Promovido','1er Grado \'B\'',16.00,'2025-05-21',NULL,'2025-05-21 19:21:13','2025-05-21 19:21:13');
INSERT INTO "calificacionesold" VALUES (538,295,46,1,15,0,'Promovido','1er Grado \'B\'',14.00,'2025-05-21',NULL,'2025-05-21 19:21:13','2025-05-21 19:21:13');
INSERT INTO "calificacionesold" VALUES (539,295,47,1,15,0,'Promovido','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 19:21:13','2025-05-21 19:21:13');
INSERT INTO "calificacionesold" VALUES (540,295,48,1,15,0,'Promovido','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 19:21:13','2025-05-21 19:21:13');
INSERT INTO "calificacionesold" VALUES (541,295,49,1,15,0,'Promovido','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 19:21:13','2025-05-21 19:21:13');
INSERT INTO "calificacionesold" VALUES (542,295,50,1,15,0,'Promovido','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 19:21:13','2025-05-21 19:21:13');
INSERT INTO "calificacionesold" VALUES (543,295,51,1,15,0,'Promovido','1er Grado \'B\'',15.00,'2025-05-21',NULL,'2025-05-21 19:21:13','2025-05-21 19:21:13');
INSERT INTO "calificacionesold" VALUES (544,297,42,1,14,0,'Promovido','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 19:21:13','2025-05-21 19:21:13');
INSERT INTO "calificacionesold" VALUES (545,297,43,1,14,0,'Promovido','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 19:21:13','2025-05-21 19:21:13');
INSERT INTO "calificacionesold" VALUES (546,297,44,1,14,0,'Promovido','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 19:21:13','2025-05-21 19:21:13');
INSERT INTO "calificacionesold" VALUES (547,297,45,1,14,0,'Promovido','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 19:21:13','2025-05-21 19:21:13');
INSERT INTO "calificacionesold" VALUES (548,297,46,1,14,0,'Promovido','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 19:21:13','2025-05-21 19:21:13');
INSERT INTO "calificacionesold" VALUES (549,297,47,1,14,0,'Promovido','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 19:21:13','2025-05-21 19:21:13');
INSERT INTO "calificacionesold" VALUES (550,297,48,1,14,0,'Promovido','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 19:21:13','2025-05-21 19:21:13');
INSERT INTO "calificacionesold" VALUES (551,297,49,1,14,0,'Promovido','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 19:21:13','2025-05-21 19:21:13');
INSERT INTO "calificacionesold" VALUES (552,297,50,1,14,0,'Promovido','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 19:21:13','2025-05-21 19:21:13');
INSERT INTO "calificacionesold" VALUES (553,297,51,1,14,0,'Promovido','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 19:21:13','2025-05-21 19:21:13');
INSERT INTO "calificacionesold" VALUES (554,298,42,1,15,1,'Aplazado','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 19:21:13','2025-05-21 19:21:13');
INSERT INTO "calificacionesold" VALUES (555,298,43,1,15,1,'Aplazado','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 19:21:13','2025-05-21 19:21:13');
INSERT INTO "calificacionesold" VALUES (556,298,44,1,15,1,'Aplazado','1er Grado \'B\'',10.00,'2025-05-21',NULL,'2025-05-21 19:21:13','2025-05-21 19:21:13');
INSERT INTO "calificacionesold" VALUES (557,298,45,1,15,1,'Aplazado','1er Grado \'B\'',15.00,'2025-05-21',NULL,'2025-05-21 19:21:13','2025-05-21 19:21:13');
INSERT INTO "calificacionesold" VALUES (558,298,46,1,15,1,'Aplazado','1er Grado \'B\'',14.00,'2025-05-21',NULL,'2025-05-21 19:21:13','2025-05-21 19:21:13');
INSERT INTO "calificacionesold" VALUES (559,298,47,1,15,1,'Aplazado','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 19:21:13','2025-05-21 19:21:13');
INSERT INTO "calificacionesold" VALUES (560,298,48,1,15,1,'Aplazado','1er Grado \'B\'',14.00,'2025-05-21',NULL,'2025-05-21 19:21:13','2025-05-21 19:21:13');
INSERT INTO "calificacionesold" VALUES (561,298,49,1,15,1,'Aplazado','1er Grado \'B\'',14.00,'2025-05-21',NULL,'2025-05-21 19:21:13','2025-05-21 19:21:13');
INSERT INTO "calificacionesold" VALUES (562,298,50,1,15,1,'Aplazado','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 19:21:13','2025-05-21 19:21:13');
INSERT INTO "calificacionesold" VALUES (563,298,51,1,15,1,'Aplazado','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 19:21:13','2025-05-21 19:21:13');
INSERT INTO "calificacionesold" VALUES (564,299,42,1,16,0,'Promovido','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 19:21:13','2025-05-21 19:21:13');
INSERT INTO "calificacionesold" VALUES (565,299,43,1,16,0,'Promovido','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 19:21:13','2025-05-21 19:21:13');
INSERT INTO "calificacionesold" VALUES (566,299,44,1,16,0,'Promovido','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 19:21:13','2025-05-21 19:21:13');
INSERT INTO "calificacionesold" VALUES (567,299,45,1,16,0,'Promovido','1er Grado \'B\'',15.00,'2025-05-21',NULL,'2025-05-21 19:21:13','2025-05-21 19:21:13');
INSERT INTO "calificacionesold" VALUES (568,299,46,1,16,0,'Promovido','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 19:21:13','2025-05-21 19:21:13');
INSERT INTO "calificacionesold" VALUES (569,299,47,1,16,0,'Promovido','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 19:21:13','2025-05-21 19:21:13');
INSERT INTO "calificacionesold" VALUES (570,299,48,1,16,0,'Promovido','1er Grado \'B\'',14.00,'2025-05-21',NULL,'2025-05-21 19:21:13','2025-05-21 19:21:13');
INSERT INTO "calificacionesold" VALUES (571,299,49,1,16,0,'Promovido','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 19:21:13','2025-05-21 19:21:13');
INSERT INTO "calificacionesold" VALUES (572,299,50,1,16,0,'Promovido','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 19:21:13','2025-05-21 19:21:13');
INSERT INTO "calificacionesold" VALUES (573,299,51,1,16,0,'Promovido','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 19:21:13','2025-05-21 19:21:13');
INSERT INTO "calificacionesold" VALUES (574,300,42,1,14,0,'Promovido','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 19:21:13','2025-05-21 19:21:13');
INSERT INTO "calificacionesold" VALUES (575,300,43,1,14,0,'Promovido','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 19:21:13','2025-05-21 19:21:13');
INSERT INTO "calificacionesold" VALUES (576,300,44,1,14,0,'Promovido','1er Grado \'B\'',14.00,'2025-05-21',NULL,'2025-05-21 19:21:13','2025-05-21 19:21:13');
INSERT INTO "calificacionesold" VALUES (577,300,45,1,14,0,'Promovido','1er Grado \'B\'',14.00,'2025-05-21',NULL,'2025-05-21 19:21:13','2025-05-21 19:21:13');
INSERT INTO "calificacionesold" VALUES (578,300,46,1,14,0,'Promovido','1er Grado \'B\'',14.00,'2025-05-21',NULL,'2025-05-21 19:21:13','2025-05-21 19:21:13');
INSERT INTO "calificacionesold" VALUES (579,300,47,1,14,0,'Promovido','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 19:21:13','2025-05-21 19:21:13');
INSERT INTO "calificacionesold" VALUES (580,300,48,1,14,0,'Promovido','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 19:21:13','2025-05-21 19:21:13');
INSERT INTO "calificacionesold" VALUES (581,300,49,1,14,0,'Promovido','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 19:21:13','2025-05-21 19:21:13');
INSERT INTO "calificacionesold" VALUES (582,300,50,1,14,0,'Promovido','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 19:21:13','2025-05-21 19:21:13');
INSERT INTO "calificacionesold" VALUES (583,300,51,1,14,0,'Promovido','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 19:21:13','2025-05-21 19:21:13');
INSERT INTO "calificacionesold" VALUES (584,301,42,1,17,0,'Promovido','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 19:21:13','2025-05-21 19:21:13');
INSERT INTO "calificacionesold" VALUES (585,301,43,1,17,0,'Promovido','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 19:21:13','2025-05-21 19:21:13');
INSERT INTO "calificacionesold" VALUES (586,301,44,1,17,0,'Promovido','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 19:21:13','2025-05-21 19:21:13');
INSERT INTO "calificacionesold" VALUES (587,301,45,1,17,0,'Promovido','1er Grado \'B\'',15.00,'2025-05-21',NULL,'2025-05-21 19:21:13','2025-05-21 19:21:13');
INSERT INTO "calificacionesold" VALUES (588,301,46,1,17,0,'Promovido','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 19:21:13','2025-05-21 19:21:13');
INSERT INTO "calificacionesold" VALUES (589,301,47,1,17,0,'Promovido','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 19:21:13','2025-05-21 19:21:13');
INSERT INTO "calificacionesold" VALUES (590,301,48,1,17,0,'Promovido','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 19:21:13','2025-05-21 19:21:13');
INSERT INTO "calificacionesold" VALUES (591,301,49,1,17,0,'Promovido','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 19:21:13','2025-05-21 19:21:13');
INSERT INTO "calificacionesold" VALUES (592,301,50,1,17,0,'Promovido','1er Grado \'B\'',15.00,'2025-05-21',NULL,'2025-05-21 19:21:13','2025-05-21 19:21:13');
INSERT INTO "calificacionesold" VALUES (593,301,51,1,17,0,'Promovido','1er Grado \'B\'',15.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (594,302,42,1,15,1,'Aplazado','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (595,302,43,1,15,1,'Aplazado','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (596,302,44,1,15,1,'Aplazado','1er Grado \'B\'',9.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (597,302,45,1,15,1,'Aplazado','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (598,302,46,1,15,1,'Aplazado','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (599,302,47,1,15,1,'Aplazado','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (600,302,48,1,15,1,'Aplazado','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (601,302,49,1,15,1,'Aplazado','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (602,302,50,1,15,1,'Aplazado','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (603,302,51,1,15,1,'Aplazado','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (604,303,42,1,14,0,'Promovido','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (605,303,43,1,14,0,'Promovido','1er Grado \'B\'',14.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (606,303,44,1,14,0,'Promovido','1er Grado \'B\'',14.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (607,303,45,1,14,0,'Promovido','1er Grado \'B\'',15.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (608,303,46,1,14,0,'Promovido','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (609,303,47,1,14,0,'Promovido','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (610,303,48,1,14,0,'Promovido','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (611,303,49,1,14,0,'Promovido','1er Grado \'B\'',14.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (612,303,50,1,14,0,'Promovido','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (613,303,51,1,14,0,'Promovido','1er Grado \'B\'',15.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (614,304,42,1,15,0,'Promovido','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (615,304,43,1,15,0,'Promovido','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (616,304,44,1,15,0,'Promovido','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (617,304,45,1,15,0,'Promovido','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (618,304,46,1,15,0,'Promovido','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (619,304,47,1,15,0,'Promovido','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (620,304,48,1,15,0,'Promovido','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (621,304,49,1,15,0,'Promovido','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (622,304,50,1,15,0,'Promovido','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (623,304,51,1,15,0,'Promovido','1er Grado \'B\'',14.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (624,305,42,1,16,1,'Aplazado','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (625,305,43,1,16,1,'Aplazado','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (626,305,44,1,16,1,'Aplazado','1er Grado \'B\'',10.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (627,305,45,1,16,1,'Aplazado','1er Grado \'B\'',14.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (628,305,46,1,16,1,'Aplazado','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (629,305,47,1,16,1,'Aplazado','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (630,305,48,1,16,1,'Aplazado','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (631,305,49,1,16,1,'Aplazado','1er Grado \'B\'',15.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (632,305,50,1,16,1,'Aplazado','1er Grado \'B\'',14.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (633,305,51,1,16,1,'Aplazado','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (634,306,42,1,14,2,'Aplazado','1er Grado \'B\'',10.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (635,306,43,1,14,2,'Aplazado','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (636,306,44,1,14,2,'Aplazado','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (637,306,45,1,14,2,'Aplazado','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (638,306,46,1,14,2,'Aplazado','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (639,306,47,1,14,2,'Aplazado','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (640,306,48,1,14,2,'Aplazado','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (641,306,49,1,14,2,'Aplazado','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (642,306,50,1,14,2,'Aplazado','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (643,306,51,1,14,2,'Aplazado','1er Grado \'B\'',10.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (644,307,42,1,16,0,'Promovido','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (645,307,43,1,16,0,'Promovido','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (646,307,44,1,16,0,'Promovido','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (647,307,45,1,16,0,'Promovido','1er Grado \'B\'',14.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (648,307,46,1,16,0,'Promovido','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (649,307,47,1,16,0,'Promovido','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (650,307,48,1,16,0,'Promovido','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (651,307,49,1,16,0,'Promovido','1er Grado \'B\'',14.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (652,307,50,1,16,0,'Promovido','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (653,307,51,1,16,0,'Promovido','1er Grado \'B\'',14.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (654,308,42,1,17,0,'Promovido','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (655,308,43,1,17,0,'Promovido','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (656,308,44,1,17,0,'Promovido','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (657,308,45,1,17,0,'Promovido','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (658,308,46,1,17,0,'Promovido','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (659,308,47,1,17,0,'Promovido','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (660,308,48,1,17,0,'Promovido','1er Grado \'B\'',14.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (661,308,49,1,17,0,'Promovido','1er Grado \'B\'',14.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (662,308,50,1,17,0,'Promovido','1er Grado \'B\'',15.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (663,308,51,1,17,0,'Promovido','1er Grado \'B\'',14.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (664,309,42,1,15,3,'Aplazado','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (665,309,43,1,15,3,'Aplazado','1er Grado \'B\'',10.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (666,309,44,1,15,3,'Aplazado','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (667,309,45,1,15,3,'Aplazado','1er Grado \'B\'',14.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (668,309,46,1,15,3,'Aplazado','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (669,309,47,1,15,3,'Aplazado','1er Grado \'B\'',9.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (670,309,48,1,15,3,'Aplazado','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (671,309,49,1,15,3,'Aplazado','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (672,309,50,1,15,3,'Aplazado','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (673,309,51,1,15,3,'Aplazado','1er Grado \'B\'',10.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (674,310,42,1,18,0,'Promovido','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (675,310,43,1,18,0,'Promovido','1er Grado \'B\'',15.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (676,310,44,1,18,0,'Promovido','1er Grado \'B\'',14.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (677,310,45,1,18,0,'Promovido','1er Grado \'B\'',15.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (678,310,46,1,18,0,'Promovido','1er Grado \'B\'',14.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (679,310,47,1,18,0,'Promovido','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (680,310,48,1,18,0,'Promovido','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (681,310,49,1,18,0,'Promovido','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (682,310,50,1,18,0,'Promovido','1er Grado \'B\'',15.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (683,310,51,1,18,0,'Promovido','1er Grado \'B\'',19.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (684,311,42,1,18,0,'Promovido','1er Grado \'B\'',16.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (685,311,43,1,18,0,'Promovido','1er Grado \'B\'',16.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (686,311,44,1,18,0,'Promovido','1er Grado \'B\'',15.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (687,311,45,1,18,0,'Promovido','1er Grado \'B\'',18.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (688,311,46,1,18,0,'Promovido','1er Grado \'B\'',16.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (689,311,47,1,18,0,'Promovido','1er Grado \'B\'',14.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (690,311,48,1,18,0,'Promovido','1er Grado \'B\'',15.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (691,311,49,1,18,0,'Promovido','1er Grado \'B\'',14.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (692,311,50,1,18,0,'Promovido','1er Grado \'B\'',15.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (693,311,51,1,18,0,'Promovido','1er Grado \'B\'',17.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (694,312,42,1,17,0,'Promovido','1er Grado \'B\'',15.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (695,312,43,1,17,0,'Promovido','1er Grado \'B\'',15.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (696,312,44,1,17,0,'Promovido','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (697,312,45,1,17,0,'Promovido','1er Grado \'B\'',15.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (698,312,46,1,17,0,'Promovido','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (699,312,47,1,17,0,'Promovido','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (700,312,48,1,17,0,'Promovido','1er Grado \'B\'',14.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (701,312,49,1,17,0,'Promovido','1er Grado \'B\'',15.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (702,312,50,1,17,0,'Promovido','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (703,312,51,1,17,0,'Promovido','1er Grado \'B\'',15.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (704,313,42,1,17,0,'Promovido','1er Grado \'B\'',14.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (705,313,43,1,17,0,'Promovido','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (706,313,44,1,17,0,'Promovido','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (707,313,45,1,17,0,'Promovido','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (708,313,46,1,17,0,'Promovido','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (709,313,47,1,17,0,'Promovido','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (710,313,48,1,17,0,'Promovido','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (711,313,49,1,17,0,'Promovido','1er Grado \'B\'',15.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (712,313,50,1,17,0,'Promovido','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (713,313,51,1,17,0,'Promovido','1er Grado \'B\'',15.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (714,314,42,1,16,0,'Promovido','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (715,314,43,1,16,0,'Promovido','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (716,314,44,1,16,0,'Promovido','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (717,314,45,1,16,0,'Promovido','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (718,314,46,1,16,0,'Promovido','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (719,314,47,1,16,0,'Promovido','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (720,314,48,1,16,0,'Promovido','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (721,314,49,1,16,0,'Promovido','1er Grado \'B\'',16.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (722,314,50,1,16,0,'Promovido','1er Grado \'B\'',14.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (723,314,51,1,16,0,'Promovido','1er Grado \'B\'',14.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (724,315,42,1,14,2,'Aplazado','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (725,315,43,1,14,2,'Aplazado','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (726,315,44,1,14,2,'Aplazado','1er Grado \'B\'',10.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (727,315,45,1,14,2,'Aplazado','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (728,315,46,1,14,2,'Aplazado','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (729,315,47,1,14,2,'Aplazado','1er Grado \'B\'',10.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (730,315,48,1,14,2,'Aplazado','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (731,315,49,1,14,2,'Aplazado','1er Grado \'B\'',15.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (732,315,50,1,14,2,'Aplazado','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (733,315,51,1,14,2,'Aplazado','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (734,316,42,1,16,0,'Promovido','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (735,316,43,1,16,0,'Promovido','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (736,316,44,1,16,0,'Promovido','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (737,316,45,1,16,0,'Promovido','1er Grado \'B\'',15.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (738,316,46,1,16,0,'Promovido','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (739,316,47,1,16,0,'Promovido','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (740,316,48,1,16,0,'Promovido','1er Grado \'B\'',14.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (741,316,49,1,16,0,'Promovido','1er Grado \'B\'',14.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (742,316,50,1,16,0,'Promovido','1er Grado \'B\'',14.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (743,316,51,1,16,0,'Promovido','1er Grado \'B\'',15.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (744,317,42,1,17,0,'Promovido','1er Grado \'B\'',14.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (745,317,43,1,17,0,'Promovido','1er Grado \'B\'',14.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (746,317,44,1,17,0,'Promovido','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (747,317,45,1,17,0,'Promovido','1er Grado \'B\'',17.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (748,317,46,1,17,0,'Promovido','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (749,317,47,1,17,0,'Promovido','1er Grado \'B\'',14.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (750,317,48,1,17,0,'Promovido','1er Grado \'B\'',14.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (751,317,49,1,17,0,'Promovido','1er Grado \'B\'',15.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (752,317,50,1,17,0,'Promovido','1er Grado \'B\'',15.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (753,317,51,1,17,0,'Promovido','1er Grado \'B\'',15.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (754,318,42,1,16,0,'Promovido','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (755,318,43,1,16,0,'Promovido','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (756,318,44,1,16,0,'Promovido','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (757,318,45,1,16,0,'Promovido','1er Grado \'B\'',14.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (758,318,46,1,16,0,'Promovido','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (759,318,47,1,16,0,'Promovido','1er Grado \'B\'',14.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (760,318,48,1,16,0,'Promovido','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (761,318,49,1,16,0,'Promovido','1er Grado \'B\'',15.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (762,318,50,1,16,0,'Promovido','1er Grado \'B\'',15.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (763,318,51,1,16,0,'Promovido','1er Grado \'B\'',15.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (764,319,42,1,16,0,'Promovido','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (765,319,43,1,16,0,'Promovido','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (766,319,44,1,16,0,'Promovido','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (767,319,45,1,16,0,'Promovido','1er Grado \'B\'',14.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (768,319,46,1,16,0,'Promovido','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (769,319,47,1,16,0,'Promovido','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (770,319,48,1,16,0,'Promovido','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (771,319,49,1,16,0,'Promovido','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (772,319,50,1,16,0,'Promovido','1er Grado \'B\'',14.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (773,319,51,1,16,0,'Promovido','1er Grado \'B\'',15.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (774,320,42,1,14,0,'Promovido','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (775,320,43,1,14,0,'Promovido','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (776,320,44,1,14,0,'Promovido','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (777,320,45,1,14,0,'Promovido','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (778,320,46,1,14,0,'Promovido','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (779,320,47,1,14,0,'Promovido','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (780,320,48,1,14,0,'Promovido','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (781,320,49,1,14,0,'Promovido','1er Grado \'B\'',15.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (782,320,50,1,14,0,'Promovido','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (783,320,51,1,14,0,'Promovido','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (784,321,42,1,16,0,'Promovido','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (785,321,43,1,16,0,'Promovido','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (786,321,44,1,16,0,'Promovido','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (787,321,45,1,16,0,'Promovido','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (788,321,46,1,16,0,'Promovido','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (789,321,47,1,16,0,'Promovido','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (790,321,48,1,16,0,'Promovido','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (791,321,49,1,16,0,'Promovido','1er Grado \'B\'',14.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (792,321,50,1,16,0,'Promovido','1er Grado \'B\'',14.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (793,321,51,1,16,0,'Promovido','1er Grado \'B\'',16.00,'2025-05-21',NULL,'2025-05-21 19:21:14','2025-05-21 19:21:14');
INSERT INTO "calificacionesold" VALUES (794,294,44,1,0,2,'Retirado','1er Grado \'B\'',0.00,'2025-05-21','Retirado por 30% de inasistencias 14/07/1999 D.D. N.º 16','2025-05-21 19:21:53','2025-05-21 19:21:53');
INSERT INTO "calificacionesold" VALUES (795,296,44,1,0,2,'Retirado','1er Grado \'B\'',0.00,'2025-05-21','Retirado por 30% de inasistencias 14/07/1999 D.D. N.º 17','2025-05-21 19:21:53','2025-05-21 19:21:53');
INSERT INTO "calificacionesold" VALUES (796,322,42,1,15,3,'Aplazado','1er Grado \'B\'',10.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (797,322,43,1,15,3,'Aplazado','1er Grado \'B\'',8.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (798,322,44,1,15,3,'Aplazado','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (799,322,45,1,15,3,'Aplazado','1er Grado \'B\'',15.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (800,322,46,1,15,3,'Aplazado','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (801,322,47,1,15,3,'Aplazado','1er Grado \'B\'',8.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (802,322,48,1,15,3,'Aplazado','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (803,322,49,1,15,3,'Aplazado','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (804,322,50,1,15,3,'Aplazado','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (805,322,51,1,15,3,'Aplazado','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (806,323,42,1,17,0,'Promovido','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (807,323,43,1,17,0,'Promovido','1er Grado \'B\'',14.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (808,323,44,1,17,0,'Promovido','1er Grado \'B\'',14.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (809,323,45,1,17,0,'Promovido','1er Grado \'B\'',16.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (810,323,46,1,17,0,'Promovido','1er Grado \'B\'',15.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (811,323,47,1,17,0,'Promovido','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (812,323,48,1,17,0,'Promovido','1er Grado \'B\'',14.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (813,323,49,1,17,0,'Promovido','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (814,323,50,1,17,0,'Promovido','1er Grado \'B\'',14.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (815,323,51,1,17,0,'Promovido','1er Grado \'B\'',16.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (816,324,42,1,14,0,'Promovido','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (817,324,43,1,14,0,'Promovido','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (818,324,44,1,14,0,'Promovido','1er Grado \'B\'',14.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (819,324,45,1,14,0,'Promovido','1er Grado \'B\'',16.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (820,324,46,1,14,0,'Promovido','1er Grado \'B\'',14.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (821,324,47,1,14,0,'Promovido','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (822,324,48,1,14,0,'Promovido','1er Grado \'B\'',14.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (823,324,49,1,14,0,'Promovido','1er Grado \'B\'',15.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (824,324,50,1,14,0,'Promovido','1er Grado \'B\'',14.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (825,324,51,1,14,0,'Promovido','1er Grado \'B\'',15.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (826,325,42,1,16,0,'Promovido','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (827,325,43,1,16,0,'Promovido','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (828,325,44,1,16,0,'Promovido','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (829,325,45,1,16,0,'Promovido','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (830,325,46,1,16,0,'Promovido','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (831,325,47,1,16,0,'Promovido','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (832,325,48,1,16,0,'Promovido','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (833,325,49,1,16,0,'Promovido','1er Grado \'B\'',14.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (834,325,50,1,16,0,'Promovido','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (835,325,51,1,16,0,'Promovido','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (836,326,42,1,14,0,'Promovido','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (837,326,43,1,14,0,'Promovido','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (838,326,44,1,14,0,'Promovido','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (839,326,45,1,14,0,'Promovido','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (840,326,46,1,14,0,'Promovido','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (841,326,47,1,14,0,'Promovido','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (842,326,48,1,14,0,'Promovido','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (843,326,49,1,14,0,'Promovido','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (844,326,50,1,14,0,'Promovido','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (845,326,51,1,14,0,'Promovido','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (846,327,42,1,19,0,'Promovido','1er Grado \'B\'',17.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (847,327,43,1,19,0,'Promovido','1er Grado \'B\'',18.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (848,327,44,1,19,0,'Promovido','1er Grado \'B\'',16.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (849,327,45,1,19,0,'Promovido','1er Grado \'B\'',18.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (850,327,46,1,19,0,'Promovido','1er Grado \'B\'',16.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (851,327,47,1,19,0,'Promovido','1er Grado \'B\'',15.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (852,327,48,1,19,0,'Promovido','1er Grado \'B\'',16.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (853,327,49,1,19,0,'Promovido','1er Grado \'B\'',16.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (854,327,50,1,19,0,'Promovido','1er Grado \'B\'',18.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (855,327,51,1,19,0,'Promovido','1er Grado \'B\'',18.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (856,328,42,1,14,1,'Aplazado','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (857,328,43,1,14,1,'Aplazado','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (858,328,44,1,14,1,'Aplazado','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (859,328,45,1,14,1,'Aplazado','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (860,328,46,1,14,1,'Aplazado','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (861,328,47,1,14,1,'Aplazado','1er Grado \'B\'',9.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (862,328,48,1,14,1,'Aplazado','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (863,328,49,1,14,1,'Aplazado','1er Grado \'B\'',15.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (864,328,50,1,14,1,'Aplazado','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (865,328,51,1,14,1,'Aplazado','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (866,329,42,1,15,1,'Aplazado','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (867,329,43,1,15,1,'Aplazado','1er Grado \'B\'',12.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (868,329,44,1,15,1,'Aplazado','1er Grado \'B\'',10.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (869,329,45,1,15,1,'Aplazado','1er Grado \'B\'',14.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (870,329,46,1,15,1,'Aplazado','1er Grado \'B\'',14.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (871,329,47,1,15,1,'Aplazado','1er Grado \'B\'',11.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (872,329,48,1,15,1,'Aplazado','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (873,329,49,1,15,1,'Aplazado','1er Grado \'B\'',14.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (874,329,50,1,15,1,'Aplazado','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (875,329,51,1,15,1,'Aplazado','1er Grado \'B\'',13.00,'2025-05-21',NULL,'2025-05-21 19:49:16','2025-05-21 19:49:16');
INSERT INTO "calificacionesold" VALUES (876,389,53,1,14,4,'Reprobado','2do Grado \'A\'',10.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (877,389,54,1,14,4,'Reprobado','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (878,389,55,1,14,4,'Reprobado','2do Grado \'A\'',10.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (879,389,64,1,14,4,'Reprobado','2do Grado \'A\'',9.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (880,389,56,1,14,4,'Reprobado','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (881,389,57,1,14,4,'Reprobado','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (882,389,58,1,14,4,'Reprobado','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (883,389,60,1,14,4,'Reprobado','2do Grado \'A\'',10.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (884,389,59,1,14,4,'Reprobado','2do Grado \'A\'',14.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (885,389,61,1,14,4,'Reprobado','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (886,389,62,1,14,4,'Reprobado','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (887,389,63,1,14,4,'Reprobado','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (888,390,53,1,18,0,'Promovido','2do Grado \'A\'',15.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (889,390,54,1,18,0,'Promovido','2do Grado \'A\'',15.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (890,390,55,1,18,0,'Promovido','2do Grado \'A\'',14.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (891,390,64,1,18,0,'Promovido','2do Grado \'A\'',14.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (892,390,56,1,18,0,'Promovido','2do Grado \'A\'',16.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (893,390,57,1,18,0,'Promovido','2do Grado \'A\'',14.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (894,390,58,1,18,0,'Promovido','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (895,390,60,1,18,0,'Promovido','2do Grado \'A\'',17.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (896,390,59,1,18,0,'Promovido','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (897,390,61,1,18,0,'Promovido','2do Grado \'A\'',16.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (898,390,62,1,18,0,'Promovido','2do Grado \'A\'',16.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (899,390,63,1,18,0,'Promovido','2do Grado \'A\'',16.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (900,391,53,1,17,0,'Promovido','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (901,391,54,1,17,0,'Promovido','2do Grado \'A\'',15.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (902,391,55,1,17,0,'Promovido','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (903,391,64,1,17,0,'Promovido','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (904,391,56,1,17,0,'Promovido','2do Grado \'A\'',15.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (905,391,57,1,17,0,'Promovido','2do Grado \'A\'',14.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (906,391,58,1,17,0,'Promovido','2do Grado \'A\'',14.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (907,391,60,1,17,0,'Promovido','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (908,391,59,1,17,0,'Promovido','2do Grado \'A\'',16.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (909,391,61,1,17,0,'Promovido','2do Grado \'A\'',14.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (910,391,62,1,17,0,'Promovido','2do Grado \'A\'',15.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (911,391,63,1,17,0,'Promovido','2do Grado \'A\'',14.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (912,392,53,1,15,4,'Reprobado','2do Grado \'A\'',10.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (913,392,54,1,15,4,'Reprobado','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (914,392,55,1,15,4,'Reprobado','2do Grado \'A\'',9.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (915,392,64,1,15,4,'Reprobado','2do Grado \'A\'',10.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (916,392,56,1,15,4,'Reprobado','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (917,392,57,1,15,4,'Reprobado','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (918,392,58,1,15,4,'Reprobado','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (919,392,60,1,15,4,'Reprobado','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (920,392,59,1,15,4,'Reprobado','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (921,392,61,1,15,4,'Reprobado','2do Grado \'A\'',16.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (922,392,62,1,15,4,'Reprobado','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (923,392,63,1,15,4,'Reprobado','2do Grado \'A\'',10.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (924,393,53,1,15,0,'Promovido','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (925,393,54,1,15,0,'Promovido','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (926,393,55,1,15,0,'Promovido','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (927,393,64,1,15,0,'Promovido','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (928,393,56,1,15,0,'Promovido','2do Grado \'A\'',14.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (929,393,57,1,15,0,'Promovido','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (930,393,58,1,15,0,'Promovido','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (931,393,60,1,15,0,'Promovido','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (932,393,59,1,15,0,'Promovido','2do Grado \'A\'',14.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (933,393,61,1,15,0,'Promovido','2do Grado \'A\'',14.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (934,393,62,1,15,0,'Promovido','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (935,393,63,1,15,0,'Promovido','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (936,394,53,1,17,0,'Promovido','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (937,394,54,1,17,0,'Promovido','2do Grado \'A\'',18.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (938,394,55,1,17,0,'Promovido','2do Grado \'A\'',15.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (939,394,64,1,17,0,'Promovido','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (940,394,56,1,17,0,'Promovido','2do Grado \'A\'',14.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (941,394,57,1,17,0,'Promovido','2do Grado \'A\'',14.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (942,394,58,1,17,0,'Promovido','2do Grado \'A\'',14.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (943,394,60,1,17,0,'Promovido','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (944,394,59,1,17,0,'Promovido','2do Grado \'A\'',15.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (945,394,61,1,17,0,'Promovido','2do Grado \'A\'',15.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (946,394,62,1,17,0,'Promovido','2do Grado \'A\'',18.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (947,394,63,1,17,0,'Promovido','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (948,395,53,1,16,0,'Promovido','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (949,395,54,1,16,0,'Promovido','2do Grado \'A\'',14.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (950,395,55,1,16,0,'Promovido','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (951,395,64,1,16,0,'Promovido','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (952,395,56,1,16,0,'Promovido','2do Grado \'A\'',16.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (953,395,57,1,16,0,'Promovido','2do Grado \'A\'',14.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (954,395,58,1,16,0,'Promovido','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (955,395,60,1,16,0,'Promovido','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (956,395,59,1,16,0,'Promovido','2do Grado \'A\'',15.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (957,395,61,1,16,0,'Promovido','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (958,395,62,1,16,0,'Promovido','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (959,395,63,1,16,0,'Promovido','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (960,396,53,1,16,0,'Promovido','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (961,396,54,1,16,0,'Promovido','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (962,396,55,1,16,0,'Promovido','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (963,396,64,1,16,0,'Promovido','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (964,396,56,1,16,0,'Promovido','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (965,396,57,1,16,0,'Promovido','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (966,396,58,1,16,0,'Promovido','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (967,396,60,1,16,0,'Promovido','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (968,396,59,1,16,0,'Promovido','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (969,396,61,1,16,0,'Promovido','2do Grado \'A\'',15.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (970,396,62,1,16,0,'Promovido','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (971,396,63,1,16,0,'Promovido','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (972,397,53,1,12,5,'Reprobado','2do Grado \'A\'',10.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (973,397,54,1,12,5,'Reprobado','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (974,397,55,1,12,5,'Reprobado','2do Grado \'A\'',9.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (975,397,64,1,12,5,'Reprobado','2do Grado \'A\'',8.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (976,397,56,1,12,5,'Reprobado','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (977,397,57,1,12,5,'Reprobado','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (978,397,58,1,12,5,'Reprobado','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (979,397,60,1,12,5,'Reprobado','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (980,397,59,1,12,5,'Reprobado','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (981,397,61,1,12,5,'Reprobado','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (982,397,62,1,12,5,'Reprobado','2do Grado \'A\'',10.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (983,397,63,1,12,5,'Reprobado','2do Grado \'A\'',7.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (984,398,53,1,17,0,'Promovido','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (985,398,54,1,17,0,'Promovido','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (986,398,55,1,17,0,'Promovido','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (987,398,64,1,17,0,'Promovido','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (988,398,56,1,17,0,'Promovido','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (989,398,57,1,17,0,'Promovido','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (990,398,58,1,17,0,'Promovido','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (991,398,60,1,17,0,'Promovido','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (992,398,59,1,17,0,'Promovido','2do Grado \'A\'',15.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (993,398,61,1,17,0,'Promovido','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (994,398,62,1,17,0,'Promovido','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (995,398,63,1,17,0,'Promovido','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (996,399,53,1,17,0,'Promovido','2do Grado \'A\'',15.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (997,399,54,1,17,0,'Promovido','2do Grado \'A\'',15.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (998,399,55,1,17,0,'Promovido','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (999,399,64,1,17,0,'Promovido','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1000,399,56,1,17,0,'Promovido','2do Grado \'A\'',17.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1001,399,57,1,17,0,'Promovido','2do Grado \'A\'',14.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1002,399,58,1,17,0,'Promovido','2do Grado \'A\'',15.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1003,399,60,1,17,0,'Promovido','2do Grado \'A\'',17.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1004,399,59,1,17,0,'Promovido','2do Grado \'A\'',16.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1005,399,61,1,17,0,'Promovido','2do Grado \'A\'',14.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1006,399,62,1,17,0,'Promovido','2do Grado \'A\'',15.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1007,399,63,1,17,0,'Promovido','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1008,400,53,1,16,0,'Promovido','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1009,400,54,1,16,0,'Promovido','2do Grado \'A\'',15.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1010,400,55,1,16,0,'Promovido','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1011,400,64,1,16,0,'Promovido','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1012,400,56,1,16,0,'Promovido','2do Grado \'A\'',15.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1013,400,57,1,16,0,'Promovido','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1014,400,58,1,16,0,'Promovido','2do Grado \'A\'',14.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1015,400,60,1,16,0,'Promovido','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1016,400,59,1,16,0,'Promovido','2do Grado \'A\'',15.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1017,400,61,1,16,0,'Promovido','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1018,400,62,1,16,0,'Promovido','2do Grado \'A\'',15.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1019,400,63,1,16,0,'Promovido','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1020,401,53,1,15,4,'Reprobado','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1021,401,54,1,15,4,'Reprobado','2do Grado \'A\'',9.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1022,401,55,1,15,4,'Reprobado','2do Grado \'A\'',7.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1023,401,64,1,15,4,'Reprobado','2do Grado \'A\'',8.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1024,401,56,1,15,4,'Reprobado','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1025,401,57,1,15,4,'Reprobado','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1026,401,58,1,15,4,'Reprobado','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1027,401,60,1,15,4,'Reprobado','2do Grado \'A\'',10.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1028,401,59,1,15,4,'Reprobado','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1029,401,61,1,15,4,'Reprobado','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1030,401,62,1,15,4,'Reprobado','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1031,401,63,1,15,4,'Reprobado','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1032,402,53,1,18,0,'Promovido','2do Grado \'A\'',14.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1033,402,54,1,18,0,'Promovido','2do Grado \'A\'',14.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1034,402,55,1,18,0,'Promovido','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1035,402,64,1,18,0,'Promovido','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1036,402,56,1,18,0,'Promovido','2do Grado \'A\'',15.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1037,402,57,1,18,0,'Promovido','2do Grado \'A\'',15.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1038,402,58,1,18,0,'Promovido','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1039,402,60,1,18,0,'Promovido','2do Grado \'A\'',14.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1040,402,59,1,18,0,'Promovido','2do Grado \'A\'',16.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1041,402,61,1,18,0,'Promovido','2do Grado \'A\'',15.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1042,402,62,1,18,0,'Promovido','2do Grado \'A\'',14.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1043,402,63,1,18,0,'Promovido','2do Grado \'A\'',15.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1044,403,53,1,16,1,'Aplazado','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1045,403,54,1,16,1,'Aplazado','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1046,403,55,1,16,1,'Aplazado','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1047,403,64,1,16,1,'Aplazado','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1048,403,56,1,16,1,'Aplazado','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1049,403,57,1,16,1,'Aplazado','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1050,403,58,1,16,1,'Aplazado','2do Grado \'A\'',10.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1051,403,60,1,16,1,'Aplazado','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1052,403,59,1,16,1,'Aplazado','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1053,403,61,1,16,1,'Aplazado','2do Grado \'A\'',15.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1054,403,62,1,16,1,'Aplazado','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1055,403,63,1,16,1,'Aplazado','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1056,404,53,1,15,0,'Promovido','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1057,404,54,1,15,0,'Promovido','2do Grado \'A\'',15.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1058,404,55,1,15,0,'Promovido','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1059,404,64,1,15,0,'Promovido','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1060,404,56,1,15,0,'Promovido','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1061,404,57,1,15,0,'Promovido','2do Grado \'A\'',14.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1062,404,58,1,15,0,'Promovido','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1063,404,60,1,15,0,'Promovido','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1064,404,59,1,15,0,'Promovido','2do Grado \'A\'',14.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1065,404,61,1,15,0,'Promovido','2do Grado \'A\'',14.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1066,404,62,1,15,0,'Promovido','2do Grado \'A\'',14.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1067,404,63,1,15,0,'Promovido','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1068,405,53,1,14,2,'Aplazado','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1069,405,54,1,14,2,'Aplazado','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1070,405,55,1,14,2,'Aplazado','2do Grado \'A\'',8.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1071,405,64,1,14,2,'Aplazado','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1072,405,56,1,14,2,'Aplazado','2do Grado \'A\'',14.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1073,405,57,1,14,2,'Aplazado','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1074,405,58,1,14,2,'Aplazado','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1075,405,60,1,14,2,'Aplazado','2do Grado \'A\'',10.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1076,405,59,1,14,2,'Aplazado','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1077,405,61,1,14,2,'Aplazado','2do Grado \'A\'',16.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1078,405,62,1,14,2,'Aplazado','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1079,405,63,1,14,2,'Aplazado','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1080,406,53,1,15,0,'Promovido','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1081,406,54,1,15,0,'Promovido','2do Grado \'A\'',16.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1082,406,55,1,15,0,'Promovido','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1083,406,64,1,15,0,'Promovido','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1084,406,56,1,15,0,'Promovido','2do Grado \'A\'',15.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1085,406,57,1,15,0,'Promovido','2do Grado \'A\'',14.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1086,406,58,1,15,0,'Promovido','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1087,406,60,1,15,0,'Promovido','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1088,406,59,1,15,0,'Promovido','2do Grado \'A\'',14.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1089,406,61,1,15,0,'Promovido','2do Grado \'A\'',16.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1090,406,62,1,15,0,'Promovido','2do Grado \'A\'',14.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1091,406,63,1,15,0,'Promovido','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1092,407,53,1,16,0,'Promovido','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1093,407,54,1,16,0,'Promovido','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1094,407,55,1,16,0,'Promovido','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1095,407,64,1,16,0,'Promovido','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1096,407,56,1,16,0,'Promovido','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1097,407,57,1,16,0,'Promovido','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1098,407,58,1,16,0,'Promovido','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1099,407,60,1,16,0,'Promovido','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1100,407,59,1,16,0,'Promovido','2do Grado \'A\'',15.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1101,407,61,1,16,0,'Promovido','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1102,407,62,1,16,0,'Promovido','2do Grado \'A\'',16.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1103,407,63,1,16,0,'Promovido','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1104,408,53,1,16,1,'Aplazado','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1105,408,54,1,16,1,'Aplazado','2do Grado \'A\'',16.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1106,408,55,1,16,1,'Aplazado','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1107,408,64,1,16,1,'Aplazado','2do Grado \'A\'',9.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1108,408,56,1,16,1,'Aplazado','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1109,408,57,1,16,1,'Aplazado','2do Grado \'A\'',14.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1110,408,58,1,16,1,'Aplazado','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1111,408,60,1,16,1,'Aplazado','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1112,408,59,1,16,1,'Aplazado','2do Grado \'A\'',14.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1113,408,61,1,16,1,'Aplazado','2do Grado \'A\'',15.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1114,408,62,1,16,1,'Aplazado','2do Grado \'A\'',14.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1115,408,63,1,16,1,'Aplazado','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1116,409,53,1,15,0,'Promovido','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1117,409,54,1,15,0,'Promovido','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1118,409,55,1,15,0,'Promovido','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1119,409,64,1,15,0,'Promovido','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1120,409,56,1,15,0,'Promovido','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1121,409,57,1,15,0,'Promovido','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1122,409,58,1,15,0,'Promovido','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1123,409,60,1,15,0,'Promovido','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1124,409,59,1,15,0,'Promovido','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1125,409,61,1,15,0,'Promovido','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1126,409,62,1,15,0,'Promovido','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1127,409,63,1,15,0,'Promovido','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1128,410,53,1,19,0,'Promovido','2do Grado \'A\'',15.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1129,410,54,1,19,0,'Promovido','2do Grado \'A\'',20.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1130,410,55,1,19,0,'Promovido','2do Grado \'A\'',18.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1131,410,64,1,19,0,'Promovido','2do Grado \'A\'',17.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1132,410,56,1,19,0,'Promovido','2do Grado \'A\'',16.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1133,410,57,1,19,0,'Promovido','2do Grado \'A\'',18.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1134,410,58,1,19,0,'Promovido','2do Grado \'A\'',17.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1135,410,60,1,19,0,'Promovido','2do Grado \'A\'',17.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1136,410,59,1,19,0,'Promovido','2do Grado \'A\'',17.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1137,410,61,1,19,0,'Promovido','2do Grado \'A\'',16.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1138,410,62,1,19,0,'Promovido','2do Grado \'A\'',18.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1139,410,63,1,19,0,'Promovido','2do Grado \'A\'',16.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1140,411,53,1,18,0,'Promovido','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1141,411,54,1,18,0,'Promovido','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1142,411,55,1,18,0,'Promovido','2do Grado \'A\'',15.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1143,411,64,1,18,0,'Promovido','2do Grado \'A\'',14.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1144,411,56,1,18,0,'Promovido','2do Grado \'A\'',15.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1145,411,57,1,18,0,'Promovido','2do Grado \'A\'',15.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1146,411,58,1,18,0,'Promovido','2do Grado \'A\'',14.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1147,411,60,1,18,0,'Promovido','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1148,411,59,1,18,0,'Promovido','2do Grado \'A\'',15.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1149,411,61,1,18,0,'Promovido','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1150,411,62,1,18,0,'Promovido','2do Grado \'A\'',14.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1151,411,63,1,18,0,'Promovido','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1152,412,53,1,17,1,'Aplazado','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1153,412,54,1,17,1,'Aplazado','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1154,412,55,1,17,1,'Aplazado','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1155,412,64,1,17,1,'Aplazado','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1156,412,56,1,17,1,'Aplazado','2do Grado \'A\'',14.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1157,412,57,1,17,1,'Aplazado','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1158,412,58,1,17,1,'Aplazado','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1159,412,60,1,17,1,'Aplazado','2do Grado \'A\'',9.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1160,412,59,1,17,1,'Aplazado','2do Grado \'A\'',15.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1161,412,61,1,17,1,'Aplazado','2do Grado \'A\'',14.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1162,412,62,1,17,1,'Aplazado','2do Grado \'A\'',15.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1163,412,63,1,17,1,'Aplazado','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1164,413,53,1,15,5,'Reprobado','2do Grado \'A\'',9.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1165,413,54,1,15,5,'Reprobado','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1166,413,55,1,15,5,'Reprobado','2do Grado \'A\'',10.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1167,413,64,1,15,5,'Reprobado','2do Grado \'A\'',10.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1168,413,56,1,15,5,'Reprobado','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1169,413,57,1,15,5,'Reprobado','2do Grado \'A\'',10.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1170,413,58,1,15,5,'Reprobado','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1171,413,60,1,15,5,'Reprobado','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1172,413,59,1,15,5,'Reprobado','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1173,413,61,1,15,5,'Reprobado','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1174,413,62,1,15,5,'Reprobado','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1175,413,63,1,15,5,'Reprobado','2do Grado \'A\'',9.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1176,414,53,1,15,1,'Aplazado','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1177,414,54,1,15,1,'Aplazado','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1178,414,55,1,15,1,'Aplazado','2do Grado \'A\'',10.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1179,414,64,1,15,1,'Aplazado','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1180,414,56,1,15,1,'Aplazado','2do Grado \'A\'',14.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1181,414,57,1,15,1,'Aplazado','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1182,414,58,1,15,1,'Aplazado','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1183,414,60,1,15,1,'Aplazado','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1184,414,59,1,15,1,'Aplazado','2do Grado \'A\'',14.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1185,414,61,1,15,1,'Aplazado','2do Grado \'A\'',16.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1186,414,62,1,15,1,'Aplazado','2do Grado \'A\'',14.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1187,414,63,1,15,1,'Aplazado','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1188,415,53,1,17,0,'Promovido','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1189,415,54,1,17,0,'Promovido','2do Grado \'A\'',14.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1190,415,55,1,17,0,'Promovido','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1191,415,64,1,17,0,'Promovido','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1192,415,56,1,17,0,'Promovido','2do Grado \'A\'',15.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1193,415,57,1,17,0,'Promovido','2do Grado \'A\'',14.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1194,415,58,1,17,0,'Promovido','2do Grado \'A\'',14.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1195,415,60,1,17,0,'Promovido','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1196,415,59,1,17,0,'Promovido','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1197,415,61,1,17,0,'Promovido','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1198,415,62,1,17,0,'Promovido','2do Grado \'A\'',15.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1199,415,63,1,17,0,'Promovido','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1200,416,53,1,17,3,'Aplazado','2do Grado \'A\'',10.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1201,416,54,1,17,3,'Aplazado','2do Grado \'A\'',14.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1202,416,55,1,17,3,'Aplazado','2do Grado \'A\'',10.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1203,416,64,1,17,3,'Aplazado','2do Grado \'A\'',10.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1204,416,56,1,17,3,'Aplazado','2do Grado \'A\'',14.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1205,416,57,1,17,3,'Aplazado','2do Grado \'A\'',14.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1206,416,58,1,17,3,'Aplazado','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1207,416,60,1,17,3,'Aplazado','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1208,416,59,1,17,3,'Aplazado','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1209,416,61,1,17,3,'Aplazado','2do Grado \'A\'',14.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1210,416,62,1,17,3,'Aplazado','2do Grado \'A\'',15.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1211,416,63,1,17,3,'Aplazado','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1212,417,53,1,18,0,'Promovido','2do Grado \'A\'',14.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1213,417,54,1,18,0,'Promovido','2do Grado \'A\'',15.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1214,417,55,1,18,0,'Promovido','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1215,417,64,1,18,0,'Promovido','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1216,417,56,1,18,0,'Promovido','2do Grado \'A\'',16.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1217,417,57,1,18,0,'Promovido','2do Grado \'A\'',14.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1218,417,58,1,18,0,'Promovido','2do Grado \'A\'',15.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1219,417,60,1,18,0,'Promovido','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1220,417,59,1,18,0,'Promovido','2do Grado \'A\'',16.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1221,417,61,1,18,0,'Promovido','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1222,417,62,1,18,0,'Promovido','2do Grado \'A\'',16.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1223,417,63,1,18,0,'Promovido','2do Grado \'A\'',14.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1224,418,53,1,18,0,'Promovido','2do Grado \'A\'',16.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1225,418,54,1,18,0,'Promovido','2do Grado \'A\'',16.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1226,418,55,1,18,0,'Promovido','2do Grado \'A\'',16.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1227,418,64,1,18,0,'Promovido','2do Grado \'A\'',15.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1228,418,56,1,18,0,'Promovido','2do Grado \'A\'',16.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1229,418,57,1,18,0,'Promovido','2do Grado \'A\'',17.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1230,418,58,1,18,0,'Promovido','2do Grado \'A\'',16.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1231,418,60,1,18,0,'Promovido','2do Grado \'A\'',15.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1232,418,59,1,18,0,'Promovido','2do Grado \'A\'',15.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1233,418,61,1,18,0,'Promovido','2do Grado \'A\'',14.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1234,418,62,1,18,0,'Promovido','2do Grado \'A\'',16.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1235,418,63,1,18,0,'Promovido','2do Grado \'A\'',14.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1236,419,53,1,18,0,'Promovido','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1237,419,54,1,18,0,'Promovido','2do Grado \'A\'',14.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1238,419,55,1,18,0,'Promovido','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1239,419,64,1,18,0,'Promovido','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1240,419,56,1,18,0,'Promovido','2do Grado \'A\'',15.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1241,419,57,1,18,0,'Promovido','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1242,419,58,1,18,0,'Promovido','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1243,419,60,1,18,0,'Promovido','2do Grado \'A\'',14.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1244,419,59,1,18,0,'Promovido','2do Grado \'A\'',14.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1245,419,61,1,18,0,'Promovido','2do Grado \'A\'',17.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1246,419,62,1,18,0,'Promovido','2do Grado \'A\'',14.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1247,419,63,1,18,0,'Promovido','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1248,420,53,1,13,3,'Aplazado','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1249,420,54,1,13,3,'Aplazado','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1250,420,55,1,13,3,'Aplazado','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1251,420,64,1,13,3,'Aplazado','2do Grado \'A\'',10.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1252,420,56,1,13,3,'Aplazado','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1253,420,57,1,13,3,'Aplazado','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1254,420,58,1,13,3,'Aplazado','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1255,420,60,1,13,3,'Aplazado','2do Grado \'A\'',10.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1256,420,59,1,13,3,'Aplazado','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1257,420,61,1,13,3,'Aplazado','2do Grado \'A\'',14.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1258,420,62,1,13,3,'Aplazado','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1259,420,63,1,13,3,'Aplazado','2do Grado \'A\'',8.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1260,421,53,1,17,0,'Promovido','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1261,421,54,1,17,0,'Promovido','2do Grado \'A\'',16.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1262,421,55,1,17,0,'Promovido','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1263,421,64,1,17,0,'Promovido','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1264,421,56,1,17,0,'Promovido','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1265,421,57,1,17,0,'Promovido','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1266,421,58,1,17,0,'Promovido','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1267,421,60,1,17,0,'Promovido','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1268,421,59,1,17,0,'Promovido','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1269,421,61,1,17,0,'Promovido','2do Grado \'A\'',16.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1270,421,62,1,17,0,'Promovido','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1271,421,63,1,17,0,'Promovido','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1272,422,53,1,16,3,'Aplazado','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1273,422,54,1,16,3,'Aplazado','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1274,422,55,1,16,3,'Aplazado','2do Grado \'A\'',8.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1275,422,64,1,16,3,'Aplazado','2do Grado \'A\'',9.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1276,422,56,1,16,3,'Aplazado','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1277,422,57,1,16,3,'Aplazado','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1278,422,58,1,16,3,'Aplazado','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1279,422,60,1,16,3,'Aplazado','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1280,422,59,1,16,3,'Aplazado','2do Grado \'A\'',14.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1281,422,61,1,16,3,'Aplazado','2do Grado \'A\'',15.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1282,422,62,1,16,3,'Aplazado','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1283,422,63,1,16,3,'Aplazado','2do Grado \'A\'',8.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1284,423,53,1,16,0,'Promovido','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1285,423,54,1,16,0,'Promovido','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1286,423,55,1,16,0,'Promovido','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1287,423,64,1,16,0,'Promovido','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1288,423,56,1,16,0,'Promovido','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1289,423,57,1,16,0,'Promovido','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1290,423,58,1,16,0,'Promovido','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1291,423,60,1,16,0,'Promovido','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1292,423,59,1,16,0,'Promovido','2do Grado \'A\'',14.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1293,423,61,1,16,0,'Promovido','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1294,423,62,1,16,0,'Promovido','2do Grado \'A\'',14.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1295,423,63,1,16,0,'Promovido','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1296,424,53,1,18,0,'Promovido','2do Grado \'A\'',16.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1297,424,54,1,18,0,'Promovido','2do Grado \'A\'',20.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1298,424,55,1,18,0,'Promovido','2do Grado \'A\'',15.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1299,424,64,1,18,0,'Promovido','2do Grado \'A\'',15.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1300,424,56,1,18,0,'Promovido','2do Grado \'A\'',17.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1301,424,57,1,18,0,'Promovido','2do Grado \'A\'',15.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1302,424,58,1,18,0,'Promovido','2do Grado \'A\'',17.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1303,424,60,1,18,0,'Promovido','2do Grado \'A\'',17.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1304,424,59,1,18,0,'Promovido','2do Grado \'A\'',16.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1305,424,61,1,18,0,'Promovido','2do Grado \'A\'',16.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1306,424,62,1,18,0,'Promovido','2do Grado \'A\'',18.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1307,424,63,1,18,0,'Promovido','2do Grado \'A\'',16.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1308,425,53,1,16,1,'Aplazado','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1309,425,54,1,16,1,'Aplazado','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1310,425,55,1,16,1,'Aplazado','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1311,425,64,1,16,1,'Aplazado','2do Grado \'A\'',9.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1312,425,56,1,16,1,'Aplazado','2do Grado \'A\'',14.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1313,425,57,1,16,1,'Aplazado','2do Grado \'A\'',14.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1314,425,58,1,16,1,'Aplazado','2do Grado \'A\'',14.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1315,425,60,1,16,1,'Aplazado','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1316,425,59,1,16,1,'Aplazado','2do Grado \'A\'',14.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1317,425,61,1,16,1,'Aplazado','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1318,425,62,1,16,1,'Aplazado','2do Grado \'A\'',15.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1319,425,63,1,16,1,'Aplazado','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1320,426,53,1,15,3,'Aplazado','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1321,426,54,1,15,3,'Aplazado','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1322,426,55,1,15,3,'Aplazado','2do Grado \'A\'',10.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1323,426,64,1,15,3,'Aplazado','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1324,426,56,1,15,3,'Aplazado','2do Grado \'A\'',15.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1325,426,57,1,15,3,'Aplazado','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1326,426,58,1,15,3,'Aplazado','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1327,426,60,1,15,3,'Aplazado','2do Grado \'A\'',10.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1328,426,59,1,15,3,'Aplazado','2do Grado \'A\'',15.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1329,426,61,1,15,3,'Aplazado','2do Grado \'A\'',15.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1330,426,62,1,15,3,'Aplazado','2do Grado \'A\'',16.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1331,426,63,1,15,3,'Aplazado','2do Grado \'A\'',9.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1332,427,53,1,14,1,'Aplazado','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1333,427,54,1,14,1,'Aplazado','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1334,427,55,1,14,1,'Aplazado','2do Grado \'A\'',10.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1335,427,64,1,14,1,'Aplazado','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1336,427,56,1,14,1,'Aplazado','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1337,427,57,1,14,1,'Aplazado','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1338,427,58,1,14,1,'Aplazado','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1339,427,60,1,14,1,'Aplazado','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1340,427,59,1,14,1,'Aplazado','2do Grado \'A\'',15.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1341,427,61,1,14,1,'Aplazado','2do Grado \'A\'',15.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1342,427,62,1,14,1,'Aplazado','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1343,427,63,1,14,1,'Aplazado','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1344,428,53,1,17,0,'Promovido','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1345,428,54,1,17,0,'Promovido','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1346,428,55,1,17,0,'Promovido','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1347,428,64,1,17,0,'Promovido','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1348,428,56,1,17,0,'Promovido','2do Grado \'A\'',15.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1349,428,57,1,17,0,'Promovido','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1350,428,58,1,17,0,'Promovido','2do Grado \'A\'',15.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1351,428,60,1,17,0,'Promovido','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1352,428,59,1,17,0,'Promovido','2do Grado \'A\'',15.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1353,428,61,1,17,0,'Promovido','2do Grado \'A\'',15.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1354,428,62,1,17,0,'Promovido','2do Grado \'A\'',15.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1355,428,63,1,17,0,'Promovido','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1356,429,53,1,17,0,'Promovido','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1357,429,54,1,17,0,'Promovido','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1358,429,55,1,17,0,'Promovido','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1359,429,64,1,17,0,'Promovido','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1360,429,56,1,17,0,'Promovido','2do Grado \'A\'',15.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1361,429,57,1,17,0,'Promovido','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1362,429,58,1,17,0,'Promovido','2do Grado \'A\'',13.00,'2025-05-21',NULL,'2025-05-21 22:21:38','2025-05-21 22:21:38');
INSERT INTO "calificacionesold" VALUES (1363,429,60,1,17,0,'Promovido','2do Grado \'A\'',11.00,'2025-05-21',NULL,'2025-05-21 22:21:39','2025-05-21 22:21:39');
INSERT INTO "calificacionesold" VALUES (1364,429,59,1,17,0,'Promovido','2do Grado \'A\'',14.00,'2025-05-21',NULL,'2025-05-21 22:21:39','2025-05-21 22:21:39');
INSERT INTO "calificacionesold" VALUES (1365,429,61,1,17,0,'Promovido','2do Grado \'A\'',15.00,'2025-05-21',NULL,'2025-05-21 22:21:39','2025-05-21 22:21:39');
INSERT INTO "calificacionesold" VALUES (1366,429,62,1,17,0,'Promovido','2do Grado \'A\'',14.00,'2025-05-21',NULL,'2025-05-21 22:21:39','2025-05-21 22:21:39');
INSERT INTO "calificacionesold" VALUES (1367,429,63,1,17,0,'Promovido','2do Grado \'A\'',12.00,'2025-05-21',NULL,'2025-05-21 22:21:39','2025-05-21 22:21:39');
INSERT INTO "calificacionesold" VALUES (1368,330,65,1,13,1,'Aplazado','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 20:51:56','2025-05-22 20:51:56');
INSERT INTO "calificacionesold" VALUES (1369,330,66,1,13,1,'Aplazado','1er Grado \'A\'',15.00,'2025-05-22',NULL,'2025-05-22 20:51:56','2025-05-22 20:51:56');
INSERT INTO "calificacionesold" VALUES (1370,330,67,1,13,1,'Aplazado','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 20:51:56','2025-05-22 20:51:56');
INSERT INTO "calificacionesold" VALUES (1371,330,69,1,13,1,'Aplazado','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 20:51:56','2025-05-22 20:51:56');
INSERT INTO "calificacionesold" VALUES (1372,330,68,1,13,1,'Aplazado','1er Grado \'A\'',11.00,'2025-05-22',NULL,'2025-05-22 20:51:56','2025-05-22 20:51:56');
INSERT INTO "calificacionesold" VALUES (1373,330,70,1,13,1,'Aplazado','1er Grado \'A\'',11.00,'2025-05-22',NULL,'2025-05-22 20:51:56','2025-05-22 20:51:56');
INSERT INTO "calificacionesold" VALUES (1374,330,71,1,13,1,'Aplazado','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 20:51:56','2025-05-22 20:51:56');
INSERT INTO "calificacionesold" VALUES (1375,330,73,1,13,1,'Aplazado','1er Grado \'A\'',15.00,'2025-05-22',NULL,'2025-05-22 20:51:56','2025-05-22 20:51:56');
INSERT INTO "calificacionesold" VALUES (1376,330,74,1,13,1,'Aplazado','1er Grado \'A\'',9.00,'2025-05-22',NULL,'2025-05-22 20:51:56','2025-05-22 20:51:56');
INSERT INTO "calificacionesold" VALUES (1377,330,72,1,13,1,'Aplazado','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 20:51:56','2025-05-22 20:51:56');
INSERT INTO "calificacionesold" VALUES (1378,331,65,1,13,2,'Aplazado','1er Grado \'A\'',10.00,'2025-05-22',NULL,'2025-05-22 20:51:56','2025-05-22 20:51:56');
INSERT INTO "calificacionesold" VALUES (1379,331,66,1,13,2,'Aplazado','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 20:51:56','2025-05-22 20:51:56');
INSERT INTO "calificacionesold" VALUES (1380,331,67,1,13,2,'Aplazado','1er Grado \'A\'',11.00,'2025-05-22',NULL,'2025-05-22 20:51:56','2025-05-22 20:51:56');
INSERT INTO "calificacionesold" VALUES (1381,331,69,1,13,2,'Aplazado','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 20:51:56','2025-05-22 20:51:56');
INSERT INTO "calificacionesold" VALUES (1382,331,68,1,13,2,'Aplazado','1er Grado \'A\'',11.00,'2025-05-22',NULL,'2025-05-22 20:51:56','2025-05-22 20:51:56');
INSERT INTO "calificacionesold" VALUES (1383,331,70,1,13,2,'Aplazado','1er Grado \'A\'',11.00,'2025-05-22',NULL,'2025-05-22 20:51:56','2025-05-22 20:51:56');
INSERT INTO "calificacionesold" VALUES (1384,331,71,1,13,2,'Aplazado','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 20:51:56','2025-05-22 20:51:56');
INSERT INTO "calificacionesold" VALUES (1385,331,73,1,13,2,'Aplazado','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 20:51:56','2025-05-22 20:51:56');
INSERT INTO "calificacionesold" VALUES (1386,331,74,1,13,2,'Aplazado','1er Grado \'A\'',10.00,'2025-05-22',NULL,'2025-05-22 20:51:56','2025-05-22 20:51:56');
INSERT INTO "calificacionesold" VALUES (1387,331,72,1,13,2,'Aplazado','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 20:51:56','2025-05-22 20:51:56');
INSERT INTO "calificacionesold" VALUES (1388,332,65,1,15,2,'Aplazado','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 20:51:56','2025-05-22 20:51:56');
INSERT INTO "calificacionesold" VALUES (1389,332,66,1,15,2,'Aplazado','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 20:51:56','2025-05-22 20:51:56');
INSERT INTO "calificacionesold" VALUES (1390,332,67,1,15,2,'Aplazado','1er Grado \'A\'',11.00,'2025-05-22',NULL,'2025-05-22 20:51:56','2025-05-22 20:51:56');
INSERT INTO "calificacionesold" VALUES (1391,332,69,1,15,2,'Aplazado','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1392,332,68,1,15,2,'Aplazado','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1393,332,70,1,15,2,'Aplazado','1er Grado \'A\'',10.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1394,332,71,1,15,2,'Aplazado','1er Grado \'A\'',15.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1395,332,73,1,15,2,'Aplazado','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1396,332,74,1,15,2,'Aplazado','1er Grado \'A\'',9.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1397,332,72,1,15,2,'Aplazado','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1398,333,65,1,16,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1399,333,66,1,16,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1400,333,67,1,16,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1401,333,68,1,16,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1402,333,70,1,16,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1403,333,71,1,16,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1404,333,73,1,16,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1405,333,74,1,16,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1406,333,72,1,16,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1407,334,65,1,15,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1408,334,66,1,15,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1409,334,67,1,15,0,'Promovido','1er Grado \'A\'',11.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1410,334,69,1,15,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1411,334,68,1,15,0,'Promovido','1er Grado \'A\'',11.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1412,334,70,1,15,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1413,334,71,1,15,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1414,334,73,1,15,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1415,334,74,1,15,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1416,334,72,1,15,0,'Promovido','1er Grado \'A\'',11.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1417,335,65,1,14,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1418,335,66,1,14,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1419,335,67,1,14,0,'Promovido','1er Grado \'A\'',11.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1420,335,69,1,14,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1421,335,68,1,14,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1422,335,70,1,14,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1423,335,71,1,14,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1424,335,73,1,14,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1425,335,74,1,14,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1426,335,72,1,14,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1427,336,65,1,15,1,'Aplazado','1er Grado \'A\'',10.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1428,336,66,1,15,1,'Aplazado','1er Grado \'A\'',11.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1429,336,67,1,15,1,'Aplazado','1er Grado \'A\'',11.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1430,336,69,1,15,1,'Aplazado','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1431,336,68,1,15,1,'Aplazado','1er Grado \'A\'',11.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1432,336,70,1,15,1,'Aplazado','1er Grado \'A\'',11.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1433,336,71,1,15,1,'Aplazado','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1434,336,73,1,15,1,'Aplazado','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1435,336,74,1,15,1,'Aplazado','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1436,336,72,1,15,1,'Aplazado','1er Grado \'A\'',11.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1437,337,65,1,18,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1438,337,66,1,18,0,'Promovido','1er Grado \'A\'',19.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1439,337,67,1,18,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1440,337,69,1,18,0,'Promovido','1er Grado \'A\'',16.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1441,337,68,1,18,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1442,337,70,1,18,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1443,337,71,1,18,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1444,337,73,1,18,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1445,337,74,1,18,0,'Promovido','1er Grado \'A\'',16.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1446,337,72,1,18,0,'Promovido','1er Grado \'A\'',17.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1447,338,65,1,15,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1448,338,66,1,15,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1449,338,67,1,15,0,'Promovido','1er Grado \'A\'',11.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1450,338,69,1,15,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1451,338,68,1,15,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1452,338,70,1,15,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1453,338,71,1,15,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1454,338,73,1,15,0,'Promovido','1er Grado \'A\'',16.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1455,338,74,1,15,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1456,338,72,1,15,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1457,339,65,1,17,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1458,339,66,1,17,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1459,339,67,1,17,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1460,339,68,1,17,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1461,339,70,1,17,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1462,339,71,1,17,0,'Promovido','1er Grado \'A\'',16.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1463,339,73,1,17,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1464,339,74,1,17,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1465,339,72,1,17,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1466,340,65,1,18,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1467,340,66,1,18,0,'Promovido','1er Grado \'A\'',16.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1468,340,67,1,18,0,'Promovido','1er Grado \'A\'',16.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1469,340,69,1,18,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1470,340,68,1,18,0,'Promovido','1er Grado \'A\'',16.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1471,340,70,1,18,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1472,340,71,1,18,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1473,340,73,1,18,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1474,340,74,1,18,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1475,340,72,1,18,0,'Promovido','1er Grado \'A\'',17.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1476,341,65,1,15,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1477,341,66,1,15,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1478,341,67,1,15,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1479,341,69,1,15,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1480,341,68,1,15,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1481,341,70,1,15,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1482,341,71,1,15,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1483,341,73,1,15,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1484,341,74,1,15,0,'Promovido','1er Grado \'A\'',11.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1485,341,72,1,15,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1486,342,65,1,15,2,'Aplazado','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1487,342,66,1,15,2,'Aplazado','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1488,342,67,1,15,2,'Aplazado','1er Grado \'A\'',9.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1489,342,69,1,15,2,'Aplazado','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1490,342,68,1,15,2,'Aplazado','1er Grado \'A\'',11.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1491,342,70,1,15,2,'Aplazado','1er Grado \'A\'',11.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1492,342,71,1,15,2,'Aplazado','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1493,342,73,1,15,2,'Aplazado','1er Grado \'A\'',11.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1494,342,74,1,15,2,'Aplazado','1er Grado \'A\'',15.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1495,342,72,1,15,2,'Aplazado','1er Grado \'A\'',9.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1496,343,65,1,15,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1497,343,66,1,15,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1498,343,67,1,15,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1499,343,69,1,15,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1500,343,68,1,15,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1501,343,70,1,15,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1502,343,71,1,15,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1503,343,73,1,15,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1504,343,74,1,15,0,'Promovido','1er Grado \'A\'',16.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1505,343,72,1,15,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 20:51:57');
INSERT INTO "calificacionesold" VALUES (1506,345,65,1,15,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1507,345,66,1,15,0,'Promovido','1er Grado \'A\'',16.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1508,345,67,1,15,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1509,345,69,1,15,0,'Promovido','1er Grado \'A\'',16.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1510,345,68,1,15,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1511,345,70,1,15,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1512,345,71,1,15,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1513,345,73,1,15,0,'Promovido','1er Grado \'A\'',16.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1514,345,74,1,15,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1515,345,72,1,15,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-22',NULL,'2025-05-22 20:51:57','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1516,349,65,1,0,1,'Trasladado','1er Grado \'A\'',0.00,'2025-05-22','Trasladado al C.E.P Andrés Avelino Cáceres 02/06/2000','2025-05-22 21:21:02','2025-05-22 21:37:35');
INSERT INTO "calificacionesold" VALUES (1517,358,65,1,0,1,'Trasladado','1er Grado \'A\'',0.00,'2025-05-22','Trasladado al C.E.P Andrés Avelino Cáceres 02/06/2000','2025-05-22 21:25:01','2025-05-22 21:37:35');
INSERT INTO "calificacionesold" VALUES (1518,381,65,1,0,1,'Trasladado','1er Grado \'A\'',0.00,'2025-05-22','Trasladado al C.E.P Juan Pablo II 30/05/2000','2025-05-22 21:34:17','2025-05-22 21:38:04');
INSERT INTO "calificacionesold" VALUES (1519,346,65,1,16,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1520,346,66,1,16,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1521,346,67,1,16,0,'Promovido','1er Grado \'A\'',11.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1522,346,69,1,16,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1523,346,68,1,16,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1524,346,70,1,16,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1525,346,71,1,16,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1526,346,73,1,16,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1527,346,74,1,16,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1528,346,72,1,16,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1529,344,65,1,17,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1530,344,66,1,17,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1531,344,67,1,17,0,'Promovido','1er Grado \'A\'',11.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1532,344,69,1,17,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1533,344,68,1,17,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1534,344,70,1,17,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1535,344,71,1,17,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1536,344,73,1,17,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1537,344,74,1,17,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1538,344,72,1,17,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1539,347,65,1,14,1,'Aplazado','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1540,347,66,1,14,1,'Aplazado','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1541,347,67,1,14,1,'Aplazado','1er Grado \'A\'',11.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1542,347,69,1,14,1,'Aplazado','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1543,347,68,1,14,1,'Aplazado','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1544,347,70,1,14,1,'Aplazado','1er Grado \'A\'',11.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1545,347,71,1,14,1,'Aplazado','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1546,347,73,1,14,1,'Aplazado','1er Grado \'A\'',15.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1547,347,74,1,14,1,'Aplazado','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1548,347,72,1,14,1,'Aplazado','1er Grado \'A\'',10.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1549,348,65,1,14,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1550,348,66,1,14,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1551,348,67,1,14,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1552,348,69,1,14,0,'Promovido','1er Grado \'A\'',17.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1553,348,68,1,14,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1554,348,70,1,14,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1555,348,71,1,14,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1556,348,73,1,14,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1557,348,74,1,14,0,'Promovido','1er Grado \'A\'',11.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1558,348,72,1,14,0,'Promovido','1er Grado \'A\'',11.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1559,350,65,1,17,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1560,350,66,1,17,0,'Promovido','1er Grado \'A\'',18.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1561,350,67,1,17,0,'Promovido','1er Grado \'A\'',16.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1562,350,69,1,17,0,'Promovido','1er Grado \'A\'',17.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1563,350,68,1,17,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1564,350,70,1,17,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1565,350,71,1,17,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1566,350,73,1,17,0,'Promovido','1er Grado \'A\'',16.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1567,350,74,1,17,0,'Promovido','1er Grado \'A\'',16.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1568,350,72,1,17,0,'Promovido','1er Grado \'A\'',17.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1569,351,65,1,15,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1570,351,66,1,15,0,'Promovido','1er Grado \'A\'',19.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1571,351,67,1,15,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1572,351,69,1,15,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1573,351,68,1,15,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1574,351,70,1,15,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1575,351,71,1,15,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1576,351,73,1,15,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1577,351,74,1,15,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1578,351,72,1,15,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1579,352,65,1,13,2,'Aplazado','1er Grado \'A\'',11.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1580,352,66,1,13,2,'Aplazado','1er Grado \'A\'',11.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1581,352,67,1,13,2,'Aplazado','1er Grado \'A\'',9.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1582,352,68,1,13,2,'Aplazado','1er Grado \'A\'',11.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1583,352,70,1,13,2,'Aplazado','1er Grado \'A\'',10.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1584,352,71,1,13,2,'Aplazado','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1585,352,73,1,13,2,'Aplazado','1er Grado \'A\'',15.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1586,352,74,1,13,2,'Aplazado','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1587,352,72,1,13,2,'Aplazado','1er Grado \'A\'',11.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1588,353,65,1,14,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1589,353,66,1,14,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1590,353,67,1,14,0,'Promovido','1er Grado \'A\'',11.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1591,353,69,1,14,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1592,353,68,1,14,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1593,353,70,1,14,0,'Promovido','1er Grado \'A\'',11.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1594,353,71,1,14,0,'Promovido','1er Grado \'A\'',11.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1595,353,73,1,14,0,'Promovido','1er Grado \'A\'',16.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1596,353,74,1,14,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1597,353,72,1,14,0,'Promovido','1er Grado \'A\'',11.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1598,354,65,1,15,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1599,354,66,1,15,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1600,354,67,1,15,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1601,354,69,1,15,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1602,354,68,1,15,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1603,354,70,1,15,0,'Promovido','1er Grado \'A\'',11.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1604,354,71,1,15,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1605,354,73,1,15,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1606,354,74,1,15,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1607,354,72,1,15,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1608,355,65,1,18,0,'Promovido','1er Grado \'A\'',17.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1609,355,66,1,18,0,'Promovido','1er Grado \'A\'',18.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1610,355,67,1,18,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1611,355,69,1,18,0,'Promovido','1er Grado \'A\'',16.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1612,355,68,1,18,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1613,355,70,1,18,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1614,355,71,1,18,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1615,355,73,1,18,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1616,355,74,1,18,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1617,355,72,1,18,0,'Promovido','1er Grado \'A\'',16.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1618,356,65,1,16,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1619,356,66,1,16,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1620,356,67,1,16,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1621,356,69,1,16,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1622,356,68,1,16,0,'Promovido','1er Grado \'A\'',11.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1623,356,70,1,16,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1624,356,71,1,16,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1625,356,73,1,16,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1626,356,74,1,16,0,'Promovido','1er Grado \'A\'',16.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1627,356,72,1,16,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1628,357,65,1,13,3,'Aplazado','1er Grado \'A\'',11.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1629,357,66,1,13,3,'Aplazado','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1630,357,67,1,13,3,'Aplazado','1er Grado \'A\'',10.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1631,357,69,1,13,3,'Aplazado','1er Grado \'A\'',11.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1632,357,68,1,13,3,'Aplazado','1er Grado \'A\'',10.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1633,357,70,1,13,3,'Aplazado','1er Grado \'A\'',9.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1634,357,71,1,13,3,'Aplazado','1er Grado \'A\'',11.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1635,357,73,1,13,3,'Aplazado','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1636,357,74,1,13,3,'Aplazado','1er Grado \'A\'',11.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1637,357,72,1,13,3,'Aplazado','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1638,359,65,1,14,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1639,359,66,1,14,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1640,359,67,1,14,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1641,359,69,1,14,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1642,359,68,1,14,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1643,359,70,1,14,0,'Promovido','1er Grado \'A\'',11.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1644,359,71,1,14,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1645,359,73,1,14,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1646,359,74,1,14,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1647,359,72,1,14,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1648,360,65,1,14,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1649,360,66,1,14,0,'Promovido','1er Grado \'A\'',18.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1650,360,67,1,14,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1651,360,69,1,14,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1652,360,68,1,14,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1653,360,70,1,14,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1654,360,71,1,14,0,'Promovido','1er Grado \'A\'',16.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1655,360,73,1,14,0,'Promovido','1er Grado \'A\'',17.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1656,360,74,1,14,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1657,360,72,1,14,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1658,361,65,1,14,1,'Aplazado','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1659,361,66,1,14,1,'Aplazado','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1660,361,67,1,14,1,'Aplazado','1er Grado \'A\'',10.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1661,361,69,1,14,1,'Aplazado','1er Grado \'A\'',15.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1662,361,68,1,14,1,'Aplazado','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1663,361,70,1,14,1,'Aplazado','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1664,361,71,1,14,1,'Aplazado','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1665,361,73,1,14,1,'Aplazado','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1666,361,74,1,14,1,'Aplazado','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1667,361,72,1,14,1,'Aplazado','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1668,362,65,1,17,0,'Promovido','1er Grado \'A\'',16.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1669,362,66,1,17,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1670,362,67,1,17,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1671,362,69,1,17,0,'Promovido','1er Grado \'A\'',17.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1672,362,68,1,17,0,'Promovido','1er Grado \'A\'',16.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1673,362,70,1,17,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1674,362,71,1,17,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1675,362,73,1,17,0,'Promovido','1er Grado \'A\'',16.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1676,362,74,1,17,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1677,362,72,1,17,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1678,363,65,1,16,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1679,363,66,1,16,0,'Promovido','1er Grado \'A\'',16.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1680,363,67,1,16,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1681,363,68,1,16,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1682,363,70,1,16,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1683,363,71,1,16,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1684,363,73,1,16,0,'Promovido','1er Grado \'A\'',16.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1685,363,74,1,16,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1686,363,72,1,16,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1687,364,65,1,13,5,'Reprobado','1er Grado \'A\'',11.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1688,364,66,1,13,5,'Reprobado','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1689,364,67,1,13,5,'Reprobado','1er Grado \'A\'',10.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1690,364,69,1,13,5,'Reprobado','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1691,364,68,1,13,5,'Reprobado','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1692,364,70,1,13,5,'Reprobado','1er Grado \'A\'',9.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1693,364,71,1,13,5,'Reprobado','1er Grado \'A\'',10.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1694,364,73,1,13,5,'Reprobado','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1695,364,74,1,13,5,'Reprobado','1er Grado \'A\'',10.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1696,364,72,1,13,5,'Reprobado','1er Grado \'A\'',9.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1697,365,65,1,15,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1698,365,66,1,15,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1699,365,67,1,15,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1700,365,69,1,15,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1701,365,68,1,15,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1702,365,70,1,15,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1703,365,71,1,15,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1704,365,73,1,15,0,'Promovido','1er Grado \'A\'',16.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1705,365,74,1,15,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1706,365,72,1,15,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1707,366,65,1,15,1,'Aplazado','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1708,366,66,1,15,1,'Aplazado','1er Grado \'A\'',11.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1709,366,67,1,15,1,'Aplazado','1er Grado \'A\'',9.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1710,366,69,1,15,1,'Aplazado','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1711,366,68,1,15,1,'Aplazado','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1712,366,70,1,15,1,'Aplazado','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:49','2025-05-22 21:36:49');
INSERT INTO "calificacionesold" VALUES (1713,366,71,1,15,1,'Aplazado','1er Grado \'A\'',15.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1714,366,73,1,15,1,'Aplazado','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1715,366,74,1,15,1,'Aplazado','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1716,366,72,1,15,1,'Aplazado','1er Grado \'A\'',11.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1717,367,65,1,18,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1718,367,66,1,18,0,'Promovido','1er Grado \'A\'',16.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1719,367,67,1,18,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1720,367,69,1,18,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1721,367,68,1,18,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1722,367,70,1,18,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1723,367,71,1,18,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1724,367,73,1,18,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1725,367,74,1,18,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1726,367,72,1,18,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1727,368,65,1,13,4,'Reprobado','1er Grado \'A\'',11.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1728,368,66,1,13,4,'Reprobado','1er Grado \'A\'',9.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1729,368,67,1,13,4,'Reprobado','1er Grado \'A\'',9.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1730,368,68,1,13,4,'Reprobado','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1731,368,70,1,13,4,'Reprobado','1er Grado \'A\'',9.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1732,368,71,1,13,4,'Reprobado','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1733,368,73,1,13,4,'Reprobado','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1734,368,74,1,13,4,'Reprobado','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1735,368,72,1,13,4,'Reprobado','1er Grado \'A\'',8.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1736,369,65,1,16,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1737,369,66,1,16,0,'Promovido','1er Grado \'A\'',17.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1738,369,67,1,16,0,'Promovido','1er Grado \'A\'',17.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1739,369,68,1,16,0,'Promovido','1er Grado \'A\'',16.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1740,369,70,1,16,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1741,369,71,1,16,0,'Promovido','1er Grado \'A\'',17.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1742,369,73,1,16,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1743,369,74,1,16,0,'Promovido','1er Grado \'A\'',17.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1744,369,72,1,16,0,'Promovido','1er Grado \'A\'',17.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1745,370,65,1,15,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1746,370,66,1,15,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1747,370,67,1,15,0,'Promovido','1er Grado \'A\'',11.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1748,370,69,1,15,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1749,370,68,1,15,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1750,370,70,1,15,0,'Promovido','1er Grado \'A\'',11.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1751,370,71,1,15,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1752,370,73,1,15,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1753,370,74,1,15,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1754,370,72,1,15,0,'Promovido','1er Grado \'A\'',11.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1755,371,65,1,13,4,'Reprobado','1er Grado \'A\'',10.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1756,371,66,1,13,4,'Reprobado','1er Grado \'A\'',10.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1757,371,67,1,13,4,'Reprobado','1er Grado \'A\'',7.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1758,371,69,1,13,4,'Reprobado','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1759,371,68,1,13,4,'Reprobado','1er Grado \'A\'',11.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1760,371,70,1,13,4,'Reprobado','1er Grado \'A\'',11.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1761,371,71,1,13,4,'Reprobado','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1762,371,73,1,13,4,'Reprobado','1er Grado \'A\'',11.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1763,371,74,1,13,4,'Reprobado','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1764,371,72,1,13,4,'Reprobado','1er Grado \'A\'',8.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1765,372,65,1,15,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1766,372,66,1,15,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1767,372,67,1,15,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1768,372,69,1,15,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1769,372,68,1,15,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1770,372,70,1,15,0,'Promovido','1er Grado \'A\'',11.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1771,372,71,1,15,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1772,372,73,1,15,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1773,372,74,1,15,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1774,372,72,1,15,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1775,373,65,1,14,1,'Aplazado','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1776,373,66,1,14,1,'Aplazado','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1777,373,67,1,14,1,'Aplazado','1er Grado \'A\'',10.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1778,373,69,1,14,1,'Aplazado','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1779,373,68,1,14,1,'Aplazado','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1780,373,70,1,14,1,'Aplazado','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1781,373,71,1,14,1,'Aplazado','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1782,373,73,1,14,1,'Aplazado','1er Grado \'A\'',16.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1783,373,74,1,14,1,'Aplazado','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1784,373,72,1,14,1,'Aplazado','1er Grado \'A\'',11.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1785,374,65,1,14,2,'Aplazado','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1786,374,66,1,14,2,'Aplazado','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1787,374,67,1,14,2,'Aplazado','1er Grado \'A\'',9.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1788,374,69,1,14,2,'Aplazado','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1789,374,68,1,14,2,'Aplazado','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1790,374,70,1,14,2,'Aplazado','1er Grado \'A\'',9.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1791,374,71,1,14,2,'Aplazado','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1792,374,73,1,14,2,'Aplazado','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1793,374,74,1,14,2,'Aplazado','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1794,374,72,1,14,2,'Aplazado','1er Grado \'A\'',11.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1795,375,65,1,14,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1796,375,66,1,14,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1797,375,67,1,14,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1798,375,69,1,14,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1799,375,68,1,14,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1800,375,70,1,14,0,'Promovido','1er Grado \'A\'',11.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1801,375,71,1,14,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1802,375,73,1,14,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1803,375,74,1,14,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1804,375,72,1,14,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1805,376,65,1,13,1,'Aplazado','1er Grado \'A\'',11.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1806,376,66,1,13,1,'Aplazado','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1807,376,67,1,13,1,'Aplazado','1er Grado \'A\'',11.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1808,376,69,1,13,1,'Aplazado','1er Grado \'A\'',11.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1809,376,68,1,13,1,'Aplazado','1er Grado \'A\'',11.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1810,376,70,1,13,1,'Aplazado','1er Grado \'A\'',7.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1811,376,71,1,13,1,'Aplazado','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1812,376,73,1,13,1,'Aplazado','1er Grado \'A\'',16.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1813,376,74,1,13,1,'Aplazado','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1814,376,72,1,13,1,'Aplazado','1er Grado \'A\'',11.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1815,377,65,1,13,4,'Reprobado','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1816,377,66,1,13,4,'Reprobado','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1817,377,67,1,13,4,'Reprobado','1er Grado \'A\'',8.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1818,377,69,1,13,4,'Reprobado','1er Grado \'A\'',11.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1819,377,68,1,13,4,'Reprobado','1er Grado \'A\'',11.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1820,377,70,1,13,4,'Reprobado','1er Grado \'A\'',9.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1821,377,71,1,13,4,'Reprobado','1er Grado \'A\'',10.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1822,377,73,1,13,4,'Reprobado','1er Grado \'A\'',15.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1823,377,74,1,13,4,'Reprobado','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1824,377,72,1,13,4,'Reprobado','1er Grado \'A\'',7.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1825,378,65,1,19,0,'Promovido','1er Grado \'A\'',17.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1826,378,66,1,19,0,'Promovido','1er Grado \'A\'',18.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1827,378,67,1,19,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1828,378,69,1,19,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1829,378,68,1,19,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1830,378,70,1,19,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1831,378,71,1,19,0,'Promovido','1er Grado \'A\'',16.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1832,378,73,1,19,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1833,378,74,1,19,0,'Promovido','1er Grado \'A\'',16.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1834,378,72,1,19,0,'Promovido','1er Grado \'A\'',16.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1835,379,65,1,15,1,'Aplazado','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1836,379,66,1,15,1,'Aplazado','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1837,379,67,1,15,1,'Aplazado','1er Grado \'A\'',9.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1838,379,69,1,15,1,'Aplazado','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1839,379,68,1,15,1,'Aplazado','1er Grado \'A\'',11.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1840,379,70,1,15,1,'Aplazado','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1841,379,71,1,15,1,'Aplazado','1er Grado \'A\'',16.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1842,379,73,1,15,1,'Aplazado','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1843,379,74,1,15,1,'Aplazado','1er Grado \'A\'',16.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1844,379,72,1,15,1,'Aplazado','1er Grado \'A\'',11.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1845,380,65,1,15,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1846,380,66,1,15,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1847,380,67,1,15,0,'Promovido','1er Grado \'A\'',17.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1848,380,69,1,15,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1849,380,68,1,15,0,'Promovido','1er Grado \'A\'',18.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1850,380,70,1,15,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1851,380,71,1,15,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1852,380,73,1,15,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1853,380,74,1,15,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1854,380,72,1,15,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1855,382,65,1,16,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1856,382,66,1,16,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1857,382,67,1,16,0,'Promovido','1er Grado \'A\'',11.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1858,382,69,1,16,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1859,382,68,1,16,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1860,382,70,1,16,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1861,382,71,1,16,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1862,382,73,1,16,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1863,382,74,1,16,0,'Promovido','1er Grado \'A\'',16.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1864,382,72,1,16,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1865,383,65,1,16,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1866,383,66,1,16,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1867,383,67,1,16,0,'Promovido','1er Grado \'A\'',11.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1868,383,69,1,16,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1869,383,68,1,16,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1870,383,70,1,16,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1871,383,71,1,16,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1872,383,73,1,16,0,'Promovido','1er Grado \'A\'',16.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1873,383,74,1,16,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1874,383,72,1,16,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1875,384,65,1,15,1,'Aplazado','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1876,384,66,1,15,1,'Aplazado','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1877,384,67,1,15,1,'Aplazado','1er Grado \'A\'',10.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1878,384,69,1,15,1,'Aplazado','1er Grado \'A\'',15.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1879,384,68,1,15,1,'Aplazado','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1880,384,70,1,15,1,'Aplazado','1er Grado \'A\'',11.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1881,384,71,1,15,1,'Aplazado','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1882,384,73,1,15,1,'Aplazado','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1883,384,74,1,15,1,'Aplazado','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1884,384,72,1,15,1,'Aplazado','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1885,385,65,1,15,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1886,385,66,1,15,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1887,385,67,1,15,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1888,385,69,1,15,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1889,385,68,1,15,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1890,385,70,1,15,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1891,385,71,1,15,0,'Promovido','1er Grado \'A\'',11.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1892,385,73,1,15,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1893,385,74,1,15,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1894,385,72,1,15,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1895,386,65,1,15,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1896,386,66,1,15,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1897,386,67,1,15,0,'Promovido','1er Grado \'A\'',11.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1898,386,69,1,15,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1899,386,68,1,15,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1900,386,70,1,15,0,'Promovido','1er Grado \'A\'',11.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1901,386,71,1,15,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1902,386,73,1,15,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1903,386,74,1,15,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1904,386,72,1,15,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1905,387,65,1,17,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1906,387,66,1,17,0,'Promovido','1er Grado \'A\'',17.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1907,387,67,1,17,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1908,387,69,1,17,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1909,387,68,1,17,0,'Promovido','1er Grado \'A\'',16.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1910,387,70,1,17,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1911,387,71,1,17,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1912,387,73,1,17,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1913,387,74,1,17,0,'Promovido','1er Grado \'A\'',18.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1914,387,72,1,17,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1915,388,65,1,16,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1916,388,66,1,16,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1917,388,67,1,16,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1918,388,69,1,16,0,'Promovido','1er Grado \'A\'',12.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1919,388,68,1,16,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1920,388,70,1,16,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1921,388,71,1,16,0,'Promovido','1er Grado \'A\'',14.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1922,388,73,1,16,0,'Promovido','1er Grado \'A\'',15.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1923,388,74,1,16,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
INSERT INTO "calificacionesold" VALUES (1924,388,72,1,16,0,'Promovido','1er Grado \'A\'',13.00,'2025-05-22',NULL,'2025-05-22 21:36:50','2025-05-22 21:36:50');
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "docentes" (
  "id_docente" bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  "nombre" varchar(50) NOT NULL,
  "apellido" varchar(50) NOT NULL,
  "dni" varchar(20) DEFAULT NULL,
  "fecha_nacimiento" date DEFAULT NULL,
  "direccion" varchar(200) DEFAULT NULL,
  "telefono" varchar(20) DEFAULT NULL,
  "email" varchar(100) DEFAULT NULL,
  "fecha_contratacion" date DEFAULT NULL,
  "id_nivel" bigint(20) unsigned DEFAULT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  PRIMARY KEY ("id_docente"),
  KEY "docentes_id_nivel_foreign" ("id_nivel"),
  CONSTRAINT "docentes_id_nivel_foreign" FOREIGN KEY ("id_nivel") REFERENCES "niveles" ("id_nivel")
);
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO "docentes" VALUES (33,'Luz Elena','Miranda Velasco',NULL,NULL,NULL,NULL,NULL,'1990-03-13',3,'2025-05-06 23:10:20','2025-05-06 23:11:22');
INSERT INTO "docentes" VALUES (34,'Marcela Isabel','Arica Benites',NULL,NULL,NULL,NULL,NULL,'1990-03-13',3,'2025-05-06 23:12:03','2025-05-06 23:12:03');
INSERT INTO "docentes" VALUES (35,'Jenny','Guarderas Delgado',NULL,NULL,NULL,NULL,NULL,'1990-03-13',3,'2025-05-06 23:12:47','2025-05-21 20:36:42');
INSERT INTO "docentes" VALUES (36,'Bertha Marisela','Seminario Leon',NULL,NULL,NULL,NULL,NULL,'1990-03-13',3,'2025-05-06 23:13:23','2025-05-06 23:13:23');
INSERT INTO "docentes" VALUES (37,'Teresa Mercedes','Vente Cuba',NULL,NULL,NULL,NULL,NULL,'1990-03-13',3,'2025-05-06 23:13:51','2025-05-06 23:13:51');
INSERT INTO "docentes" VALUES (38,'Moisés','More Ancajima',NULL,NULL,NULL,NULL,NULL,'1990-03-13',3,'2025-05-06 23:38:23','2025-05-06 23:38:49');
INSERT INTO "docentes" VALUES (40,'Sonia','Palacios Chavarry',NULL,NULL,NULL,NULL,NULL,'1995-03-15',3,'2025-05-21 20:34:01','2025-05-21 20:34:01');
INSERT INTO "docentes" VALUES (41,'Cesar','Abramonte Rufino',NULL,NULL,NULL,NULL,NULL,'1995-03-15',3,'2025-05-21 20:37:52','2025-05-21 20:37:52');
INSERT INTO "docentes" VALUES (42,'Celia','Quevedo Valdiviezo',NULL,NULL,NULL,NULL,NULL,'1995-03-15',3,'2025-05-21 20:38:25','2025-05-21 21:37:37');
INSERT INTO "docentes" VALUES (43,'Julia Janet','Espinoza Morán',NULL,NULL,NULL,NULL,NULL,'1995-03-15',3,'2025-05-21 21:40:00','2025-05-21 21:40:00');
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "estudiante_apoderado" (
  "id_estudiante" bigint(20) unsigned NOT NULL,
  "id_apoderado" bigint(20) unsigned NOT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  PRIMARY KEY ("id_estudiante","id_apoderado"),
  KEY "estudiante_apoderado_id_apoderado_foreign" ("id_apoderado"),
  CONSTRAINT "estudiante_apoderado_id_apoderado_foreign" FOREIGN KEY ("id_apoderado") REFERENCES "apoderados" ("id_apoderado") ON DELETE CASCADE,
  CONSTRAINT "estudiante_apoderado_id_estudiante_foreign" FOREIGN KEY ("id_estudiante") REFERENCES "estudiantes" ("id_estudiante") ON DELETE CASCADE
);
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO "estudiante_apoderado" VALUES (216,2,'2025-04-02 07:17:16','2025-04-02 07:17:16');
INSERT INTO "estudiante_apoderado" VALUES (218,2,'2025-04-02 06:18:11','2025-04-02 06:18:11');
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "estudiantes" (
  "id_estudiante" bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  "nombre" varchar(50) NOT NULL,
  "apellido" varchar(50) NOT NULL,
  "dni" varchar(20) DEFAULT NULL,
  "fecha_nacimiento" date DEFAULT NULL,
  "telefono" varchar(20) DEFAULT NULL,
  "id_aula" bigint(20) unsigned DEFAULT NULL,
  "fecha_ingreso" date DEFAULT NULL,
  "estado" enum('Activo','Retirado','Egresado') NOT NULL DEFAULT 'Activo',
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  PRIMARY KEY ("id_estudiante"),
  KEY "estudiantes_id_aula_foreign" ("id_aula"),
  CONSTRAINT "estudiantes_id_aula_foreign" FOREIGN KEY ("id_aula") REFERENCES "aulas" ("id_aula") ON DELETE SET NULL
);
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO "estudiantes" VALUES (4,'Jeanette','Hegmann','87058687',NULL,'918902255',12,NULL,'Activo','2025-03-30 21:42:21','2025-03-30 21:47:42');
INSERT INTO "estudiantes" VALUES (5,'Cayla','Sanford','55472302',NULL,'935062974',16,NULL,'Activo','2025-03-30 21:42:21','2025-03-30 21:47:54');
INSERT INTO "estudiantes" VALUES (6,'Shanelle','Murray','16528584',NULL,'902125844',24,NULL,'Activo','2025-03-30 21:42:21','2025-03-30 21:48:04');
INSERT INTO "estudiantes" VALUES (7,'Cindy','Lakin','85443346',NULL,'963974484',20,NULL,'Activo','2025-03-30 21:42:21','2025-03-30 21:48:14');
INSERT INTO "estudiantes" VALUES (8,'Caden','Treutel','27465142',NULL,'969665265',18,NULL,'Activo','2025-03-30 21:42:21','2025-03-30 21:48:25');
INSERT INTO "estudiantes" VALUES (9,'Brisa','Douglas','50385712','2008-04-14','951953438',28,'2022-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (10,'Janie','Wisozk','56080697','2010-10-01','995093641',28,'2023-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (11,'Daphnee','Lindgren','49345608','2010-07-13','931639083',28,'2024-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (12,'Cielo','Steuber','56740985','2007-08-20','966317895',28,'2022-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (13,'Lincoln','Kozey','09747260','2013-02-24','971996540',28,'2023-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (14,'Madge','Koch','47878234','2009-07-08','975550296',28,'2024-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (15,'Magdalena','Braun','12233458','2009-03-30','954748096',28,'2022-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (16,'Karianne','Ritchie','38492009','2013-03-15','949128123',28,'2024-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (17,'Baby','Davis','92522576','2011-11-29','957506900',28,'2024-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (18,'Luis','Mohr','31344292','2010-12-03','988262571',28,'2023-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (19,'Julia','Koepp','27992456','2011-07-19','981634216',29,'2023-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (20,'Nasir','Simonis','76380699','2011-08-21','961037956',29,'2022-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (21,'Aliza','Jacobi','84448729','2009-11-25','960032754',29,'2023-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (22,'Laurine','Nitzsche','83157955','2010-12-08','903048394',29,'2023-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (23,'Alanna','Herman','32684858','2010-10-04','954127384',29,'2023-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (24,'Dolores','Kiehn','99404705','2011-02-11','976152227',29,'2022-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (25,'Merl','Jacobs','55891728','2009-04-17','955077997',29,'2024-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (26,'Albertha','Von','74402764','2012-05-18','906508178',29,'2024-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (27,'Eula','Conn','94359742','2011-05-09','997179635',29,'2023-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (28,'Cornell','O\'Reilly','87908050','2008-10-16','946143728',29,'2024-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (29,'Alyce','Breitenberg','41399713','2011-02-02','973721491',8,'2024-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (30,'Joyce','Davis','94116348','2010-11-28','995574532',8,'2024-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (31,'Damaris','O\'Conner','23264246','2010-07-27','925737004',8,'2023-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (32,'Milan','Simonis','16434327','2012-04-29','957333498',8,'2024-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (33,'Cindy','Considine','51289927','2008-07-11','972281552',8,'2022-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (34,'Luis','Kuphal','04734319','2008-03-20','970192668',8,'2024-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (35,'Samara','Kshlerin','50176142','2007-10-27','939788236',8,'2023-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (36,'Stone','Auer','16096367','2013-02-10','962450725',8,'2022-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (37,'Jordon','Jacobs','99540960','2009-07-24','916439106',8,'2022-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (38,'Monica','Towne','55243897','2007-07-01','932739338',8,'2022-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (39,'Arden','Johnston','31605266','2012-02-05','925489286',9,'2022-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (40,'Jacky','Wolff','97562367','2008-07-16','922546785',9,'2023-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (41,'Kaden','Fahey','77080204','2008-08-29','964516585',9,'2023-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (42,'Earnestine','Veum','54936255','2012-01-24','956056932',9,'2024-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (43,'Colleen','Nicolas','69887924','2007-12-16','900231507',9,'2022-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (44,'Marco','Bernier','60855500','2010-11-04','937042986',9,'2023-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (45,'Lia','Kilback','54321109','2012-04-27','990126809',9,'2024-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (46,'Kelsi','Paucek','16578754','2011-03-15','926022304',9,'2024-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (47,'Ruthe','Nitzsche','53727797','2009-03-31','979680311',9,'2022-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (48,'Adrain','Kunde','38303311','2008-11-28','971446770',9,'2022-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (49,'Tyler','Wilderman','85122231','2008-09-01','908947709',10,'2023-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (50,'Marge','Dickens','71375111','2012-06-13','908782488',10,'2023-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (51,'Sasha','Daugherty','66501828','2011-07-23','995362409',10,'2024-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (52,'Blair','O\'Conner','68574843','2008-06-24','901655926',10,'2023-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (53,'Norwood','Abshire','59950834','2010-05-23','939154810',10,'2022-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (54,'Cordie','Altenwerth','06302538','2010-05-10','915914186',10,'2022-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (55,'Marco','Goyette','44058920','2008-02-06','940642768',10,'2024-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (56,'Maureen','Nader','98666922','2011-06-24','947401496',10,'2023-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (57,'Rosella','Lebsack','15623844','2012-12-31','975831470',10,'2024-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (58,'Stacy','Rosenbaum','89657603','2008-06-01','945222887',10,'2022-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (59,'Hunter','Berge','64025066','2008-11-08','918996169',11,'2022-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (60,'Jonathan','Abshire','98885469','2009-05-09','982952544',11,'2023-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (61,'Alvina','Wunsch','72671559','2010-01-18','905981730',11,'2022-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (62,'Raleigh','Donnelly','53230473','2008-05-14','906715740',11,'2024-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (63,'Rodrick','Bashirian','39080965','2013-03-20','957251988',11,'2024-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (64,'Boris','Waters','60922713','2008-03-09','961758782',11,'2022-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (65,'Violet','Jones','29298125','2011-07-13','912953819',11,'2024-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (66,'Roxane','Jones','58465003','2008-07-03','942573331',11,'2023-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (67,'Dandre','Stokes','54140859','2008-03-11','981472516',11,'2022-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (68,'Jeremie','Hamill','77172577','2011-03-04','977023003',11,'2022-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (69,'Porter','Haley','24369608','2009-04-22','943802021',12,'2022-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (70,'Buster','Schulist','70361233','2010-12-10','949217326',12,'2022-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (71,'Garnett','Gulgowski','62279318','2011-12-21','992150851',12,'2022-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (72,'Myron','Ritchie','20739182','2011-05-07','955490734',12,'2023-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (73,'Dewayne','DuBuque','53602260','2011-12-19','949284448',12,'2024-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (74,'Conner','Deckow','56445473','2011-09-30','970883747',12,'2024-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (75,'Garrick','Lakin','31237133','2007-06-25','952206761',12,'2024-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (76,'Natasha','Fritsch','10331863','2008-04-02','951179015',12,'2022-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (77,'Sasha','Upton','74577505','2007-05-04','984058594',12,'2023-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (78,'Andreane','Jacobi','12845097','2009-04-04','965263690',12,'2022-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (79,'Jameson','Christiansen','74477853','2012-11-25','987598051',13,'2024-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (80,'Agnes','Bauch','56666318','2013-03-03','920602226',13,'2022-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (81,'Connie','Thompson','81850471','2007-12-02','924420680',13,'2024-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (82,'Lia','Koelpin','19912677','2011-04-16','975502141',13,'2023-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (83,'Selina','Daugherty','83922269','2007-11-01','946341265',13,'2024-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (84,'Bernard','Schuster','55982188','2009-12-11','942606110',13,'2023-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (85,'Hulda','Labadie','34615625','2013-02-24','989498808',13,'2024-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (86,'Amber','Howell','51955555','2009-01-19','964646005',13,'2024-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (87,'Stefanie','Rath','53002748','2009-05-06','964483115',13,'2024-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (88,'Flossie','Rempel','68391937','2010-12-18','903042000',13,'2022-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (89,'Fabian','Collier','63400063','2009-06-25','937280196',14,'2022-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (90,'Frederic','Gerlach','80226110','2009-02-07','981842753',14,'2024-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (91,'Jordyn','Jast','74963366','2007-09-11','932195116',14,'2023-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (92,'Nona','Willms','52118473','2008-01-30','925733698',14,'2024-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (93,'Aylin','Reynolds','24340097','2007-05-08','989036469',14,'2024-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (94,'Meta','Hahn','47614241','2009-12-31','969235641',14,'2022-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (95,'Zoie','Sauer','90571072','2010-05-16','959747316',14,'2022-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (96,'Maxwell','Bartoletti','66957815','2009-03-06','920677384',14,'2023-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (97,'Alfred','Hagenes','09357713','2010-02-05','943381925',14,'2023-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (98,'Liza','Breitenberg','63839769','2009-04-03','934157743',14,'2024-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (99,'Kaci','Ortiz','76576625','2011-06-17','902463215',15,'2022-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (100,'Jennifer','Wisoky','82160519','2009-12-06','970098998',15,'2024-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (101,'Victor','Witting','69459340','2012-01-01','935196694',15,'2022-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (102,'Derrick','Heidenreich','19122537','2010-01-24','961308075',15,'2024-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (103,'Mohamed','Borer','88895811','2007-11-15','984015240',15,'2023-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (104,'Clemmie','Denesik','14748306','2011-08-22','951892387',15,'2024-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (105,'Bernhard','Cassin','94055090','2011-06-23','980394721',15,'2022-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (106,'Mariela','Zemlak','29264433','2008-03-30','913362595',15,'2023-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (107,'Christophe','Hilpert','30951977','2007-12-06','946119945',15,'2023-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (108,'Nayeli','Rolfson','71493846','2007-12-15','949848867',15,'2024-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (109,'Dereck','Fahey','46218556','2012-02-25','958647111',16,'2024-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (110,'Ned','Harber','03353584','2008-01-07','905804995',16,'2024-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (111,'Jaquelin','Johnson','63031432','2011-04-02','907464561',16,'2024-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (112,'Elbert','Mraz','32274545','2009-02-03','996565325',16,'2023-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (113,'Shemar','Ruecker','00403539','2009-10-07','971932297',16,'2024-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (114,'Rahsaan','Berge','30062199','2008-11-01','935603214',16,'2023-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (115,'Conrad','Moen','44119977','2010-05-11','910247603',16,'2023-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (116,'Eugenia','Bode','44067291','2009-12-01','927661801',16,'2024-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (117,'Alejandrin','Boyle','15844960','2010-05-18','957444838',16,'2022-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (118,'Edison','Lueilwitz','36604908','2009-12-22','977521793',16,'2023-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (119,'Rebeka','VonRueden','97692869','2010-01-27','915393660',17,'2022-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (120,'Leonor','Hahn','53434868','2010-09-08','958222402',17,'2023-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (121,'Gilbert','Padberg','65697220','2007-10-26','913450011',17,'2022-03-30','Activo','2025-03-30 21:42:21','2025-03-30 21:42:21');
INSERT INTO "estudiantes" VALUES (122,'Jerome','D\'Amore','82476521','2012-01-14','986720753',17,'2024-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (123,'Kayden','Welch','60677948','2009-10-29','905215564',17,'2023-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (124,'Orlo','Gorczany','17287342','2010-01-19','942519051',17,'2022-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (125,'Rosendo','Koss','93535974','2008-05-11','968694729',17,'2023-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (126,'Domenica','Hodkiewicz','77512125','2008-09-03','979138711',17,'2024-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (127,'Lucinda','Lemke','43524752','2012-10-01','949898507',17,'2024-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (128,'Tina','Bogisich','88044852','2011-02-23','906557359',17,'2022-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (129,'Zula','Emard','81508823','2011-07-20','906949395',18,'2022-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (130,'Justus','Vandervort','71743974','2008-08-17','940142886',18,'2022-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (131,'Celestino','Gleason','50318086','2010-10-02','998344163',18,'2023-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (132,'Geraldine','Reilly','33325737','2013-02-15','933157851',18,'2024-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (133,'Asha','Walter','99820613','2008-02-03','901050901',18,'2023-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (134,'Amya','Rippin','77977919','2010-06-30','908383730',18,'2022-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (135,'Dorothea','Bashirian','64820757','2012-09-08','904580886',18,'2024-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (136,'Stacy','McKenzie','28786142','2007-11-21','966540701',18,'2024-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (137,'Kamren','Crooks','97707183','2008-07-08','921249155',18,'2023-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (138,'Jermaine','Pfeffer','02136747','2007-08-18','931011343',18,'2022-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (139,'Rafaela','Murphy','39270675','2010-06-11','985849682',19,'2023-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (140,'Myriam','Kutch','80421832','2010-11-29','906163676',19,'2024-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (141,'Carolanne','Bahringer','43889972','2007-05-10','911479030',19,'2023-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (142,'Maud','Parisian','75134595','2008-03-15','912412437',19,'2022-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (143,'Kattie','Boyer','95341506','2011-10-18','936695879',19,'2023-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (144,'Keenan','Monahan','35814203','2011-09-21','940407018',19,'2023-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (145,'Tania','Wuckert','99749964','2008-03-06','927504398',19,'2022-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (146,'Eleanora','Steuber','25674487','2007-11-22','933674216',19,'2022-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (147,'Camryn','Cummings','28835995','2007-05-17','953052613',19,'2023-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (148,'Dorthy','Treutel','22633923','2008-04-05','919854318',19,'2023-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (149,'Violet','Herzog','10865169','2012-08-23','980153898',20,'2022-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (150,'Jayson','Daugherty','17153140','2009-04-19','937418938',20,'2022-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (151,'Quentin','Erdman','40263523','2008-11-19','988319655',20,'2023-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (152,'Leta','Upton','08628142','2009-12-21','908182866',20,'2023-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (153,'Zelma','Roberts','48924131','2012-09-15','911470540',20,'2023-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (154,'Neoma','Barton','05501129','2008-11-23','983930217',20,'2022-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (155,'Therese','Gutmann','72368018','2008-07-31','913989219',20,'2024-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (156,'Crystal','Abshire','18081581','2009-03-10','942643298',20,'2023-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (157,'Rosemarie','Ritchie','78197381','2008-01-02','973773412',20,'2022-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (158,'Stefanie','Zemlak','23154850','2007-05-27','975646978',20,'2024-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (159,'Margarita','Thiel','67863163','2012-07-11','954927838',22,'2023-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (160,'Dora','Carroll','26262739','2010-07-17','981083158',22,'2023-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (161,'Tremaine','Prosacco','52008863','2011-03-01','938896023',22,'2022-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (162,'Wilburn','Turner','27443575','2011-12-12','970194272',22,'2024-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (163,'Allan','Kohler','29149277','2007-05-10','905681711',22,'2022-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (164,'Joey','Lemke','48061003','2008-08-17','939751929',22,'2022-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (165,'Ubaldo','Berge','15802511','2012-08-08','982877680',22,'2024-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (166,'Barry','Bayer','96316821','2012-03-30','919383041',22,'2024-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (167,'Estevan','Koch','28511701','2010-03-17','948414791',22,'2022-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (168,'Kory','Erdman','54636526','2011-05-20','954283098',22,'2024-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (169,'Emelie','Waters','08828902','2007-04-16','971609645',23,'2022-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (170,'Agustin','Koepp','34157463','2009-08-09','998716343',23,'2022-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (171,'Lily','Purdy','64136492','2008-07-18','983212318',23,'2023-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (172,'Katrina','Mosciski','42564257','2009-12-28','951712148',23,'2022-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (173,'Alta','Adams','89684984','2011-06-13','996488207',23,'2023-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (174,'Jevon','Shanahan','72913119','2012-12-30','920931983',23,'2023-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (175,'Enrique','Towne','76329731','2009-12-14','963776654',23,'2022-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (176,'Willard','Kihn','25622260','2009-10-28','901030530',23,'2023-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (177,'Amani','Ruecker','42363048','2009-04-01','905381228',23,'2023-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (178,'Heloise','Wiza','13333514','2009-07-13','920050142',23,'2022-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (179,'Madisyn','Price','48849380','2008-09-14','999292536',24,'2023-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (180,'Rolando','Gleason','60367366','2012-05-29','942684370',24,'2024-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (181,'Diana','Schaden','18538438','2013-02-04','918575539',24,'2022-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (182,'Brianne','Dickens','82756118','2008-10-19','943260353',24,'2024-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (183,'Macy','Yundt','02656711','2011-04-02','921176266',24,'2024-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (184,'Marlee','Bartoletti','86892810','2007-08-03','999175245',24,'2024-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (185,'Rasheed','Hettinger','67948544','2008-09-25','975067373',24,'2024-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (186,'Robb','O\'Conner','59694409','2010-08-26','975256047',24,'2022-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (187,'Delfina','Murray','20672623','2008-12-15','991452281',24,'2023-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (188,'Lucie','Swift','27200838','2010-11-18','927869234',24,'2022-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (189,'Jamil','Kulas','34164816','2009-09-30','925712111',25,'2024-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (190,'Caden','Fritsch','72851754','2007-06-11','990685428',25,'2024-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (191,'Ashly','Nikolaus','44646063','2012-06-14','909388208',25,'2024-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (192,'Else','Ullrich','45932095','2010-04-17','952401592',25,'2022-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (193,'Reba','Friesen','23653234','2009-10-25','985396783',25,'2023-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (194,'Ansel','Skiles','60387805','2011-02-08','931116326',25,'2022-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (195,'Narciso','Crist','61663370','2007-07-27','974732258',25,'2023-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (196,'Fletcher','Spencer','29683696','2007-06-20','976126207',25,'2022-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (197,'Dedrick','Walter','55959594','2007-12-22','972805664',25,'2023-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (198,'Norma','Leffler','79947141','2013-02-19','942125984',25,'2024-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (199,'Alexa','Bednar','12519936','2012-09-16','959996848',26,'2024-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (200,'Else','Keebler','82701834','2010-02-26','928675397',26,'2024-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (201,'Telly','Baumbach','56471010','2012-04-29','947883171',26,'2022-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (202,'Dorris','Kshlerin','21790921','2013-01-14','962880658',26,'2024-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (203,'Ryder','Nolan','10517525','2012-08-10','996994078',26,'2023-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (204,'Judy','Steuber','11791856','2008-06-22','942200842',26,'2024-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (205,'Rosemarie','Leffler','10680831','2010-06-02','974271870',26,'2022-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (206,'Adrian','Jacobs','98415574','2008-02-01','949659791',26,'2023-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (207,'Sherwood','Sauer','37157196','2010-04-15','911248592',26,'2024-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (208,'Coleman','Purdy','37380160','2010-07-11','939402724',26,'2023-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (209,'Dudley','Koss','87169577','2009-09-03','958276980',4,'2023-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (210,'Kristina','Kling','18858493','2012-11-28','901581523',4,'2023-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (211,'Anthony','Rempel','65112126','2012-11-25','903145328',4,'2024-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (212,'Rodrigo','Hammes','92938485','2007-03-31','901193378',4,'2024-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (213,'Ashlynn','Wolf','01343331','2008-01-12','998758253',4,'2022-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (214,'Gloria','Lowe','37071041','2011-11-04','992662371',4,'2024-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (215,'Ernesto','Muller','47740339','2007-06-07','937842783',4,'2022-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (216,'Hipolito','Bashirian','56621752',NULL,'991523345',4,NULL,'Activo','2025-03-30 21:42:22','2025-04-02 07:17:16');
INSERT INTO "estudiantes" VALUES (217,'Fiona','Mann','81274524','2012-12-22','940687618',4,'2023-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (218,'Jillian','Jacobi','46503103','2007-07-21','926145032',4,'2023-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (219,'Breana','Satterfield','40915612','2011-12-07','961253445',5,'2023-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (220,'Branson','Dietrich','70744924','2008-06-09','911078692',5,'2024-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (221,'Yasmeen','Skiles','00271694','2010-12-13','984981654',5,'2024-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (222,'Anita','Beatty','35915587','2007-12-03','976120525',5,'2024-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (223,'Lamont','Kulas','83203342','2012-06-11','940553787',5,'2022-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (224,'Amaya','Torp','57104002','2010-02-15','913839033',5,'2024-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (225,'Jackson','Bergnaum','16318542','2011-01-05','976301971',5,'2022-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (226,'Hillard','Larkin','57590828','2011-12-31','992101360',5,'2024-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (227,'Alia','Little','11603020','2007-08-09','975537570',5,'2024-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (228,'Jade','Bergnaum','07259517','2008-11-19','923251615',5,'2024-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (229,'Briana','Wiza','54731847','2011-12-24','956795659',6,'2022-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (230,'Garrick','Jaskolski','24772282','2008-04-03','987262338',6,'2023-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (231,'Eugene','Schamberger','30592981','2010-03-18','967608983',6,'2023-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (232,'Edd','Kunde','20094205','2012-04-29','930844030',6,'2022-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (233,'Eldon','Turcotte','47133656','2008-12-16','933800448',6,'2023-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (234,'Jessyca','Davis','94191280','2011-01-24','980900525',6,'2023-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (235,'Sabryna','Sawayn','29024357','2010-03-31','934734552',6,'2022-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (236,'Zita','Durgan','85045469','2011-05-11','915221541',6,'2024-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (237,'Efren','Kub','04232840','2011-12-31','963519702',6,'2023-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (238,'Carmine','Nikolaus','22772631','2012-02-24','998827982',6,'2024-03-30','Activo','2025-03-30 21:42:22','2025-03-30 21:42:22');
INSERT INTO "estudiantes" VALUES (239,'WAGNER LEE','FERNANDEZ CARRASCO','73887137',NULL,NULL,5,NULL,'Activo','2025-04-10 19:43:32','2025-04-10 19:43:32');
INSERT INTO "estudiantes" VALUES (240,'Marisol Yulissa','Aguirre Huertas',NULL,NULL,NULL,18,'1995-03-13','Egresado','2025-05-06 17:50:48','2025-05-06 17:50:48');
INSERT INTO "estudiantes" VALUES (241,'Cinthia Analí','Agurto Quispe',NULL,NULL,NULL,18,'1995-03-13','Egresado','2025-05-06 17:50:48','2025-05-08 18:53:34');
INSERT INTO "estudiantes" VALUES (242,'Teresa Isabel de Jesús','Álvarez Benites',NULL,NULL,NULL,18,'1995-03-13','Egresado','2025-05-06 17:50:48','2025-05-06 17:50:48');
INSERT INTO "estudiantes" VALUES (243,'Luis Miguel','Álvarez Cueva',NULL,NULL,NULL,18,'1995-03-13','Egresado','2025-05-06 17:50:48','2025-05-06 17:50:48');
INSERT INTO "estudiantes" VALUES (244,'Eberth Joel','Álvarez Morán',NULL,NULL,NULL,18,'1995-03-13','Egresado','2025-05-06 17:50:48','2025-05-08 18:55:10');
INSERT INTO "estudiantes" VALUES (245,'Juan Augusto','Avilés Zapata',NULL,NULL,NULL,18,'1995-03-13','Egresado','2025-05-06 17:50:48','2025-05-06 17:50:48');
INSERT INTO "estudiantes" VALUES (246,'Marlon Robert','Bayona Chunga',NULL,NULL,NULL,18,'1995-03-13','Egresado','2025-05-06 17:50:48','2025-05-08 18:56:16');
INSERT INTO "estudiantes" VALUES (247,'Yuli Casandra','Calvo Saldivar',NULL,NULL,NULL,18,'1995-03-13','Egresado','2025-05-06 17:50:48','2025-05-08 18:56:49');
INSERT INTO "estudiantes" VALUES (248,'Jhon Pool','Canales Frías',NULL,NULL,NULL,18,'1995-03-13','Egresado','2025-05-06 17:50:48','2025-05-08 18:57:18');
INSERT INTO "estudiantes" VALUES (249,'Erick Orlando','Carpio Guerrero',NULL,NULL,NULL,18,'1995-03-13','Egresado','2025-05-06 17:50:48','2025-05-06 17:50:48');
INSERT INTO "estudiantes" VALUES (250,'Darwin Dubiel','Castillo Navarro',NULL,NULL,NULL,18,'1995-03-13','Egresado','2025-05-06 17:50:48','2025-05-08 18:57:48');
INSERT INTO "estudiantes" VALUES (251,'Diana Carolina','Castillo Navarro',NULL,NULL,NULL,18,'1995-03-13','Egresado','2025-05-06 17:50:48','2025-05-06 17:50:48');
INSERT INTO "estudiantes" VALUES (252,'Gibson Dilmar','Castro López',NULL,NULL,NULL,18,'1995-03-13','Egresado','2025-05-06 17:50:48','2025-05-08 18:58:09');
INSERT INTO "estudiantes" VALUES (253,'Adriana Petronila','Cevallos Navarro',NULL,NULL,NULL,18,'1995-03-13','Egresado','2025-05-06 17:50:48','2025-05-06 17:50:48');
INSERT INTO "estudiantes" VALUES (254,'Bertha Isabel','Chapilliquen Cornejo',NULL,NULL,NULL,18,'1995-03-13','Egresado','2025-05-06 17:50:48','2025-05-06 17:50:48');
INSERT INTO "estudiantes" VALUES (255,'Carlos Enrique','Clement Flores',NULL,NULL,NULL,18,'1995-03-13','Egresado','2025-05-06 17:50:48','2025-05-06 17:50:48');
INSERT INTO "estudiantes" VALUES (256,'Moisés Agustín','Custodio Vinces',NULL,NULL,NULL,18,'1995-03-13','Egresado','2025-05-06 17:50:48','2025-05-06 17:50:48');
INSERT INTO "estudiantes" VALUES (257,'Mercedes Lourdes','Dioses Morales',NULL,NULL,NULL,18,'1995-03-13','Egresado','2025-05-06 17:50:48','2025-05-06 17:50:48');
INSERT INTO "estudiantes" VALUES (258,'Luis Leonardo','Erazo Garcia',NULL,NULL,NULL,18,'1995-03-13','Egresado','2025-05-06 17:50:48','2025-05-06 17:50:48');
INSERT INTO "estudiantes" VALUES (259,'Luis Francisco','Farfan Sernaque',NULL,NULL,NULL,18,'1995-03-13','Egresado','2025-05-06 17:50:48','2025-05-06 17:50:48');
INSERT INTO "estudiantes" VALUES (260,'Edson Francisco','Farfan Vargas',NULL,NULL,NULL,18,'1995-03-13','Egresado','2025-05-06 17:50:48','2025-05-06 17:50:48');
INSERT INTO "estudiantes" VALUES (261,'Pedro Arturo','Garcia Ponce',NULL,NULL,NULL,18,'1995-03-13','Egresado','2025-05-06 17:50:48','2025-05-06 17:50:48');
INSERT INTO "estudiantes" VALUES (262,'Jorge Junior','Guerrero Eche',NULL,NULL,NULL,18,'1995-03-13','Egresado','2025-05-06 17:50:48','2025-05-08 18:59:56');
INSERT INTO "estudiantes" VALUES (263,'Katherin Dolres','Gurrionero Saldarriaga',NULL,NULL,NULL,18,'1995-03-13','Egresado','2025-05-06 17:50:48','2025-05-06 17:50:48');
INSERT INTO "estudiantes" VALUES (264,'Katy Massiel','Gutierrez Eche',NULL,NULL,NULL,18,'1995-03-13','Egresado','2025-05-06 17:50:48','2025-05-06 17:50:48');
INSERT INTO "estudiantes" VALUES (265,'Nadir Elizabeth','Hidalgo Peña',NULL,NULL,NULL,18,'1995-03-13','Egresado','2025-05-06 17:50:48','2025-05-06 17:50:48');
INSERT INTO "estudiantes" VALUES (266,'Pablo','Jimenez Garcia',NULL,NULL,NULL,18,'1995-03-13','Egresado','2025-05-06 17:50:48','2025-05-06 17:50:48');
INSERT INTO "estudiantes" VALUES (267,'Samuel','Karasas Mejia',NULL,NULL,NULL,18,'1995-03-13','Egresado','2025-05-06 17:50:48','2025-05-06 17:50:48');
INSERT INTO "estudiantes" VALUES (268,'Edwing','Litano Bruno',NULL,NULL,NULL,18,'1995-03-13','Egresado','2025-05-06 17:50:48','2025-05-06 17:50:48');
INSERT INTO "estudiantes" VALUES (269,'Yriabeth Agripina','Luna Marchan',NULL,NULL,NULL,18,'1995-03-13','Egresado','2025-05-06 17:50:48','2025-05-08 19:01:07');
INSERT INTO "estudiantes" VALUES (270,'Eder Javier','Maceda Alcalde',NULL,NULL,NULL,18,'1995-03-13','Egresado','2025-05-06 17:50:48','2025-05-06 17:50:48');
INSERT INTO "estudiantes" VALUES (271,'Frank Gilbert','Marchena Saavedra',NULL,NULL,NULL,18,'1995-03-13','Egresado','2025-05-06 17:50:48','2025-05-06 17:50:48');
INSERT INTO "estudiantes" VALUES (272,'Reyna Yusara','Martinez Nizama',NULL,NULL,NULL,18,'1995-03-13','Egresado','2025-05-06 17:50:48','2025-05-06 17:50:48');
INSERT INTO "estudiantes" VALUES (273,'Carlos Lenin','Melendez Valladares',NULL,NULL,NULL,18,'1995-03-13','Egresado','2025-05-06 17:50:48','2025-05-06 17:50:48');
INSERT INTO "estudiantes" VALUES (274,'Alaín','Mendizabal Olivares',NULL,NULL,NULL,18,'1995-03-13','Egresado','2025-05-06 17:50:48','2025-05-06 17:50:48');
INSERT INTO "estudiantes" VALUES (275,'Patricia Mellisa','Morales Castro',NULL,NULL,NULL,18,'1995-03-13','Egresado','2025-05-06 17:50:48','2025-05-06 17:50:48');
INSERT INTO "estudiantes" VALUES (276,'Patricia Verónica','Morquencho Díaz',NULL,NULL,NULL,18,'1995-03-13','Egresado','2025-05-06 17:50:48','2025-05-06 17:50:48');
INSERT INTO "estudiantes" VALUES (277,'Mirian Yermep','Pejerrey Sernaqué',NULL,NULL,NULL,18,'1995-03-13','Egresado','2025-05-06 17:50:48','2025-05-06 17:50:48');
INSERT INTO "estudiantes" VALUES (278,'Esther Beatriz','Peña Barrios',NULL,NULL,NULL,18,'1995-03-13','Egresado','2025-05-06 17:50:48','2025-05-06 17:50:48');
INSERT INTO "estudiantes" VALUES (279,'Viviana Lizeth','Ramírez Hilario',NULL,NULL,NULL,18,'1995-03-13','Egresado','2025-05-06 17:50:48','2025-05-06 17:50:48');
INSERT INTO "estudiantes" VALUES (280,'Santos Eda Carolina','Romero Bancayan',NULL,NULL,NULL,18,'1995-03-13','Egresado','2025-05-06 17:50:48','2025-05-06 17:50:48');
INSERT INTO "estudiantes" VALUES (281,'Jean Carlos','Rosales Timana',NULL,NULL,NULL,18,'1995-03-13','Egresado','2025-05-06 17:50:48','2025-05-06 17:50:48');
INSERT INTO "estudiantes" VALUES (282,'Víctor Pedro','Rufino Torres',NULL,NULL,NULL,18,'1995-03-13','Egresado','2025-05-06 17:50:48','2025-05-06 17:50:48');
INSERT INTO "estudiantes" VALUES (283,'Santos Fidel','Ruiz Rivera',NULL,NULL,NULL,18,'1995-03-13','Egresado','2025-05-06 17:50:48','2025-05-06 17:50:48');
INSERT INTO "estudiantes" VALUES (284,'Andrés Martín','Yarlequé Lizama',NULL,NULL,NULL,18,'1995-03-13','Egresado','2025-05-06 17:50:48','2025-05-06 17:50:48');
INSERT INTO "estudiantes" VALUES (285,'Christopher César','Zapata Delgado',NULL,NULL,NULL,18,'1995-03-13','Egresado','2025-05-06 17:50:48','2025-05-06 17:50:48');
INSERT INTO "estudiantes" VALUES (286,'Verónica Pamela','Acedo Huerta',NULL,NULL,NULL,19,'1999-03-15','Egresado','2025-05-19 20:58:16','2025-05-19 21:03:02');
INSERT INTO "estudiantes" VALUES (287,'Julio Guillermo','Aguilar Bruno',NULL,NULL,NULL,19,'1999-03-15','Egresado','2025-05-19 20:58:16','2025-05-19 21:03:08');
INSERT INTO "estudiantes" VALUES (288,'Rosa Manuela','Barreto Ubillus',NULL,NULL,NULL,19,'1999-03-15','Egresado','2025-05-19 20:58:16','2025-05-19 21:03:12');
INSERT INTO "estudiantes" VALUES (289,'Magaly Milagros','Bayona Marchan',NULL,NULL,NULL,19,'1999-03-15','Egresado','2025-05-19 20:58:16','2025-05-19 21:03:17');
INSERT INTO "estudiantes" VALUES (290,'María Belén','Carrillo Saldarriaga',NULL,NULL,NULL,19,'1999-03-15','Egresado','2025-05-19 20:58:16','2025-05-19 21:03:20');
INSERT INTO "estudiantes" VALUES (291,'Fabiana','Cespedes Sandoval',NULL,NULL,NULL,19,'1999-03-15','Egresado','2025-05-19 20:58:16','2025-05-19 21:03:24');
INSERT INTO "estudiantes" VALUES (292,'Janeth','Chira Juarez',NULL,NULL,NULL,19,'1999-03-15','Activo','2025-05-19 20:58:16','2025-05-19 20:58:16');
INSERT INTO "estudiantes" VALUES (293,'Ana Luisa','Cornejo Vega',NULL,NULL,NULL,19,'1999-03-15','Activo','2025-05-19 20:58:16','2025-05-19 20:58:16');
INSERT INTO "estudiantes" VALUES (294,'Joel Enrique','Cruz Vasquez',NULL,NULL,NULL,19,'1999-03-15','Activo','2025-05-19 20:58:16','2025-05-19 20:58:16');
INSERT INTO "estudiantes" VALUES (295,'Tatiana Marili','Del Rosario Sernaque',NULL,NULL,NULL,19,'1999-03-15','Activo','2025-05-19 20:58:16','2025-05-19 20:58:16');
INSERT INTO "estudiantes" VALUES (296,'Jorge Luis','Farfan Maza',NULL,NULL,NULL,19,'1999-03-15','Activo','2025-05-19 20:58:16','2025-05-19 20:58:16');
INSERT INTO "estudiantes" VALUES (297,'Alexander','Garcia Amaya',NULL,NULL,NULL,19,'1999-03-15','Activo','2025-05-19 20:58:16','2025-05-19 20:58:16');
INSERT INTO "estudiantes" VALUES (298,'Karen Dianaly Corona','Garcia Arizaga',NULL,NULL,NULL,19,'1999-03-15','Activo','2025-05-19 20:58:16','2025-05-19 20:58:16');
INSERT INTO "estudiantes" VALUES (299,'Jhon Henry','Gonzales Guevara',NULL,NULL,NULL,19,'1999-03-15','Activo','2025-05-19 20:58:16','2025-05-19 20:58:16');
INSERT INTO "estudiantes" VALUES (300,'Víctor Alan','Gonzales Manrique',NULL,NULL,NULL,19,'1999-03-15','Activo','2025-05-19 20:58:16','2025-05-19 20:58:16');
INSERT INTO "estudiantes" VALUES (301,'Dora Elena','Herrera Dioses',NULL,NULL,NULL,19,'1999-03-15','Activo','2025-05-19 20:58:16','2025-05-19 20:58:16');
INSERT INTO "estudiantes" VALUES (302,'Cinthya Lizeth','Huertas Carranza',NULL,NULL,NULL,19,'1999-03-15','Activo','2025-05-19 20:58:16','2025-05-19 20:58:16');
INSERT INTO "estudiantes" VALUES (303,'Maximiliano Luis','Huertas Gomez',NULL,NULL,NULL,19,'1999-03-15','Activo','2025-05-19 20:58:16','2025-05-19 20:58:16');
INSERT INTO "estudiantes" VALUES (304,'Armando ','Jimenez Garces',NULL,NULL,NULL,19,'1999-03-15','Activo','2025-05-19 20:58:16','2025-05-19 20:58:16');
INSERT INTO "estudiantes" VALUES (305,'Mareia Lucía','Jimenez Lopez',NULL,NULL,NULL,19,'1999-03-15','Activo','2025-05-19 20:58:16','2025-05-19 20:58:16');
INSERT INTO "estudiantes" VALUES (306,'Cristian Enrique','Medina Seminario',NULL,NULL,NULL,19,'1999-03-15','Activo','2025-05-19 20:58:16','2025-05-19 20:58:16');
INSERT INTO "estudiantes" VALUES (307,'Alex Alberto','Miñope Machare',NULL,NULL,NULL,19,'1999-03-15','Activo','2025-05-19 20:58:16','2025-05-19 20:58:16');
INSERT INTO "estudiantes" VALUES (308,'Carlos Antonio','Mogollon Peña',NULL,NULL,NULL,19,'1999-03-15','Activo','2025-05-19 20:58:16','2025-05-19 20:58:16');
INSERT INTO "estudiantes" VALUES (309,'Maria Guisela','Monasterio Fernandez',NULL,NULL,NULL,19,'1999-03-15','Activo','2025-05-19 20:58:16','2025-05-19 20:58:16');
INSERT INTO "estudiantes" VALUES (310,'Guiulliana Marelyn','Moscol Bobbio',NULL,NULL,NULL,19,'1999-03-15','Activo','2025-05-19 20:58:16','2025-05-19 20:58:16');
INSERT INTO "estudiantes" VALUES (311,'Tania Marilú','Nicolini Valladares',NULL,NULL,NULL,19,'1999-03-15','Activo','2025-05-19 20:58:16','2025-05-19 20:58:16');
INSERT INTO "estudiantes" VALUES (312,'Ana María del Carmen','Nores Mesta',NULL,NULL,NULL,19,'1999-03-15','Activo','2025-05-19 20:58:16','2025-05-19 20:58:16');
INSERT INTO "estudiantes" VALUES (313,'Carlos Reynaldo','Olaya Ponce',NULL,NULL,NULL,19,'1999-03-15','Activo','2025-05-19 20:58:16','2025-05-19 20:58:16');
INSERT INTO "estudiantes" VALUES (314,'Cristhian Alberto','Olaya Ponce',NULL,NULL,NULL,19,'1999-03-15','Activo','2025-05-19 20:58:16','2025-05-19 20:58:16');
INSERT INTO "estudiantes" VALUES (315,'Diana Patricia','Peña Flores',NULL,NULL,NULL,19,'1999-03-15','Activo','2025-05-19 20:58:16','2025-05-19 20:58:16');
INSERT INTO "estudiantes" VALUES (316,'Cinthia Lourdes','Quevedo Morales',NULL,NULL,NULL,19,'1999-03-15','Activo','2025-05-19 20:58:16','2025-05-19 20:58:16');
INSERT INTO "estudiantes" VALUES (317,'Luis Miguel','Renteria Ruiz',NULL,NULL,NULL,19,'1999-03-15','Activo','2025-05-19 20:58:16','2025-05-19 20:58:16');
INSERT INTO "estudiantes" VALUES (318,'Elberth Daniel','Rivera Castillo',NULL,NULL,NULL,19,'1999-03-15','Activo','2025-05-19 20:58:16','2025-05-19 20:58:16');
INSERT INTO "estudiantes" VALUES (319,'William Alberto','Rivera Peña',NULL,NULL,NULL,19,'1999-03-15','Activo','2025-05-19 20:58:16','2025-05-19 20:58:16');
INSERT INTO "estudiantes" VALUES (320,'Claudia Analy','Roman Camacho',NULL,NULL,NULL,19,'1999-03-15','Activo','2025-05-19 20:58:16','2025-05-19 20:58:16');
INSERT INTO "estudiantes" VALUES (321,'Erickson David','Rosales Sobrino',NULL,NULL,NULL,19,'1999-03-15','Activo','2025-05-19 20:58:16','2025-05-19 20:58:16');
INSERT INTO "estudiantes" VALUES (322,'José Carlos','Salazar Lecarnaque',NULL,NULL,NULL,19,'1999-03-15','Activo','2025-05-19 20:58:16','2025-05-19 20:58:16');
INSERT INTO "estudiantes" VALUES (323,'Fanny Janeth','Salazar Sanchez',NULL,NULL,NULL,19,'1999-03-15','Activo','2025-05-19 20:58:16','2025-05-19 20:58:16');
INSERT INTO "estudiantes" VALUES (324,'Jesús Carlos','Salcedo Angeldonis',NULL,NULL,NULL,19,'1999-03-15','Activo','2025-05-19 20:58:16','2025-05-19 20:58:16');
INSERT INTO "estudiantes" VALUES (325,'Jorge Andrés','Sanchez Arcela',NULL,NULL,NULL,19,'1999-03-15','Activo','2025-05-19 20:58:16','2025-05-19 20:58:16');
INSERT INTO "estudiantes" VALUES (326,'Germán Enrique','Sojo Ramos',NULL,NULL,NULL,19,'1999-03-15','Activo','2025-05-19 20:58:16','2025-05-19 20:58:16');
INSERT INTO "estudiantes" VALUES (327,'Fabiola Lisette','Suclupe Garcia',NULL,NULL,NULL,19,'1999-03-15','Activo','2025-05-19 20:58:16','2025-05-19 20:58:16');
INSERT INTO "estudiantes" VALUES (328,'Heli Steward','Valladares Flores',NULL,NULL,NULL,19,'1999-03-15','Activo','2025-05-19 20:58:16','2025-05-19 20:58:16');
INSERT INTO "estudiantes" VALUES (329,'Verónica Yanet','Vite Cardoza',NULL,NULL,NULL,19,'1999-03-15','Activo','2025-05-19 20:58:16','2025-05-19 20:58:16');
INSERT INTO "estudiantes" VALUES (330,' Joannes','Aguirre Cueva',NULL,NULL,NULL,18,'2000-03-15','Activo','2025-05-19 21:42:24','2025-05-19 21:42:24');
INSERT INTO "estudiantes" VALUES (331,'Miguel Bryan','Aguirre Cueva',NULL,NULL,NULL,18,'2000-03-15','Egresado','2025-05-19 21:42:24','2025-05-22 21:08:21');
INSERT INTO "estudiantes" VALUES (332,' Teresa Isabel de Jesús','Álvarez Benites',NULL,NULL,NULL,18,'2000-03-15','Activo','2025-05-19 21:42:24','2025-05-19 21:42:24');
INSERT INTO "estudiantes" VALUES (333,' Cinthia Clydy','Álvarez Mora',NULL,NULL,NULL,18,'2000-03-15','Activo','2025-05-19 21:42:24','2025-05-19 21:42:24');
INSERT INTO "estudiantes" VALUES (334,' Erika Yovana','Aranda Ramirez',NULL,NULL,NULL,18,'2000-03-15','Activo','2025-05-19 21:42:24','2025-05-19 21:42:24');
INSERT INTO "estudiantes" VALUES (335,' Victor hugo ','Arcelles Peña',NULL,NULL,NULL,18,'2000-03-15','Activo','2025-05-19 21:42:24','2025-05-19 21:42:24');
INSERT INTO "estudiantes" VALUES (336,' Angel Miguel','Ayala Marchan',NULL,NULL,NULL,18,'2000-03-15','Activo','2025-05-19 21:42:24','2025-05-19 21:42:24');
INSERT INTO "estudiantes" VALUES (337,' Kattya Pierina','Barreto Chiroque',NULL,NULL,NULL,18,'2000-03-15','Activo','2025-05-19 21:42:24','2025-05-19 21:42:24');
INSERT INTO "estudiantes" VALUES (338,' Jorge','Canales Barreto',NULL,NULL,NULL,18,'2000-03-15','Activo','2025-05-19 21:42:24','2025-05-19 21:42:24');
INSERT INTO "estudiantes" VALUES (339,' Miriam Elizabeth ','Carreño Prieto',NULL,NULL,NULL,18,'2000-03-15','Activo','2025-05-19 21:42:24','2025-05-19 21:42:24');
INSERT INTO "estudiantes" VALUES (340,' Claudia Paola','Castillo Talledo',NULL,NULL,NULL,18,'2000-03-15','Activo','2025-05-19 21:42:24','2025-05-19 21:42:24');
INSERT INTO "estudiantes" VALUES (341,' Lisbeth Maribel','Chero Lopez',NULL,NULL,NULL,18,'2000-03-15','Activo','2025-05-19 21:42:24','2025-05-19 21:42:24');
INSERT INTO "estudiantes" VALUES (342,' Antoanette','Clavijo Dominguez',NULL,NULL,NULL,18,'2000-03-15','Activo','2025-05-19 21:42:24','2025-05-19 21:42:24');
INSERT INTO "estudiantes" VALUES (343,' Jorge Armando','Clavijo Sullon',NULL,NULL,NULL,18,'2000-03-15','Activo','2025-05-19 21:42:24','2025-05-19 21:42:24');
INSERT INTO "estudiantes" VALUES (344,' Berenise','Cuello Andrade',NULL,NULL,NULL,18,'2000-03-15','Activo','2025-05-19 21:42:24','2025-05-19 21:42:24');
INSERT INTO "estudiantes" VALUES (345,'Darwin Alexis','Cruz Arismendiz',NULL,NULL,NULL,18,'2000-03-15','Activo','2025-05-19 21:42:24','2025-05-19 21:42:24');
INSERT INTO "estudiantes" VALUES (346,' Brenda Lili','Cruz Zapata',NULL,NULL,NULL,18,'2000-03-15','Activo','2025-05-19 21:42:24','2025-05-19 21:42:24');
INSERT INTO "estudiantes" VALUES (347,' José Luis ','Del Rosario Moscol',NULL,NULL,NULL,18,'2000-03-15','Activo','2025-05-19 21:42:24','2025-05-19 21:42:24');
INSERT INTO "estudiantes" VALUES (348,' Elio Janz','Eche Martinez',NULL,NULL,NULL,18,'2000-03-15','Activo','2025-05-19 21:42:24','2025-05-19 21:42:24');
INSERT INTO "estudiantes" VALUES (349,' Gabriel Augusto','Garcia Andrade',NULL,NULL,NULL,18,'2000-03-15','Activo','2025-05-19 21:42:24','2025-05-19 21:42:24');
INSERT INTO "estudiantes" VALUES (350,' Michel Yoel','Granda Castillo',NULL,NULL,NULL,18,'2000-03-15','Activo','2025-05-19 21:42:24','2025-05-19 21:42:24');
INSERT INTO "estudiantes" VALUES (351,' Luis Guillermo','Guitierrez Olaya',NULL,NULL,NULL,18,'2000-03-15','Activo','2025-05-19 21:42:24','2025-05-19 21:42:24');
INSERT INTO "estudiantes" VALUES (352,' Elias','Hurtado Vinces',NULL,NULL,NULL,18,'2000-03-15','Activo','2025-05-19 21:42:24','2025-05-19 21:42:24');
INSERT INTO "estudiantes" VALUES (353,' Sergio Adams','Lazo Hurtado',NULL,NULL,NULL,18,'2000-03-15','Activo','2025-05-19 21:42:24','2025-05-19 21:42:24');
INSERT INTO "estudiantes" VALUES (354,'Miguel Angel','Lloclla Ramos',NULL,NULL,NULL,18,'2000-03-15','Egresado','2025-05-19 21:42:24','2025-05-22 21:05:16');
INSERT INTO "estudiantes" VALUES (355,' Ruth Abigail ','Lopez Adrianzen',NULL,NULL,NULL,18,'2000-03-15','Activo','2025-05-19 21:42:24','2025-05-19 21:42:24');
INSERT INTO "estudiantes" VALUES (356,' Frank Roberth','Mauricio Nolasco',NULL,NULL,NULL,18,'2000-03-15','Activo','2025-05-19 21:42:24','2025-05-19 21:42:24');
INSERT INTO "estudiantes" VALUES (357,' Carlos Lenin','Melendez Valladares',NULL,NULL,NULL,18,'2000-03-15','Activo','2025-05-19 21:42:24','2025-05-19 21:42:24');
INSERT INTO "estudiantes" VALUES (358,' Jorge Luis','Mendoza Andrade',NULL,NULL,NULL,18,'2000-03-15','Activo','2025-05-19 21:42:24','2025-05-19 21:42:24');
INSERT INTO "estudiantes" VALUES (359,' José Luis','Mendoza Zevallos',NULL,NULL,NULL,18,'2000-03-15','Activo','2025-05-19 21:42:24','2025-05-19 21:42:24');
INSERT INTO "estudiantes" VALUES (360,' Luis Alberto','Miranda Fernandez',NULL,NULL,NULL,18,'2000-03-15','Activo','2025-05-19 21:42:24','2025-05-19 21:42:24');
INSERT INTO "estudiantes" VALUES (361,' Dafne Anaiz','Mogollon Bustamante',NULL,NULL,NULL,18,'2000-03-15','Activo','2025-05-19 21:42:24','2025-05-19 21:42:24');
INSERT INTO "estudiantes" VALUES (362,' Jonathan Manuel','Mogollon Guzman',NULL,NULL,NULL,18,'2000-03-15','Activo','2025-05-19 21:42:24','2025-05-19 21:42:24');
INSERT INTO "estudiantes" VALUES (363,'David Daniel','Mogollon Martinez ',NULL,NULL,NULL,18,'2000-03-15','Activo','2025-05-19 21:42:24','2025-05-19 21:42:24');
INSERT INTO "estudiantes" VALUES (364,' Patricia Melissa','Morales Castro',NULL,NULL,NULL,18,'2000-03-15','Activo','2025-05-19 21:42:24','2025-05-19 21:42:24');
INSERT INTO "estudiantes" VALUES (365,' Santos Manuel','Morales Moran',NULL,NULL,NULL,18,'2000-03-15','Activo','2025-05-19 21:42:24','2025-05-19 21:42:24');
INSERT INTO "estudiantes" VALUES (366,' Clara Fiorella','Navarro Carhualloclla',NULL,NULL,NULL,18,'2000-03-15','Activo','2025-05-19 21:42:24','2025-05-19 21:42:24');
INSERT INTO "estudiantes" VALUES (367,' Zaida Mariella','Nicolini Valladares',NULL,NULL,NULL,18,'2000-03-15','Activo','2025-05-19 21:42:24','2025-05-19 21:42:24');
INSERT INTO "estudiantes" VALUES (368,' Jessica Brilly','Olaya Aguirre',NULL,NULL,NULL,18,'2000-03-15','Activo','2025-05-19 21:42:24','2025-05-19 21:42:24');
INSERT INTO "estudiantes" VALUES (369,' Ruth Noemi','Olivares Arismendis',NULL,NULL,NULL,18,'2000-03-15','Activo','2025-05-19 21:42:24','2025-05-19 21:42:24');
INSERT INTO "estudiantes" VALUES (370,' Katherine Elizabeth','Palacios Garcia',NULL,NULL,NULL,18,'2000-03-15','Activo','2025-05-19 21:42:24','2025-05-19 21:42:24');
INSERT INTO "estudiantes" VALUES (371,' Melisa','Peralta Coronado',NULL,NULL,NULL,18,'2000-03-15','Activo','2025-05-19 21:42:24','2025-05-19 21:42:24');
INSERT INTO "estudiantes" VALUES (372,' Luz Angelica','Prado Icanaque',NULL,NULL,NULL,18,'2000-03-15','Activo','2025-05-19 21:42:24','2025-05-19 21:42:24');
INSERT INTO "estudiantes" VALUES (373,' Lia Lisbeth','Querevalu Chirinos',NULL,NULL,NULL,18,'2000-03-15','Activo','2025-05-19 21:42:24','2025-05-19 21:42:24');
INSERT INTO "estudiantes" VALUES (374,' Cynthia Lisbeth','Reto Ayala',NULL,NULL,NULL,18,'2000-03-15','Activo','2025-05-19 21:42:24','2025-05-19 21:42:24');
INSERT INTO "estudiantes" VALUES (375,' Jose Luis ','Riveros Coronado',NULL,NULL,NULL,18,'2000-03-15','Activo','2025-05-19 21:42:24','2025-05-19 21:42:24');
INSERT INTO "estudiantes" VALUES (376,' Jhon Davis','Rosales Agurto',NULL,NULL,NULL,18,'2000-03-15','Activo','2025-05-19 21:42:24','2025-05-19 21:42:24');
INSERT INTO "estudiantes" VALUES (377,' Jorge Luis ','Ruesta Medina',NULL,NULL,NULL,18,'2000-03-15','Activo','2025-05-19 21:42:24','2025-05-19 21:42:24');
INSERT INTO "estudiantes" VALUES (378,' Ruth Linda','Ruiz Rivera',NULL,NULL,NULL,18,'2000-03-15','Activo','2025-05-19 21:42:24','2025-05-19 21:42:24');
INSERT INTO "estudiantes" VALUES (379,' Janidet Andreina','Sandoval Cruz',NULL,NULL,NULL,18,'2000-03-15','Activo','2025-05-19 21:42:24','2025-05-19 21:42:24');
INSERT INTO "estudiantes" VALUES (380,'Andy','Sernaque Garcia ',NULL,NULL,NULL,18,'2000-03-15','Activo','2025-05-19 21:42:24','2025-05-19 21:42:24');
INSERT INTO "estudiantes" VALUES (381,' Jose Hernan','Silva Talledo',NULL,NULL,NULL,18,'2000-03-15','Activo','2025-05-19 21:42:24','2025-05-19 21:42:24');
INSERT INTO "estudiantes" VALUES (382,' Marlee Lizeth','Solano Boyer',NULL,NULL,NULL,18,'2000-03-15','Activo','2025-05-19 21:42:24','2025-05-19 21:42:24');
INSERT INTO "estudiantes" VALUES (383,' Segundo Eduardo','Temoche Barreto',NULL,NULL,NULL,18,'2000-03-15','Activo','2025-05-19 21:42:24','2025-05-19 21:42:24');
INSERT INTO "estudiantes" VALUES (384,' Susan Maryory','Temoche Garcia',NULL,NULL,NULL,18,'2000-03-15','Activo','2025-05-19 21:42:24','2025-05-19 21:42:24');
INSERT INTO "estudiantes" VALUES (385,' Jose David','Torres Carrillo',NULL,NULL,NULL,18,'2000-03-15','Activo','2025-05-19 21:42:24','2025-05-19 21:42:24');
INSERT INTO "estudiantes" VALUES (386,' Clever Smith','Valladares Benites',NULL,NULL,NULL,18,'2000-03-15','Activo','2025-05-19 21:42:24','2025-05-19 21:42:24');
INSERT INTO "estudiantes" VALUES (387,' Sandra Mercedes','Vilela Vilela',NULL,NULL,NULL,18,'2000-03-15','Activo','2025-05-19 21:42:24','2025-05-19 21:42:24');
INSERT INTO "estudiantes" VALUES (388,' Irwing Dubberly','Yarleque Ayala',NULL,NULL,NULL,18,'2000-03-15','Activo','2025-05-19 21:42:24','2025-05-19 21:42:24');
INSERT INTO "estudiantes" VALUES (389,'Veronica Pamela','Acedo Huertas',NULL,NULL,NULL,20,'2000-03-15','Activo','2025-05-21 20:20:16','2025-05-21 20:20:16');
INSERT INTO "estudiantes" VALUES (390,'Eber Joel ','Alvarez Moran',NULL,NULL,NULL,20,'2000-03-15','Activo','2025-05-21 20:20:16','2025-05-21 20:20:16');
INSERT INTO "estudiantes" VALUES (391,'Magaly Milagros','Bayona Marchan',NULL,NULL,NULL,20,'2000-03-15','Activo','2025-05-21 20:20:16','2025-05-21 20:20:16');
INSERT INTO "estudiantes" VALUES (392,'Rogger','Benites Garcia',NULL,NULL,NULL,20,'2000-03-15','Activo','2025-05-21 20:20:16','2025-05-21 20:20:16');
INSERT INTO "estudiantes" VALUES (393,'Maria Belen ','Carrillo Saldarriaga',NULL,NULL,NULL,20,'2000-03-15','Activo','2025-05-21 20:20:16','2025-05-21 20:20:16');
INSERT INTO "estudiantes" VALUES (394,'Darwin Dubiel','Castillo Navarro',NULL,NULL,NULL,20,'2000-03-15','Activo','2025-05-21 20:20:16','2025-05-21 20:20:16');
INSERT INTO "estudiantes" VALUES (395,'Fabiana','Cespedes Sandoval',NULL,NULL,NULL,20,'2000-03-15','Activo','2025-05-21 20:20:16','2025-05-21 20:20:16');
INSERT INTO "estudiantes" VALUES (396,'Marvin','Cespedes Sandoval',NULL,NULL,NULL,20,'2000-03-15','Activo','2025-05-21 20:20:16','2025-05-21 20:20:16');
INSERT INTO "estudiantes" VALUES (397,'Bertha','Chapilliquen Cornejo ',NULL,NULL,NULL,20,'2000-03-15','Activo','2025-05-21 20:20:16','2025-05-21 20:20:16');
INSERT INTO "estudiantes" VALUES (398,'Janeth','Chira Juarez',NULL,NULL,NULL,20,'2000-03-15','Activo','2025-05-21 20:20:16','2025-05-21 20:20:16');
INSERT INTO "estudiantes" VALUES (399,'Yovany Yanneth','Clavijo Sullon ',NULL,NULL,NULL,20,'2000-03-15','Activo','2025-05-21 20:20:16','2025-05-21 20:20:16');
INSERT INTO "estudiantes" VALUES (400,'Ana Luisa','CornejoVega',NULL,NULL,NULL,20,'2000-03-15','Activo','2025-05-21 20:20:16','2025-05-21 20:20:16');
INSERT INTO "estudiantes" VALUES (401,'Lourdes Mercedes','Dioses Morales ',NULL,NULL,NULL,20,'2000-03-15','Activo','2025-05-21 20:20:16','2025-05-21 20:20:16');
INSERT INTO "estudiantes" VALUES (402,'Luis Leonardo','Erazo Garcia',NULL,NULL,NULL,20,'2000-03-15','Activo','2025-05-21 20:20:16','2025-05-21 20:20:16');
INSERT INTO "estudiantes" VALUES (403,'Luis Francisco','Farfan Sernaque',NULL,NULL,NULL,20,'2000-03-15','Activo','2025-05-21 20:20:16','2025-05-21 20:20:16');
INSERT INTO "estudiantes" VALUES (404,'Alexander','Garcia Amaya ',NULL,NULL,NULL,20,'2000-03-15','Activo','2025-05-21 20:20:16','2025-05-21 20:20:16');
INSERT INTO "estudiantes" VALUES (405,'Henry','Gonzales Guevara',NULL,NULL,NULL,20,'2000-03-15','Activo','2025-05-21 20:20:16','2025-05-21 20:20:16');
INSERT INTO "estudiantes" VALUES (406,'Jorge Junior','Guerrero Eche',NULL,NULL,NULL,20,'2000-03-15','Activo','2025-05-21 20:20:16','2025-05-21 20:20:16');
INSERT INTO "estudiantes" VALUES (407,'Katherin','Gurrionero Saldarriaga',NULL,NULL,NULL,20,'2000-03-15','Activo','2025-05-21 20:20:16','2025-05-21 20:20:16');
INSERT INTO "estudiantes" VALUES (408,'Armando','Jimenez Garces',NULL,NULL,NULL,20,'2000-03-15','Activo','2025-05-21 20:20:16','2025-05-21 20:20:16');
INSERT INTO "estudiantes" VALUES (409,'Marcia Lucia','Jimenez Lopez',NULL,NULL,NULL,20,'2000-03-15','Activo','2025-05-21 20:20:16','2025-05-21 20:20:16');
INSERT INTO "estudiantes" VALUES (410,'Samuel','Karasas Mejia',NULL,NULL,NULL,20,'2000-03-15','Activo','2025-05-21 20:20:16','2025-05-21 20:20:16');
INSERT INTO "estudiantes" VALUES (411,'Eder Javier','Maceda Alcalde',NULL,NULL,NULL,20,'2000-03-15','Activo','2025-05-21 20:20:16','2025-05-21 20:20:16');
INSERT INTO "estudiantes" VALUES (412,'Frank','Marchena Saavedra',NULL,NULL,NULL,20,'2000-03-15','Activo','2025-05-21 20:20:16','2025-05-21 20:20:16');
INSERT INTO "estudiantes" VALUES (413,'Alain','Mendizabal Olivarez',NULL,NULL,NULL,20,'2000-03-15','Activo','2025-05-21 20:20:16','2025-05-21 20:20:16');
INSERT INTO "estudiantes" VALUES (414,'Carlos Antonio','Mogollon Peña',NULL,NULL,NULL,20,'2000-03-15','Activo','2025-05-21 20:20:16','2025-05-21 20:20:16');
INSERT INTO "estudiantes" VALUES (415,'Leslie','Morales Dioses',NULL,NULL,NULL,20,'2000-03-15','Activo','2025-05-21 20:20:16','2025-05-21 20:20:16');
INSERT INTO "estudiantes" VALUES (416,'Lizeth','Morales DIoses',NULL,NULL,NULL,20,'2000-03-15','Activo','2025-05-21 20:20:16','2025-05-21 20:20:16');
INSERT INTO "estudiantes" VALUES (417,'Mirian Danitza','Moscol Juarez ',NULL,NULL,NULL,20,'2000-03-15','Activo','2025-05-21 20:20:16','2025-05-21 20:20:16');
INSERT INTO "estudiantes" VALUES (418,'Tania Marilu','Nicolinni Valladares',NULL,NULL,NULL,20,'2000-03-15','Activo','2025-05-21 20:20:16','2025-05-21 20:20:16');
INSERT INTO "estudiantes" VALUES (419,'Cristhian Alberto','Olaya Ponce',NULL,NULL,NULL,20,'2000-03-15','Activo','2025-05-21 20:20:16','2025-05-21 20:20:16');
INSERT INTO "estudiantes" VALUES (420,'Mirian Yarnet','Pejerrey Sernaque',NULL,NULL,NULL,20,'2000-03-15','Activo','2025-05-21 20:20:16','2025-05-21 20:20:16');
INSERT INTO "estudiantes" VALUES (421,'Elberth Daniel','Rivera Castillo ',NULL,NULL,NULL,20,'2000-03-15','Activo','2025-05-21 20:20:16','2025-05-21 20:20:16');
INSERT INTO "estudiantes" VALUES (422,'Claudia Anali','Roman Camacho',NULL,NULL,NULL,20,'2000-03-15','Activo','2025-05-21 20:20:16','2025-05-21 20:20:16');
INSERT INTO "estudiantes" VALUES (423,'Erickson David','Rosales Sobrino',NULL,NULL,NULL,20,'2000-03-15','Activo','2025-05-21 20:20:16','2025-05-21 20:20:16');
INSERT INTO "estudiantes" VALUES (424,'Santos Fidel','Ruiz Rivera',NULL,NULL,NULL,20,'2000-03-15','Activo','2025-05-21 20:20:16','2025-05-21 20:20:16');
INSERT INTO "estudiantes" VALUES (425,'Lysbel Mary','Vargas Arellano',NULL,NULL,NULL,20,'2000-03-15','Activo','2025-05-21 20:20:16','2025-05-21 20:20:16');
INSERT INTO "estudiantes" VALUES (426,'Veronica Yanet','Vite Cardoza ',NULL,NULL,NULL,20,'2000-03-15','Activo','2025-05-21 20:20:16','2025-05-21 20:20:16');
INSERT INTO "estudiantes" VALUES (427,'Carlos Yoel','Yarleque Ayala',NULL,NULL,NULL,20,'2000-03-15','Activo','2025-05-21 20:20:16','2025-05-21 20:20:16');
INSERT INTO "estudiantes" VALUES (428,'Andres Martin','Yarleque Lizama',NULL,NULL,NULL,20,'2000-03-15','Activo','2025-05-21 20:20:16','2025-05-21 20:20:16');
INSERT INTO "estudiantes" VALUES (429,'Cristtopher Cesar','Zapata Delgado',NULL,NULL,NULL,20,'2000-03-15','Activo','2025-05-21 20:20:16','2025-05-21 20:20:16');
INSERT INTO "estudiantes" VALUES (489,' Katherine Liseth','Agurto Quispe',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:57:02','2025-05-22 22:57:02');
INSERT INTO "estudiantes" VALUES (490,' Ericka Janira','Alonso Ezpinosa',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:57:02','2025-05-22 22:57:02');
INSERT INTO "estudiantes" VALUES (491,' Karina Paola','Amaya Pasache',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:57:02','2025-05-22 22:57:02');
INSERT INTO "estudiantes" VALUES (492,' Dellany Chirley','Asansa Campos',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:57:02','2025-05-22 22:57:02');
INSERT INTO "estudiantes" VALUES (493,' Oscar Leonel','Avila Huertas',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:57:02','2025-05-22 22:57:02');
INSERT INTO "estudiantes" VALUES (494,' Vanesa Melisa','Ayala Lluen',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:57:02','2025-05-22 22:57:02');
INSERT INTO "estudiantes" VALUES (495,' Carolina Mercedes ','Baylon Canales',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:57:02','2025-05-22 22:57:02');
INSERT INTO "estudiantes" VALUES (496,' Jhony Willy','Benites Valladares',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:57:02','2025-05-22 22:57:02');
INSERT INTO "estudiantes" VALUES (497,' Richard Omar','Campos Aponte',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:57:02','2025-05-22 22:57:02');
INSERT INTO "estudiantes" VALUES (498,' Jhonny','Chapillequen Cornejo',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:57:02','2025-05-22 22:57:02');
INSERT INTO "estudiantes" VALUES (499,' Danny Lany','Chero Uria',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:57:02','2025-05-22 22:57:02');
INSERT INTO "estudiantes" VALUES (500,' Dickson Kenedy Andress','Chero Uria',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:57:02','2025-05-22 22:57:02');
INSERT INTO "estudiantes" VALUES (501,' Yelicsa Paola ','Chinga Martinez',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:57:02','2025-05-22 22:57:02');
INSERT INTO "estudiantes" VALUES (502,' Fabian Junior','Chiroque Atoche',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:57:02','2025-05-22 22:57:02');
INSERT INTO "estudiantes" VALUES (503,'Miluska Lorena','Chiroque Atoche',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:57:02','2025-05-22 22:57:02');
INSERT INTO "estudiantes" VALUES (504,' Jose Armando','Chumo Olaya',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:57:02','2025-05-22 22:57:02');
INSERT INTO "estudiantes" VALUES (505,' Maribel Paola','Coronado Angeldonis',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:57:02','2025-05-22 22:57:02');
INSERT INTO "estudiantes" VALUES (506,' Roger Alberto','Cruz Olivos',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:57:02','2025-05-22 22:57:02');
INSERT INTO "estudiantes" VALUES (507,' Edwin Javier ','Dominguez Ruiz',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:57:02','2025-05-22 22:57:02');
INSERT INTO "estudiantes" VALUES (508,' Eduardo Alexis','Garcia Ocaña',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:57:02','2025-05-22 22:57:02');
INSERT INTO "estudiantes" VALUES (509,' Monica del Pilar','Garcia Ponce',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:57:02','2025-05-22 22:57:02');
INSERT INTO "estudiantes" VALUES (510,' David','Guerra Soto',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:57:02','2025-05-22 22:57:02');
INSERT INTO "estudiantes" VALUES (511,' Rogger Jean Carlos','Guerrero Frias',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:57:02','2025-05-22 22:57:02');
INSERT INTO "estudiantes" VALUES (512,' Luis Enrique','Herrera Dioses',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:57:02','2025-05-22 22:57:02');
INSERT INTO "estudiantes" VALUES (513,' Willinton Rommel ','Hidalgo Peña',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:57:02','2025-05-22 22:57:02');
INSERT INTO "estudiantes" VALUES (514,' Juan Jhullian ','Huaman Sanchez',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:57:02','2025-05-22 22:57:02');
INSERT INTO "estudiantes" VALUES (515,' Angel Joel','Isminio Escobar',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:57:02','2025-05-22 22:57:02');
INSERT INTO "estudiantes" VALUES (516,' Juan Pablo','Lecarnaque Carrasco',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:57:02','2025-05-22 22:57:02');
INSERT INTO "estudiantes" VALUES (517,' Lessly Ivonng','Mauricio Morales',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:57:02','2025-05-22 22:57:02');
INSERT INTO "estudiantes" VALUES (518,' Jorge Adrian','Mogollon Juarez',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:57:02','2025-05-22 22:57:02');
INSERT INTO "estudiantes" VALUES (519,' Paoly Yovana','Mogollon Nolasco',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:57:02','2025-05-22 22:57:02');
INSERT INTO "estudiantes" VALUES (520,' Juana Maria ','Mogollon Socola',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:57:02','2025-05-22 22:57:02');
INSERT INTO "estudiantes" VALUES (521,' Harold Kevin','Morales Castillo',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:57:02','2025-05-22 22:57:02');
INSERT INTO "estudiantes" VALUES (522,' Marlon Steven','Morcillo Pacheco',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:57:02','2025-05-22 22:57:02');
INSERT INTO "estudiantes" VALUES (523,' Alonso Alexander','Murillo Lejabo',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:57:02','2025-05-22 22:57:02');
INSERT INTO "estudiantes" VALUES (524,' Ivette Andreina','Namuche Lupuche',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:57:02','2025-05-22 22:57:02');
INSERT INTO "estudiantes" VALUES (525,'  Audry Yudith','Olaya Cornejo',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:57:02','2025-05-22 22:57:02');
INSERT INTO "estudiantes" VALUES (526,' Darwin Joel ','Ortega Mendoza',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:57:02','2025-05-22 22:57:02');
INSERT INTO "estudiantes" VALUES (527,' Jimmy Darwin ','Pinzon Villegas',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:57:02','2025-05-22 22:57:02');
INSERT INTO "estudiantes" VALUES (528,' Ines','Poquioma Guerrero',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:57:02','2025-05-22 22:57:02');
INSERT INTO "estudiantes" VALUES (529,' Ana Rosa','Ramos Castillo',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:57:02','2025-05-22 22:57:02');
INSERT INTO "estudiantes" VALUES (530,' Daniel Ronald','Reyes Machare',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:57:02','2025-05-22 22:57:02');
INSERT INTO "estudiantes" VALUES (531,' Jordan','Rios Chuquihuanga',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:57:02','2025-05-22 22:57:02');
INSERT INTO "estudiantes" VALUES (532,' Robert ','Roman Echandia',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:57:02','2025-05-22 22:57:02');
INSERT INTO "estudiantes" VALUES (533,' Alan Paul ','Rosales Timana',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:57:02','2025-05-22 22:57:02');
INSERT INTO "estudiantes" VALUES (534,' Linda Noemi','Ruiz Arias',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:57:02','2025-05-22 22:57:02');
INSERT INTO "estudiantes" VALUES (535,' Daniel Josue','Sanchez Alban',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:57:02','2025-05-22 22:57:02');
INSERT INTO "estudiantes" VALUES (536,' Erick David','Sanchez Alban',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:57:02','2025-05-22 22:57:02');
INSERT INTO "estudiantes" VALUES (537,' Rick Kevin','Sanchez Alban',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:57:02','2025-05-22 22:57:02');
INSERT INTO "estudiantes" VALUES (538,' Cesar Orlando ','Sanchez Saldarriaga',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:57:02','2025-05-22 22:57:02');
INSERT INTO "estudiantes" VALUES (539,' Juan Carlos','Sullon Morante',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:57:02','2025-05-22 22:57:02');
INSERT INTO "estudiantes" VALUES (540,' Catherine Lizet','Tapia Santamaria',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:57:02','2025-05-22 22:57:02');
INSERT INTO "estudiantes" VALUES (541,' Ana Lorena','Urbina Cruz',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:57:02','2025-05-22 22:57:02');
INSERT INTO "estudiantes" VALUES (542,' Karen Cynthia','Vargas Arellano',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:57:02','2025-05-22 22:57:02');
INSERT INTO "estudiantes" VALUES (543,' Jean Carlos','Vargas Mejia',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:57:02','2025-05-22 22:57:02');
INSERT INTO "estudiantes" VALUES (544,' Evelyn Fabiola','Villar Silupu',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:57:02','2025-05-22 22:57:02');
INSERT INTO "estudiantes" VALUES (545,' Katherine del Rosario','Yacila Urbina',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:57:02','2025-05-22 22:57:02');
INSERT INTO "estudiantes" VALUES (546,' Marcia Maribel','Yovera Ramirez',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:57:02','2025-05-22 22:57:02');
INSERT INTO "estudiantes" VALUES (547,' Edson Jhonnathan','Zapata Delgado',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:57:02','2025-05-22 22:57:02');
INSERT INTO "estudiantes" VALUES (548,' Katherine Liseth','Agurto Quispe',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:59:39','2025-05-22 22:59:39');
INSERT INTO "estudiantes" VALUES (549,' Ericka Janira','Alonso Ezpinosa',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:59:39','2025-05-22 22:59:39');
INSERT INTO "estudiantes" VALUES (550,' Karina Paola','Amaya Pasache',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:59:39','2025-05-22 22:59:39');
INSERT INTO "estudiantes" VALUES (551,' Dellany Chirley','Asansa Campos',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:59:39','2025-05-22 22:59:39');
INSERT INTO "estudiantes" VALUES (552,' Oscar Leonel','Avila Huertas',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:59:39','2025-05-22 22:59:39');
INSERT INTO "estudiantes" VALUES (553,' Vanesa Melisa','Ayala Lluen',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:59:39','2025-05-22 22:59:39');
INSERT INTO "estudiantes" VALUES (554,' Carolina Mercedes ','Baylon Canales',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:59:39','2025-05-22 22:59:39');
INSERT INTO "estudiantes" VALUES (555,' Jhony Willy','Benites Valladares',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:59:39','2025-05-22 22:59:39');
INSERT INTO "estudiantes" VALUES (556,' Richard Omar','Campos Aponte',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:59:39','2025-05-22 22:59:39');
INSERT INTO "estudiantes" VALUES (557,' Jhonny','Chapillequen Cornejo',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:59:39','2025-05-22 22:59:39');
INSERT INTO "estudiantes" VALUES (558,' Danny Lany','Chero Uria',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:59:39','2025-05-22 22:59:39');
INSERT INTO "estudiantes" VALUES (559,' Dickson Kenedy Andress','Chero Uria',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:59:39','2025-05-22 22:59:39');
INSERT INTO "estudiantes" VALUES (560,' Yelicsa Paola ','Chinga Martinez',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:59:39','2025-05-22 22:59:39');
INSERT INTO "estudiantes" VALUES (561,' Fabian Junior','Chiroque Atoche',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:59:39','2025-05-22 22:59:39');
INSERT INTO "estudiantes" VALUES (562,'Miluska Lorena','Chiroque Atoche',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:59:39','2025-05-22 22:59:39');
INSERT INTO "estudiantes" VALUES (563,' Jose Armando','Chumo Olaya',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:59:39','2025-05-22 22:59:39');
INSERT INTO "estudiantes" VALUES (564,' Maribel Paola','Coronado Angeldonis',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:59:39','2025-05-22 22:59:39');
INSERT INTO "estudiantes" VALUES (565,' Roger Alberto','Cruz Olivos',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:59:39','2025-05-22 22:59:39');
INSERT INTO "estudiantes" VALUES (566,' Edwin Javier ','Dominguez Ruiz',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:59:39','2025-05-22 22:59:39');
INSERT INTO "estudiantes" VALUES (567,' Eduardo Alexis','Garcia Ocaña',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:59:39','2025-05-22 22:59:39');
INSERT INTO "estudiantes" VALUES (568,' Monica del Pilar','Garcia Ponce',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:59:39','2025-05-22 22:59:39');
INSERT INTO "estudiantes" VALUES (569,' David','Guerra Soto',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:59:39','2025-05-22 22:59:39');
INSERT INTO "estudiantes" VALUES (570,' Rogger Jean Carlos','Guerrero Frias',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:59:39','2025-05-22 22:59:39');
INSERT INTO "estudiantes" VALUES (571,' Luis Enrique','Herrera Dioses',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:59:39','2025-05-22 22:59:39');
INSERT INTO "estudiantes" VALUES (572,' Willinton Rommel ','Hidalgo Peña',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:59:39','2025-05-22 22:59:39');
INSERT INTO "estudiantes" VALUES (573,' Juan Jhullian ','Huaman Sanchez',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:59:39','2025-05-22 22:59:39');
INSERT INTO "estudiantes" VALUES (574,' Angel Joel','Isminio Escobar',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:59:39','2025-05-22 22:59:39');
INSERT INTO "estudiantes" VALUES (575,' Juan Pablo','Lecarnaque Carrasco',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:59:39','2025-05-22 22:59:39');
INSERT INTO "estudiantes" VALUES (576,' Lessly Ivonng','Mauricio Morales',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:59:39','2025-05-22 22:59:39');
INSERT INTO "estudiantes" VALUES (577,' Jorge Adrian','Mogollon Juarez',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:59:39','2025-05-22 22:59:39');
INSERT INTO "estudiantes" VALUES (578,' Paoly Yovana','Mogollon Nolasco',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:59:39','2025-05-22 22:59:39');
INSERT INTO "estudiantes" VALUES (579,' Juana Maria ','Mogollon Socola',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:59:39','2025-05-22 22:59:39');
INSERT INTO "estudiantes" VALUES (580,' Harold Kevin','Morales Castillo',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:59:39','2025-05-22 22:59:39');
INSERT INTO "estudiantes" VALUES (581,' Marlon Steven','Morcillo Pacheco',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:59:39','2025-05-22 22:59:39');
INSERT INTO "estudiantes" VALUES (582,' Alonso Alexander','Murillo Lejabo',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:59:39','2025-05-22 22:59:39');
INSERT INTO "estudiantes" VALUES (583,' Ivette Andreina','Namuche Lupuche',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:59:39','2025-05-22 22:59:39');
INSERT INTO "estudiantes" VALUES (584,'  Audry Yudith','Olaya Cornejo',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:59:39','2025-05-22 22:59:39');
INSERT INTO "estudiantes" VALUES (585,' Darwin Joel ','Ortega Mendoza',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:59:39','2025-05-22 22:59:39');
INSERT INTO "estudiantes" VALUES (586,' Jimmy Darwin ','Pinzon Villegas',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:59:39','2025-05-22 22:59:39');
INSERT INTO "estudiantes" VALUES (587,' Ines','Poquioma Guerrero',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:59:39','2025-05-22 22:59:39');
INSERT INTO "estudiantes" VALUES (588,' Ana Rosa','Ramos Castillo',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:59:39','2025-05-22 22:59:39');
INSERT INTO "estudiantes" VALUES (589,' Daniel Ronald','Reyes Machare',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:59:39','2025-05-22 22:59:39');
INSERT INTO "estudiantes" VALUES (590,' Jordan','Rios Chuquihuanga',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:59:39','2025-05-22 22:59:39');
INSERT INTO "estudiantes" VALUES (591,' Robert ','Roman Echandia',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:59:39','2025-05-22 22:59:39');
INSERT INTO "estudiantes" VALUES (592,' Alan Paul ','Rosales Timana',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:59:39','2025-05-22 22:59:39');
INSERT INTO "estudiantes" VALUES (593,' Linda Noemi','Ruiz Arias',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:59:39','2025-05-22 22:59:39');
INSERT INTO "estudiantes" VALUES (594,' Daniel Josue','Sanchez Alban',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:59:39','2025-05-22 22:59:39');
INSERT INTO "estudiantes" VALUES (595,' Erick David','Sanchez Alban',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:59:39','2025-05-22 22:59:39');
INSERT INTO "estudiantes" VALUES (596,' Rick Kevin','Sanchez Alban',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:59:39','2025-05-22 22:59:39');
INSERT INTO "estudiantes" VALUES (597,' Cesar Orlando ','Sanchez Saldarriaga',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:59:39','2025-05-22 22:59:39');
INSERT INTO "estudiantes" VALUES (598,' Juan Carlos','Sullon Morante',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:59:39','2025-05-22 22:59:39');
INSERT INTO "estudiantes" VALUES (599,' Catherine Lizet','Tapia Santamaria',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:59:39','2025-05-22 22:59:39');
INSERT INTO "estudiantes" VALUES (600,' Ana Lorena','Urbina Cruz',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:59:39','2025-05-22 22:59:39');
INSERT INTO "estudiantes" VALUES (601,' Karen Cynthia','Vargas Arellano',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:59:39','2025-05-22 22:59:39');
INSERT INTO "estudiantes" VALUES (602,' Jean Carlos','Vargas Mejia',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:59:39','2025-05-22 22:59:39');
INSERT INTO "estudiantes" VALUES (603,' Evelyn Fabiola','Villar Silupu',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:59:39','2025-05-22 22:59:39');
INSERT INTO "estudiantes" VALUES (604,' Katherine del Rosario','Yacila Urbina',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:59:39','2025-05-22 22:59:39');
INSERT INTO "estudiantes" VALUES (605,' Marcia Maribel','Yovera Ramirez',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:59:39','2025-05-22 22:59:39');
INSERT INTO "estudiantes" VALUES (606,' Edson Jhonnathan','Zapata Delgado',NULL,NULL,NULL,19,'2000-03-15','Activo','2025-05-22 22:59:39','2025-05-22 22:59:39');
INSERT INTO "estudiantes" VALUES (607,'Julio Guillermo','Aguilar Bruno',NULL,NULL,NULL,22,'2000-03-15','Activo','2025-05-22 23:53:17','2025-05-22 23:53:17');
INSERT INTO "estudiantes" VALUES (608,'Marisol Julissa','Aguirre Huertas',NULL,NULL,NULL,22,'2000-03-15','Activo','2025-05-22 23:53:17','2025-05-22 23:53:17');
INSERT INTO "estudiantes" VALUES (609,'Cinthia Anali','Agurto Quispe',NULL,NULL,NULL,22,'2000-03-15','Activo','2025-05-22 23:53:17','2025-05-22 23:53:17');
INSERT INTO "estudiantes" VALUES (610,'Luis Miguel','Alvarez Cueva',NULL,NULL,NULL,22,'2000-03-15','Activo','2025-05-22 23:53:17','2025-05-22 23:53:17');
INSERT INTO "estudiantes" VALUES (611,'Juan Augusto','Aviles Zapata',NULL,NULL,NULL,22,'2000-03-15','Activo','2025-05-22 23:53:17','2025-05-22 23:53:17');
INSERT INTO "estudiantes" VALUES (612,'Rosa Manuela','Barreto Ubillus',NULL,NULL,NULL,22,'2000-03-15','Activo','2025-05-22 23:53:17','2025-05-22 23:53:17');
INSERT INTO "estudiantes" VALUES (613,'Marlon  Robert','Bayona Chunga',NULL,NULL,NULL,22,'2000-03-15','Activo','2025-05-22 23:53:17','2025-05-22 23:53:17');
INSERT INTO "estudiantes" VALUES (614,'José Leonardo','Boyer Negron',NULL,NULL,NULL,22,'2000-03-15','Activo','2025-05-22 23:53:17','2025-05-22 23:53:17');
INSERT INTO "estudiantes" VALUES (615,'Jhon Pool','Canales Frias',NULL,NULL,NULL,22,'2000-03-15','Activo','2025-05-22 23:53:17','2025-05-22 23:53:17');
INSERT INTO "estudiantes" VALUES (616,'Erick Orlando','Carpio Guerrero',NULL,NULL,NULL,22,'2000-03-15','Activo','2025-05-22 23:53:17','2025-05-22 23:53:17');
INSERT INTO "estudiantes" VALUES (617,'Diana Carolina','Castillo Navarro',NULL,NULL,NULL,22,'2000-03-15','Activo','2025-05-22 23:53:17','2025-05-22 23:53:17');
INSERT INTO "estudiantes" VALUES (618,'Gibson Dilmar','Castro Lopez',NULL,NULL,NULL,22,'2000-03-15','Activo','2025-05-22 23:53:17','2025-05-22 23:53:17');
INSERT INTO "estudiantes" VALUES (619,'Adriana Petronila','Cevallos Navarro',NULL,NULL,NULL,22,'2000-03-15','Activo','2025-05-22 23:53:17','2025-05-22 23:53:17');
INSERT INTO "estudiantes" VALUES (620,'Moises Agustin','Custodio Vinces',NULL,NULL,NULL,22,'2000-03-15','Activo','2025-05-22 23:53:17','2025-05-22 23:53:17');
INSERT INTO "estudiantes" VALUES (621,'Etty Cecilia','De La Cruz  Peña',NULL,NULL,NULL,22,'2000-03-15','Activo','2025-05-22 23:53:17','2025-05-22 23:53:17');
INSERT INTO "estudiantes" VALUES (622,'Tatiana Marili','Del Rosario Sernaque',NULL,NULL,NULL,22,'2000-03-15','Activo','2025-05-22 23:53:17','2025-05-22 23:53:17');
INSERT INTO "estudiantes" VALUES (623,'Edson Francisco','Farfan Vargas ',NULL,NULL,NULL,22,'2000-03-15','Activo','2025-05-22 23:53:17','2025-05-22 23:53:17');
INSERT INTO "estudiantes" VALUES (624,'Karen Dianaly Corona','Garcia Arizaga',NULL,NULL,NULL,22,'2000-03-15','Activo','2025-05-22 23:53:17','2025-05-22 23:53:17');
INSERT INTO "estudiantes" VALUES (625,'Pedro Arturo','Garcia Ponce',NULL,NULL,NULL,22,'2000-03-15','Activo','2025-05-22 23:53:17','2025-05-22 23:53:17');
INSERT INTO "estudiantes" VALUES (626,'Victor Alan','Gonzales Manrique',NULL,NULL,NULL,22,'2000-03-15','Activo','2025-05-22 23:53:17','2025-05-22 23:53:17');
INSERT INTO "estudiantes" VALUES (627,'Dora Elena','Herrera Dioses',NULL,NULL,NULL,22,'2000-03-15','Activo','2025-05-22 23:53:17','2025-05-22 23:53:17');
INSERT INTO "estudiantes" VALUES (628,'Nadir Elizabeth','Hidalgo Peña ',NULL,NULL,NULL,22,'2000-03-15','Activo','2025-05-22 23:53:17','2025-05-22 23:53:17');
INSERT INTO "estudiantes" VALUES (629,'Cinthya Lizeth','Huertas Carranza ',NULL,NULL,NULL,22,'2000-03-15','Activo','2025-05-22 23:53:17','2025-05-22 23:53:17');
INSERT INTO "estudiantes" VALUES (630,'Maximiliano Luis','Huertas Gomez',NULL,NULL,NULL,22,'2000-03-15','Activo','2025-05-22 23:53:17','2025-05-22 23:53:17');
INSERT INTO "estudiantes" VALUES (631,'Pablo','Jimenez Garcia',NULL,NULL,NULL,22,'2000-03-15','Activo','2025-05-22 23:53:17','2025-05-22 23:53:17');
INSERT INTO "estudiantes" VALUES (632,'Edwing','Litano Bruno',NULL,NULL,NULL,22,'2000-03-15','Activo','2025-05-22 23:53:17','2025-05-22 23:53:17');
INSERT INTO "estudiantes" VALUES (633,'Reyna Yusara','Martinez Nizama ',NULL,NULL,NULL,22,'2000-03-15','Activo','2025-05-22 23:53:17','2025-05-22 23:53:17');
INSERT INTO "estudiantes" VALUES (634,'Juan Alberto','Mauricio Vargas',NULL,NULL,NULL,22,'2000-03-15','Activo','2025-05-22 23:53:17','2025-05-22 23:53:17');
INSERT INTO "estudiantes" VALUES (635,'Cristian Enrique','Medina Seminario',NULL,NULL,NULL,22,'2000-03-15','Activo','2025-05-22 23:53:17','2025-05-22 23:53:17');
INSERT INTO "estudiantes" VALUES (636,'Alex Alberto','Miñope Machare',NULL,NULL,NULL,22,'2000-03-15','Activo','2025-05-22 23:53:17','2025-05-22 23:53:17');
INSERT INTO "estudiantes" VALUES (637,'Carlos Reynaldo','Olaya Ponce',NULL,NULL,NULL,22,'2000-03-15','Activo','2025-05-22 23:53:17','2025-05-22 23:53:17');
INSERT INTO "estudiantes" VALUES (638,'Diana Patricia ','Peña Flores',NULL,NULL,NULL,22,'2000-03-15','Activo','2025-05-22 23:53:17','2025-05-22 23:53:17');
INSERT INTO "estudiantes" VALUES (639,'Viviana Lisset','Ramirez Hilario ',NULL,NULL,NULL,22,'2000-03-15','Activo','2025-05-22 23:53:17','2025-05-22 23:53:17');
INSERT INTO "estudiantes" VALUES (640,'Luis Miguel','Renteria Ruiz',NULL,NULL,NULL,22,'2000-03-15','Activo','2025-05-22 23:53:17','2025-05-22 23:53:17');
INSERT INTO "estudiantes" VALUES (641,'Wiliam Alberto','Rivera Peña ',NULL,NULL,NULL,22,'2000-03-15','Activo','2025-05-22 23:53:17','2025-05-22 23:53:17');
INSERT INTO "estudiantes" VALUES (642,'Jean Carlo','Rosales Timana',NULL,NULL,NULL,22,'2000-03-15','Activo','2025-05-22 23:53:17','2025-05-22 23:53:17');
INSERT INTO "estudiantes" VALUES (643,'Fanny Janeth','Salazar Sánchez',NULL,NULL,NULL,22,'2000-03-15','Activo','2025-05-22 23:53:17','2025-05-22 23:53:17');
INSERT INTO "estudiantes" VALUES (644,'Jesús Carlos','Salcedo Angeldonis',NULL,NULL,NULL,22,'2000-03-15','Activo','2025-05-22 23:53:17','2025-05-22 23:53:17');
INSERT INTO "estudiantes" VALUES (645,'Jorge Andrés','Sánchez Arcela',NULL,NULL,NULL,22,'2000-03-15','Activo','2025-05-22 23:53:17','2025-05-22 23:53:17');
INSERT INTO "estudiantes" VALUES (646,'Heli Steward','Valladares Flores',NULL,NULL,NULL,22,'2000-03-15','Activo','2025-05-22 23:53:17','2025-05-22 23:53:17');
INSERT INTO "estudiantes" VALUES (647,'Jecsee Judith','Villegas Sullón',NULL,NULL,NULL,22,'2000-03-15','Activo','2025-05-22 23:53:17','2025-05-22 23:53:17');
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "failed_jobs" (
  "id" bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  "uuid" varchar(255) NOT NULL,
  "connection" text NOT NULL,
  "queue" text NOT NULL,
  "payload" longtext NOT NULL,
  "exception" longtext NOT NULL,
  "failed_at" timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY ("id"),
  UNIQUE KEY "failed_jobs_uuid_unique" ("uuid")
);
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "grados" (
  "id_grado" bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  "nombre" varchar(50) NOT NULL,
  "id_nivel" bigint(20) unsigned NOT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  PRIMARY KEY ("id_grado"),
  UNIQUE KEY "grados_nombre_id_nivel_unique" ("nombre","id_nivel"),
  KEY "grados_id_nivel_foreign" ("id_nivel"),
  CONSTRAINT "grados_id_nivel_foreign" FOREIGN KEY ("id_nivel") REFERENCES "niveles" ("id_nivel") ON DELETE CASCADE
);
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO "grados" VALUES (1,'1er Grado',2,'2025-03-27 23:28:56','2025-03-28 00:08:16');
INSERT INTO "grados" VALUES (2,'2do Grado',2,'2025-03-27 23:29:07','2025-03-28 00:08:28');
INSERT INTO "grados" VALUES (3,'3er Grado',2,'2025-03-27 23:29:18','2025-03-28 00:08:38');
INSERT INTO "grados" VALUES (4,'4to Grado',2,'2025-03-27 23:29:43','2025-03-28 00:08:46');
INSERT INTO "grados" VALUES (5,'5to Grado',2,'2025-03-27 23:29:54','2025-03-28 00:08:55');
INSERT INTO "grados" VALUES (6,'6to Grado',2,'2025-03-27 23:30:20','2025-03-28 00:09:03');
INSERT INTO "grados" VALUES (7,'1er Grado',3,'2025-03-27 23:54:33','2025-03-28 00:09:14');
INSERT INTO "grados" VALUES (9,'2do Grado',3,'2025-03-27 23:55:46','2025-03-28 00:09:23');
INSERT INTO "grados" VALUES (11,'3er Grado',3,'2025-03-28 00:09:38','2025-03-28 00:09:38');
INSERT INTO "grados" VALUES (12,'4to Grado',3,'2025-03-28 00:09:49','2025-03-28 00:09:49');
INSERT INTO "grados" VALUES (13,'5to Grado',3,'2025-03-28 00:10:18','2025-03-28 00:10:18');
INSERT INTO "grados" VALUES (14,'3',1,'2025-03-28 00:17:32','2025-03-28 00:17:43');
INSERT INTO "grados" VALUES (15,'4',1,'2025-03-28 00:18:00','2025-03-28 00:18:00');
INSERT INTO "grados" VALUES (16,'5',1,'2025-03-28 00:18:44','2025-03-28 00:18:44');
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "importaciones_historial" (
  "id_importacion" bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  "nombre_archivo" varchar(255) NOT NULL COMMENT 'Nombre del archivo que se importÃ³',
  "anio_academico" varchar(50) DEFAULT NULL COMMENT 'AÃ±o acadÃ©mico seleccionado',
  "id_nivel" bigint(20) unsigned DEFAULT NULL COMMENT 'ID del nivel educativo',
  "id_aula" bigint(20) unsigned DEFAULT NULL COMMENT 'ID del aula seleccionada',
  "nivel_nombre" varchar(100) DEFAULT NULL COMMENT 'Nombre del nivel (para mantener el historial aunque cambie)',
  "aula_nombre" varchar(255) DEFAULT NULL COMMENT 'Nombre completo del aula incluyendo grado y secciÃ³n',
  "fecha_importacion" date NOT NULL COMMENT 'Fecha en que se realizÃ³ la importaciÃ³n',
  "total_importados" int(11) NOT NULL DEFAULT 0 COMMENT 'Cantidad de estudiantes importados',
  "usuario" varchar(100) DEFAULT NULL COMMENT 'Nombre del usuario que realizÃ³ la importaciÃ³n',
  "id_usuario" bigint(20) unsigned DEFAULT NULL COMMENT 'ID del usuario que realizÃ³ la importaciÃ³n',
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  PRIMARY KEY ("id_importacion"),
  KEY "importaciones_historial_id_nivel_foreign" ("id_nivel"),
  KEY "importaciones_historial_id_aula_foreign" ("id_aula"),
  KEY "importaciones_historial_id_usuario_foreign" ("id_usuario"),
  CONSTRAINT "importaciones_historial_id_aula_foreign" FOREIGN KEY ("id_aula") REFERENCES "aulas" ("id_aula") ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT "importaciones_historial_id_nivel_foreign" FOREIGN KEY ("id_nivel") REFERENCES "niveles" ("id_nivel") ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT "importaciones_historial_id_usuario_foreign" FOREIGN KEY ("id_usuario") REFERENCES "usuarios" ("id_usuario") ON DELETE SET NULL ON UPDATE CASCADE
);
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO "importaciones_historial" VALUES (1,'Plantilla_Estudiantes_Secundaria_2do Grado_B_2000-03-15.xlsx','2000',3,22,'Secundaria','2do Grado - B','2025-05-22',41,'Will',1,'2025-05-22 23:53:17','2025-05-22 23:53:17');
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "incidentes" (
  "id_incidente" bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  "id_estudiante" bigint(20) unsigned NOT NULL,
  "fecha" date NOT NULL,
  "tipo" enum('Disciplina','Salud','Otro') NOT NULL,
  "descripcion" text NOT NULL,
  "id_usuario_registro" bigint(20) unsigned NOT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  PRIMARY KEY ("id_incidente"),
  KEY "incidentes_id_estudiante_foreign" ("id_estudiante"),
  KEY "incidentes_id_usuario_registro_foreign" ("id_usuario_registro"),
  CONSTRAINT "incidentes_id_estudiante_foreign" FOREIGN KEY ("id_estudiante") REFERENCES "estudiantes" ("id_estudiante") ON DELETE CASCADE,
  CONSTRAINT "incidentes_id_usuario_registro_foreign" FOREIGN KEY ("id_usuario_registro") REFERENCES "usuarios" ("id_usuario")
);
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "job_batches" (
  "id" varchar(255) NOT NULL,
  "name" varchar(255) NOT NULL,
  "total_jobs" int(11) NOT NULL,
  "pending_jobs" int(11) NOT NULL,
  "failed_jobs" int(11) NOT NULL,
  "failed_job_ids" longtext NOT NULL,
  "options" mediumtext DEFAULT NULL,
  "cancelled_at" int(11) DEFAULT NULL,
  "created_at" int(11) NOT NULL,
  "finished_at" int(11) DEFAULT NULL,
  PRIMARY KEY ("id")
);
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "jobs" (
  "id" bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  "queue" varchar(255) NOT NULL,
  "payload" longtext NOT NULL,
  "attempts" tinyint(3) unsigned NOT NULL,
  "reserved_at" int(10) unsigned DEFAULT NULL,
  "available_at" int(10) unsigned NOT NULL,
  "created_at" int(10) unsigned NOT NULL,
  PRIMARY KEY ("id"),
  KEY "jobs_queue_index" ("queue")
);
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "materias" (
  "id_materia" bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  "nombre" varchar(100) NOT NULL,
  "id_nivel" bigint(20) unsigned NOT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  PRIMARY KEY ("id_materia"),
  UNIQUE KEY "materias_nombre_id_nivel_unique" ("nombre","id_nivel"),
  KEY "materias_id_nivel_foreign" ("id_nivel"),
  CONSTRAINT "materias_id_nivel_foreign" FOREIGN KEY ("id_nivel") REFERENCES "niveles" ("id_nivel")
);
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO "materias" VALUES (2,'Comunicación y Expresión',1,NULL,NULL);
INSERT INTO "materias" VALUES (3,'Pensamiento Matemático',1,NULL,NULL);
INSERT INTO "materias" VALUES (4,'Exploración del Entorno',1,NULL,NULL);
INSERT INTO "materias" VALUES (5,'Educación Artística',1,NULL,NULL);
INSERT INTO "materias" VALUES (6,'Educación Física',1,NULL,NULL);
INSERT INTO "materias" VALUES (7,'Comunicación',2,NULL,NULL);
INSERT INTO "materias" VALUES (8,'Matemática',2,NULL,NULL);
INSERT INTO "materias" VALUES (9,'Ciencias Naturales',2,NULL,NULL);
INSERT INTO "materias" VALUES (10,'Ciencias Sociales',2,NULL,NULL);
INSERT INTO "materias" VALUES (11,'Educación Artística',2,NULL,NULL);
INSERT INTO "materias" VALUES (12,'Educación Física',2,NULL,NULL);
INSERT INTO "materias" VALUES (13,'Educación en Valores',2,NULL,NULL);
INSERT INTO "materias" VALUES (14,'Inglés',2,NULL,NULL);
INSERT INTO "materias" VALUES (15,'Comunicación',3,NULL,NULL);
INSERT INTO "materias" VALUES (16,'Matemática',3,NULL,NULL);
INSERT INTO "materias" VALUES (17,'Física',3,NULL,NULL);
INSERT INTO "materias" VALUES (18,'Química',3,NULL,NULL);
INSERT INTO "materias" VALUES (19,'Biología',3,NULL,NULL);
INSERT INTO "materias" VALUES (20,'Historia',3,NULL,NULL);
INSERT INTO "materias" VALUES (21,'Geografía',3,NULL,NULL);
INSERT INTO "materias" VALUES (24,'Tecnología',3,NULL,NULL);
INSERT INTO "materias" VALUES (27,'Ingles',3,'2025-03-30 02:38:18','2025-03-30 02:38:18');
INSERT INTO "materias" VALUES (40,'Lenguaje y Literatura',3,NULL,'2025-05-06 23:08:16');
INSERT INTO "materias" VALUES (41,'Idioma Extranjero',3,NULL,NULL);
INSERT INTO "materias" VALUES (42,'Historia y Geografía',3,NULL,NULL);
INSERT INTO "materias" VALUES (43,'Educación Religiosa',3,NULL,NULL);
INSERT INTO "materias" VALUES (44,'Familia y Civismo',3,NULL,NULL);
INSERT INTO "materias" VALUES (45,'Arte y Creatividad',3,NULL,NULL);
INSERT INTO "materias" VALUES (46,'Educación Física',3,NULL,NULL);
INSERT INTO "materias" VALUES (47,'Ciencias Naturales',3,NULL,NULL);
INSERT INTO "materias" VALUES (48,'Educación para el trabajo',3,NULL,NULL);
INSERT INTO "materias" VALUES (53,'Historia del Perú Proc. Am. Mundial',3,'2025-05-21 20:47:17','2025-05-21 20:47:17');
INSERT INTO "materias" VALUES (54,'Geografia del Perú y del Mundo',3,'2025-05-21 21:26:59','2025-05-21 21:26:59');
INSERT INTO "materias" VALUES (55,'Educación Familiar',3,'2025-05-21 21:27:25','2025-05-21 21:27:25');
INSERT INTO "materias" VALUES (56,'Educación Civica',3,'2025-05-21 21:27:59','2025-05-21 21:27:59');
INSERT INTO "materias" VALUES (57,'Educación Artística',3,'2025-05-21 21:35:57','2025-05-21 21:35:57');
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "migrations" (
  "id" int(10) unsigned NOT NULL AUTO_INCREMENT,
  "migration" varchar(255) NOT NULL,
  "batch" int(11) NOT NULL,
  PRIMARY KEY ("id")
);
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO "migrations" VALUES (1,'0001_01_01_000000_create_users_table',1);
INSERT INTO "migrations" VALUES (2,'0001_01_01_000001_create_cache_table',1);
INSERT INTO "migrations" VALUES (3,'0001_01_01_000002_create_jobs_table',1);
INSERT INTO "migrations" VALUES (4,'2025_03_10_163755_create_usuarios_table',1);
INSERT INTO "migrations" VALUES (5,'2025_03_10_164017_create_niveles_table',1);
INSERT INTO "migrations" VALUES (6,'2025_03_10_164042_create_grados_table',1);
INSERT INTO "migrations" VALUES (7,'2025_03_10_164107_create_secciones_table',1);
INSERT INTO "migrations" VALUES (8,'2025_03_10_164127_create_materias_table',1);
INSERT INTO "migrations" VALUES (9,'2025_03_10_164128_create_aulas_table',1);
INSERT INTO "migrations" VALUES (10,'2025_03_10_164141_create_docentes_table',1);
INSERT INTO "migrations" VALUES (11,'2025_03_10_164155_create_apoderados_table',1);
INSERT INTO "migrations" VALUES (12,'2025_03_10_164208_create_estudiantes_table',1);
INSERT INTO "migrations" VALUES (13,'2025_03_10_164244_create_estudiante_apoderado_table',1);
INSERT INTO "migrations" VALUES (14,'2025_03_10_164309_create_anios_academicos_table',1);
INSERT INTO "migrations" VALUES (15,'2025_03_10_164325_create_trimestres_table',1);
INSERT INTO "migrations" VALUES (16,'2025_03_10_164357_create_asignaciones_table',1);
INSERT INTO "migrations" VALUES (17,'2025_03_10_164358_create_asistencia_table',1);
INSERT INTO "migrations" VALUES (18,'2025_03_10_164412_create_calificaciones_table',1);
INSERT INTO "migrations" VALUES (19,'2025_03_10_164423_create_incidentes_table',1);
INSERT INTO "migrations" VALUES (20,'2025_03_15_182532_create_aula_estudiante_table',1);
INSERT INTO "migrations" VALUES (21,'2025_03_15_184141_create_aula_docente_table',1);
INSERT INTO "migrations" VALUES (22,'2025_03_29_234847_change_materia_to_nivel_in_docentes_table',2);
INSERT INTO "migrations" VALUES (23,'2025_03_30_000506_remove_nivel_id_from_docentes_table',3);
INSERT INTO "migrations" VALUES (24,'2025_03_30_000927_agregar_nivel_id',4);
INSERT INTO "migrations" VALUES (26,'2025_05_05_145936_create_calificaciones_old_table',5);
INSERT INTO "migrations" VALUES (29,'2025_05_22_170845_create_importaciones_historial_table',6);
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "niveles" (
  "id_nivel" bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  "nombre" varchar(50) NOT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  PRIMARY KEY ("id_nivel"),
  UNIQUE KEY "niveles_nombre_unique" ("nombre")
);
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO "niveles" VALUES (1,'Inicial','2025-03-27 23:28:13','2025-03-27 23:28:13');
INSERT INTO "niveles" VALUES (2,'Primaria','2025-03-27 23:28:13','2025-03-27 23:28:13');
INSERT INTO "niveles" VALUES (3,'Secundaria','2025-03-27 23:28:13','2025-03-27 23:28:13');
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "password_reset_tokens" (
  "email" varchar(255) NOT NULL,
  "token" varchar(255) NOT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  PRIMARY KEY ("email")
);
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "secciones" (
  "id_seccion" bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  "nombre" varchar(10) NOT NULL,
  "id_grado" bigint(20) unsigned NOT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  PRIMARY KEY ("id_seccion"),
  UNIQUE KEY "secciones_nombre_id_grado_unique" ("nombre","id_grado"),
  KEY "secciones_id_grado_foreign" ("id_grado"),
  CONSTRAINT "secciones_id_grado_foreign" FOREIGN KEY ("id_grado") REFERENCES "grados" ("id_grado") ON DELETE CASCADE
);
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO "secciones" VALUES (3,'Años',14,'2025-03-28 20:58:57','2025-03-28 20:58:57');
INSERT INTO "secciones" VALUES (5,'Años',15,'2025-03-28 21:15:05','2025-03-28 21:15:05');
INSERT INTO "secciones" VALUES (6,'Años',16,'2025-03-28 21:15:17','2025-03-28 21:15:17');
INSERT INTO "secciones" VALUES (7,'A',3,'2025-03-28 21:21:21','2025-03-28 21:21:21');
INSERT INTO "secciones" VALUES (8,'A',4,'2025-03-28 21:22:38','2025-03-28 21:22:38');
INSERT INTO "secciones" VALUES (9,'A',5,'2025-03-28 21:22:47','2025-03-28 21:22:47');
INSERT INTO "secciones" VALUES (10,'A',6,'2025-03-28 21:22:55','2025-03-28 21:22:55');
INSERT INTO "secciones" VALUES (11,'B',7,'2025-03-28 21:23:19','2025-03-28 21:23:19');
INSERT INTO "secciones" VALUES (12,'B',9,'2025-03-28 21:23:28','2025-03-28 21:23:28');
INSERT INTO "secciones" VALUES (13,'B',11,'2025-03-28 21:23:35','2025-03-28 21:23:35');
INSERT INTO "secciones" VALUES (14,'B',12,'2025-03-28 21:23:43','2025-03-28 21:23:43');
INSERT INTO "secciones" VALUES (15,'B',13,'2025-03-28 21:23:52','2025-03-28 21:23:52');
INSERT INTO "secciones" VALUES (16,'A',7,'2025-03-28 21:29:05','2025-03-28 21:29:05');
INSERT INTO "secciones" VALUES (17,'A',9,'2025-03-28 21:29:13','2025-03-28 21:29:13');
INSERT INTO "secciones" VALUES (18,'A',11,'2025-03-28 21:29:23','2025-03-28 21:29:23');
INSERT INTO "secciones" VALUES (19,'A',12,'2025-03-28 21:30:10','2025-03-28 21:30:10');
INSERT INTO "secciones" VALUES (20,'A',13,'2025-03-28 21:30:18','2025-03-28 21:30:18');
INSERT INTO "secciones" VALUES (21,'A',2,'2025-03-28 22:53:01','2025-03-28 22:53:01');
INSERT INTO "secciones" VALUES (22,'B',2,'2025-03-28 22:53:08','2025-03-28 22:53:08');
INSERT INTO "secciones" VALUES (23,'B',3,'2025-03-28 22:53:34','2025-03-28 22:53:34');
INSERT INTO "secciones" VALUES (24,'B',4,'2025-03-28 22:53:50','2025-03-28 22:53:50');
INSERT INTO "secciones" VALUES (25,'B',5,'2025-03-28 22:53:59','2025-03-28 22:53:59');
INSERT INTO "secciones" VALUES (26,'B',6,'2025-03-28 22:54:07','2025-03-28 22:54:07');
INSERT INTO "secciones" VALUES (28,'B',1,'2025-03-28 23:19:51','2025-03-28 23:19:51');
INSERT INTO "secciones" VALUES (29,'A',1,'2025-03-28 23:26:08','2025-03-28 23:26:08');
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "sessions" (
  "id" varchar(255) NOT NULL,
  "user_id" bigint(20) unsigned DEFAULT NULL,
  "ip_address" varchar(45) DEFAULT NULL,
  "user_agent" text DEFAULT NULL,
  "payload" longtext NOT NULL,
  "last_activity" int(11) NOT NULL,
  PRIMARY KEY ("id"),
  KEY "sessions_user_id_index" ("user_id"),
  KEY "sessions_last_activity_index" ("last_activity")
);
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO "sessions" VALUES ('7xf7mE6o4XNPEVt1ZmPbXpfK3UNOBjKjxx7eUKhZ',4,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) laravel-desktop/1.0.0 Chrome/136.0.7103.113 Electron/36.3.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiU1NRd292Y0U4MTM2MjE2RG1taUNLT04wWWowTGVZWGFhcDNkNmhUSiI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NDtzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czozMToiaHR0cDovL2xvY2FsaG9zdDo4MDAwL2Rhc2hib2FyZCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1748486585);
INSERT INTO "sessions" VALUES ('aXZp3KMHHlRCEO55x8aEk1LjGw0KAut0YhXlTyi0',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36 Edg/136.0.0.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoidEhaQ0FJTHpJeklwbHBXU2FraDZWN3MzZ1pOSUVQWmlNcVBHbGNYbyI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czozMToiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2Rhc2hib2FyZCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1748528943);
INSERT INTO "sessions" VALUES ('Lzr9Cke99GOqB4aHAQkLcwRW24JCTt1PWx8ORN6f',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36 Edg/136.0.0.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiNnFyb3ZEWUNKMVBmcUM0Y0hhZFRBTEZWc0J1TFU5dVFKU0pLZXVoQiI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czozMToiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2Rhc2hib2FyZCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1748483730);
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "trimestres" (
  "id_trimestre" bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  "nombre" varchar(50) NOT NULL,
  "id_anio" bigint(20) unsigned NOT NULL,
  "fecha_inicio" date NOT NULL,
  "fecha_fin" date NOT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  PRIMARY KEY ("id_trimestre"),
  UNIQUE KEY "trimestres_nombre_id_anio_unique" ("nombre","id_anio"),
  KEY "trimestres_id_anio_foreign" ("id_anio"),
  CONSTRAINT "trimestres_id_anio_foreign" FOREIGN KEY ("id_anio") REFERENCES "anios_academicos" ("id_anio") ON DELETE CASCADE
);
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO "trimestres" VALUES (1,'1er Trimestre',2,'2025-03-15','2025-07-15','2025-04-02 07:35:28','2025-04-02 07:35:28');
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "users" (
  "id" bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  "name" varchar(255) NOT NULL,
  "email" varchar(255) NOT NULL,
  "email_verified_at" timestamp NULL DEFAULT NULL,
  "password" varchar(255) NOT NULL,
  "remember_token" varchar(100) DEFAULT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  PRIMARY KEY ("id"),
  UNIQUE KEY "users_email_unique" ("email")
);
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "usuarios" (
  "id_usuario" bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  "nombre" varchar(50) NOT NULL,
  "email" varchar(100) NOT NULL,
  "password" varchar(255) NOT NULL,
  "fecha_registro" date NOT NULL DEFAULT curdate(),
  "activo" tinyint(1) NOT NULL DEFAULT 1,
  "remember_token" varchar(100) DEFAULT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  PRIMARY KEY ("id_usuario"),
  UNIQUE KEY "usuarios_email_unique" ("email")
);
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO "usuarios" VALUES (1,'Will','callebaca@gmail.com','$2y$12$yM/3rPEGKMsR9nFI1oxzueuQR05W5Rt17M60laB5.qH.OMfk7dNj.','2025-03-27',1,'7fkHqOvE9L7pXl2TFwbMMMi4Osr5LrDJJn0XVPBXcK626fJqqWMpPPOE22Wv',NULL,'2025-04-10 22:45:21');
INSERT INTO "usuarios" VALUES (3,'ronaldo','1502974@senati.pe','$2y$12$R9WAt8kxCN4m5RUmfup0l.r/0r8QTEpa4lKti0yFyWmFERL4yqk/e','2025-04-10',1,NULL,'2025-04-10 22:59:18','2025-04-10 22:59:18');
INSERT INTO "usuarios" VALUES (4,'raccoon','raccoon@email.com','$2y$12$BgkTVUB03tS2hyqa/RZzHe1fTZT9hdZUyjLf204HCmcWhWs21rElm','2025-05-16',1,'bLIfobzuPA43pDjfl0stcr1rGF7kfpczsj5beypcyKmW0Ikrum9QWsiA22jI','2025-05-16 21:51:11','2025-05-16 21:51:11');
