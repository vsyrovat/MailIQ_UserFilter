<?php declare(strict_types = 1);

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20180420223733 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql("CREATE TABLE `users_about` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user` int(10) UNSIGNED NOT NULL,
  `item` enum('country','firstname','state') COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `up_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");

        $this->addSql("ALTER TABLE `users_about`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user` (`user`),
  ADD KEY `user_item_value` (`user`,`item`,`value`) USING BTREE,
  ADD KEY `item` (`item`);");

        $this->addSql("ALTER TABLE `users_about`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;");

        $this->addSql("ALTER TABLE `users_about`
  ADD CONSTRAINT `users_about_ibfk_1` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;");
    }

    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql("ALTER TABLE users_about DROP FOREIGN KEY users_about_ibfk_1");
        $this->addSql("DROP TABLE users_about");
    }
}
