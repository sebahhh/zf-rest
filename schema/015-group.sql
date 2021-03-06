/*
 * douggr/zf-rest
 *
 * @link https://github.com/douggr/zf-rest for the canonical source repository
 * @version 1.0.0
 *
 * For the full copyright and license information, please view the LICENSE
 * file distributed with this source code.
 */

/*!40101 SET NAMES utf8 */;
/*!40101 SET GLOBAL log_output = 'TABLE' */;
/*!40101 SET GLOBAL general_log = 'ON' */;

-- ---------------------------------------------------------------------------
-- Table structure for table `%DATABASE%`.`group`
-- ---------------------------------------------------------------------------
DROP TABLE IF EXISTS `%DATABASE%`.`group`;
CREATE TABLE IF NOT EXISTS `%DATABASE%`.`group` (
  `id`            INTEGER       NOT NULL AUTO_INCREMENT,

  `name`          VARCHAR(200)  NOT NULL,
  `summary`       TEXT          NULL,
  `active`        BOOLEAN       NOT NULL DEFAULT TRUE,
  `entity_id`     INTEGER       NOT NULL DEFAULT 1,
  `admin`         BOOLEAN       NOT NULL DEFAULT FALSE,

  `created_at`    TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by`    INTEGER       NOT NULL,
  `locale_id`     INTEGER       NOT NULL,
  PRIMARY KEY (`id`, `entity_id`, `locale_id`)
) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;

ALTER TABLE `%DATABASE%`.`group`
  ADD CONSTRAINT `group_fk_created_by`
  FOREIGN KEY (`created_by`) REFERENCES `user`(`id`);

ALTER TABLE `%DATABASE%`.`group`
  ADD CONSTRAINT `group_fk_locale`
  FOREIGN KEY (`locale_id`) REFERENCES `locale`(`id`);

ALTER TABLE `%DATABASE%`.`group`
  ADD CONSTRAINT `group_fk_entity`
  FOREIGN KEY (`entity_id`) REFERENCES `entity`(`id`);
