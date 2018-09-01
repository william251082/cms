<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180901134620 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user_preferences (id INT AUTO_INCREMENT NOT NULL, locale VARCHAR(8) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user ADD preferences_id INT DEFAULT NULL, CHANGE confirmation_token confirmation_token VARCHAR(30) DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6497CCD6FB7 FOREIGN KEY (preferences_id) REFERENCES user_preferences (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6497CCD6FB7 ON user (preferences_id)');
        $this->addSql('ALTER TABLE notification CHANGE user_id user_id INT DEFAULT NULL, CHANGE post_id post_id INT DEFAULT NULL, CHANGE liked_by_id liked_by_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6497CCD6FB7');
        $this->addSql('DROP TABLE user_preferences');
        $this->addSql('ALTER TABLE notification CHANGE user_id user_id INT DEFAULT NULL, CHANGE post_id post_id INT DEFAULT NULL, CHANGE liked_by_id liked_by_id INT DEFAULT NULL');
        $this->addSql('DROP INDEX UNIQ_8D93D6497CCD6FB7 ON user');
        $this->addSql('ALTER TABLE user DROP preferences_id, CHANGE confirmation_token confirmation_token VARCHAR(30) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
    }
}
